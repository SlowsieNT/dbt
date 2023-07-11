<?php
class DBTTable {
	static $RegisteredClasses = array();
	// Don't assign these in this class:
	static $DBIndex = 0, $Name = "", $Key = "id";
	const qq = "";
	// tt() returns array(dbtype=0, dbindex=1, tablename=2, tableKey=3, tableCols=4, tableCols2=5)
	// Used to retrieve information of descending classes' table information
	// Function tt() is used to auto-register the class
	// thus tt() is required to be called almost everywhere!
	public static function tt() {
		self::TTRegister();
		$CCName = get_called_class();
		return self::$RegisteredClasses[$CCName];
	}
	public static function TTRegister() {
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
		// $data[dbtype=0, dbindex=1, tablename=2, tableKey=3, tableCols=4, tableCols2=5]
		$data = array($dbtype, $DBIndex, $eti[1], $eti[2], array());
		// Assign table columns
		self::ResolveColumns($dbtype, $DBIndex, $CCName, $data);
	}
	static function ResolveColumns($aDBType, $aDBIndex, $aCCName, array $atInfo) {
		// dbtype=0, dbindex=1, tablename=2, tableKey=3, tableCols=4, tableCols2=5
		$atInfo[4] = DBT::GetTableInfo($aDBType, $aDBIndex, $atInfo[2]);
		$atInfo[5] = array();
		foreach ($atInfo[4] as $col) {
			$cname = false;
			if (isset($col->name))
				$cname = $col->name;
			elseif (isset($col->Field))
				$cname = $col->Field;
			if ($cname)
				$atInfo[5][$cname] = $col;
		}
		self::$RegisteredClasses[$aCCName] = $atInfo;
	}
	// Should be called after altering columns
	public static function UpdateColumns(&$aCCName = null, &$atInfo = null) {
		if (null === $aCCName)
			$aCCName = get_called_class();
		if (null === $atInfo)
			$atInfo = self::tt(); // dbtype=0, dbindex=1, tablename=2, tableKey=3, tableCols=4, tableCols2=5
		self::$RegisteredClasses[$aCCName][4] = DBT::GetTableInfo($atInfo[0], $atInfo[1], $atInfo[2]);
		self::ResolveColumns($atInfo[0], $atInfo[1], $aCCName, $atInfo);
	}
	// Returns array containing column names in database table.
	public static function GetTableColumnNames(&$atInfo = null) {
		if (null === $atInfo)
			$atInfo = self::tt(); // dbtype=0, dbindex=1, tablename=2, tableKey=3, tableCols=4, tableCols2=5
		$r = array();
		$dbtype = $atInfo[0];
		// print_r($atInfo);
		foreach ($atInfo[4] as $col) {
			if (isset($col->name))
				$r[] = $col->name;
			elseif (isset($col->Field))
				$r[] = $col->Field;
		}
		return $r;
	}
	// Returns id
	public static function LastInsertId() {
		$tInfo = self::tt(); // dbtype=0, dbindex=1, tablename=2, tableKey=3, tableCols=4, tableCols2=5
		return DBT::LastInsertId($tInfo[1], $tInfo[2]);
	}
	// Returns affected rowCount
	public static function Delete($aFollowedBy = "WHERE 1", array $aUPV = array()) {
		$tInfo = self::tt(); // dbtype=0, dbindex=1, tablename=2, tableKey=3, tableCols=4, tableCols2=5
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
		$tInfo = self::tt(); // dbtype=0, dbindex=1, tablename=2, tableKey=3, tableCols=4, tableCols2=5
		if ("*" == $aSelector)
			$aSelector = implode(",", self::GetTableColumnNames());
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
		$selDef = "count(*) as c";
		if (null === $aSelector) $aSelector = $selDef;
		if (null === $aFollowedBy) $aFollowedBy = "WHERE 1";
		if (null === $aUPV) $aUPV = array();
		if (null === $aFetchType) $aFetchType = 0;
		$tInfo = self::tt(); // dbtype=0, dbindex=1, tablename=2, tableKey=3, tableCols=4, tableCols2=5
		$sql = "SELECT $aSelector from $tInfo[2] $aFollowedBy";
		$pst = self::Execute($sql, $aUPV, $tInfo);
		$obj = $pst->fetch(PDO::FETCH_OBJ);
		if ($obj)
			return $obj->c;
		return $obj;
	}
	// Returns last id, or PDOStatement
	public static function Insert(array $aArray, $aIgnoreInto=true, $aNoRetPst=true) {
		$tInfo = self::tt(); // dbtype=0, dbindex=1, tablename=2, tableKey=3, tableCols=4, tableCols2=5
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
		$tInfo = self::tt(); // dbtype=0, dbindex=1, tablename=2, tableKey=3, tableCols=4, tableCols2=5
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
	// Retrieve column information
	public static function GetCDef($aColumnName, &$atInfo = null) {
		if (null === $atInfo)
			$atInfo = self::tt(); // dbtype=0, dbindex=1, tablename=2, tableKey=3, tableCols=4, tableCols2=5
		if (!isset($atInfo[5][$aColumnName]))
			return false;
		$cdef = $atInfo[5][$aColumnName];
		$ret = array();
		if (isset($cdef->Field)) $ret["name"] = $cdef->Field;
		else if (isset($cdef->name)) $ret["name"] = $cdef->name;
		// primary key?
		if (isset($cdef->Key)) $ret["pk"] = intval("PRI"==$cdef->Key);
		else if (isset($cdef->pk)) $ret["pk"] = $cdef->pk;
		// column type?
		if (isset($cdef->Type)) $ret["type"] = $cdef->Type;
		else if (isset($cdef->type)) $ret["type"] = $cdef->type;
		// not null?
		if (isset($cdef->Null)) $ret["notnull"] = intval("NO" == $cdef->Null);
		else if (isset($cdef->notnull)) $ret["notnull"] = $cdef->notnull;
		// default value?
		if (isset($cdef->Default)) $ret["default"] = $cdef->Default;
		else if (isset($cdef->dflt_value)) $ret["default"] = $cdef->dflt_value;
		else $ret["default"] = "";
		return (object)$ret;
	}
	// Experimental
	// Can be used to rename, usually works for MySQL
	// It will execute regardless if $aDefinition is null
	// Returns PDOStatement
	public static function ChangeCDef($aColumnName, $aNewName, $aDefinition=null) {
		$tInfo = self::tt(); // dbtype=0, dbindex=1, tablename=2, tableKey=3, tableCols=4, tableCols2=5
		if (null === $aDefinition) {
			$cdef = self::GetCDef($aColumnName, $tInfo);
			if (false === $cdef) return false;
			$aDefinition = $cdef->type;
		}
		$sql = "ALTER TABLE $tInfo[2] CHANGE `$aColumnName` `$aNewName` $aDefinition";
		// prepare
		$pst = self::Execute($sql, null, $tInfo);
		self::UpdateColumns();
		return $pst;
	}
	// This may also work on MySQL8+
	// Returns PDOStatement
	public static function RenameColumn_SQLite($aColumn, $aNewName) {
		$tInfo = self::tt(); // dbtype=0, dbindex=1, tablename=2, tableKey=3, tableCols=4, tableCols2=5
		$sql = "ALTER TABLE $tInfo[2] RENAME COLUMN $aColumn TO $aNewName";
		// prepare
		$pst = self::Execute($sql, null, $tInfo);
		self::UpdateColumns();
		return $pst;
	}
	// Returns PDOStatement
	public static function AddColumn($aColumn, $aDefinition) {
		$tInfo = self::tt(); // dbtype=0, dbindex=1, tablename=2, tableKey=3, tableCols=4, tableCols2=5
		$sql = "ALTER TABLE $tInfo[2] ADD COLUMN $aColumn $aDefinition";
		// prepare
		$pst = self::Execute($sql, null, $tInfo);
		self::UpdateColumns();
		return $pst;
	}
	// $aColumn is case sensitive
	// Returns PDOStatement, or false if not found
	public static function DropColumn($aColumn) {
		$tInfo = self::tt(); // dbtype=0, dbindex=1, tablename=2, tableKey=3, tableCols=4, tableCols2=5
		if (false === self::GetCDef($aColumn))
			return false;
		$sql = "ALTER TABLE $tInfo[2] DROP COLUMN $aColumn";
		// prepare
		$pst = self::Execute($sql, null, $tInfo);
		self::UpdateColumns();
		return $pst;
	}
	// Returns PDOStatement
	public static function Drop() {
		$tInfo = self::tt(); // dbtype=0, dbindex=1, tablename=2, tableKey=3, tableCols=4, tableCols2=5
		$pst = DBT::Query($tInfo[1], "DROP TABLE IF EXISTS " . self::tt()[2]);
		return $pst;
	}
	// -------------- Extensions --------------
	// Returns object, or false
	public static function GetByKey($aValue, int $aFetchType=1) {
		$tInfo = self::tt(); // dbtype=0, dbindex=1, tablename=2, tableKey=3, tableCols=4, tableCols2=5
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