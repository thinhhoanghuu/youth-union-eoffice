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

global $currentlang, $module_name, $datafold, $prefix, $db;
include ( "" . INCLUDE_PATH . "" . $datafold . "/config_News.php" );
$content = "";
if ( $module_name == "News" and isset($_GET['op']) and $_GET['op'] == "viewst" and isset($_GET['sid']) )
{
	$sql = "SELECT * FROM " . $prefix . "_stories_images WHERE sid='" . intval( $_GET['sid'] ) . "' AND catid='0' AND ihome='0' ORDER BY imgid DESC LIMIT $block_atl";
} elseif ( $module_name == "News" and isset($_GET['op']) and $_GET['op'] == "viewcat" and isset($_GET['catid']) )
{
	if ( $htatl == 0 )
	{
		$total = $db->sql_numrows( $db->sql_query("SELECT * FROM " . $prefix . "_stories_images WHERE catid='" . intval($_GET['catid']) . "' AND sid='0' AND ihome='0' AND (ilanguage='$currentlang' OR ilanguage='')") );
		$total = $total - 1;
		mt_srand( (double)microtime() * 1000000 );
		$sidrand = mt_rand( 0, $total );
		$sql = "SELECT * FROM " . $prefix . "_stories_images WHERE catid='" . intval( $_GET['catid'] ) . "' AND sid='0' AND ihome='0' AND (ilanguage='$currentlang' OR ilanguage='') LIMIT $sidrand, 1";
	}
	else
	{
		$sql = "SELECT * FROM " . $prefix . "_stories_images WHERE catid='" . intval( $_GET['catid'] ) . "' AND sid='0' AND ihome='0' AND (ilanguage='$currentlang' OR ilanguage='') ORDER BY imgid DESC LIMIT 0, 1";
	}
}
else
{
	if ( $htatl == 0 )
	{
		$total = $db->sql_numrows( $db->sql_query("SELECT * FROM " . $prefix . "_stories_images WHERE catid='0' AND sid='0' AND ihome='1' AND (ilanguage='$currentlang' OR ilanguage='')") );
		$total = $total - 1;
		mt_srand( (double)microtime() * 1000000 );
		$sidrand = mt_rand( 0, $total );
		$sql = "SELECT * FROM " . $prefix . "_stories_images WHERE catid='0' AND sid='0' AND ihome='1' AND (ilanguage='$currentlang' OR ilanguage='') LIMIT $sidrand, 1";
	}
	else
	{
		$sql = "SELECT * FROM " . $prefix . "_stories_images WHERE catid='0' AND sid='0' AND ihome='1' AND (ilanguage='$currentlang' OR ilanguage='') ORDER BY imgid DESC LIMIT 0, 1";
	}
}
$result = $db->sql_query( $sql );
if ( $db->sql_numrows($result) != 0 )
{
	$a = 0;
	while ( $row = $db->sql_fetchrow($result) )
	{
		$imgid = intval( $row['imgid'] );
		$imgtitle = stripslashes( check_html($row['imgtitle'], "nohtml") );
		$imgtext = stripslashes( check_html($row['imgtext'], "nohtml") );
		$imglink = stripslashes( $row['imglink'] );
		if ( $imglink != "" and file_exists("" . INCLUDE_PATH . "" . $path . "/" . $imglink . "") )
		{
			$size1 = @getimagesize( "" . INCLUDE_PATH . "" . $path . "/" . $imglink . "" );
			$imglink1 = $imglink;
			if ( file_exists("" . INCLUDE_PATH . "" . $path . "/small_" . $imglink . "") )
			{
				$imglink = "small_" . $imglink . "";
			}
			$size2 = @getimagesize( "" . INCLUDE_PATH . "" . $path . "/" . $imglink . "" );
			if ( $size2[0] > $sizeatl )
			{
				$size3 = $sizeatl;
			}
			else
			{
				$size3 = $size2[0];
			}
			if ( $a > 0 ) $content .= "<hr>";
			$content .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
			if ( $size1[0] > $size3 )
			{
				$content .= "<tr>\n<td align=\"center\"><img style=\"cursor: hand\" onclick=\"return MM_openBrWindow('" . INCLUDE_PATH . "viewimg.php?imglink=" . $path . "/" . $imglink1 . "','viewclick','scrollbars=no,width=$size1[0],height=$size1[1],resizable=no');\" border=\"0\" src=\"" . INCLUDE_PATH . "" . $path . "/" . $imglink . "\" width=\"$size3\" style=\"border: 0px solid #000000\" title=\"$imgtitle\"></td></tr>\n";
			}
			else
			{
				$content .= "<tr>\n<td align=\"center\"><img border=\"0\" src=\"" . INCLUDE_PATH . "" . $path . "/" . $imglink . "\" width=\"$size3\" style=\"border: 0px solid #000000\" title=\"$imgtitle\"></td></tr>\n";
			}
			if ( $imgtext != "" )
			{
				$content .= "<tr>\n<td style=\"text-align: justify\">$imgtext</td>\n</tr>\n";
			}
			$content .= "</table>\n";
			$a++;
		}
	}
}

?>