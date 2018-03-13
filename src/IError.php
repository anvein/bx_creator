<?php

namespace anvein\bx_creator;

interface IError
{

    /**
     * Возвращает ошибки из текущего объекта, если они есть
     * @return array - если ошибки есть, то массив с ошибками, либо пустой массив
     */
    public function getErrors();

    /**
     * Добавляет ошибку в текущий объект
     * @param string - строка с ошибкой
     * @throw Exception - если $error не строка и не массив
     */
    public function addError($error);


    /**
     * Проверяет были ли ошибки при создании объекта на основе настроек конфигуратора
     * @return bool
     */
    public function isSuccess();
}