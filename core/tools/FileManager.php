<?php

namespace anvi\bxcreator\tools;

use anvi\bxcreator\Application;
use Exception;

class FileManager
{
    private static $tmpDir = '/tmp';


    /**
     * Создает папку для временных файлов '/tmp'
     * @return bool - true, если папка создана или существует
     * @throws Exception - если папки нет и не удалось папку
     */
    public static function createTmpDir()
    {
        $app = Application::getInstance();
        $tmpDir = $app->getRootDir() . self::$tmpDir;

        if (!is_dir($tmpDir)) {
            if (!mkdir($tmpDir)) {
                throw new Exception('Не удалось создать временную папку');
            }
        }

        return true;
    }


    /**
     * Удаляет папку ддля временных файлов
    *  @return bool - true, если папки не существует или она удалена
     * @throws Exception - если папки нет и не удалось папку
     */
    public static function removeTmpDir()
    {
        $app = Application::getInstance();
        $tmpDir = $app->getRootDir() . self::$tmpDir;

        if (is_dir($tmpDir)) {
            if (!rmdir($tmpDir)) {
                throw new Exception('Не удалось удалить временную папку');
            }
        }

        return true;
    }


    /**
     * Проверяет существует ли папка $path
     * @param $path - проверяемый путь
     * @return bool - true, если существует, иначе false
     */
    public static function existDir($path)
    {
        $path = realpath($path);
        if (is_dir($path)) {
            return true;
        } else {
            return false;
        }
    }

}