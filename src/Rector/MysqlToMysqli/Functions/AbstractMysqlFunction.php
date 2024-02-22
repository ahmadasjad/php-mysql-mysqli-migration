<?php

namespace AhmadAsjad\Refactor\MysqlToMysqli\Functions;

use AhmadAsjad\Refactor\MysqlToMysqli\Functions\Interfaces\FunctionInterface;

abstract class AbstractMysqlFunction implements FunctionInterface
{
    public function getOldReturnType(): string
    {
        return '';
    }

    public function getNewReturnType(): string
    {
        return '';
    }
}