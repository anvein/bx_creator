<?php

namespace anvi\bxcreator\creator;

use anvi\bxcreator\configurator\IConfigurator;
use anvi\bxcreator\IError;
use anvi\bxcreator\tools\ErrorTrait;
use Exception;

class Creator implements ICreator, IError
{
    use ErrorTrait;

    /**
     * Объект конфигуратора
     *
     * @var \anvi\bxcreator\configurator\IConfigurator
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
        
    }

}