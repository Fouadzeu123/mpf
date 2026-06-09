<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatus;
use App\Models\PaymentTransaction;
use App\Services\CommunionService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function __construct(
        protected CommunionService $communionService,
    ) {}

    /**
     * Handle NotchPay webhook
     */
    public function notchpay(Request $request): Response
    {
        try {
            // Verify webhook signature
            if (!$this->verifyNotchPaySignature($request)) {
                Log::warning('NotchPay webhook signature invalid', [
                    'ip' => $request->ip(),
                    'reference' => $request->get('reference'),
                ]);
                return response('Unauthorized', 401);
            }

            $reference = $request->get('reference');
            $status = $request->get('status');

            if (!$reference) {
                return response('Missing reference', 400);
            }

            $transaction = PaymentTransaction::where('reference', $reference)->first();

            if (!$transaction) {
                Log::warning('NotchPay webhook received for unknown transaction', [
                    'reference' => $reference,
                ]);
                return response('Transaction not found', 404);
            }

            // Map NotchPay status to PaymentStatus
            $paymentStatus = match ($status) {
                'completed', 'complete' => PaymentStatus::Paid,
                'failed' => PaymentStatus::Failed,
                'cancelled' => PaymentStatus::Cancelled,
                default => PaymentStatus::Pending,
            };

            // Update transaction
            $transaction->update([
                'status' => $paymentStatus,
                'payload' => $request->all(),
            ]);

            // If payment completed, process the action
            if ($paymentStatus === PaymentStatus::Paid) {
                if ($transaction->type === 'event_contribution') {
                    \App\Models\Contribution::where('payment_reference', $reference)->update(['payment_status' => 'paid']);
                } else {
                    $this->communionService->prepare(
                        $transaction->member,
                        remote: true,
                        paymentReference: $reference
                    );
                }

                Log::info('Payment processed via webhook', [
                    'reference' => $reference,
                    'member_id' => $transaction->member_id,
                    'type' => $transaction->type,
                ]);
            }

            return response('Webhook received', 200);
        } catch (\Throwable $e) {
            Log::error('NotchPay webhook error: '.$e->getMessage(), [
                'exception' => $e,
            ]);
            return response('Webhook processing failed', 500);
        }
    }

    /**
     * Verify NotchPay webhook signature
     */
    protected function verifyNotchPaySignature(Request $request): bool
    {
        $signature = $request->header('X-Notchpay-Signature');

        if (!$signature) {
            return false;
        }

        $privateKey = config('services.notchpay.private_key');

        if (!$privateKey) {
            return false;
        }

        // Get raw request body
        $payload = $request->getContent();

        // Compute HMAC SHA256
        $computed = hash_hmac(
            'sha256',
            $payload,
            $privateKey,
            false
        );

        // Compare signatures using timing-safe comparison
        return hash_equals($computed, $signature);
    }
}
