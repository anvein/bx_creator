<?php

namespace anvi\bxcreator;

class Application
{
    /**
     * путь к composer autoload.php
     * // TODO: нужен ли?
     */
    const COMPOSER_PATH = '/../../../autoload.php';

    /**
     * @var null - объект приложения
     */
    private static $instance = null;

    /**
     * Application constructor.
     */
    public function __construct()
    {
        require_once __DIR__ . self::COMPOSER_PATH;
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
     * Запуск приложения
     */
    public function run()
    {

        return true;
    }
}