<?php

namespace anvi\bxcreator\configurator;

interface IConfigurator
{

    /**
     * IConfigurator constructor.
     * @param string $code - код объекта конфигуратора
     */
    public function __construct($code);


    /**
     * Задает значение $value параметру name в конфигураторе
     * @param $value - значение параметра
     * @return $this - объект конфигуратора
     */
    public function setName($value);

    /**
     * Получает значение параметра name из конфигуратора
     * @return string - значение параметра
     */
    public function getName();


    /**
     * Задает значение $value параметру path в конфигураторе
     * @param $value - значение параметра
     * @return $this - объект конфигуратора
     */
    public function setPath($value);

    /**
     * Получает значение параметра path из конфигуратора
     * @return string - значение параметра
     */
    public function getPath();


    /**
     * Возвращает массив с настройками конфигуратора
     * @return array - настройки конфигуратора
     */
    public function getInfo();

    /**
     * Валидация параметров конфигуратора
     * @return bool - false, если валидация не прошла, иначе true<br>
     * ошибки будут в объекте конфигуратора
     */
    public function validate();

}