<?php

namespace AhmadAsjad\Refactor\MysqlToMysqli\Functions;

use AhmadAsjad\Refactor\MysqlToMysqli\Param\ParamOptional;
use AhmadAsjad\Refactor\MysqlToMysqli\Param\ParamRequired;
use AhmadAsjad\Refactor\MysqlToMysqli\Rule\ChangeMysqlToMysqli;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Name;
use Rector\Rector\AbstractRector;

trait OnlyLinkIdentifierToMysqliTrait
{

    public function getOldParams(): array
    {
        return [
            (new ParamOptional())->setName('$link_identifier')->setType('resource'),
        ];
    }

    public function getNewParams(): array
    {
        return [
            (new ParamRequired())->setName('$mysql')->setType('mysqli')->setValueIfNull(ChangeMysqlToMysqli::getDbConnectionVariable()),
        ];
    }


    public function changeParams(Node $node, AbstractRector $rector): Node
    {
        $passedArgs = $node->getArgs();
        if (empty($passedArgs)) {
            $passedArgs = [new Arg(new ConstFetch(new Name($this->getNewParams()[0]->getValueIfNull())))];
        }
        $node->args = $passedArgs;

        // return $node if you modified it
        return $node;
    }
}