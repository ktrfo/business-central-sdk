<?php

namespace Ktr\BusinessCentral\Models\ODataV4;

use Ktr\BusinessCentral\Models\Builder\Builder;

/**
 * @mixin Builder
 */
class Model extends \Ktr\BusinessCentral\Models\Model
{
    protected $apiVersion = 'ODataV4';
}
