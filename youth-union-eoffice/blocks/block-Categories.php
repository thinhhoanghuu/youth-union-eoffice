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

global $cat, $language, $prefix, $multilingual, $currentlang, $db;

if ( $multilingual == 1 )
{
	$querylang = "AND (alanguage='$currentlang' OR alanguage='')";
}
else
{
	$querylang = "";
}
$sql = "SELECT catid, title FROM " . $prefix . "_stories_cat ORDER BY parentid, weight";
$result = $db->sql_query( $sql );
$numrows = $db->sql_numrows( $result );
if ( $numrows != 0 )
{
	$boxstuff = "<font class=\"content\">";
	while ( $row = $db->sql_fetchrow($result) )
	{
		$catid = intval( $row['catid'] );
		$title = $row['title'];
		$numrows = $db->sql_numrows( $db->sql_query("SELECT * FROM " . $prefix . "_stories WHERE catid='$catid' $querylang LIMIT 1") );
		if ( $numrows > 0 )
		{
			if ( $cat == 0 and ! $a )
			{
				$boxstuff .= "<strong><big>&middot;</big></strong>&nbsp;<b>" . _ALLCATEGORIES . "</b><br>";
				$a = 1;
			} elseif ( $cat != 0 and ! $a )
			{
				$boxstuff .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"" . INCLUDE_PATH . "modules.php?name=News\">" . _ALLCATEGORIES . "</a><br>";
				$a = 1;
			}

			if ( $cat == $catid )
			{
				$boxstuff .= "<strong><big>&middot;</big></strong>&nbsp;<b>$title</b><br>";
			}
			else
			{
				$boxstuff .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"" . INCLUDE_PATH . "modules.php?name=News&amp;op=viewcat&amp;catid=$catid\">$title</a><br>";
			}
		}
	}
	$boxstuff .= "</font>";
	$content = $boxstuff;
}

?>
