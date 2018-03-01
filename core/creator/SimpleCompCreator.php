<?php

namespace anvi\bxcreator\creator;

use anvi\bxcreator\Application;
use anvi\bxcreator\creator\Creator;
use anvi\bxcreator\tools\FileManager;
use Bitrix\Main\IO\File;

class SimpleCompCreator extends Creator
{
    /**
     * @inheritdoc
     */
    public function run()
    {
        if (!parent::run()) {
            return false;
        }

        $app = Application::getInstance();
        $rootDir = $app->getRootDir();

        FileManager::reCreateTmpDir();

        if (!is_dir($this->config->getPath())) {
            $this->addError("Дирректории с компонентами {$this->config->getPath()} не существует");
            return false;
        }

        $compPath = $this->config->getPath() . '/' . $this->config->getName();
        if (is_dir($compPath)) {
            $this->addError("Компонент {$this->config->getName()} уже существует");
            return false;
        }

        $fromPath = $rootDir . '/templates/component_simple';
        $toTmpPath = $rootDir . FileManager::TMP_DIR . '/' . $this->config->getName();
        if (!FileManager::copyDir($fromPath, $toTmpPath)) {
            $this->addError("Не удалось скопировать дирректорию компонента во временную дирректорию");
        }













//        echo '<pre>';
//        print_r($this->getErrors());
//        echo '</pre>';
//        die();



        return true;
    }

}