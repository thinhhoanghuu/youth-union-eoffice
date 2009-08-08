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

    case "polls":
    case "poll_status":
    case "showlistcomm":
    case "poll_creat_step2":   
    case "poll_creat_step3":   
    case "poll_edit":
    case "poll_edit_save":
    case "poll_del":
    case "poll_del_comm":
    case "poll_delallcomm":
    include("modules/voting.php");
    break;

}

?>
