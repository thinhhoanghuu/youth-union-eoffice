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

$imgdir = "uploads/randompic"; //thu muc chua cac file hinh anh
$imgt = array( "gif", "jpg", "png" ); //loai file hinh cho phep
$imgw = 140; //chieu ngang cua hinh duoc the hien tren block

/****************Het phan khai bao***************/
$pic_rand = array();
$opend = @opendir( "" . INCLUDE_PATH . "" . $imgdir . "" );
$c = 0;
while ( $fname = @readdir($opend) )
{
	$fileres = end( explode(".", $fname) );
	if ( $fname != "" and in_array($fileres, $imgt) )
	{
		$pic_rand[$c] = $fname;
		$c++;
	}
}
@closedir( $opend );
$content = "";
if ( $c > 0 )
{
	$rand = mt_rand( 0, ($c - 1) );
	$pic_r = $pic_rand[$rand];
	if ( file_exists("" . INCLUDE_PATH . "" . $imgdir . "/" . $pic_r . "") )
	{
		$size_img = @getimagesize( "" . INCLUDE_PATH . "" . $imgdir . "/" . $pic_r . "" );
		if ( $size_img[0] > $imgw )
		{
			$pic_r2 = "<img style=\"cursor: hand\" onclick=\"return MM_openBrWindow('viewimg.php?imglink=" . $imgdir . "/" . $pic_r . "','viewclick','scrollbars=no,width=$size_img[0],height=$size_img[1],resizable=no');\" border=\"0\" src=\"" . INCLUDE_PATH . "" . $imgdir . "/" . $pic_r . "\" width=\"" . $imgw . "\">";
		}
		else
		{
			$pic_r2 = "<img border=\"0\" src=\"" . INCLUDE_PATH . "" . $imgdir . "/" . $pic_r . "\" width=\"$size_img[0]\">";
		}
		$content .= "<table border=\"0\" cellpadding=\"0\" style=\"border-collapse: collapse\" width=\"100%\">\n";
		$content .= "<tr>\n";
		$content .= "<td align=\"center\">\n" . $pic_r2 . "</td>\n";
		$content .= "</tr>\n";
		$content .= "</table>\n";
	}
}

?>