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

class PaymentController extends Controller
{
    private const DEMO_AMOUNT_INR = 499;  // ₹499 — single source of truth, never from client

    /**
     * Step 1 — Create a Razorpay order server-side.
     * Called via AJAX when the user clicks "Pay ₹499 & Book Demo".
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
        ]);

        try {
            $key    = config('services.razorpay.key');
            $secret = config('services.razorpay.secret');

            if (empty($key) || empty($secret)) {
                return response()->json(['success' => false, 'message' => 'Payment gateway is not configured. Please contact support.'], 500);
            }

            $api = new Api($key, $secret);

            // Amount is always set server-side — never trust client
            $amountPaise = self::DEMO_AMOUNT_INR * 100; // ₹499 → 49900 paise

            $razorpayOrder = $api->order->create([
                'receipt'          => 'harita-demo-' . time(),
                'amount'           => $amountPaise,
                'currency'         => 'INR',
                'payment_capture'  => 1,  // auto-capture on payment
                'notes'            => [
                    'name'       => $validated['student_name'],
                    'instrument' => $validated['instrument'],
                ],
            ]);

            // Store a pending payment record immediately (before payment)
            $payment = Payment::create([
                'student_name'   => $validated['student_name'],
                'email'          => $validated['email'],
                'phone'          => $validated['phone'],
                'instrument'     => $validated['instrument'],
                'preferred_date' => $validated['preferred_date'],
                'preferred_time' => $validated['preferred_time'],
                'amount'         => self::DEMO_AMOUNT_INR,
                'payment_mode'   => 'Online',
                'transaction_date' => today(),
                'status'         => 'pending',
                'razorpay_order_id' => $razorpayOrder['id'],
            ]);

            return response()->json([
                'success'    => true,
                'order_id'   => $razorpayOrder['id'],
                'amount'     => $amountPaise,
                'key'        => $key,
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

            // Double-check the amount from Razorpay API to prevent amount tampering
            $rzpPayment = $api->payment->fetch($validated['razorpay_payment_id']);
            $paidAmountPaise = (int) $rzpPayment['amount'];

            if ($paidAmountPaise !== self::DEMO_AMOUNT_INR * 100) {
                Log::warning("Razorpay amount mismatch! Expected: " . (self::DEMO_AMOUNT_INR * 100) . ", Got: {$paidAmountPaise}");
                return response()->json(['success' => false, 'message' => 'Payment amount mismatch. Please contact support.'], 400);
            }

            $payment->update([
                'razorpay_payment_id' => $validated['razorpay_payment_id'],
                'status'              => 'confirmed',
            ]);

            Log::info("Demo payment confirmed: Payment #{$payment->id}, Razorpay: {$validated['razorpay_payment_id']}");

            // Send confirmation email
            try {
                Mail::to($payment->email)->send(new PaymentConfirmationMail($payment));
            } catch (Throwable $e) {
                Log::error("Failed to send payment confirmation email for Payment #{$payment->id}: " . $e->getMessage());
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

                    // Send confirmation email
                    try {
                        Mail::to($payment->email)->send(new PaymentConfirmationMail($payment));
                    } catch (Throwable $e) {
                        Log::error("Failed to send payment confirmation email via webhook for Payment #{$payment->id}: " . $e->getMessage());
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
