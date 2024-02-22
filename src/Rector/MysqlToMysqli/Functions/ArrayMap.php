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

class ArrayMap implements FunctionInterface
{

    public function getOldFunctionName(): string
    {
        return 'array_map';
    }

    public function getNewFunctionName(): string
    {
        return 'array_map';
    }

    public function getOldParams(): array
    {
        return [
            (new ParamRequired())->setName('$callback')->setType('callable'),
            (new ParamRequired())->setName('$array')->setType('array'),
        ];
    }

    public function getNewParams(): array
    {
        return [
            (new ParamRequired())->setName('$callback')->setType('callable'),
            (new ParamRequired())->setName('$array')->setType('array'),
        ];
    }

    public function getOldReturnType(): string
    {
        return 'array';
    }

    public function getNewReturnType(): string
    {
        return 'array';
    }

    public function changeParams(Node $node, AbstractRector $rector): Node
    {
        $passedArgs = $node->getArgs();
        if (empty($passedArgs)) {
            throw new \RuntimeException('Required args not available');
        }

        // modify first argument
        $funcName = (string)$passedArgs[0]->value->value;
        if (!in_array($funcName, ['mysql_real_escape_string', '\mysql_real_escape_string'])) {
            return $node;
        }
        $conVariable = ChangeMysqlToMysqli::getDbConnectionVariable();
        $firstArg = 'function($value) { return mysqli_real_escape_string('.$conVariable.', $value); }';
        $firstArg = new Arg(new ConstFetch(new Name($firstArg)));

        $node->args = [$firstArg, $passedArgs[1]];

        return $node;
    }
}