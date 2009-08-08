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

//block design by Lam Hoang Thien. website:http://www.metinfo.net. mail: thienlhoang@yahoo.com

if ( (! defined('NV_SYSTEM')) and (! defined('NV_ADMIN')) )
{
	Header( "Location: ../index.php" );
	exit;
}

global $prefix, $db, $bgcolor2, $bgcolor3, $multilingual, $currentlang;

$limit = 5; //so ban tin
$sizeximg = 120; //Chieu ngang hinh
$interv = 90; //Khoang thoi gian, tinh bang ngay
$yesimg = 1; //chi chon nhung bai co hinh anh
$path = "" . INCLUDE_PATH . "uploads/News/pic";
$Scroll = 1; //Co cho noi dung cua block chay tu duoi len tren hay khong. 0 - khong, 1 - dong y
############### Het phan khai bao ##########################
if ( $multilingual == 1 )
{
	$querylang = "AND (alanguage='$currentlang' OR alanguage='')";
}
else
{
	$querylang = "";
}
$sr = time() - $interv * 86400;
if ( $yesimg == 1 )
{
	$resulttop10art = $db->sql_query( "SELECT sid, title, images FROM " . $prefix . "_stories WHERE (images!='') AND (UNIX_TIMESTAMP(time) > $sr) $querylang ORDER BY counter DESC LIMIT $limit" );
}
else
{
	$resulttop10art = $db->sql_query( "SELECT sid, title, images FROM " . $prefix . "_stories WHERE (UNIX_TIMESTAMP(time) > $sr) $querylang ORDER BY counter DESC LIMIT $limit" );
}
$numstories = $db->sql_numrows( $resulttop10art );
$a = 1;
$content = "";
if ( $numstories != 0 )
{
	if ( $Scroll == 1 )
	{
		$content .= "<marquee behavior= \"scroll\" align= \"center\" direction= \"up\" height=\"150\" scrollamount= \"2\" scrolldelay= \"100\" onmouseover='this.stop()' onmouseout='this.start()'>";
	}
	$content .= "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" width=\"100%\">\n";
	while ( $rowtop10articl = $db->sql_fetchrow($resulttop10art) )
	{
		$sidtop10art = intval( $rowtop10articl['sid'] );
		$imgtop10art = $rowtop10articl['images'];
		$titletop10art = stripslashes( check_html($rowtop10articl['title'], "nohtml") );
		$bgcolor12 = $bgcolor2;
		if ( $a % 2 == 1 )
		{
			$bgcolor12 = $bgcolor1;
		}
		$content .= "<tr>\n<td><table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"3\" width=\"100%\" onmouseout=\"this.style.background=''\" onmouseover=\"this.style.background='$bgcolor1'\">\n";
		if ( $imgtop10art != "" and file_exists("" . $path . "/" . $imgtop10art . "") )
		{
			if ( file_exists("" . $path . "/small_" . $imgtop10art . "") )
			{
				$imgtop10art = "small_" . $imgtop10art . "";
			}
			$content .= "<tr><td valign=\"center\" width=\"$sizeximg\" align=\"center\"><a title=\"$titletop10art\" href=\"modules.php?name=News&op=viewst&sid=$sidtop10art\"><img border=\"0\" src=\"$path/$imgtop10art\" width=\"$sizeximg\"></a></td></tr>\n";
		}
		$content .= "<tr><td width=\"100%\" align=\"left\"><a href=\"modules.php?name=News&op=viewst&sid=$sidtop10art\">$titletop10art</a></td></tr></table></td>\n</tr>\n";
		$a = $a + 1;
	}
	$content .= "</table>\n";
	if ( $Scroll == 1 )
	{
		$content .= "</marquee>";
	}
}

?>