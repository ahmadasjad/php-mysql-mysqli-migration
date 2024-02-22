<?php

namespace AhmadAsjad\Refactor\MysqlToMysqli\Rule;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Name;


class RefactorWithoutClass
{
    private $optionalConParamRequiredConParamMap = [
        // 'old_func_name'=> position by zero index,
    ];

    private $swithArgPositionMap = [
        // 'old_func_name'=> [old position by zero index, new position by zero index],
    ];

    public function changeParams(Node\Expr\FuncCall $node, $functionCallName)
    {
        if (array_key_exists($functionCallName, $this->optionalConParamRequiredConParamMap))
        {
            $passedArgs = $node->getArgs();
            if (!isset($passedArgs[$this->optionalConParamRequiredConParamMap[$functionCallName]])) {
                $contArg = new Arg(new ConstFetch(new Name(ChangeMysqlToMysqli::getDbConnectionVariable())));
                $passedArgs[$this->optionalConParamRequiredConParamMap[$functionCallName]] = $contArg;
                $node->args = $passedArgs;
            }
        }

        return $this->changeParamPosition($node, $functionCallName);
    }

    private function changeParamPosition(Node\Expr\FuncCall $node, $functionCallName)
    {
        if (array_key_exists($functionCallName, $this->swithArgPositionMap))        {
            $passedArgs = $node->getArgs();
            $oldPos = $this->swithArgPositionMap[$functionCallName][0];
            $newPos = $this->swithArgPositionMap[$functionCallName][1];

            // Remove the value from the old position
            $valueToMove = array_splice($passedArgs, $oldPos, 1)[0];

            // Insert the value at the new position
            array_splice($passedArgs, $newPos, 0, $valueToMove);
            $node->args = $passedArgs;
        }

        return $node;
    }
}