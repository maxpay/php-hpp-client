<?php

declare(strict_types=1);

namespace Maxpay\Lib\Component;

use Maxpay\Lib\Model\IdentityInterface;
use Maxpay\Lib\Util\ClientInterface;
use Maxpay\Lib\Util\CurlClient;
use Maxpay\Lib\Util\SignatureHelper;
use Maxpay\Lib\Util\Validator;
use Maxpay\Lib\Util\ValidatorInterface;
use Psr\Log\LoggerInterface;

class StopSubscriptionBuilder extends BaseBuilder
{
    private string $action = 'api/cancel';

    private IdentityInterface $identity;

    private ValidatorInterface $validator;

    private LoggerInterface $logger;

    private string $baseHost;

    private string $userId;

    private string $transactionId;

    private ClientInterface $client;

    private SignatureHelper $signatureHelper;

    public function __construct(
        IdentityInterface $identity,
        string $userId,
        string $transactionId,
        LoggerInterface $logger,
        string $baseHost
    ) {
        parent::__construct($logger);

        $this->validator = new Validator();
        $this->identity = $identity;
        $this->logger = $logger;
        $this->userId = $this->validator->validateString('userId', $userId);
        $this->transactionId = $this->validator->validateString('transactionId', $transactionId);
        $this->baseHost = $baseHost;
        $this->client = new CurlClient($this->baseHost . $this->action, $logger);
        $this->signatureHelper = new SignatureHelper();

        $this->logger->info('Stop subscription builder successfully initialized');
    }

    public function send(): array
    {
        $data = [
            'uniqueUserId' => $this->userId,
            'transactionId' => $this->transactionId,
            'publicKey' => $this->identity->getPublicKey()
        ];

        $data['signature'] = $this->signatureHelper->generateForArray(
            $data,
            $this->identity->getPrivateKey(),
            true
        );

        return $this->prepareAnswer($this->client->send($data));
    }
}
