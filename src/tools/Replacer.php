<?php

namespace anvein\bx_creator\tools;

use Exception;

class Replacer
{
    /**
     * Заменяет в файле $path {% TWIGIS %} на текст переданные в массиве $arReplase.
     *
     * @param array $arReplace - массив с заменами ['TWIGIS' => 'заменяемое значение']
     * @param $path - путь к файлу в котором нужно произвести замену
     *
     * @throws Exception - если не удалось записать измененные данные в файл<br>
     *                   Не передан $path, не найден файл $path или он не является файлом
     */
    public static function replaceTwigis(array $arReplace = [], $path)
    {
        if (empty($path)) {
            throw new Exception('Не передан обязательный параметр $path');
        } elseif (!file_exists($path)) {
            throw new Exception("Не найден файл {$path}");
        } elseif (is_file($path)) {
            throw new Exception("$path не является файлом");
        }

        $fileContent = file_get_contents($path);
        if ($fileContent !== false) {
            foreach ($arReplace as $hashtag => $replace) {
                $fileContent = str_replace(strtoupper($hashtag), $replace, $fileContent);
            }
        }

        if (file_put_contents($path, $fileContent) === false) {
            throw new Exception("Не удалось произвести запись в файл {$path}");
        }
    }

    /**
     * Удаляет из файла $path все {% ТВИГИСЫ %}.
     *
     * @param $path - путь к файлу в котором нужно произвести удаление {% ТВИГИСОВ %}
     *
     * @throws Exception - если не удалось записать измененные данные в файл<br>
     *                   Не передан $path, не найден файл $path или он не является файлом
     */
    public static function clearTwigis($path)
    {
        if (empty($path)) {
            throw new Exception('Не передан обязательный параметр $path');
        } elseif (!file_exists($path)) {
            throw new Exception("Не найден файл {$path}");
        } elseif (is_file($path)) {
            throw new Exception("$path не является файлом");
        }

        $fileContent = file_get_contents($path);
        if ($fileContent !== false) {
            $fileContent = preg_replace('{% [a-zA-Z0-9_]* %}/i', '', $fileContent);
        }

        if (file_put_contents($path, $fileContent) === false) {
            throw new Exception("Не удалось произвести запись в файл {$path}");
        }
    }
}
