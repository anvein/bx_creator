<?php

namespace Anvi\BitrixCreator;

class CompConfigurator extends Configurator
{
    const SIMPLE_COMPONENT = 'simple';
    const COMPLEX_COMPONENT = 'complex';

    protected $type = self::SIMPLE_COMPONENT;


    /**
     * Установка параметра type конфигуратора
     * @param $value - значение параметра type
     * @return $this - объект конфигуратора
     */
    public function setType($value)
    {
        return $this->setParam('type', $value);
    }

    /**
     * Возвращает параметр type конфигуратора
     * @return mixed - значение параметра конфигуратора type
     */
    public function getType()
    {
        return $this->getParam('type');
    }

}