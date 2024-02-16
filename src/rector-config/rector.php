<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;

//use Rector\Config\RectorConfig;
//use Rector\Set\ValueObject\SetList;
//use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector;

include_once './vendor/autoload.php';

function getPathsToSkip($root_path) {
    return [
        $root_path.'/.vscode',
        $root_path.'/vendor',
        $root_path.'/your/other/exclusion/directory',
    ];
}


//// For rector: 0.17.2
//return static function (RectorConfig $rectorConfig): void {
//    $root_path = '/your/app/src';
//
//    // // register single rule
//    // $rectorConfig->rule(TypedPropertyFromStrictConstructorRector::class);
//
//    $rectorConfig->paths([
//        $root_path,
//    ]);
//
//    // here we can define, what sets of rules will be applied
//    // tip: use "SetList" class to autocomplete sets with your IDE
//    $rectorConfig->sets([
//        SetList::MYSQL_TO_MYSQLI,
//    ]);
//};


// For rector: 1.0.1
$root_path = '/your/app/src';
\AhmadAsjad\Refactor\MysqlToMysqli\Rule\ChangeMysqlToMysqli::setDbConnectionVariable('\Example\DbConnection::getInstance()');
 return RectorConfig::configure()
     ->withPaths([
         $root_path,
     ])
      ->withSkip(getPathsToSkip($root_path))
     //->withPhpVersion(\Rector\ValueObject\PhpVersion::PHP_56)
     ->withRules([
         \AhmadAsjad\Refactor\MysqlToMysqli\Rule\ChangeMysqlToMysqli::class,
     ]);
