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

	case "BlocksAdmin":
	case "BlocksAdd":
	case "BlocksEdit":
	case "BlocksEditSave":
	case "ChangeStatus":
	case "BlocksDelete":
	case "BlockOrder":
	case "HeadlinesDel":
	case "HeadlinesAdd":
	case "HeadlinesSave":
	case "HeadlinesAdmin":
	case "HeadlinesEdit":
	case "fixweight2":
	case "block_show":
		include ( "modules/blocks.php" );
		break;

}

?>