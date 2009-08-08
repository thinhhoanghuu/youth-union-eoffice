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
	die( 'Stop!!!' );
}
if ( ! defined('NV_MAINFILE') )
{
	die( 'Stop!!!' );
}

/**
 * ulist()
 * 
 * @return
 */
function ulist()
{
	global $db, $user_prefix, $datafold;
	$fsql = "SELECT user_id, username, viewuname, user_email FROM " . $user_prefix . "_users";
	$fresult = $db->sql_query( $fsql );
	@chmod( "" . INCLUDE_PATH . "" . $datafold . "/ulist.php", 0777 );
	@$file = fopen( "" . INCLUDE_PATH . "" . $datafold . "/ulist.php", "w" );
	$content = "<?php\n\n";
	$fctime = date( "d-m-Y H:i:s", filectime("" . INCLUDE_PATH . "" . $datafold . "/ulist.php") );
	$fmtime = date( "d-m-Y H:i:s" );
	$content .= "// File: ulist.php.\n// Created: $fctime.\n// Modified: $fmtime.\n// Do not change anything in this file!\n\n";
	$content .= "if ((!defined('NV_SYSTEM')) AND (!defined('NV_ADMIN'))) {\n";
	$content .= "die('Stop!!!');\n";
	$content .= "}\n";
	$content .= "\n";
	while ( $frow = $db->sql_fetchrow($fresult) )
	{
		if ( $frow[1] != "" and $frow[1] != "Anonymous" )
		{
			if ( $frow[2] == "" )
			{
				$frow[2] = $frow[1];
			}
			$content .= "\$udt[" . $frow[0] . "] = \"" . $frow[1] . "|" . addslashes( $frow[2] ) . "|" . $frow[3] . "\";\n";
		}
	}
	$content .= "\n";
	$content .= "?>";
	@$writefile = fwrite( $file, $content );
	@fclose( $file );
	@chmod( "" . INCLUDE_PATH . "" . $datafold . "/ulist.php", 0604 );
}

if ( ! file_exists("" . INCLUDE_PATH . "" . $datafold . "/ulist.php") )
{
	ulist();
	header( "Location: " . INCLUDE_PATH . "index.php" );
	exit();
}

?>