<?php

namespace anvi\bxcreator\creator;

use anvi\bxcreator\configurator\IConfigurator;
use Exception;
use anvi\bxcreator\IError;

class Creator implements ICreator, IError
{
    /**
     * @var array - Массив с ошибками
     */
    protected $errors = [];

    /**
     * Объект конфигуратора
     *
     * @var null - \anvi\bxcreator\configurator\IConfigurator
     */
    protected $config = null;


    /**
     * @inheritdoc
     */
    public function __construct(IConfigurator $config)
    {
        $this->config = $config;
    }


    /**
     * @inheritdoc
     */
    public function run()
    {
        if (!$this->config->validate()) {
            return false;
        }

        return true;
    }


    /**
     * @inheritdoc
     */
    public function addError($error)
    {
        if (!(is_string($error) && is_array($error))) {
            throw new Exception('Аргумент $error должен быть строкой или массивом');
        }

        if (is_string($error)) {
            $this->errors[] = $error;
        } elseif (is_array($error)) {
            $this->errors += $error;
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