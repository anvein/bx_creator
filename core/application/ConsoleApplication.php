<?php

namespace anvi\bxcreator;

use anvi\bxcreator\Application;

class ConsoleApplication extends Application
{
    /**
     * @inheritdoc
     */
    protected static $autoloadPaths = [
        __DIR__ . '/../Command',
    ];

    /**
     * Возвращает пути автозагрузчика
     * @return array
     */
    protected static function getAutoloadPaths()
    {
        $arPaths = static::$autoloadPaths;
        $arPaths += parent::getAutoloadPaths();

        return $arPaths;
    }

}