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
global $prefix, $db, $bgcolor1, $bgcolor4;
$num = 10;
$content = "";
$result = $db->sql_query( "SELECT lid, title, UNIX_TIMESTAMP(date) as formatted FROM " . $prefix . "_files WHERE status !='0' ORDER BY date DESC LIMIT $num" );
$conz = "";
while ( list($lid, $title, $addtime) = $db->sql_fetchrow($result) )
{
	$conz .= "<img border=\"0\" src=\"images/navi.gif\" width=\"6\" height=\"6\">&nbsp;<a href=\"modules.php?name=Files&go=view_file&lid=$lid\">$title</a><br>\n"; // ".viewtime($addtime,1)."<br>
}
if ( $conz != "" )
{
	$content .= "" . $conz . "";
}

?>