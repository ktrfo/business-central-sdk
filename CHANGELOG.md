# Changelog

All notable changes to `business-central-sdk` will be documented in this file

## 1.0.1 - 2025-10-23

### Changed
- 📦 **Minimum Laravel version**: Dropped Laravel 9 and 10 support, now requires `^11.0|^12.0`
- 🔒 **Security**: Resolves Dependabot alert for outdated Laravel versions (< 10.48.29)

This is a non-breaking change for existing users as Laravel 11+ was already the recommended version.

## 1.0.0 - 2025-10-23

### Added
- ✅ **Laravel Pint** for code formatting (following Laravel standards)
- ✅ **Pest PHP** for modern testing framework
- ✅ **Comprehensive test suite** with 23 tests covering Builder, filters, and core functionality
- ✅ **Composer scripts**: `test`, `test-coverage`, `pint`, `pint-test`
- ✅ **PHPUnit/Pest configuration** with proper test environment
- ✅ **Enhanced composer.json** with modern dev dependencies
- ✅ **Auto-detection of DateTime objects** in `where()` method for seamless date filtering
- ✅ **Professional README.md** with comprehensive examples and clear structure

### Changed
- 🎨 **Code formatted** - All 105 PHP files formatted with Laravel Pint (102 style issues fixed)
- 📦 **Updated dependencies**:
  - Orchestra Testbench: `^6.0` → `^9.0|^10.0`
  - Replaced PHPUnit with Pest `^3.0`
  - PHP support: `^8.2` → `^8.2|^8.3`
- 📝 **Improved package description** with better keywords for discoverability
- 📝 **Complete README.md rewrite** with extensive examples, use cases, and documentation

### Removed
- ❌ `.styleci.yml` (replaced by Laravel Pint)

### Fixed
- 🔒 **Security**: Audit passed - no vulnerabilities
- 🎨 **Code quality**: All code now follows PSR-12 and Laravel standards
- 🐛 **Builder filters**: Fixed critical issues in `HasFilters` trait:
  - Fixed `filter()` method parameter syntax
  - Fixed `whereIn()` to properly store `$before` parameter for OR conditions
  - Fixed `whereGroup()` to create new Builder instances correctly
  - Fixed `whereNot()` to properly implement negation logic
  - Fixed `formatValue()` to correctly handle numeric, boolean, and string values
  - Fixed `where()` with Closure to preserve AND/OR operators
  - Added automatic DateTime detection in `where()` method

### Developer Experience
- ✅ Modern testing setup ready for expansion
- ✅ Automated code formatting with `composer pint`
- ✅ Professional package structure following Laravel conventions
- ✅ `.gitignore` properly configured for package development

## 0.5.0 - 2024-06-12

- initial release
