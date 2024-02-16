<?php

namespace AhmadAsjad\Refactor\MysqlToMysqli;

use PhpParser\Node;
use Rector\Rector\AbstractRector;

interface FunctionInterface
{
    public function getOldFunctionName(): string;
    public function getNewFunctionName(): string;
    public function getOldParams(): array;
    public function getNewParams(): array;
    public function getOldReturnType(): string;
    public function getNewReturnType(): string;
    public function changeParams(Node $node, AbstractRector $rector): Node;
}