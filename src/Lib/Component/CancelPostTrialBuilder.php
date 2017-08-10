<?php

namespace Maxpay\Lib\Component;

use Maxpay\Lib\Exception\GeneralMaxpayException;
use Maxpay\Lib\Model\IdentityInterface;
use Maxpay\Lib\Util\ClientInterface;
use Maxpay\Lib\Util\CurlClient;
use Maxpay\Lib\Util\SignatureHelper;
use Maxpay\Lib\Util\Validator;
use Psr\Log\LoggerInterface;

/**
 * Class CancelPostTrialBuilder
 * @package Maxpay\Lib\Component
 */
class CancelPostTrialBuilder extends BaseBuilder
{
    /** @var IdentityInterface */
    private $identity;

    /** @var string */
    private $transactionId;

    /** @var ClientInterface */
    private $client;

    /**
     * @param IdentityInterface $identity
     * @param string $transactionId
     * @param LoggerInterface $logger
     * @param string $baseHost
     * @throws GeneralMaxpayException
     */
    public function __construct(
        IdentityInterface $identity,
        $transactionId,
        LoggerInterface $logger,
        $baseHost
    ) {
        parent::__construct($logger);

        $validator = new Validator();
        $validator->validateString('transactionId', $transactionId);

        $this->identity = $identity;
        $this->transactionId = $transactionId;
        $this->client = new CurlClient($baseHost . 'api/cancel_post_trial', $logger);

        $logger->info('Cancel post trial builder successfully initialized');
    }

    /**
     * @return array
     * @throws GeneralMaxpayException
     */
    public function send()
    {
        $data = [
            'transactionId' => $this->transactionId,
            'publicKey' => $this->identity->getPublicKey()
        ];

        $data['signature'] = (new SignatureHelper())->generate(
            $data,
            $this->identity->getPrivateKey(),
            true
        );

        return $this->prepareAnswer($this->client->send($data));
    }
}
