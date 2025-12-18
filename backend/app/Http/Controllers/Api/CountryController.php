<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CountryController extends Controller
{
    public function index()
    {
        $url = config('app.countries_with_currency_api_url');
        /** @var \Illuminate\Http\Client\Response $response */
        $response = Http::get($url);

        $countries = collect($response->json())->map(fn($item) => [
            'country' => $item['name']['common'] ?? null,
            'currency' => !empty($item['currencies'])
                ? array_key_first($item['currencies'])
                : null,
        ]);

        return response()->json($countries->values());
    }
}
