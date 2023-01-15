# DBT Enhanced
## This is free and unencumbered software released into the public domain.<br>
Dynamic dbtools framework.<br>
![Visitor count](https://shields-io-visitor-counter.herokuapp.com/badge?page=slowsient.dbt)<br>
```php
<?php
require_once "inc/lib/DBTCore.php";
// you can make it understandable
class tbl_test0 extends DBTTable { static $DBIndex = 0, $Name = "test", $Key = "id"; }
// or make it harder but easier to write
class tbl_test2 extends DBTTable { const qq = "0 test2sqlite id"; }
```
## Short summary
DBT supports multiple database connections.<br>
DBT supports SQLite and MySQL.<br>

## Examples
All examples are here: https://github.com/SlowsieNT/dbt/blob/main/src/examples.php
