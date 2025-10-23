<?php

namespace Ktr\BusinessCentral;

use Illuminate\Support\Facades\Http;

/**
 * @mixin Http
 */
class BusinessCentral
{
    protected $apiVersion = 'api/v2.0';

    /**
     * Sets the API version to ODataV4.
     *
     * @return $this
     */
    public function oDataV4()
    {
        $this->apiVersion = 'ODataV4';

        return $this;
    }

    public function __call($method, $parameters)
    {
        return Http::businessCentral($this->apiVersion)->$method(...$parameters);
    }
}
