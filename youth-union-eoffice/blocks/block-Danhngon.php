<?php

/*
* @Program:		NukeViet CMS
* @File name: 	NukeViet System
* @Version: 	2.0 RC1
* @Date: 		01.05.2009
* @Website: 	www.nukeviet.vn
* @Copyright: 	(C) 2009
* @License: 	http://opensource.org/licenses/gpl-license.php GNU Public License
*/

if ( (! defined('NV_SYSTEM')) and (! defined('NV_ADMIN')) )
{
	Header( "Location: ../index.php" );
	exit;
}
global $datafold, $themepath;
$frases = file( "" . INCLUDE_PATH . "" . $datafold . "/danhngon.txt" );
$numero_frases = count( $frases );
if ( $numero_frases != 0 )
{
	$numero_frases--;
}
mt_srand( (double)microtime() * 1000000 );

$numero_aleatorio = mt_rand( 0, $numero_frases );
$content = "$frases[$numero_aleatorio]";

?>