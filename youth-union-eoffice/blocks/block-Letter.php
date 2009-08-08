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

$content = "<center>";
$content .= "<form action=\"" . INCLUDE_PATH . "modules.php?name=Newsletter\" method=\"post\">" . _YOURMAIL . "<br>";
$content .= "<input maxlength=30 size=20 value=\"\" name=\"new_email\" style=\"text-align: center\"><br>";
$content .= "" . _FORMATSTORY . "<br><select name=\"new_type\"><option name=\"new_type\" value=\"0\" selected>" . _TEXT . "<option name=\"new_type\" value=\"1\">" . _HTML . "</option></select><br>";
$content .= "<center><input type=\"hidden\" name=\"func\" value=\"action\"><input type=submit value=\"" . _BREG . "\"></center></form>";

?>