<?php

namespace Maxpay\Lib\Exception;

/**
 * Class NotStringException
 * @package Maxpay\Lib\Exception
 */
class NotStringException extends GeneralMaxpayException
{
    /**
     * @param string $paramName
     */
    public function __construct(string $paramName)
    {
        parent::__construct(
            sprintf('Passed argument `%s` is not string', $paramName)
        );
    }
}
