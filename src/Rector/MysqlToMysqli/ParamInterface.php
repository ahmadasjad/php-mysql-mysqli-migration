<?php

namespace AhmadAsjad\Refactor\MysqlToMysqli;

interface ParamInterface
{
    public function getName();
    public function setName($name);
    public function getType();
    public function setType($type);
}