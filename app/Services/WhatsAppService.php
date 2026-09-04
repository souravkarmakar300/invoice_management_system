<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    public function isConfigured(): bool
    {
        return filled(config('services.whatsapp.api_key'))
            && filled(config('services.whatsapp.template_name'))
            && filled(config('services.whatsapp.template_language'));
    }

    public function canSendViaApi(): bool
    {
        if (! $this->isConfigured()) {
            return false;
        }

        $appUrl = strtolower((string) config('app.url'));

        return filled($appUrl)
            && ! str_contains($appUrl, 'localhost')
            && ! str_contains($appUrl, '127.0.0.1');
    }

    public function sendDocument(
        string $phone,
        string $fileName,
        string $pdfUrl,
        string $invoiceNo,
        string $totalFormatted
    ): array {
        $apiKey = config('services.whatsapp.api_key');
        $baseUrl = rtrim(config('services.whatsapp.base_url', 'https://api.interakt.ai'), '/');
        $templateName = config('services.whatsapp.template_name');
        $languageCode = config('services.whatsapp.template_language', 'en');

        $phone = preg_replace('/\D+/', '', $phone);

        if ($phone === '') {
            return [
                'success' => false,
                'message' => 'Invalid WhatsApp phone number.',
            ];
        }

        if (str_starts_with($phone, '91')) {
            $countryCode = '+91';
            $phoneNumber = substr($phone, 2);
        } elseif (str_starts_with($phone, '61')) {
            $countryCode = '+61';
            $phoneNumber = substr($phone, 2);
        } else {
            return [
                'success' => false,
                'message' => 'Please enter a WhatsApp number with country code +91 or +61.',
            ];
        }

        $payload = [
            'countryCode' => $countryCode,
            'phoneNumber' => $phoneNumber,
            'type' => 'Template',
            'callbackData' => 'invoice_' . $invoiceNo,
            'template' => [
                'name' => $templateName,
                'languageCode' => $languageCode,
                'headerValues' => [$pdfUrl],
                'fileName' => $fileName,
                'bodyValues' => [
                    $invoiceNo,
                ],
            ],
        ];

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Basic ' . $apiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->post($baseUrl . '/v1/public/message/', $payload);

            $responseData = $response->json() ?? [];

            if (! $response->successful() || ($responseData['result'] ?? false) !== true) {

                Log::error('Interakt WhatsApp API failed', [
                    'status' => $response->status(),
                    'response' => $responseData,
                    'body' => $response->body(),
                    'phone' => $phone,
                    'template' => $templateName,
                    'pdf_url' => $pdfUrl,
                ]);

                return [
                    'success' => false,
                    'message' => $responseData['message']
                        ?? $responseData['error']
                        ?? 'Interakt WhatsApp API failed.',
                    'debug' => [
                        'http_status' => $response->status(),
                        'response' => $responseData,
                    ],
                ];
            }

            // if (! $response->successful() || ($responseData['result'] ?? false) !== true) {
            //     Log::error('Interakt WhatsApp API failed', [
            //         'status' => $response->status(),
            //         'response' => $responseData,
            //         'body' => $response->body(),
            //         'phone' => $phone,
            //         'template' => $templateName,
            //         'pdf_url' => $pdfUrl,
            //     ]);

            //     return [
            //         'success' => false,
            //         'message' => $responseData['message']
            //             ?? $responseData['error']
            //             ?? 'Interakt could not send the WhatsApp message.',
            //     ];
            // }

            // return [
            //     'success' => true,
            //     'mode' => 'api',
            //     'message' => 'Invoice PDF queued for WhatsApp delivery.',
            //     'message_id' => $responseData['id'] ?? null,
            // ];
        } catch (\Throwable $e) {
            Log::error('Interakt WhatsApp exception', [
                'message' => $e->getMessage(),
                'phone' => $phone,
                'template' => $templateName,
            ]);

            return [
                'success' => false,
                'message' => 'Unable to connect to Interakt WhatsApp API.',
            ];
        }
    }
}
