<?php
require_once "DBTUtils.php";
require_once "DBTMime.php"; // DBTMime is not necessary for now.
require_once "DBTBaseConv.php";
require_once "DBTTable.php";
class DBT {
	public static $m_DBs = array();
	// Returns int
	public static function NewMySQL($aName="", $aUser="root", $aPass="", $aHost="localhost", $aOptions=null) {
		if (null === $aUser) $aUser = "root";
		$dbi = self::NewDsnConnect(1, "mysql:host=$aHost;dbname=$aName;charset=utf8mb4", $aUser, $aPass, $aOptions);
		return $dbi;
	}
	// Returns int
	public static function NewSQLite($aFilename="", $aUseWAL=true) {
		$dbi = self::NewDsnConnect(0, "sqlite:$aFilename");
		if ($aUseWAL)
			self::Query($dbi, "PRAGMA journal_mode=WAL;");
		return $dbi;
	}
	// Returns int
	public static function NewDsnConnect(int $aDBType, string $aDSN, $aUser="root", $aPass="", $aOptions=null) {
		self::$m_DBs[] = array($aDBType, new PDO($aDSN, $aUser, $aPass, $aOptions));
		return -1 + count(self::$m_DBs);
	}
	// Returns PDO
	// Returns array of PDO if $aIndex equals -1 
	public static function D(int $aIndex, int $aIndex2 = 1, bool $aRetAll = false) {
		if (-1 == $aIndex)
			return self::$m_DBs;
		if (!$aRetAll)
			return self::$m_DBs[$aIndex][$aIndex2];
		return self::$m_DBs[$aIndex];
	}
	public static function Exec(int $aIndex, $aParams) {
		return self::D($aIndex)->exec($aParams);
	}
	public static function Query(int $aIndex, string $aSQLStatement) {
		return self::D($aIndex)->query($aSQLStatement);
	}
	public static function ListTables(int $aType = 0, int $aIndex = 0) {
		$sql = "SHOW TABLES";
		if (0 === $aType)
			$sql = "SELECT name FROM sqlite_schema WHERE type='table' and not name LIKE 'sqlite_%' ORDER BY name;";
		$pst = self::Query($aIndex, $sql);
		return $pst->fetchAll(PDO::FETCH_COLUMN);
	}
	public static function MakeClassesForTables(int $aDBType, int $aIndex, bool $aShortDef=false) {
		$rbuf = array();
		foreach (self::ListTables($aDBType, $aIndex) as $tableName) {
			$ti = self::GetTableInfo($aDBType, $aIndex, $tableName, PDO::FETCH_ASSOC);
			$tkey = "";
			foreach ($ti as $v) {
				foreach ($v as $kk => $vv) {
					if ($vv && ($kk == "Key" || $kk == "pk")) {
						if ($kk == "Key") $tkey = $v["Field"];
						if ($kk == "pk") $tkey = $v["name"];
						break;
					}
				}
			}
			if (!$aShortDef)
				$rbuf[] = "class $tableName extends DBTTable { static \$DBIndex = $aIndex, \$Name = \"$tableName\", \$Key = \"$tkey\"; }";
			else $rbuf[] = "class $tableName extends DBTTable { const qq = \"$aIndex $tableName $tkey\"; }";
		}
		return $rbuf;
	}
	public static function GetTableInfo($aDBType, $aIndex, $aTableName, $aFetchMode=PDO::FETCH_OBJ) {
		$sql = "PRAGMA table_info('$aTableName')";
		$dbtype = $aDBType;
		if (0 !== $dbtype)
			$sql = "DESCRIBE $aTableName";
		// echo "$sql\r\n";
		return DBT::Query($aIndex, $sql)->FetchAll($aFetchMode);
	}
	public static function GetTableColumnNames($aDBType, $aIndex, $aTableName) {
		$ti = self::GetTableInfo($aDBType, $aIndex, $aTableName);
		$r = array();
		foreach ($ti as $col) {
			if (isset($col->name))
				$r[] = $col->name;
			elseif (isset($col->Field))
				$r[] = $col->Field;
		}
		return $r;
	}
	public static function GetAttribute(int $aIndex, int $aAttribute){ return self::D($aIndex)->getAttribute($aAttribute); }
	public static function SetAttribute(int $aIndex, int $aAttribute, $aValue){ return self::D($aIndex)->setAttribute($aAttribute, $aValue); }
	public static function LastInsertId(int $aIndex, $aName = null) { return self::D($aIndex)->lastInsertId($aName); }
	public static function Quote(int $aIndex, string $aStr) { return self::D($aIndex)->quote($aStr); }
	public static function Prepare(int $aIndex, string $aQuery, array $aOptions = array()) { return self::D($aIndex)->prepare($aQuery, $aOptions); }
	public static function InTransaction(int $aIndex=0) { return self::D($aIndex)->inTransaction(); }
	public static function GetAvailableDrivers(int $aIndex=0) { return self::D($aIndex)->getAvailableDrivers(); }
	public static function RollBack(int $aIndex=0) { return self::D($aIndex)->rollBack(); }
	public static function ErrorCode(int $aIndex=0) { return self::D($aIndex)->errorCode(); }
	public static function ErrorInfo(int $aIndex=0) { return self::D($aIndex)->errorInfo(); }
	public static function BeginTransaction(int $aIndex=0) { return self::D($aIndex)->beginTransaction(); }
	public static function Commit(int $aIndex=0) { return self::D($aIndex)->commit(); }
	public static function S($aStr) {
		// May need rework, who knows, who cares?
		$aStr = str_replace("\r", "\\r", $aStr);
		$aStr = str_replace("\n", "\\n", $aStr);
		$aStr = str_replace("\t", "\\t", $aStr);
		return addslashes($aStr);
	}
	// Returns array (dbIndex, tableName, tableKey)
	public static function GCV($aCCName) {
		// When you don't care about Reflection be like:
		return eval("return array($aCCName::\$DBIndex,$aCCName::\$Name,$aCCName::\$Key,$aCCName::qq);");
	}
	public static function RandStr($aLength, $aAdd="", $aChars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
		if ($aAdd)
			$aChars .= $aAdd;
		for ($i = 0, $rstr = '', $L = strlen($aChars); $i < $aLength; $i++)
			$rstr .= $aChars[mt_rand(0, $L-1)];
		return $rstr;
	}
	public static function RandHex($aLength) { return self::RandStr($aLength, "", "0123456789ABCDEF"); }
	public static function RandHex2($aLength) { return self::RandStr($aLength, "", "0123456789abcdef"); }
	public static function R($aM, $aMx) { return mt_rand($aM, $aMx); }
	public static function RX($aMax) { return mt_rand(0, $aMax); }
	// Returns random guid as BaseN string
	public static function uuid($aBase=9e9, $aBaseIndex=null) {
		// strlen: Min=18, Avg=24, Max=24;
		$s = BConv::Enc(mt_rand(0, 65535), $aBaseIndex, $aBase);
		$s .= BConv::Enc(mt_rand(0, 65535), $aBaseIndex, $aBase);
		$s .= BConv::Enc(mt_rand(0, 65535), $aBaseIndex, $aBase);
		$s .= BConv::Enc(mt_rand(0, 4095) | 16384, $aBaseIndex, $aBase);
		$s .= BConv::Enc(mt_rand(0, 16383) | 32768, $aBaseIndex, $aBase);
		$s .= BConv::Enc(mt_rand(0, 65535), $aBaseIndex, $aBase);
		$s .= BConv::Enc(mt_rand(0, 65535), $aBaseIndex, $aBase);
		$s .= BConv::Enc(mt_rand(0, 65535), $aBaseIndex, $aBase);
		return $s;
	}
}
?>
