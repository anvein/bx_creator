<?php

namespace anvi\bxcreator\configurator;

use anvi\bxcreator\IError;
use Exception;

class Configurator implements IConfigurator, IError
{
    /**
     * Код "объекта"
     * @var null
     */
    protected $code = null;
    protected $errors = [];

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
            throw new Exception("У конфиша нет параметра {$codeParam}");
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
            throw new Exception("У конфига нет параметра {$codeParam}");
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
        if ($value[strlen($value) - 1] === DIRECTORY_SEPARATOR) {
            $value = substr($value, 0, -1);
        }

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

//        // TODO: валидировать name
//        if (preg_match('//', $this->name) === 1) {
//            $errors[] = "Не указано название {$this->code}";
//        }

        if (empty($this->path)) {
            $errors[] = "Не указан путь где должен быть создан {$this->code}";
        } elseif (!is_dir($this->path)) {
            $errors[] = "Путь, где должен быть создан {$this->code} не существует";
        }

        if (empty($errors)) {
            return true;
        } else {
            $this->addError($errors);
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function getInfo()
    {
        return $arInfo = [
            "Название объекта: {$this->name}",
            "Путь к папке, где надо создать объект: {$this->path}",
        ];
    }

    /**
     * @inheritdoc
     */
    public function addError($error)
    {
       if (is_string($error)) {
           $this->errors[] = $error;
       } elseif (is_array($error)) {
           $this->errors += $error;
       } else {
           throw new Exception('Аргумент $error должен быть строкой или массивом');
       }

       return true;
    }


    /**
     * @inheritdoc
     */
    public function getErrors()
    {
       if (empty($this->errors)) {
           return false;
       } else {
           return $this->errors;
       }
    }

}