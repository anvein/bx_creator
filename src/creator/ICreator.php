<?php

namespace anvein\bx_creator\creator;

use anvein\bx_creator\configurator\IConfigurator;

interface ICreator
{
    /**
     * IConfugurator constructor.
     *
     * @param \anvein\bx_creator\configurator\IConfigurator $config - объект конфигуратора
     */
    public function __construct(IConfigurator $config);

    /**
     * Cоздание объекта по конфигу.
     */
    public function run();
}
