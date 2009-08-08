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
	Header( "Location: ../../index.php" );
	exit;
}
############# Block Menu V 1.0 ###########
// Chiều cao một ô menu
$cao_menu = "18px";
############# HẾT KHAI BÁO #############

global $adminfold, $adminfile, $prefix, $db, $admin, $Home_Module;
$main_module = $Home_Module;

$content = "<TABLE cellSpacing=0 cellPadding=0 width=\"100%\" border=\"0\" >\n";
$content .= "<TBODY>\n";
$content .= "<TR>\n";
$content .= "<TD height=\"$cao_menu\">\n";

if ( defined('IS_ADMIN') )
{
	$content .= "<DIV align=left><b><A href=\"" . INCLUDE_PATH . "" . $adminfold . "/" . $adminfile . ".php\"><img border=\"0\" src=\"" . INCLUDE_PATH . "images/001.gif\" width=\"9\" height=\"9\"> " . _ADMINPAGE . "</A> / <A href=\"" . INCLUDE_PATH . "" . $adminfold . "/" . $adminfile . ".php?op=logout\"><font color=red>" . _LOGOUT . "</font></A></b></DIV></TD>\n";
	$content .= "</TR><TR>\n";
	$content .= "<TD height=\"$cao_menu\">\n";
}
$content .= "<DIV align=left><b><A href=\"" . INCLUDE_PATH . "index.php\"><img border=\"0\" src=\"" . INCLUDE_PATH . "images/001.gif\" width=\"9\" height=\"9\"> " . _HOME . "</A></b></DIV></TD>\n";
$content .= "</TR><TR>\n";
$content .= "<TD height=\"$cao_menu\">\n";
if ( defined('IS_USER') )
{
	$content .= "<DIV align=left><b><A href=\"" . INCLUDE_PATH . "modules.php?name=Your_Account\"><img border=\"0\" src=\"" . INCLUDE_PATH . "images/001.gif\" width=\"9\" height=\"9\"> " . _USERPAGE . "</A> / <A href=\"" . INCLUDE_PATH . "modules.php?name=Your_Account&op=logout\">" . _LOGOUT . "</A></b></DIV></TD>\n";
	$content .= "</TR><TR>\n";
	$content .= "<TD height=\"$cao_menu\">\n";
}
else
{
//	$content .= "<DIV align=left><b><A href=\"" . INCLUDE_PATH . "modules.php?name=Your_Account\"><img border=\"0\" src=\"" . INCLUDE_PATH . "images/001.gif\" width=\"9\" height=\"9\"> " . _LOGIN . "</A> / <A href=\"" . INCLUDE_PATH . "modules.php?name=Your_Account&op=new_user\">" . _NEWREG . "</A></b></DIV></TD>\n";
//	$content .= "</TR><TR>\n";
//	$content .= "<TD height=\"$cao_menu\">\n";
}
$content .= "<DIV align=left><b><A href=\"" . INCLUDE_PATH . "modules.php?name=News\"><img border=\"0\" src=\"" . INCLUDE_PATH . "images/001.gif\" width=\"9\" height=\"9\"> Tin tức</A></b></DIV></TD>\n";
$sql = "SELECT title, custom_title, view FROM " . $prefix . "_modules WHERE active='1' AND title!='$def_module' AND title!='Your_Account' AND (inmenu='1' OR inmenu='2' OR inmenu='3' OR inmenu='4' OR inmenu='5' OR inmenu='6' OR inmenu='7' OR inmenu='8' OR inmenu='9') ORDER BY custom_title ASC";
$result = $db->sql_query( $sql );
while ( $row = $db->sql_fetchrow($result) )
{
	$m_title = $row[title];
	$custom_title = $row[custom_title];
	$view = $row[view];
	$m_title2 = ereg_replace( "_", " ", $m_title );
	if ( $custom_title != "" )
	{
		$m_title2 = $custom_title;
	}
	if ( $m_title != $main_module )
	{
		if ( (defined('IS_ADMIN') and $view == 2) or $view != 2 )
		{
			$content .= "</TR><TR>\n";
			$content .= "<TD height=\"$cao_menu\">\n";
			$content .= "<DIV align=left><b><A href=\"" . INCLUDE_PATH . "modules.php?name=$m_title\"><img border=\"0\" src=\"" . INCLUDE_PATH . "images/001.gif\" width=\"9\" height=\"9\"> $m_title2</A></b></DIV></TD>\n";

		}
	}
}

$content .= "</TR></TBODY></TABLE>\n";

?>