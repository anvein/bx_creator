<?php

namespace Anvi\BitrixCreator;

interface IConfigurator
{

    /**
     * IConfigurator constructor.
     * @param array $arParams - массив параметров конфигуратора, которые нужно установить
     */
    public function __construct(array $arParams);

    /**
     * Задает значение $value параметру $codeParam в конфигураторе
     * @param $codeParam - код параметра
     * @param $value - значение
     * @return $this - объект конфигуратора
     */
    public function setParam($codeParam, $value);

    /**
     * Получает значение параметра $codeParam из конфигуратора
     * @param $codeParam - код параметра
     * @return mixed - значение параметра
     */
    public function getParam($codeParam);

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
     * Валидация параметров конфигуратора
     * @return array $arErrors - массив с сообщениями об ошибках
     */
    public function validate();

}