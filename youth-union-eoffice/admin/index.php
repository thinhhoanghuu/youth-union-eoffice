<?php

/*
* @Program:		NukeViet CMS
* @File name: 	NukeViet System
* @Version: 	2.0 RC2
* @Date: 		07.07.2009
* @Website: 	www.nukeviet.vn
* @Copyright: 	(C) 2009
* @License: 	http://opensource.org/licenses/gpl-license.php GNU Public License
*/

if ( ! file_exists("../mainfile.php") ) exit();
define( 'NV_ADMIN', true );
@require_once ( "../mainfile.php" );

/**
 * Kiem tra admin
 */
unset( $admin, $aid, $pwd );
if ( defined("IS_ADMIN") or defined("IS_SPADMIN") ) die();
if ( isset($_SESSION[ADMIN_COOKIE]) && ! empty($_SESSION[ADMIN_COOKIE]) )
{
	$admin = addslashes( base64_decode($_SESSION[ADMIN_COOKIE]) );
	$admin = explode( "#:#", $admin );
	$aid = addslashes( $admin[0] );
	$pwd = $admin[1];
	if ( ! empty($aid) and ! empty($pwd) and (! empty($admin[4]) and $admin[4] == substr(trim($_SERVER['HTTP_USER_AGENT']), 0, 80)) )
	{
		$aid = substr( $aid, 0, 25 );
		$bossresult = $db->sql_query( "SELECT `name`, `pwd`, `checknum`, `agent`, `last_ip` FROM `" . $prefix . "_authors` WHERE `aid`='" . $aid . "'" );
		if ( $bossresult )
		{
			list( $radminname, $rpwd, $rchecknum, $ragent, $rlast_ip ) = $db->sql_fetchrow( $bossresult );
			if ( (! empty($rpwd) and $rpwd == $pwd) and (! empty($rchecknum) and $rchecknum == $admin[3]) and (! empty($ragent) and $ragent == $admin[4]) and (! empty($rlast_ip) and $rlast_ip == $admin[5]) )
			{
				define( 'IS_ADMIN', true );
			}
		}
	}

	if ( ! defined("IS_ADMIN") ) unset( $_SESSION[ADMIN_COOKIE], $admin, $aid, $pwd, $admlanguage );
}

if ( defined("IS_ADMIN") )
{
	Header( "Location: " . $adminfile . ".php" );
	exit;
}
else
{
	Header( "Location: " . INCLUDE_PATH . "index.php" );
	exit;
}

?>