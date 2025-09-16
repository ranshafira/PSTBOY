<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteHelper
{
    public static function sendMessage(string $target, string $message): bool
    {
        try {
            $token = config('fonnte.api_token');
            $url = config('fonnte.api_url') . '/send';

            if (empty($token)) {
                Log::error('FONNTE_API_TOKEN tidak ditemukan di konfigurasi.');
                return false;
            }

            $target = self::formatPhoneNumber($target);
            if (!$target) {
                Log::error('Nomor telepon tidak valid.');
                return false;
            }

            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => $token,
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ])
                ->asForm()
                ->post($url, [
                    'target' => $target,
                    'message' => $message,
                ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['status']) && $data['status'] == true) {
                    Log::info('✅ WhatsApp berhasil dikirim ke: ' . $target);
                    return true;
                } else {
                    Log::warning('⚠️ Fonnte response menunjukkan gagal: ' . json_encode($data));
                    return false;
                }
            } else {
                Log::error('❌ HTTP Error: ' . $response->status());
                Log::error('Response: ' . $response->body());
                return false;
            }
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('❌ Connection Error: ' . $e->getMessage());
            return false;
        } catch (\Exception $e) {
            Log::error('❌ Unexpected Error: ' . $e->getMessage());
            return false;
        }
    }

    private static function formatPhoneNumber(string $phone)
    {
        if (empty($phone)) {
            return false;
        }

        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (empty($phone)) {
            return false;
        }

        if (substr($phone, 0, 2) === '62') {
            // Sudah benar
        } elseif (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        } else {
            $phone = '62' . $phone;
        }

        return $phone;
    }
}
