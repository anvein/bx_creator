<?php

namespace anvi\bxcreator\tools;

class Helper
{
    /**
     * Приводит строку к CamelCase даже с разделителями (._,-:+=)
     * @param string $str - входная строка
     * @return string - обработанная строка
     */
    public static function strToCamelCase($str)
    {
        if (empty($str)) {
            return '';
        }

        $arParts = preg_split('/[._,-:+=]/', $str);
        $result = '';
        foreach ($arParts as $ind => $part) {
            $result .= ucfirst(strtolower($part));
        }

        return $result;
    }


    /**
     * Конвертирует пустое значение - "Нет", true - "Да", массив склеивает
     * @param null $str - конверируемый параметр
     * @return string - "Нет", "Да", строка со склененными элементами массива
     */
    public static function tfConvert($str = null)
    {
        $result = '';
        if (empty($str)) {
            $result = 'Нет';
        } elseif (is_array($str)) {
            $result = implode(', ', $str);
        } else {
            $result = 'Да';
        }

        return $result;
    }


    /**
     * Подготавливает строку $namespace с namespace (заменяет \ на //)
     * @param null $namespace - подготавливаемая исходная строка
     * @return string - подготовленная строка с namespace
     */
    public static function prepareNamescape($namespace = null)
    {
        if (empty($namespace)) {
            return '';
        }

        return $namespace = str_replace('/', '\\', $namespace);

    }

}