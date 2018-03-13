<?php

namespace anvi\bx_creator\creator;

use anvi\bx_creator\configurator\IConfigurator;

interface ICreator
{

    /**
     * IConfugurator constructor.
     *
     * @param \Anvi\bx_creator\configurator\IConfigurator $config - объект конфигуратора
     */
    public function __construct(IConfigurator $config);


    /**
     * Cоздание объекта по конфигу
     *
     * @return void
     */
    public function run();

}