<?php

declare(strict_types=1);

namespace Maxpay\Lib\Model;

use Maxpay\Lib\Exception\GeneralMaxpayException;
use Maxpay\Lib\Util\Validator;

class Identity implements IdentityInterface
{
    private string $publicKey;

    private string $privateKey;

    public function __construct(string $publicKey, string $privateKey)
    {
        $validator = new Validator();
        $this->publicKey = $validator->validateString('publicKey', $publicKey);
        $this->privateKey = $validator->validateString('privateKey', $privateKey);
    }

    public function getPublicKey(): string
    {
        return $this->publicKey;
    }

    public function getPrivateKey(): string
    {
        return $this->privateKey;
    }
}
