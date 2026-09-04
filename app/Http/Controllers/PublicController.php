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

        \App\Models\Payment::create([
            'student_name' => $validated['student_name'],
            'email'        => $validated['email'],
            'phone'        => $validated['phone'],
            'instrument'   => $validated['instrument'],
            'amount'       => 499.00,
            'payment_mode' => 'Online',
            'transaction_date' => today(),
            'status'       => 'pending',
            'preferred_date' => $startsAt->format('Y-m-d'),
            'preferred_time' => $startsAt->format('H:i:s'),
        ]);

        return back()->with('demo_success', true);
    }
}
