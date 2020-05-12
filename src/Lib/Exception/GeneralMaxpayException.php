<?php

namespace Maxpay\Lib\Exception;

/**
 * General Maxpay exception
 *
 * Class GeneralMaxpayException
 * @package Maxpay\Lib\Exception
 */
class GeneralMaxpayException extends \Exception
{
    public function __construct(string $message = "", \Exception $previous = null, int $code = 0)
    {
        \Exception::__construct($message, $code, $previous);
    }
}
