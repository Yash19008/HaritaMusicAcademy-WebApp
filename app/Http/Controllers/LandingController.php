<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Payment;
use App\Notifications\DemoBookedNotification;
use Illuminate\Support\Facades\Notification;

class LandingController extends Controller
{
    /**
     * Render the country-specific landing page.
     * $locale and $country are already shared via SetLocale middleware.
     */
    public function index(string $country): View
    {
        return view('landing.index');
    }

    /**
     * Store a demo booking (non-payment fallback, kept for backward compatibility).
     * The main payment flow uses PaymentController@createOrder + verifyPayment.
     */
    public function storeDemo(Request $request, string $country): RedirectResponse
    {
        $locale = config("locales.{$country}");

        $validated = $request->validate([
            'student_name'   => 'required|string|max:255',
            'email'          => 'required|email|max:255',
            'phone'          => 'required|string|max:20',
            'instrument'     => 'required|string|max:255',
            'preferred_date' => 'required|date|after_or_equal:today',
            'preferred_time' => 'required|string',
        ]);

        $payment = Payment::create([
            'student_name'    => $validated['student_name'],
            'email'           => $validated['email'],
            'phone'           => $validated['phone'],
            'instrument'      => $validated['instrument'],
            'amount'          => $locale['demo_price'],
            'currency'        => $locale['currency_code'],
            'payment_mode'    => 'Online',
            'transaction_date'=> today(),
            'status'          => 'pending', // Admins can manage these via the panel
            'preferred_date'  => Carbon::parse($validated['preferred_date'])->format('Y-m-d'),
            'preferred_time'  => $validated['preferred_time'],
        ]);

        try {
            $admins = User::where('role', 'admin')->get();
            Notification::send($admins, new DemoBookedNotification($payment));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Failed to send demo notification: " . $e->getMessage());
        }

        return back()->with('demo_success', true);
    }
}
