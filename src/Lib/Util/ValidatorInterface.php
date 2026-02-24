<?php

declare(strict_types=1);

namespace Maxpay\Lib\Util;

use Maxpay\Lib\Exception\GeneralMaxpayException;

interface ValidatorInterface
{
    public function validateString(
        string $paramName,
        string $value,
        int $minLength = 1,
        ?int $maxLength = null
    ): string;

    public function validateFloat(string $paramName, float $value): float;

    public function validateInt(string $paramName, int $value): int;

    public function getDefaultEncoding(): string;
}
