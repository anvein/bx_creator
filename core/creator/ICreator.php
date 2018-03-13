<?php

namespace anvi\bxcreator\creator;

use anvi\bxcreator\configurator\IConfigurator;

interface ICreator
{

    /**
     * IConfugurator constructor.
     *
     * @param \Anvi\bxcreator\configurator\IConfigurator $config - объект конфигуратора
     */
    public function __construct(IConfigurator $config);


    /**
     * Cоздание объекта по конфигу
     *
     * @return void
     */
    public function run();

}