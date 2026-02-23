# Upgrading from 2.x to 3.0

## Requirements

- PHP 8.2 or higher
- Composer 2.0+

## Breaking Changes

### PHP Version
Minimum PHP version is now 8.2.
```bash
# Update your composer.json
composer require maxpay/hpp-client:^3.0
```

### Strict Types
All files now use `declare(strict_types=1)`. Ensure your code passes correct types.

### Type Declarations
All methods now have full type declarations:
```php
// Before (2.x) - will still work if types are correct
$product = new \Maxpay\Lib\Model\FixedProduct(
  'myProducId1',
  'Garden Table',
  100,
  'USD'
);

// After (3.x) - same code, but now type-safe
$product = new \Maxpay\Lib\Model\FixedProduct(
  'myProducId1',
  'Garden Table',
  100.0, // Note: float for amount
  'USD'
);
```

### Common Migration Issues

#### Issue 1: Passing wrong types
```php
// ❌ Will throw TypeError in 3.0
$client->createTransaction('100', 'USD');
$product = new \Maxpay\Lib\Model\FixedProduct(
  'myProducId1',
  'Garden Table',
  '100',
  'USD'
);

// ✅ Correct
$product = new \Maxpay\Lib\Model\FixedProduct(
  'myProducId1',
  'Garden Table',
  100.0,
  'USD'
);
```

#### Issue 2: Null values
```php
$scriney = new \Maxpay\Scriney('publicKey', 'privateKey');

// ❌ Will throw TypeError if method doesn't accept null
echo $scriney->buildButton('userId')->setSuccessReturnUrl(null)->buildPopup()->asString();

// ✅ Check method signature first
$button = $scriney->buildButton('userId');
if (null !== $successReturnUrl) {
    $button->setSuccessReturnUrl($successReturnUrl);
}
echo $button->buildPopup()->asString();
```

## Need Help?

- Open an issue: https://github.com/maxpay/php-hpp-client/issues
