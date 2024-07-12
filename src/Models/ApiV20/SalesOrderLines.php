<?php

namespace Ktr\BusinessCentral\Models\ApiV20;

/**
 * @property-read string $id
 * @property-read string $documentId
 * @property int $sequence
 * @property string $itemId
 * @property string $accountId
 * @property string $lineType
 * @property string $lineObjectNumber
 * @property string $description
 * @property string $unitOfMeasureId
 * @property string $unitOfMeasureCode
 * @property int $quantity
 * @property float $unitPrice
 * @property float $discountAmount
 * @property float $discountPercent
 * @property bool $discountAppliedBeforeTax
 * @property float $amountExcludingTax
 * @property string $taxCode
 * @property float $taxPercent
 * @property float $totalTaxAmount
 * @property float $amountIncludingTax
 * @property float $invoiceDiscountAllocation
 * @property float $netAmount
 * @property float $netTaxAmount
 * @property float $netAmountIncludingTax
 * @property string $shipmentDate
 * @property int $shippedQuantity
 * @property int $invoicedQuantity
 * @property int $invoiceQuantity
 * @property int $shipQuantity
 * @property string $itemVariantId
 */
class SalesOrderLines extends Model
{

}
