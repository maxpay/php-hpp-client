<?php

namespace Maxpay\Lib\Model;

/**
 * Interface UserInfoInterface
 * @package Maxpay\Lib\Model
 */
interface UserInfoInterface
{
    /**
     * @return array
     */
    public function toHashMap(): array;
}
