<?php

namespace anvi\bxcreator;

use anvi\bxcreator\Autoloader;

class Application
{
    /**
     * путь к composer autoload.php
     */
    const COMPOSER_PATH = __DIR__ . '/../../../../autoload.php';

    /**
     * @var null - объект приложения
     */
    private static $instance = null;

    /**
     * @var array - пути для автозагрузчика
     */
    protected static $autoloadPaths = [
        __DIR__ . '/..',
        __DIR__ . '/../Application',
        __DIR__ . '/../Configurator',
        __DIR__ . '/../Creator',
    ];


    /**
     * Application constructor.
     */
    public function __construct()
    {
        require_once __DIR__ . '../Autoloader.php';
        Autoloader::addPaths(static::getAutoloadPaths());
        Autoloader::init();



    }

    /**
     * Возвращает объект приложения Application
     * @return Application - объект приложения
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new Application();
        }

        return self::$instance;
    }


    /**
     * Возвращает пути автозагрузчика
     * @return array
     */
    protected static function getAutoloadPaths()
    {
        return static::$autoloadPaths;
    }


    /**
     * Запуск приложения
     */
    public function run()
    {




        return true;
    }
}