<?php

/*
* @Program:		NukeViet CMS v2.0 RC1
* @File name: 	Files.php @ Module Search
* @Version: 	2.0
* @Date: 		01.05.2009
* @Website: 	www.nukeviet.vn
* @Copyright: 	(C) 2009
* @License: 	http://opensource.org/licenses/gpl-license.php GNU Public License
*/

if ( eregi("Files.php", $_SERVER['SCRIPT_NAME']) )
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

$sqlFiles = "select lid, title, description from $prefix" . _files . " where (title like '%$query%' OR description like '%$query%')";
$resultFiles = $db->sql_query( $sqlFiles );
$nrowsFiles = $db->sql_numrows( $resultFiles );

$sql = "select lid, title, description from $prefix" . _files . " where (title like '%$query%' OR description like '%$query%') AND status != '0' LIMIT $min,$offset";
$result = $db->sql_query( $sql );
$nrows = $db->sql_numrows( $result );
$x = 0;
if ( $nrows > 0 )
{
	$sql3 = "SELECT custom_title  FROM " . $prefix . "_modules WHERE title='Files'";
	$result3 = $db->sql_query( $sql3 );
	$row3 = $db->sql_fetchrow( $result3 );
	$m_title = $row3[custom_title];
	echo "<br><font class=storytitle>" . _NSRESULTS . ":&nbsp;<a href=modules.php?name=Files class=storytitle>$m_title</a></font>";
	while ( $row = $db->sql_fetchrow($result) )
	{
		$lid = $row['lid'];
		$title = $row['title'];
		$furl = "modules.php?name=Files&go=view_file&lid=$lid";
		echo "<br><a href=\"$furl\"><b>$title</b></a><br>";
		$description = $row['description'];
		$text = "$description";
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
		echo "<br><font class=storytitle>" . _NSRESULTS . ":&nbsp;<a href=modules.php?name=Files class=storytitle>$m_title</a></font><br><br>";
		echo "" . _KQNODATA . ": <b>''$query''</b> " . _KQINMOD . " <a href=modules.php?name=Files>$m_title</a>";
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
	echo "<b>" . _NEXTMATCHES . "</b></a><br><i>Tìm thấy $nrowsFiles kết quả</i>";
}
echo "</center>";

?>