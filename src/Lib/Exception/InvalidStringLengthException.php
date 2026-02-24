<?php

declare(strict_types=1);

namespace Maxpay\Lib\Exception;

class InvalidStringLengthException extends GeneralMaxpayException
{
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
