<?php

namespace App\Services;

use App\Models\Member;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    public function sendCommunionVerse(Member $member, string $reference, string $text): bool
    {
        if (! $member->phone) {
            return false;
        }

        $message = $this->buildMessage($member->first_name, $reference, $text);

        return match (config('services.whatsapp.driver')) {
            'twilio' => $this->sendViaTwilio($member->phone, $message),
            'business' => $this->sendViaBusinessApi($member->phone, $message),
            default => $this->logMessage($member->phone, $message),
        };
    }

    /**
     * Send a generic Bible verse
     */
    public function sendVerse(string $phone, string $reference, string $text): bool
    {
        if (! $phone) {
            return false;
        }

        $message = "Votre verset du jour :\n\n"
            ."{$reference}\n"
            ."{$text}\n\n"
            .'Que Dieu vous bénisse.';

        return match (config('services.whatsapp.driver')) {
            'twilio' => $this->sendViaTwilio($phone, $message),
            'business' => $this->sendViaBusinessApi($phone, $message),
            default => $this->logMessage($phone, $message),
        };
    }

    protected function buildMessage(string $firstName, string $reference, string $text): string
    {
        return "Bonjour {$firstName},\n\n"
            ."Votre préparation pour la Sainte Cène a été enregistrée.\n\n"
            ."Voici votre verset du jour :\n\n"
            ."{$reference}\n"
            ."{$text}\n\n"
            .'Que Dieu vous bénisse.';
    }

    protected function sendViaTwilio(string $phone, string $message): bool
    {
        $sid = config('services.whatsapp.twilio_sid');
        $token = config('services.whatsapp.twilio_token');
        $from = config('services.whatsapp.twilio_from');

        if (! $sid || ! $token || ! $from) {
            return $this->logMessage($phone, $message);
        }

        try {
            $response = Http::withBasicAuth($sid, $token)
                ->asForm()
                ->post("https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json", [
                    'From' => $from,
                    'To' => 'whatsapp:'.$this->formatPhone($phone),
                    'Body' => $message,
                ]);

            return $response->successful();
        } catch (\Throwable $e) {
            Log::error('Twilio WhatsApp: '.$e->getMessage());

            return false;
        }
    }

    protected function sendViaBusinessApi(string $phone, string $message): bool
    {
        $token = config('services.whatsapp.business_token');
        $phoneId = config('services.whatsapp.business_phone_id');

        if (! $token || ! $phoneId) {
            return $this->logMessage($phone, $message);
        }

        try {
            $response = Http::withToken($token)
                ->post("https://graph.facebook.com/v18.0/{$phoneId}/messages", [
                    'messaging_product' => 'whatsapp',
                    'to' => $this->formatPhone($phone),
                    'type' => 'text',
                    'text' => ['body' => $message],
                ]);

            return $response->successful();
        } catch (\Throwable $e) {
            Log::error('WhatsApp Business: '.$e->getMessage());

            return false;
        }
    }

    protected function logMessage(string $phone, string $message): bool
    {
        Log::info('WhatsApp (simulation)', ['phone' => $phone, 'message' => $message]);

        return true;
    }

    protected function formatPhone(string $phone): string
    {
        $digits = preg_replace('/\D/', '', $phone);

        return str_starts_with($digits, '237') ? $digits : '237'.$digits;
    }
}
