# Changelog

All notable changes to this project will be documented in this file.

## [Unreleased]

## [3.0.0] - 2026-03-01

### Breaking Changes
- **Minimum PHP version is now 8.2**
- Added `declare(strict_types=1)` to all files
- Added full type declarations (parameters and return types)
- Updated dependencies to PHP 8.2+ compatible versions

### Changed
- Updated `psr/log` to ^2.0 || ^3.0
- Updated `phpstan/phpstan` to 2.1.23
- Updated `phpunit/phpunit` to 11.5.53
- Improved type safety across entire codebase

### Migration
See [UPGRADE-3.0.md](UPGRADE-3.0.md) for detailed migration guide.

---

## [2.0.3] - Previous release

Last release supporting PHP 7.1+
See [2.x branch](https://github.com/maxpay/php-hpp-client/tree/2.x) for maintenance updates.

[Unreleased]: https://github.com/maxpay/php-hpp-client/compare/3.0.0...HEAD
[3.0.0]: https://github.com/maxpay/php-hpp-client/compare/2.0.3...3.0.0
[2.0.3]: https://github.com/maxpay/php-hpp-client/releases/tag/2.0.3
