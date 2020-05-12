<?php

namespace Maxpay\Lib\Exception;

class InvalidStringLengthException extends GeneralMaxpayException
{
    /**
     * @param string $paramName
     * @param int $maxLength
     * @param int $minLength
     */
    public function __construct(string $paramName, int $maxLength, int $minLength)
    {
        parent::__construct(
            sprintf(
                'Passed argument `%s` exceeds allowed length, allowed length: from `%d` to `%d`',
                $paramName,
                $minLength,
                $maxLength
            )
        );
    }
}
