<?php

namespace anvi\bx_creator\creator;

use anvi\bx_creator\configurator\IConfigurator;
use anvi\bx_creator\IError;
use anvi\bx_creator\tools\ErrorTrait;
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