<?php

declare(strict_types=1);

namespace Maxpay\Lib\Exception;

class NotStringException extends GeneralMaxpayException
{
    public function __construct(string $paramName)
    {
        parent::__construct(
            sprintf('Passed argument `%s` is not string', $paramName)
        );
    }
}
