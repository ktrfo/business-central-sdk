<?php

namespace Ktr\BusinessCentral\Models\ODataV4;

/**
 * @property-read string "@odata.etag"
 * @property-read string "Type"
 * @property-read string "Code"
 * @property-read string "Sales_Type"
 * @property-read string "Sales_Code"
 * @property-read string "Starting_Date"
 * @property-read string "Currency_Code"
 * @property-read string "Variant_Code"
 * @property-read string "Unit_of_Measure_Code"
 * @property-read float "Minimum_Quantity"
 * @property-read float "Line_Discount_Percent"
 * @property-read string "Ending_Date"
 */
class SalesLineDisc extends Model
{
    protected $resource = 'SalesLineDisc'; //Page Object id 7009
}
