<?php

namespace AhmadAsjad\Refactor\MysqlToMysqli\Rule;

use AhmadAsjad\Refactor\MysqlToMysqli\Functions\ArrayMap;
use AhmadAsjad\Refactor\MysqlToMysqli\Functions\MysqlAffectedRows;
use AhmadAsjad\Refactor\MysqlToMysqli\FunctionInterface;
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
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class ChangeMysqlToMysqli extends AbstractRector
{
    private static $_db_connection;
    private $function_map = [
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

        if (!array_key_exists($functionCallName, $this->function_map)) {
            return null;
        }

        $class = $this->function_map[$functionCallName];

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

    private function refactorUse(Node\Stmt\UseUse $node)
    {
        $functionUseName = $this->getName($node->name);
        if ($functionUseName === null) {
            return null;
        }

        if (!array_key_exists($functionUseName, $this->function_map)) {
            return null;
        }

        $class = $this->function_map[$functionUseName];
        $funcChanging = new $class();
        $newFunName = $funcChanging->getNewFunctionName();
        $node->name = new Node\Name($newFunName);

        return $node;
    }
}
