<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Opportunity;
use App\Models\OpportunityRejection;
use App\Models\Teacher;
use App\Models\ClassBooking;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class OpportunityController extends Controller
{
    public function current(): JsonResponse
    {
        $teacher = Teacher::where('user_id', auth()->id())->first();
        if (!$teacher) {
            return response()->json(null);
        }

        // Find active opportunity that the teacher hasn't rejected
        $opportunity = Opportunity::with('booking')
            ->where('status', 'pending')
            ->where('active_at', '<=', now())
            ->where('expires_at', '>=', now())
            ->where('original_teacher_id', '!=', $teacher->id)
            ->whereDoesntHave('rejections', function($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id);
            })
            ->orderBy('active_at', 'asc')
            ->first();

        if ($opportunity) {
            $booking = $opportunity->booking;
            if (!$booking) return response()->json(null);

            // Check if this teacher has an overlapping class
            // To be completely safe, we just check if they have a scheduled class whose start or end overlaps
            $hasClash = ClassBooking::where('teacher_id', $teacher->id)
                ->where('status', 'scheduled')
                ->where(function($query) use ($booking) {
                    $query->where(function($q) use ($booking) {
                        $q->where('starts_at', '<', $booking->ends_at)
                          ->where('ends_at', '>', $booking->starts_at);
                    });
                })->exists();

            if (!$hasClash) {
                return response()->json([
                    'id' => $opportunity->id,
                    'subject' => $booking->instrument ?? 'Class',
                    'date' => $booking->starts_at->format('l, M d, Y'),
                    'time' => $booking->starts_at->format('h:i A') . ' - ' . $booking->ends_at->format('h:i A'),
                    'bonus' => Setting::get('opportunity_bonus_rs', 100),
                    'expires_at' => $opportunity->expires_at->toIso8601String(),
                ]);
            }
        }

        return response()->json(null);
    }

    public function accept($id): JsonResponse
    {
        $teacher = Teacher::where('user_id', auth()->id())->first();
        if (!$teacher) {
            return response()->json(['success' => false, 'message' => 'Not a teacher.']);
        }

        // Atomic update to prevent race conditions
        $updated = Opportunity::where('id', $id)
            ->where('status', 'pending')
            ->where('expires_at', '>=', now())
            ->update([
                'status' => 'accepted',
                'accepted_teacher_id' => $teacher->id
            ]);

        if ($updated) {
            $opportunity = Opportunity::find($id);
            $booking = ClassBooking::find($opportunity->class_booking_id);
            
            if ($booking) {
                $booking->update(['teacher_id' => $teacher->id]);

                // Update Google Calendar
                try {
                    app(\App\Services\GoogleCalendarService::class)->updateEvent($booking);
                } catch (\Exception $e) {
                    Log::error('Google Calendar Update Failed for Opportunity: ' . $e->getMessage());
                }
            }

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Too late! Opportunity has already been accepted or expired.']);
    }

    public function reject($id): JsonResponse
    {
        $teacher = Teacher::where('user_id', auth()->id())->first();
        if ($teacher) {
            OpportunityRejection::firstOrCreate([
                'opportunity_id' => $id,
                'teacher_id' => $teacher->id
            ]);
        }
        return response()->json(['success' => true]);
    }
}
