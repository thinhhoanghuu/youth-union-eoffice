<?php

/*
* @Program:		NukeViet CMS
* @File name: 	NukeViet System
* @Version: 	2.0 RC1
* @Date: 		01.05.2009
* @Website: 	www.nukeviet.vn
* @Copyright: 	(C) 2009
* @License: 	http://opensource.org/licenses/gpl-license.php GNU Public License
*/

if ( (! defined('NV_SYSTEM')) and (! defined('NV_ADMIN')) )
{
	Header( "Location: ../index.php" );
	exit;
}

global $ThemeSel, $changtheme, $actthemes, $adminfile;
if ( $changtheme == 1 )
{
	if ( defined('NV_ADMIN') )
	{
		$actthem = "" . $adminfile . ".php";
	}
	else
	{
		$actthem = "index.php";
	}
	$actthem2 = "" . $actthem . "?newsk=";
	if ( $_SERVER['QUERY_STRING'] != "" )
	{
		if ( defined('NV_ADMIN') )
		{
			$actthem = "" . $adminfile . ".php?" . $_SERVER['QUERY_STRING'] . "";
		}
		else
		{
			$actthem = "modules.php?" . $_SERVER['QUERY_STRING'] . "";
		}
		$actthem2 = "" . $actthem . "&newsk=";
	}
	$actthemes_ar = explode( "|", $actthemes );
	$actthemes_ar2 = array();
	$c = 0;
	for ( $i = 0; $i < sizeof($actthemes_ar); $i++ )
	{
		if ( (file_exists("" . INCLUDE_PATH . "themes/" . $actthemes_ar[$i] . "/theme.php")) and (! eregi("\.", "$actthemes_ar[$i]")) )
		{
			$actthemes_ar2[$c] = $actthemes_ar[$i];
			$c++;
		}
	}
	sort( $actthemes_ar2 );
	if ( sizeof($actthemes_ar2) > 0 )
	{
		$content = "<center><form action=\"" . $actthem . "\" method=\"get\"><select name=\"newtheme\" onChange=\"top.location.href=this.options[this.selectedIndex].value\">";
		for ( $i = 0; $i < sizeof($actthemes_ar); $i++ )
		{
			if ( $actthemes_ar[$i] != "" )
			{
				$content .= "<option value=\"" . $actthem2 . "" . $actthemes_ar[$i] . "\" ";
				if ( $actthemes_ar[$i] == $ThemeSel ) $content .= " selected";
				$content .= ">" . ucfirst( $actthemes_ar[$i] ) . "</option>\n";
			}
		}
		$content .= "</select></form></center>";
	}
}

?>