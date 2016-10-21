<?php

namespace Maxpay\Lib\Util;

use Maxpay\Lib\Exception\EmptyArgumentException;
use Maxpay\Lib\Exception\GeneralMaxpayException;
use Maxpay\Lib\Exception\InvalidEncodingException;
use Maxpay\Lib\Exception\InvalidStringLengthException;
use Maxpay\Lib\Exception\NotNumericException;
use Maxpay\Lib\Exception\NotStringException;

/**
 * Class Validator
 * @package Maxpay\Lib\Util
 */
class Validator implements ValidatorInterface
{
    /** @var string */
    private $encoding = 'utf-8';

    /**
     * @param string $paramName
     * @param string $value
     * @param int $minLength
     * @param int|null $maxLength
     * @throws GeneralMaxpayException
     * @return string
     */
    public function validateString($paramName, $value, $minLength = 1, $maxLength = null)
    {
        if (!is_string($value)) {
            throw new NotStringException($paramName);
        }
        if (mb_strlen($value, $this->encoding) === 0) {
            throw new EmptyArgumentException($paramName);
        }
        if (!is_null($maxLength) && is_int($maxLength)) {
            if (mb_strlen($value, $this->encoding) > $maxLength ||  mb_strlen($value, $this->encoding) < $minLength) {
                throw new InvalidStringLengthException($paramName, $minLength, $maxLength);
            }
        }

        return $value;
    }

    /**
     * @param string $paramName
     * @param float|int $value
     * @throws GeneralMaxpayException
     * @return float|int
     */
    public function validateNumeric($paramName, $value)
    {
        if (!is_int($value) && !is_float($value)) {
            throw new NotNumericException($paramName);
        }
        if ($value <= 0) {
            throw new GeneralMaxpayException($paramName . 'must be greater than zero');
        }

        return $value;
    }

    /** @return string */
    public function getDefaultEncoding()
    {
        return $this->encoding;
    }
}
