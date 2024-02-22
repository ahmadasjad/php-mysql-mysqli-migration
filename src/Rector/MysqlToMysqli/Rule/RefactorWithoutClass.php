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
        'mysql_client_encoding' => 0,
        'mysql_close' => 0,
        'mysql_db_name' => 0,
        'mysql_db_query' => 0,
        'mysql_drop_db' => 0,
        'mysql_errno' => 0,
        'mysql_error' => 0,
        'mysql_escape_string' => 0,
        'mysql_fetch_field' => null, // No optional $link_identifier
        'mysql_fetch_lengths' => 0,
        'mysql_fetch_row' => 0,
        'mysql_field_seek' => null, // No optional $link_identifier
        'mysql_field_table' => null, // No optional $link_identifier
        'mysql_field_type' => null, // No optional $link_identifier
        'mysql_free_result' => 0,
        'mysql_list_dbs' => 0,
        'mysql_list_fields' => 2,
        'mysql_list_processes' => 0,
        'mysql_num_rows' => 0,
        'mysql_ping' => 0,
        'mysql_query' => 1,
        'mysql_real_escape_string' => 1,
        'mysql_select_db' => 1,
        'mysql_tablename' => 1,
        'mysql_thread_id' => 0,
        'mysql_unbuffered_query' => 1,
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