<?php
function blankfunc($aParam=null) {
	// Sometimes blankfunc is actually used.
	return $aParam;
}
function str_linebr(string $aStr, $aHTMLBr = null, $aLineBr = null) {
	// Has other purposes too, example:
	// Remove all \r and or \n if both $aHTMLBr and $aLineBr are empty string!
	$rval = "";
	if (null === $aHTMLBr) $aHTMLBr = "<br>";
	if (null === $aLineBr) $aLineBr = "\r\n";
	for ($i = 0, $L = strlen($aStr); $i < $L; $i++) {
		$cchr = $aStr[$i];
		$nchr = "";
		if (1 + $i < $L)
			$nchr = $aStr[1 + $i];
		if ("\r" == $cchr && "\n" == $nchr) {
			$rval .= "$aHTMLBr$aLineBr";
			$i++;
		} elseif ("\r" == $cchr && "\n" != $nchr)
			$rval .= "$aHTMLBr$aLineBr";
		elseif ("\n" == $cchr)
			$rval .= "$aHTMLBr$aLineBr";
		else $rval .= $cchr;
	}
	return $rval;
}
function str_exclude(string $aStr, string $aRemove) {
	$rval = "";
	for ($i = 0, $L = strlen($aStr), $L2 = strlen($aRemove); $i < $L; $i++) {
		$canAdd = 1; $cchr = $aStr[$i];
		for ($a = 0; $a < $L2; $a++)
			if ($aRemove[$a] == $cchr) {
				$canAdd = 0;
				break;
			}
		if ($canAdd)
			$rval .= $cchr;
	}
	return $rval;
}
?>
