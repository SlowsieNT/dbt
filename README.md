# DBT Enhanced
## This is free and unencumbered software released into the public domain.<br>
Dynamic dbtools framework, easy to use, supports SQLite and MySQL.<br>
![Visitor count](https://shields-io-visitor-counter.herokuapp.com/badge?page=slowsient.dbt)<br>

## Simple syntax!
<details>
<summary>Very simple usage (click this to expand)</summary>

```php
<?php
require_once "inc/lib/DBTCore.php";
// you can make it understandable
class tbl_test0 extends DBTTable { static $DBIndex = 0, $Name = "test", $Key = "id"; }
// or make it harder but easier to write
class tbl_test2 extends DBTTable { const qq = "0 test2sqlite id"; }
```

</details>

## Tired of $_GET, $_POST, ...???
<details>
<summary>Then use <code>GGet33()</code>, <code>GPost33()</code>, <code>GFile33()</code>... (click this to expand)</summary>

```php
<?php
// DBTCore includes ReqHelpers.php by default!
require_once "inc/lib/DBTCore.php";
// no more isset(), no more assignments!
if (GGet33("getparam1", $g1, "getparam2", $g2) && GFile33("a", $file1) && GPost33("b", $b, "c", $c)) {
  echo "<pre>";
  echo "g1=$g1;g2=$g2;";
  print_r($file1);
  echo "b=$b;c=$c;";
  echo "</pre>";
}
?>
<div>
<form action="?getparam1=getvalue1&getparam2=getvalue2" enctype="multipart/form-data" method="post">
	<input type="file" name="a" id="">
	<input type="text" name="b" value="b's value">
	<input type="text" name="c" value="c's value">
	<button>ok</button>
</form>
</div>
```

</details>

## Short summary
DBT supports multiple database connections.<br>
DBT supports SQLite and MySQL.<br>

## Examples
All examples are here: https://github.com/SlowsieNT/dbt/blob/main/src/examples.php
