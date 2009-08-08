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

if (!defined('NV_ADMIN')) {
    die ("Access Denied");
}

switch($op) {
    case "go_search_user":
    case "listUser":
    case "UsersConfig":
    case "UsersConfigSave":
    case "mod_users":
    case "modifyUser":
    case "modifyUserTemp":
    case "updateUser":
    case "updateUserTemp":
    case "delUser":
    case "delUserTemp":
    case "delUserConf":
    case "delUserTempConf":
    case "addUser":
    case "addUserSave":
    case "actUserTemp":
    include("modules/users.php");
    break;

}

?>
