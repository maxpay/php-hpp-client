<?php

declare(strict_types=1);

namespace Maxpay\Lib\Model;

use Maxpay\Lib\Exception\GeneralMaxpayException;
use Maxpay\Lib\Util\Validator;

class BaseProduct implements ProductInterface
{
    const TYPE_SUBSCRIPTION = 'subscriptionProduct';
    const TYPE_FIXED = 'fixedProduct';
    const TYPE_TRIAL = 'trialProduct';

    const DISCOUNT_AMOUNT = 'amountOff';
    const DISCOUNT_PERCENT = 'percentOff';

    const SUBSCRIPTION_24H = '24H';
    const SUBSCRIPTION_7D = '7D';
    const SUBSCRIPTION_30D = '30D';
    const SUBSCRIPTION_365D = '365D';

    const TRIAL_24H = '24H';
    const TRIAL_7D = '7D';
    const TRIAL_30D = '30D';
    const TRIAL_365D = '365D';

    private string $type;

    private string $productId;

    private string $productName;

    private ?string $productDescription;

    private string $currency;

    private float $amount;

    private ?float $discount = null;

    private ?string $discountType = null;

    private ?int $subscriptionLength = null;

    private ?string $subscriptionPeriod = null;

    private ?float $subscriptionEndDate = null;

    private ?int $subscriptionBillingCycles = null;

    private ?string $postTrialProductId = null;

    private ?int $postTrialLength = null;

    private ?string $postTrialPeriod = null;

    public function __construct(
        string $type,
        string $productId,
        string $productName,
        string $currency,
        float $amount,
        ?float $discount = null,
        ?string $discountType = null,
        ?string $productDescription = null,
        ?int $subscriptionLength = null,
        ?string $subscriptionPeriod = null,
        ?int $subscriptionBillingCycles = null,
        ?float $subscriptionEndDate = null,
        ?string $postTrialProductId = null,
        ?int $postTrialLength = null,
        ?string $postTrialPeriod = null
    ) {
        $validator = new Validator();
        $type = $validator->validateString('productType', $type);
        if (!in_array($type, [self::TYPE_SUBSCRIPTION, self::TYPE_TRIAL, self::TYPE_FIXED])) {
            throw new GeneralMaxpayException('Invalid product type given');
        }

        $this->type = $type;
        $this->productId = $validator->validateString('productId', $productId);
        $this->productName = $validator->validateString('productName', $productName);
        $this->currency = $validator->validateString('currency', $currency, 3, 3);
        $this->amount = $validator->validateFloat('amount', $amount);
        $this->productDescription = is_null($productDescription) ?
            null :
            $validator->validateString('productDescription', $productDescription);

        $this->discount = is_null($discount) ? null : $validator->validateFloat('discount', $discount);

        if (!is_null($discountType)) {
            $discountType = $validator->validateString('discountType', $discountType);
            if (!in_array($discountType, [self::DISCOUNT_AMOUNT, self::DISCOUNT_PERCENT])) {
                throw new GeneralMaxpayException('Invalid discount type given');
            }
            $this->discountType = $discountType;
        }

        if (!is_null($subscriptionLength) && $this->type === self::TYPE_SUBSCRIPTION) {
            $this->subscriptionLength = $validator->validateInt('subscriptionLength', $subscriptionLength);
            $subscriptionPeriod = $validator->validateString('subscriptionPeriod', $subscriptionPeriod ?? '');

            if (!in_array(
                $subscriptionPeriod,
                [
                    self::SUBSCRIPTION_24H,
                    self::SUBSCRIPTION_7D,
                    self::SUBSCRIPTION_30D,
                    self::SUBSCRIPTION_365D
                ]
            )) {
                throw new GeneralMaxpayException('Invalid subscription period given');
            }

            $this->subscriptionPeriod = $subscriptionPeriod;
            $this->subscriptionBillingCycles = is_null($subscriptionBillingCycles) ?
                null :
                $validator->validateInt('subscriptionBillingCycles', $subscriptionBillingCycles);

            $this->subscriptionEndDate = is_null($subscriptionEndDate) ?
                null :
                $validator->validateFloat('subscriptionEndDate', $subscriptionEndDate);
        }

        if (!is_null($postTrialProductId) && $this->type === self::TYPE_TRIAL) {
            $this->postTrialProductId = $validator->validateString('postTrialProductId', $postTrialProductId);
            $this->postTrialLength = $validator->validateInt('postTrialLength', $postTrialLength ?? 0);
            $postTrialPeriod = $validator->validateString('postTrialPeriod', $postTrialPeriod ?? '');
            if (!in_array($postTrialPeriod, [self::TRIAL_24H, self::TRIAL_7D, self::TRIAL_30D, self::TRIAL_365D])) {
                throw new GeneralMaxpayException('Invalid post trial period given');
            }
            $this->postTrialPeriod = $postTrialPeriod;
        }
    }

    public function toHashMap(): array
    {
        $result = [
            'productType' => $this->type,
            'productId' => $this->productId,
            'productName' => $this->productName,
            'currency' => $this->currency,
            'amount' => $this->amount
        ];

        if (!is_null($this->discount) && !is_null($this->discountType)) {
            $result['discount'] = $this->discount;
            $result['discountType'] = $this->discountType;
        }

        if (!is_null($this->productDescription)) {
            $result['productDescription'] = $this->productDescription;
        }

        //Subscription section
        if (!is_null($this->subscriptionLength)) {
            $result['subscriptionLength'] = intval($this->subscriptionLength);
        }
        if (!is_null($this->subscriptionPeriod)) {
            $result['subscriptionPeriod'] = $this->subscriptionPeriod;
        }
        if (!is_null($this->subscriptionBillingCycles)) {
            $result['subscriptionBillingCycles'] = intval($this->subscriptionBillingCycles);
        }
        if (!is_null($this->subscriptionEndDate)) {
            $result['subscriptionEndDate'] = $this->subscriptionEndDate;
        }

        //Post trial section
        if (!is_null($this->postTrialProductId)) {
            $result['postTrialProductId'] = $this->postTrialProductId;
        }
        if (!is_null($this->postTrialLength)) {
            $result['trialLength'] = intval($this->postTrialLength);
        }
        if (!is_null($this->postTrialPeriod)) {
            $result['trialPeriod'] = $this->postTrialPeriod;
        }

        return $result;
    }
}
