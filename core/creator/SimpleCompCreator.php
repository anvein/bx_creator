<?php

namespace anvi\bxcreator\creator;

use anvi\bxcreator\Application;
use anvi\bxcreator\tools\FileManager;
use anvi\bxcreator\tools\Helper;
use anvi\bxcreator\tools\Replacer;

class SimpleCompCreator extends Creator
{
    /**
     * @inheritdoc
     */
    public function run()
    {
        $app = Application::getInstance();
        $rootDir = $app->getRootDir();

        FileManager::reCreateTmpDir();

        if (!is_dir($this->config->getPath())) {
            $this->addError("Дирректории с компонентами {$this->config->getPath()} не существует");
            return;
        }

        $compPath = $this->config->getPath() . '/' . $this->config->getName();
        if (is_dir($compPath)) {
            $this->addError("Компонент {$this->config->getName()} уже существует");
            return;
        }

        $fromPath = $rootDir . '/templates/component_simple';
        $pathTmpComp = $rootDir . FileManager::TMP_DIR . '/' . $this->config->getName();
        FileManager::copyDir($fromPath, $pathTmpComp);


        if (!$this->config->getCreateParams()) {
            unlink("{$pathTmpComp}/.parameters.php");
        }

        if (!$this->config->getCreateDescr()) {
            unlink("{$pathTmpComp}/.description.php");
        }

        // lang-файлы
        if (!$this->config->getCreateLang()) {
            FileManager::removeDir("/lang");
        } else {
            if (!$this->config->getCreateParams()) {
                unlink("{$pathTmpComp}/lang/ru/.parameters.php");
                unlink("{$pathTmpComp}/lang/en/.parameters.php");
            }

            if (!$this->config->getCreateDescr()) {
                unlink("{$pathTmpComp}/lang/ru/.description.php");
                unlink("{$pathTmpComp}/lang/en/.description.php");
            }
        }

        $namespace = '';
        if (!empty($this->config->getNamespace())) {
            $namespace = "\nnamespace {$this->config->getNamespace()};\n";
        }

        Replacer::replaceHashstags(
            [
                '#NAMESPACE#' => $namespace,
                '#CLASS_NAME#' => Helper::strToCamelCase($this->config->getName()),
            ],
            $pathTmpComp . '/class.php'
        );

        $arCompFiles = FileManager::getFilesRecursive($pathTmpComp);
        foreach ($arCompFiles as $file) {
            Replacer::clearHashtags($file);
        }

        FileManager::copyDir($pathTmpComp, $compPath);
        FileManager::reCreateTmpDir();

        return;
    }

}