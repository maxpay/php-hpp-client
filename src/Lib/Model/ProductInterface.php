<?php

namespace Maxpay\Lib\Model;

/**
 * Interface ProductInterface
 * @package Maxpay\Lib\Model
 */
interface ProductInterface
{
    /**
     * @return array
     */
    public function toHashMap(): array;
}
