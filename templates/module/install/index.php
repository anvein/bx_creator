<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Application;
use CAgent;

Loc::loadMessages(__FILE__);

class {% MODULE_CLASSNAME %} extends CModule
{
    private $PARTNER_DIR;

    /**
     * Точка входа
     */
    public function __construct()
    {
        $arModuleVersion = [];
        include_once __DIR__ . '/version.php';

        $this->PARTNER_DIR = '{% VENDOR_DIR %}';
        $this->MODULE_ID = '{% MODULE_ID %}';
        $this->MODULE_NAME = Loc::getMessage('mod_{% VENDOR_CODE %}_{% MODULE_CODE %}_name');
        $this->MODULE_DESCRIPTION = Loc::getMessage('mod_{% VENDOR_CODE %}_{% MODULE_CODE %}_description');

        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];

        $this->PARTNER_NAME = Loc::getMessage('mod_{% VENDOR_CODE %}_{% MODULE_CODE %}_partner_name');
        $this->PARTNER_URI = '{% VENDOR_URI %}';
    }


    /**
     * Точка входа при установке
     *
     * @return bool - true, если установился
     */
    public function DoInstall()
    {
        if (!IsModuleInstalled($this->MODULE_ID)) {
            ModuleManager::registerModule($this->MODULE_ID);
            $this->InstallFiles();

            return true;
        }
    }

    /**
     * Точка входа при удалении
     *
     * @return bool - true, если удалился
     */
    public function DoUninstall()
    {
        if (IsModuleInstalled($this->MODULE_ID)) {
            $this->UnInstallFiles();
            ModuleManager::unregisterModule($this->MODULE_ID);

            return true;
        }
    }


    /**
     * Установка файлов и компонентов
     *
     * @return bool - true, если всё успешно установилось
     */
    public function InstallFiles()
    {
        // установка компонентов
        $path = __DIR__ . "/components/{$this->PARTNER_DIR}";
        if (is_dir($path)) {
            if ($dir = opendir($path)) {
                while (false !== $item = readdir($dir)) {
                    if ($item === '..' || $item === '.') {
                        continue;
                    }

                    CopyDirFiles(
                        "{$path}/{$item}",
                        "{$_SERVER['DOCUMENT_ROOT']}/local/components/{$this->PARTNER_DIR}/{$item}",
                        $rewrite = true,
                        $recursive = true
                    );
                }
                closedir($dir);
            }
        }

        // установка файлов админки
        $path = __DIR__ . '/admin';
        if (is_dir($path)) {
            if ($dir = opendir($path)) {
                while (false !== $item = readdir($dir)) {
                    if ($item === '..' || $item === '.') {
                        continue;
                    }

                    copy(
                        "{$path}/{$item}",
                        "{$_SERVER['DOCUMENT_ROOT']}/bitrix/admin/{$item}"
                    );
                }
                closedir($dir);
            }
        }

        return true;
    }

    /**
     * Удаление файлов и компонентов
     *
     * @return bool - true, если всё успешно удалилось
     */
    public function UnInstallFiles()
    {
        // удаление компонентов
        $path = __DIR__ . "/components/{$this->PARTNER_DIR}";
        if (is_dir($path)) {
            if ($dir = opendir($path)) {
                while (false !== $item = readdir($dir)) {
                    if ($item === '..' || $item === '.') {
                        continue;
                    }

                    $compInBitrix = "/bitrix/components/{$this->PARTNER_DIR}/{$item}";
                    if (file_exists($_SERVER['DOCUMENT_ROOT'] . $compInBitrix)) {
                        DeleteDirFilesEx($compInBitrix);
                    }

                    $compInLocal = "/local/components/{$this->PARTNER_DIR}/{$item}";
                    if (file_exists($_SERVER['DOCUMENT_ROOT'] . $compInLocal)) {
                        DeleteDirFilesEx($compInLocal);
                    }
                }
                closedir($dir);
            }
        }

        // удаление файлов админки
        $path = __DIR__ . '/admin';
        if (is_dir($path)) {
            if ($dir = opendir($path)) {
                while (false !== $item = readdir($dir)) {
                    if ($item === '..' || $item === '.') {
                        continue;
                    }

                    $pathFile = "{$_SERVER['DOCUMENT_ROOT']}/bitrix/admin/{$item}";
                    if (file_exists($pathFile)) {
                        unlink($pathFile);
                    }
                }
                closedir($dir);
            }
        }

        return true;
    }


    /**
     * Операции над БД при установке модуля
     *
     * @return bool - true, если таблицы успешно созданы
     */
    public function InstallDB()
    {
        //$connection = Application::getConnection();
        // TODO: создать таблицы в БД, если требуется

        return true;
    }

    /**
     * Операции над БД при удалении модуля
     *
     * @return bool - true, если таблицы успешно удалены
     */
    public function UnInstallDB()
    {
        // TODO: удалить таблицы из БД, если требуется
        return true;
    }


    /**
     * Создание событий при установке
     *
     * @return bool - true, если события успешно созданы
     */
    public function InstallEvents()
    {
        return true;
    }

    /**
     * Удаление событий модуля
     * @return bool - true, если события модуля успешно удалены
     */
    public function UnInstallEvents()
    {
        CAgent::RemoveModuleAgents($this->MODULE_ID);
        return true;
    }

}
