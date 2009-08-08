<?php

/*
* @Program:		NukeViet CMS v2.0 RC1
* @File name: 	save.php @ Module News
* @Version: 	2.0
* @Date: 		01.05.2009
* @Website: 	www.nukeviet.vn
* @Copyright: 	(C) 2009
* @License: 	http://opensource.org/licenses/gpl-license.php GNU Public License
*/

if ( ! defined('NV_SYSTEM') )
{
	die( "You can't access this file directly..." );
}

require_once ( "mainfile.php" );
$module_name = basename( dirname(__file__) );
get_lang( $module_name );
if ( file_exists("" . $datafold . "/config_" . $module_name . ".php") )
{
	@require_once ( "" . $datafold . "/config_" . $module_name . ".php" );
}
if ( defined('_MODTITLE') ) $module_title = _MODTITLE;
##########################################

global $admin, $nukeurl, $sitename, $datetime, $prefix, $db, $module_name;
if ( (defined('IS_ADMMOD')) || ($newssave == 1) || ($newssave == 2 and (defined('IS_USER'))) )
{
	$sid = intval( $sid );
	if ( (stristr($REQUEST_URI, "mainfile")) || (! isset($sid)) )
	{
		exit;
	}
	$sql = "SELECT title, time, hometext, bodytext, notes FROM " . $prefix . "_stories WHERE sid='$sid'";
	$result = $db->sql_query( $sql );
	if ( $numrows = $db->sql_numrows($result) != 1 )
	{
		exit;
	}
	$row = $db->sql_fetchrow( $result );
	$title = stripslashes( check_html($row['title'], "nohtml") );
	$time = formatTimestamp( $row['time'] );
	$hometext = stripslashes( $row['hometext'] );
	$bodytext = stripslashes( $row['bodytext'] );
	$notes = stripslashes( $row['notes'] );

	header( "Pragma: no-cache" );
	header( "Expires: 0" );
	header( "Content-Type: text/x-delimtext; name=\"$module_name-$sid.html\"" );
	header( "Content-disposition: attachment; filename=$module_name-$sid.html" );
	echo "
<html>
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=" . _CHARSET . "\">
<title>$sitename - $title</title>
</head>
<body link=\"#4D5764\" vlink=\"#4D5764\" alink=\"#4D5764\" text=\"#4D5764\">
<center>
<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\">
<tr>
<td><b><font size=\"4\">$sitename</font></b></td>
<td align=\"right\"><font size=\"4\"><a href=\"$nukeurl\">$nukeurl</a></font></td>
</tr>
</table>
<hr color=\"#DC0312\" width=\"600\">
<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\">
<tr>
<td><b><font size=\"5\">$title</font></b><br>
<font size=\"2\">$datetime</font><br><br>
<p align=\"justify\"><b>$hometext</b><br>
$bodytext<br>
<i>$notes</i></td>
</tr>
</table>
<hr color=\"#DC0312\" width=\"600\">
<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\">
<tr>
<td align=\"center\">" . _THEURL . ":<a href=\"$nukeurl/modules.php?name=$module_name&op=viewst&sid=$sid\">$nukeurl/modules.php?name=$module_name&amp;op=viewst&amp;sid=$sid</a></td>
</tr>
</table>
<hr color=\"#4D5764\" width=\"600\">
<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\">
<tr>
<td><font size=\"2\">&copy; </font> <a href=\"$nukeurl\"><font size=\"2\">$sitename</font></a></td>
<td align=\"right\"><font size=\"2\">contact: </font> <a href=\"mailto:$adminmail\"><font size=\"2\">$adminmail</font></a></td>
</tr>
</table>
</center>
</body>
</html>
";
}
else
{
	exit;
}

?>
