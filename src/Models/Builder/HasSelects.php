<?php

namespace Ktr\BusinessCentral\Models\Builder;

use Ktr\BusinessCentral\Models\Model;

trait HasSelects
{
    protected array $selects = [];

    public function select(): Builder
    {
        foreach (func_get_args() as $arg) {
            $arg = !is_array($arg) ? [$arg] : $arg;
            $this->selects = array_unique(array_merge($this->selects, $arg));
        }
        if (!empty($this->selects)) {
            $this->query['$select'] = implode(',', $this->selects);
        }
        return $this;
    }

}
