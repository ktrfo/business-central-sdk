<?php

namespace Ktr\BusinessCentral\Tests;

use Illuminate\Support\Facades\Cache;
use Ktr\BusinessCentral\BusinessCentralServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            BusinessCentralServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        config()->set('business-central.client_id', 'test-client-id');
        config()->set('business-central.client_secret', 'test-client-secret');
        config()->set('business-central.tenant', 'test-tenant');
        config()->set('business-central.environment', 'test');
        config()->set('business-central.company', 'test-company');
        config()->set('business-central.version', 'api/v2.0');
        config()->set('business-central.timeout', 30);

        // Mock the access token to prevent OAuth2 calls in tests
        Cache::shouldReceive('get')
            ->with('BusinessCentralAccessToken')
            ->andReturn('test-access-token');
    }
}
