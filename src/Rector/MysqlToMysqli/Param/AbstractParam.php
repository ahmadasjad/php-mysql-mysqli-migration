<?php

namespace AhmadAsjad\Refactor\MysqlToMysqli\Param;

use AhmadAsjad\Refactor\MysqlToMysqli\ParamInterface;

abstract class AbstractParam implements ParamInterface
{
    private $_param_name = null;
    private $_param_type = null;
    public function getName()
    {
        if ($this->_param_name === null) {
            throw new \RuntimeException('The parameter name is not set');
        }
        return $this->_param_name;
    }

    public function setName($name)
    {
        $this->_param_name = $name;
        return $this;
    }

    public function getType()
    {
        if ($this->_param_type === null) {
            throw new \RuntimeException('The parameter type is not set');
        }
        return $this->_param_type;
    }

    public function setType($type)
    {
        $this->_param_type = $type;
        return $this;
    }
}