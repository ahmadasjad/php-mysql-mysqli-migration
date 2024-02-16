<?php

namespace AhmadAsjad\Refactor\MysqlToMysqli;

interface ParamRequiredInterface extends ParamInterface
{
    public function getValueIfNull();
    public function setValueIfNull($value);
}
