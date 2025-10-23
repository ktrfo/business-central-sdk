<?php

namespace Ktr\BusinessCentral\Models\ODataV4;

use Illuminate\Support\Collection;

/**
 * @property-read string $no
 * @property-read string $Description
 * @property-read int $Inventory
 * @property-read int $Qty_on_Purch_Order
 * @property-read int $Qty_on_Sales_Order
 * @property-read int $Net_Invoiced_Qty
 */
class ItemQuantities extends Model
{
    protected $resource = 'ItemQuantities'; // Query Object id 2552
    // protected $apiVersion = 'ODataV4';

    public static function available(): Collection
    {
        return (new static)->newQuery()->where('Inventory', '>', 1)->get();

    }
}
