<?php

namespace App\Http\Controllers;

use App\Models\ClassBooking;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class AttendanceController extends Controller
{
    /**
     * Record attendance via a role-specific join token and redirect to Google Meet.
     *
     * Security model:
     *   - `type=teacher` => looks up by teacher_join_token  => ONLY marks teacher_attended
     *   - `type=student` => looks up by student_join_token  => ONLY marks student_attended
     *   - No cross-role possible — tokens are separate columns, separate UUIDs.
     *   - Also verifies the authenticated user is the correct teacher/student.
     *
     * 15-minute rule:
     *   - Join is only recorded if class starts within 15 min from now (or has started).
     *   - If too early, still redirect but do not mark attendance.
     */
    public function join(Request $request, string $type, string $token): RedirectResponse
    {
        abort_unless(in_array($type, ['teacher', 'student'], true), 404);

        $tokenColumn = $type === 'teacher' ? 'teacher_join_token' : 'student_join_token';
        
        // Find booking by the role-specific token column
        $booking = ClassBooking::where($tokenColumn, $token)->first();
        $isDemo = false;

        if (!$booking) {
            $booking = \App\Models\DemoBooking::where($tokenColumn, $token)->firstOrFail();
            $isDemo = true;
        }

        $meetLink = $booking->google_meet_link ?? 'https://meet.google.com';
        $user = auth()->user();
        $now = now();

        // --- Authorization: verify the user is the right person ---
        if ($type === 'teacher') {
            if (!$user) {
                return redirect()->guest(route('login'));
            }
            if (!$user->teacher || $user->teacher->id !== $booking->teacher_id) {
                abort(403, 'You are not the teacher for this class.');
            }
        } else {
            // student
            if ($isDemo) {
                // For demo classes, we allow the join via token directly,
                // but if they are logged in as a student AND the demo has a converted_student_id, we can validate.
                // Otherwise, the token itself is sufficient authorization for a guest prospect.
                if ($user && $user->student && $booking->converted_student_id) {
                    if ($user->student->id !== $booking->converted_student_id) {
                        abort(403, 'You are not the assigned student for this demo class.');
                    }
                }
            } else {
                // Regular class — check direct student or group member
                if (!$user) {
                    return redirect()->guest(route('login'));
                }
                
                $isStudent = $user->student && $user->student->id === $booking->student_id;
                $isGroupMember = false;
                if (!$isStudent && $booking->student_group_id && $user->student) {
                    $isGroupMember = $user->student->groups()
                        ->where('student_group_id', $booking->student_group_id)
                        ->exists();
                }
                if (!$isStudent && !$isGroupMember) {
                    abort(403, 'You are not a participant in this class.');
                }
            }
        }

        // --- 15-minute window: only record attendance if class starts within 15 min ---
        $startsAt = $isDemo ? $booking->scheduled_at : $booking->starts_at;
        $endsAt = $isDemo ? $booking->scheduled_at->copy()->addMinutes($booking->duration_minutes ?? 40) : ($booking->ends_at ?? $booking->starts_at->copy()->addMinutes($booking->duration_minutes ?? 40));
        
        $minutesUntilClass = $now->diffInMinutes($startsAt, false); // negative if class already started
        $classEnded = $now->isAfter($endsAt);

        // Window: 15 min before start to end of class
        $withinWindow = $minutesUntilClass <= 15 && !$classEnded;

        if ($withinWindow) {
            if ($type === 'teacher' && $booking->teacher_attended === null) {
                $booking->teacher_attended = true;
                $booking->save();
            } elseif ($type === 'student' && $booking->student_attended === null) {
                $booking->student_attended = true;
                $booking->save();
            }
            // Idempotent: if already marked, don't flip back
        }

        return redirect()->away($meetLink);
    }
}
