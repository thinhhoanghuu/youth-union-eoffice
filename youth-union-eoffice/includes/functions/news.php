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
 * liststauto()
 * 
 * @return
 */
function liststauto()
{
	global $datafold, $prefix, $db;
	@chmod( "" . INCLUDE_PATH . "" . $datafold . "/autnewslist.php", 0777 );
	@$file = fopen( "" . INCLUDE_PATH . "" . $datafold . "/autnewslist.php", "w" );
	$content = "<?php\n\n";
	$fctime = date( "d-m-Y H:i:s", filectime("" . INCLUDE_PATH . "" . $datafold . "/autnewslist.php") );
	$fmtime = date( "d-m-Y H:i:s" );
	$content .= "// File: autnewslist.php.\n// Created: $fctime.\n// Modified: $fmtime.\n// Do not change anything in this file!\n\n";
	$content .= "if ((!defined('NV_SYSTEM')) AND (!defined('NV_ADMIN'))) {\n";
	$content .= "die('Stop!!!');\n";
	$content .= "}\n";
	$content .= "\n";
	$result = $db->sql_query( "SELECT anid, UNIX_TIMESTAMP(time) as formatted FROM " . $prefix . "_stories_auto" );
	$a = 0;
	while ( $row = $db->sql_fetchrow($result) )
	{
		$content .= "\$autnews[" . $a . "] = \"" . $row['anid'] . "|" . $row['formatted'] . "\";\n";
		$a++;
	}
	$content .= "\n";
	$content .= "?>";
	@fwrite( $file, $content );
	@fclose( $file );
	@chmod( "" . INCLUDE_PATH . "" . $datafold . "/autnewslist.php", 0604 );
}

/**
 * automated_news()
 * 
 * @return
 */
function automated_news()
{
	global $datafold, $prefix, $db;
	$autnews = array();
	if ( file_exists("" . INCLUDE_PATH . "" . $datafold . "/autnewslist.php") )
	{
		@include_once ( "" . INCLUDE_PATH . "" . $datafold . "/autnewslist.php" );
		$x = 0;
		$time = time();
		for ( $i = 0; $i < sizeof($autnews); $i++ )
		{
			$autnews_ar[$i] = explode( "|", $autnews[$i] );
			$autn[$i] = intval( $autnews_ar[$i][1] );
			if ( $autn[$i] <= $time and $autn[$i] != 0 )
			{
				$result2 = $db->sql_query( "SELECT * FROM " . $prefix . "_stories_auto WHERE anid='" . $autnews_ar[$i][0] . "'" );
				$row2 = $db->sql_fetchrow( $result2 );
				$catid2 = intval( $row2['catid'] );
				$title = stripslashes( FixQuotes($row2['title']) );
				$hometext = stripslashes( FixQuotes($row2['hometext']) );
				$bodytext = stripslashes( FixQuotes($row2['bodytext']) );
				$notes = stripslashes( FixQuotes($row2['notes']) );
				$imgtext = stripslashes( FixQuotes($imgtext) );
				$source = stripslashes( FixQuotes($source) );
				$db->sql_query( "INSERT INTO " . $prefix . "_stories VALUES (NULL, '$catid2', '$row2[aid]', '$title', now(), '$hometext', '$bodytext', '$row2[images]', '0', '0', '$notes', '$row2[ihome]', '$row2[alanguage]', '$row2[acomm]', '$row2[imgtext]', '$row2[source]', '$row2[topicid]', '0')" );
				$db->sql_query( "DELETE FROM " . $prefix . "_stories_auto WHERE anid='" . $autnews_ar[$i][0] . "'" );
				$db->sql_query( "OPTIMIZE TABLE " . $prefix . "_stories_auto" );
				$x++;
			}
		}
		if ( $x > 0 )
		{
			liststauto();
			header( "Location: " . INCLUDE_PATH . "index.php" );
			exit();
		}
	}
}

/**
 * newsstart()
 * 
 * @return
 */
