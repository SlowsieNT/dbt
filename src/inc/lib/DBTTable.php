<?php
class DBTTable {
	static $RegisteredClasses = array();
	// Never, ever use these:
	static $DBIndex = 0, $Name = "", $Key = "id";
	const qq = ",,";
	// tt() returns array(dbtype=0, dbindex=1, tablename=2, tableKey=3, tableCols=4)
	// Used to retrieve information of descending classes' table information
	// Function tt() is used to auto-register the class
	// thus tt() is required to be called almost everywhere!
	public static function tt() {
		self::Register();
		$CCName = get_called_class();
		return self::$RegisteredClasses[$CCName];
	}
	public static function TTSetIdx($data, $aIdx, $aVal) {
		$CCName = get_called_class();
		// $data[dbtype=0, dbindex=1, tablename=2, tableKey=3, tableCols=4]
		self::$RegisteredClasses[$CCName][$aIdx] = $aVal;
	}
	public static function Register() {
		$CCName = get_called_class();
		if (isset(self::$RegisteredClasses[$CCName]))
			return;
		// Watch this shitshow:
		$eti = DBT::GCV($CCName);
		// Now prepare further
		if (3 <= count($eti[3] = explode(" ", $eti[3]))) {
			$eti[0] = isset($eti[3][0]) ? $eti[3][0] : "";
			$eti[1] = isset($eti[3][1]) ? $eti[3][1] : "";
			$eti[2] = isset($eti[3][2]) ? $eti[3][2] : "";
		}
		$DBIndex = $eti[0];
		$dbtype = DBT::D($DBIndex, 0);
		// $data[dbtype=0, dbindex=1, tablename=2, tableKey=3, tableCols=4]
		$data = array($dbtype, $DBIndex, $eti[1], $eti[2], array());
		// Assign table columns
		$data[4] = self::GetTableColumns(0, $data);
		self::$RegisteredClasses[$CCName] = $data;
	}
	// Retrieve columns from database table, used for dynamic data handling
	public static function GetTableColumns($aIgnoreArgs=1, array $aTInfo=array()) {
		$tInfo = array();
		if ($aIgnoreArgs)
			$tInfo = self::tt(); // dbtype=0, dbindex=1, tablename=2, tableKey=3, tableCols=4
		else {
			if (!count($aTInfo))
				die("GetTableColumns \$aTInfo is empty.");
			$tInfo = $aTInfo;
		}
		return DBT::GetTableInfo($tInfo[0], $tInfo[1], $tInfo[2]);
	}
	// Returns array containing column names in database table.
	public static function GetTableColumnNames() {
		$tInfo = self::tt(); // dbtype=0, dbindex=1, tablename=2, tableKey=3, tableCols=4
		$r = array();
		$dbtype = $tInfo[0];
		// print_r($tInfo);
		foreach ($tInfo[4] as $col) {
			if (0 === $dbtype) {
				// SQLite?
				// $col[cid:int, name:str, type:str, notnull:int, dflt_value:?, pk:int]
				$r[] = $col->name;
			} elseif (1 === $dbtype) {
				// MySQL?
				// $col[Field:str, Type:str, Null("YES","NO"), Key("PRI",""), Default, Extra("", "auto_increment")]
				$r[] = $col->Field;
			}
		}
		return $r;
	}
	public static function Drop() {
		$tInfo = self::tt(); // dbtype=0, dbindex=1, tablename=2, tableKey=3, tableCols=4
		return DBT::Query($tInfo[1], "DROP TABLE IF EXISTS " . self::tt()[2]);
	}
	// Returns id
	public static function LastInsertId() {
		$tInfo = self::tt(); // dbtype=0, dbindex=1, tablename=2, tableKey=3, tableCols=4
		return DBT::LastInsertId($tInfo[1], $tInfo[2]);
	}
	// Returns affected rowCount
	public static function Delete($aFollowedBy = "WHERE 1", array $aUPV = array()) {
		$tInfo = self::tt(); // dbtype=0, dbindex=1, tablename=2, tableKey=3, tableCols=4
		// $aUPV means: array of unprotected values
		$sql = "DELETE FROM $tInfo[2] $aFollowedBy";
		return self::Execute($sql, $aUPV, $tInfo)->rowCount();
	}
	// Returns PDOStatement
	public static function Execute($aSQL, $aOptions = array(), &$aTInfo = null) {
		if (null === $aTInfo)
			$aTInfo = self::tt();
		$pst = DBT::Prepare($aTInfo[1], $aSQL);
		$pst->execute($aOptions);
		return $pst;
	}
	// Returns array, or object
	// If any argument is null then default value is used.
	public static function Select($aSelector = "*", $aFollowedBy = "WHERE 1", $aUPV = array(), $aFetchType = 0, $aFetchMode = PDO::FETCH_OBJ) {
		if (null === $aSelector) $aSelector = "*";
		if (null === $aFollowedBy) $aFollowedBy = "WHERE 1";
		if (null === $aUPV) $aUPV = array();
		if (null === $aFetchType) $aFetchType = 0;
		if (null === $aFetchMode) $aFetchMode = PDO::FETCH_OBJ;
		$tInfo = self::tt(); // dbtype=0, dbindex=1, tablename=2, tableKey=3, tableCols=4
		$sql = "SELECT $aSelector from $tInfo[2] $aFollowedBy";
		$pst = self::Execute($sql, $aUPV, $tInfo);
		if (0 === $aFetchType)
			return $pst->fetchAll($aFetchMode);
		return $pst->fetch($aFetchMode);
	}
	// Returns array, or object
	public static function SelectM($aFetchMode = PDO::FETCH_OBJ, string $aSelector = "*", $aFollowedBy = "WHERE 1", $aUPV = array(), $aFetchType = 0) {
		return self::Select($aSelector, $aFollowedBy, $aUPV, $aFetchType, $aFetchMode);
	}
	// Returns number, or false
	// If any argument is null then default value is used.
	public static function SelectC($aSelector = "count(*) as c", $aFollowedBy = "WHERE 1", $aUPV = array(), $aFetchType = 0) {
		if (null === $aSelector) $aSelector = "count(*) as c";
		if (null === $aFollowedBy) $aFollowedBy = "WHERE 1";
		if (null === $aUPV) $aUPV = array();
		if (null === $aFetchType) $aFetchType = 0;
		$tInfo = self::tt(); // dbtype=0, dbindex=1, tablename=2, tableKey=3, tableCols=4
		$sql = "SELECT $aSelector from $tInfo[2] $aFollowedBy";
		$pst = self::Execute($sql, $aUPV, $tInfo);
		$obj = $pst->fetch(PDO::FETCH_OBJ);
		if ($obj)
			return $obj->c;
		return $obj;
	}
	// Returns last id, or PDOStatement
	public static function Insert(array $aArray, $aIgnoreInto=true, $aNoRetPst=true) {
		$tInfo = self::tt(); // dbtype=0, dbindex=1, tablename=2, tableKey=3, tableCols=4
		$oi = "";
		$dbtype = $tInfo[0];
		$tcols = self::GetTableColumnNames();
		$icols = array(); // insert columns
		$vcols = array(); // values columns (?)
		$vvals = array(); // exec values
		if (-1 !== $dbtype && $aIgnoreInto)
			$oi = $dbtype ? "IGNORE" : "OR IGNORE";
		foreach ($aArray as $key=>$val) {
			if (in_array($key, $tcols)) {
				if (!is_null($val)) {
					$icols[] = $key;
					$vcols[] = "?";
					$vvals[] = $aArray[$key];
				}
			}
		}
		$cols = implode(",", $icols);
		$vcols = implode(",", $vcols);
		// gen sql
		$sql = "INSERT $oi into $tInfo[2] ($cols)values($vcols)";
		// prepare
		$pst = self::Execute($sql, $vvals, $tInfo);
		if ($aNoRetPst)
			return self::LastInsertId();
		return $pst;
	}
	// Returns affected rowCount
	public static function Update(array $aArray, $aUPV = array(), string $aFollowedBy = "where 1") {
		$tInfo = self::tt(); // dbtype=0, dbindex=1, tablename=2, tableKey=3, tableCols=4
		$tcols = self::GetTableColumnNames();
		$qs = array();
		$vvals = array();
		if (is_null($aUPV))
			$aUPV = array();
		elseif (!is_array($aUPV))
			$aUPV = array($aUPV);
		foreach ($aArray as $key => $val)
			if (in_array($key, $tcols))
				if (null !== $val) {
					$qs[] = "$key=?";
					$vvals[] = $val;
				}
		$vvals = array_merge($vvals, $aUPV);
		$qs = implode(",", $qs);
		// gen sql
		$sql = "UPDATE $tInfo[2] set $qs $aFollowedBy";
		// prepare
		$pst = self::Execute($sql, $vvals, $tInfo);
		return $pst->rowCount();
	}
	// Returns PDOStatement
	public static function RenameColumn_SQLite($aColumn, $aNewName) {
		$tInfo = self::tt(); // dbtype=0, dbindex=1, tablename=2, tableKey=3, tableCols=4
		$sql = "ALTER TABLE $tInfo[2] RENAME COLUMN $aColumn TO $aNewName";
		// prepare
		return self::Execute($sql, null, $tInfo);
	}
	// Returns PDOStatement
	public static function AddColumn($aColumn, $aDefinition) {
		$tInfo = self::tt(); // dbtype=0, dbindex=1, tablename=2, tableKey=3, tableCols=4
		$sql = "ALTER TABLE $tInfo[2] ADD COLUMN $aColumn $aDefinition";
		// prepare
		return self::Execute($sql, null, $tInfo);
	}
	// Returns PDOStatement
	public static function DropColumn($aColumn) {
		$tInfo = self::tt(); // dbtype=0, dbindex=1, tablename=2, tableKey=3, tableCols=4
		$sql = "ALTER TABLE $tInfo[2] DROP COLUMN $aColumn";
		// prepare
		return self::Execute($sql, null, $tInfo);
	}
	// Returns SQL string
	public static function Create($aDBIndex = 0, $aTableName = "test", array $aFields = array()) {
		$strFields = "
		text_64kb TEXT,
		text_16mb MEDIUMTEXT,
		blob_64kb BLOB,
		blob_16mb MEDIUMBLOB,
		floating_field DOUBLE,
		int_field INT";
		if (count($aFields))
			$strFields = implode(",", $aFields);
		$sql = "CREATE TABLE IF NOT EXISTS $aTableName ($strFields);";
		// query
		return DBT::Query($aDBIndex, $sql);
	}
	// -------------- Extensions --------------
	// Returns object, or false
	public static function GetByKey($aValue, int $aFetchType=1) {
		$tInfo = self::tt(); // dbtype=0, dbindex=1, tablename=2, tableKey=3, tableCols=4
		return self::Select("*", "where $tInfo[3]=?", array($aValue), $aFetchType);
	}
	// Returns last id, or PDOStatement
	// CAUTION: $aValues is array of values that must match column order in database table.
	public static function InsertV(array $aValues, $aIgnoreInto=true, $aNoRetPst=true) {
		$cols = self::GetTableColumnNames();
		$vals = array();
		$TL = count($cols);
		$L = count($aValues);
		if ($L > $TL)
			$L = $TL;
		for ($i = 0; $i < $L; $i++) {
			if (!is_null($aValues[$i]))
				$vals[$cols[$i]] = $aValues[$i];
		}
		return self::Insert($vals, $aIgnoreInto, $aNoRetPst);
	}
	// Returns last id, or PDOStatement
	// CAUTION: Values must match column order in database table.
	// CAUTION: AVOID using this function.
	// EXAMPLE: tbl_test0::Inserts(1,1,  "fieldvalue", null, "fieldvalue3")
	public static function Inserts($aIgnoreInto=true, $aNoRetPst=true) {
		$fp = array_slice(func_get_args(), 2);
		return self::InsertV($fp, $aIgnoreInto, $aNoRetPst);
	}
	// Returns affected rowCount
	// CAUTION: $aValues is array of values that match column order in database table.
	public static function UpdateV(array $aValues, $aUPV = array(), $aFollowedBy = "where 1", $aIgnoreInto=true, $aNoRetPst=true) {
		$cols = self::GetTableColumnNames();
		$vals = array();
		$TL = count($cols);
		$L = count($aValues);
		if ($L > $TL)
			$L = $TL; 
		for ($i = 0; $i < $L; $i++)
			if (!is_null($aValues[$i]))
				$vals[$cols[$i]] = $aValues[$i];
		return self::Update($vals, $aUPV, $aFollowedBy);
	}
	
}
?>