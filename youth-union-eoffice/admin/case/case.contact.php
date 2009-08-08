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

	case "contacthome":
	case "contactemail":
	case "contactemailsend":
	case "deptdefault":
	case "phoneedit":
	case "deptedit":
	case "phonemodify":
	case "deptmodify":
	case "phoneadd":
	case "deptadd":
	case "phonedelete":
	case "deptdelete":
	case "contactemailxong":
	case "readcontact":
	case "re_contactemail":
	case "contactdel":
		include ( "modules/contact.php" );
		break;

}

?>