## How to use

- create a class similar to `src/Example/DbConnection.php`
- copy `src/rector-config/rector.php` to your root directory
- modify it as per your need.
- Must call `\AhmadAsjad\Refactor\MysqlToMysqli\Rule\ChangeMysqlToMysqli::setDbConnectionVariable('\Example\DbConnection::getInstance()');` and change its argument value to your custom class value


## Rector version details
refactoring for mysql supported in rector: 0.8.8
refactoring for mysql supported in rector: 0.17.2
refactoring for mysql removed from 0.17.3
