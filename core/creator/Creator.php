<?php

namespace anvi\bxcreator;

class Creator implements ICreator
{
    /**
     * @var array - Массив с ошибками
     */
    protected $errors = [];

    /**
     * Объект конфигуратора
     *
     * @var null - \Anvi\bxcreator\IConfigurator
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
    public function getErrors()
    {
        if (!empty($this->errors)) {
            return $this->errors;
        } else {
            return false;
        }
    }


    /**
     * @inheritdoc
     */
    public function run()
    {

    }



}