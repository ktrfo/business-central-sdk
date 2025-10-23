<?php

use Ktr\BusinessCentral\Models\ApiV20\Items;

it('can build simple where filter', function () {
    $builder = Items::where('type', 'Inventory');

    $filters = $builder->getFilters();

    expect($filters)->toHaveCount(1)
        ->and($filters[0])->toHaveKey('type', 'where')
        ->and($filters[0])->toHaveKey('property', 'type')
        ->and($filters[0])->toHaveKey('operator', 'eq')
        ->and($filters[0])->toHaveKey('value', 'Inventory');
});

it('can build where filter with operator', function () {
    $builder = Items::where('unitPrice', '>', 100);

    $filters = $builder->getFilters();

    expect($filters)->toHaveCount(1)
        ->and($filters[0]['operator'])->toBe('gt')
        ->and($filters[0]['value'])->toBe(100);
});

it('can build multiple where filters', function () {
    $builder = Items::query()
        ->where('type', 'Inventory')
        ->where('unitPrice', '>', 50);

    $filters = $builder->getFilters();

    expect($filters)->toHaveCount(2)
        ->and($filters[0]['property'])->toBe('type')
        ->and($filters[1]['property'])->toBe('unitPrice')
        ->and($filters[1]['before'])->toBe('and');
});

it('can build or where filters', function () {
    $builder = Items::query()
        ->where('type', 'Inventory')
        ->orWhere('type', 'Service');

    $filters = $builder->getFilters();

    expect($filters)->toHaveCount(2)
        ->and($filters[1]['before'])->toBe('or');
});

it('can build whereIn filter', function () {
    $builder = Items::whereIn('type', ['Inventory', 'Service']);

    $filters = $builder->getFilters();

    expect($filters)->toHaveCount(1)
        ->and($filters[0]['type'])->toBe('in')
        ->and($filters[0]['values'])->toBe(['Inventory', 'Service']);
});

it('can build whereContains filter', function () {
    $builder = Items::whereContains('displayName', 'Widget');

    $filters = $builder->getFilters();

    expect($filters)->toHaveCount(1)
        ->and($filters[0]['type'])->toBe('contains')
        ->and($filters[0]['property'])->toBe('displayName')
        ->and($filters[0]['value'])->toBe('Widget');
});

it('formats string values correctly', function () {
    $builder = Items::where('type', 'Inventory');

    // Use reflection to access protected method
    $reflection = new ReflectionClass($builder);
    $method = $reflection->getMethod('formatValue');
    $method->setAccessible(true);

    $result = $method->invoke($builder, 'Inventory');

    expect($result)->toBe("'Inventory'");
});

it('formats numeric values correctly', function () {
    $builder = Items::query();

    // Use reflection to access protected method
    $reflection = new ReflectionClass($builder);
    $method = $reflection->getMethod('formatValue');
    $method->setAccessible(true);

    $intResult = $method->invoke($builder, 100);
    $floatResult = $method->invoke($builder, 99.99);
    $boolResult = $method->invoke($builder, true);

    expect($intResult)->toBe(100)
        ->and($floatResult)->toBe(99.99)
        ->and($boolResult)->toBe(true);
});

it('parses operators correctly', function () {
    $builder = Items::query();

    // Use reflection to access protected method
    $reflection = new ReflectionClass($builder);
    $method = $reflection->getMethod('parseOperator');
    $method->setAccessible(true);

    expect($method->invoke($builder, '='))->toBe('eq')
        ->and($method->invoke($builder, '!='))->toBe('ne')
        ->and($method->invoke($builder, '>'))->toBe('gt')
        ->and($method->invoke($builder, '>='))->toBe('ge')
        ->and($method->invoke($builder, '<'))->toBe('lt')
        ->and($method->invoke($builder, '<='))->toBe('le');
});
