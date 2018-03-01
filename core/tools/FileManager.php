<?php

namespace anvi\bxcreator\tools;

use anvi\bxcreator\Application;
use Exception;

class FileManager
{
    const TMP_DIR = '/tmp';


    /**
     * Создает папку для временных файлов '/tmp'
     * @return bool - true, если папка создана или существует
     * @throws Exception - если папки нет и не удалось папку
     */
    public static function reCreateTmpDir()
    {
        $app = Application::getInstance();
        $tmpDir = $app->getRootDir() . self::TMP_DIR;

        if (is_dir($tmpDir)) {
            self::removeDir($tmpDir);
        }

        if (!is_dir($tmpDir)) {
            if (!mkdir($tmpDir)) {
                throw new Exception('Не удалось создать временную папку');
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

    /**
     * Рекурсивно копирует папку со всеми вложениями
     * @param $from - путь откуда копировать
     * @param $to - путь куда копировать
     * @return bool - true, если всё скопировалось
     */
    public static function copyDir($from, $to)
    {
        if (is_dir($from)) {
            @mkdir($to);
            $d = dir($from);
            while (false !== ($entry = $d->read())) {
                if ($entry == "." || $entry == "..") {
                    continue;
                }
                self::copyDir("{$from}/{$entry}", "{$to}/{$entry}");
            }
            $d->close();
        } else {
            copy($from, $to);
        }

        return true;
    }

    /**
     * Рекурсивно удаляет папку
     * @param $path - путь к удаляемой папке
     * @return bool - true, если папку удалилась
     */
    public static function removeDir($path)
    {
        $dir = opendir($path);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                $full = $path . '/' . $file;
                if (is_dir($full)) {
                    self::removeDir($full);
                } else {
                    unlink($full);
                }
            }
        }
        closedir($dir);
        rmdir($path);

        return true;
    }


}