<?php

namespace Ktr\BusinessCentral\Models\Builder;

trait HasPagination
{
    public function limit(?int $limit = null): Builder
    {
        if ($limit) {
            $this->query['$top'] = $limit;
        }

        return $this;
    }

    public function offset(?int $offset = null): Builder
    {
        if ($offset) {
            $this->query['$skip'] = $offset;
        }

        return $this;
    }

    public function take(int $value): Builder
    {
        return $this->limit($value);
    }

    public function skip(int $value): Builder
    {
        return $this->offset($value);
    }
}
