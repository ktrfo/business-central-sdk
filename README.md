# Microsoft Business Central SDK for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ktr/business-central-sdk.svg?style=flat-square)](https://packagist.org/packages/ktr/business-central-sdk)
[![Total Downloads](https://img.shields.io/packagist/dt/ktr/business-central-sdk.svg?style=flat-square)](https://packagist.org/packages/ktr/business-central-sdk)


Microsoft Business Central SDK for Laravel.

## Installation

You can install the package via composer:

```bash
composer require ktr/business-central-sdk dev-main
```

## Usage

```php
// Add to .env
BUSINESS_CENTRAL_CLIENT_ID=
BUSINESS_CENTRAL_CLIENT_SECRET=
BUSINESS_CENTRAL_TENANT=
BUSINESS_CENTRAL_ENVIRONMENT=
BUSINESS_CENTRAL_COMPANY=

// Use Http Client
$order = Http::businessCentral()->post('salesOrders', [
    'customerId' => 'id',
    'shipToName' => 'Customer Name',
    'salesOrderLines' => [
        [
            'lineType' => 'Item',
            'itemId' => 'id',
            'quantity' => 10,
            'unitPrice' => 20.0
        ]
    ]
]);

// Use Facade
$order = BusinessCentral::post('salesOrders', [
    'customerId' => 'id',
    'shipToName' => 'Customer Name',
    'salesOrderLines' => [
        [
            'lineType' => 'Item',
            'itemId' => 'id',
            'quantity' => 10,
            'unitPrice' => 20.0
        ]
    ]
]);
    
// Usr Model    
$order = SalesOrders::create([
    'customerId' => 'id',
    'shipToName' => 'Customer Name',
    'salesOrderLines' => [
        [
            'lineType' => 'Item',
            'itemId' => 'id',
            'quantity' => 10,
            'unitPrice' => 20.0
        ]
    ]
]);

// Get Sales Order
$order = SalesOrders::select('customerId', 'shipToName','email')
    ->where('number', '{no}')
    ->expand(SalesOrderLines::class)
    ->first();

// Update Sales Order
$order->email = "test@test.com";
$order->save();
    
// Delete Sales Order
$order->delete();

```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

-   [John Eystein Johannesen](https://github.com/ktrfo)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
