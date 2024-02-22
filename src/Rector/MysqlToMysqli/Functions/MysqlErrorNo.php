<?php

namespace AhmadAsjad\Refactor\MysqlToMysqli\Functions;

use AhmadAsjad\Refactor\MysqlToMysqli\Functions\Interfaces\FunctionInterface;
use AhmadAsjad\Refactor\MysqlToMysqli\Functions\Traits\OnlyLinkIdentifierToMysqliTrait;

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