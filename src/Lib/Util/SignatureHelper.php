<?php

namespace Maxpay\Lib\Util;

use Maxpay\Lib\Exception\EmptyArgumentException;

/**
 * Class SignatureHelper
 * @package Maxpay\Lib\Util
 */
class SignatureHelper
{
    /**
     * Generates signature with sha256 algorithm to check callback.
     *
     * @param string $data
     * @param string $secret
     * @param bool $inLowercase
     * @return string
     */
    public function generateForString(string $data, string $secret, bool $inLowercase = false): string
    {
        if (empty($data)) {
            throw new EmptyArgumentException('Data argument cant be empty');
        }
        if (empty($secret)) {
            throw new EmptyArgumentException('Secret key cant be empty');
        }

        $signature = $data . $secret;

        return $this->hashString($this->optionalToLowerString($signature, $inLowercase));
    }

    /**
     * Generates signature with sha256 algorithm.
     *
     * @param array $data
     * @param string $secret
     * @param bool $inLowercase
     * @return string
     */
    public function generateForArray(array $data, string $secret, bool $inLowercase = false): string
    {
        if (count($data) < 1) {
            throw new EmptyArgumentException('Data argument cant be empty');
        } else {
            $this->checkRecursive($data);
        }
        if (empty($secret)) {
            throw new EmptyArgumentException('Secret key cant be empty');
        }

        $signature = $this->implodeRecursive($data);
        $signature .= $secret;

        return $this->hashString($this->optionalToLowerString($signature, $inLowercase));
    }

    private function optionalToLowerString(string $string, bool $toLower): string
    {
        return $toLower ? mb_strtolower($string) : $string;
    }

    private function hashString(string $string): string
    {
        return hash('sha256', $string);
    }

    /**
     * Check all array elms for scalar type recursive
     *
     * @param array $data
     */
    public function checkRecursive(array $data): void
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
    public function implodeRecursive(array $data, string $prefix = null): string
    {
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
