<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeocodingService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = env('GOOGLE_MAPS_API_KEY'); // Make sure to set your API key in the .env file
    }

    public function getCoordinates($address)
    {
        $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
            'address' => $address,
            'key' => $this->apiKey,
        ]);

        return $response->json();
    }
}

