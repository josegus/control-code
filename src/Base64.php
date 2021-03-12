<?php

namespace Josegus\ControlCode;

final class Base64
{
    const DICTIONARY = [
        '0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',
        'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T',
        'U', 'V', 'W', 'X', 'Y', 'Z', 'a', 'b', 'c', 'd',
        'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n',
        'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x',
        'y', 'z', '+', '/',
    ];

    public static function convert(string $value): string
    {
        $quotient = 1;
        $word = '';

        while ($quotient > 0) {
            $quotient = floor($value / 64);
            $remainder = $value % 64;
            $word = static::DICTIONARY[$remainder] . $word;
            $value = $quotient;
        }

        return $word;
    }
}
