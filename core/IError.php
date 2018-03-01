<?php

namespace anvi\bxcreator;

interface IError
{

    /**
     * Возвращает ошибки из текущего объекта, если они есть
     * @return array|bool - если ошибок нет - false, иначе массив с ошибками
     */
    public function getErrors();

    /**
     * Добавляет ошибку в текущий объект
     * @param string - строка с ошибкой
     * @throw Exception - если $error не строка и не массив
     * @return mixed - true, если ошибка добавилась
     */
    public function addError($error);

}