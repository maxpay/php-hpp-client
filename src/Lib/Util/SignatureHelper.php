<?php

namespace Maxpay\Lib\Util;

/**
 * Class SignatureHelper
 * @package Maxpay\Lib\Util
 */
class SignatureHelper
{
    /**
     * Generates signature with sha256 algorithm.
     *
     * @param array $mixed
     * @param string $secret
     * @param bool $inLowercase
     * @return string
     */
    public function generate(array $mixed, $secret, $inLowercase = false)
    {
        if (count($mixed) < 1) {
            throw new \InvalidArgumentException('Data argument cant be empty');
        } else {
            $this->checkRecursive($mixed);
        }
        if (!is_string($secret)) {
            throw new \InvalidArgumentException('Secret must be string');
        }
        if (!is_bool($inLowercase)) {
            throw new \InvalidArgumentException('inLowercase must be boolean');
        }

        $signature = $this->implodeRecursive($mixed);
        $signature .= $secret;

        if ($inLowercase) {
            $signature = mb_strtolower($signature);
        }

        return hash('sha256', $signature);
    }

    /**
     * Check all array elms for scalar type recursive
     *
     * @param array $data
     */
    public function checkRecursive(array $data)
    {
        foreach ($data as $elm) {
            if (is_array($elm)) {
                $this->checkRecursive($elm);
                continue;
            }

            if (!is_scalar($elm)) {
                throw new \InvalidArgumentException('Value is not a scalar');
            }
        }
    }

    /**
     * Implode all array recursive ro string
     *
     * @param array $data
     * @param string|null $prefix
     * @return string
     */
    public function implodeRecursive(array $data, $prefix = null)
    {
        if ($prefix !== null && !is_string($prefix)) {
            throw new \InvalidArgumentException("Prefix must be string");
        }

        $out = "";
        ksort($data);

        foreach ($data as $key => $elm) {
            $key = $prefix !== null ? "{$prefix}.{$key}" : $key;

            if (is_array($elm)) {
                $out .= $this->implodeRecursive($elm, strval($key));
                continue;
            }

            $out .= "{$key}={$elm}|";
        }

        return $out;
    }
}
