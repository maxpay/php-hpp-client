<?php

namespace Maxpay\Lib\Component;

use Maxpay\Lib\Exception\GeneralMaxpayException;
use Maxpay\Lib\Exception\NotBooleanException;
use Maxpay\Lib\Model\IdentityInterface;
use Maxpay\Lib\Util\ClientInterface;
use Maxpay\Lib\Util\CurlClient;
use Maxpay\Lib\Util\SignatureHelper;
use Maxpay\Lib\Util\Validator;
use Maxpay\Lib\Util\ValidatorInterface;
use Psr\Log\LoggerInterface;

/**
 * Class RefundBuilder
 * @package Maxpay\Lib\Component
 */
class RefundBuilder extends BaseBuilder
{
    /** @var string */
    private $action = 'api/refund';

    /** @var IdentityInterface */
    private $identity;

    /** @var ValidatorInterface */
    private $validator;

    /** @var LoggerInterface */
    private $logger;

    /** @var string */
    private $baseHost;

    /** @var string */
    private $transactionId;

    /** @var ClientInterface */
    private $client;

    /** @var SignatureHelper */
    private $signatureHelper;

    /** @var bool */
    private $stopSubscriptionFlag;

    /**
     * @param IdentityInterface $identity
     * @param string $transactionId
     * @param LoggerInterface $logger
     * @param string $baseHost
     * @param bool $stopSubscriptionFlag
     * @throws GeneralMaxpayException
     */
    public function __construct(
        IdentityInterface $identity,
        $transactionId,
        LoggerInterface $logger,
        $baseHost,
        $stopSubscriptionFlag = false
    ) {
        parent::__construct($logger);

        $this->validator = new Validator();
        $this->identity = $identity;
        $this->logger = $logger;
        $this->transactionId = $this->validator->validateString('transactionId', $transactionId);
        $this->baseHost = $baseHost;
        $this->client = new CurlClient($this->baseHost . $this->action, $logger);
        $this->signatureHelper  = new SignatureHelper();

        if (!is_bool($stopSubscriptionFlag)) {
            throw new NotBooleanException('stopSubscriptionFlag');
        }
        $this->stopSubscriptionFlag = $stopSubscriptionFlag;

        $this->logger->info('Refund builder successfully initialized');
    }

    /**
     * @return array
     * @throws GeneralMaxpayException
     */
    public function send()
    {
        $data = [
            'transactionId' => $this->transactionId,
            'publicKey' => $this->identity->getPublicKey(),
            'stopLinkedSubscription' => $this->stopSubscriptionFlag ? '1' : '0'
        ];

        $data['signature'] = $this->signatureHelper->generate(
            $data,
            $this->identity->getPrivateKey(),
            true
        );

        return $this->prepareAnswer($this->client->send($data));
    }
}
