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
$content = "<table align=\"center\"><tr><form action=\"modules.php?name=Search\" method=\"post\">\n";
$content .= "<td align=\"right\">\n";
$content .= "<input type=\"text\" name=\"query\" size=\"15\" maxLength=\"57\" onblur=\"if (this.value==''){this.value='" . _SEARCH . "';}\" onfocus=\"if (this.value=='" . _SEARCH . "') {this.value = '';}\" value=\"" . _SEARCH . "\" style=\"text-align: center\">\n";
$content .= "</td>\n";
$content .= "<td>\n";
$content .= "<input type=\"image\" src=\"images/go.gif\" alt=\"" . _SEARCH . "\" value=\"" . _SEARCH . "\" style=\"border: 0\">\n";
$content .= "</td></form></tr></table>\n";

?>