<?php

/*
* @Program:	NukeViet CMS v2.0 RC1
* @File name: 	Module Addnews
* @Author: 		Nguyen Anh Tu (Nukeviet Group)
* @Version: 	1.1
* @Date: 		01.05.2009
* @Website: 	www.nukeviet.vn
* @Contact: 	anht@mail.ru
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

$index = ( defined('MOD_BLTYPE') ) ? MOD_BLTYPE : 1;
/********************************************/

if ( isset($_REQUEST['lid']) )
{
	$lid = intval( $_REQUEST['lid'] );
	if ( $lid )
	{
		list( $url ) = $db->sql_fetchrow( $db->sql_query("SELECT url FROM " . $prefix . "_weblinks_links WHERE id='" . $lid . "' AND active='1'") );
		if ( $url != "" )
		{
			if ( ! eregi("http://", $url) )
			{
				$url = "http://" . $url;
			}
			$db->sql_query( "UPDATE " . $prefix . "_weblinks_links SET hits=hits+1 WHERE id='" . $lid . "'" );
			Header( "Location: " . $url );
			exit;
		}
	}
}

$linkcats = array();
$homecat = 0;
$result = $db->sql_query( "SELECT * FROM " . $prefix . "_weblinks_cats WHERE language='" . $currentlang . "' ORDER BY id" );
while ( $row = $db->sql_fetchrow($result) )
{
	$linkcats[intval( $row['id'] )] = array( 'id' => intval($row['id']), 'title' => stripslashes($row['title']) );
	if ( $row['ihome'] == '1' )
	{
		$homecat = intval( $row['id'] );
	}
}
$viewcat = intval( $_REQUEST['viewcat'] );
if ( ! $viewcat )
{
	$viewcat = $homecat;
}
if ( ! isset($linkcats[$viewcat]) )
{
	foreach ( $linkcats as $key => $value )
	{
		$viewcat = $key;
		break;
	}
}

include ( "header.php" );
echo "<div style=\"padding: 5px; margin: 5px\">\n";
echo "<div style=\"text-align: center; font-family: Arial, Helvetica, sans-serif; font-size: 16px; font-weight: bolder; color: #F36400;\">\n";
echo "<a href=\"modules.php?name=" . $module_name . "\" title=\"" . _WEBLINKS . "\"><img alt=\"" . _WEBLINKS . "\" src=\"images/weblinks.gif\" width=\"98\" height=\"70\" style=\"vertical-align: middle;border-width: 0px;\"></a> " . _WEBLINKS . "</div>\n";

$view = "";
if ( isset($_GET['view']) and ! ereg("[^a-zA-Z0-9]", $_GET['view']) )
{
	$view = trim( $_GET['view'] );
}
if ( file_exists("modules/$module_name/" . $view . ".htm") )
{
	include ( "modules/$module_name/" . $view . ".htm" );
}
if ( $view == "demo" )
{
	echo "<hr />
	<p align=\"center\">
	<font face=\"Tahoma\" size=\"2\" color=\"#008000\">
	<b><font size=\"2\">" . _WEBLINKS1 . "</font></b></font></p>
	<p align=\"center\">
	<font face=\"Tahoma\" size=\"2\" color=\"#008000\">
	<textarea readonly rows=\"4\" onclick=\"this.focus();this.select()\" id=\"code2\" name=\"CODE2\" cols=\"64\" ><div align=\"center\" id=\"logoexc\">" . _WEBLINKS2 . "
	</div><script src=\"" . $nukeurl . "/modules.php?name=" . $module_name . "&file=weblinks.js\"  type=\"text/javascript\"></script>
	<script src=\"" . $nukeurl . "/js/logo.js\" type=\"text/javascript\"></script><hr /><center><a href=\"" . $nukeurl . "/modules.php?name=" . $module_name . "\">" . _WEBLINKS3 . "</a>
	<br /></center></textarea></font></p>";
} elseif ( $view == "demo2" )
{
	echo "<hr />
	<p align=\"center\">
	<font face=\"Tahoma\" size=\"2\" color=\"#008000\">
	<b><font size=\"2\">" . _WEBLINKS1 . "</font></b></font></p>
	<p align=\"center\">
	<font face=\"Tahoma\" size=\"2\" color=\"#008000\">
	<textarea readonly rows=\"4\" onclick=\"this.focus();this.select()\" id=\"code2\" name=\"CODE2\" cols=\"64\" >
	<script src=\"" . $nukeurl . "/modules.php?name=" . $module_name . "&file=xoayvong.js\"  type=\"text/javascript\"></script>
	<body onload=\"chay_vong()\">
	<div align=\"center\"><div align=\"center\" id=\"chay_vong\" style=\"position:relative;\"><br><br><br><br><br></div></div>
	<center><a href=\"modules.php?name=" . $module_name . "\">" . _WEBLINKS3 . "</a>
	</center></textarea></font></p>";
} elseif ( file_exists("modules/$module_name/language/" . $currentlang . "/quydinh.htm") )
{
	include ( "modules/$module_name/language/" . $currentlang . "/quydinh.htm" );
}

