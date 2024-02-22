<?php

namespace AhmadAsjad\Refactor\MysqlToMysqli\Functions;

use AhmadAsjad\Refactor\MysqlToMysqli\Functions\Interfaces\FunctionInterface;
use AhmadAsjad\Refactor\MysqlToMysqli\Functions\Traits\OnlyNameChangeIsEnoughTrait;
use AhmadAsjad\Refactor\MysqlToMysqli\Param\ParamRequired;

class MysqlNumRows extends AbstractMysqlFunction implements FunctionInterface
{
    use OnlyNameChangeIsEnoughTrait;
    public function getOldFunctionName(): string
    {
        return 'mysql_num_rows';
    }

    public function getNewFunctionName(): string
    {
        return 'mysqli_num_rows';
    }

    public function getOldParams(): array
    {
        return [
            (new ParamRequired())->setName('$result')->setType('resource')->setValueIfNull('null'),
        ];
    }

    public function getNewParams(): array
    {
        return [
            (new ParamRequired())->setName('$result')->setType('mysqli_result')->setValueIfNull('null'),
        ];
    }

    public function getOldReturnType(): string
    {
        return 'int|false';
    }

    public function getNewReturnType(): string
    {
        return 'int|string';
    }
}