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
global $prefix, $db, $bgcolor2, $bgcolor3, $multilingual, $currentlang;
$limit = 10; //so ban tin
$sizeximg = 50; //Chieu ngang hinh
$yesimg = 0; //chi chon nhung bai co hinh anh
$path = "" . INCLUDE_PATH . "uploads/News/pic";
$Scroll = 0; //Co cho noi dung cua block chay tu duoi len tren hay khong. 0 - khong, 1 - dong y
############### Het phan khai bao ##########################
if ( $multilingual == 1 )
{
	$where_bl = ( $yesimg == 1 ) ? "WHERE (alanguage='$currentlang' OR alanguage='') AND (images!='')" : "WHERE (alanguage='$currentlang' OR alanguage='')";
}
else
{
	$where_bl = ( $yesimg == 1 ) ? "WHERE (images!='')" : "";
}
$resultlast10art = $db->sql_query( "SELECT sid, title, images FROM " . $prefix . "_stories $where_bl ORDER BY sid DESC LIMIT $limit" );
$numstories = $db->sql_numrows( $resultlast10art );
$a = 1;
$content = "";
if ( $numstories > 0 )
{
	$content .= "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n<tr>\n<td>";
	if ( $Scroll )
	{
		$content .= "<marquee behavior= \"scroll\" align= \"center\" direction= \"up\" height=\"150\" scrollamount= \"2\" scrolldelay= \"100\" onmouseover='this.stop()' onmouseout='this.start()'>";
	}
	$content .= "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" width=\"100%\">\n";
	while ( $rowlast10articl = $db->sql_fetchrow($resultlast10art) )
	{
		$sidlast10art = intval( $rowlast10articl['sid'] );
		$imglast10art = $rowlast10articl['images'];
		$titlelast10art = stripslashes( check_html($rowlast10articl['title'], "nohtml") );
		$bgcolor23 = $bgcolor3;
		if ( $a % 2 == 1 )
		{
			$bgcolor23 = $bgcolor2;
		}
		$content .= "<tr>\n<td><table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"3\" width=\"100%\" bgcolor=\"$bgcolor23\"><tr>\n";
		if ( $imglast10art != "" and file_exists("" . $path . "/" . $imglast10art . "") )
		{
			if ( file_exists("" . $path . "/small_" . $imglast10art . "") )
			{
				$imglast10art = "small_" . $imglast10art . "";
			}
			$content .= "<td valign=\"center\" width=\"$sizeximg\"><a title=\"$titlelast10art\" href=\"modules.php?name=News&op=viewst&sid=$sidlast10art\"><img border=\"0\" src=\"$path/$imglast10art\" width=\"$sizeximg\"></a></td>\n";
		}
		$content .= "<td width=\"100%\" align=\"left\"><a href=\"modules.php?name=News&op=viewst&sid=$sidlast10art\">$titlelast10art</a></td></tr></table></td>\n</tr>\n";
		$a = $a + 1;
	}

	$content .= "</table>\n";
	if ( $Scroll )
	{
		$content .= "</marquee>";
	}
	$content .= "</td>\n</tr>\n</table>\n";
}

?> 