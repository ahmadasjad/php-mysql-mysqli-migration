<?php

namespace AhmadAsjad\Refactor\MysqlToMysqli\Functions;

use AhmadAsjad\Refactor\MysqlToMysqli\FunctionInterface;

class MysqlAffectedRows implements FunctionInterface
{
    use OnlyLinkIdentifierToMysqliTrait;

    public function getOldFunctionName(): string
    {
        return 'mysql_affected_rows';
    }

    public function getNewFunctionName(): string
    {
        return 'mysqli_affected_rows';
    }

    public function getOldReturnType(): string
    {
        return 'int';
    }

    public function getNewReturnType(): string
    {
        return 'int|string';
    }
}
