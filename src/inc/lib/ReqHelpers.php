<?php
function Request_GPFSC_TypeStr($aType) {
	$vars = array("_GET", "_POST", "_FILES", "_SESSION", "_COOKIE");
	return $vars[intval($aType) % count($vars)];
}
// Getters
function Request_GPFSC_Get33($aType = 0, $aKey1 = "", &$aOutVar1 = null, $aKey2 = "", &$aOutVar2 = null, $aKey3 = "", &$aOutVar3 = null, $aKey4 = "", &$aOutVar4 = null, $aKey5 = "", &$aOutVar5 = null, $aKey6 = "", &$aOutVar6 = null, $aKey7 = "", &$aOutVar7 = null, $aKey8 = "", &$aOutVar8 = null, $aKey9 = "", &$aOutVar9 = null, $aKey10 = "", &$aOutVar10 = null, $aKey11 = "", &$aOutVar11 = null, $aKey12 = "", &$aOutVar12 = null, $aKey13 = "", &$aOutVar13 = null, $aKey14 = "", &$aOutVar14 = null, $aKey15 = "", &$aOutVar15 = null, $aKey16 = "", &$aOutVar16 = null, $aKey17 = "", &$aOutVar17 = null, $aKey18 = "", &$aOutVar18 = null, $aKey19 = "", &$aOutVar19 = null, $aKey20 = "", &$aOutVar20 = null, $aKey21 = "", &$aOutVar21 = null, $aKey22 = "", &$aOutVar22 = null, $aKey23 = "", &$aOutVar23 = null, $aKey24 = "", &$aOutVar24 = null, $aKey25 = "", &$aOutVar25 = null, $aKey26 = "", &$aOutVar26 = null, $aKey27 = "", &$aOutVar27 = null, $aKey28 = "", &$aOutVar28 = null, $aKey29 = "", &$aOutVar29 = null, $aKey30 = "", &$aOutVar30 = null, $aKey31 = "", &$aOutVar31 = null, $aKey32 = "", &$aOutVar32 = null, $aKey33 = "", &$aOutVar33 = null) {
	// Why 33? Because is easy to write 33
	// But why so many??? Did you forget there are maniacs?
	$gvn = Request_GPFSC_TypeStr($aType);
	$AC = 0; $VC = 0;
	for ($i = 1; $i < 34; $i++)
		if (${"aKey$i"} && ++$AC && isset($GLOBALS[$gvn][${"aKey$i"}]) && ++$VC)
			${"aOutVar$i"} = $GLOBALS[$gvn][${"aKey$i"}];
	return $AC && $AC == $VC;
}
// Getter for $_GET
function GGet33($aKey1 = "", &$aOutVar1 = null, $aKey2 = "", &$aOutVar2 = null, $aKey3 = "", &$aOutVar3 = null, $aKey4 = "", &$aOutVar4 = null, $aKey5 = "", &$aOutVar5 = null, $aKey6 = "", &$aOutVar6 = null, $aKey7 = "", &$aOutVar7 = null, $aKey8 = "", &$aOutVar8 = null, $aKey9 = "", &$aOutVar9 = null, $aKey10 = "", &$aOutVar10 = null, $aKey11 = "", &$aOutVar11 = null, $aKey12 = "", &$aOutVar12 = null, $aKey13 = "", &$aOutVar13 = null, $aKey14 = "", &$aOutVar14 = null, $aKey15 = "", &$aOutVar15 = null, $aKey16 = "", &$aOutVar16 = null, $aKey17 = "", &$aOutVar17 = null, $aKey18 = "", &$aOutVar18 = null, $aKey19 = "", &$aOutVar19 = null, $aKey20 = "", &$aOutVar20 = null, $aKey21 = "", &$aOutVar21 = null, $aKey22 = "", &$aOutVar22 = null, $aKey23 = "", &$aOutVar23 = null, $aKey24 = "", &$aOutVar24 = null, $aKey25 = "", &$aOutVar25 = null, $aKey26 = "", &$aOutVar26 = null, $aKey27 = "", &$aOutVar27 = null, $aKey28 = "", &$aOutVar28 = null, $aKey29 = "", &$aOutVar29 = null, $aKey30 = "", &$aOutVar30 = null, $aKey31 = "", &$aOutVar31 = null, $aKey32 = "", &$aOutVar32 = null, $aKey33 = "", &$aOutVar33 = null) {
	return Request_GPFSC_Get33(0, $aKey1, $aOutVar1, $aKey2, $aOutVar2, $aKey3, $aOutVar3, $aKey4, $aOutVar4, $aKey5, $aOutVar5, $aKey6, $aOutVar6, $aKey7, $aOutVar7, $aKey8, $aOutVar8);
}
// Getter for $_POST
function GPost33($aKey1 = "", &$aOutVar1 = null, $aKey2 = "", &$aOutVar2 = null, $aKey3 = "", &$aOutVar3 = null, $aKey4 = "", &$aOutVar4 = null, $aKey5 = "", &$aOutVar5 = null, $aKey6 = "", &$aOutVar6 = null, $aKey7 = "", &$aOutVar7 = null, $aKey8 = "", &$aOutVar8 = null, $aKey9 = "", &$aOutVar9 = null, $aKey10 = "", &$aOutVar10 = null, $aKey11 = "", &$aOutVar11 = null, $aKey12 = "", &$aOutVar12 = null, $aKey13 = "", &$aOutVar13 = null, $aKey14 = "", &$aOutVar14 = null, $aKey15 = "", &$aOutVar15 = null, $aKey16 = "", &$aOutVar16 = null, $aKey17 = "", &$aOutVar17 = null, $aKey18 = "", &$aOutVar18 = null, $aKey19 = "", &$aOutVar19 = null, $aKey20 = "", &$aOutVar20 = null, $aKey21 = "", &$aOutVar21 = null, $aKey22 = "", &$aOutVar22 = null, $aKey23 = "", &$aOutVar23 = null, $aKey24 = "", &$aOutVar24 = null, $aKey25 = "", &$aOutVar25 = null, $aKey26 = "", &$aOutVar26 = null, $aKey27 = "", &$aOutVar27 = null, $aKey28 = "", &$aOutVar28 = null, $aKey29 = "", &$aOutVar29 = null, $aKey30 = "", &$aOutVar30 = null, $aKey31 = "", &$aOutVar31 = null, $aKey32 = "", &$aOutVar32 = null, $aKey33 = "", &$aOutVar33 = null) {
	return Request_GPFSC_Get33(1, $aKey1, $aOutVar1, $aKey2, $aOutVar2, $aKey3, $aOutVar3, $aKey4, $aOutVar4, $aKey5, $aOutVar5, $aKey6, $aOutVar6, $aKey7, $aOutVar7, $aKey8, $aOutVar8);
}
// Getter for $_FILES
function GFile33($aKey1 = "", &$aOutVar1 = null, $aKey2 = "", &$aOutVar2 = null, $aKey3 = "", &$aOutVar3 = null, $aKey4 = "", &$aOutVar4 = null, $aKey5 = "", &$aOutVar5 = null, $aKey6 = "", &$aOutVar6 = null, $aKey7 = "", &$aOutVar7 = null, $aKey8 = "", &$aOutVar8 = null, $aKey9 = "", &$aOutVar9 = null, $aKey10 = "", &$aOutVar10 = null, $aKey11 = "", &$aOutVar11 = null, $aKey12 = "", &$aOutVar12 = null, $aKey13 = "", &$aOutVar13 = null, $aKey14 = "", &$aOutVar14 = null, $aKey15 = "", &$aOutVar15 = null, $aKey16 = "", &$aOutVar16 = null, $aKey17 = "", &$aOutVar17 = null, $aKey18 = "", &$aOutVar18 = null, $aKey19 = "", &$aOutVar19 = null, $aKey20 = "", &$aOutVar20 = null, $aKey21 = "", &$aOutVar21 = null, $aKey22 = "", &$aOutVar22 = null, $aKey23 = "", &$aOutVar23 = null, $aKey24 = "", &$aOutVar24 = null, $aKey25 = "", &$aOutVar25 = null, $aKey26 = "", &$aOutVar26 = null, $aKey27 = "", &$aOutVar27 = null, $aKey28 = "", &$aOutVar28 = null, $aKey29 = "", &$aOutVar29 = null, $aKey30 = "", &$aOutVar30 = null, $aKey31 = "", &$aOutVar31 = null, $aKey32 = "", &$aOutVar32 = null, $aKey33 = "", &$aOutVar33 = null) {
	return Request_GPFSC_Get33(2, $aKey1, $aOutVar1, $aKey2, $aOutVar2, $aKey3, $aOutVar3, $aKey4, $aOutVar4, $aKey5, $aOutVar5, $aKey6, $aOutVar6, $aKey7, $aOutVar7, $aKey8, $aOutVar8);
}
// Getter for $_SESSION
function GSession33($aKey1 = "", &$aOutVar1 = null, $aKey2 = "", &$aOutVar2 = null, $aKey3 = "", &$aOutVar3 = null, $aKey4 = "", &$aOutVar4 = null, $aKey5 = "", &$aOutVar5 = null, $aKey6 = "", &$aOutVar6 = null, $aKey7 = "", &$aOutVar7 = null, $aKey8 = "", &$aOutVar8 = null, $aKey9 = "", &$aOutVar9 = null, $aKey10 = "", &$aOutVar10 = null, $aKey11 = "", &$aOutVar11 = null, $aKey12 = "", &$aOutVar12 = null, $aKey13 = "", &$aOutVar13 = null, $aKey14 = "", &$aOutVar14 = null, $aKey15 = "", &$aOutVar15 = null, $aKey16 = "", &$aOutVar16 = null, $aKey17 = "", &$aOutVar17 = null, $aKey18 = "", &$aOutVar18 = null, $aKey19 = "", &$aOutVar19 = null, $aKey20 = "", &$aOutVar20 = null, $aKey21 = "", &$aOutVar21 = null, $aKey22 = "", &$aOutVar22 = null, $aKey23 = "", &$aOutVar23 = null, $aKey24 = "", &$aOutVar24 = null, $aKey25 = "", &$aOutVar25 = null, $aKey26 = "", &$aOutVar26 = null, $aKey27 = "", &$aOutVar27 = null, $aKey28 = "", &$aOutVar28 = null, $aKey29 = "", &$aOutVar29 = null, $aKey30 = "", &$aOutVar30 = null, $aKey31 = "", &$aOutVar31 = null, $aKey32 = "", &$aOutVar32 = null, $aKey33 = "", &$aOutVar33 = null) {
	if (!session_id()) session_start();
	return Request_GPFSC_Get33(3, $aKey1, $aOutVar1, $aKey2, $aOutVar2, $aKey3, $aOutVar3, $aKey4, $aOutVar4, $aKey5, $aOutVar5, $aKey6, $aOutVar6, $aKey7, $aOutVar7, $aKey8, $aOutVar8);
}
// Getter for $_COOKIE
function GCookie33($aKey1 = "", &$aOutVar1 = null, $aKey2 = "", &$aOutVar2 = null, $aKey3 = "", &$aOutVar3 = null, $aKey4 = "", &$aOutVar4 = null, $aKey5 = "", &$aOutVar5 = null, $aKey6 = "", &$aOutVar6 = null, $aKey7 = "", &$aOutVar7 = null, $aKey8 = "", &$aOutVar8 = null, $aKey9 = "", &$aOutVar9 = null, $aKey10 = "", &$aOutVar10 = null, $aKey11 = "", &$aOutVar11 = null, $aKey12 = "", &$aOutVar12 = null, $aKey13 = "", &$aOutVar13 = null, $aKey14 = "", &$aOutVar14 = null, $aKey15 = "", &$aOutVar15 = null, $aKey16 = "", &$aOutVar16 = null, $aKey17 = "", &$aOutVar17 = null, $aKey18 = "", &$aOutVar18 = null, $aKey19 = "", &$aOutVar19 = null, $aKey20 = "", &$aOutVar20 = null, $aKey21 = "", &$aOutVar21 = null, $aKey22 = "", &$aOutVar22 = null, $aKey23 = "", &$aOutVar23 = null, $aKey24 = "", &$aOutVar24 = null, $aKey25 = "", &$aOutVar25 = null, $aKey26 = "", &$aOutVar26 = null, $aKey27 = "", &$aOutVar27 = null, $aKey28 = "", &$aOutVar28 = null, $aKey29 = "", &$aOutVar29 = null, $aKey30 = "", &$aOutVar30 = null, $aKey31 = "", &$aOutVar31 = null, $aKey32 = "", &$aOutVar32 = null, $aKey33 = "", &$aOutVar33 = null) {
	return Request_GPFSC_Get33(4, $aKey1, $aOutVar1, $aKey2, $aOutVar2, $aKey3, $aOutVar3, $aKey4, $aOutVar4, $aKey5, $aOutVar5, $aKey6, $aOutVar6, $aKey7, $aOutVar7, $aKey8, $aOutVar8);
}
// Get request body of the client
function GBodyText() {
	return file_get_contents("php://input");
}
// Get json object from request body of the client
function GBodyJson() {
	return json_decode(file_get_contents("php://input"));
}
// Setters
function SSession4($aKey1 = "", $aValue1 = "", $aKey2 = "", $aValue2 = "", $aKey3 = "", $aValue3 = "", $aKey4 = "", $aValue4 = "") {
	global $_SESSION;
	if (!session_id()) session_start();
	if ($aKey1) $_SESSION[$aKey1] = $aValue1;
	if ($aKey2) $_SESSION[$aKey2] = $aValue2;
	if ($aKey3) $_SESSION[$aKey3] = $aValue3;
	if ($aKey4) $_SESSION[$aKey4] = $aValue4;
}
function SSessionArray($aArrayKeyValue) {
	global $_SESSION;
	if (!session_id()) session_start();
	foreach ($aArrayKeyValue as $k=>$v)
		if ($k) $_SESSION[$k] = $v;
}
function SCookie4Basic($aKey1 = "", $aValue1 = "", $aKey2 = "", $aValue2 = "", $aKey3 = "", $aValue3 = "", $aKey4 = "", $aValue4 = "") {
	$t = time() + 365.2 * 86400;
	if ($aKey1) setcookie($aKey1, $aValue1, $t);
	if ($aKey2) setcookie($aKey2, $aValue2, $t);
	if ($aKey3) setcookie($aKey3, $aValue3, $t);
	if ($aKey4) setcookie($aKey4, $aValue4, $t);
}
?>
