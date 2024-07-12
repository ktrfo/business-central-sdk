<?php

namespace Ktr\BusinessCentral\Models\ApiV20;

/**
 * @property-read string $id The unique ID of the item. Non-editable.
 * @property-read string $number Specifies the number of the item.
 * @property string $displayName Specifies the item's name. This name will appear on all sales documents for the item.
 * @property string $displayName2
 * @property string $type Specifies the type of the item. It can be "Inventory", "Service" or "Non-Inventory".
 * @property string $itemCategoryId The ID of the item category in the item.
 * @property string $itemCategoryCode The code of the item category in the item.
 * @property bool $blocked Specifies that entries cannot be posted to the item. True indicates account is blocked and posting is not allowed.
 * @property string $gtin This is the Global Trade Item Number.
 * @property-read float $inventory Specifies how many units, such as pieces, boxes, or cans, of the item are in inventory. Read-Only.
 * @property float $unitPrice Specifies the price for one unit of the item in the specified item.
 * @property bool $priceIncludesTax Specifies that the unitPrice includes tax. Set to true, if unitPrice includes tax.
 * @property float $unitCost The unit cost of each individual item in the item.
 * @property string $taxGroupId Specifies the ID of the Tax Group for the item.
 * @property string $taxGroupCode A Tax Group represents a group of inventory items or resources that are subject to identical tax terms.
 * @property string $baseUnitOfMeasureId Specifies the ID of the unit of measure.
 * @property string $baseUnitOfMeasureCode The item's base unit of measure code.
 * @property string $generalProductPostingGroupId
 * @property string $generalProductPostingGroupCode
 * @property string $inventoryPostingGroupId
 * @property string $inventoryPostingGroupCode
 * @property-read string $lastModifiedDateTime The last datetime the item was modified. Read-Only.
 */
class Items extends Model
{

}
