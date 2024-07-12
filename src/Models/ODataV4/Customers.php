<?php

namespace Ktr\BusinessCentral\Models\ODataV4;

use Illuminate\Support\Collection;

/**
 * @property-read string "@odata.etag"
 * @property-read string "No"
 * @property-read string "OldCustomerNo"
 * @property-read string "Name"
 * @property-read string "Address"
 * @property-read string "Address_2"
 * @property-read string "Name_2"
 * @property-read string "Responsibility_Center"
 * @property-read string "Location_Code"
 * @property-read string "Post_Code"
 * @property-read string "Country_Region_Code"
 * @property-read string "Phone_No"
 * @property-read string "IC_Partner_Code"
 * @property-read string "Contact"
 * @property-read string "Salesperson_Code"
 * @property-read string "Customer_Posting_Group"
 * @property-read bool "Allow_Multiple_Posting_Groups"
 * @property-read string "Gen_Bus_Posting_Group"
 * @property-read string "VAT_Bus_Posting_Group"
 * @property-read string "Customer_Price_Group"
 * @property-read string "Customer_Disc_Group"
 * @property-read string "Payment_Terms_Code"
 * @property-read string "Reminder_Terms_Code"
 * @property-read string "Fin_Charge_Terms_Code"
 * @property-read string "Currency_Code"
 * @property-read string "Language_Code"
 * @property-read string "Search_Name"
 * @property-read float "Credit_Limit_LCY"
 * @property-read string "Blocked"
 * @property-read bool "Privacy_Blocked"
 * @property-read string "Last_Date_Modified"
 * @property-read string "Application_Method"
 * @property-read bool "Combine_Shipments"
 * @property-read string "Reserve"
 * @property-read string "Ship_to_Code"
 * @property-read string "Shipping_Advice"
 * @property-read string "Shipping_Agent_Code"
 * @property-read string "Base_Calendar_Code"
 * @property-read float "Balance_LCY"
 * @property-read float "Balance_Due_LCY"
 * @property-read float "Sales_LCY"
 * @property-read float "Payments_LCY"
 * @property-read bool "Coupled_to_CRM"
 * @property-read bool "Coupled_to_Dataverse"
 * @property-read string "Global_Dimension_1_Filter"
 * @property-read string "Global_Dimension_2_Filter"
 * @property-read string "Currency_Filter"
 * @property-read string "Date_Filter"
 */
class Customers extends Model
{
    protected $resource = 'Customers'; //Page Object id 22
}
