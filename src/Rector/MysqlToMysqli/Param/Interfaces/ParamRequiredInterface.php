<?php

namespace AhmadAsjad\Refactor\MysqlToMysqli\Param\Interfaces;

use AhmadAsjad\Refactor\MysqlToMysqli\Param\Interfaces\ParamInterface;

interface ParamRequiredInterface extends ParamInterface
{
    public function getValueIfNull();
    public function setValueIfNull($value);
}
