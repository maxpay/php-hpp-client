<?php

namespace Maxpay\Lib\Util;

use Maxpay\Lib\Exception\GeneralMaxpayException;

/**
 * Interface ClientInterface
 * @package Maxpay\Lib\Util
 */
interface ClientInterface
{
    /**
     * @param mixed[] $data
     * @return mixed[]
     * @throws GeneralMaxpayException
     */
    public function send(array $data);
}
