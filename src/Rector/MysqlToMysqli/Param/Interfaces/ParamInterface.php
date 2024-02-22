<?php

namespace AhmadAsjad\Refactor\MysqlToMysqli\Param\Interfaces;

interface ParamInterface
{
    public function getName();
    public function setName($name);
    public function getType();
    public function setType($type);
}