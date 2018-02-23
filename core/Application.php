<?php

namespace Anvi\BitrixCreator;

class Application
{
    static private $instance = null;

    public function __construct()
    {
        // TODO: проработать
    }

    /**
     * Возвращает объект приложения Application
     * @return Application - объект приложения
     */
    static public function getInstance()
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