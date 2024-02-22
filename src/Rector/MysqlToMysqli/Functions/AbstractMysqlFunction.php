<?php

namespace AhmadAsjad\Refactor\MysqlToMysqli\Functions;

use AhmadAsjad\Refactor\MysqlToMysqli\Functions\Interfaces\FunctionInterface;
use AhmadAsjad\Refactor\MysqlToMysqli\Functions\Interfaces\OldNewSameNameInterface;

abstract class AbstractMysqlFunction implements FunctionInterface
{
    public function getOldFunctionName(): string
    {
        return $this->getSameName();
    }

    public function getNewFunctionName(): string
    {
        return $this->getSameName();
    }

    private function getSameName()
    {
        if ($this instanceof OldNewSameNameInterface) {
            return $this->getFunctionName();
        }

        throw new \RuntimeException('Please implement getOldFunctionName and getNewFunctionName for the class: '. get_class($this));
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