<?php

namespace Josegus\ControlCode;

final class Verhoeff
{
    const MULTIPLIERS = [
        [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
        [1, 2, 3, 4, 0, 6, 7, 8, 9, 5],
        [2, 3, 4, 0, 1, 7, 8, 9, 5, 6],
        [3, 4, 0, 1, 2, 8, 9, 5, 6, 7],
        [4, 0, 1, 2, 3, 9, 5, 6, 7, 8],
        [5, 9, 8, 7, 6, 0, 4, 3, 2, 1],
        [6, 5, 9, 8, 7, 1, 0, 4, 3, 2],
        [7, 6, 5, 9, 8, 2, 1, 0, 4, 3],
        [8, 7, 6, 5, 9, 3, 2, 1, 0, 4],
        [9, 8, 7, 6, 5, 4, 3, 2, 1, 0],
    ];

    const PERMUTATIONS = [
        [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
        [1, 5, 7, 6, 2, 8, 3, 0, 9, 4],
        [5, 8, 0, 3, 7, 9, 6, 1, 4, 2],
        [8, 9, 1, 6, 0, 4, 3, 5, 2, 7],
        [9, 4, 5, 3, 1, 2, 6, 8, 7, 0],
        [4, 2, 8, 6, 5, 7, 3, 9, 0, 1],
        [2, 7, 9, 3, 8, 0, 6, 4, 1, 5],
        [7, 0, 4, 6, 9, 1, 3, 2, 5, 8],
    ];

    const INVERT = [0, 4, 3, 2, 1, 5, 6, 7, 8, 9];

    /**
     * Generate the Verhoeff digit for the given value
     *
     * @param string $number
     * @return string
     */
    public static function generate(string $number): string
    {
        $check = 0;

        $invertedValue = str_split(($number));
        $invertedValue = array_reverse($invertedValue);

        for ($i = 0; $i < count($invertedValue); $i++) {
            $check = self::MULTIPLIERS[$check][self::PERMUTATIONS[(($i + 1) % 8)][intval($invertedValue[$i])]];
        }

        return (string) self::INVERT[$check];
    }

    /**
     * Append many Verhoeff digits to to given value
     *
     * @param string $value
     * @param int $count
     * @return string
     */
    public static function append(string $value, int $count = 1): string
    {
        for ($i = 1; $i <= $count; $i++) {
            $value .= static::generate($value);
        }

        return $value;
    }
}
