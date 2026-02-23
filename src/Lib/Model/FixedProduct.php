<?php

declare(strict_types=1);

namespace Maxpay\Lib\Model;

class FixedProduct extends BaseProduct
{
    public function __construct(
        string $productId,
        string $productName,
        float $amount,
        string $currency,
        ?float $discount = null,
        ?string $discountType = null,
        ?string $productDescription = null
    ) {
        parent::__construct(
            self::TYPE_FIXED,
            $productId,
            $productName,
            $currency,
            $amount,
            $discount,
            $discountType,
            $productDescription
        );
    }
}
