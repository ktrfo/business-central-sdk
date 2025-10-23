<?php

namespace Ktr\BusinessCentral\Client;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HttpClient
{
    public static function get(string $resource, string $apiVersion, array $query): ?Collection
    {
        $response = Http::businessCentral($apiVersion)->get($resource, $query);

        if ($response->successful()) {
            return collect($response->json()['value']);
        }

        Log::error($response->json()['error']['code'].' '.$response->json()['error']['message']);

        return null;

    }

    public static function post(string $resource, string $apiVersion, array $data): array
    {
        $response = Http::businessCentral($apiVersion)->post($resource, $data);

        if ($response->successful()) {
            return $response->json();
        } else {
            throw new ConnectionException($response->json()['error']['code'].' '.$response->json()['error']['message']);
        }
    }

    public static function patch(string $etag, string $resource, string $apiVersion, array $data): array
    {
        $response = Http::businessCentral($apiVersion)->withHeader(
            'If-Match', $etag
        )->patch($resource, $data);

        if ($response->successful()) {
            return $response->json();
        } else {
            throw new ConnectionException($response->json()['error']['code'].' '.$response->json()['error']['message']);
        }
    }

    public static function delete(string $etag, string $resource, string $apiVersion): bool
    {
        $response = Http::businessCentral($apiVersion)->withHeader(
            'If-Match', $etag
        )->delete($resource);

        if ($response->successful()) {
            return true;
        } else {
            throw new ConnectionException($response->json()['error']['code'].' '.$response->json()['error']['message']);
        }
    }
}
