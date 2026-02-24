<?php

declare(strict_types=1);

namespace Maxpay\Lib\Component;

use Maxpay\Lib\Model\IdentityInterface;
use Maxpay\Lib\Model\ProductInterface;
use Maxpay\Lib\Util\CurlClient;
use Maxpay\Lib\Util\SignatureHelper;
use Maxpay\Lib\Util\Validator;
use Maxpay\Lib\Util\ValidatorInterface;
use Psr\Log\LoggerInterface;

class RebillBuilder extends BaseBuilder
{
    private string $action = 'api/rebilling';

    private string $baseHost;

    private ValidatorInterface $validator;

    private IdentityInterface $identity;

    private LoggerInterface $logger;

    private string $billToken;

    private string $userId;

    private ?ProductInterface $customProduct = null;

    private SignatureHelper $signatureHelper;

    private CurlClient $client;

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

        if (count($this->customParams) > 0) {
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
