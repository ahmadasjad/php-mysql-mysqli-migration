<?php

namespace AhmadAsjad\Refactor\MysqlToMysqli\Param;

use AhmadAsjad\Refactor\MysqlToMysqli\Param\Interfaces\ParamRequiredInterface;

class ParamRequired extends AbstractParam implements ParamRequiredInterface
{
    private $_paramValue = null;
    public function getValueIfNull(): string
    {
        if ($this->_paramValue === null) {
            throw new \RuntimeException('The parameter value is not set.');
        }

        return $this->_paramValue;
    }

    public function setValueIfNull($value) {
        $this->_paramValue = $value;
        return $this;
    }
}