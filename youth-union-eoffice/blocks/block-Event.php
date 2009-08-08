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

global $language, $prefix, $multilingual, $currentlang, $db;
if ( $multilingual == 1 )
{
	$querylang = "AND (alanguage='$currentlang' OR alanguage='')";
}
else
{
	$querylang = "";
}
$sql = "SELECT * FROM " . $prefix . "_stories_topic ORDER BY rand() LIMIT 10";
$result = $db->sql_query( $sql );
$numrows = $db->sql_numrows( $result );
if ( $numrows != 0 )
{
	$boxstuff = "<font class=\"content\">";
	while ( $row = $db->sql_fetchrow($result) )
	{
		$topicid = intval( $row['topicid'] );
		$topictitle = $row['topictitle'];
		$numrows = $db->sql_numrows( $db->sql_query("SELECT * FROM " . $prefix . "_stories WHERE topicid='$topicid' $querylang LIMIT 1") );
		if ( $numrows > 0 )
		{
			$boxstuff .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"" . INCLUDE_PATH . "modules.php?name=News&amp;op=viewtop&amp;topicid=$topicid\">$topictitle</a><br>";
		}
	}
	$boxstuff .= "</font>";
	$content = $boxstuff;
}

?>