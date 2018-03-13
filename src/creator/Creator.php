<?php

namespace anvein\bx_creator\creator;

use anvein\bx_creator\configurator\IConfigurator;
use anvein\bx_creator\IError;
use anvein\bx_creator\tools\ErrorTrait;
use Exception;

abstract class Creator implements ICreator, IError
{
    use ErrorTrait;

    /**
     * Объект конфигуратора
     *
     * @var IConfigurator
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
    abstract public function run();

}