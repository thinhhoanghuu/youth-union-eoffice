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

if ( ! defined('NV_SYSTEM') )
{
	die( "You can't access this file directly..." );
}
// www.mangvn.org - 2009
require_once ( "mainfile.php" );
$module_name = basename( dirname(__file__) );
get_lang( $module_name );
if ( file_exists("" . $datafold . "/config_" . $module_name . ".php") )
{
	@require_once ( "" . $datafold . "/config_" . $module_name . ".php" );
}
if ( defined('_MODTITLE') ) $module_title = _MODTITLE;
##########################################

// Khai báo các thông số trình bày hình cho block Weblinks.
$tocdo = 5; // Tốc độ slide, tính bằng giây
$hinh = 5; // số banner trình bày trong 1 slide
$rong = 120; // chiều rộng, tính bằng px
$cao = 60; // chiều cao, tính bằng px
$kieu = 2; // Kiểu trình bày. 1: dàn logo dọc; 2: dàn logo ngang
$tongso = 50; // Số banner tối đa gọi ra trong 1 lần
$source = 0; // Lọc banner từ chủ đề nào? 0: tất cả chủ đề; -1: chủ đề gốc; >0: chủ đề lựa chọn

// Hết khai báo

echo "var avx = new Array(); var avy = new Array();\n\n";

if ( $source == -1 )
{

	$linkcats = array();
	$homecat = 0;
	$result = $db->sql_query( "SELECT * FROM " . $prefix . "_weblinks_cats WHERE language='" . $currentlang . "' ORDER BY id" );
	while ( $row = $db->sql_fetchrow($result) )
	{
		$linkcats[intval( $row['id'] )] = array( 'id' => intval($row['id']), 'title' => stripslashes($row['title']) );
		if ( $row['ihome'] == '1' )
		{
			$homecat = intval( $row['id'] );
		}
	}
	$viewcat = intval( $_REQUEST['viewcat'] );
	if ( ! $viewcat )
	{
		$viewcat = $homecat;
	}
	if ( ! isset($linkcats[$viewcat]) )
	{
		foreach ( $linkcats as $key => $value )
		{
			$viewcat = $key;
			break;
		}
	}

	if ( $linkcats != array() )
	{
		$cid = $viewcat;
		//	$cname = $linkcats[$viewcat]['title'];

		$result2 = $db->sql_query( "SELECT * FROM " . $prefix . "_weblinks_links WHERE cid='" . $cid . "' AND active='1' AND urlimg!='' ORDER BY RAND() LIMIT " . $tongso . "" );
		$numrows2 = $db->sql_numrows( $result2 );
		if ( $numrows2 )
		{

			$i = 0;
			while ( $row2 = $db->sql_fetchrow($result2) )
			{
				$id = intval( $row2['id'] );
				$urlimg = ( $row2['urlimg'] );
				//$title = stripslashes($row2['title']);
				//$description = stripslashes($row2['description']);
				echo "avx[$i]=\"$nukeurl/modules.php?name=" . $module_name . "&amp;lid=" . $id . "\";";
				echo " avy[$i]=\"" . $urlimg . "\";\n";
				//
				$i = $i + 1;
			}
		}
	}

} elseif ( $source == 0 )
{
	$result2 = $db->sql_query( "SELECT * FROM " . $prefix . "_weblinks_links WHERE active='1' AND urlimg!='' ORDER BY RAND() LIMIT " . $tongso . "" );
	$numrows2 = $db->sql_numrows( $result2 );
	if ( $numrows2 )
	{

		$i = 0;
		while ( $row2 = $db->sql_fetchrow($result2) )
		{
			$id = intval( $row2['id'] );
			$urlimg = ( $row2['urlimg'] );
			//$title = stripslashes($row2['title']);
			//$description = stripslashes($row2['description']);
			echo "avx[$i]=\"$nukeurl/modules.php?name=" . $module_name . "&amp;lid=" . $id . "\";";
			echo " avy[$i]=\"" . $urlimg . "\";\n";
			//
			$i = $i + 1;
		}
	}
}
else
{

	$result2 = $db->sql_query( "SELECT * FROM " . $prefix . "_weblinks_links WHERE cid='" . $source . "' AND active='1' AND urlimg!='' ORDER BY RAND() LIMIT " . $tongso . "" );
	$numrows2 = $db->sql_numrows( $result2 );
	if ( $numrows2 )
	{

		$i = 0;
		while ( $row2 = $db->sql_fetchrow($result2) )
		{
			$id = intval( $row2['id'] );
			$urlimg = ( $row2['urlimg'] );
			//$title = stripslashes($row2['title']);
			//$description = stripslashes($row2['description']);
			echo "avx[$i]=\"$nukeurl/modules.php?name=" . $module_name . "&amp;lid=" . $id . "\";";
			echo " avy[$i]=\"" . $urlimg . "\";\n";
			//
			$i = $i + 1;
		}
	}

}


// khai bao cac tham so trinh bay.
echo "target = \"_blank\";\n";
echo "rnd = \"y\";\n";
echo "numlg = $hinh;\n";
echo "width = $rong;\n";
echo "height = $cao;\n";
echo "border = 0;\n";
echo "margin = 2;\n";
if ( $kieu = 1 )
{
	echo "space = \"<br>\";\n";
}
else
{
	echo "space = \" \";\n";
}
echo "intervan = $tocdo;\n";

?>