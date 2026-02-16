<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send SMS via MSG91 API
     */
    public static function sendSms(string $mobile, string $message, array $variables = []): bool
    {
        $authKey = config('services.msg91.auth_key');
        $senderId = config('services.msg91.sender_id', 'SETUSV');
        $templateId = config('services.msg91.template_id');

        if (!$authKey || $authKey === 'your-msg91-auth-key') {
            Log::warning("SMS not sent — MSG91 auth key not configured. Mobile: {$mobile}, Message: {$message}");
            return false;
        }

        try {
            $response = Http::withHeaders([
                'authkey' => $authKey,
                'Content-Type' => 'application/json',
            ])->post('https://control.msg91.com/api/v5/flow/', [
                'template_id' => $templateId,
                'sender' => $senderId,
                'short_url' => '0',
                'mobiles' => '91' . $mobile,
                'VAR1' => $variables['VAR1'] ?? $message,
                'VAR2' => $variables['VAR2'] ?? '',
            ]);

            if ($response->successful()) {
                Log::info("SMS sent to {$mobile}: {$message}");
                return true;
            }

            Log::error("SMS failed to {$mobile}: " . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error("SMS exception for {$mobile}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send WhatsApp message via Meta Cloud API
     */
    public static function sendWhatsApp(string $mobile, string $templateName, array $parameters = []): bool
    {
        $token = config('services.whatsapp.token');
        $phoneId = config('services.whatsapp.phone_id');

        if (!$token || $token === 'your-whatsapp-business-api-token') {
            Log::warning("WhatsApp not sent — API token not configured. Mobile: {$mobile}, Template: {$templateName}");
            return false;
        }

        try {
            $components = [];
            if (!empty($parameters)) {
                $params = array_map(fn($p) => ['type' => 'text', 'text' => $p], $parameters);
                $components[] = ['type' => 'body', 'parameters' => $params];
            }

            $response = Http::withToken($token)->post(
                "https://graph.facebook.com/v18.0/{$phoneId}/messages",
                [
                    'messaging_product' => 'whatsapp',
                    'to' => '91' . $mobile,
                    'type' => 'template',
                    'template' => [
                        'name' => $templateName,
                        'language' => ['code' => 'mr'],
                        'components' => $components,
                    ],
                ]
            );

            if ($response->successful()) {
                Log::info("WhatsApp sent to {$mobile}: template={$templateName}");
                return true;
            }

            Log::error("WhatsApp failed to {$mobile}: " . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error("WhatsApp exception for {$mobile}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send notification via both SMS and WhatsApp
     */
    public static function notify(string $mobile, string $smsMessage, string $whatsappTemplate = '', array $params = []): void
    {
        self::sendSms($mobile, $smsMessage, $params);

        if ($whatsappTemplate) {
            self::sendWhatsApp($mobile, $whatsappTemplate, $params);
        }
    }
}
