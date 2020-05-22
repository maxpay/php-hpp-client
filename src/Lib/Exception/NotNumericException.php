<?php

namespace Maxpay\Lib\Exception;

/**
 * Class NotNumericException
 * @package Maxpay\Lib\Exception
 */
class NotNumericException extends GeneralMaxpayException
{
    /**
     * @param string $paramName
     */
    public function __construct(string $paramName)
    {
        parent::__construct(
            sprintf('Passed argument `%s` is not numeric expected int or float value', $paramName)
        );
    }
}
