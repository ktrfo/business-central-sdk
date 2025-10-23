<?php

use Ktr\BusinessCentral\BusinessCentral;

it('can instantiate BusinessCentral class', function () {
    $businessCentral = new BusinessCentral;

    expect($businessCentral)->toBeInstanceOf(BusinessCentral::class);
});

it('can set API version to ODataV4', function () {
    $businessCentral = new BusinessCentral;
    $result = $businessCentral->oDataV4();

    expect($result)->toBeInstanceOf(BusinessCentral::class);
});

it('uses api v2.0 as default version', function () {
    $businessCentral = new BusinessCentral;

    // Use reflection to access protected property
    $reflection = new ReflectionClass($businessCentral);
    $property = $reflection->getProperty('apiVersion');
    $property->setAccessible(true);

    expect($property->getValue($businessCentral))->toBe('api/v2.0');
});
