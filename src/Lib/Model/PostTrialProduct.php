<?php

namespace Maxpay\Lib\Model;

/**
 * Class PostTrialProduct
 * @package Maxpay\Lib\Model
 */
class PostTrialProduct extends BaseProduct
{
    /**
     * @param string $productId
     * @param string $productName
     * @param string $amount
     * @param string $currency
     * @param string $postTrialProductId Existing product id from Mportal
     * @param int $trialLength
     * @param string $trialPeriod
     * @param float|null $discount
     * @param string|null $discountType
     * @param string|null $productDescription
     * @throws \Maxpay\Lib\Exception\GeneralMaxpayException
     */
    public function __construct(
        $productId,
        $productName,
        $amount,
        $currency,
        $postTrialProductId,
        $trialLength,
        $trialPeriod,
        $discount = null,
        $discountType = null,
        $productDescription = null
    ) {
        parent::__construct(
            self::TYPE_TRIAL,
            $productId,
            $productName,
            $currency,
            $amount,
            $discount,
            $discountType,
            $productDescription,
            null,
            null,
            null,
            null,
            $postTrialProductId,
            $trialLength,
            $trialPeriod
        );
    }
}
