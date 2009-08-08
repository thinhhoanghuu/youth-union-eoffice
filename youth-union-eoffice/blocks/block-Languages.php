<?php

if ( (! defined('NV_SYSTEM')) and (! defined('NV_ADMIN')) )
{
	Header( "Location: ../index.php" );
	exit;
}

global $currentlang, $multilingual, $adminfile, $changtheme;
if ( $multilingual == 1 )
{
	if ( defined('NV_ADMIN') )
	{
		$actlang = "" . $adminfile . ".php";
	}
	else
	{
		$actlang = "index.php";
	}
	$actlang2 = "" . $actlang . "?newlang=";
	if ( $_SERVER['QUERY_STRING'] != "" )
	{
		if ( defined('NV_ADMIN') )
		{
			$actlang = "" . $adminfile . ".php?" . $_SERVER['QUERY_STRING'] . "";
		}
		else
		{
			$actlang = "modules.php?" . $_SERVER['QUERY_STRING'] . "";
		}
		$actlang2 = "" . $actlang . "&newlang=";
	}

	$content = "<center><form action=\"" . $actlang . "\" method=\"get\"><select name=\"newlanguage\" onChange=\"top.location.href=this.options[this.selectedIndex].value\">";
	$handle = opendir( "" . INCLUDE_PATH . "language" );
	while ( $file = readdir($handle) )
	{
		if ( preg_match("/^lang\-(.+)\.php/", $file, $matches) )
		{
			$langFound = $matches[1];
			$languageslist .= "$langFound ";
		}
	}
	closedir( $handle );
	$languageslist = explode( " ", $languageslist );
	sort( $languageslist );
	for ( $i = 0; $i < sizeof($languageslist); $i++ )
	{
		if ( $languageslist[$i] != "" )
		{
			$content .= "<option value=\"" . $actlang2 . "" . $languageslist[$i] . "\" ";
			if ( $languageslist[$i] == $currentlang ) $content .= " selected";
			$content .= ">" . ucfirst( $languageslist[$i] ) . "</option>\n";
		}
	}
	$content .= "</select></form></center>";
}

?>