<?php

namespace AhmadAsjad\Refactor\MysqlToMysqli\Rule;

use AhmadAsjad\Refactor\MysqlToMysqli\Functions\ArrayMap;
use AhmadAsjad\Refactor\MysqlToMysqli\Functions\MysqlAffectedRows;
use AhmadAsjad\Refactor\MysqlToMysqli\Functions\Interfaces\FunctionInterface;
use AhmadAsjad\Refactor\MysqlToMysqli\Functions\MysqlError;
use AhmadAsjad\Refactor\MysqlToMysqli\Functions\MysqlErrorNo;
use AhmadAsjad\Refactor\MysqlToMysqli\Functions\MysqlFetchArray;
use AhmadAsjad\Refactor\MysqlToMysqli\Functions\MysqlFetchAssoc;
use AhmadAsjad\Refactor\MysqlToMysqli\Functions\MysqlFetchObject;
use AhmadAsjad\Refactor\MysqlToMysqli\Functions\MysqlInsertId;
use AhmadAsjad\Refactor\MysqlToMysqli\Functions\MysqlNumRows;
use AhmadAsjad\Refactor\MysqlToMysqli\Functions\MysqlQuery;
use AhmadAsjad\Refactor\MysqlToMysqli\Functions\MysqlRealEscapeString;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Name;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class ChangeMysqlToMysqli extends AbstractRector
{
    private static $_db_connection;
    private $function_class_map = [
        'mysql_affected_rows' => MysqlAffectedRows::class,
        'mysql_errno' => MysqlErrorNo::class,
        'mysql_error' => MysqlError::class,
        'mysql_fetch_array' => MysqlFetchArray::class,
        'mysql_fetch_assoc' => MysqlFetchAssoc::class,
        'mysql_fetch_object' => MysqlFetchObject::class,
        'mysql_insert_id' => MysqlInsertId::class,
        'mysql_num_rows' => MysqlNumRows::class,
        'mysql_query' => MysqlQuery::class,
        'mysql_real_escape_string' => MysqlRealEscapeString::class,
        'array_map' => ArrayMap::class,
    ];

    private $function_function_map = [
        'mysql_affected_rows' => 'mysqli_affected_rows',
        'mysql_client_encoding' => 'mysqli_character_set_name',
        'mysql_close' => 'mysqli_close',
        'mysql_connect' => 'mysqli_connect',
        'mysql_create_db' => null, // Not directly equivalent, consider using SQL queries directly.
        'mysql_data_seek' => 'mysqli_data_seek',
        'mysql_db_name' => null, // Not directly equivalent, consider using SQL queries directly.
        'mysql_db_query' => null, // Not directly equivalent, consider using mysqli_query
        'mysql_drop_db' => null, // Not directly equivalent, consider using SQL queries directly.
        'mysql_errno' => 'mysqli_errno',
        'mysql_error' => 'mysqli_error',
        'mysql_escape_string' => 'mysqli_real_escape_string', // mysqli_escape_string is an alias
        'mysql_fetch_array' => 'mysqli_fetch_array',
        'mysql_fetch_assoc' => 'mysqli_fetch_assoc',
        'mysql_fetch_field' => 'mysqli_fetch_field',
        'mysql_fetch_lengths' => 'mysqli_fetch_lengths',
        'mysql_fetch_object' => 'mysqli_fetch_object',
        'mysql_fetch_row' => 'mysqli_fetch_row',
        'mysql_field_flags' => 'mysqli_fetch_field_direct',
        'mysql_field_len' => 'mysqli_fetch_field_direct',
        'mysql_field_name' => 'mysqli_fetch_field_direct',
        'mysql_field_seek' => 'mysqli_field_seek',
        'mysql_field_table' => 'mysqli_fetch_field_direct',
        'mysql_field_type' => 'mysqli_fetch_field_direct',
        'mysql_free_result' => 'mysqli_free_result',
        'mysql_get_client_info' => 'mysqli_get_client_info',
        'mysql_get_host_info' => 'mysqli_get_host_info',
        'mysql_get_proto_info' => 'mysqli_get_proto_info',
        'mysql_get_server_info' => 'mysqli_get_server_info',
        'mysql_info' => 'mysqli_info',
        'mysql_insert_id' => 'mysqli_insert_id',
        'mysql_list_dbs' => null, // Not directly equivalent, consider using mysqli_query with "SHOW DATABASES"
        'mysql_list_fields' => null, // Not directly equivalent, consider using SQL queries directly.
        'mysql_list_processes' => null, // Not directly equivalent, consider using SQL queries directly.
        'mysql_list_tables' => 'mysqli_query with "SHOW TABLES"',
        'mysql_num_fields' => 'mysqli_num_fields',
        'mysql_num_rows' => 'mysqli_num_rows',
        'mysql_pconnect' => 'mysqli_connect with persistent link option',
        'mysql_ping' => 'mysqli_ping',
        'mysql_query' => 'mysqli_query',
        'mysql_real_escape_string' => 'mysqli_real_escape_string',
        'mysql_result' => 'mysqli_data_seek() in conjunction with mysqli_field_seek() and mysqli_fetch_field()',
        'mysql_select_db' => 'mysqli_select_db',
        'mysql_set_charset' => 'mysqli_set_charset',
        'mysql_stat' => 'mysqli_stat',
        'mysql_tablename' => null, // Not directly equivalent, consider using SQL queries directly.
        'mysql_thread_id' => 'mysqli_thread_id',
        'mysql_unbuffered_query' => 'mysqli_query with MYSQLI_USE_RESULT option',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Change method calls from mysql_* to mysqli_*', [
                new CodeSample(
                // code before
                    'mysql_affected_rows($link);',
                    // code after
                    'mysql_affected_rows($mysqli);'
                ),
            ]
        );
    }

    public static function setDbConnectionVariable($conVariable)
    {
        self::$_db_connection = $conVariable;
    }

    public static function getDbConnectionVariable()
    {
        return self::$_db_connection;
    }

    public function getNodeTypes(): array
    {
        // what node types are we looking for?
        // pick from
        // https://github.com/rectorphp/php-parser-nodes-docs/
        return [Node\Expr\FuncCall::class, Node\Stmt\UseUse::class];
    }

    public function refactor(Node $node)
    {
        if ($node instanceof Node\Expr\FuncCall) {
            return $this->refactorFunctionCall($node);
        }

        if ($node instanceof Node\Stmt\UseUse) {
            return $this->refactorUse($node);
        }

        return null;
    }

    private function refactorFunctionCall(Node\Expr\FuncCall $node)
    {
        $functionCallName = $this->getName($node->name);
        if ($functionCallName === null) {
            return null;
        }

        if (array_key_exists($functionCallName, $this->function_class_map)) {
            return $this->refactorFunctionWithClass($node, $functionCallName);
        }

        if (array_key_exists($functionCallName, $this->function_function_map)) {
            return $this->refactorFunctionWithoutClass($node, $functionCallName);
        }

        return null;
    }

    private function refactorFunctionWithClass(Node\Expr\FuncCall $node, $functionCallName)
    {
        $class = $this->function_class_map[$functionCallName];

        $funcChanging = new $class();

        /**
         * @var $funcChanging FunctionInterface
         */

        $newFunName = $funcChanging->getNewFunctionName();
        if ($newFunName !== $funcChanging->getOldFunctionName()) {
            $node->name = new Node\Name($newFunName);
        }

        return $funcChanging->changeParams($node, $this);
    }

    private function refactorFunctionWithoutClass(Node\Expr\FuncCall $node, $functionCallName)
    {
        // Change name
        if ($this->function_function_map[$functionCallName] !== null) {
            $newFunName = $this->function_function_map[$functionCallName];
            $node->name = new Node\Name($newFunName);
        }

        // Change params
        $changeParamWithoutClass = new RefactorWithoutClass();
        $node = $changeParamWithoutClass->changeParams($node, $functionCallName);

        return $node;
    }

    private function refactorUse(Node\Stmt\UseUse $node)
    {
        $functionUseName = $this->getName($node->name);
        if ($functionUseName === null) {
            return null;
        }

        if (!array_key_exists($functionUseName, $this->function_class_map)) {
            return null;
        }

        $class = $this->function_class_map[$functionUseName];
        $funcChanging = new $class();
        $newFunName = $funcChanging->getNewFunctionName();
        $node->name = new Node\Name($newFunName);

        return $node;
    }
}
