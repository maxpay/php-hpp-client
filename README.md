You can sign up for a maxpay account at https://my.maxpay.com/

# Requirements

PHP 7.1 and later.

# Composer

You can install the bindings via [Composer](http://getcomposer.org/). Run the following command:

```bash
composer require maxpay/hpp-client
```

To use the bindings, use Composer's [autoload](https://getcomposer.org/doc/00-intro.md#autoloading):

```php
require_once('vendor/autoload.php');
```

# Getting Started

## Simple payment form:

```php
$scriney = new \Maxpay\Scriney('publicKey', 'privateKey');
echo $scriney->buildButton('userId')->buildPopup();
```

## Payment form with pre selected product:

```php
$scriney = new \Maxpay\Scriney('publicKey', 'privateKey');
echo $scriney->buildButton('userId')->setProductId('productIdInMportal')->buildPopup();
```

## Payment form with filled user information:

```php
$scriney = new \Maxpay\Scriney('publicKey', 'privateKey');
echo $scriney->buildButton('userId')->setUserInfo(
          new \Maxpay\Lib\Model\UserInfo(
              'example@example.com',
              'John',
              'Anderson',
              'USA',
              'Los angeles',
              '90217',
              '2896 Providence Lane',
              '6267746913'
          )
      )->buildPopup();
```

## Payment form with custom return urls:

```php
$scriney = new \Maxpay\Scriney('publicKey', 'privateKey');
echo $scriney->buildButton('userId')->setSuccessReturnUrl(
        "https://example.com/success"
    )->setDeclineReturnUrl(
        "https://example.com/decline"
    )->setBackUrl(
        "https://example.com/back"
    )->buildPopup();
```

## Payment form with custom params, params will be returned in callback:

```php
$scriney = new \Maxpay\Scriney('publicKey', 'privateKey');
echo $scriney->buildButton('userId')->setCustomParams(
        [
            'custom_param1' => 'param value 1',
            'custom_param2' => 'param value 2'
        ]
    )->buildPopup();
```

## Payment form with dynamic products:

```php
$scriney = new \Maxpay\Scriney('publicKey', 'privateKey');
echo $scriney->buildButton('userId')->setCustomProducts(
          [
              new \Maxpay\Lib\Model\FixedProduct(
                  'myProducId1',
                  'Garden Table',
                  198.98,
                  'USD',
                  null,
                  null,
                  'Magic Garden Table & Set of 2 Chairs'
              ),
              new \Maxpay\Lib\Model\FixedProduct(
                  'myProducId2',
                  'Chair',
                  110.50,
                  'USD',
                  null,
                  null,
                  'Magic Garden Rocking Chair'
              )
          ]
      )->buildPopup();
```
## Valdiate callback data:
$data - json string of callback response data read from file_get_contents('php://input');
$headers - array of callback response headers
```php
$scriney = new \Maxpay\Scriney('publicKey', 'privateKey');
if ($scriney->validateCallback($data, $headers)) {
    echo 'callback data is valid';
} else {
    echo 'invalid callback data';
}
```
# Api

## Rebilling api
### Create simple rebilling request by existing product
```php
$scriney = new \Maxpay\Scriney('publicKey', 'privateKey');

try {
    $result = $scriney->createRebillRequest(
        '569ded06-c1c0-4ecb-9b9c-59c1630f6969',
        'userId'
    )->setProductId(
        'p_3ba675d110'
    )->setUserInfo(
         new \Maxpay\Lib\Model\UserInfo(
             'example@example.com',
             'John',
             'Anderson',
             'USA',
             'Los angeles',
             '90217',
             '2896 Providence Lane',
             '6267746913'
         )
     )->send();
} catch (\Maxpay\Lib\Exception\GeneralMaxpayException $e) {
    //
}

if ($scriney->validateApiResult($result)) {
    //Api result is valid
}

```

### Create rebilling request with custom product and custom params
```php
$scriney = new \Maxpay\Scriney('publicKey', 'privateKey');

try {
    $result = $scriney->createRebillRequest(
        '569ded06-c1c0-4ecb-9b9c-59c1630f6969',
        'userId'
    )->setUserInfo(
         new \Maxpay\Lib\Model\UserInfo(
             'example@example.com',
             'John',
             'Anderson',
             'USA',
             'Los angeles',
             '90217',
             '2896 Providence Lane',
             '6267746913'
         )
     )->setCustomProduct(
        new \Maxpay\Lib\Model\FixedProduct(
            'myProducId1',
            'Garden Table',
            198.98,
            'USD',
            null,
            null,
            'Magic Garden Table & Set of 2 Chairs'
        )
     )->setCustomParams(
        [
            'custom_param_name1' => 'value 1',
            'custom_param_name2' => 'value 2'
        ]
     )->send();
} catch (\Maxpay\Lib\Exception\GeneralMaxpayException $e) {
    //
}

if ($scriney->validateApiResult($result)) {
    //Api result is valid
    //Api result example:
    /*
        Array
        (
            [transactionId] => hppR1463555724.2658mId548aId9
            [uniqueUserId] => userId
            [totalAmount] => 198.98
            [currency] => USD
            [transactionType] => SALE
            [status] => success
            [message] => Transaction processed successfully
            [code] => 0
            [productList] => Array
                (
                    [0] => Array
                        (
                            [productId] => myProducId1
                            [name] => Garden Table
                            [amount] => 198.98
                            [currency] => USD
                        )

                )

            [customParameters] => Array
                (
                    [custom_param_name1] => value 1
                    [custom_param_name2] => value 2
                )

            [checkSum] => 285e7c239dd8945b49157e36c0000692932e3dca04e8581ffa43abecef260beb
        )
    */
}

```

## Cancel subscription api
```php

$scriney = new \Maxpay\Scriney('publicKey', 'privateKey');
$result = $scriney->stopSubscription('hppR1463555724.2658mId548aId9', 'userId');
if ($scriney->validateApiResult($result)) {
    //Api result is valid
}
```

## Cancel post trial product api
```php

$scriney = new \Maxpay\Scriney('publicKey', 'privateKey');
$result = $scriney->cancelPostTrial('hppR1463555724.2658mId548aId9');
if ($scriney->validateApiResult($result)) {
    //Api result is valid
}
```

## Full/partial Refund api
```php
$scriney = new \Maxpay\Scriney('publicKey', 'privateKey');
$result = $scriney->refund('hppR1463555724.2658mId548aId9', 123.24, 'USD');
if ($scriney->validateApiResult($result)) {
    //Api result is valid
}
//Api result example
/*
  Array
  (
      [message] => Refund processed successfully, but all subscriptions already stopped.
      [status] => Success
      [transactionId] => hppAR1468587714.1807mId548aId9
      [checkSum] => ee7ecd3b401735c40c5da4c3dcaf38952df5721d9626402cbbc1ccadd65b5616
  )
*/
```
# Development

Install dependencies:

``` bash
composer install
```
