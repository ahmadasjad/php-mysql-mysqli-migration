## How to use

- create a class similar to `src/Example/DbConnection.php`
- copy `src/rector-config/rector.php` to your root directory
- modify it as per your need.
- Must call `\AhmadAsjad\Refactor\MysqlToMysqli\Rule\ChangeMysqlToMysqli::setDbConnectionVariable('\Example\DbConnection::getInstance()');` and change its argument value to your custom class value


## Example

rector.php
```php
use AhmadAsjad\Refactor\MysqlToMysqli\Rule\ChangeMysqlToMysqli;

$root_path = '/your/app/src';
ChangeMysqlToMysqli::setDbConnectionVariable('\Example\DbConnection::getInstance()');
 return RectorConfig::configure()
     ->withPaths([
         $root_path,
     ])
     //->withSkip([$root_path.'/path/1', $root_path.'/path/2',])
     //->withPhpVersion(\Rector\ValueObject\PhpVersion::PHP_56)
     ->withRules([
         ChangeMysqlToMysqli::class,
     ]);
```


command
`vendor/bin/rector process`

## Rector version details
- refactoring for mysql supported in rector: 0.8.8
- refactoring for mysql supported in rector: 0.17.2
- refactoring for mysql removed from 0.17.3
