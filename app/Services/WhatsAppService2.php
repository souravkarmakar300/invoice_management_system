<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    public function isConfigured(): bool
    {
        return filled(config('services.whatsapp.token'))
            && filled(config('services.whatsapp.phone_number_id'));
    }

    public function sendDocument(string $phone, string $filePath, string $fileName, string $caption = ''): array
    {
        $token = config('services.whatsapp.token');
        $phoneNumberId = config('services.whatsapp.phone_number_id');
        $version = config('services.whatsapp.api_version', 'v21.0');

        $upload = Http::withToken($token)
            ->attach('file', file_get_contents($filePath), $fileName)
            ->post("https://graph.facebook.com/{$version}/{$phoneNumberId}/media", [
                'messaging_product' => 'whatsapp',
                'type' => 'application/pdf',
            ]);

        if (! $upload->successful()) {
            Log::error('WhatsApp media upload failed', ['response' => $upload->json()]);

            return [
                'success' => false,
                'message' => $upload->json('error.message') ?? 'Failed to upload PDF to WhatsApp.',
            ];
        }

        $mediaId = $upload->json('id');

        $send = Http::withToken($token)
            ->post("https://graph.facebook.com/{$version}/{$phoneNumberId}/messages", [
                'messaging_product' => 'whatsapp',
                'to' => $phone,
                'type' => 'document',
                'document' => [
                    'id' => $mediaId,
                    'filename' => $fileName,
                    'caption' => $caption,
                ],
            ]);

        if (! $send->successful()) {
            Log::error('WhatsApp message send failed', ['response' => $send->json()]);

            return [
                'success' => false,
                'message' => $send->json('error.message') ?? 'Failed to send WhatsApp message.',
            ];
        }

        return [
            'success' => true,
            'message' => 'Invoice PDF sent successfully on WhatsApp.',
        ];
    }
}
