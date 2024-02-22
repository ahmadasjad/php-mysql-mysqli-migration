<?php

namespace AhmadAsjad\Refactor\MysqlToMysqli\Functions;

use AhmadAsjad\Refactor\MysqlToMysqli\Functions\Interfaces\FunctionInterface;
use AhmadAsjad\Refactor\MysqlToMysqli\Functions\Traits\OnlyNameChangeIsEnoughTrait;
use AhmadAsjad\Refactor\MysqlToMysqli\Param\ParamOptional;
use AhmadAsjad\Refactor\MysqlToMysqli\Param\ParamRequired;

class MysqlFetchObject implements FunctionInterface
{
    use OnlyNameChangeIsEnoughTrait;

    public function getOldFunctionName(): string
    {
        return 'mysql_fetch_object';
    }

    public function getNewFunctionName(): string
    {
        return 'mysqli_fetch_object';
    }

    public function getOldParams(): array
    {
        return [
            (new ParamRequired())->setName('$result')->setType('resource')->setValueIfNull('null'),
            (new ParamOptional())->setName('$class_name')->setType('string'),
            (new ParamOptional())->setName('$params')->setType('array'),
        ];
    }

    public function getNewParams(): array
    {
        return [
            (new ParamRequired())->setName('$result')->setType('mysqli_result')->setValueIfNull('null'),
            (new ParamOptional())->setName('$class')->setType('string'),
            (new ParamOptional())->setName('$constructor_args')->setType('array'),
        ];
    }

    public function getOldReturnType(): string
    {
        return 'object';
    }

    public function getNewReturnType(): string
    {
        return 'object|null|false';
    }
}