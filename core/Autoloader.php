<?php

namespace Anvi\BitrixCreator;

use Exception;

class Autoloader
{
    private static $arPaths = [];

    /**
     * Запускает автозагрузку перечисленными способами
     * @throw Exception - в случае, если класс не найден
     */
    public static function init()
    {
        spl_autoload_register(function ($className) {
            $pos = strrpos($className, '\\');
            if ($pos !== false) {
                $className = substr($className, $pos + 1);
            }


            $pathToClassFile = '';
            $findClass = false;
            foreach (self::$arPaths as $path) {
                $pathToClassFile = $path . $className . '.php';
                if (file_exists($pathToClassFile)) {
                    $findClass = true;
                    break;
                }
            }

            if ($findClass) {
                require_once($pathToClassFile);
            } else {
                throw new Exception("Класс {$className} не найден");
            }
        });
    }


    /**
     * Добавление путей для поиска файлов с классами
     * @param array $paths - пути поиска файлов
     * @throws Exception - в случае, если пути поиска не заданы или один из них не существует
     */
    public static function addPath(array $paths)
    {
        if (empty($paths)) {
            throw new \Exception('Не переданы пути поиска файлов с классми $paths');
        }

        foreach ($paths as $path) {
            if (is_dir($path)) {
                self::$arPaths[] = $path;
            } else {
                throw new Exception("Путь для поиска файлов с классами не существует {$path}");
            }
        }

        self::$arPaths += $paths;
    }

}