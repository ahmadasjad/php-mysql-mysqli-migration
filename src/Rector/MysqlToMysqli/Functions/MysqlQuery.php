<?php

namespace AhmadAsjad\Refactor\MysqlToMysqli\Functions;

use AhmadAsjad\Refactor\MysqlToMysqli\Functions\Interfaces\FunctionInterface;
use AhmadAsjad\Refactor\MysqlToMysqli\Param\ParamOptional;
use AhmadAsjad\Refactor\MysqlToMysqli\Param\ParamRequired;
use AhmadAsjad\Refactor\MysqlToMysqli\Rule\ChangeMysqlToMysqli;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Name;
use Rector\Rector\AbstractRector;

class MysqlQuery implements FunctionInterface
{
    public function getOldFunctionName(): string
    {
        return 'mysql_query';
    }

    public function getNewFunctionName(): string
    {
        return 'mysqli_query';
    }

    public function getOldParams(): array
    {
        return [
            (new ParamRequired())->setName('$query')->setType('string')->setValueIfNull('null'),
            (new ParamOptional())->setName('$link_identifier')->setType('resource'),
        ];
    }

    public function getNewParams(): array
    {
        return [
            (new ParamRequired())->setName('$mysql')->setType('mysqli')->setValueIfNull(ChangeMysqlToMysqli::getDbConnectionVariable()),
            (new ParamRequired())->setName('$query')->setType('string')->setValueIfNull('null'),
            (new ParamOptional())->setName('$result_mode')->setType('int'),
        ];
    }

    public function getOldReturnType(): string
    {
        return '';
    }

    public function getNewReturnType(): string
    {
        return '';
    }

    public function changeParams(Node $node, AbstractRector $rector): Node
    {
        $passedArgs = $node->getArgs();
        if (empty($passedArgs)) {
            throw new \RuntimeException('Required query param is not available');
        }
        $secondArg = $passedArgs[0];
        if (!isset($passedArgs[1])) {
            $firstArg = new Arg(new ConstFetch(new Name($this->getNewParams()[0]->getValueIfNull())));
        } else {
            $firstArg = $passedArgs[1];
        }

        // leaving third argument as it's not needed in our case

        $node->args = [$firstArg, $secondArg];

        // return $node if you modified it
        return $node;
    }
}