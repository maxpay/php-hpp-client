<?php

namespace Maxpay;

use Maxpay\Lib\Component\ButtonBuilder;
use Maxpay\Lib\Component\RebillBuilder;
use Maxpay\Lib\Exception\GeneralMaxpayException;

/**
 * Interface ScrineyInterface
 * @package Maxpay
 */
interface ScrineyInterface
{
    /**
     * Method build integration code of pay button
     *
     * @param string $userId User Id in your system
     * @throws GeneralMaxpayException
     * @return ButtonBuilder
     */
    public function buildButton(string $userId);

    /**
     * Method will return builder which allow to create and send rebill request
     *
     * @param string $billToken
     * @param string $userId
     * @throws GeneralMaxpayException
     * @return RebillBuilder
     */
    public function createRebillRequest(string $billToken, string $userId);

    /**
     * @param string $transactionId
     * @param string $userId
     * @throws GeneralMaxpayException
     * @return mixed[]
     */
    public function stopSubscription(string $transactionId, string $userId);

    /**
     * @param string $transactionId
     * @param float $amount Money amount to be refunded.
     * @param string $currencyCode Transaction currency iso code.
     * @throws GeneralMaxpayException
     * @return mixed[]
     */
    public function refund(string $transactionId, float $amount, string $currencyCode);

    /**
     * Method for validate callback
     *
     * @param string $data callback json string data from Maxpay.
     * @param array $headers headers from response from Maxpay.
     * @throws GeneralMaxpayException
     * @return bool
     */
    public function validateCallback(string $data, array $headers);
}
