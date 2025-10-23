<?php

namespace Ktr\BusinessCentral\Models\ApiV20;

/**
 * @property-read string "@odata.etag"
 * @property-read string $id
 * @property-read string $number
 * @property string $externalDocumentNumber
 * @property string $orderDate
 * @property string $postingDate
 * @property string $customerId
 * @property string $customerNumber
 * @property string $customerName
 * @property string $billToName
 * @property string $billToCustomerId
 * @property string $billToCustomerNumber
 * @property string $shipToName
 * @property string $shipToContact
 * @property string $sellToAddressLine1
 * @property string $sellToAddressLine2
 * @property string $sellToCity
 * @property string $sellToCountry
 * @property string $sellToState
 * @property string $sellToPostCode
 * @property string $billToAddressLine1
 * @property string $billToAddressLine2
 * @property string $billToCity
 * @property string $billToCountry
 * @property string $billToState
 * @property string $billToPostCode
 * @property string $shipToAddressLine1
 * @property string $shipToAddressLine2
 * @property string $shipToCity
 * @property string $shipToCountry
 * @property string $shipToState
 * @property string $shipToPostCode
 * @property string $shortcutDimension1Code
 * @property string $shortcutDimension2Code
 * @property string $currencyId
 * @property string $currencyCode
 * @property bool $pricesIncludeTax
 * @property string $paymentTermsId
 * @property string $shipmentMethodId
 * @property string $salesperson
 * @property bool $partialShipping
 * @property string $requestedDeliveryDate
 * @property float $discountAmount
 * @property bool $discountAppliedBeforeTax
 * @property float $totalAmountExcludingTax
 * @property float $totalTaxAmount
 * @property float $totalAmountIncludingTax
 * @property bool $fullyShipped
 * @property string $status
 * @property string $lastModifiedDateTime
 * @property string $phoneNumber
 * @property string $email
 */
class SalesOrders extends Model {}
