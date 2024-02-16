<?php

namespace AhmadAsjad\Refactor\MysqlToMysqli\Functions;

use PhpParser\Node;
use Rector\Rector\AbstractRector;

trait OnlyNameChangeIsEnoughTrait
{
    public function changeParams(Node $node, AbstractRector $rector): Node
    {
        return $node;
    }
}