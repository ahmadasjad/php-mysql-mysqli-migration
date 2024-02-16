<?php

namespace AhmadAsjad\Refactor\MysqlToMysqli\Functions;

use AhmadAsjad\Refactor\MysqlToMysqli\FunctionInterface;

class MysqlError implements FunctionInterface
{
    use OnlyLinkIdentifierToMysqliTrait;

    public function getOldFunctionName(): string
    {
        return 'mysql_error';
    }

    public function getNewFunctionName(): string
    {
        return 'mysqli_error';
    }

    public function getOldReturnType(): string
    {
        return 'string';
    }

    public function getNewReturnType(): string
    {
        return 'string';
    }

}