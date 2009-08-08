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
############# Block Menu5 ###########
// Chiều cao một ô menu
$cao_menu = "18px";
############# HẾT KHAI BÁO #############

global $adminfold, $adminfile, $prefix, $db, $admin, $Home_Module;
$main_module = $Home_Module;

$content = "<TABLE cellSpacing=0 cellPadding=0 width=\"100%\" border=\"0\" >\n";
$content .= "<TBODY>\n";

$sql = "SELECT title, custom_title, view FROM " . $prefix . "_modules WHERE active='1' AND inmenu='5' ORDER BY custom_title ASC";
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
			$content .= "<TR>\n";
			$content .= "<TD height=\"$cao_menu\">\n";
			$content .= "<DIV align=left><b><A href=\"" . INCLUDE_PATH . "modules.php?name=$m_title\"><img border=\"0\" src=\"" . INCLUDE_PATH . "images/001.gif\" width=\"9\" height=\"9\"> $m_title2</A></b></DIV></TD></TR>\n";

		}
	}
}

$content .= "</TBODY></TABLE>\n";

?>