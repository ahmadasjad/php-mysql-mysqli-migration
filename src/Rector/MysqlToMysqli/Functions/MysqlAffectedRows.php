<?php

namespace AhmadAsjad\Refactor\MysqlToMysqli\Functions;

use AhmadAsjad\Refactor\MysqlToMysqli\Functions\Interfaces\FunctionInterface;
use AhmadAsjad\Refactor\MysqlToMysqli\Functions\Traits\OnlyLinkIdentifierToMysqliTrait;

class MysqlAffectedRows extends AbstractMysqlFunction implements FunctionInterface
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
