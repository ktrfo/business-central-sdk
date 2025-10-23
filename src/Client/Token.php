<?php

namespace Ktr\BusinessCentral\Client;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class Token
{
    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public static function resolve(): string
    {
        $cacheKey = 'BusinessCentralAccessToken';

        if ($tokenData = Cache::get($cacheKey)) {
            return $tokenData;
        }

        $tenant = config('business-central.tenant');
        $redirectUri = "https://login.microsoftonline.com/{$tenant}/oauth2/v2.0/token";

        $payload = [
            'client_id' => config('business-central.client_id'),
            'client_secret' => config('business-central.client_secret'),
            'redirect_uri' => $redirectUri,
            'grant_type' => 'client_credentials',
            'scope' => config('business-central.scope'),
        ];

        $response = Http::asMultipart()
            ->post($redirectUri, $payload)
            ->throw();

        Cache::remember($cacheKey, $response->json()['expires_in'], fn (): string => $response->json()['access_token']);

        return $response->json()['access_token'];
    }
}
