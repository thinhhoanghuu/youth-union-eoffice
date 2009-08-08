<?php

/*
* @Program:		NukeViet CMS v2.0 RC1
* @File name: 	News.php @ Module Search
* @Version: 	2.0
* @Date: 		01.05.2009
* @Website: 	www.nukeviet.vn
* @Copyright: 	(C) 2009
* @License: 	http://opensource.org/licenses/gpl-license.php GNU Public License
*/

if ( eregi("News.php", $_SERVER['SCRIPT_NAME']) )
{
	Header( "Location: ../index.php" );
	exit;
}
$checkurl = $_SERVER['REQUEST_URI'];
if ( preg_match("/http:\/\//i", $checkurl) )
{
	Header( "Location: ../index.php" );
	exit;
}
if ( ! isset($min) ) $min = 0;
if ( ! isset($max) ) $max = $min + $offset;

$sqlNews = "select sid, title, hometext, bodytext  from $prefix" . _stories . " where (title like '%$query%' OR hometext like '%$query%' OR bodytext like '%$query%') AND alanguage='$currentlang'";
$resultNews = $db->sql_query( $sqlNews );
$nrowsNews = $db->sql_numrows( $resultNews );

$sql = "select sid, title, hometext, bodytext from $prefix" . _stories . " where (title like '%$query%' OR hometext like '%$query%' OR bodytext like '%$query%') AND alanguage='$currentlang' ORDER BY sid DESC limit $min, $offset";
$result = $db->sql_query( $sql );
$nrows = $db->sql_numrows( $result );
$x = 0;
if ( $nrows > 0 )
{
	$sql3 = "SELECT custom_title  FROM " . $prefix . "_modules WHERE title='News'";
	$result3 = $db->sql_query( $sql3 );
	$row3 = $db->sql_fetchrow( $result3 );
	$m_title = $row3[custom_title];
	echo "<br><font class=storytitle>" . _NSRESULTS . ":&nbsp;<a href=modules.php?name=News class=storytitle>$m_title</a></font><br><br>";
	while ( $row = $db->sql_fetchrow($result) )
	{
		$sid = $row['sid'];
		$title = $row['title'];
		$furl = "modules.php?name=News&op=viewst&sid=$sid";
		echo "<br><a href=\"$furl\"><b>$title</b></a><br>";
		$hometext = $row['hometext'];
		$bodytext = $row['bodytext'];
		$text = "$hometext $bodytext";
		$text = stripslashes( check_html($text, nohtml) );
		$vitri = strpos( $text, $query );

		if ( $vitri > 80 )
		{
			$text = substr( $text, $vitri - 80 );
			echo "...";
			$vitri1 = strpos( $text, " " );
		}
		else
		{
			$vitri1 = 0;
		}
		$text = substr( $text, $vitri1, $vitri1 + 160 );
		$vitri2 = strrpos( $text, " " );
		$text = substr( $text, 0, $vitri2 );
		$query1 = "<span style=\"background-color: #FFFF00\">$query</span>";
		$text1 = str_replace( $query, $query1, $text );
		echo "$text1...<br>";
		$x++;
	}
}
else
{
	if ( $offset = 1 )
	{
		echo "<br><font class=storytitle>" . _NSRESULTS . ":&nbsp;<a href=modules.php?name=News class=storytitle>$m_title</a></font><br><br>";
		echo "" . _KQNODATA . ": <b>''$query''</b> " . _KQINMOD . " <a href=modules.php?name=News>$m_title</a>";
	}
}

$prev = $min - $offset;
$next = $min + $offset;
echo "<br><center>";
if ( $prev >= 0 )
{
	echo "<a href=\"modules.php?name=Search&modname=$mod_title&min=$prev&amp;query=$query\">";
	echo "<b>$min " . _PREVMATCHES . "</b></a>&nbsp;&nbsp;";
}
if ( $x >= 15 )
{
	echo "&nbsp;&nbsp;<a href=\"modules.php?name=Search&modname=$mod_title&min=$next&amp;query=$query\">";
	echo "<b>" . _NEXTMATCHES . "</b></a><br><i>Tìm thấy $nrowsNews kết quả</i>";
}
echo "</center>";

?>