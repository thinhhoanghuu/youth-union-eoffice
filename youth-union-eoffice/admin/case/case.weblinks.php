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

    case "Weblinks_CatManager":
    case "Weblinks_CatEdit":
    case "Weblinks_CatDel":
    case "Weblinks_CatIhome":
    case "Weblinks_LinkManager":
    case "Weblinks_LinkEdit":
    case "Weblinks_LinkDel":
    case "Weblinks_LinkAct":
    include("modules/weblinks.php");
    break;

}

?>