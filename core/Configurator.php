<?php

namespace Anvi\BitrixCreator;

use Exception;

class Configurator implements IConfigurator
{
    /**
     * Код "объекта"
     * @var null
     */
    // TODO: нужен ли???
    static protected $code = null;

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
    public function __construct(array $arParams = [])
    {
        if (!empty($arParams)) {
            foreach ($arParams as $keyParam => $param) {
                $this->setParam($keyParam, $param);
            }
        }
    }


    /**
     * @inheritdoc
     */
    public function setParam($codeParam, $value)
    {
        if (property_exists($this, $codeParam)) {
            $this->$codeParam = $value;
        } else {
            throw new Exception("У конфигуратора нет свойства (параметра) {$codeParam}");
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getParam($codeParam)
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
        //$path = $_SERVER['DOCUMENT_ROOT']
        // TODO: сделать чтобы вычислслся абсолютный путь
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
            $errors[] = "Не указано название объекта {$this->code}";
        }

        if (empty(empty($this->path))) {
            $errors[] = "Не указан путь где должен быть создан объект {$this->code}";
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