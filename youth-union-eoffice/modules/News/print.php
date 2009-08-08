<?php

/*
* @Program:		NukeViet CMS v2.0 RC1
* @File name: 	print.php @ Module News
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

if ( (defined('IS_ADMMOD')) || ($newsprint == 1) || ($newsprint == 2 and (defined('IS_USER'))) )
{
	$sid = intval( $sid );
	if ( (stristr($REQUEST_URI, "mainfile")) || (! isset($sid)) )
	{
		exit;
	}
	$sql = "SELECT * FROM " . $prefix . "_stories WHERE sid='$sid'";
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
	$images = $row['images'];
	$notes = stripslashes( $row['notes'] );
	$imgtext = stripslashes( check_html($row['imgtext'], "nohtml") );
	if ( $images != "" and file_exists("" . $path . "/" . $images . "") )
	{
		if ( file_exists("" . $path . "/small_" . $images . "") )
		{
			$images = "small_" . $images . "";
		}
		$size2 = @getimagesize( "$path/$images" );
		$widthimg = $size2[0];
		if ( $size2[0] > $sizecatnewshomeimg )
		{
			$widthimg = $sizecatnewshomeimg;
		}
		$xtitle = stripslashes( check_html($row['title'], "nohtml") );
		$story_pic = "<table border=\"0\" width=\"$widthimg\" cellpadding=\"0\" cellspacing=\"3\" align=\"$catnewshomeimg\">\n<tr>\n<td>\n<a href=\"modules.php?name=$module_name&amp;op=viewst&amp;sid=$sid\"><img border=\"0\" src=\"$path/$images\" width=\"$widthimg\" alt=\"$xtitle\"></a></td>\n</tr>\n<tr>\n<td align=\"center\">$imgtext</td>\n</tr>\n</table>\n";
	}
	else
	{
		$story_pic = "";
	}
	echo "
<html>
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=" . _CHARSET . "\">
<title>$sitename - $title</title>
<script language=\"JavaScript\">
<!-- Begin
function varitext(text){
text=document
print(text)
}
//  End -->
</script>
</head>
<body link=\"#4D5764\" vlink=\"#4D5764\" alink=\"#4D5764\" text=\"#4D5764\">
<center>
<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\">
<tr>
<td><b><font size=\"4\">$sitename</font></b></td>
<td align=\"right\"><font size=\"4\"><a href=\"$nukeurl\" target=\"_blank\">$nukeurl</a></font></td>
</tr>
</table>
<hr color=\"#DC0312\" width=\"600\">
<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\">
<tr>
<td><b><font size=\"5\">$title</font></b><br>
<font size=\"2\">$datetime | <a href=\"javascript:;\" onClick=\"varitext()\">" . _PRINT . "</a> | <a href=\"\" onclick=\"window.close();\">" . _CLOSEWIN . "</a></font><br><br>
<p align=\"justify\"><b>$story_pic$hometext</b><br>
$bodytext<br>
<i>$notes</i></td>
</tr>
</table>
<hr color=\"#DC0312\" width=\"600\">
<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\">
<tr>
<td align=\"center\">" . _THEURL . ":<a href=\"$nukeurl/modules.php?name=$module_name&op=viewst&sid=$sid\" target=\"_blank\">$nukeurl/modules.php?name=$module_name&amp;op=viewst&amp;sid=$sid</a></td>
</tr>
</table>
<hr color=\"#4D5764\" width=\"600\">
<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\">
<tr>
<td><font size=\"2\">&copy; </font> <a href=\"$nukeurl\" target=\"_blank\"><font size=\"2\">$sitename</font></a></td>
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
