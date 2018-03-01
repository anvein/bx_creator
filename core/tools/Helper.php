<?php

namespace anvi\bxcreator\tools;

class Helper
{
    public static function strToCamelCase($str)
    {
        if (empty($str)) {
            return '';
        }

        $arParts = explode(['_', '-', '.', ' ', ''], $str);
        var_dump($arParts);
        die();
        $result = '';
        foreach ($arParts as $ind => $part) {
            $preparePart = strtolower($part);
            if ($ind === 0) {
                $result .= $part;
            }

            $result .= ucfirst($part);
        }

        return $result;
    }



}