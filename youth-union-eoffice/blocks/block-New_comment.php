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
// viet boi conmatdo - nghethuatsong.biz
$path1 = "" . INCLUDE_PATH . "uploads/News/pic";
global $catid, $prefix, $user_prefix, $db, $multilingual, $currentlang;
if ( $multilingual == 1 )
{
	$querylang = "AND (alanguage='$currentlang' OR alanguage='')";
}
else
{
	$querylang = "";
}
if ( $catid != "" )
{
	$cat25 = "AND b.catid='$catid'";
}
$cmm = "SELECT a.tid as tid, a.sid as sid, a.name as name, a.comment as comment, b.title as title, b.images as images, b.catid as catid, c.viewuname as viewuname, c.user_id as user_id FROM " . $prefix . "_stories_comments a, " . $prefix . "_stories b, " . $user_prefix . "_users c WHERE b.sid=a.sid AND c.username=a.name $cat25 $querylang ORDER BY a.date DESC  LIMIT 5";
$hai15 = $db->sql_query( $cmm );
if ( $db->sql_numrows($hai15) != 0 )
{
	$content = "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"margin-top: 5px;\">\n" . "<tr>\n" . "<td style=\"border: 1px solid #F0F0F0\">\n" . "<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">\n" . "<tr>\n<td>";
	while ( $chaphet = $db->sql_fetchrow($hai15) )
	{
		$sidcm = intval( $chaphet['sid'] );
		$titlecm = stripslashes( check_html($chaphet['title'], "nohtml") );
		$comment = stripslashes( check_html($chaphet['comment'], "nohtml") );
		$ten = stripslashes( check_html($chaphet['viewuname'], "nohtml") );
		$img = $chaphet['images'];
		$user_id = intval( $chaphet['user_id'] );
		//cat ngan comment - copy tu cat ngan trong module Files cua lazer
		$comment = substr( $comment, 0, "100" );
		$phandoan = strrpos( $comment, " " );
		$comment = substr( $comment, 0, $phandoan );
		$more = "...";
		//het cat ngan
		$ha = "colspan=\"2\"";
		if ( $img != "" )
		{
			$imgs = "<a title=\"$titlecm\" href=\"modules.php?name=News&op=viewst&sid=$sidcm\"><img border=\"0\" src=\"$path1/$img\" width=\"45\"></a></td><td>";
			$ha = "";
		}
		$content .= "<tr>\n<td colspan=\"2\"><hr /><b><small><a href=\"modules.php?name=Your_Account&op=userinfo&user_id=$user_id\">$ten</a></small></b><br>$comment$more</td>\n</tr><tr>\n<td>";
		$content .= "$imgs<a title=\"$titletop10art\" href=\"modules.php?name=News&op=viewst&sid=$sidcm\"><i>$titlecm</i></a></td></tr>\n";
	}
	$content .= "\n</table>\n</td>\n</tr>\n</table>\n<br>\n";
}

?>