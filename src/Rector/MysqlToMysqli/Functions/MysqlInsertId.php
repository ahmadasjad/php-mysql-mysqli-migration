<?php

namespace AhmadAsjad\Refactor\MysqlToMysqli\Functions;

use AhmadAsjad\Refactor\MysqlToMysqli\FunctionInterface;

class MysqlInsertId implements FunctionInterface
{
    use OnlyLinkIdentifierToMysqliTrait;
    public function getOldFunctionName(): string
    {
        return 'mysql_insert_id';
    }

    public function getNewFunctionName(): string
    {
        return 'mysqli_insert_id';
    }

    public function getOldReturnType(): string
    {
        return '';
    }

    public function getNewReturnType(): string
    {
        return '';
    }
}