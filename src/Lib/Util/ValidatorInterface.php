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
     * @return string
     * @throws GeneralMaxpayException
     */
    public function validateString(string $paramName, string $value, int $minLength = 1, int $maxLength = null): string;

    /**
     * @param string $paramName
     * @param float|int $value
     * @return float|int
     * @throws GeneralMaxpayException
     */
    public function validateNumeric(string $paramName, $value);

    /**
     * @return string
     */
    public function getDefaultEncoding(): string;
}
