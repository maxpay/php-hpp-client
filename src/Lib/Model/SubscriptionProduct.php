<?php

namespace Maxpay\Lib\Model;

/**
 * Class SubscriptionProduct
 * @package Maxpay\Lib\Model
 */
class SubscriptionProduct extends BaseProduct
{
    /**
     * @param string $productId
     * @param string $productName
     * @param string $amount
     * @param string $currency
     * @param int $subscriptionLength
     * @param string $subscriptionPeriod  Allowed types - 24H, 7D, 30D, 365D
     * @param int|null $subscriptionBillingCycles
     * @param float|null $subscriptionEndDate
     * @param int|float|null $discount
     * @param string|null $discountType
     * @param string|null $productDescription
     * @throws \Maxpay\Lib\Exception\GeneralMaxpayException
     */
    public function __construct(
        $productId,
        $productName,
        $amount,
        $currency,
        $subscriptionLength,
        $subscriptionPeriod,
        $subscriptionBillingCycles = null,
        $subscriptionEndDate = null,
        $discount = null,
        $discountType = null,
        $productDescription = null
    ) {
        parent::__construct(
            self::TYPE_SUBSCRIPTION,
            $productId,
            $productName,
            $currency,
            $amount,
            $discount,
            $discountType,
            $productDescription,
            $subscriptionLength,
            $subscriptionPeriod,
            $subscriptionBillingCycles,
            $subscriptionEndDate
        );
    }
}
