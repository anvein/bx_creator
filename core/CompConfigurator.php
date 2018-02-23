<?php

namespace Anvi\BitrixCreator;

class CompConfigurator extends Configurator
{
    const SIMPLE_COMPONENT = 'simple';
    const COMPLEX_COMPONENT = 'complex';

    protected $type = self::SIMPLE_COMPONENT;
    protected $namespace = '';
    protected $createParams = false;
    protected $createDescr = false;
    protected $createLang = false;

    protected $complexFiles = [];

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


    /**
     * @inheritdoc
     */
    public function getInfo()
    {
        $arInfo = [];

        $arInfo[] = "Тип компонента: {$this->type}";
        $arInfo[] = "Название компонента: {$this->name}";
        $arInfo[] = "Путь где надо создать компонент: {$this->path}";
        $arInfo[] = "namespace: {$this->namespace}";
        $arInfo[] = "Создавать ли файл .parameters.php: " . $this->tfConvert($this->createParams);
        $arInfo[] = "Создавать ли файл .description.php: " . $this->tfConvert($this->createDescr);
        $arInfo[] = "Создавать ли lang файлы: " . $this->tfConvert($this->createLang);

        if ($this->type === self::COMPLEX_COMPONENT) {
            $arInfo[] = "Создаватьф айлы комплексного компонента: " . implode(', ', $this->complexFiles);
        }

        return $arInfo;
    }


    /**
     * Конвертирует true в Да, false и empty в нет
     * @param null $param - конверируемый параметр
     * @return string - да или нет
     */
    protected function tfConvert($param = null)
    {
        if (empty($param)) {
            return 'Нет';
        } else {
            return 'Да';
        }
    }
}