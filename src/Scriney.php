<?php

namespace Maxpay;

use Maxpay\Lib\Component\ButtonBuilder;
use Maxpay\Lib\Component\RebillBuilder;
use Maxpay\Lib\Component\RefundBuilder;
use Maxpay\Lib\Component\StopSubscriptionBuilder;
use Maxpay\Lib\Exception\GeneralMaxpayException;
use Maxpay\Lib\Model\Identity;
use Maxpay\Lib\Model\IdentityInterface;
use Maxpay\Lib\Util\SignatureHelper;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Class Scriney
 * @package Maxpay
 */
class Scriney implements ScrineyInterface
{
    const HOST_BASE = 'https://hpp.maxpay.com/';

    /** @var LoggerInterface */
    private $logger;

    /** @var IdentityInterface */
    private $identity;

    /**
     * @param string $publicKey Available in your Mportal
     * @param string $privateKey Available in your Mportal
     * @param LoggerInterface|null $logger Any PSR-3 logger
     * @throws GeneralMaxpayException
     */
    public function __construct($publicKey, $privateKey, LoggerInterface $logger = null)
    {
        $this->logger = is_null($logger) ? new NullLogger() : $logger;

        try {
            $this->identity = new Identity($publicKey, $privateKey);
        } catch (GeneralMaxpayException $e) {
            $this->logger->error(
                'Wrong init param',
                [
                    'exception' => $e,
                ]
            );

            throw $e;
        } catch (\Exception $ex) {
            $this->logger->error(
                'Initialization failed',
                [
                    'exception' => $ex,
                ]
            );

            throw new GeneralMaxpayException($ex->getMessage(), $ex);
        }

        $this->logger->info(
            'Scriney object successfully built',
            []
        );
    }

    /**
     * @param string $billToken
     * @param string $userId
     * @throws GeneralMaxpayException
     * @return RebillBuilder
     */
    public function createRebillRequest($billToken, $userId)
    {
        try {
            return new RebillBuilder($this->identity, $billToken, $userId, $this->logger, self::HOST_BASE);
        } catch (GeneralMaxpayException $e) {
            $this->logger->error(
                "Can't init rebill builder",
                [
                    'exception' => $e,
                ]
            );

            throw $e;
        } catch (\Exception $ex) {
            $this->logger->error(
                'Rebill builder initialization failed',
                [
                    'exception' => $ex,
                ]
            );

            throw new GeneralMaxpayException($ex->getMessage(), $ex);
        }
    }

    /**
     * Method build integration code of pay button
     *
     * @param string $userId User Id in your system
     * @throws GeneralMaxpayException
     * @return ButtonBuilder
     */
    public function buildButton($userId)
    {
        try {
            return new ButtonBuilder($this->identity, $userId, $this->logger, self::HOST_BASE);
        } catch (GeneralMaxpayException $e) {
            $this->logger->error(
                "Can't init button builder",
                [
                    'exception' => $e,
                ]
            );

            throw $e;
        } catch (\Exception $ex) {
            $this->logger->error(
                'Page builder initialization failed',
                [
                    'exception' => $ex,
                ]
            );

            throw new GeneralMaxpayException($ex->getMessage(), $ex);
        }
    }

    /**
     * Method stop subscription
     *
     * @param string $transactionId
     * @param string $userId
     * @throws GeneralMaxpayException
     * @return mixed[]
     */
    public function stopSubscription($transactionId, $userId)
    {
        try {
            $subscriptionBuilder =  new StopSubscriptionBuilder(
                $this->identity,
                $userId,
                $transactionId,
                $this->logger,
                self::HOST_BASE
            );

            return $subscriptionBuilder->send();
        } catch (GeneralMaxpayException $e) {
            $this->logger->error(
                "Can't init stop subscription builder",
                [
                    'exception' => $e,
                ]
            );

            throw $e;
        } catch (\Exception $ex) {
            $this->logger->error(
                'Stop subscription builder initialization failed',
                [
                    'exception' => $ex,
                ]
            );

            throw new GeneralMaxpayException($ex->getMessage(), $ex);
        }
    }

    /**
     * Method refunds transaction
     *
     * @param string $transactionId
     * @throws GeneralMaxpayException
     * @return mixed[]
     */
    public function refund($transactionId)
    {
        try {
            $refundBuilder =  new RefundBuilder(
                $this->identity,
                $transactionId,
                $this->logger,
                self::HOST_BASE
            );

            return $refundBuilder->send();
        } catch (GeneralMaxpayException $e) {
            $this->logger->error(
                "Can't init refund builder",
                [
                    'exception' => $e,
                ]
            );

            throw $e;
        } catch (\Exception $ex) {
            $this->logger->error(
                'Refund builder initialization failed',
                [
                    'exception' => $ex,
                ]
            );

            throw new GeneralMaxpayException($ex->getMessage(), $ex);
        }
    }

    /**
     * Method for validate callback
     *
     * @param array $data callback data from Maxpay
     * @throws GeneralMaxpayException
     * @return bool
     */
    public function validateCallback(array $data)
    {
        try {
            $signatureHelper = new SignatureHelper();
            $checkSum = null;
            $callbackData = [];
            foreach ($data as $k => $v) {
                if ($k !== 'checkSum') {
                    $callbackData[$k] = $v;
                } else {
                    $checkSum = $v;
                }
            }

            if (is_null($checkSum)) {
                $this->logger->error(
                    'checkSum field is required in callback data',
                    []
                );
                return false;
            }

            if ($checkSum !== $signatureHelper->generate($callbackData, $this->identity->getPrivateKey())) {
                $this->logger->error(
                    'Check sum validation failure',
                    []
                );
                return false;
            }

            $this->logger->info(
                'Callback data valid',
                []
            );
            return true;
        } catch (\Exception $ex) {
            $this->logger->error(
                'Callback validation failed',
                [
                    'exception' => $ex,
                ]
            );

            throw new GeneralMaxpayException($ex->getMessage(), $ex);
        }
    }
}
