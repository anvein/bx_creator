<?php

namespace anvein\bx_creator\tools;

class Helper
{
    /**
     * Приводит строку к CamelCase даже с разделителями (._,-:+=).
     *
     * @param string $str - входная строка
     *
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
     *
     * @param null $str - конверируемый параметр
     *
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
     * Подготавливает строку $namespace с namespace (заменяет \ на //).
     *
     * @param null $namespace - подготавливаемая исходная строка
     *
     * @return string - подготовленная строка с namespace
     */
    public static function prepareNamescape($namespace = null)
    {
        if (empty($namespace)) {
            return '';
        }

        return $namespace = str_replace('/', '\\', $namespace);
    }

    /**
     * Конвертирует строку из консоли в массив, разбивая $str по $delimeter на элементы<br>
     * и тримит получившиеся элементы
     *
     * @param $str - входная строка
     * @param $delimiter - разделитель (по умолчанию ',')
     *
     * @return array
     */
    public static function convertStringToArray($str, $delimeter = ',')
    {
        $arStr = explode($delimeter, $str);
        foreach ($arStr as $key => $str) {
            $arStr[$key] = trim($str);
        }

        return $arStr;
    }
}
