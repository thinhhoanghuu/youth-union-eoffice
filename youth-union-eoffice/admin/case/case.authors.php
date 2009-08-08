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

	case "AdminsConfigSave":
	case "mod_authors":
	case "modifyadmin":
	case "UpdateAuthor":
	case "AddAuthor":
	case "deladmin2":
	case "deladmin":
	case "assignstories":
	case "deladminconf":
		include ( "modules/authors.php" );
		break;

}

?>