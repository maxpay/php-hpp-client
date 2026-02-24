<?php

declare(strict_types=1);

namespace Maxpay\Lib\Component;

use Maxpay\Lib\Exception\GeneralMaxpayException;
use Maxpay\Lib\Model\IdentityInterface;
use Maxpay\Lib\Util\ClientInterface;
use Maxpay\Lib\Util\CurlClient;
use Maxpay\Lib\Util\SignatureHelper;
use Maxpay\Lib\Util\Validator;
use Maxpay\Lib\Util\ValidatorInterface;
use Psr\Log\LoggerInterface;

class CancelPostTrialBuilder extends BaseBuilder
{
    private IdentityInterface $identity;

    private string $action = 'api/cancel_post_trial';

    private string $transactionId;

    private ClientInterface $client;

    private ValidatorInterface $validator;

    private SignatureHelper $signatureHelper;

    public function __construct(
        IdentityInterface $identity,
        string $transactionId,
        LoggerInterface $logger,
        string $baseHost
    ) {
        parent::__construct($logger);

        $this->validator = new Validator();
        $this->identity = $identity;
        $this->transactionId = $this->validator->validateString('transactionId', $transactionId);
        $this->signatureHelper = new SignatureHelper();

        $baseHost = $this->validator->validateString('baseHost', $baseHost);
        $this->client = new CurlClient($baseHost . $this->action, $logger);
        $logger->info('Cancel post trial builder successfully initialized');
    }

    /**
     * @return array
     * @throws GeneralMaxpayException
     */
    public function send(): array
    {
        $data = [
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
