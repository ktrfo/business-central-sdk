<?php

use Ktr\BusinessCentral\Models\ApiV20\Items;
use Ktr\BusinessCentral\Models\ODataV4\ItemQuantities;

it('can query items with inventory greater than zero', function () {
    $builder = Items::query()
        ->where('inventory', '>', 0);

    $filters = $builder->getFilters();

    expect($filters)->toHaveCount(1)
        ->and($filters[0]['type'])->toBe('where')
        ->and($filters[0]['property'])->toBe('inventory')
        ->and($filters[0]['operator'])->toBe('gt')
        ->and($filters[0]['value'])->toBe(0);
});

it('can query ItemQuantities with Inventory greater than zero', function () {
    $builder = ItemQuantities::query()
        ->where('Inventory', '>', 0);

    $filters = $builder->getFilters();

    expect($filters)->toHaveCount(1)
        ->and($filters[0]['type'])->toBe('where')
        ->and($filters[0]['property'])->toBe('Inventory')
        ->and($filters[0]['operator'])->toBe('gt')
        ->and($filters[0]['value'])->toBe(0);
});

it('formats filter string correctly for inventory query', function () {
    $builder = Items::query()
        ->where('inventory', '>', 0);

    $filterString = $builder->getFiltersString(false);

    expect($filterString)->toBe('inventory gt 0');
});

it('can build complex inventory query', function () {
    $builder = Items::query()
        ->where('inventory', '>', 0)
        ->where('type', 'Inventory')
        ->where('blocked', false);

    $filters = $builder->getFilters();

    expect($filters)->toHaveCount(3)
        ->and($filters[0]['value'])->toBe(0) // numeric
        ->and($filters[1]['value'])->toBe('Inventory') // string
        ->and($filters[2]['value'])->toBe(false); // boolean
});

it('validates numeric zero is not treated as string', function () {
    $builder = Items::query()
        ->where('inventory', '=', 0)
        ->where('unitPrice', '>', 0.0);

    $filters = $builder->getFilters();

    expect($filters[0]['value'])->toBe(0)
        ->and($filters[0]['value'])->not->toBe('0')
        ->and($filters[1]['value'])->toBe(0.0)
        ->and($filters[1]['value'])->not->toBe('0.0');
});

it('can query with negative numbers', function () {
    $builder = Items::query()
        ->where('balanceDue', '<', 0)
        ->where('creditLimit', '>=', -1000);

    $filters = $builder->getFilters();

    expect($filters[0]['value'])->toBe(0)
        ->and($filters[1]['value'])->toBe(-1000);
});

it('handles mixed numeric and string conditions', function () {
    $builder = Items::query()
        ->where('type', 'Inventory')
        ->where('inventory', '>', 0)
        ->where('displayName', 'Widget A')
        ->where('unitPrice', '>=', 99.99);

    $filterString = $builder->getFiltersString(false);

    // Verify strings are quoted, numbers are not
    expect($filterString)
        ->toContain("type eq 'Inventory'")
        ->toContain('inventory gt 0')
        ->toContain("displayName eq 'Widget") // URL encoded with + for space
        ->toContain('unitPrice ge 99.99');
});

it('correctly formats boolean false vs integer zero', function () {
    $builderFalse = Items::where('blocked', false);
    $builderZero = Items::where('inventory', 0);

    $filtersFalse = $builderFalse->getFilters();
    $filtersZero = $builderZero->getFilters();

    expect($filtersFalse[0]['value'])->toBe(false)
        ->and($filtersFalse[0]['value'])->not->toBe(0)
        ->and($filtersZero[0]['value'])->toBe(0)
        ->and($filtersZero[0]['value'])->not->toBe(false);
});

it('preserves exact query pattern from user example', function () {
    // Exact pattern: ItemQuantities::query()->where('Inventory', '>', 0)->get()
    $builder = ItemQuantities::query()->where('Inventory', '>', 0);

    $filters = $builder->getFilters();
    $filterString = $builder->getFiltersString(false);

    expect($filters)->toHaveCount(1)
        ->and($filters[0]['property'])->toBe('Inventory')
        ->and($filters[0]['operator'])->toBe('gt')
        ->and($filters[0]['value'])->toBeInt()
        ->and($filters[0]['value'])->toBe(0)
        ->and($filterString)->toBe('Inventory gt 0');
});
