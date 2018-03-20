<?php

namespace anvein\bx_creator\creator;

use anvein\bx_creator\configurator\ModuleConfigurator;
use anvein\bx_creator\tools\FileManager;
use anvein\bx_creator\tools\Replacer;
use anvein\bx_creator\Application;


class CreatorModule extends Creator
{
    /**
     * @var ModuleConfigurator
     */
    protected $config;

    /**
     * @inheritdoc
     */
    public function run()
    {
        $app = Application::getInstance();
        $rootDir = $app->getRootDir();

        FileManager::reCreateTmpDir();
        if (!is_dir($this->config->getPath())) {
            $this->addError("Дирректории с модулями {$this->config->getPath()} не существует");

            return;
        }

        $moduleDir = "{$this->config->getVendor()}.{$this->config->getName()}";
        $modulesPath = $this->config->getPath() . '/' . $moduleDir;
        if (is_dir($modulesPath)) {
            $this->addError("Модуль {$this->config->getName()} уже существует");

            return;
        }

        $fromPath = $rootDir . '/templates/module';
        $pathTmpModule = $rootDir . FileManager::TMP_DIR . '/' . $moduleDir;
        FileManager::copyDir($fromPath, $pathTmpModule);

        $arReplaces = [
            'MODULE_CLASSNAME' => ucfirst($this->config->getVendor()) . ucfirst($this->config->getName()),
            'VENDOR_DIR' => $this->config->getVendor(),
            'MODULE_ID' => "{$this->config->getVendor()}.{$this->config->getName()}",
            'MODULE_DIR' => "{$this->config->getVendor()}.{$this->config->getName()}",
            'VENDOR_CODE' => $this->config->getVendor(),
            'MODULE_CODE' => $this->config->getName(),
            'VENDOR_URI' => $this->config->getVendorUri(),
            'MODULE_VERSION' => $this->config->getVersion(),
            'MODULE_VERSION_DATE' => $this->config->getVersionDate(),
        ];

        $arCompFiles = FileManager::getFilesRecursive($pathTmpModule);
        foreach ($arCompFiles as $file) {
            Replacer::replaceTwigis($arReplaces, $file);
            Replacer::clearTwigis($file);
        }

        FileManager::copyDir($pathTmpModule, $modulesPath);
        FileManager::reCreateTmpDir();
        return;
    }
}