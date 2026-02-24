<?php

declare(strict_types=1);

namespace Maxpay\Lib\Util;

use Maxpay\Lib\Exception\NotStringException;

class StringHelper
{
    public function encodeHtmlAttribute(string $string): string
    {
        if ('' === $string) {
            throw new \InvalidArgumentException('String argument cant be empty');
        }
        if (1 == preg_match('/^./su', $string) ? false : true) {
            throw new NotStringException('string');
        }

        return (string)preg_replace_callback(
            '#[^a-zA-Z0-9,\.\-_]#Su',
            function ($matches) {
                $entityMap = [
                    34 => 'quot',
                    38 => 'amp',
                    60 => 'lt',
                    62 => 'gt',
                ];
                $chr = $matches[0];
                $ord = ord($chr);

                if (($ord <= 0x1f && $chr != "\t" && $chr != "\n" && $chr != "\r") || ($ord >= 0x7f && $ord <= 0x9f)) {
                    return '&#xFFFD;';
                }

                if (strlen($chr) == 1) {
                    $hex = strtoupper(substr('00' . bin2hex($chr), -2));
                } else {
                    $chr = mb_convert_encoding($chr, 'UTF-16BE', 'UTF-8');
                    $hex = strtoupper(substr('0000' . bin2hex($chr), -4));
                }
                $int = (int)hexdec($hex);

                if (array_key_exists($int, $entityMap)) {
                    return sprintf('&%s;', $entityMap[$int]);
                }

                return sprintf('&#x%s;', $hex);
            },
            $string
        );
    }
}
