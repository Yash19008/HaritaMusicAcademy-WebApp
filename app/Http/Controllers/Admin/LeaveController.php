<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeacherLeave;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeaveController extends Controller
{
    public function index(): View
    {
        $leaves = TeacherLeave::with('teacher')->latest()->get();
        return view('admin.leaves.index', compact('leaves'));
    }

    public function approve(TeacherLeave $teacherLeave): RedirectResponse
    {
        $teacherLeave->update(['status' => 'approved']);
        return back()->with('success', 'Leave approved.');
    }

    public function reject(TeacherLeave $teacherLeave): RedirectResponse
    {
        $teacherLeave->update(['status' => 'rejected']);
        return back()->with('success', 'Leave rejected.');
    }
}
