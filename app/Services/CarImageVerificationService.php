<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;

class CarImageVerificationService
{
    public function verify($imageUrl): bool
    {
        // dd($imageUrl);
        try {

            $response = Http::timeout(500)
                ->retry(2, 2000)
                ->withHeaders([
                    'x-rapidapi-key' => config('services.rapidapi.key'),
                    'x-rapidapi-host' => 'car-license-plate-detection.p.rapidapi.com',
                    'Content-Type' => 'application/json'
                ])
                ->post(
                    'https://car-license-plate-detection.p.rapidapi.com/recognizeCars',
                    [
                        'image_url' => $imageUrl
                    ]
                );

            if (!$response->successful()) {
                return false;
            }

            $data = $response->json();

            return isset($data['cars']) && count($data['cars']) > 0;

        } catch (ConnectionException $e) {

            return false;
        }
    }
}