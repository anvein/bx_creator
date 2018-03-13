<?php

namespace anvein\bx_creator\tools;

use Exception;

class Replacer
{
    /**
     * Заменяет в файле $path #теги# на текст переданные в массиве $arReplase.
     *
     * @param array $arReplace - массив с заменами ['#HASHTAG#' => 'заменяемое значение']
     * @param $path - путь к файлу в котором нужно произвести замену
     *
     * @return bool - true, если всё заменилось, false, если пустой $arReplace, $path или путь $path не найден
     *
     * @throws Exception - если не удалось записать измененные данные в файл
     */
    public static function replaceHashstags(array $arReplace = [], $path)
    {
        if (empty($arReplace) || empty($path)) {
            return false;
        }

        if (!file_exists($path)) {
            return false;
        }
        $fileContent = file_get_contents($path);
        if ($fileContent === false) {
            return false;
        } else {
            foreach ($arReplace as $hashtag => $replace) {
                $fileContent = str_replace(strtoupper($hashtag), $replace, $fileContent);
            }
        }

        if (file_put_contents($path, $fileContent) === false) {
            throw new Exception("Не удалось произвести запись в файл {$path}");
        }

        return true;
    }

    /**
     * Удаляет из файла $path все #хештеги#.
     *
     * @param $path - путь к файлу в котором нужно произвести удаление #хештегов#
     *
     * @return bool - true, если #хештеги# заменились
     *
     * @throws Exception - если не удалось записать измененные данные в файл
     */
    public static function clearHashtags($path)
    {
        if (empty($path) || !file_exists($path)) {
            return false;
        }

        $fileContent = file_get_contents($path);
        if ($fileContent === false) {
            return false;
        } else {
            $fileContent = preg_replace('/#[a-zA-Z0-9_]*#/i', '', $fileContent);
        }

        if (file_put_contents($path, $fileContent) === false) {
            throw new Exception("Не удалось произвести запись в файл {$path}");
        }

        return true;
    }
}
