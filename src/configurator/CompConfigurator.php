<?php

namespace anvein\bx_creator\configurator;

use anvein\bx_creator\tools\Helper;

class CompConfigurator extends Configurator
{
    const SIMPLE_COMPONENT = 'simple';
    const COMPLEX_COMPONENT = 'complex';

    protected $type = self::SIMPLE_COMPONENT;
    protected $namespace = '';

    protected $createParams = false;
    protected $createDescr = false;
    protected $createLang = false;

    // TODO: создать класс для параметра LangFiles и вооще сделать класс для таких Параметров
    protected $langFiles = null; // TODO: unused
    protected $complexFiles = [];

    /**
     * Установка параметра type конфигуратора
     * @param $value - значение параметра type
     * @return $this - объект конфигуратора
     */
    public function setType($value)
    {
        return $this->setParam('type', (string)$value);
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
     * Установка параметра namespace конфигуратора
     * @param $value - значение параметра namespace
     * @return $this - объект конфигуратора
     */
    public function setNamespace($value)
    {
        $value = Helper::prepareNamescape($value);
        return $this->setParam('namespace', (string)$value);
    }

    /**
     * Возвращает параметр namespace конфигуратора
     * @return mixed - значение параметра конфигуратора Namespace
     */
    public function getNamespace()
    {
        return $this->getParam('namespace');
    }


    /**
     * Установка параметра createParams конфигуратора
     * @param $value - значение параметра createParams
     * @return $this - объект конфигуратора
     */
    public function setCreateParams($value)
    {
        return $this->setParam('createParams', (bool)$value);
    }

    /**
     * Возвращает параметр createParams конфигуратора
     * @return mixed - значение параметра конфигуратора createParams
     */
    public function getCreateParams()
    {
        return $this->getParam('createParams');
    }


    /**
     * Установка параметра createDescr конфигуратора
     * @param $value - значение параметра createDescr
     * @return $this - объект конфигуратора
     */
    public function setCreateDescr($value)
    {
        return $this->setParam('createDescr', (bool)$value);
    }

    /**
     * Возвращает параметр createDescr конфигуратора
     * @return mixed - значение параметра конфигуратора createDescr
     */
    public function getCreateDescr()
    {
        return $this->getParam('createDescr');
    }


    /**
     * Установка параметра createLang конфигуратора
     * @param $value - значение параметра createLang
     * @return $this - объект конфигуратора
     */
    public function setCreateLang($value)
    {
        return $this->setParam('createLang', (bool)$value);
    }

    /**
     * Возвращает параметр createLang конфигуратора
     * @return $this - значение параметра createLang
     */
    public function getCreateLang($value)
    {
        return $this->getParam('createLang');
    }

    /**
     * Возвращает параметр createLang конфигуратора
     * @return mixed - значение параметра конфигуратора createLang
     */
    public function getLangFiles()
    {
        return $this->getParam('langFiles');
    }


    /**
     * Установка параметра createLangParams конфигуратора
     * @param $value
     * @return $this
     */
    public function setLangFiles($value)
    {
        return $this->setParam('langFiles', (array)$value);
    }


    /**
     * Установка параметра complexFiles конфигуратора
     * @param $value - значение параметра complexFiles
     * @return $this - объект конфигуратора
     */
    public function setComplexFiles($value)
    {
        return $this->setParam('complexFiles', (array)$value);
    }

    /**
     * Возвращает параметр complexFiles конфигуратора
     * @return mixed - значение параметра конфигуратора complexFiles
     */
    public function getComplexFiles()
    {
        return $this->getParam('complexFiles');
    }

    /**
     * @inheritdoc
     */
    public function validate()
    {
        $arErrors = [];

        $parResult = parent::validate();
        $arErrors += is_array($parResult) ? $parResult : [];

        $arAllowType = [self::SIMPLE_COMPONENT, self::COMPLEX_COMPONENT];
        $type = $this->getType();
        if (!in_array($type, $arAllowType, true)) {
            $arErrors[] = "Неизвестный тип компонента {$type}";
        }

        if (empty($arErrors)) {
            return true;
        } else {
            $this->addError($arErrors);
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function getInfo()
    {
        $arInfo = [
            "Тип компонента: {$this->type}",
            "Название компонента: {$this->name}",
            "Путь где надо создать компонент: {$this->path}",
            "namespace: {$this->getNamespace()}",
            "Создавать ли файл .parameters.php: " . Helper::tfConvert($this->createParams),
            "Создавать ли файл .description.php: " . Helper::tfConvert($this->createDescr),
            "Создавать lang файлы для языков: " . Helper::tfConvert($this->createLang),
        ];

        if ($this->type === self::COMPLEX_COMPONENT) {
            $arInfo[] = "Создавать файлы комплексного компонента: " . implode(', ', $this->complexFiles);
        }

        return $arInfo;
    }

}