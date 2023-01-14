<?php
// Need to call Initialize()
BConv::Initialize();
class BConv {
	static $LBase = array(); // Allocated at runtime.
	// $UseURLSafe is related to array $URLSafeSymbols
	// $UseURLSafe if false THEN $AllChars is used unless Enc/Dec $aIndex is not $DefaultBaseIndex
	static $UseURLSafe = true;
	static $URLSafeIndex = 2; // Refers to array $URLSafeSymbols, default: 2
	protected static $URLSafeSymbols = array(
		"!'()*-._~", "!-._~", "-._" // $URLSafeIndex = 2 recommended
	);
	const
		Numbers = "0123456789",
		ALPHA1 = "abcdefghijklmnopqrstuvwxyz",
		ALPHA2 = "ABCDEFGHIJKLMNOPQRSTUVWXYZ",
		Symbols = " !\"#$%&'()*+,-./:;<=>?@[\\]^_`{|}~";
	protected static $DefaultChars = ""; // Allocated at runtime, same as $AllChars if not $UseURLSafe.
	protected static $DefaultCharsURLSafe = ""; // Allocated at runtime
	protected static $AllChars = ""; // Allocated at runtime
	protected static $CurrentBaseIndex = -1; // Allocated at runtime, likely 0
	protected static $DefaultBaseIndex = -1; // Allocated at runtime, likely 0
	protected static $AllCharsBaseIndex = -1; // Allocated at runtime, likely 1
	public static function GetAlphanum($aPrefix="", $aPostfix="") {
		return $aPrefix . self::Numbers . self::ALPHA1 . self::ALPHA2 . $aPostfix;
	}
	public static function GetAlpha($aPrefix="", $aPostfix="") {
		return $aPrefix . self::ALPHA1 . self::ALPHA2 . $aPostfix;
	}
	public static function GetSymbols() { return self::Symbols; }
	public static function NewBase(string $aChars) {
		self::$LBase[] = array(
			$aChars,
			strlen($aChars),
			self::GenerateTable($aChars)
		);
		return -1 + count(self::$LBase);
	}
	public static function GenerateTable($aChars) {
		$arr = array();
		for ($i = 0, $L = strlen($aChars); $i < $L; $i++)
			$arr[$aChars[$i]] = $i;
		return $arr;
	}
	public static function Initialize() {
		// All chars
		if (-1 === self::$AllCharsBaseIndex) {
			self::$AllChars = self::GetAlphanum("", self::Symbols);
			self::$AllCharsBaseIndex = self::NewBase(self::$AllChars);
		}
		// Default chars
		if (-1 === self::$DefaultBaseIndex) {
			self::$DefaultCharsURLSafe = self::GetAlphanum("", self::$URLSafeSymbols[self::$URLSafeIndex]);
			self::$DefaultChars = self::$DefaultCharsURLSafe;
			self::$DefaultBaseIndex = self::NewBase(self::$DefaultChars);
		}
		// Adjust default index
		if (self::$UseURLSafe) self::$CurrentBaseIndex = self::$DefaultBaseIndex;
		else self::$CurrentBaseIndex = self::$AllCharsBaseIndex;
	}
	// if $aIndex is different than $DefaultBaseIndex, and UseURLSafe is defined, then UseURLSafe is ignored.
	public static function Enc($aValue, $aIndex=null, int $aBase=-1) {
		if (null === $aIndex) $aIndex = self::$CurrentBaseIndex;
		$aValue = @intval($aValue);
		$rval = "";
		// Use AllChars if not $UseURLSafe, and aIndex is default
		if (!self::$UseURLSafe && $aIndex != self::$DefaultBaseIndex)
			$aIndex = self::$AllCharsBaseIndex;
		$chars = self::$LBase[$aIndex][0];
		$charsLen = self::$LBase[$aIndex][1];
		if (-1 != $aBase && $aBase <= $charsLen)
			$charsLen = $aBase;
		if (0 == $aValue)
			return $chars[0];
		while ($aValue) {
			$rval = $chars[$aValue % $charsLen] . $rval;
			$aValue /= $charsLen;
			$aValue = floor($aValue);
		}
		return $rval;
	}
	// if $aIndex is different than $DefaultBaseIndex, and UseURLSafe is defined, then UseURLSafe is ignored.
	public static function Dec($aValue, $aIndex=null, int $aBase=-1) {
		if (null === $aIndex) $aIndex = self::$CurrentBaseIndex;
		$aValue = "$aValue";
		$rval = 0;
		// Use AllChars if not urlsafe, and aIndex is default (0)
		if (!self::$UseURLSafe && $aIndex != self::$DefaultBaseIndex) $aIndex = self::$AllCharsBaseIndex;
		$aValue = strrev($aValue);
		$table = self::$LBase[$aIndex][2];
		$charsLen = self::$LBase[$aIndex][1];
		if (-1 != $aBase && $aBase <= $charsLen)
			$charsLen = $aBase;
		for ($i = 0, $L = strlen($aValue); $i < $L; $i++) {
			$chr = $aValue[$i];
			$rval += pow($charsLen, $i) * $table[$chr];
		}
		return $rval;
	}
}
?>