<?php

namespace Maxpay\Lib\Exception;

/**
 * Class NotBooleanException
 * @package Maxpay\Lib\Exception
 */
class NotBooleanException extends GeneralMaxpayException
{
    /**
     * @param string $paramName
     */
    public function __construct(string $paramName)
    {
        parent::__construct(
            sprintf('Passed argument `%s` is not bool', $paramName)
        );
    }
}
