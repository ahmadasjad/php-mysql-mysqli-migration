<?php

namespace AhmadAsjad\Refactor\MysqlToMysqli\Functions;

use AhmadAsjad\Refactor\MysqlToMysqli\Functions\Interfaces\FunctionInterface;
use AhmadAsjad\Refactor\MysqlToMysqli\Functions\Traits\OnlyLinkIdentifierToMysqliTrait;

class MysqlError extends AbstractMysqlFunction implements FunctionInterface
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