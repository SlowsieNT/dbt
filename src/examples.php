<body bgcolor="0" text="gray">
<?php
require_once "inc/lib/DBTCore.php";

class tbl_test0 extends DBTTable { static $DBIndex = 0, $Name = "test3", $Key = "id"; }
class tbl_test2 extends DBTTable { const qq = "0 test2 id"; }
class tbl_test1 extends DBTTable { const qq = "1 test id"; }


$dbi  = DBT::NewSQLite("test.db");
$dbi2 = DBT::NewMySQL("test", "root");

// Drop 'em!
#tbl_test0::Drop();
#tbl_test1::Drop();
#tbl_test2::Drop();

// Make ANEW!!
DBTTable::Create($dbi, "test3", array(
	"id integer primary key autoincrement",
	"title TEXT",
	"content MEDIUMTEXT",
	//"id integer unique",
	"image MEDIUMBLOB",
	"pdate DOUBLE",
));
DBTTable::Create($dbi, "test2", array(
	"id integer primary key autoincrement",
	"id2 TEXT unique",
	"title TEXT",
	"content MEDIUMTEXT",
	"image MEDIUMBLOB",
	"pdate DOUBLE",
));
DBTTable::Create($dbi2, "test", array(
	"id TEXT unique",
	"title TEXT",
	"content MEDIUMTEXT",
	"image MEDIUMBLOB",
	"pdate DOUBLE",
));

echo "-------- Uniqueing SQLite --------<br>\r\n";
$col = "id2";
$ii = tbl_test2::IsUnique($col);
print_r(tbl_test2::GetUniques());
echo "col=$col,unique=".intval($ii)."<bR>\r\n";

echo "-------- Uniqueing MySQL --------<br>\r\n";
$col = "id";
$ii = tbl_test1::IsUnique($col);
print_r(tbl_test1::GetUniques());
echo "col=$col,unique=".intval($ii)."<bR>\r\n";

echo "-------- Column Definitions --------<br>\r\n";
print_r(tbl_test1::GetCDef("id"));
print_r(tbl_test2::GetCDef("id"));
echo "-------- Etc --------<br>\r\n";

// Delete 'em!
tbl_test0::Delete();
tbl_test2::Delete();
tbl_test1::Delete();

// Do 'em column management
tbl_test0::DropColumn("yo1");
tbl_test0::DropColumn("yo2");
tbl_test0::AddColumn("yo1", "int");
tbl_test0::RenameColumn_SQLite("yo1", "yo2");


// insert 'em VALUES by DB table column order
tbl_test0::InsertV(array( 1, 2, 3, 1+microtime(1) ));
tbl_test0::InsertV(array( 2, 2, 3, 2+microtime(1) ));
tbl_test0::InsertV(array( 3, 2, 3, 3+microtime(1) ));
// now - another!
tbl_test1::InsertV(array( 4, 3, 4, 1+microtime(1) ));
tbl_test1::InsertV(array( 5, 3, 4, 2+microtime(1) ));
tbl_test1::InsertV(array( 6, 3, 4, 3+microtime(1) ));

// Update 'em!
tbl_test0::UpdateV(array( null, DBT::RandHex(5) ), 3, "where image=?");
tbl_test1::UpdateV(array( null, DBT::RandHex(5) ), 4, "where image=?");

// Select 'em!
echo "-------- Select-ing --------<br>\r\n";
print_r(tbl_test0::Select());
print_r(tbl_test1::Select());

// Select 'em counts!
echo "-------- Select-ing count --------<br>\r\n";
var_dump(tbl_test0::SelectC());
var_dump(tbl_test1::SelectC());


// Testing uuid
echo "<br><br>Testing uuid:<br>\r\n";
$uuid = DBT::uuid();
$e = $uuid;
echo "<br>$e<br>\r\n";

// Testing DBTMime
echo "<br><br>Testing DBTMime:<br>\r\n";
echo json_encode(DBTMime::From(2, "zip"));
echo "<br>\r\n";

// Testing str_linebr
echo "<br><br>Testing str_linebr:<br>\r\n";
echo str_linebr("\ra\r\rb\r\nc\nd","","");


echo "<br>\r\n<br>";


echo "<pre style=font-size:20px>";
foreach (dbt::MakeClassesForTables(0, 0) as $code) {
	echo "$code<br>\r\n";
}
// Short class definition with: qq = "{dbIndex} {tableName} {primaryKeyName}";
foreach (dbt::MakeClassesForTables(1, 1, true) as $code) {
	echo "$code<br>\r\n";
}
echo "</pre>";


// old way:
if (isset($_GET["getparam1"])) {
	$g1 = $_GET["getparam1"];
	echo $g1;
}

// dbt's way:
if (GGet33("getparam1", $g1)) {
	echo $g1;
}

if (GGet33("getparam1", $g1, "getparam2", $g2) && GFile33("a", $file1) && GPost33("b", $b, "c", $c)) {
	echo "<pre>";
	echo "g1=$g1;g2=$g2;";
	print_r($file1);
	echo "b=$b;c=$c;";
	echo "</pre>";
}

?>
<script>

</script>
<div>
<form action="?getparam1=getvalue1&getparam2=getvalue2" enctype="multipart/form-data" method="post">
	<input type="file" name="a" id="">
	<input type="text" name="b" value="b's value">
	<input type="text" name="c" value="c's value">
	<button>ok</button>
</form>
</div>
