<?php

use Carbon\Carbon;
use Ktr\BusinessCentral\Models\ApiV20\Items;

it('can use where with DateTime objects', function () {
    $date = new DateTime('2024-01-15');
    $builder = Items::where('lastModifiedDateTime', '>=', $date);

    $filters = $builder->getFilters();

    expect($filters)->toHaveCount(1)
        ->and($filters[0]['type'])->toBe('datetime')
        ->and($filters[0]['value'])->toBeInstanceOf(DateTime::class);
});

it('can use where with Carbon objects', function () {
    $date = Carbon::parse('2024-01-15');
    $builder = Items::where('lastModifiedDateTime', '>=', $date);

    $filters = $builder->getFilters();

    expect($filters)->toHaveCount(1)
        ->and($filters[0]['type'])->toBe('datetime')
        ->and($filters[0]['value'])->toBeInstanceOf(DateTime::class);
});

it('can use whereDateTime explicitly', function () {
    $date = new DateTime('2024-01-15');
    $builder = Items::whereDateTime('lastModifiedDateTime', '>=', $date);

    $filters = $builder->getFilters();

    expect($filters)->toHaveCount(1)
        ->and($filters[0]['type'])->toBe('datetime');
});

it('formats datetime correctly in filter string', function () {
    $date = new DateTime('2024-01-15 10:30:00');
    $builder = Items::where('lastModifiedDateTime', '>=', $date);

    $filterString = $builder->getFiltersString(false);

    expect($filterString)->toContain('2024-01-15T10:30:00');
});
