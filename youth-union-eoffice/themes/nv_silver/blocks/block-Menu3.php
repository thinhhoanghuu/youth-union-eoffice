<?php

/*
* @Program:		NukeViet CMS v2.0 RC1
* @File name: 	Theme Nv_orange
* @Author: 		Boder - Nguyen Minh Giap
* @Version: 	1.0
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

global $adminfold, $adminfile, $prefix, $db, $admin, $Home_Module, $module_name, $home, $bgcolor1, $bgcolor2, $bgcolor3, $bgcolor4, $ThemeSel;


$color_menu = "#003C5E";

$chu = "thuong";


if ( $chu == "in" )
{
	$dinhdang = "style=\"text-transform: uppercase\"";
}
else
{
	$dinhdang = "";
}
echo "<TABLE style=\"BORDER-COLLAPSE: collapse\" cellPadding=0 width=\"100%\" border=0>\n";
echo "<TBODY><TR>\n";
echo "<td width=\"40\"><a href=\"" . INCLUDE_PATH . "modules.php?name=Rss\"><img border=\"0\" src=\"" . INCLUDE_PATH . "images/rss.gif\" width=\"39\" height=\"14\" align=\"left\"></a></td>\n";
echo "<TD style=\"FONT-WEIGHT: bold; COLOR: #A2A2A2; TEXT-ALIGN: center\">\n";
echo " <A $dinhdang href=\"" . INCLUDE_PATH . "index.php\"><FONT  color=$color_menu>" . _HOME . "</FONT></A>\n";

$sql = "SELECT title, custom_title, view FROM " . $prefix . "_modules WHERE active='1' AND (inmenu='3' OR inmenu='4') ORDER BY custom_title ASC";
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
	if ( $m_title != $Home_Module )
	{
		if ( (defined('IS_ADMIN') and $view == 2) or $view != 2 )
		{
			echo "&nbsp; &nbsp;¤&nbsp; &nbsp;<A $dinhdang href=\"" . INCLUDE_PATH . "modules.php?name=$m_title\"><FONT color=$color_menu>$m_title2</FONT></A>\n";
		}
	}
}
echo " </TD>\n";
echo "<td width=\"12\"><a href=\"#top\"><img border=\"0\" src=\"" . INCLUDE_PATH . "images/up.gif\" width=\"11\" height=\"14\" align=\"right\"></a></td>\n";
echo "</TR></TBODY></TABLE>\n";

?>
