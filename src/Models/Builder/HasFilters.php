<?php

namespace Ktr\BusinessCentral\Models\Builder;

use Closure;
use DateTime;

trait HasFilters
{
    public array $filters = [];

    public function filter(string $raw, string $before = 'and'): Builder
    {
        return $this->whereRaw($raw, $before);
    }

    public function orFilter(string $raw, $before = 'or'): Builder
    {
        return $this->whereRaw($raw, $before);
    }

    public function whereRaw(string $raw, $before = 'and'): Builder
    {
        $this->filters[] = [
            'type' => 'raw',
            'query' => $raw,
            'before' => $before,
        ];

        $this->getFiltersString();

        return $this;
    }

    public function orWhereRaw(string $raw, $before = 'or'): Builder
    {
        return $this->whereRaw($raw, $before);
    }

    public function where($property, $operator = null, $value = null, string $before = 'and'): Builder
    {
        if ($property instanceof Closure) {
            return $this->whereGroup($property, $before);
        }

        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }

        // Auto-detect DateTime objects and use whereDateTime instead
        if ($value instanceof DateTime) {
            return $this->whereDateTime($property, $operator, $value, $before);
        }

        $this->filters[] = [
            'type' => 'where',
            'property' => $property,
            'operator' => $this->parseOperator($operator),
            'value' => $value,
            'before' => $before,
        ];

        $this->getFiltersString();

        return $this;
    }

    public function orWhere($property, $operator = null, $value = null, string $before = 'or'): Builder
    {
        return $this->where($property, $operator, $value, $before);
    }

    public function whereIn(string $property, array $values, string $before = 'and'): Builder
    {
        $this->filters[] = [
            'type' => 'in',
            'property' => $property,
            'values' => $values,
            'before' => $before,
        ];

        $this->getFiltersString();

        return $this;
    }

    public function orWhereIn(string $property, array $values, string $before = 'or'): Builder
    {
        return $this->whereIn($property, $values, $before);
    }

    public function whereDateTime(string $property, $operator, ?DateTime $value = null, string $before = 'and'): Builder
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }

        $this->filters[] = [
            'type' => 'datetime',
            'property' => $property,
            'operator' => $this->parseOperator($operator),
            'value' => $value,
            'before' => $before,
        ];

        $this->getFiltersString();

        return $this;
    }

    public function orWhereDateTime(string $property, $operator, ?DateTime $value = null, string $before = 'or'): Builder
    {
        return $this->whereDateTime($property, $operator, $value, $before);
    }

    public function whereNot(Closure $callback, string $before = 'and'): Builder
    {
        $this->filters[] = [
            'type' => 'not',
            'callback' => $callback,
            'before' => $before,
        ];

        $this->getFiltersString();

        return $this;
    }

    public function orWhereNot(Closure $callback, string $before = 'or'): Builder
    {
        return $this->whereNot($callback, $before);
    }

    public function whereGroup(Closure $callback, string $before = 'and'): Builder
    {
        $this->filters[] = [
            'type' => 'group',
            'callback' => $callback,
            'before' => $before,
        ];

        $this->getFiltersString();

        return $this;
    }

    public function orWhereGroup(Closure $callback, string $before = 'or'): Builder
    {
        return $this->whereGroup($callback, $before);
    }

    public function whereContains(string $property, $value, string $before = 'and'): Builder
    {
        $this->filters[] = [
            'type' => 'contains',
            'property' => $property,
            'value' => $value,
            'before' => $before,
        ];

        $this->getFiltersString();

        return $this;
    }

    public function orWhereContains(string $property, $value, string $before = 'or'): Builder
    {
        return $this->whereContains($property, $value, $before);
    }

    public function whereStartsWith(string $property, $value, string $before = 'and'): Builder
    {
        $this->filters[] = [
            'type' => 'startswith',
            'property' => $property,
            'value' => $value,
            'before' => $before,
        ];

        $this->getFiltersString();

        return $this;
    }

    public function orWhereStartsWith(string $property, $value, string $before = 'or'): Builder
    {
        return $this->whereStartsWith($property, $value, $before);
    }

    public function whereEndsWith(string $property, $value, string $before = 'and'): Builder
    {
        $this->filters[] = [
            'type' => 'endswith',
            'property' => $property,
            'value' => $value,
            'before' => $before,
        ];

        $this->getFiltersString();

        return $this;
    }

    public function orWhereEndsWith(string $property, $value, string $before = 'or'): Builder
    {
        return $this->whereEndsWith($property, $value, $before);
    }

    public function getFiltersString($with_prefix = true)
    {
        if (empty($this->filters)) {
            return null;
        }

        $filters = [];
        foreach ($this->filters as $i => $filter) {
            if ($i !== 0) {
                $filters[] = $filter['before'];
            }
            switch ($filter['type']) {
                case 'where':
                    $filters[] = "$filter[property] $filter[operator] {$this->formatValue($filter['value'])}";
                    break;

                case 'datetime':
                    $filters[] = "$filter[property] $filter[operator] {$filter['value']->format('Y-m-d\TH:i:s.v\Z')}";
                    break;

                case 'group':
                    $group_query = new self($this->model);
                    $filter['callback']($group_query);
                    $filters[] = "( {$group_query->getFiltersString(false)} )";
                    break;

                case 'startswith':
                case 'endswith':
                case 'contains':
                    $filters[] = "$filter[type]($filter[property], {$this->formatValue($filter['value'])})";
                    break;

                case 'in':
                    $values = implode(', ', array_map([$this, 'formatValue'], $filter['values']));

                    $filters[] = "$filter[property] in ( $values )";
                    break;

                case 'not':
                    $not_query = new self($this->model);
                    $filter['callback']($not_query);
                    $filters[] = "not ( {$not_query->getFiltersString(false)} )";
                    break;

                case 'raw':
                    $filters[] = $filter['query'];
            }
        }

        if (! empty($this->filters)) {
            $this->query['$filter'] = implode(' ', $filters);
        }

        return ($with_prefix ? '$filter=' : '').implode(' ', $filters);
    }

    /**
     * Converts the value into a usable state for $filter
     *
     * @param  mixed  $value
     * @param  bool  $for_query
     * @return string|int|float|bool
     */
    protected function formatValue(mixed $value, bool $for_query = true): string|int|float|bool
    {
        // Non-string values (int, float, bool) are returned as-is
        if (! is_string($value)) {
            return $value;
        }

        // String values need to be wrapped in quotes for OData
        if ($for_query) {
            return sprintf("'%s'", urlencode($value));
        }

        return sprintf("'%s'", $value);
    }

    /**
     * Parse standard logical operators to OData variants
     */
    protected function parseOperator($operator): string
    {
        return ['=' => 'eq', '!=' => 'ne', '>' => 'gt', '>=' => 'ge', '<' => 'lt', '<=' => 'le'][$operator] ?? $operator;
    }

    public function getFilters(): array
    {
        return $this->filters;
    }

    /**
     * Set filters for query
     *
     *
     * @return $this|Builder
     */
    public function setFilters(array $filters)
    {
        $this->filters = $filters;

        return $this;
    }

    /**
     * Remove filters from the query
     */
    public function withoutFilters(): Builder
    {
        return $this->setFilters([]);
    }
}
