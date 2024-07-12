<?php

namespace Ktr\BusinessCentral\Models\Builder;

use Ktr\BusinessCentral\Models\Model;

trait HasExpands
{
    protected array $expands = [];

    public function with(): Builder
    {
        return $this->expand(...func_get_args());
    }

    public function expand(): Builder
    {
        foreach (func_get_args() as $arg) {
            $this->createExpandArray($this->getExpandValue($arg));
        }

        if (!empty($this->expands)) {
            $this->query['$expand'] = implode(',', $this->expands);
        }

        return $this;
    }


    private function getExpandValue($resourceInput): string
    {

        if (!class_exists($resourceInput)) {
            return $resourceInput;
        }
        $possibleModel = new $resourceInput;

        return ($possibleModel instanceof Model)
            ? $possibleModel->getResource()
            : $resourceInput;
    }

    private function createExpandArray($arg): void
    {
        $arg = !is_array($arg) ? [$arg] : $arg;
        $this->expands = array_unique(array_merge($this->expands, $arg));
    }

}
