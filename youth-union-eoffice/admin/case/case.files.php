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

	case "FilesComment":
	case "EditFilesComment":
	case "SaveEditFilesComment":
	case "ListFilesAdded":
	case "files":
	case "FilesSetting":
	case "FilesSettingSave":
	case "add_files_category":
	case "add_files_sub_cat":
	case "edit_files_category":
	case "save_files_category":
	case "del_files_cat":
	case "AddNewFile":
	case "file_save":
	case "FilesWaiting":
	case "brocen_files":
	case "edit_files":
	case "file_edit_save":
	case "delit_file":
	case "view_add_file":
	case "ignore_broc":
	case "MoveFilesCat":
	case "MoveFilesCatSave":
	case "file_add_save":
	case "delit_file_comment":
		include ( "modules/files.php" );
		break;

}

?>