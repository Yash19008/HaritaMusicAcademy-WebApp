<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DemoBooking;
use Carbon\Carbon;

class PublicController extends Controller
{
    public function storeDemo(Request $request)
    {
        $validated = $request->validate([
            'student_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'instrument' => 'required|string|max:255',
            'scheduled_at' => 'required|date',
        ]);

        $startsAt = Carbon::parse($validated['scheduled_at']);

        // Create a lead in the Sales Dashboard (Payment table)
        // Format: email|phone so admin can use it when booking demo or converting
        \App\Models\Payment::create([
            'student_name' => $validated['student_name'],
            'contact' => $validated['email'] . '|' . $validated['phone'], // Format: email|phone
            'instrument' => $validated['instrument'],
            'amount' => 499.00,
            'payment_mode' => 'Online',
            'transaction_date' => today(),
            'status' => 'pending', // Changed from 'confirmed' to 'pending' so it shows as "Inquiry"
        ]);

        return back()->with('demo_success', true);
    }
}
