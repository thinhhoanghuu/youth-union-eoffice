<?php

/*
* @Program:	NukeViet CMS
* @File name: 	NukeViet System
* @Version: 	2.0 RC1
* @Date: 		01.05.2009
* @Website: 	www.nukeviet.vn
* @Copyright: 	(C) 2009
* @License: 	http://opensource.org/licenses/gpl-license.php GNU Public License
*/

if ( ! defined('NV_ADMIN') ) die( "Access Denied" );

switch ( $op )
{

	case "adminnl":
	case "adminaddstorynl":
	case "adminsendnl":
	case "viewsubnl":
	case "viewsendnl":
	case "viewletternl":
	case "viewallnl":
	case "delletternl":
	case "delusernl":
	case "actionusernl":
		include ( "modules/newsletter.php" );
		break;

}

?>