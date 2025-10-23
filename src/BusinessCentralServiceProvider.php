<?php

namespace Ktr\BusinessCentral;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;
use Ktr\BusinessCentral\Client\Token;

class BusinessCentralServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('business-central.php'),
            ], 'config');
        }

        Http::macro('businessCentral', function ($version = null) {
            $version = $version ?? config('business-central.version');
            $tenant = config('business-central.tenant');
            $environment = config('business-central.environment');
            $baseUrl = "https://api.businesscentral.dynamics.com/v2.0/{$tenant}/{$environment}/{$version}/";

            return Http::timeout(config('business-central.timeout'))->withUserAgent('KTR')->withToken(Token::resolve())->withHeaders([
                'Company' => config('business-central.company'),
            ])->baseUrl($baseUrl);
        });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'business-central');

        // Register the main class to use with the facade
        $this->app->singleton('business-central-sdk', function () {
            return new BusinessCentral;
        });
    }
}
