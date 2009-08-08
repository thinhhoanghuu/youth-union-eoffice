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

// Block www.mangvn.org 2009
if ( (! defined('NV_SYSTEM')) and (! defined('NV_ADMIN')) )
{
	Header( "Location: ../index.php" );
	exit;
}
$content .= "<script src=\"modules.php?name=Weblinks&file=xoayvong.js\" type=\"text/javascript\"></script>\n";
$content .= "<body onload=\"chay_vong()\">\n";
$content .= "<div id=\"chay_vong\" style=\"position:relative\"><br><br><br><br><br></div>\n";
$content .= "<center><hr /><a href=\"modules.php?name=Weblinks\">" . _LOGOHERE . "</a>\n";
$content .= "  <br /></center>\n";

?>