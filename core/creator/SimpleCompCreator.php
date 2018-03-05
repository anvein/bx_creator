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
        if (!parent::run()) {
            $this->addError($this->config->getErrors());
            return false;
        }

        $app = Application::getInstance();
        $rootDir = $app->getRootDir();

        FileManager::reCreateTmpDir();

        if (!is_dir($this->config->getPath())) {
            $this->addError("Дирректория с компонентами {$this->config->getPath()} не существует");
            return false;
        }

        $compPath = $this->config->getPath() . '/' . $this->config->getName();
        if (is_dir($compPath)) {
            $this->addError("Компонент {$this->config->getName()} уже существует");
            return false;
        }

        $fromPath = $rootDir . '/templates/component_simple';
        $pathTmpComp = $rootDir . FileManager::TMP_DIR . '/' . $this->config->getName();
        if (!FileManager::copyDir($fromPath, $pathTmpComp)) {
            $this->addError("Не удалось скопировать дирректорию компонента во временную дирректорию");
        }


        if (!$this->config->getCreateParams()) {
            unlink("{$pathTmpComp}/.parameters.php");
        }


        if (!$this->config->getCreateDescr()) {
            unlink("{$pathTmpComp}/.description.php");
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


        if (!FileManager::copyDir($pathTmpComp, $compPath )) {
            $this->addError("Не удалось скопировать дирректорию компонента из временной папки в {$this->config->getPath()}");
        }

        FileManager::reCreateTmpDir();

        return true;
    }

}