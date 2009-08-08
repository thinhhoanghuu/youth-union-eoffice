<?php

/*
* @Program:	NukeViet CMS v2.0 RC1
* @File name: 	Theme Nv_orange
* @Author: 	Boder - Nguyen Minh Giap
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

global $adminfold, $adminfile, $prefix, $db, $admin, $Home_Module, $module_name, $home, $bgcolor1, $bgcolor2, $bgcolor3, $bgcolor4;


$cao_menu = "29px";

$nen_mouse = "#FFFFFF";


$nen_menu = "#FFFFFF";


$chu = "in";

$stylemenu = "style=\"border-right:1px solid #dddddd;\"";


if ( $home == "1" )
{
	$nen_home = "bgcolor=\"" . $nen_menu . "\"";
}
else
{
	$nen_home = "";
}

if ( $chu == "in" )
{
	$dinhdang = "style=\"text-transform: uppercase\"";
}
else
{
	$dinhdang = "";
}
$nen = "";
echo "<TABLE cellSpacing=0 cellPadding=0 width=\"100%\" border=\"0\" background=\"" . INCLUDE_PATH . "themes/" . $ThemeSel . "/images/menu2.gif\"><tr><td>\n";
echo "<TABLE cellSpacing=\"0\" cellPadding=\"10\" border=\"0\" height=\"$cao_menu\" style=\"border-collapse: collapse\">\n";
echo "<TR>\n";
echo "<TD " . $nen_home . " align=\"center\" " . $stylemenu . " onMouseOver=\"this.style.background='" . $nen_mouse . "'\" onMouseOut=\"this.style.background=''\">\n";
echo "<b><A $dinhdang href=\"" . INCLUDE_PATH . "index.php\">" . _HOME . "</A></b></TD>\n";

$sql = "SELECT title, custom_title, view FROM " . $prefix . "_modules WHERE active='1' AND (inmenu='2' OR inmenu='4') ORDER BY custom_title ASC";
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
			if ( $module_name == $m_title )
			{
				$nen = "bgcolor='" . $nen_menu . "'";
			}
			else
			{
				$nen = "";
			}
			echo "<TD " . $nen . " align=\"center\" " . $stylemenu . " onMouseOver=\"this.style.background='" . $nen_mouse . "'\" onMouseOut=\"this.style.background=''\">\n";
			echo "<b><A $dinhdang href=\"" . INCLUDE_PATH . "modules.php?name=$m_title\">$m_title2</A></b></TD>\n";
		}
	}
}
echo "</TR></TABLE>\n";

echo "</td><td align=\"right\">\n";
echo "<form action=\"" . INCLUDE_PATH . "modules.php?name=Search\" method=\"post\">\n<input type=\"text\" name=\"query\" size=\"25\" maxLength=\"57\" onblur=\"if (this.value==''){this.value='" . _SEARCH . "';}\" onfocus=\"if (this.value=='" . _SEARCH . "') {this.value = '';}\" value=\"" . _SEARCH . "\" style=\"text-align: center; border: 0;\">\n";
echo "<input type=\"hidden\" src=\"" . INCLUDE_PATH . "images/go.gif\" alt=\"" . _SEARCH . "\" value=\"" . _SEARCH . "\" style=\"border: 0\"> </form>\n";

echo "</td></tr></TABLE>\n";
echo "<table cellSpacing=0 cellPadding=0 width=\"100%\" border=\"0\"><tr><td height=\"3\">\n";
echo "</td></tr></table>\n";

?>
