<?php

declare(strict_types=1);

namespace Maxpay\Lib\Util;

use Maxpay\Lib\Exception\EmptyArgumentException;
use Maxpay\Lib\Exception\GeneralMaxpayException;
use Maxpay\Lib\Exception\InvalidStringLengthException;

class Validator implements ValidatorInterface
{
    private string $encoding = 'utf-8';

    public function validateString(string $paramName, string $value, int $minLength = 1, ?int $maxLength = null): string
    {
        if ('' === $value) {
            throw new EmptyArgumentException($paramName);
        }
        if (!is_null($maxLength)) {
            if (mb_strlen($value, $this->encoding) > $maxLength || mb_strlen($value, $this->encoding) < $minLength) {
                throw new InvalidStringLengthException($paramName, $minLength, $maxLength);
            }
        }

        return $value;
    }

    public function validateFloat(string $paramName, float $value): float
    {
        if ($value <= 0) {
            throw new GeneralMaxpayException($paramName . 'must be greater than zero');
        }

        return $value;
    }

    public function validateInt(string $paramName, int $value): int
    {
        if ($value <= 0) {
            throw new GeneralMaxpayException($paramName . 'must be greater than zero');
        }

        return $value;
    }

    public function getDefaultEncoding(): string
    {
        return $this->encoding;
    }
}
