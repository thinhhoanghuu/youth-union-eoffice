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

if ( eregi("footer.php", $_SERVER['SCRIPT_NAME']) )
{
	Header( "Location: index.php" );
	die();
}

/**
 * footmsg()
 * 
 * @return
 */
function footmsg()
{
	global $start_time, $db;

	echo "<font class=\"footmsg\">\n";
	$end_time = get_microtime();
	$total_time = ( $end_time - $start_time + $db->time );
	$total_time = _PAGEGENERATION . " " . substr( $total_time, 0, 5 ) . " " . _SECONDS . ". " . _SLTCCSDL . ": " . $db->num_queries;

	echo "<br />" . $total_time . "<br />" . _FOOTCPR . "<br />";
	if ( defined('_BQMOD') and _BQMOD != "" )
	{
		echo "" . _BQMOD . "<br />";
	}
	echo "<br />";
}

/**
 * foot()
 * 
 * @return
 */
function foot()
{
	global $db, $prefix, $nukeurl, $datafold, $home, $gzip_method, $do_gzip_compress, $name, $protect, $protected_links;
	if ( $home ) blocks( Down, $name );
	themefooter();
	// Begin Link Protect
	if ( $protect )
	{
		echo "<script src=\"" . INCLUDE_PATH . "protect/protect.js\" type=\"text/javascript\"></script>\n";
		echo "<script type=\"text/javascript\">\n";
		echo "protected_links = \"" . $protected_links . "\";\n";
		echo "auto_anonymize();\n";
		echo "</script>\n";
	}
	// End Link Protect

	echo "<script type=\"text/javascript\" src=\"" . INCLUDE_PATH . "js/avim.js\"></script>\n";
	echo "</body>\n";
	echo "</html>";
	if ( $gzip_method )
	{
		if ( $do_gzip_compress )
		{
			$gzip_contents = ob_get_contents();
			ob_end_clean();
			$gzip_size = strlen( $gzip_contents );
			$gzip_crc = crc32( $gzip_contents );
			$gzip_contents = gzcompress( $gzip_contents, 9 );
			$gzip_contents = substr( $gzip_contents, 0, strlen($gzip_contents) - 4 );
			echo "\x1f\x8b\x08\x00\x00\x00\x00\x00";
			echo $gzip_contents;
			echo pack( 'V', $gzip_crc );
			echo pack( 'V', $gzip_size );
		}
	}
	$db->sql_close();
	exit();
}

foot();

?>