function newsstart()
{
	global $datafold, $db, $prefix, $multilingual, $currentlang;
	include ( "" . INCLUDE_PATH . "" . $datafold . "/config_News.php" );
	if ( $multilingual == 1 )
	{
		$querylang = "AND (alanguage='$currentlang' OR alanguage='')";
	}
	else
	{
		$querylang = "";
	}
	$resuls1 = $db->sql_query( "SELECT * FROM " . $prefix . "_stories where newsst='1' $querylang ORDER BY sid DESC LIMIT 1" );
	$row1 = $db->sql_fetchrow( $result1 );
	if ( $row1 )
	{
		$sid_st = intval( $row1['sid'] );
		$title_st = "<a href=\"modules.php?name=News&amp;op=viewst&amp;sid=$sid_st\">" . stripslashes( check_html($row1['title'], "nohtml") ) . "</a>";
		$hometext_st = stripslashes( $row1['hometext'] );
		$image_st = $row1['images'];
		if ( file_exists("" . INCLUDE_PATH . "" . $path . "/nst_" . $image_st . "") )
		{
			$image_st = "nst_" . $image_st . "";
		} elseif ( file_exists("" . INCLUDE_PATH . "" . $path . "/small_" . $image_st . "") )
		{
			$image_st = "small_" . $image_st . "";
		}
		$size2 = @getimagesize( "$path/$image_st" );
		$widthimg = $size2[0];
		if ( $widthimg > $sizeimgskqa ) $widthimg = $sizeimgskqa;
		if ( $image_st != "" )
		{
			$image_st01 = "<table border=\"0\" width=\"$widthimg\" cellpadding=\"0\" cellspacing=\"3\" align=\"$catnewshomeimg\">\n<tr>\n<td>\n<a href=\"modules.php?name=News&amp;op=viewst&amp;sid=$sid_st\" title=\"" . stripslashes( check_html($row1['title'], "nohtml") ) . "\"><img border=\"0\" src=\"$path/$image_st\" width=\"$widthimg\"></a></td>\n</tr>\n</table>\n";
		}
		else
		{
			$image_st01 = "";
		}
		$story_link = "<a href=\"modules.php?name=News&amp;op=viewst&amp;sid=$sid_st\"><img src='images/more.gif' border='0'  alt=\"" . _READMORE . "\" align=\"right\"></a>";
		themenewsst( $title_st, $image_st01, $hometext_st, $story_link );
	}
}

/**
 * ncatlist()
 * 
 * @return
 */
function ncatlist()
{
	global $db, $prefix, $datafold;
	@chmod( "" . INCLUDE_PATH . "" . $datafold . "/ncatlist.php", 0777 );
	@$file = fopen( "" . INCLUDE_PATH . "" . $datafold . "/ncatlist.php", "w" );
	$content = "<?php\n\n";
	$fctime = date( "d-m-Y H:i:s", filectime("" . INCLUDE_PATH . "" . $datafold . "/ncatlist.php") );
	$fmtime = date( "d-m-Y H:i:s" );
	$content .= "// File: ncatlist.php.\n// Created: $fctime.\n// Modified: $fmtime.\n// Do not change anything in this file!\n\n";
	$content .= "if ((!defined('NV_SYSTEM')) AND (!defined('NV_ADMIN'))) {\n";
	$content .= "die('Stop!!!');\n";
	$content .= "}\n";
	$content .= "\n";
	$sql = "SELECT * FROM " . $prefix . "_stories_cat ORDER BY weight";
	$result = $db->sql_query( $sql );
	$a = 0;
	while ( $row = $db->sql_fetchrow($result) )
	{
		$sql2 = "SELECT catid FROM " . $prefix . "_stories_cat WHERE parentid='$row[0]' ORDER BY weight";
		$result2 = $db->sql_query( $sql2 );
		$parids = array();
		while ( $row2 = $db->sql_fetchrow($result2) )
		{
			$parids[] = $row2[0];
		}
		$parids = implode( ",", $parids );
		$numx = $db->sql_fetchrow( $db->sql_query("SELECT COUNT(*) FROM " . $prefix . "_stories WHERE sid='$row[6]' AND newsst!='1'") );
		if ( $numx[0] == 1 )
		{
			$tintd = $row[6];
		}
		else
		{
			$tintd = 0;
		}
		$content .= "\$nvcat[" . $a . "] = \"" . $row[0] . "|" . $row[1] . "|" . $row[2] . "|" . $row[3] . "|" . $row[4] . "|" . $row[5] . "|" . $tintd . "|" . $row[7] . "|" . $parids . "\";\n";
		$content .= "\$nvcat2[" . $row[0] . "] = \"" . $row[0] . "|" . $row[1] . "|" . $row[2] . "|" . $row[3] . "|" . $row[4] . "|" . $row[5] . "|" . $tintd . "|" . $row[7] . "|" . $parids . "\";\n";
		$a++;
	}
	$content .= "\n";
	$content .= "?>";
	@$writefile = fwrite( $file, $content );
	@fclose( $file );
	@chmod( "" . INCLUDE_PATH . "" . $datafold . "/ncatlist.php", 0604 );
}

if ( ! file_exists("" . INCLUDE_PATH . "" . $datafold . "/autnewslist.php") )
{
	liststauto();
	header( "Location: " . INCLUDE_PATH . "index.php" );
	exit();
}
if ( ! file_exists("" . INCLUDE_PATH . "" . $datafold . "/ncatlist.php") )
{
	ncatlist();
	header( "Location: " . INCLUDE_PATH . "index.php" );
	exit();
}

?>