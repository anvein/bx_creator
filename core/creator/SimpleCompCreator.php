<?php

namespace anvi\bxcreator\creator;

use anvi\bxcreator\Application;
use anvi\bxcreator\creator\Creator;
use anvi\bxcreator\tools\FileManager;

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

        FileManager::createTmpDir();

        if (is_dir($this->config->getPath())) {
            $this->addError("Папка {$this->config->getPath()} не существует");
            return false;
        }






//        echo '<pre>';
//        print_r($this->getErrors());
//        echo '</pre>';
//        die();



        return true;
    }

}