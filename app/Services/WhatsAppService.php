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

        $message = "Bonjour {$member->first_name},\n\n"
            ."Votre préparation pour la Sainte Cène a bien été enregistrée.\n\n"
            ."Voici votre verset biblique du jour :\n\n"
            ."📖 {$reference}\n"
            ."« {$text} »\n\n"
            ."Que le Seigneur vous bénisse abondamment !";

        return $this->dispatchMessage($member->phone, $message);
    }

    public function sendAttendanceVerse(Member $member, string $reference, string $text, ?string $time = null): bool
    {
        if (! $member->phone) {
            return false;
        }

        $timeStr = $time ? " à {$time}" : "";
        $message = "Bonjour {$member->first_name},\n\n"
            ."Votre présence au culte a bien été enregistrée{$timeStr}.\n\n"
            ."Voici votre verset biblique du jour :\n\n"
            ."📖 {$reference}\n"
            ."« {$text} »\n\n"
            ."Que le Seigneur vous bénisse abondamment !";

        return $this->dispatchMessage($member->phone, $message);
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

        return $this->dispatchMessage($phone, $message);
    }

    protected function dispatchMessage(string $phone, string $message): bool
    {
        return match (config('services.whatsapp.driver')) {
            'infobip' => $this->sendViaInfobip($phone, $message),
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

    protected function sendViaInfobip(string $phone, string $message): bool
    {
        $baseUrl = config('services.whatsapp.infobip_base_url');
        $apiKey = config('services.whatsapp.infobip_api_key');
        $from = config('services.whatsapp.infobip_sender');

        if (! $baseUrl || ! $apiKey || ! $from) {
            Log::warning('Infobip WhatsApp: Configuration incomplète. Veuillez définir INFOBIP_BASE_URL, INFOBIP_API_KEY et INFOBIP_WHATSAPP_SENDER dans votre fichier .env. Le message est consigné dans les logs.', [
                'has_base_url' => ! empty($baseUrl),
                'has_api_key' => ! empty($apiKey),
                'has_sender' => ! empty($from),
            ]);

            return $this->logMessage($phone, $message);
        }

        $baseUrl = rtrim($baseUrl, '/');
        if (! str_starts_with($baseUrl, 'http://') && ! str_starts_with($baseUrl, 'https://')) {
            $baseUrl = 'https://' . $baseUrl;
        }

        $endpoint = "{$baseUrl}/whatsapp/1/message/text";
        $recipient = $this->formatPhone($phone);
        $cleanFrom = preg_replace('/\D/', '', $from);

        try {
            $response = Http::withOptions(['verify' => false])
                ->withHeaders([
                    'Authorization' => 'App ' . $apiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])->timeout(15)->post($endpoint, [
                    'from' => $cleanFrom,
                    'to' => $recipient,
                    'content' => [
                        'text' => $message,
                    ],
                ]);

            if ($response->successful()) {
                Log::info('Infobip WhatsApp envoyé avec succès', [
                    'to' => $recipient,
                    'status' => $response->status(),
                    'data' => $response->json(),
                ]);

                return true;
            }

            Log::error('Infobip WhatsApp Erreur API', [
                'status' => $response->status(),
                'to' => $recipient,
                'response' => $response->body(),
            ]);

            return false;
        } catch (\Throwable $e) {
            Log::error('Infobip WhatsApp Exception: ' . $e->getMessage(), [
                'to' => $recipient,
            ]);

            return false;
        }
    }

    public function sendTemplateViaInfobip(string $phone, string $templateName, array $placeholders = [], string $language = 'en'): bool
    {
        $baseUrl = config('services.whatsapp.infobip_base_url');
        $apiKey = config('services.whatsapp.infobip_api_key');
        $from = config('services.whatsapp.infobip_sender');

        if (! $baseUrl || ! $apiKey || ! $from) {
            return false;
        }

        $baseUrl = rtrim($baseUrl, '/');
        if (! str_starts_with($baseUrl, 'http://') && ! str_starts_with($baseUrl, 'https://')) {
            $baseUrl = 'https://' . $baseUrl;
        }

        $endpoint = "{$baseUrl}/whatsapp/1/message/template";
        $recipient = $this->formatPhone($phone);
        $cleanFrom = preg_replace('/\D/', '', $from);

        try {
            $response = Http::withOptions(['verify' => false])
                ->withHeaders([
                    'Authorization' => 'App ' . $apiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])->timeout(15)->post($endpoint, [
                    'messages' => [
                        [
                            'from' => $cleanFrom,
                            'to' => $recipient,
                            'content' => [
                                'templateName' => $templateName,
                                'templateData' => [
                                    'body' => [
                                        'placeholders' => $placeholders,
                                    ],
                                ],
                                'language' => $language,
                            ],
                        ],
                    ],
                ]);

            return $response->successful();
        } catch (\Throwable $e) {
            Log::error('Infobip WhatsApp Template Exception: ' . $e->getMessage(), ['to' => $recipient]);
            return false;
        }
    }

    protected function sendViaTwilio(string $phone, string $message): bool
    {
        $sid = config('services.whatsapp.twilio_sid');
        $token = config('services.whatsapp.twilio_token');
        $from = config('services.whatsapp.twilio_from') ?: 'whatsapp:+237691051864';

        if (! str_starts_with($from, 'whatsapp:')) {
            $from = 'whatsapp:'.$from;
        }

        if (! $sid || ! $token) {
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
