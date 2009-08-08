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

if (!defined('NV_ADMIN')) {die ("Access Denied"); } 

switch($op) {

    case "Support_List_Cat":
    case "Support_Edit_Cat":
    case "Support_Del_Cat":
    case "Support_ChgW_Cat":
    case "Support_Act_Cat":
    case "Support_ListS_All":
    case "Support_AddS_All":
    case "Support_EditS_All":
    case "Support_DelS_All":
    case "Support_ListS_User":
    case "Support_EditS_User":
    case "Support_Ignore":
    case "Support_DelS_User":
    include("modules/support.php");
    break;

}

?>