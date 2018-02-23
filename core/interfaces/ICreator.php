<?php

namespace Anvi\BitrixCreator;


interface ICreator
{

    /**
     * IConfugurator constructor.
     *
     * @param \Anvi\BitrixCreator\IConfigurator $config - объект конфигуратора
     */
    public function __construct(IConfigurator $config);


    /**
     * Cоздание объекта по конфигу
     *
     * @return bool - true, если объект создан, false, если возникли ошибки
     */
    public function run();


    /**
     * Возвращает ошибки из текущего объекта, если они есть, иначе false
     *
     * @return bool|array
     */
    public function getErrors();

}