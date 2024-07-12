<?php

namespace Ktr\BusinessCentral\Models\ODataV4;

use Illuminate\Support\Collection;

/**
 * @property-read string "@odata.etag"
 * @property-read string "No"
 * @property-read string "Nummer_2"
 * @property-read string "Description"
 * @property-read string "Description_2"
 * @property-read string "Søgebeskrivelse"
 * @property-read string "Base_Unit_of_Measure"
 * @property-read float "Unit_Price"
 * @property-read int "InventoryCtrl"
 * @property-read bool "Created_From_Nonstock_Item"
 * @property-read bool "Stockkeeping_Unit_Exists"
 * @property-read string "Routing_No"
 * @property-read string "Shelf_No"
 * @property-read string "Costing_Method"
 * @property-read bool "Cost_is_Adjusted"
 * @property-read float "Standard_Cost"
 * @property-read float "Unit_Cost"
 * @property-read float "Last_Direct_Cost"
 * @property-read string "Price_Profit_Calculation"
 * @property-read float "Profit_Percent"
 * @property-read string "Inventory_Posting_Group"
 * @property-read string "Gen_Prod_Posting_Group"
 * @property-read string "VAT_Prod_Posting_Group"
 * @property-read string "Item_Disc_Group"
 * @property-read string "Vendor_No"
 * @property-read string "Vendor_Item_No"
 * @property-read string "Tariff_No"
 * @property-read string "Search_Description"
 * @property-read float "Overhead_Rate"
 * @property-read float "Indirect_Cost_Percent"
 * @property-read string "Item_Category_Code"
 * @property-read bool "Blocked"
 * @property-read string "Last_Date_Modified"
 * @property-read string "Sales_Unit_of_Measure"
 * @property-read string "Replenishment_System"
 * @property-read string "Purch_Unit_of_Measure"
 * @property-read string "Lead_Time_Calculation"
 * @property-read string "Manufacturing_Policy"
 * @property-read string "Flushing_Method"
 * @property-read string "Assembly_Policy"
 * @property-read string "Item_Tracking_Code"
 * @property-read string "Default_Deferral_Template_Code"
 * @property-read string "Global_Dimension_1_Filter"
 * @property-read string "Global_Dimension_2_Filter"
 * @property-read string "Location_Filter"
 * @property-read string "Drop_Shipment_Filter"
 * @property-read string "Variant_Filter"
 * @property-read string "Lot_No_Filter"
 * @property-read string "Serial_No_Filter"
 * @property-read string "Unit_of_Measure_Filter"
 * @property-read string "Package_No_Filter"
 */
class Items extends Model
{
    protected $resource = 'items'; //Page Object id 32
}
