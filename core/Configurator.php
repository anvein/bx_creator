<?php

namespace Anvi\BitrixCreator;

use Exception;

class Configurator implements IConfigurator
{
    /**
     * Код "объекта"
     * @var null
     */
    protected $code = null;

    /**
     * Путь к папке, где надо создать "объект"
     * @var null
     */
    protected $path = null;

    /**
     * Название "объекта"
     * @var null
     */
    protected $name = null;


    /**
     * Configurator constructor
     */
    public function __construct($code)
    {
        if (empty($code)) {
            throw new Exception('Не передан обязательный атрибут $code');
        } else {
            $this->code = $code;
        }
    }



    /**
     * Задает значение $value параметру $codeParam в конфигураторе
     * @param $codeParam
     * @param $value - значение
     * @return $this - объект конфигуратора
     * @throws Exception - если у конфигуратора нет параметра $codeParam
     */
    protected function setParam($codeParam, $value)
    {
        if (property_exists($this, $codeParam)) {
            $this->$codeParam = $value;
        } else {
            throw new Exception("У конфигуратора нет свойства (параметра) {$codeParam}");
        }

        return $this;
    }

    /**
     * Получает значение параметра $codeParam из конфигуратора
     * @param $codeParam - код параметра
     * @return mixed - значение параметра
     * @throws Exception - если у конфигуратора нет параметра $codeParam
     */
    protected function getParam($codeParam)
    {
        if (property_exists($this, $codeParam)) {
            return $this->$codeParam;
        } else {
            throw new Exception("У конфигуратора нет свойства (параметра) {$codeParam}");
        }
    }


    /**
     * @inheritdoc
     */
    public function setName($value)
    {
        return $this->setParam('name', $value);
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return $this->getParam('name');
    }

    /**
     * @inheritdoc
     */
    public function setPath($value)
    {
        return $this->setParam('path', $value);
    }

    /**
     * @inheritdoc
     */
    public function getPath()
    {
        return $this->getParam('path');
    }

    /**
     * @inheritdoc
     */
    public function validate()
    {
        $errors = [];
        if (empty($this->name)) {
            $errors[] = "Не указано название {$this->code}";
        }

        if (empty($this->path)) {
            $errors[] = "Не указан путь где должен быть создан {$this->code}";
        } elseif (is_dir($this->path)) {
            $errors[] = "Путь, где должен быть создан {$this->code} не существует";
        }

        if (empty($errors)) {
            return true;
        } else {
            return $errors;
        }
    }

    /**
     * @inheritdoc
     */
    public function getInfo()
    {
        $arInfo = [];
        $arInfo[] = "Название объекта: {$this->name}";
        $arInfo[] = "Путь к папке, где надо создать объект: {$this->path}";

        return $arInfo;
    }

}