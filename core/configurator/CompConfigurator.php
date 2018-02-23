<?php

namespace anvi\bxcreator\configurator;

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
     * @return mixed - значение параметра конфигуратора createLang
     */
    public function getCreateLang()
    {
        return $this->getParam('createLang');
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
            $arErrors[] = "Неизвестный тип компонента (type) {$type}";
        }

        if (empty($errors)) {
            return true;
        } else {
            return $errors;
        }
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
     * TODO: ввынести во вспомогательный класс мб?
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