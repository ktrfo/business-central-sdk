<?php

namespace Ktr\BusinessCentral\Models\Builder;

trait HasSorting
{
    protected array $order = [];

    public function orderByAsc(string $property): Builder
    {
        return $this->orderBy($property, 'asc');
    }

    public function orderByDesc(string $property): Builder
    {
        return $this->orderBy($property, 'desc');
    }

    public function latest(string $key = 'lastModifiedDateTime'): Builder
    {
        return $this->orderByDesc($key);
    }

    public function newest(string $key = 'lastModifiedDateTime'): Builder
    {
        return $this->orderByAsc($key);
    }

    public function orderBy($property, string $direction = 'asc'): Builder
    {

        if (is_array($property)) {
            foreach ($property as $key => $direction) {
                $this->orderBy($key, $direction);
            }
        } else {
            $this->order[$property] = $direction;
        }

        $this->query['$orderby'] = $this->constructOrderbyQueryString();

        return $this;
    }

    private function constructOrderbyQueryString(): string
    {
        $sorting = [];
        foreach ($this->order as $field => $direction) {
            $sorting[] = "$field $direction";
        }

        return implode(',', $sorting);
    }
}
