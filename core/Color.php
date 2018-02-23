<?php

namespace anvi\bxcreator;

class Color
{
    private static $colors = [
        'red' => "\033[31m",
        'green' => "\033[32m",
        'blue' => "\033[34m",
        'yellow' => "\x1b[33m",
        'white' => "\033[37m",
    ];
    /**
     * Красит (ap - apply, применяет) цвет к строке для консоли
     * @param        $str - исходная строка
     * @param string $color - название цвета (или первая буква)
     * @return string - раскрашенная строка
     */
    public static function ap($str, $color = 'white')
    {
        if (empty($str)) {
            return '';
        }

        $colorCode = null;
        $color = strtolower($color);
        if (isset(self::$colors[$color])) {
            $colorCode = self::$colors[$color];
        } else {
            foreach (self::$colors as $key => $clr) {
                if (substr($key, 0, 1) === $color) {
                    $colorCode = self::$colors[$key];
                    break;
                }
            }
        }

        if (empty($colorCode)) {
            $colorCode = self::$colors['white'];
        }

        $defaultColorCode = self::$colors['white'];
        return $colorCode . $str . $defaultColorCode;
    }

}