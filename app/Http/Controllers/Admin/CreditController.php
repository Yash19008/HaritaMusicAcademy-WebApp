<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\CreditTransaction;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CreditController extends Controller
{
    public function index(): View
    {
        $students     = Student::with('course:id,name')->select('id', 'name', 'course_id', 'credits')->get();
        $transactions = CreditTransaction::with('student:id,name')->latest()->limit(100)->get();
        return view('admin.credits.index', compact('students', 'transactions'));
    }

    public function adjust(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'quantity'   => ['required', 'integer'],
            'reason'     => ['nullable', 'string'],
        ]);

        $student = Student::findOrFail($data['student_id']);
        $student->increment('credits', $data['quantity']);

        CreditTransaction::create([
            'student_id' => $student->id,
            'action'     => $data['quantity'] > 0 ? 'Added' : 'Deducted',
            'quantity'   => $data['quantity'],
            'reason'     => $data['reason'] ?? 'Manual adjustment',
        ]);

        return back()->with('success', 'Credits adjusted.');
    }
}
