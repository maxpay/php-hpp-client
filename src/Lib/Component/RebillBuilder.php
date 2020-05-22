<?php

namespace Maxpay\Lib\Component;

use Maxpay\Lib\Exception\GeneralMaxpayException;
use Maxpay\Lib\Model\IdentityInterface;
use Maxpay\Lib\Model\ProductInterface;
use Maxpay\Lib\Util\CurlClient;
use Maxpay\Lib\Util\SignatureHelper;
use Maxpay\Lib\Util\Validator;
use Maxpay\Lib\Util\ValidatorInterface;
use Psr\Log\LoggerInterface;

/**
 * Class RebillBuilder
 * @package Maxpay\Lib\Component
 */
class RebillBuilder extends BaseBuilder
{
    /** @var string */
    private $action = 'api/rebilling';

    /** @var string */
    private $baseHost;

    /** @var ValidatorInterface */
    private $validator;

    /** @var IdentityInterface */
    private $identity;

    /** @var LoggerInterface */
    private $logger;

    /** @var string */
    private $billToken;

    /** @var string */
    private $userId;

    /** @var ProductInterface|null */
    private $customProduct;

    /** @var SignatureHelper */
    private $signatureHelper;

    /** @var CurlClient */
    private $client;

    /**
     * @param IdentityInterface $identity
     * @param string $billToken
     * @param string $userId
     * @param LoggerInterface $logger
     * @param string $baseHost
     */
    public function __construct(
        IdentityInterface $identity,
        string $billToken,
        string $userId,
        LoggerInterface $logger,
        string $baseHost
    ) {
        parent::__construct($logger);
        $this->validator = new Validator();
        $this->identity = $identity;
        $this->logger = $logger;
        $this->baseHost = $this->validator->validateString('baseHost', $baseHost);
        $this->billToken = $this->validator->validateString('billToken', $billToken);
        $this->userId = $this->validator->validateString('userId', $userId);
        $this->signatureHelper = new SignatureHelper();
        $this->client = new CurlClient($this->baseHost . $this->action, $logger);

        $this->logger->info('Rebill builder successfully initialized');
    }

    /**
     * Setup a custom product
     *
     * @param ProductInterface $product
     * @return RebillBuilder
     */
    public function setCustomProduct(ProductInterface $product): RebillBuilder
    {
        $this->customProduct = $product;
        $this->logger->info('Custom product successfully set');

        return $this;
    }

    /**
     * @return array
     * @throws GeneralMaxpayException
     */
    public function send(): array
    {
        $preparedData = [
            'publicKey' => $this->identity->getPublicKey(),
            'uniqueUserId' => $this->userId,
            'rebillToken' => $this->billToken,
        ];

        if (!is_null($this->productId)) {
            $preparedData['productId'] = $this->productId;
        }

        if (!is_null($this->userInfo)) {
            $preparedData = array_merge($preparedData, $this->userInfo->toHashMap());
        }

        if (is_array($this->customParams)) {
            foreach ($this->customParams as $k => $v) {
                $preparedData[$k] = $v;
            }
        }

        if (!is_null($this->customProduct)) {
            $preparedData = array_merge($preparedData, $this->customProduct->toHashMap());
        }

        $preparedData['signature'] = $this->signatureHelper->generateForArray(
            $preparedData,
            $this->identity->getPrivateKey(),
            true
        );

        return $this->prepareAnswer($this->client->send($preparedData));
    }
}
