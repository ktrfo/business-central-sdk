<?php

use Carbon\Carbon;
use Ktr\BusinessCentral\Models\ApiV20\Items;
use Ktr\BusinessCentral\Models\ApiV20\SalesOrders;

it('can build complex query with multiple filter types', function () {
    $date = Carbon::parse('2024-01-01');

    $builder = Items::query()
        ->where('type', 'Inventory')
        ->where('unitPrice', '>', 50)
        ->whereIn('itemCategoryCode', ['CAT1', 'CAT2'])
        ->whereContains('displayName', 'Widget')
        ->where('lastModifiedDateTime', '>=', $date)
        ->where('blocked', false);

    $filters = $builder->getFilters();

    expect($filters)->toHaveCount(6)
        ->and($filters[0]['type'])->toBe('where')
        ->and($filters[1]['type'])->toBe('where')
        ->and($filters[2]['type'])->toBe('in')
        ->and($filters[3]['type'])->toBe('contains')
        ->and($filters[4]['type'])->toBe('datetime')
        ->and($filters[5]['type'])->toBe('where');
});

it('can build query with grouped conditions', function () {
    $builder = Items::query()
        ->where(function ($query) {
            $query->where('type', 'Inventory')
                ->where('unitPrice', '>', 100);
        })
        ->orWhere(function ($query) {
            $query->where('type', 'Service')
                ->where('unitCost', '<', 50);
        });

    $filters = $builder->getFilters();

    expect($filters)->toHaveCount(2)
        ->and($filters[0]['type'])->toBe('group')
        ->and($filters[1]['type'])->toBe('group')
        ->and($filters[1]['before'])->toBe('or');
});

it('can chain where with orWhere correctly', function () {
    $builder = Items::query()
        ->where('type', 'Inventory')
        ->orWhere('type', 'Service')
        ->where('blocked', false);

    $filters = $builder->getFilters();

    expect($filters)->toHaveCount(3)
        ->and($filters[0]['before'])->toBe('and')
        ->and($filters[1]['before'])->toBe('or')
        ->and($filters[2]['before'])->toBe('and');
});

it('can use raw filters when needed', function () {
    $builder = Items::query()
        ->where('type', 'Inventory')
        ->filter("unitPrice gt 100 and unitPrice lt 1000");

    $filters = $builder->getFilters();

    expect($filters)->toHaveCount(2)
        ->and($filters[0]['type'])->toBe('where')
        ->and($filters[1]['type'])->toBe('raw');
});

it('formats filter string correctly for OData', function () {
    $builder = Items::query()
        ->where('type', 'Inventory')
        ->where('unitPrice', '>', 100)
        ->where('blocked', false);

    $filterString = $builder->getFiltersString(false);

    expect($filterString)->toContain('type eq')
        ->and($filterString)->toContain('unitPrice gt 100')
        ->and($filterString)->toContain('and');
});

it('can use whereNot with callback', function () {
    $builder = Items::query()
        ->where('type', 'Inventory')
        ->whereNot(function ($query) {
            $query->where('blocked', true);
        });

    $filters = $builder->getFilters();

    expect($filters)->toHaveCount(2)
        ->and($filters[1]['type'])->toBe('not');
});

it('supports all string matching methods', function () {
    $builderContains = Items::whereContains('displayName', 'Widget');
    $builderStarts = Items::whereStartsWith('number', 'ITEM-');
    $builderEnds = Items::whereEndsWith('number', '-X');

    expect($builderContains->getFilters()[0]['type'])->toBe('contains')
        ->and($builderStarts->getFilters()[0]['type'])->toBe('startswith')
        ->and($builderEnds->getFilters()[0]['type'])->toBe('endswith');
});
