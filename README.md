# Microsoft Business Central SDK for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ktr/business-central-sdk.svg?style=flat-square)](https://packagist.org/packages/ktr/business-central-sdk)
[![Total Downloads](https://img.shields.io/packagist/dt/ktr/business-central-sdk.svg?style=flat-square)](https://packagist.org/packages/ktr/business-central-sdk)
![Tests](https://github.com/ktrfo/business-central-sdk/workflows/tests/badge.svg)

A modern Laravel SDK for Microsoft Dynamics 365 Business Central. Connect to Business Central's web services with an elegant, Laravel-style API that feels right at home in your Laravel application.

## Why This Package?

Building integrations with Microsoft Business Central can be complex and time-consuming. This SDK simplifies the entire process by providing:

- **Laravel-Native Integration** - Feels like native Laravel code with familiar query builder patterns
- **Three Flexible APIs** - Choose between HTTP Client macros, Facades, or Eloquent-style Models
- **Comprehensive Coverage** - Access 85+ Business Central entities (Customers, Sales Orders, Items, Invoices, and more)
- **Modern PHP** - Built for PHP 8.2+ and Laravel 11+
- **Type-Safe** - Full PHPDoc annotations for IDE autocompletion
- **OAuth2 Authentication** - Handles token management and caching automatically
- **OData Query Builder** - Powerful filtering, sorting, expanding, and pagination

## Features

✅ **Model-Based API** - Eloquent-like models for all Business Central entities
✅ **Query Builder** - Intuitive filtering with `where()`, `whereIn()`, `whereContains()`, and more
✅ **Relationship Expansion** - Eager load related data with `expand()`
✅ **Pagination Support** - Built-in `take()`, `skip()`, `lazy()`, and `all()` methods
✅ **HTTP Client Macro** - Use `Http::businessCentral()` for quick API calls
✅ **Facade Support** - Simple `BusinessCentral::get()` / `::post()` interface
✅ **Automatic Token Management** - OAuth2 tokens cached and refreshed automatically
✅ **Laravel 11 & 12 Support** - Tested on PHP 8.2 and 8.3

## Requirements

- PHP 8.2 or higher
- Laravel 11.0 or higher

## Installation

Install the package via Composer:

```bash
composer require ktr/business-central-sdk
```

## Configuration

Add your Business Central credentials to your `.env` file:

```env
BUSINESS_CENTRAL_CLIENT_ID=your-client-id
BUSINESS_CENTRAL_CLIENT_SECRET=your-client-secret
BUSINESS_CENTRAL_TENANT=your-tenant-id
BUSINESS_CENTRAL_ENVIRONMENT=production
BUSINESS_CENTRAL_COMPANY=your-company-id
```

### Getting Your Credentials

1. Register your app in [Azure Portal](https://portal.azure.com)
2. Create an App Registration with API permissions for Business Central
3. Generate a client secret
4. Note your Tenant ID from Azure AD
5. Find your Company ID and Environment from Business Central

> **Tip:** For sandbox testing, set `BUSINESS_CENTRAL_ENVIRONMENT=sandbox`

## Usage

This SDK provides three different APIs to fit your coding style. All examples below accomplish the same task - choose the one that feels best for your project.

### 1. Model-Based API (Recommended)

The Model API provides an Eloquent-like experience with full IDE autocompletion:

```php
use Ktr\BusinessCentral\Models\ApiV20\SalesOrders;
use Ktr\BusinessCentral\Models\ApiV20\SalesOrderLines;
use Ktr\BusinessCentral\Models\ApiV20\Customers;
use Ktr\BusinessCentral\Models\ApiV20\Items;

// Create a sales order
$order = SalesOrders::create([
    'customerId' => '{customer-id}',
    'shipToName' => 'John Doe',
    'shipToAddress' => '123 Main St',
    'salesOrderLines' => [
        [
            'lineType' => 'Item',
            'itemId' => '{item-id}',
            'quantity' => 5,
            'unitPrice' => 29.99
        ]
    ]
]);

// Query with filters
$orders = SalesOrders::query()
    ->where('customerId', '{customer-id}')
    ->where('totalAmount', '>', 1000)
    ->orderBy('orderDate', 'desc')
    ->take(10)
    ->get();

// Select specific fields
$customer = Customers::select('id', 'displayName', 'email', 'phoneNumber')
    ->where('number', 'C-001')
    ->first();

// Expand related data
$order = SalesOrders::select('number', 'customerId', 'totalAmount')
    ->where('number', 'SO-001')
    ->expand(SalesOrderLines::class) // Load order lines
    ->first();

// Access expanded data
echo $order->number; // SO-001
echo $order->salesOrderLines[0]->quantity; // 5

// Update a record
$customer->email = 'newemail@example.com';
$customer->phoneNumber = '+1-555-0123';
$customer->save();

// Delete a record
$order->delete();
```

### 2. HTTP Client Macro

For quick, one-off API calls without models:

```php
use Illuminate\Support\Facades\Http;

// Create a sales order
$order = Http::businessCentral()->post('salesOrders', [
    'customerId' => '{customer-id}',
    'shipToName' => 'Jane Smith',
    'salesOrderLines' => [
        [
            'lineType' => 'Item',
            'itemId' => '{item-id}',
            'quantity' => 3,
            'unitPrice' => 49.99
        ]
    ]
]);

// Get customers with query parameters
$customers = Http::businessCentral()->get('customers', [
    '$filter' => "city eq 'New York'",
    '$select' => 'id,displayName,email',
    '$top' => 20
]);

// Update with PATCH
$response = Http::businessCentral()->patch("customers('{id}')", [
    'phoneNumber' => '+1-555-9999'
]);
```

### 3. Facade API

Clean and simple for straightforward API interactions:

```php
use Ktr\BusinessCentral\Facades\BusinessCentral;

// Create a customer
$customer = BusinessCentral::post('customers', [
    'displayName' => 'Acme Corporation',
    'email' => 'contact@acme.com',
    'phoneNumber' => '+1-555-1234',
    'addressLine1' => '456 Business Ave',
    'city' => 'San Francisco',
    'state' => 'CA',
    'postalCode' => '94102'
]);

// Get all items
$items = BusinessCentral::get('items');

// Get with filters
$items = BusinessCentral::get('items', [
    '$filter' => "type eq 'Inventory' and unitPrice gt 100",
    '$orderby' => 'displayName asc'
]);
```

## Query Builder Examples

The Model API includes a powerful query builder with OData support:

### Basic Filtering

```php
use Ktr\BusinessCentral\Models\ApiV20\Items;

// Simple where clause
$items = Items::where('type', 'Inventory')->get();

// Multiple conditions
$items = Items::query()
    ->where('type', 'Inventory')
    ->where('unitPrice', '>', 50)
    ->where('blocked', false)
    ->get();

// Or conditions
$items = Items::query()
    ->where('type', 'Inventory')
    ->orWhere('type', 'Service')
    ->get();
```

### Advanced Filtering

```php
// Contains search
$customers = Customers::whereContains('displayName', 'Tech')->get();

// Starts with
$customers = Customers::whereStartsWith('displayName', 'Acme')->get();

// Ends with
$items = Items::whereEndsWith('number', '-X')->get();

// In clause
$customers = Customers::whereIn('city', ['New York', 'Los Angeles', 'Chicago'])->get();

// Date filtering
$orders = SalesOrders::whereDateTime('orderDate', '>=', now()->subDays(30))->get();

// Complex grouped conditions
$items = Items::query()
    ->where(function($query) {
        $query->where('type', 'Inventory')
              ->where('unitPrice', '>', 100);
    })
    ->orWhere(function($query) {
        $query->where('type', 'Service')
              ->where('unitCost', '<', 50);
    })
    ->get();
```

### Selecting Fields

```php
// Select specific fields only
$customers = Customers::select('id', 'displayName', 'email')
    ->get();

// Select with filtering
$items = Items::select('id', 'number', 'displayName', 'unitPrice')
    ->where('type', 'Inventory')
    ->orderBy('displayName')
    ->get();
```

### Expanding Relationships

```php
use Ktr\BusinessCentral\Models\ApiV20\SalesInvoices;
use Ktr\BusinessCentral\Models\ApiV20\SalesInvoiceLines;

// Expand related entities
$invoice = SalesInvoices::query()
    ->expand(SalesInvoiceLines::class)
    ->first();

// Multiple expands
$order = SalesOrders::query()
    ->expand(SalesOrderLines::class, 'customer', 'shipmentMethod')
    ->where('number', 'SO-001')
    ->first();
```

### Sorting

```php
// Order by single field
$customers = Customers::orderBy('displayName', 'asc')->get();

// Order by multiple fields
$items = Items::query()
    ->orderBy('type', 'asc')
    ->orderBy('unitPrice', 'desc')
    ->get();
```

### Pagination

```php
// Simple pagination
$items = Items::take(10)->get(); // First 10 items

// Skip and take
$items = Items::skip(20)->take(10)->get(); // Items 21-30

// Lazy loading for large datasets (memory efficient)
Items::lazy(1000)->each(function ($item) {
    // Process each item without loading all into memory
    echo $item->displayName;
});

// Get all records (uses lazy loading internally)
$allItems = Items::all();
```

### Finding Records

```php
// Find by ID
$customer = Customers::find('{customer-id}');

// First matching record
$customer = Customers::where('email', 'john@example.com')->first();

// Get all matching records
$customers = Customers::where('city', 'Boston')->get();

// Count records
$count = Customers::where('blocked', false)->count();
```

### Data Type Handling

The query builder intelligently handles different data types to ensure correct OData formatting:

```php
// String values - automatically quoted
Items::where('type', 'Inventory')->get();
// Generates: type eq 'Inventory'

// Numeric values - not quoted
Items::where('unitPrice', '>', 100)->get();
Items::where('quantity', 0)->get();
// Generates: unitPrice gt 100 and quantity eq 0

// Boolean values - not quoted
Items::where('blocked', false)->get();
Customers::where('taxLiable', true)->get();
// Generates: blocked eq false and taxLiable eq true

// DateTime/Carbon objects - auto-formatted to ISO 8601
Items::where('lastModifiedDateTime', '>=', Carbon::parse('2024-01-01'))->get();
// Generates: lastModifiedDateTime ge 2024-01-01T00:00:00.000Z

// Negative numbers work correctly
Customers::where('balanceDue', '<', 0)->get();
// Generates: balanceDue lt 0

// Real-world example: Items with inventory greater than zero
use Ktr\BusinessCentral\Models\ODataV4\ItemQuantities;

$items = ItemQuantities::query()
    ->where('Inventory', '>', 0)
    ->get();
// Generates: Inventory gt 0
```

**Important Notes:**
- Numeric `0` is correctly handled as integer, not string `'0'`
- Boolean `false` is distinct from integer `0`
- String values are automatically URL-encoded for OData compatibility
- DateTime objects are automatically detected and formatted correctly

## Available Models

This SDK provides access to 85+ Business Central entities. Here are the most commonly used:

### Sales & Customers
- `Customers` - Customer master data
- `SalesOrders` - Sales orders
- `SalesOrderLines` - Sales order line items
- `SalesInvoices` - Posted sales invoices
- `SalesInvoiceLines` - Sales invoice line items
- `SalesQuotes` - Sales quotes
- `SalesShipments` - Posted sales shipments
- `SalesCreditMemos` - Sales credit memos

### Purchasing & Vendors
- `Vendors` - Vendor master data
- `PurchaseOrders` - Purchase orders
- `PurchaseOrderLines` - Purchase order line items
- `PurchaseInvoices` - Posted purchase invoices
- `PurchaseInvoiceLines` - Purchase invoice line items
- `PurchaseCreditMemos` - Purchase credit memos

### Inventory & Items
- `Items` - Item master data
- `ItemCategories` - Item categories
- `UnitsOfMeasure` - Units of measure
- `InventoryPostingGroups` - Inventory posting groups

### Financial
- `Accounts` - Chart of accounts
- `GeneralLedgerEntries` - G/L entries
- `Currencies` - Currency master data
- `CurrencyExchangeRates` - Exchange rates
- `PaymentTerms` - Payment terms
- `PaymentMethods` - Payment methods
- `Dimensions` - Financial dimensions
- `BankAccounts` - Bank account master data

### Journals
- `Journals` - General journals
- `ItemJournals` - Item journals
- `CustomerPaymentJournals` - Payment journals
- `VendorPaymentJournals` - Vendor payment journals

### Reporting & Analysis
- `BalanceSheets` - Balance sheet reports
- `IncomeStatements` - Income statement reports
- `CashFlowStatements` - Cash flow reports
- `AgedAccountsReceivables` - AR aging reports
- `AgedAccountsPayables` - AP aging reports
- `TrialBalances` - Trial balance reports

### Master Data
- `Employees` - Employee records
- `Projects` - Project/job cards
- `CountriesRegions` - Countries and regions
- `TaxGroups` - Tax groups
- `TaxAreas` - Tax areas
- `Contacts` - Contact information
- `CompanyInformation` - Company details

> **Tip:** All models are located in `Ktr\BusinessCentral\Models\ApiV20` namespace and include full PHPDoc type hints for IDE autocompletion.

## API Reference

For detailed information about available fields, filters, and operations for each entity, refer to the official [Microsoft Business Central API v2.0 Documentation](https://learn.microsoft.com/en-us/dynamics365/business-central/dev-itpro/api-reference/v2.0/).

## Real-World Examples

### Example 1: Create a Complete Sales Order

```php
use Ktr\BusinessCentral\Models\ApiV20\SalesOrders;
use Ktr\BusinessCentral\Models\ApiV20\Customers;
use Ktr\BusinessCentral\Models\ApiV20\Items;

// Find customer
$customer = Customers::where('number', 'C-001')->first();

// Find items
$item1 = Items::where('number', 'ITEM-001')->first();
$item2 = Items::where('number', 'ITEM-002')->first();

// Create order with multiple lines
$order = SalesOrders::create([
    'customerId' => $customer->id,
    'customerNumber' => $customer->number,
    'shipToName' => $customer->displayName,
    'shipToAddress' => $customer->addressLine1,
    'shipToCity' => $customer->city,
    'salesOrderLines' => [
        [
            'lineType' => 'Item',
            'itemId' => $item1->id,
            'quantity' => 10,
            'unitPrice' => $item1->unitPrice,
            'description' => $item1->displayName
        ],
        [
            'lineType' => 'Item',
            'itemId' => $item2->id,
            'quantity' => 5,
            'unitPrice' => $item2->unitPrice,
            'description' => $item2->displayName
        ]
    ]
]);

echo "Order created: {$order->number}";
```

### Example 2: Generate Sales Report

```php
use Ktr\BusinessCentral\Models\ApiV20\SalesInvoices;
use Carbon\Carbon;

// Get sales for last 30 days
$startDate = Carbon::now()->subDays(30);
$invoices = SalesInvoices::query()
    ->whereDateTime('orderDate', '>=', $startDate)
    ->select('id', 'number', 'customerId', 'totalAmountIncludingTax', 'orderDate')
    ->orderBy('orderDate', 'desc')
    ->get();

$totalRevenue = $invoices->sum('totalAmountIncludingTax');
$invoiceCount = $invoices->count();

echo "Total invoices: {$invoiceCount}\n";
echo "Total revenue: $" . number_format($totalRevenue, 2);
```

### Example 3: Sync Inventory Levels

```php
use Ktr\BusinessCentral\Models\ApiV20\Items;

// Get low stock items
$lowStockItems = Items::query()
    ->where('type', 'Inventory')
    ->where('inventory', '<', 10)
    ->select('id', 'number', 'displayName', 'inventory')
    ->orderBy('inventory', 'asc')
    ->get();

foreach ($lowStockItems as $item) {
    echo "{$item->displayName}: {$item->inventory} units in stock\n";

    // Your reorder logic here
    if ($item->inventory < 5) {
        // Send notification or create purchase order
    }
}
```

### Example 4: Customer Management

```php
use Ktr\BusinessCentral\Models\ApiV20\Customers;

// Create new customer
$customer = Customers::create([
    'displayName' => 'New Customer Inc.',
    'email' => 'contact@newcustomer.com',
    'phoneNumber' => '+1-555-0100',
    'addressLine1' => '789 Customer Blvd',
    'city' => 'Seattle',
    'state' => 'WA',
    'postalCode' => '98101',
    'countryCode' => 'US',
    'currencyCode' => 'USD',
    'blocked' => false,
    'taxLiable' => true
]);

// Update customer information
$customer->email = 'newemail@newcustomer.com';
$customer->creditLimit = 50000.00;
$customer->save();

// Find customers by city
$seattleCustomers = Customers::query()
    ->where('city', 'Seattle')
    ->where('blocked', false)
    ->select('displayName', 'email', 'phoneNumber')
    ->get();
```

## Troubleshooting

### Authentication Errors

If you receive authentication errors:

1. Verify your credentials in `.env`
2. Check that your Azure app has the correct API permissions
3. Ensure your client secret hasn't expired
4. Clear the token cache: `php artisan cache:clear`

### API Limitations

- Business Central API has rate limits - implement appropriate throttling
- Some entities are read-only (e.g., Posted Invoices)
- Complex calculations may need to be done in Business Central AL code first

### OData Query Syntax

This SDK uses OData v4 query conventions. If you encounter filter errors:

- Use single quotes for string values: `where('city', 'Boston')`
- Use proper operators: `eq`, `ne`, `gt`, `ge`, `lt`, `le`
- Check the [OData documentation](https://www.odata.org/documentation/) for advanced syntax

### Getting Help

- Review the [Business Central API Documentation](https://learn.microsoft.com/en-us/dynamics365/business-central/dev-itpro/api-reference/v2.0/)
- Check existing [GitHub Issues](https://github.com/ktrfo/business-central-sdk/issues)
- Create a new issue with details about your problem

## Testing

Run the test suite:

```bash
composer test
```

Run tests with coverage:

```bash
composer test-coverage
```

Check code style:

```bash
composer pint-test
```

Fix code style:

```bash
composer pint
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for recent changes and version history.

## Contributing

We welcome contributions! Please see [CONTRIBUTING](CONTRIBUTING.md) for details on how to contribute to this project.

## Security

If you discover any security-related issues, please email john@ktr.fo instead of using the issue tracker.

## Credits

- [John Eystein Johannesen](https://github.com/ktrfo) - Creator and maintainer
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

---

**Built with ❤️ for the Laravel community**
