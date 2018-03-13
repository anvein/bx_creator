<?php

namespace anvein\bx_creator;

class Application
{
    /**
     * путь к composer autoload.php отсительно Application.php
     */
    const COMPOSER_PATH = '/../../../autoload.php';

    /**
     * Путь к корневой папке относительно Application.php
     */
    const ROOT_DIR = '/..';


    /**
     * @var string
     */
    private $rootDir = null;

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
        $this->rootDir = __DIR__ . self::ROOT_DIR;
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
     * Возвращает полный путь к корневой папке библиотеки
     * @return string
     */
    public function getRootDir()
    {
        return realpath($this->rootDir);
    }


    /**
     * Запуск приложения
     * TODO: надо ли?
     */
    public function run()
    {

        return true;
    }
}