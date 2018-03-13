<?php
#NAMESPACE#
use CBitrixComponent;

class #CLASS_NAME# extends CBitrixComponent
{
    /**
     * Подготовка входных параметров компонента
     * @param $params - входящие в компонент параметры
     * @return array - модифицированные параметры
     */
    public function onPrepareComponentParams($params)
    {
        // TODO: обработать входящие параметры компонента
        $params = parent::onPrepareComponentParams($params);
        $params['silent'] = empty($params['silent']) ? false : true;

        return $params;
    }

    /**
     * Выполнение компонента
     * @return mixed
     */
    public function executeComponent()
    {
        // TODO: проработать выполнение компонента


        if ($this->arParams['silent']) {
            return $this->arResult;
        } else {
            $this->includeComponentTemplate();
        }
    }

}