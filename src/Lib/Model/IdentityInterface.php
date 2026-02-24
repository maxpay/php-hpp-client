<?php

declare(strict_types=1);

namespace Maxpay\Lib\Model;

interface IdentityInterface
{
    public function getPublicKey(): string;

    public function getPrivateKey(): string;
}
