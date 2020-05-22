<?php

namespace Maxpay\Lib\Model;

use Maxpay\Lib\Exception\GeneralMaxpayException;
use Maxpay\Lib\Util\Validator;

/**
 * Class Identity
 * @package Maxpay\Lib\Model
 */
class Identity implements IdentityInterface
{
    /** @var string */
    private $publicKey;

    /** @var string */
    private $privateKey;

    /**
     * @param string $publicKey
     * @param string $privateKey
     * @throws GeneralMaxpayException
     */
    public function __construct(string $publicKey, string $privateKey)
    {
        $validator = new Validator();
        $this->publicKey = $validator->validateString('publicKey', $publicKey);
        $this->privateKey = $validator->validateString('privateKey', $privateKey);
    }

    /**
     * @return string
     */
    public function getPublicKey(): string
    {
        return $this->publicKey;
    }

    /**
     * @return string
     */
    public function getPrivateKey(): string
    {
        return $this->privateKey;
    }
}
