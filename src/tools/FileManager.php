<?php

namespace anvein\bx_creator\tools;

use anvein\bx_creator\Application;
use Exception;
use FilesystemIterator;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class FileManager
{
    const TMP_DIR = '/tmp';


    /**
     * Создает папку для временных файлов '/tmp', если она уже есть, то пересоздает её
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
    }

    /**
     * Рекурсивно удаляет папку
     * @param $path - путь к удаляемой папке
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
    }


    /**
     * Возвращает рекурсивно собранные файлы с расширениями $extensions (обертка getFilesRecursiveSub)
     * @param $path - путь к дирректории из которой надо получить рекурсивно файлы
     * @param array $extensions - массив-фильтр файлов по расширениям
     * @return array - массив с путями к файлам
     * @throws Exception - если $path не является дирректорией или не существует
     */
    public static function getFilesRecursive($path, array $extensions = [])
    {
        if (!is_dir($path)) {
            throw new Exception("Путь {$path} не является дирректорией");
        }

        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
        return static::getFilesRecursiveSub($iterator, $extensions);
    }

    /**
     * Возвращает рекурсивно собранные файлы с расширениями $extensions
     * @param $iterator - RecursiveIterator
     * @param array $extensions - массив с искомыми расширениями
     * @return array - итоговый массив с путями к файлам
     */
    public static function getFilesRecursiveSub($iterator, array $extensions = [])
    {
        $arFiles = [];
        /** @var SplFileInfo $iPath */
        foreach ($iterator as $iPath) {
            if ($iPath->getBasename() === '..') {
                continue;
            }

            if ($iPath->isDir()) {
                $bufFiles = static::getFilesRecursiveSub($iPath, $extensions);
                $arFiles = array_merge($bufFiles, $arFiles);
            } else {
                $ext = $iPath->getExtension();

                if (empty($extensions)) {
                    $arFiles[] = $iPath->getPathname();
                } elseif (array_search($ext, $extensions) !== false) {
                    $arFiles[] = $iPath->getPathname();
                }
            }
        }
        return $arFiles;
    }

}