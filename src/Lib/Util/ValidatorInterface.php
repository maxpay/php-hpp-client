<?php

namespace Maxpay\Lib\Util;

use Maxpay\Lib\Exception\GeneralMaxpayException;

/**
 * Interface ValidatorInterface
 * @package Maxpay\Lib\Util
 */
interface ValidatorInterface
{
    /**
     * Method will return valid value or throw exception
     *
     * @param string $paramName
     * @param string $value
     * @param int $minLength
     * @param int|null $maxLength
     * @throws GeneralMaxpayException
     * @return string
     */
    public function validateString(string $paramName, string $value, int $minLength = 1, int $maxLength = null): string;

    /**
     * @param string $paramName
     * @param float|int $value
     * @throws GeneralMaxpayException
     * @return float|int
     */
    public function validateNumeric(string $paramName, $value);

    /** @return string */
    public function getDefaultEncoding(): string;
}
