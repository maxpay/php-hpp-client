<?php

declare(strict_types=1);

namespace Maxpay\Lib\Exception;

class EmptyArgumentException extends GeneralMaxpayException
{
    public function __construct(string $paramName)
    {
        parent::__construct(
            sprintf('Passed argument `%s` is empty', $paramName)
        );
    }
}
