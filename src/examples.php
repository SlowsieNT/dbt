<body bgcolor="#111" text="gray">
<?php
require_once "inc/lib/DBTCore.php";

class tbl_test0 extends DBTTable { static $DBIndex = 0, $Name = "test", $Key = "id"; }
class tbl_test2 extends DBTTable { const qq = "0 test2sqlite id"; }
class tbl_test1 extends DBTTable { const qq = "1 test id"; }

$dbi  = DBT::NewSQLite("test.db");
$dbi2 = DBT::NewMySQL("test", "root");

// Drop 'em!
tbl_test0::Drop();
tbl_test1::Drop();
tbl_test2::Drop();

// Make ANEW!!
DBTTable::Create($dbi, "test", array(
	"id integer primary key autoincrement",
	"title TEXT",
	"content MEDIUMTEXT",
	"image MEDIUMBLOB",
	"pdate DOUBLE",
));
DBTTable::Create($dbi, "test2sqlite", array(
	"title TEXT",
	"content MEDIUMTEXT",
	"image MEDIUMBLOB",
	"pdate DOUBLE",
));
DBTTable::Create($dbi2, "test", array(
	"title TEXT",
	"content MEDIUMTEXT",
	"image MEDIUMBLOB",
	"pdate DOUBLE",
));

// Delete 'em!
tbl_test0::Delete();
tbl_test1::Delete();

// insert 'em VALUES by DB table column order
tbl_test0::InsertV(array( 1, 2, 3, microtime(1) ));
tbl_test0::InsertV(array( 2, 2, 3, microtime(1) ));
// now - another!
tbl_test1::InsertV(array( 3, 3, 4, microtime(1) ));
tbl_test1::InsertV(array( 4, 3, 4, microtime(1) ));

// Update 'em!
tbl_test0::UpdateV(array( null, DBT::RandHex(5) ), 3, "where image=?");
tbl_test1::UpdateV(array( null, DBT::RandHex(5) ), 4, "where image=?");

// Do 'em column management
tbl_test0::AddColumn("yo", "int");
tbl_test0::AddColumn("yo1", "int");
tbl_test0::RenameColumn_SQLite("yo1", "yo2");
tbl_test0::DropColumn("yo");

// Select 'em!
//print_r(tbl_test0::Select());
//print_r(tbl_test1::Select());

// Select 'em counts!
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

?>
<body text="gray" bgcolor="#111">
