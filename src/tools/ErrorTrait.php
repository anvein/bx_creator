<?php

namespace anvein\bx_creator\tools;

use Exception;

trait ErrorTrait
{
    /**
     * @var array - Массив с ошибками
     */
    protected $errors = [];

    /**
     * @inheritdoc
     */
    public function addError($error)
    {
        if (is_string($error)) {
            $this->errors[] = $error;
        } elseif (is_array($error)) {
            $this->errors += $error;
        } else {
            throw new Exception('Аргумент $error должен быть строкой или массивом');
        }
    }

    /**
     * @inheritdoc
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @inheritdoc
     */
    public function isSuccess()
    {
        if (empty($this->errors)) {
            return true;
        } else {
            return false;
        }
    }
}
