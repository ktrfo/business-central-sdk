<?php

namespace Ktr\BusinessCentral\Models\ApiV20;

/**
 * @property-read string $id
 * @property-read string $number
 * @property-read string $displayName
 * @property-read string $type
 * @property-read string $addressLine1
 * @property-read string $addressLine2
 * @property-read string $city
 * @property-read string $state
 * @property-read string $country
 * @property-read string $postalCode
 * @property-read string $phoneNumber
 * @property-read string $email
 * @property-read string $website
 * @property-read string $salespersonCode
 * @property-read float $balanceDue
 * @property-read float $creditLimit
 * @property-read bool $taxLiable
 * @property-read string $taxAreaId
 * @property-read string $taxAreaDisplayName
 * @property-read string $taxRegistrationNumber
 * @property-read string $currencyId
 * @property-read string $currencyCode
 * @property-read string $paymentTermsId
 * @property-read string $shipmentMethodId
 * @property-read string $paymentMethodId
 * @property-read string $blocked
 * @property-read string $lastModifiedDateTime
 */
class Customers extends Model {}
