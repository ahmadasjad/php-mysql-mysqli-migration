<?php

namespace AhmadAsjad\Refactor\MysqlToMysqli\Functions;

use AhmadAsjad\Refactor\MysqlToMysqli\FunctionInterface;

class MysqlErrorNo implements FunctionInterface
{
    use OnlyLinkIdentifierToMysqliTrait;
    public function getOldFunctionName(): string
    {
        return 'mysql_errno';
    }

    public function getNewFunctionName(): string
    {
        return 'mysqli_errno';
    }

    public function getOldReturnType(): string
    {
        return 'int';
    }

    public function getNewReturnType(): string
    {
        return 'int';
    }
}