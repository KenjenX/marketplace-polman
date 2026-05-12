<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class RegionController extends Controller
{
    public function provinces()
    {
        $response = Http::get(
            'https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json'
        );

        return response()->json(
            $response->json()
        );
    }

    public function cities($provinceId)
    {
        $response = Http::get(
            "https://www.emsifa.com/api-wilayah-indonesia/api/regencies/{$provinceId}.json"
        );

        return response()->json(
            $response->json()
        );
    }

    public function districts($cityId)
    {
        $response = Http::get(
            "https://www.emsifa.com/api-wilayah-indonesia/api/districts/{$cityId}.json"
        );

        return response()->json(
            $response->json()
        );
    }
}