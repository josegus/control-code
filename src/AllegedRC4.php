<?php

namespace Josegus\ControlCode;

class AllegedRC4
{
    public static function generate($message, $key, $mode = 'hex')
    {
        $state = [];
        $x = 0;
        $y = 0;
        $index1 = 0;
        $index2 = 0;

        $hexString = '';
        $plainString = '';

        for ($i = 0; $i <= 255; $i++) {
            $state[$i] = $i;
        }

        for ($i = 0; $i <= 255; $i++) {
            $index2 = (ord($key[$index1]) + $state[$i] + $index2) % 256;
            self::swap($state[$i], $state[$index2]);
            $index1 = ($index1 + 1) % strlen($key);
        }

        for ($i = 0; $i < strlen($message); $i++) {
            $x = ($x + 1) % 256;
            $y = ($state[$x] + $y) % 256;

            # Intercambiar valores
            self::swap($state[$x], $state[$y]);

            $aux = ($state[$x] + $state[$y]) % 256;
            $ascci = ord($message[$i]) ^ $state[$aux];

            $hexString .= '-' . self::fillZero(dechex($ascci));
            $plainString .= self::fillZero(dechex($ascci));
        }

        return $mode === 'normal' ? $plainString : substr($hexString, 1);
    }

    protected static function swap(&$x, &$y)
    {
        $z = $x;
        $x = $y;
        $y = $z;
    }

    protected static function fillZero($string)
    {
        if (strlen($string) === 1) {
            return '0' . strtoupper($string);
        }

        return strtoupper($string);
    }
}
