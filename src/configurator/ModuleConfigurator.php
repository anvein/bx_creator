<?php

namespace anvein\bx_creator\configurator;

use anvein\bx_creator\tools\Helper;
use DateTime;

class ModuleConfigurator extends Configurator
{
    /**
     * Вендор модуля
     * @var string
     */
    protected $vendor = null;

    /**
     * Версия модуля
     * @var string
     */
    protected $version = '0.0.1';

    /**
     * Дата и время версии
     * @var string
     */
    protected $versionDate;

    /**
     * Название модуля
     * @var string
     */
    protected $title = '';

    /**
     * Описание модуля
     * @var string
     */
    protected $description = '';

    /**
     * Ссылка (url) на вендора
     * @var string
     */
    protected $vendorUri = '/';

    /**
     * Дополнительные языки, на которые должен быть переведен модуль
     * @var array
     */
    protected $additionalLangs = [];

    /**
     * Переводить ли автоматически lang-фразы
     * @var bool
     */
    protected $autoTranslate = false;



    /**
     * Возвращает параметр vendor
     *
     * @return string
     */
    public function getVendor()
    {
        return $this->getParam('vendor');
    }

    /**
     * Задает параметр vendor
     *
     * @param string $vendor
     *
     * @return $this - объект конфигуратора
     */
    public function setVendor($vendor)
    {
        return $this->setParam('vendor', trim(strtolower($vendor)));
    }

    /**
     * Возвращает параметр version
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->getParam('version');
    }

    /**
     * Задает параметр version
     *
     * @param string $version
     *
     * @return $this - объект конфигуратора
     */
    public function setVersion($version)
    {
        return $this->setParam('version', (string) trim($version));
    }

    /**
     * Возвращает параметр versionDate
     *
     * @return string
     */
    public function getVersionDate()
    {
        return $this->getParam('versionDate');
    }

    /**
     * Задает параметр versionDate
     *
     * @param string $versionDate
     *
     * @return $this - объект конфигуратора
     */
    public function setVersionDate($versionDate)
    {
        return $this->setParam('versionDate', (string) trim($versionDate));
    }

    /**
     * Возвращает параметр title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getParam('title');
    }


    /**
     * Задает параметр title
     *
     * @param string $title
     *
     * @return $this - объект конфигуратора
     */
    public function setTitle($title)
    {
        return $this->setParam('title', (string) trim($title));
    }

    /**
     * Возвращает параметр description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->getParam('description');
    }

    /**
     * Задает параметр description
     *
     * @param string $description
     *
     * @return $this - объект конфигуратора
     */
    public function setDescription($description)
    {
        return $this->setParam('description', (string) trim($description));
    }

    /**
     * Возвращает параметр vendorUri
     *
     * @return string
     */
    public function getVendorUri()
    {
        return $this->getParam( 'vendorUri');
    }

    /**
     * Задает параметр vendorUri
     *
     * @param string $vendorUri
     *
     * @return $this - объект конфигуратора
     */
    public function setVendorUri($vendorUri)
    {
        return $this->setParam('vendorUri', (string) trim($vendorUri));
    }

    /**
     * Возвращает параметр additionLangs
     *
     * @return array
     */
    public function getAdditionalLangs()
    {
        return $this->getParam('additionLangs');
    }

    /**
     * Задает параметр additionLangs
     *
     * @param array $additionalLangs
     *
     * @return $this - объект конфигуратора
     */
    public function setAdditionalLangs(array $additionalLangs)
    {
        $codeRu = array_search('ru', $additionalLangs);
        if ($codeRu !== false) {
            unset($additionalLangs[$codeRu]);
        }

        return $this->setParam('additionalLangs', $additionalLangs);
    }

    /**
     * Возвращает параметр autoTranslate
     *
     * @return bool
     */
    public function isAutoTranslate()
    {
        return (bool) $this->getParam('autoTranslate');
    }

    /**
     * Задает параметр autoTranslate
     *
     * @param bool $autoTranslate
     *
     * @return $this - объект конфигуратора
     */
    public function setAutoTranslate($autoTranslate)
    {
        return $this->setParam('autoTranslate', trim($autoTranslate));
    }

    /**
     * @inheritdoc
     */
    public function validate()
    {
        $arErrors = [];

        $parResult = parent::validate();
        $arErrors += is_array($parResult) ? $parResult : [];

        // TODO: чекнуть вендора
        // TODO: чекнуть версию

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
            "Название (код) модуля: {$this->name}",
            "Путь где надо создать модуль: {$this->path}",
            "Вендор: {$this->vendor}",
            "Адрес (url) вендора: {$this->vendorUri}",
            "Версия: {$this->version}",
            "Дата версии: {$this->versionDate}",
            "Название (текстовое, для админки): {$this->title}",
            "Описание: {$this->description}",
            'Переводить автоматически на дополнительные языки: ' .  Helper::tfConvert($this->autoTranslate),
            'Дополнительные языки, на которые нужно перевести: ' . implode(', ', $this->additionalLangs),
        ];

        return $arInfo;
    }
}
