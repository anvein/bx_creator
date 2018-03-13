<?php

namespace anvi\bx_creator\configurator;

use anvi\bx_creator\tools\ErrorTrait;
use anvi\bx_creator\IError;
use Exception;

abstract class Configurator implements IConfigurator, IError
{
    use ErrorTrait;

    /**
     * Название "объекта" (например: компонент / модуль)
     * @var null
     */
    protected $title = null;

    /**
     * Путь к папке, где надо создать "объект"
     * @var null
     */
    protected $path = null;

    /**
     * Название "объекта" (например: название компонента - news.list)
     * @var null
     */
    protected $name = null;


    /**
     * Configurator constructor
     */
    public function __construct($code)
    {
        if (!empty($code)) {
            $this->title = $code;
        }
    }

    /**
     * Возвращает название "объекта"
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Задает название "объекта"
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
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
            throw new Exception("У конфига нет параметра {$codeParam}");
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
            $errors[] = "Не указано название {$this->title}";
        }

//        // TODO: валидировать name
//        if (preg_match('//', $this->name) === 1) {
//            $errors[] = "Не указано название {$this->code}";
//        }

        if (empty($this->path)) {
            $errors[] = "Не указан путь где должен быть создан {$this->title}";
        } elseif (!is_dir($this->path)) {
            $errors[] = "Путь, где должен быть создан {$this->title} не существует";
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

}