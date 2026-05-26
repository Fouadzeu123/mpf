<?php

namespace App\Services;

use App\Enums\PaymentStatus;
use App\Models\Member;
use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use NotchPay\NotchPay;
use NotchPay\Payment;

class NotchPayService
{
    public function __construct()
    {
        $publicKey = config('services.notchpay.public_key');

        if ($publicKey) {
            NotchPay::setApiKey($publicKey);
        }
    }

    public function initiate(Member $member, int $amount, string $description): PaymentTransaction
    {
        $reference = 'MPF-'.Str::upper(Str::random(12));

        $transaction = PaymentTransaction::create([
            'member_id' => $member->id,
            'reference' => $reference,
            'amount' => $amount,
            'currency' => 'XAF',
            'status' => PaymentStatus::Pending,
        ]);

        $publicKey = config('services.notchpay.public_key');

        if (! $publicKey) {
            return $transaction;
        }

        try {
            $result = Payment::initialize([
                'amount' => $amount,
                'currency' => 'XAF',
                'reference' => $reference,
                'description' => $description,
                'email' => $member->member_code.'@mpf.local',
                'phone' => $member->phone,
                'callback' => config('services.notchpay.callback_url') ?? route('payments.callback'),
            ]);

            $resultData = is_object($result) ? (array) $result : $result;

            $transaction->update([
                'notchpay_id' => $resultData['transaction']['reference'] ?? $resultData['reference'] ?? null,
                'payload' => $resultData,
            ]);
        } catch (\Throwable $e) {
            Log::error('NotchPay initiate error: '.$e->getMessage());
            $transaction->update(['status' => PaymentStatus::Failed]);
        }

        return $transaction;
    }

    public function verify(string $reference): PaymentTransaction
    {
        $transaction = PaymentTransaction::where('reference', $reference)->firstOrFail();

        $publicKey = config('services.notchpay.public_key');

        if (! $publicKey) {
            return $transaction;
        }

        try {
            $result = Payment::verify($reference);

            $resultData = is_object($result) ? (array) $result : $result;
            $status = $resultData['transaction']['status'] ?? $resultData['status'] ?? null;

            $transaction->update([
                'status' => $status === 'complete' || $status === 'completed'
                    ? PaymentStatus::Paid
                    : PaymentStatus::Pending,
                'payload' => $resultData,
            ]);
        } catch (\Throwable $e) {
            Log::warning('NotchPay verify error: '.$e->getMessage());
        }

        return $transaction->fresh();
    }
}
