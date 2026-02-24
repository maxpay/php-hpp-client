<?php

declare(strict_types=1);

namespace Maxpay;

use Maxpay\Lib\Component\ButtonBuilder;
use Maxpay\Lib\Component\RebillBuilder;
use Maxpay\Lib\Exception\GeneralMaxpayException;

interface ScrineyInterface
{
    /**
     * Method build integration code of pay button
     *
     * @param string $userId User Id in your system
     * @return ButtonBuilder
     * @throws GeneralMaxpayException
     */
    public function buildButton(string $userId): ButtonBuilder;

    /**
     * Method will return builder which allow to create and send rebill request
     *
     * @param string $billToken
     * @param string $userId
     * @return RebillBuilder
     * @throws GeneralMaxpayException
     */
    public function createRebillRequest(string $billToken, string $userId): RebillBuilder;

    /**
     * @param string $transactionId
     * @param string $userId
     * @return array
     * @throws GeneralMaxpayException
     */
    public function stopSubscription(string $transactionId, string $userId): array;

    /**
     * @param string $transactionId
     * @param float $amount Money amount to be refunded.
     * @param string $currencyCode Transaction currency iso code.
     * @return array
     * @throws GeneralMaxpayException
     */
    public function refund(string $transactionId, float $amount, string $currencyCode): array;

    /**
     * Method for validate callback
     *
     * @param string $data callback json string data from Maxpay.
     * @param array $headers headers from response from Maxpay.
     * @return bool
     * @throws GeneralMaxpayException
     */
    public function validateCallback(string $data, array $headers): bool;
}
