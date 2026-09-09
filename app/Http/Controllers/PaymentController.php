<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
use Throwable;
use App\Mail\PaymentConfirmationMail;
use App\Models\User;
use App\Notifications\DemoBookedNotification;
use Illuminate\Support\Facades\Notification;

class PaymentController extends Controller
{
    /** Valid country slugs — must match config/locales.php keys */
    private const SUPPORTED_COUNTRIES = ['in', 'us', 'uk', 'cad', 'uae'];

    /**
     * Resolve demo price and currency from locale config.
     * Country is supplied by the client but the price/currency are always
     * read server-side from config — the client cannot manipulate them.
     */
    private function resolveLocale(string $country): array
    {
        if (!in_array($country, self::SUPPORTED_COUNTRIES, true)) {
            $country = 'in'; // fallback
        }
        return config("locales.{$country}");
    }

    /**
     * Step 1 — Create a Razorpay order server-side.
     * Called via AJAX when the user clicks "Pay & Book Demo".
     */
    public function createOrder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'student_name'   => 'required|string|max:255',
            'email'          => 'required|email|max:255',
            'phone'          => 'required|string|max:20',
            'instrument'     => 'required|string|max:255',
            'preferred_date' => 'required|date|after_or_equal:today',
            'preferred_time' => 'required|string',
            'country'        => 'nullable|string|in:in,us,uk,cad,uae',
        ]);

        try {
            $key    = config('services.razorpay.key');
            $secret = config('services.razorpay.secret');

            if (empty($key) || empty($secret)) {
                return response()->json(['success' => false, 'message' => 'Payment gateway is not configured. Please contact support.'], 500);
            }

            // Resolve locale server-side — price/currency are never from the client
            $locale       = $this->resolveLocale($validated['country'] ?? 'in');
            $demoAmount   = $locale['demo_price'];   // e.g. 499, 15, 12
            $currency     = $locale['currency_code']; // e.g. INR, USD, GBP
            // Razorpay requires amount in smallest unit (paise/cents/pence)
            $amountSmall  = $demoAmount * 100;

            $api = new Api($key, $secret);

            $razorpayOrder = $api->order->create([
                'receipt'         => 'harita-demo-' . time(),
                'amount'          => $amountSmall,
                'currency'        => $currency,
                'payment_capture' => 1, // auto-capture
                'notes'           => [
                    'name'       => $validated['student_name'],
                    'instrument' => $validated['instrument'],
                    'country'    => $validated['country'] ?? 'in',
                ],
            ]);

            // Store a pending payment record (before user pays)
            $payment = Payment::create([
                'student_name'      => $validated['student_name'],
                'email'             => $validated['email'],
                'phone'             => $validated['phone'],
                'instrument'        => $validated['instrument'],
                'preferred_date'    => $validated['preferred_date'],
                'preferred_time'    => $validated['preferred_time'],
                'amount'            => $demoAmount,
                'payment_mode'      => 'Online',
                'transaction_date'  => today(),
                'status'            => 'pending',
                'razorpay_order_id' => $razorpayOrder['id'],
            ]);

            return response()->json([
                'success'       => true,
                'order_id'      => $razorpayOrder['id'],
                'amount'        => $amountSmall,
                'currency'      => $currency,
                'key'           => $key,
                'payment_db_id' => $payment->id,
            ]);

        } catch (Throwable $e) {
            Log::error('Razorpay createOrder failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Could not initialize payment. Please try again.'], 500);
        }
    }

    /**
     * Step 2 — Verify Razorpay payment signature (HMAC-SHA256).
     * This is the critical security check — runs server-side, not trusting client.
     */
    public function verifyPayment(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'razorpay_order_id'   => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature'  => 'required|string',
            'payment_db_id'       => 'required|integer|exists:payments,id',
        ]);

        try {
            $key    = config('services.razorpay.key');
            $secret = config('services.razorpay.secret');

            if (empty($key) || empty($secret)) {
                return response()->json(['success' => false, 'message' => 'Payment gateway is not configured.'], 500);
            }

            $api = new Api($key, $secret);

            // Cryptographic signature verification — this is the real security gate
            $api->utility->verifyPaymentSignature([
                'razorpay_order_id'   => $validated['razorpay_order_id'],
                'razorpay_payment_id' => $validated['razorpay_payment_id'],
                'razorpay_signature'  => $validated['razorpay_signature'],
            ]);

            // Find OUR payment record and make sure amounts haven't been tampered
            $payment = Payment::where('id', $validated['payment_db_id'])
                ->where('razorpay_order_id', $validated['razorpay_order_id'])
                ->where('status', 'pending')  // only confirm if still pending
                ->firstOrFail();

            // Double-check the amount from Razorpay API to prevent amount tampering.
            // Compare against the amount stored in OUR database (set server-side on createOrder).
            $rzpPayment      = $api->payment->fetch($validated['razorpay_payment_id']);
            $paidAmountSmall = (int) $rzpPayment['amount']; // smallest unit (paise/cents/pence)
            $expectedSmall   = (int) round($payment->amount * 100);

            if ($paidAmountSmall !== $expectedSmall) {
                Log::warning("Razorpay amount mismatch! Expected: {$expectedSmall}, Got: {$paidAmountSmall} for Payment #{$payment->id}");
                return response()->json(['success' => false, 'message' => 'Payment amount mismatch. Please contact support.'], 400);
            }

            $payment->update([
                'razorpay_payment_id' => $validated['razorpay_payment_id'],
                'status'              => 'confirmed',
            ]);

            Log::info("Demo payment confirmed: Payment #{$payment->id}, Razorpay: {$validated['razorpay_payment_id']}");

            // Send confirmation email and admin notification
            try {
                Mail::to($payment->email)->send(new PaymentConfirmationMail($payment));
                $admins = User::where('role', 'admin')->get();
                Notification::send($admins, new DemoBookedNotification($payment));
            } catch (Throwable $e) {
                Log::error("Failed to send payment confirmation email/notification for Payment #{$payment->id}: " . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Payment verified successfully! We will contact you shortly to confirm your demo slot.',
            ]);

        } catch (SignatureVerificationError $e) {
            Log::error('Razorpay signature mismatch for order: ' . ($validated['razorpay_order_id'] ?? 'unknown'));

            // Mark as failed if signature is invalid
            Payment::where('id', $validated['payment_db_id'] ?? 0)
                ->where('status', 'pending')
                ->update(['status' => 'failed']);

            return response()->json(['success' => false, 'message' => 'Payment verification failed. If money was deducted, please contact support.'], 400);

        } catch (Throwable $e) {
            Log::error('Razorpay verifyPayment error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Verification error. Please contact support.'], 500);
        }
    }

    /**
     * Step 3 (Async) — Webhook for delayed or async payment confirmations.
     * Razorpay calls this directly — no CSRF, but we verify webhook signature instead.
     */
    public function webhook(Request $request): JsonResponse
    {
        $webhookSecret    = config('services.razorpay.webhook_secret');
        $webhookSignature = $request->header('X-Razorpay-Signature');
        $payload          = $request->getContent();

        if (empty($webhookSecret)) {
            Log::warning('Razorpay webhook called but webhook_secret is not configured.');
            return response()->json(['status' => 'ignored'], 200); // return 200 so Razorpay doesn't retry
        }

        try {
            $key    = config('services.razorpay.key');
            $secret = config('services.razorpay.secret');
            $api    = new Api($key, $secret);

            // Verify webhook signature
            $api->utility->verifyWebhookSignature($payload, $webhookSignature, $webhookSecret);

            $data  = json_decode($payload, true);
            $event = $data['event'] ?? null;

            if ($event === 'payment.captured' || $event === 'payment.authorized') {
                $entity  = $data['payload']['payment']['entity'];
                $orderId = $entity['order_id'];

                $payment = Payment::where('razorpay_order_id', $orderId)->first();
                if ($payment && $payment->status !== 'confirmed') {
                    $payment->update([
                        'razorpay_payment_id' => $entity['id'],
                        'status'              => 'confirmed',
                    ]);
                    Log::info("Webhook confirmed payment for order: {$orderId}");

                    // Send confirmation email and admin notification
                    try {
                        Mail::to($payment->email)->send(new PaymentConfirmationMail($payment));
                        $admins = User::where('role', 'admin')->get();
                        Notification::send($admins, new DemoBookedNotification($payment));
                    } catch (Throwable $e) {
                        Log::error("Failed to send payment confirmation email/notification via webhook for Payment #{$payment->id}: " . $e->getMessage());
                    }
                }

            } elseif ($event === 'payment.failed') {
                $entity  = $data['payload']['payment']['entity'];
                $orderId = $entity['order_id'];

                $payment = Payment::where('razorpay_order_id', $orderId)->first();
                if ($payment && $payment->status === 'pending') {
                    $payment->update(['status' => 'failed']);
                    Log::info("Webhook marked payment as failed for order: {$orderId}");
                }
            }

            return response()->json(['status' => 'ok']);

        } catch (Throwable $e) {
            Log::error('Razorpay webhook error: ' . $e->getMessage());
            return response()->json(['error' => 'Webhook processing failed'], 400);
        }
    }
}
