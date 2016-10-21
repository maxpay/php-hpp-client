<?php

namespace Maxpay\Lib\Model;

/**
 * Interface IdentityInterface
 * @package Maxpay\Lib\Model
 */
interface IdentityInterface
{
    /** @return string */
    public function getPublicKey();

    /** @return string */
    public function getPrivateKey();
}