if ( $linkcats != array() )
{
	$cid = $viewcat;
	$cname = $linkcats[$viewcat]['title'];
	echo "<table style=\"width: 100%\" cellpadding=\"5\" cellspacing=\"5\">\n";
	echo "<tr>\n<td  style=\"font-family: Arial, Helvetica, sans-serif; font-size: 14px; font-weight: bold; color: #0054A6;\">\n";
	echo "<img alt=\"" . $cname . "\" src=\"images/FolderWindows.png\" width=\"24\" height=\"24\" style=\"vertical-align: middle\">\n";
	echo $cname . "</td>\n";
	echo "<td style=\"text-align: right\">\n";
	echo "<form method=\"get\">\n";
	echo "<select name=\"viewcat\" onchange=\"top.location.href=this.options[this.selectedIndex].value\">\n";
	echo "<option value=\"\">" . _VIEWOTHERCAT . "</option>\n";
	foreach ( $linkcats as $key => $value )
	{
		if ( $key != $viewcat )
		{
			echo "<option value=\"modules.php?name=" . $module_name . "&viewcat=" . $key . "\">" . $value['title'] . "</option>\n";
		}
	}
	echo "</select></form></td>\n</tr>\n</table>\n";
	$result2 = $db->sql_query( "SELECT * FROM " . $prefix . "_weblinks_links WHERE cid='" . $cid . "' AND active='1' ORDER BY title ASC" );
	$numrows2 = $db->sql_numrows( $result2 );
	if ( $numrows2 )
	{
		echo "<div>\n";
		echo "<table style=\"width: 100%\" cellpadding=\"5\" cellspacing=\"5\">\n";
		while ( $row2 = $db->sql_fetchrow($result2) )
		{
			$id = intval( $row2['id'] );
			$title = stripslashes( $row2['title'] );
			$description = stripslashes( $row2['description'] );
			$tothits = intval( $row2['hits'] );
			echo "<tr>\n";
			echo "<td style=\"border-style: dotted; border-width: 0px 0px 1px 0px; border-color: #CCCCCC; font-family: Tahoma, Helvetica, sans-serif; font-size: 12px\">\n";
			echo "<strong>" . $title . "</strong> [" . _LTOTHITS . ": " . $tothits . "]";
			if ( defined('IS_ADMMOD') )
			{
				echo " [<a href=\"" . $adminfold . "/" . $adminfile . ".php?op=Weblinks_LinkEdit&amp;id=" . $id . "\">" . _EDIT . "</a> | <a href=\"" . $adminfold . "/" . $adminfile . ".php?op=Weblinks_LinkDel&amp;id=" . $id . "\">" . _DELETE . "</a>]";
			}
			echo "<br>\n";
			echo "<span style=\"font-family: Tahoma; font-size: 11px; color: #666666;\">" . $description . "";
			echo "</span></td>\n";
			echo "<td style=\"border-style: dotted; border-width: 0px 0px 1px 0px; border-color: #CCCCCC;width: 16px\">\n";
			echo "<a target=\"weblink\" href=\"modules.php?name=" . $module_name . "&amp;lid=" . $id . "\">\n";
			echo "<img style=\"border-width: 0px;vertical-align: bottom\" alt=\"" . $title . "\" src=\"images/OpenWeb.png\" width=\"24\" height=\"24\"></a></td>\n";
			echo "</tr>\n";
		}
		echo "</table>\n</div>\n<br>\n";
	}
}
echo "</div>\n";
include ( "footer.php" );

?>