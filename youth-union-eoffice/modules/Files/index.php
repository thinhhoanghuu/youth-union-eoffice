<?php

/*
* @Program:		NukeViet CMS v2.0 RC1
* @File name: 	Module Files
* @Version: 	2.2
* @Date: 		14.06.2009
* @Website: 	www.nukeviet.vn
* @Copyright: 	(C) 2009
* @License: 	http://opensource.org/licenses/gpl-license.php GNU Public License
*/

if ( ! defined('NV_SYSTEM') )
{
	die( "You can't access this file directly..." );
}

require_once ( "mainfile.php" );
$module_name = basename( dirname(__file__) );
get_lang( $module_name );
if ( file_exists("" . $datafold . "/config_" . $module_name . ".php") )
{
	@require_once ( "" . $datafold . "/config_" . $module_name . ".php" );
}

$index = ( defined('MOD_BLTYPE') ) ? MOD_BLTYPE : 1;
/********************************************/

/**
 * getit()
 * 
 * @return
 */
function getit()
{
	global $sitekey, $prefix, $db, $module_name, $download, $fchecknum, $nukeurl, $ttt, $prefilename, $autofilename; //FIL07 / 19.07.06
	$lid = intval( $_POST['lid'] );
	$gfx_check = intval( $_POST['gfx_check'] );
	if ( ! isset($lid) or $lid == 0 )
	{
		Header( "Location: modules.php?name=$module_name" );
		die();
	} elseif ( $numrows = $db->sql_numrows($db->sql_query("select lid FROM " . $prefix . "_files where lid='$lid' AND status!='0'")) != 1 )
	{
		Header( "Location: modules.php?name=$module_name" );
		die();
	} elseif ( $download == 1 and ! defined('IS_USER') and ! defined('IS_ADMMOD') )
	{
		Header( "Location: index.php" );
		exit;
	} elseif ( extension_loaded("gd") and (! nv_capcha_txt($gfx_check)) and ($fchecknum == 1) and (! defined('IS_ADMMOD') OR ! defined('IS_USER')) )
	{
		include ( "header.php" );
		OpenTable();
		echo "<center><br><br><b><a href=\"modules.php?name=$module_name&go=view_file&lid=$lid\">" . _WRONGCHECKNUM . "</a></b><br></center>";
		CloseTable();
		include ( "footer.php" );
	}
	else
	{
		list( $url ) = $db->sql_fetchrow( $db->sql_query("SELECT url FROM " . $prefix . "_files WHERE lid='$lid'") );
		//******************************************************************************/
		//Dung lenh nay` de? tra ve url cua file
		//cach nay se de dang bi phat hien ra url cua file tren host
		//Chi dung dong duoi day neu nhu code lay file ko cho ket qua
		//Header("Location: $url");
		//******************************************************************************/

		$url = str_replace( " ", "%20", $url );
		$url = str_replace( "'", "%27", $url );
		// Bật tắt việc download trực tiếp trong trường hợp lỗi
		if ( $ttt == 1 )
		{ // Neu Cau hinh tai truc tiep bật - add by www.mangvn.org 07-01-2009
			$db->sql_query( "UPDATE " . $prefix . "_files set hits=hits+1 WHERE lid=$lid" );
			Header( "Location: $url" );
			exit();
		} //END
		//code duoi' day se tra file download ve o dang. acttachment,neu click vao nut Tai File
		//ma ko thu duoc file thi dung cau lenh Header("Location: $url");
		elseif ( eregi("" . $nukeurl . "", $url) )
		{ //FIL07 / 19.07.06
			$lastdot = strrpos( $url, "." );
			$lastx = strrpos( $url, "/" );
			$file_name = strtolower( substr($url, $lastx + 1) ); //Dung cho truong hop ban muon hien thi ten file ta?i ve`
			$rand_num = rand( 111111, 999999 ); //Tra ten file ve client mot cach ngau nhien
			$ext = strtolower( substr($url, $lastdot) ); //Phan mo rong cua file
			switch ( $ext )
			{
				case "pdf":
					$ctype = "application/pdf";
					break;
				case "exe":
					$ctype = "application/octet-stream";
					break;
				case "zip":
					$ctype = "application/x-zip-compressed";
					break;
				case "rar":
					$ctype = "application/octet-stream";
					break;
				case "gz":
					$ctype = "application/gzip";
					break;
				case "tgz":
					$ctype = "application/tgz";
					break;
				case "tar":
					$ctype = "application/tar";
					break;
				case "bz2":
					$ctype = "application/bz2";
					break;
				case "doc":
					$ctype = "application/msword";
					break;
				case "xls":
					$ctype = "application/vnd.ms-excel";
					break;
				case "ppt":
					$ctype = "application/vnd.ms-powerpoint";
					break;
				case "gif":
					$ctype = "image/gif";
					break;
				case "png":
					$ctype = "image/png";
					break;
				case "jpeg":
				case "jpg":
					$ctype = "image/jpg";
					break;
				case "mp3":
					$ctype = "audio/mp3";
					break;
				case "wav":
					$ctype = "audio/x-wav";
					break;
				case "mpeg":
				case "mpg":
				case "mpe":
					$ctype = "video/mpeg";
					break;
				case "mov":
					$ctype = "video/quicktime";
					break;
				case "wmv":
				case "avi":
					$ctype = "video/x-msvideo";
					break;
				case "php":
				case "htm":
				case "html":
				case "txt":
					die( "" . eror('' . _ERORTYPE . '') . "" );
					break;
				default:
					$ctype = "application/force-download";
			}
			$db->sql_query( "UPDATE " . $prefix . "_files set hits=hits+1 WHERE lid=$lid" );
			header( "Pragma: no-cache" );
			header( "Expires: 0" );
			if ( $autofilename == 1 )
			{
				header( "Content-Type: $ctype; name=\"" . $prefilename . "" . $rand_num . "" . $ext . "\"" );
				header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
				header( 'Content-Disposition: attachment; filename="' . $prefilename . '' . $rand_num . '' . $ext . '"' );
			}
			else
			{
				header( "Content-Type: $ctype; name=\"" . $prefilename . "" . $file_name . "\"" );
				header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
				header( 'Content-Disposition: attachment; filename="' . $prefilename . '' . $file_name . '"' );
			}
			//ob_start();
			readfile( $url );
			//$size=ob_get_length();
			//header("Content-Length: $size");
			//ob_end_flush();
			//Test tren localhost window thi ok ma tren host linux thi nguoi ta keu loi~ :(,lam on test lai va fix giup em
			exit();
		}
		else
		{ //FIL07 / 19.07.06
			$db->sql_query( "UPDATE " . $prefix . "_files set hits=hits+1 WHERE lid=$lid" ); // Fix loi bo dem tai file sd link ngoai - http://mangvn.org - 08/2008
			Header( "Location: $url" );
			exit();
		} //END
	}
}

//Lay ten cua thu muc khi biet id thu muc
/**
 * get_cat_name()
 * 
 * @param mixed $cid_temp
 * @return
 */
function get_cat_name( $cid_temp )
{
	global $prefix, $db;
	list( $title ) = $db->sql_fetchrow( $db->sql_query("SELECT title FROM " . $prefix . "_files_categories WHERE cid='$cid_temp'") );
	return $title;
}

//Lay mieu ta cua thu muc khi biet id thu muc - by www.mangvn.org 07-01-2009
/**
 * get_cdescription()
 * 
 * @param mixed $cid_temp2
 * @return
 */
function get_cdescription( $cid_temp2 )
{
	global $prefix, $db;
	list( $cdescription ) = $db->sql_fetchrow( $db->sql_query("SELECT cdescription FROM " . $prefix . "_files_categories WHERE cid='$cid_temp2'") );
	return $cdescription;
}

//Duong dan thu muc
/**
 * getparent()
 * 
 * @param mixed $parentid
 * @param mixed $title
 * @return
 */
function getparent( $parentid, $title )
{
	global $prefix, $db;
	$sql = "SELECT cid, title, parentid FROM " . $prefix . "_files_categories WHERE cid=$parentid";
	$res = $db->sql_query( $sql );
	$row = $db->sql_fetchrow( $res );
	$cid = $row['cid'];
	$ptitle = $row['title'];
	$pparentid = $row['parentid'];
	if ( $ptitle != "" ) $title = $ptitle . " | " . $title;
	if ( $pparentid != 0 )
	{
		$title = getparent( $pparentid, $title );
	}
	return $title;
}

//The hien phan header cua module Files
/**
 * files_head()
 * 
 * @return
 */
function files_head()
{
	global $prefix, $db, $module_name, $page_title2, $page_title, $bgcolor1, $bgcolor2, $fnote, $showsub, $addfiles, $fhomemsg, $cid, $lid, $currentlang, $prefix;
	$logofiles = "logo_" . $currentlang . ".gif";
	echo "<br><center><a href=\"modules.php?name=$module_name\"><img border=\"0\" src=\"images/modules/$module_name/$logofiles\"></a></center><br>";
	// trinh bay lai - www.mangvn.org
	if ( ($cid == 0) and ($lid == 0) )
	{
		OpenTable();
		if ( $fnote == 1 )
		{
			echo "<p class=\"indexhometext\">" . $fhomemsg . "</p>";
		}
		CloseTable();
		echo "<br>";
	}
	//
	OpenTable();
	echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\"  align=\"center\">\n<tr>\n";
	$xp = intval( $_GET['p'] );
	$cid = intval( $_GET['cid'] );
	$lid = intval( $_GET['lid'] );
	if ( $cid != 0 )
	{
		$num_parentid = $db->sql_numrows( $db->sql_query("SELECT cid  FROM " . $prefix . "_files_categories WHERE parentid = '$cid'") );
		if ( $num_parentid > 0 )
		{
			$go = "cat";
		}
		else
		{
			$go = "showcat";
		}
		echo "<td><img width=\"11\" border=\"0\" height=\"10\" src=\"images/modules/$module_name/sitemap.gif\"> <a href=\"modules.php?name=$module_name\"><b>" . _MHOME . "</b></a> &raquo; <b>" . get_cat_name( $cid ) . "</b></td>\n";
		echo "<td align=\"right\">\n<form name=\"filesorder\" method=\"post\">" . _XEPFILE . ": " . "<select name=\"fileslist\" OnChange=\"location.href=filesorder.fileslist.options[selectedIndex].value\">\n";
		echo "<option  value=\"modules.php?name=$module_name&go=$go&cid=$cid\">" . _NEWER . "</option>\n";
		$select_text = array( _OLDER, _AZ, _ZA, _HOT, _LESS, _VOTE1, _VOTE2 );
		for ( $p = 0; $p <= 6; $p++ )
		{
			$p2 = $p + 1;
			$seld = "";
			if ( $p2 == $xp )
			{
				$seld = " selected";
			}
			echo "<option  value=\"modules.php?name=$module_name&go=$go&cid=$cid&p=$p2\" $seld>" . $select_text[$p] . "</option>\n";
		}
		echo "</select></form></td>\n";
	}

	// Lấy tên thư mục chứa file // www.mangvn.org 06-01-2009
	elseif ( $lid != 0 )
	{
		$sql = "SELECT cid, title FROM " . $prefix . "_files WHERE lid = '$lid'";
		$res = $db->sql_query( $sql );
		$row = $db->sql_fetchrow( $res );
		$cid = $row['cid'];
		$title = $row['title'];
		$thumuc = "<a href=\"modules.php?name=$module_name&go=cat&cid=$cid\"><b>" . get_cat_name( $cid ) . "</b></a>";
		echo "<td><img width=\"11\" border=\"0\" height=\"10\" src=\"images/modules/$module_name/sitemap.gif\"> <a href=\"modules.php?name=$module_name\"><b>" . _MHOME . "</b></a> &raquo; $thumuc &raquo; $title</td>\n";
		echo "<td align=\"right\">\n </td>\n";

		//		$page_title2 = "&raquo; <a href=\"modules.php?name=$module_name\"><b>"._MHOME."</b></a> &raquo; $thumuc &raquo; $title";
		//		$page_title = ""._MHOME." | $thumuc | $title";

	}

	else
	{

		echo "<td><img width=\"11\" border=\"0\" height=\"10\" src=\"images/modules/$module_name/sitemap.gif\"> <a href=\"modules.php?name=$module_name\"><b>" . _MHOME . "</b></a></td>\n";
		echo "<td align=\"right\">\n<form name=\"filesorder\" method=\"post\">" . _XEPFILE . ": " . "<select name=\"fileslist\" OnChange=\"location.href=filesorder.fileslist.options[selectedIndex].value\">" . "<option value=\"0\">" . _TOPFILES . "</option>" . "<option value=\"modules.php?name=$module_name&go=popular&p=1\">" . _TOPNEWFILES . "</option>\n" . "<option value=\"modules.php?name=$module_name&go=popular&p=2\">" . _TOPDOWN . "</option>\n" . "<option value=\"modules.php?name=$module_name&go=popular&p=3\">" . _TOPVOTE . "</option>\n";
		echo "</select></form></td>\n";
	}
	echo "</tr>\n</table>\n";
	CloseTable();
	echo "<br>";
	// trinh bay lai - www.mangvn.org
	if ( ($cid != 0) and ($lid == 0) )
	{
		OpenTable();
		echo "<p class=\"indexhometext\">" . get_cdescription( $cid ) . "</p>";
		CloseTable();
		echo "<br>";
	}
}

//Begin Lấy tin từ module News - www.mangvn.org 09-2008
/**
 * viewcatnews()
 * 
 * @param mixed $catid
 * @param mixed $numnews
 * @return
 */
function viewcatnews( $catid, $numnews )
{
	global $module_name, $db, $prefix, $multilingual, $currentlang;
	$catid = intval( $catid );
	if ( $catid == 0 )
	{
		Header( "Location: modules.php?name=$module_name" );
		exit;
	}
	list( $catparentid, $cattitle, $storieshome2 ) = $db->sql_fetchrow( $db->sql_query("select parentid, title, storieshome from " . $prefix . "_stories_cat where catid='$catid'") );
	if ( $storieshome2 != 0 )
	{
		$resuls1 = $db->sql_query( "SELECT * FROM " . $prefix . "_stories where sid='$storieshome2'" );
	}
	else
	{
		$result1 = $db->sql_query( "SELECT * FROM " . $prefix . "_stories WHERE catid='$catid' $querylang ORDER BY sid DESC LIMIT 1" );
	}
	if ( $row1 = $db->sql_fetchrow($result1) )
	{
		$path = "uploads/News/pic";
		$width = 600;
		$sizeimgskqa = 200;
		$catnewshomeimg = "left";
		$sid_st = intval( $row1['sid'] );
		$title_st = "<a href=\"modules.php?name=News&amp;op=viewst&amp;sid=$sid_st\">" . stripslashes( check_html($row1['title'], "nohtml") ) . "</a>";
		$hometext_st = stripslashes( $row1['hometext'] );
		$image_st = $row1['images'];
		if ( file_exists("" . INCLUDE_PATH . "" . $path . "/nst_" . $image_st . "") )
		{
			$image_st = "nst_" . $image_st . "";
		}
		if ( $image_st != "" )
		{
			$image_st01 = "<table border=\"0\" width=\"$sizeimgskqa\" cellpadding=\"0\" cellspacing=\"3\" align=\"$catnewshomeimg\">\n<tr>\n<td>\n<a href=\"modules.php?name=News&amp;op=viewst&amp;sid=$sid_st\" title=\"" . stripslashes( check_html($row1['title'], "nohtml") ) . "\"><img border=\"0\" src=\"$path/$image_st\" width=\"$sizeimgskqa\"></a></td>\n</tr>\n</table>\n";
		}
		else
		{
			$image_st01 = "";
		}
		$story_link = "<a href=\"modules.php?name=News&amp;op=viewst&amp;sid=$sid_st\"><img src='images/more.gif' border='0'  alt=\"" . _READMORE . "\" align=\"right\"></a>";
		themenewsst( $title_st, $image_st01, $hometext_st, $story_link );
	}

	$result = $db->sql_query( "SELECT * FROM " . $prefix . "_stories WHERE catid='$catid' AND sid!='$sid_st' $querylang ORDER BY sid DESC LIMIT $numnews" );
	while ( $rowtopic = $db->sql_fetchrow($result) )
	{
		$tsid = intval( $rowtopic['sid'] );
		$ttitle = stripslashes( check_html($rowtopic['title'], "nohtml") );
		$ttime = formatTimestamp( $rowtopic['time'], 2 );
		echo "<img border=\"0\" src=\"images/arrow2.gif\" width=\"10\" height=\"5\"><a href=\"modules.php?name=News&op=viewst&sid=$tsid\"><font  class=\"indexhometext\">$ttitle</font></a><br>\n";
	}
	echo "<a href=\"modules.php?name=News&amp;op=viewcat&amp;catid=$catid\"><img src='images/more.gif' border='0' align=\"right\"></a><br>";
}
//End Lấy tin từ module News - www.mangvn.org 09-2008


//The hien phan footer trang module Files
/**
 * files_foot()
 * 
 * @return
 */
function files_foot()
{
	global $module_name, $prefix, $db, $addfiles;
	echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"margin-top: 4\" align=\"center\">\n<tr>\n";
	$sql = "select cid, title, parentid from " . $prefix . "_files_categories order by parentid,title";
	$res = $db->sql_query( $sql );
	echo " <SCRIPT language=javascript>" . "function openWin(movefilescat) {window.location.href=movefilescat.value; }" . "</SCRIPT>";
	echo "<td>\n<form name=\"gocat\" method=\"post\">" . "<select name=\"cat\" OnChange=\"location.href=gocat.cat.options[selectedIndex].value\">" . "<option value=\"0\">" . _EXPRESSCAT . "</option>" . "<option value=\"modules.php?name=$module_name\">" . _MHOME . "</option>\n";
	while ( $row = $db->sql_fetchrow($res) )
	{
		$cid = $row['cid'];
		$cat_title = $row['title'];
		$parentid = $row['parentid'];
		if ( $parentid != 0 ) $cat_title = getparent( $parentid, $cat_title );
		$sql2 = "SELECT cid  FROM " . $prefix . "_files_categories WHERE parentid = '$cid'";
		$resnum = $db->sql_query( $sql2 );
		if ( $numrows = $db->sql_numrows($resnum) > 0 )
		{
			$links = "cat";
		}
		else
		{
			$links = "showcat";
		}
		echo "<option value=\"modules.php?name=$module_name&go=$links&cid=$cid\">$cat_title</option>";
	}
	if ( $addfiles == 1 )
	{
		echo "<option value=\"modules.php?name=$module_name&go=add_file\">" . _ADDFILE . "</option>";
	}
	echo "</select></form></td>\n";
	echo "<td align=\"center\" width=\"100%\" valign=\"bottom\">\n" . "<a href=\"#top\" onMouseOut=\"window.status=''; return true;\" onMouseOver=\"window.status='" . _TOPPAGE . "'; return true;\"><img border=\"0\" src=\"images/modules/$module_name/up.gif\"  alt=\"" . _TOPPAGE . "\"></a></td>\n";
	echo "<form action=\"modules.php?name=Search\" method=\"post\">" . "<td align=\"right\">" . "<input type=\"text\" name=\"query\" size=\"23\" maxLength=\"57\" onblur=\"if (this.value==''){this.value='" . _SEARCH . "';}\" onfocus=\"if (this.value=='" . _SEARCH . "') {this.value = '';}\" value=\"" . _SEARCH . "\" style=\"text-align: center\">" . "</td>" . "<td width=\"19\">";
	echo "<input type=\"image\" src=\"images/modules/$module_name/go.gif\" alt=\"" . _SEARCH . "\" value=\"" . _SEARCH . "\" style=\"border: 0\">" . "</td>" . "</form>";
	echo "</tr>\n</table>\n";
}

/**
 * newfilesgraphic()
 * 
 * @param mixed $f_data
 * @return
 */
function newfilesgraphic( $f_data )
{
	global $module_name;
	$t_data = mktime();
	$n_data = $t_data - $f_data;
	if ( $n_data < 86400 )
	{
		echo "<img src=\"images/modules/$module_name/new_1.gif\" alt=\"" . _NEWTODAY . "\">";
	}
	if ( ($n_data > 86400) and ($n_data < 259200) )
	{
		echo "<img src=\"images/modules/$module_name/new_3.gif\" alt=\"" . _NEWLAST3DAYS . "\">";
	}
	if ( ($n_data > 259200) and ($n_data < 604800) )
	{
		echo "<img src=\"images/modules/$module_name/new_7.gif\" alt=\"" . _NEWTHISWEEK . "\">";
	}
}

/**
 * reitinggraphic()
 * 
 * @param mixed $votes
 * @param mixed $totalvotes
 * @return
 */
function reitinggraphic( $votes, $totalvotes )
{
	global $module_name;
	@$reiting = $totalvotes / $votes;
	$reiting = number_format( $reiting, 1 );
	if ( $reiting < 1 )
	{
		echo "$reiting/$votes<img src=\"images/modules/$module_name/stars-0.gif\">";
	}
	if ( $reiting >= 1 && $reiting < 2 )
	{
		echo "$reiting/$votes<img src=\"images/modules/$module_name/stars-1.gif\">";
	}
	if ( $reiting >= 2 && $reiting < 3 )
	{
		echo "$reiting/$votes<img src=\"images/modules/$module_name/stars-2.gif\">";
	}
	if ( $reiting >= 3 && $reiting < 4 )
	{
		echo "$reiting/$votes<img src=\"images/modules/$module_name/stars-3.gif\">";
	}
	if ( $reiting >= 4 && $reiting < 5 )
	{
		echo "$reiting/$votes<img src=\"images/modules/$module_name/stars-4.gif\">";
	}
	if ( $reiting >= 5 )
	{
		echo "$reiting/$votes<img src=\"images/modules/$module_name/stars-5.gif\">";
	}
}

/**
 * files_size()
 * 
 * @param mixed $f_size
 * @return
 */
function files_size( $f_size )
{
	$mb = 1024 * 1024;
	if ( $f_size > $mb )
	{
		$mysize = sprintf( "%01.2f", $f_size / $mb ) . " Mb";
	} elseif ( $f_size >= 1024 )
	{
		$mysize = sprintf( "%01.2f", $f_size / 1024 ) . " Kb";
	}
	else
	{
		$mysize = $f_size . " bytes";
	}
	return $mysize;
}


//The hien tren trang chinh
/**
 * mainfiles()
 * 
 * @return
 */
function mainfiles()
{
	global $adminfile, $newscatid, $numnews, $catngan, $choncatngan, $adminfold, $prefix, $db, $module_name, $bgcolor1, $bgcolor2, $fnote, $tabcolumn, $showsub, $filesperpage, $addfiles, $fhomemsg, $l;
	include ( "header.php" );
	files_head();
	// Begin trình bày tin của News - www.mangvn.org - 09-2008
	if ( $newscatid != 0 )
	{
		viewcatnews( $newscatid, $numnews );
	}
	else
	{
		echo "";
		//		$logomusics= "logo_music_".$currentlang.".gif";
		//		echo "<br><center><img border=\"0\" src=\"images/modules/$module_name/$logomusics\" width=\"285\" height=\"84\"></center><br>";
	}
	// End trình bày tin của News - www.mangvn.org - 09-2008
	OpenTable();
	@$tdwidth = round( 100 / intval($tabcolumn) );
	$sql = "SELECT cid, title, cdescription  FROM " . $prefix . "_files_categories WHERE parentid = '0'";
	$result = $db->sql_query( $sql );
	$cont = 0;
	$numt = $db->sql_numrows( $result );
	if ( $numt )
	{
		echo "<center><table border=\"0\" cellspacing=\"10\" cellpadding=\"10\"><tr><td valign=\"top\" width=\"" . $tdwidth . "%\">";
		while ( $row = $db->sql_fetchrow($result) )
		{
			$cid = $row['cid'];
			$title = $row['title'];
			$num_parentid = $db->sql_numrows( $db->sql_query("SELECT cid  FROM " . $prefix . "_files_categories WHERE parentid = '$cid'") );
			if ( $num_parentid > 0 )
			{
				$links = "cat";
			}
			else
			{
				$links = "showcat";
			}
			echo "<a href=modules.php?name=$module_name&go=$links&cid=$cid><img border=\"0\" src=\"images/modules/$module_name/folder.gif\"> <b>$title</b></a><br>";
			if ( $showsub == 1 )
			{
				$sql2 = "SELECT cid, title  FROM " . $prefix . "_files_categories WHERE parentid = '$cid'";
				$result2 = $db->sql_query( $sql2 );
				$num = $db->sql_numrows( $result2 );
				if ( $num > 0 )
				{
					$l == 0;
					while ( $row = $db->sql_fetchrow($result2) )
					{
						$s_cid = $row['cid'];
						$s_title = $row['title'];
						$num_parentid2 = $db->sql_numrows( $db->sql_query("SELECT cid  FROM " . $prefix . "_files_categories WHERE parentid = '$s_cid'") );
						if ( $num_parentid2 > 0 )
						{
							$links2 = "cat";
						}
						else
						{
							$links2 = "showcat";
						}
						if ( $l > 0 )
						{
							echo " ";
						}
						echo "|--<a href=" . INCLUDE_PATH . "modules.php?name=$module_name&go=$links2&cid=$s_cid><img border=\"0\" src=\"images/modules/$module_name/folder2.gif\"> $s_title</a><br>";
						$l++;
					}
				}
			}
			$cont++;

			if ( $cont < $tabcolumn )
			{
				echo "</td><td valign=\"top\" width=\"" . $tdwidth . "%\">";
			}
			if ( $cont == $tabcolumn )
			{
				echo "</td></tr><tr><td valign=\"top\" width=\"" . $tdwidth . "%\">";
				$cont = 0;
			}

		}

		echo "</td></tr></table></center><hr>";
	}
	$numf = $db->sql_fetchrow( $db->sql_query("SELECT COUNT(*) FROM " . $prefix . "_files WHERE cid = '0' AND status !='0'") );
	$page = ( isset($_GET['page']) ) ? intval( $_GET['page'] ) : 0;
	$all_page = ( $numf[0] ) ? $numf[0] : 1;
	$per_page = 10;
	$base_url = "modules.php?name=$module_name";
	$sql3 = "SELECT lid, title, description, UNIX_TIMESTAMP(date) as formatted, votes, totalvotes, totalcomments, hits  FROM " . $prefix . "_files WHERE cid = '0' AND status !='0' ORDER BY lid DESC LIMIT $page,$per_page";
	$result3 = $db->sql_query( $sql3 );
	if ( $numf[0] > 0 )
	{
		echo "<br><center>";
		while ( $row = $db->sql_fetchrow($result3) )
		{
			$lid = $row['lid'];
			$f_title = $row['title'];
			$description = $row['description'];
			$hits = $row['hits'];
			$comment = $row['totalcomments'];
			$f_data = $row['formatted'];
			$fp_data = viewtime( $row["formatted"], 2 );
			$votes = $row['votes'];
			$totalvotes = $row['totalvotes'];


			// cat ngan phan mo ta cho file by www.mangvn.org 06-01-2009
			if ( $choncatngan == 1 )
			{
				$description = stripslashes( check_html($description, nohtml) );
				$description = substr( $description, 0, $catngan );
				$phandoan = strrpos( $description, " " );
				$description = substr( $description, 0, $phandoan );
				$more = "...";
			}
			// het code cat ngan.


			echo "<table border=\"0\" width=\"100%\" cellpadding=\"3\" cellspacing=\"1\" bgcolor=\"$bgcolor2\"><tr>\n" . "<td bgcolor=\"$bgcolor1\" colspan=\"2\"><b><a href=modules.php?name=$module_name&go=view_file&lid=$lid>$f_title</a></b>";
			newfilesgraphic( $f_data );
			echo "</td><td bgcolor=\"$bgcolor1\" align=\"right\">$fp_data</td></tr>\n" . "<tr><td bgcolor=\"$bgcolor1\" colspan=\"3\">$description$more</td></tr><tr>\n" . "<td bgcolor=\"$bgcolor1\" width=\"33%\">" . _FILEHITS . " $hits</td>\n" . "<td bgcolor=\"$bgcolor1\" width=\"33%\">" . _FILECOMMENTS . " $comment</td><td bgcolor=\"$bgcolor1\" width=\"34%\">" . _FILEREITING . ": ";
			reitinggraphic( $votes, $totalvotes );
			echo "</td></tr>";
			if ( defined('IS_ADMMOD') )
			{
				echo "<tr><td bgcolor=\"$bgcolor1\" colspan=\"3\" align=\"center\">[ <a href=" . $adminfold . "/" . $adminfile . ".php?op=delit_file&lid=$lid>" . _FDELETE . "</a> | <a href=" . $adminfold . "/" . $adminfile . ".php?op=edit_files&lid=$lid>" . _FEDIT . "</a> ]</td></tr>";
			}
			echo "</table><br>";
		}
		echo @generate_page( $base_url, $all_page, $per_page, $page );
		echo "</center>";
	}
	$files_num = $db->sql_numrows( $db->sql_query("SELECT lid FROM " . $prefix . "_files  WHERE status!='0'") );
	$cat_num = $db->sql_numrows( $db->sql_query("SELECT * FROM " . $prefix . "_files_categories") );
	echo "<center><b>" . _ALLFILES . " $files_num " . _INF . " $cat_num " . _ALLFILES2 . "</b></center>";
	CloseTable();

	echo "\n<hr><table><tr><td>";

	// chèn Thư viện Box.net
	if ( file_exists("" . INCLUDE_PATH . "modules/$module_name/box.php") )
	{
		echo "<a href=\"" . INCLUDE_PATH . "modules.php?name=$module_name&amp;file=box\"><img src='" . INCLUDE_PATH . "images/modules/$module_name/folder.gif' border='0' align=\"middle\" hspace=\"10\" vspace=\"10\"></a></td><td>";
	}
	if ( defined('IS_ADMMOD') || ((defined('IS_USER')) and ($addfiles == 2)) || ($addfiles == 1) )
	{
		echo "<center>" . _ADDFILERECOMMENT . "</center>";
	}
	else
	{
		echo "<center><b><A href=\"" . INCLUDE_PATH . "modules.php?name=Your_Account&op=new_user\">" . _NEWREG . "</A></b> rồi <b><A href=\"" . INCLUDE_PATH . "modules.php?name=Your_Account\">" . _LOGIN . "</A></b> để chia sẻ file và có thể tải file nhanh hơn.</center>";
	}

	// Thể hiện một số file trên trang chủ
	//    files_foot();
	echo "</td></tr></table><br>";

	$numf = $db->sql_fetchrow( $db->sql_query("SELECT COUNT(*) FROM " . $prefix . "_files WHERE status !='0'") );
	$page = ( isset($_GET['page']) ) ? intval( $_GET['page'] ) : 0;
	$p = ( isset($_GET['p']) ) ? intval( $_GET['p'] ) : 0;
	$all_page = ( $numf[0] ) ? $numf[0] : 1;
	$per_page = 5;
	$base_url = "" . INCLUDE_PATH . "modules.php?name=$module_name&go=popular&p=$p";

	// Những file mới nhất - http://mangvn.org

	$sql2 = "SELECT lid, title, description, UNIX_TIMESTAMP(date) as formatted, votes, totalvotes, totalcomments, hits  FROM " . $prefix . "_files WHERE status !='0' ORDER BY formatted DESC LIMIT $page,$per_page";

	$result2 = $db->sql_query( $sql2 );
	if ( $numf[0] > 0 )
	{
		OpenTable();
		echo "<center><font size=\"3\"><b>" . _TOPNEWFILES . "</b></font></center>";
		echo "<hr><br><br><center>";
		while ( $row = $db->sql_fetchrow($result2) )
		{
			$lid = intval( $row['lid'] );
			$f_title = $row['title'];
			$description = $row['description'];
			$hits = intval( $row['hits'] );
			$comment = intval( $row['totalcomments'] );
			$f_data = $row['formatted'];
			$fp_data = viewtime( $row["formatted"], 2 );
			$votes = intval( $row['votes'] );
			$totalvotes = intval( $row['totalvotes'] );

			// cat ngan phan mo ta cho file by www.mangvn.org 06-01-2009
			if ( $choncatngan == 1 )
			{
				$description = stripslashes( check_html($description, nohtml) );
				$description = substr( $description, 0, $catngan );
				$phandoan = strrpos( $description, " " );
				$description = substr( $description, 0, $phandoan );
				$more = "...";
			}
			// het code cat ngan.

			echo "<table border=\"0\" width=\"100%\" cellpadding=\"3\" cellspacing=\"1\" bgcolor=\"$bgcolor2\"><tr>\n" . "<td bgcolor=\"$bgcolor1\" colspan=\"2\"><p class=storytitle><a href=" . INCLUDE_PATH . "modules.php?name=$module_name&go=view_file&lid=$lid class=\"storytitle\">$f_title</a>";
			newfilesgraphic( $f_data );
			echo "</p></td><td bgcolor=\"$bgcolor1\" align=\"right\">$fp_data</td></tr>\n" . "<tr><td bgcolor=\"$bgcolor1\" colspan=\"3\" style=\"text-align: justify\"><font class=\"indexhometext\" align=\"justify\">$description$more</font></td></tr><tr>\n" . "<td bgcolor=\"$bgcolor1\" width=\"33%\">" . _FILEHITS . " $hits</td>\n" . "<td bgcolor=\"$bgcolor1\" width=\"33%\">" . _FILECOMMENTS . " $comment</td><td bgcolor=\"$bgcolor1\" width=\"34%\">" . _FILEREITING . ": ";
			reitinggraphic( $votes, $totalvotes );
			echo "</td></tr>";
			if ( defined('IS_ADMMOD') )
			{
				echo "<tr><td bgcolor=\"$bgcolor1\" colspan=\"3\" align=\"center\">[ <a href=" . $adminfold . "/" . $adminfile . ".php?op=delit_file&lid=$lid>" . _FDELETE . "</a> | <a href=" . $adminfold . "/" . $adminfile . ".php?op=edit_files&lid=$lid>" . _FEDIT . "</a> ]</td></tr>";
			}
			echo "</table><br>";
		}
		echo "</center><hr>";
		echo "<center>[ <a href=\"" . INCLUDE_PATH . "modules.php?name=$module_name&go=popular&p=1\">" . _MOREFILE . "</a> ]</center>";
		CloseTable();
		echo "<br><br>";
	}

	/*

	* // Nhung file duoc nhieu nguoi tai nhat - http://mangvn.org 

	* $sql2 = "SELECT lid, title, description, UNIX_TIMESTAMP(date) as formatted, votes, totalvotes, totalcomments, hits  FROM ".$prefix."_files WHERE status !='0' ORDER BY hits DESC LIMIT $page,$per_page"; 


	* $result2 = $db->sql_query($sql2);
	* if ($numf[0] > 0) {
	* OpenTable();
	* echo "<center><font size=\"3\"><b>"._TOPDOWN."</b></font></center>";
	* echo "<hr><br><br><center>";
	* while ($row = $db->sql_fetchrow($result2)) {
	* $lid = intval($row['lid']);
	* $f_title = $row['title'];
	* $description = $row['description'];
	* $hits = intval($row['hits']);
	* $comment = intval($row['totalcomments']);
	* $f_data = $row['formatted'];
	* $fp_data = viewtime($row["formatted"],2);
	* $votes = intval($row['votes']);
	* $totalvotes = intval($row['totalvotes']);
	* 
	* // cat ngan phan mo ta cho file by www.mangvn.org 06-01-2009
	* if ($choncatngan == 1) {
	* $description = stripslashes(check_html($description, nohtml));
	* $description = substr($description, 0, $catngan);
	* $phandoan = strrpos($description, " ");
	* $description = substr($description, 0, $phandoan);
	* $more = "...";
	* }
	* // het code cat ngan.

	* echo "<table border=\"0\" width=\"100%\" cellpadding=\"3\" cellspacing=\"1\" bgcolor=\"$bgcolor2\"><tr>\n"
	* ."<td bgcolor=\"$bgcolor1\" colspan=\"2\"><p class=storytitle><a href=modules.php?name=$module_name&go=view_file&lid=$lid class=\"storytitle\">$f_title</a>";
	* newfilesgraphic($f_data);
	* echo "</p></td><td bgcolor=\"$bgcolor1\" align=\"right\">$fp_data</td></tr>\n"
	* ."<tr><td bgcolor=\"$bgcolor1\" colspan=\"3\" style=\"text-align: justify\"><font class=\"indexhometext\" align=\"justify\">$description$more</font></td></tr><tr>\n"
	* ."<td bgcolor=\"$bgcolor1\" width=\"33%\">"._FILEHITS." $hits</td>\n"
	* ."<td bgcolor=\"$bgcolor1\" width=\"33%\">"._FILECOMMENTS." $comment</td><td bgcolor=\"$bgcolor1\" width=\"34%\">"._FILEREITING.": ";
	* reitinggraphic($votes, $totalvotes);
	* echo "</td></tr>";
	* if (defined('IS_ADMMOD')) {
	* echo "<tr><td bgcolor=\"$bgcolor1\" colspan=\"3\" align=\"center\">[ <a href=".$adminfold."/".$adminfile.".php?op=delit_file&lid=$lid>"._FDELETE."</a> | <a href=".$adminfold."/".$adminfile.".php?op=edit_files&lid=$lid>"._FEDIT."</a> ]</td></tr>";
	* }
	* echo "</table><br>";
	* }
	* echo "</center><hr>";
	* }
	* echo "<center>[ <a href=\"modules.php?name=$module_name&go=popular&p=2\">"._MOREFILE."</a> ]</center>";
	* CloseTable();
	* 
	*/

	// Những file được nhiều bình chọn nhất (votes) | ngẫu nhiên (rand) - http://mangvn.org
	//	$sql2 = "SELECT lid, title, description, UNIX_TIMESTAMP(date) as formatted, votes, totalvotes, totalcomments, hits  FROM ".$prefix."_files WHERE status !='0' ORDER BY votes DESC LIMIT $page,$per_page";

	// Những file hiển thị ngẫu nhiên (rand) - http://mangvn.org
	$sql2 = "SELECT lid, title, description, UNIX_TIMESTAMP(date) as formatted, votes, totalvotes, totalcomments, hits  FROM " . $prefix . "_files WHERE status !='0' ORDER BY RAND() LIMIT $page,$per_page";
	$result2 = $db->sql_query( $sql2 );
	if ( $numf[0] > 0 )
	{
		OpenTable();
		echo "<center><font size=\"3\"><b>" . _FILESRAND . "</b></font></center>";
		echo "<hr><br><br><center>";
		while ( $row = $db->sql_fetchrow($result2) )
		{
			$lid = intval( $row['lid'] );
			$f_title = $row['title'];
			$description = $row['description'];
			$hits = intval( $row['hits'] );
			$comment = intval( $row['totalcomments'] );
			$f_data = $row['formatted'];
			$fp_data = viewtime( $row["formatted"], 2 );
			$votes = intval( $row['votes'] );
			$totalvotes = intval( $row['totalvotes'] );

			// cat ngan phan mo ta cho file by www.mangvn.org 06-01-2009
			if ( $choncatngan == 1 )
			{
				$description = stripslashes( check_html($description, nohtml) );
				$description = substr( $description, 0, $catngan );
				$phandoan = strrpos( $description, " " );
				$description = substr( $description, 0, $phandoan );
				$more = "...";
			}
			// het code cat ngan.

			echo "<table border=\"0\" width=\"100%\" cellpadding=\"3\" cellspacing=\"1\" bgcolor=\"$bgcolor2\"><tr>\n" . "<td bgcolor=\"$bgcolor1\" colspan=\"2\"><p class=storytitle><a href=modules.php?name=$module_name&go=view_file&lid=$lid class=\"storytitle\">$f_title</a>";
			newfilesgraphic( $f_data );
			echo "</p></td><td bgcolor=\"$bgcolor1\" align=\"right\">$fp_data</td></tr>\n" . "<tr><td bgcolor=\"$bgcolor1\" colspan=\"3\" style=\"text-align: justify\"><font class=\"indexhometext\" align=\"justify\">$description$more</font></td></tr><tr>\n" . "<td bgcolor=\"$bgcolor1\" width=\"33%\">" . _FILEHITS . " $hits</td>\n" . "<td bgcolor=\"$bgcolor1\" width=\"33%\">" . _FILECOMMENTS . " $comment</td><td bgcolor=\"$bgcolor1\" width=\"34%\">" . _FILEREITING . ": ";
			reitinggraphic( $votes, $totalvotes );
			echo "</td></tr>";
			if ( defined('IS_ADMMOD') )
			{
				echo "<tr><td bgcolor=\"$bgcolor1\" colspan=\"3\" align=\"center\">[ <a href=" . $adminfold . "/" . $adminfile . ".php?op=delit_file&lid=$lid>" . _FDELETE . "</a> | <a href=" . $adminfold . "/" . $adminfile . ".php?op=edit_files&lid=$lid>" . _FEDIT . "</a> ]</td></tr>";
			}
			echo "</table><br>";
		}
		echo "</center><hr>";
		echo "<center>[ <a href=\"modules.php?name=$module_name&go=popular&p=3\">" . _MOREFILE . "</a> ]</center>";
		CloseTable();
	}

	// End

	echo "<br>";

	//    files_foot();
	include ( "footer.php" );
}
//The hien muc chinh
/**
 * cat()
 * 
 * @return
 */
function cat()
{
	global $adminfold, $adminfile, $catngan, $choncatngan, $prefix, $db, $module_name, $bgcolor1, $bgcolor2, $tabcolumn, $showsub, $filesperpage;
	$cid = intval( $_GET['cid'] );
	if ( ! isset($cid) or empty($cid) )
	{
		Header( "Location: modules.php?name=$module_name" );
		exit();
	}
	if ( $db->sql_numrows($db->sql_query("SELECT * FROM " . $prefix . "_files_categories WHERE cid='$cid'")) != 1 )
	{
		Header( "Location: modules.php?name=$module_name" );
		exit();
	}
	include ( "header.php" );
	files_head();
	OpenTable();
	$numf = $db->sql_fetchrow( $db->sql_query("SELECT COUNT(*) FROM " . $prefix . "_files WHERE cid = '$cid' AND status !='0'") );
	$page = ( isset($_GET['page']) ) ? intval( $_GET['page'] ) : 0;
	$p = ( isset($_GET['p']) ) ? intval( $_GET['p'] ) : 0;
	$all_page = ( $numf[0] ) ? $numf[0] : 1;
	$per_page = $filesperpage;
	$base_url = "modules.php?name=$module_name&go=cat&cid=$cid&p=$p";
	if ( $p == 1 )
	{
		$sql3 = "SELECT lid, title, description, UNIX_TIMESTAMP(date) as formatted, votes, totalvotes, totalcomments, hits  FROM " . $prefix . "_files WHERE cid = '$cid' AND status !='0' ORDER BY formatted ASC LIMIT $page,$per_page";
	}
	else
		if ( $p == 2 )
		{
			$sql3 = "SELECT lid, title, description, UNIX_TIMESTAMP(date) as formatted, votes, totalvotes, totalcomments, hits  FROM " . $prefix . "_files WHERE cid = '$cid' AND status !='0' ORDER BY title ASC LIMIT $page,$per_page";
		}
		else
			if ( $p == 3 )
			{
				$sql3 = "SELECT lid, title, description, UNIX_TIMESTAMP(date) as formatted, votes, totalvotes, totalcomments, hits  FROM " . $prefix . "_files WHERE cid = '$cid' AND status !='0' ORDER BY title DESC LIMIT $page,$per_page";
			}
			else
				if ( $p == 4 )
				{
					$sql3 = "SELECT lid, title, description, UNIX_TIMESTAMP(date) as formatted, votes, totalvotes, totalcomments, hits  FROM " . $prefix . "_files WHERE cid = '$cid' AND status !='0' ORDER BY hits DESC LIMIT $page,$per_page";
				}
				else
					if ( $p == 5 )
					{
						$sql3 = "SELECT lid, title, description, UNIX_TIMESTAMP(date) as formatted, votes, totalvotes, totalcomments, hits  FROM " . $prefix . "_files WHERE cid = '$cid' AND status !='0' ORDER BY hits ASC LIMIT $page,$per_page";
					}
					else
						if ( $p == 6 )
						{
							$sql3 = "SELECT lid, title, description, UNIX_TIMESTAMP(date) as formatted, votes, totalvotes, totalcomments, hits  FROM " . $prefix . "_files WHERE cid = '$cid' AND status !='0' ORDER BY totalvotes DESC LIMIT $page,$per_page";
						}
						else
							if ( $p == 7 )
							{
								$sql3 = "SELECT lid, title, description, UNIX_TIMESTAMP(date) as formatted, votes, totalvotes, totalcomments, hits  FROM " . $prefix . "_files WHERE cid = '$cid' AND status !='0' ORDER BY totalvotes ASC LIMIT $page,$per_page";
							}
							else
							{
								$sql3 = "SELECT lid, title, description, UNIX_TIMESTAMP(date) as formatted, votes, totalvotes, totalcomments, hits  FROM " . $prefix . "_files WHERE cid = '$cid' AND status !='0' ORDER BY lid DESC LIMIT $page,$per_page";
							}

							$sql = "SELECT cid, title, cdescription  FROM " . $prefix . "_files_categories WHERE parentid='$cid'";
	$result = $db->sql_query( $sql );
	$cont = 0;
	@$tdwidth = round( 100 / $tabcolumn );
	echo "<table border=\"0\" cellspacing=\"10\" cellpadding=\"10\" width=\"100%\"><tr><td valign=\"top\" width=\"" . $tdwidth . "%\">";
	while ( $row = $db->sql_fetchrow($result) )
	{
		$c_cid = intval( $row['cid'] );
		$c_title = $row['title'];
		$c_cdescription = $row['cdescription'];
		$num_parentid = $db->sql_numrows( $db->sql_query("SELECT cid  FROM " . $prefix . "_files_categories WHERE parentid = '$c_cid'") );
		if ( $num_parentid > 0 )
		{
			$links = "cat";
		}
		else
		{
			$links = "showcat";
		}
		echo "<a href=modules.php?name=$module_name&go=$links&cid=$c_cid><img border=\"0\" src=\"images/modules/$module_name/folder.gif\"> <b>$c_title</b></a><br>";
		if ( $showsub == 1 )
		{
			$sql2 = "SELECT cid, title  FROM " . $prefix . "_files_categories WHERE parentid = '$c_cid'";
			$result2 = $db->sql_query( $sql2 );
			$num = $db->sql_numrows( $result2 );
			if ( $num > 0 )
			{
				$l == 0;
				while ( $row = $db->sql_fetchrow($result2) )
				{
					$s_cid = $row['cid'];
					$s_title = $row['title'];
					$num_parentid2 = $db->sql_numrows( $db->sql_query("SELECT cid  FROM " . $prefix . "_files_categories WHERE parentid = '$s_cid'") );
					if ( $num_parentid2 > 0 )
					{
						$links2 = "cat";
					}
					else
					{
						$links2 = "showcat";
					}
					if ( $l > 0 )
					{
						echo " ";
					}
					echo "<a href=modules.php?name=$module_name&go=$links2&cid=$s_cid>|--<img border=\"0\" src=\"images/modules/$module_name/folder2.gif\"> $s_title</a><br>";
					$l++;
				}
			}
		}
		$cont++;

		if ( $cont < $tabcolumn )
		{
			echo "</td><td valign=\"top\" width=\"" . $tdwidth . "%\">";
		}
		if ( $cont == $tabcolumn )
		{
			echo "</td></tr><tr><td valign=\"top\" width=\"" . $tdwidth . "%\">";
			$cont = 0;
		}

	}
	echo "</td></tr></table></center>";

	$result3 = $db->sql_query( $sql3 );
	if ( $numf[0] > 0 )
	{
		while ( $row = $db->sql_fetchrow($result3) )
		{
			$lid = intval( $row['lid'] );
			$f_title = $row['title'];
			$description = $row['description'];
			$hits = intval( $row['hits'] );
			$comment = $row['totalcomments'];
			$f_data = $row['formatted'];
			$fp_data = viewtime( $row["formatted"], 2 );
			$votes = intval( $row['votes'] );
			$totalvotes = intval( $row['totalvotes'] );

			// cat ngan phan mo ta cho file by www.mangvn.org 06-01-2009
			if ( $choncatngan == 1 )
			{
				$description = stripslashes( check_html($description, nohtml) );
				$description = substr( $description, 0, $catngan );
				$phandoan = strrpos( $description, " " );
				$description = substr( $description, 0, $phandoan );
				$more = "...";
			}
			// het code cat ngan.

			echo "<table border=\"0\" width=\"100%\" cellpadding=\"3\" cellspacing=\"1\" bgcolor=\"$bgcolor2\"><tr>\n" . "<td bgcolor=\"$bgcolor1\" colspan=\"2\"><b><a href=modules.php?name=$module_name&go=view_file&lid=$lid>$f_title</a></b>";
			newfilesgraphic( $f_data );
			echo "</td><td bgcolor=\"$bgcolor1\" align=\"right\">$fp_data</td></tr>\n" . "<tr><td bgcolor=\"$bgcolor1\" colspan=\"3\">$description$more</td></tr><tr>\n" . "<td bgcolor=\"$bgcolor1\" width=\"33%\">" . _FILEHITS . " $hits</td>\n" . "<td bgcolor=\"$bgcolor1\" width=\"33%\">" . _FILECOMMENTS . " $comment</td><td bgcolor=\"$bgcolor1\" width=\"34%\">" . _FILEREITING . ": ";
			reitinggraphic( $votes, $totalvotes );
			echo "</td></tr>";
			if ( defined('IS_ADMMOD') )
			{
				echo "<tr><td bgcolor=\"$bgcolor1\" colspan=\"3\" align=\"center\">[ <a href=" . $adminfold . "/" . $adminfile . ".php?op=delit_file&lid=$lid>" . _FDELETE . "</a> | <a href=" . $adminfold . "/" . $adminfile . ".php?op=edit_files&lid=$lid>" . _FEDIT . "</a> ]</td></tr>";
			}
			echo "</table><br>";
		}
		echo @generate_page( $base_url, $all_page, $per_page, $page );
		echo "</center><hr>";
	}
	echo "<center>[ <a href=\"javascript:history.go(-1)\">" . _PBACK . "</a> | <a href=\"modules.php?name=$module_name\">" . _PHOME . "</a> ]</center>";
	CloseTable();
	echo "<br>";
	files_foot();
	include ( "footer.php" );
}

//The hien sub cat
/**
 * showcat()
 * 
 * @return
 */
function showcat()
{
	global $adminfold, $adminfile, $catngan, $choncatngan, $prefix, $db, $module_name, $bgcolor1, $bgcolor2, $filesperpage;
	$cid = intval( $_GET['cid'] );
	if ( ! isset($cid) or empty($cid) )
	{
		Header( "Location: modules.php?name=$module_name" );
		exit();
	}
	if ( $db->sql_numrows($db->sql_query("SELECT * FROM " . $prefix . "_files_categories WHERE cid='$cid'")) != 1 )
	{
		Header( "Location: modules.php?name=$module_name" );
		exit();
	}
	include ( "header.php" );
	files_head();
	OpenTable();
	$numf = $db->sql_fetchrow( $db->sql_query("SELECT COUNT(*) FROM " . $prefix . "_files WHERE cid = '$cid' AND status !='0'") );
	$page = ( isset($_GET['page']) ) ? intval( $_GET['page'] ) : 0;
	$p = ( isset($_GET['p']) ) ? intval( $_GET['p'] ) : 0;
	$all_page = ( $numf[0] ) ? $numf[0] : 1;
	$per_page = $filesperpage;
	$base_url = "modules.php?name=$module_name&go=showcat&cid=$cid&p=$p";
	if ( $p == 1 )
	{
		$sql2 = "SELECT lid, title, description, UNIX_TIMESTAMP(date) as formatted, votes, totalvotes, totalcomments, hits  FROM " . $prefix . "_files WHERE cid = '$cid' AND status !='0' ORDER BY formatted ASC LIMIT $page,$per_page";
	}
	else
		if ( $p == 2 )
		{
			$sql2 = "SELECT lid, title, description, UNIX_TIMESTAMP(date) as formatted, votes, totalvotes, totalcomments, hits  FROM " . $prefix . "_files WHERE cid = '$cid' AND status !='0' ORDER BY title ASC LIMIT $page,$per_page";
		}
		else
			if ( $p == 3 )
			{
				$sql2 = "SELECT lid, title, description, UNIX_TIMESTAMP(date) as formatted, votes, totalvotes, totalcomments, hits  FROM " . $prefix . "_files WHERE cid = '$cid' AND status !='0' ORDER BY title DESC LIMIT $page,$per_page";
			}
			else
				if ( $p == 4 )
				{
					$sql2 = "SELECT lid, title, description, UNIX_TIMESTAMP(date) as formatted, votes, totalvotes, totalcomments, hits  FROM " . $prefix . "_files WHERE cid = '$cid' AND status !='0' ORDER BY hits DESC LIMIT $page,$per_page";
				}
				else
					if ( $p == 5 )
					{
						$sql2 = "SELECT lid, title, description, UNIX_TIMESTAMP(date) as formatted, votes, totalvotes, totalcomments, hits  FROM " . $prefix . "_files WHERE cid = '$cid' AND status !='0' ORDER BY hits ASC LIMIT $page,$per_page";
					}
					else
						if ( $p == 6 )
						{
							$sql2 = "SELECT lid, title, description, UNIX_TIMESTAMP(date) as formatted, votes, totalvotes, totalcomments, hits  FROM " . $prefix . "_files WHERE cid = '$cid' AND status !='0' ORDER BY totalvotes DESC LIMIT $page,$per_page";
						}
						else
							if ( $p == 7 )
							{
								$sql2 = "SELECT lid, title, description, UNIX_TIMESTAMP(date) as formatted, votes, totalvotes, totalcomments, hits  FROM " . $prefix . "_files WHERE cid = '$cid' AND status !='0' ORDER BY totalvotes ASC LIMIT $page,$per_page";
							}
							else
							{
								$sql2 = "SELECT lid, title, description, UNIX_TIMESTAMP(date) as formatted, votes, totalvotes, totalcomments, hits  FROM " . $prefix . "_files WHERE cid = '$cid' AND status !='0' ORDER BY formatted DESC LIMIT $page,$per_page";
							}

							$result2 = $db->sql_query( $sql2 );
	if ( $numf[0] > 0 )
	{
		echo "<center>";
		while ( $row = $db->sql_fetchrow($result2) )
		{
			$lid = intval( $row['lid'] );
			$f_title = $row['title'];
			$description = $row['description'];
			$hits = intval( $row['hits'] );
			$comment = intval( $row['totalcomments'] );
			$f_data = $row['formatted'];
			$fp_data = viewtime( $row["formatted"], 2 );
			$votes = intval( $row['votes'] );
			$totalvotes = intval( $row['totalvotes'] );

			// cat ngan phan mo ta cho file by www.mangvn.org 06-01-2009
			if ( $choncatngan == 1 )
			{
				$description = stripslashes( check_html($description, nohtml) );
				$description = substr( $description, 0, $catngan );
				$phandoan = strrpos( $description, " " );
				$description = substr( $description, 0, $phandoan );
				$more = "...";
			}
			// het code cat ngan.


			echo "<table border=\"0\" width=\"100%\" cellpadding=\"3\" cellspacing=\"1\" bgcolor=\"$bgcolor2\"><tr>\n" . "<td bgcolor=\"$bgcolor1\" colspan=\"2\"><p class=storytitle><a href=modules.php?name=$module_name&go=view_file&lid=$lid class=\"storytitle\">$f_title</a>";
			newfilesgraphic( $f_data );
			echo "</p></td><td bgcolor=\"$bgcolor1\" align=\"right\">$fp_data</td></tr>\n" . "<tr><td bgcolor=\"$bgcolor1\" colspan=\"3\" style=\"text-align: justify\"><font class=\"indexhometext\" align=\"justify\">$description$more</font></td></tr><tr>\n" . "<td bgcolor=\"$bgcolor1\" width=\"33%\">" . _FILEHITS . " $hits</td>\n" . "<td bgcolor=\"$bgcolor1\" width=\"33%\">" . _FILECOMMENTS . " $comment</td><td bgcolor=\"$bgcolor1\" width=\"34%\">" . _FILEREITING . ": ";
			reitinggraphic( $votes, $totalvotes );
			echo "</td></tr>";
			if ( defined('IS_ADMMOD') )
			{
				echo "<tr><td bgcolor=\"$bgcolor1\" colspan=\"3\" align=\"center\">[ <a href=" . $adminfold . "/" . $adminfile . ".php?op=delit_file&lid=$lid>" . _FDELETE . "</a> | <a href=" . $adminfold . "/" . $adminfile . ".php?op=edit_files&lid=$lid>" . _FEDIT . "</a> ]</td></tr>";
			}
			echo "</table><br>";
		}
		echo @generate_page( $base_url, $all_page, $per_page, $page );
		echo "</center><hr>";
	}
	echo "<center>[ <a href=\"javascript:history.go(-1)\">" . _PBACK . "</a> | <a href=\"modules.php?name=$module_name\">" . _PHOME . "</a> ]</center>";
	CloseTable();

	files_foot();
	include ( "footer.php" );
}

//Pho bien
/**
 * popular()
 * 
 * @return
 */
function popular()
{
	global $adminfold, $adminfile, $catngan, $choncatngan, $prefix, $db, $module_name, $bgcolor1, $bgcolor2, $filesperpage;
	include ( "header.php" );
	files_head();
	OpenTable();
	$numf = $db->sql_fetchrow( $db->sql_query("SELECT COUNT(*) FROM " . $prefix . "_files WHERE status !='0'") );
	$page = ( isset($_GET['page']) ) ? intval( $_GET['page'] ) : 0;
	$p = ( isset($_GET['p']) ) ? intval( $_GET['p'] ) : 0;
	$all_page = ( $numf[0] ) ? $numf[0] : 1;
	$per_page = 10;
	$base_url = "modules.php?name=$module_name&go=popular&p=$p";
	if ( $p == 2 )
	{
		echo "<center><font size=\"3\"><b>" . _TOPDOWN . "</b></font></center>";
		$sql2 = "SELECT lid, title, description, UNIX_TIMESTAMP(date) as formatted, votes, totalvotes, totalcomments, hits  FROM " . $prefix . "_files WHERE status !='0' ORDER BY hits DESC LIMIT $page,$per_page";
	}
	else
		if ( $p == 3 )
		{
			echo "<center><font size=\"3\"><b>" . _TOPVOTE . "</b></font></center>";
			$sql2 = "SELECT lid, title, description, UNIX_TIMESTAMP(date) as formatted, votes, totalvotes, totalcomments, hits  FROM " . $prefix . "_files WHERE status !='0' ORDER BY votes DESC LIMIT $page,$per_page";
		}
		else
		{
			echo "<center><font size=\"3\"><b>" . _TOPNEWFILES . "</b></font></center>";
			$sql2 = "SELECT lid, title, description, UNIX_TIMESTAMP(date) as formatted, votes, totalvotes, totalcomments, hits  FROM " . $prefix . "_files WHERE status !='0' ORDER BY formatted DESC LIMIT $page,$per_page";
		}

		$result2 = $db->sql_query( $sql2 );
	if ( $numf[0] > 0 )
	{
		echo "<hr><br><br><center>";
		while ( $row = $db->sql_fetchrow($result2) )
		{
			$lid = intval( $row['lid'] );
			$f_title = $row['title'];
			$description = $row['description'];
			$hits = intval( $row['hits'] );
			$comment = intval( $row['totalcomments'] );
			$f_data = $row['formatted'];
			$fp_data = viewtime( $row["formatted"], 2 );
			$votes = intval( $row['votes'] );
			$totalvotes = intval( $row['totalvotes'] );

			// cat ngan phan mo ta cho file by www.mangvn.org 06-01-2009
			if ( $choncatngan == 1 )
			{
				$description = stripslashes( check_html($description, nohtml) );
				$description = substr( $description, 0, $catngan );
				$phandoan = strrpos( $description, " " );
				$description = substr( $description, 0, $phandoan );
				$more = "...";
			}
			// het code cat ngan.

			echo "<table border=\"0\" width=\"100%\" cellpadding=\"3\" cellspacing=\"1\" bgcolor=\"$bgcolor2\"><tr>\n" . "<td bgcolor=\"$bgcolor1\" colspan=\"2\"><p class=storytitle><a href=modules.php?name=$module_name&go=view_file&lid=$lid class=\"storytitle\">$f_title</a>";
			newfilesgraphic( $f_data );
			echo "</p></td><td bgcolor=\"$bgcolor1\" align=\"right\">$fp_data</td></tr>\n" . "<tr><td bgcolor=\"$bgcolor1\" colspan=\"3\" style=\"text-align: justify\"><font class=\"indexhometext\" align=\"justify\">$description$more</font></td></tr><tr>\n" . "<td bgcolor=\"$bgcolor1\" width=\"33%\">" . _FILEHITS . " $hits</td>\n" . "<td bgcolor=\"$bgcolor1\" width=\"33%\">" . _FILECOMMENTS . " $comment</td><td bgcolor=\"$bgcolor1\" width=\"34%\">" . _FILEREITING . ": ";
			reitinggraphic( $votes, $totalvotes );
			echo "</td></tr>";
			if ( defined('IS_ADMMOD') )
			{
				echo "<tr><td bgcolor=\"$bgcolor1\" colspan=\"3\" align=\"center\">[ <a href=" . $adminfold . "/" . $adminfile . ".php?op=delit_file&lid=$lid>" . _FDELETE . "</a> | <a href=" . $adminfold . "/" . $adminfile . ".php?op=edit_files&lid=$lid>" . _FEDIT . "</a> ]</td></tr>";
			}
			echo "</table><br>";
		}
		echo @generate_page( $base_url, $all_page, $per_page, $page );
		echo "</center><hr>";
	}
	echo "<center>[ <a href=\"javascript:history.go(-1)\">" . _PBACK . "</a> | <a href=\"modules.php?name=$module_name\">" . _PHOME . "</a> ]</center>";
	CloseTable();
	files_foot();
	include ( "footer.php" );
}

//Xem file
/**
 * view_file()
 * 
 * @return
 */
function view_file()
{
	global $anonpost, $datafold, $adminfold, $adminfile, $header_page_keyword, $key_words, $page_title, $page_title2, $prefix, $db, $module_name, $bgcolor1, $bgcolor2, $download, $addcomments, $filesvote, $brokewarning, $fchecknum, $nukeurl;
	$lid = intval( $_GET['lid'] );
	if ( ! isset($lid) )
	{
		Header( "Location: modules.php?name=$module_name" );
		exit();
	}
	if ( $db->sql_numrows($db->sql_query("select lid FROM " . $prefix . "_files where lid='$lid' AND status!='0'")) != 1 )
	{
		Header( "Location: modules.php?name=$module_name" );
		die();
	}
	include ( "header.php" );
	files_head();
	OpenTable();
	$sql = "SELECT cid, title, description, UNIX_TIMESTAMP(date) as formatted, filesize, version, name, email, homepage, votes, totalvotes, totalcomments, hits  FROM " . $prefix . "_files WHERE lid='$lid' AND status!=0";
	$result = $db->sql_query( $sql );
	$row = $db->sql_fetchrow( $result );
	$cid = intval( $row['cid'] );
	$title = $row['title'];
	//    $page_title = $title;
	$description = $row['description'];
	$header_page_keyword = $key_words = "$title";
	if ( $description != "" ) $header_page_keyword = $key_words = "$title $description";
	$f_data = $row['formatted'];
	$fp_data = viewtime( $f_data, 2 );
	$f_size = intval( $row['filesize'] );
	$version = $row['version'];
	$a_name = $row['name'];
	$a_email = $row['email'];
	$a_homepage = $row['homepage'];
	$votes = intval( $row['votes'] );
	$totalvotes = intval( $row['totalvotes'] );
	$totalcomments = intval( $row['totalcomments'] );
	$hits = intval( $row['hits'] );


	echo "<table border=\"0\" width=\"100%\" cellpadding=\"5\" cellspacing=\"0\">" . "<tr><td align=\"center\" class=\"indexhometext\"><h3>" . _VIEWFILE . "</h3><b>" . $title . "</b></td></tr><tr><td><hr></td></tr>";
	if ( $a_name != "" )
	{
		echo "<tr><td><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td>";
		echo "<b>" . _FILEAUTOR . ":</b>&nbsp;</td><td>$a_name&nbsp;";
		echo "</td>\n" . "<td>";
		if ( $a_email != "" )
		{
			echo "<a href=\"mailto:" . $a_email . "\"><img border=\"0\" src=\"images/modules/Files/email.gif\" width=\"16\" height=\"16\"></a>";
		}
		else
		{
			echo "&nbsp;";
		}
		echo "</td>\n" . "<td>";
		if ( $a_homepage != "" )
		{
			echo "<a href=\"" . $a_homepage . "\" target=\"_blank\"><img border=\"0\" src=\"images/modules/Files/www.gif\" width=\"16\" height=\"16\"></a>";
		}
		else
		{
			echo "&nbsp;";
		}
		echo "</td></tr></table>\n</td></tr>\n";
	}
	echo "<tr><td><b>" . _ADDDATE . "</b> $fp_data</td></tr>\n";
	if ( $version != "" ) echo "<tr><td><b>" . _FILEVERS . "</b>&nbsp;$version</td></tr>";
	echo "<tr><td><b>" . _FILESIZ . "</b>&nbsp;" . files_size( $f_size ) . "</td><tr><tr><td><b>" . _FILEREITING . "</b>&nbsp;";
	reitinggraphic( $votes, $totalvotes );
	echo "</td></tr><tr><td><b>" . _FILEHITS . "</b> $hits</td></tr>\n";
	if ( $description != "" ) echo "<tr><td><b>" . _FILESUBSCR . "</b><br>$description</td></tr>\n";

	echo "<tr><td><hr>\n";
	// add Send to Yahoo - www.mangvn.org - 10-01-2009
	echo "<a href=\"ymsgr:im?+&amp;msg=" . _SENDFILE1 . " '" . $title . "' " . _SENDFILE2 . ": " . $nukeurl . "modules%2Ephp%3Fname%3D$module_name%26go%3Dview_file%26lid%3D$lid%20\" onMouseOut=\"window.status=''; return true;\" onMouseOver=\"window.status='" . _SENDFILE3 . "'; return true;\"><img src=\"images/send_ym.gif\" border=\"0\" alt=\"" . _SENDFILE4 . "\" title=\"" . _SENDFILE3 . "\"></a>\n";
	echo "<!-- AddThis Button BEGIN -->\n" . "<script type=\"text/javascript\">addthis_pub  = 'mangvn';</script>\n" . "<a href=\"http://www.addthis.com/bookmark.php\" onmouseover=\"return addthis_open(this, '', '[URL]', '[TITLE]')\" onmouseout=\"addthis_close()\" onclick=\"return addthis_sendto()\"><img src=\"".INCLUDE_PATH."images/button1-bm.gif\" width=\"125\" height=\"16\" border=\"0\" alt=\"\" /></a><script type=\"text/javascript\" src=\"http://s7.addthis.com/js/152/addthis_widget.js\"></script>\n" . "<!-- AddThis Button END -->\n";
	echo "</td></tr>\n";
	if ( ((defined('IS_USER')) and ($filesvote == 2)) || ($filesvote == 1) )
	{
		echo "<tr><td><b>" . _RATEFILE . "</b>: <form method=\"POST\" action=\"modules.php?name=$module_name\" style=\"display: inline\">" . "<input type=\"hidden\" name=\"go\" value=\"pool\">" . "<input type=\"hidden\" name=\"lid\" value=\"$lid\">" . "<select name=\"send_reiting\">" . "<option selected value=\"5\">" . _RATE5 . "</option>" . "<option value=\"4\">" . _RATE4 . "</option>" . "<option value=\"3\">" . _RATE3 . "</option>" . "<option value=\"2\">" . _RATE2 . "</option>" . "<option value=\"1\">" . _RATE1 . "</option></select>&nbsp;" . "<input type=\"submit\" value=\"" . _RATEFILE . "\"></form></td></tr>";
	}
	if ( ($brokewarning == 1) || ((defined('IS_USER')) and ($brokewarning == 2)) )
	{
		echo "<tr><td>" . _BROCFILE1 . " <a href=modules.php?name=$module_name&go=broken&lid=$lid>" . _BROCFILE . "</a></td></tr>";
	}


	if ( defined('IS_ADMMOD') )
	{
		echo "<tr><td>" . _ADMINFILE . " [ <a href=" . $adminfold . "/" . $adminfile . ".php?op=delit_file&lid=$lid>" . _FDELETE . "</a> | <a href=" . $adminfold . "/" . $adminfile . ".php?op=edit_files&lid=$lid>" . _FEDIT . "</a> ]</td></tr>";
	}

	echo "<tr><td><hr></td></tr><tr><td align=\"center\">";
	echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td>";
	if ( defined('IS_ADMMOD') || ($download == 0) || ((defined('IS_USER')) and ($download == 1)) )
	{
		echo "<form method=\"POST\" action=\"modules.php?name=$module_name\" style=\"display: inline\">" . "<input type=\"hidden\" name=\"lid\" value=\"$lid\">";
		if ( extension_loaded("gd") and ($fchecknum == 1) and (! defined('IS_ADMMOD') OR ! defined('IS_USER')) )
		{
			echo "<center>" . _DOWNNOTES . "</center><br>";
			echo "<b>" . _SECURITYCODE . ":</b> <img width=\"73\" height=\"17\" src='?gfx=gfx' border='1' alt='" . _SECURITYCODE . "' title='" . _SECURITYCODE . "'>\n" . "<b>" . _TYPESECCODE . ":</b> <input type=\"text\" NAME=\"gfx_check\" SIZE=\"11\" MAXLENGTH=\"6\">\n";
		}
		echo "<input type=\"hidden\" name=\"go\" value=\"getit\">" . "<input type=\"submit\" value=\"" . _DOWNLFILE . "\">&nbsp;</form>";
	}
	else
	{
		echo "<center><font class=\"indexhometext\">" . _MEMBERRIQUIRED . "</font></center>";
	}

	echo "</td>";
	echo "</tr></table>";
	echo "</td></tr><tr><td>";
	echo "<hr><center>[ <a href=\"javascript:history.go(-1)\">" . _PBACK . "</a> | <a href=\"modules.php?name=$module_name\">" . _PHOME . "</a> ]</center>";
	echo "</td></tr></table>";
	CloseTable();
	if ( defined('IS_ADMMOD') || ((defined('IS_USER')) and ($addcomments == 2)) || ($addcomments == 1) )
	{
		echo "<br><b>" . _COMMENTSQ . "</b><hr>";
		$subject = "Re: $title";
		@include ( "" . $datafold . "/ulist.php" );
		if ( ! defined('IS_ADMMOD') and ! defined('IS_USER') and $anonpost == 0 )
		{
			OpenTable();
			echo "<center><b>" . _NOANONCOMMENTS . "<b></center>";
			CloseTable();
		}
		else
		{
			echo "<form action=\"modules.php?name=$module_name\" method=\"post\">";
			if ( ! defined('IS_USER') )
			{
				echo "<font class=option><b>" . _YOURNAME . ":</b></font><br>";
				echo "<input type=\"text\" name=\"postname\" size=\"62\"><br>";
				echo "<font class=option><b>" . _FYOUREMAIL . "</b></font><br>";
				echo "<input type=\"text\" name=\"postemail\" size=\"62\"><br>";
				echo "<font class=option><b>" . _URL . ":</b></font><br>";
				echo "<input type=\"text\" name=\"posturl\" size=\"62\"><br>\n";
			}
			else
			{
				global $user_ar;
				$tentv = explode( "|", $udt[intval($user_ar[0])] );
				echo "<font class=option><b>" . _YOURNAME . ":</b> $tentv[1]</font><br>";
				echo "<input type=\"hidden\" name=\"postname\" value=\"$tentv[1]\">\n" 
				. "<input type=\"hidden\" name=\"postemail\" value=\"$tentv[2]\">\n" 
				. "<input type=\"hidden\" name=\"posturl\" value=\"\">\n";
			}
			echo "<font class=\"option\"><b>" . _SUBJECT . ":</b></font><br>";
			echo "<input type=\"text\" name=\"subject\" size=\"62\" value=\"$subject\"><br>";
			echo "<font class=\"option\"><b>" . _UCOMMENT . ":</b></font><br>" 
			. "<textarea wrap=\"virtual\" cols=\"62\" rows=\"5\" name=\"comment\"></textarea><br>";
			if ( extension_loaded("gd") and (! defined('IS_ADMMOD') OR ! defined('IS_USER')) )
			{
				echo "<b>" . _TYPESECCODE . ":</b><br>\n";
				echo "<img style=\"vertical-align: middle;\" width=\"73\" height=\"17\" src='?gfx=gfx' border='1' alt='" . _SECURITYCODE . "' title='" . _SECURITYCODE . "'>\n";
				echo "<input type='text' name='gfx_check' id='gfx_check' size='11' maxlength='6'><br><br>\n";
			}
			echo "<input type=\"hidden\" name=\"lid\" value=\"$lid\">\n" 
			. "<input type=\"hidden\" name=\"go\" value=\"savecomments\">\n" 
			. "<input type=\"submit\" value=\"" . _COMMENTREPLY . "\"></form>\n";
		}
		echo "<hr><br><a name=com></a>";
		$sql = "SELECT tid, UNIX_TIMESTAMP(date) as formatted, name, email, url, host_name, subject, comment FROM " . $prefix . "_files_comments WHERE lid='$lid' ORDER BY date DESC LIMIT 5";
		$result = $db->sql_query( $sql );
		$a = 1;
		while ( $row = $db->sql_fetchrow($result) )
		{
			$tid = intval( $row['tid'] );
			$send_date = viewtime( $row['formatted'], 2 );
			$sender_name = $row['name'];
			$sender_email = $row['email'];
			$sender_page = $row['url'];
			$sender_host = $row['host_name'];
			$com_title = $row['subject'];
			$com_text = $row['comment'];
			if ( $sender_email != "" )
			{
				$sender_email = "<a href=\"mailto:$sender_email\"><img border=\"0\" src=\"images/email.gif\" width=\"16\" height=\"16\"></a>";
			}
			else
			{
				$sender_email = "<img border=\"0\" src=\"images/email.gif\" width=\"16\" height=\"16\" title=\"" . _NOEMAIL . "\">";
			}
			if ( $sender_page != "" )
			{
				$sender_page = "<a href=\"$sender_page\" target=\"_blank\"><img border=\"0\" src=\"images/www.gif\" width=\"16\" height=\"16\"></a>";
			}
			else
			{
				$sender_page = "<img border=\"0\" src=\"images/www.gif\" width=\"16\" height=\"16\" title=\"" . _NOURL . "\">";
			}
			echo "<table border=\"0\" cellpadding=\"2\" cellspacing=\"1\" width=\"100%\" bgcolor=\"$bgcolor2\">\n" . "<tr><td bgcolor=\"$bgcolor1\">$a</td><td bgcolor=\"$bgcolor1\">$sender_email</td>\n" . "<td bgcolor=\"$bgcolor1\">$sender_page</td><td width=\"70%\" bgcolor=\"$bgcolor1\"><b>$sender_name</b></td>\n" . "<td width=\"30%\" bgcolor=\"$bgcolor1\"><p align=\"right\">$send_date</td>\n" . "</tr><tr><td width=\"100%\" bgcolor=\"$bgcolor1\" colspan=\"5\"><b>$com_title</b><br>$com_text</td></tr>\n";
			if ( defined('IS_ADMMOD') )
			{
				echo "<tr><td width=\"100%\" bgcolor=\"$bgcolor1\" colspan=\"5\" align=\"center\">[ <a href=\"" . $adminfold . "/" . $adminfile . ".php?op=ConfigureBan&bad_ip=$sender_host\">$sender_host</a> | <a href='" . $adminfold . "/" . $adminfile . ".php?op=EditFilesComment&amp;tid=$tid&amp;lid=$lid'>" . _EDIT . "</a>&nbsp;|&nbsp;<a href=\"" . $adminfold . "/" . $adminfile . ".php?op=delit_file_comment&tid=$tid&lid=$lid\">" . _DELETE . "</a> ]</td></tr>";
			}
			echo "</table><br><br>";
			$a++;
		}
		$num = $db->sql_numrows( $db->sql_query("SELECT tid FROM " . $prefix . "_files_comments WHERE lid='$lid'") );
		if ( $num > 5 )
		{
			echo "<b><a href=\"modules.php?name=$module_name&amp;go=show&lid=$lid\">" . _READREST . "</a></b> (" . _ALL . ": $num)";
			echo "<hr>";
		}
	}
	files_foot();
	include ( "footer.php" );
}

/**
 * savecomments()
 * 
 * @return
 */
function savecomments()
{
	global $client_ip, $prefix, $db, $module_name, $addcomments, $sitekey;
	if ( $addcomments == 0 )
	{
		Header( "Location: index.php" );
		exit;
	}
	$lid = intval( $_POST['lid'] );
	$postname = FixQuotes( filter_text($_POST['postname'], "nohtml") );
	$postemail = FixQuotes( filter_text($_POST['postemail'], "nohtml") );
	$posturl = FixQuotes( filter_text($_POST['posturl'], "nohtml") );
	$subject = FixQuotes( filter_text($_POST['subject'], "nohtml") );
	$comment = $_POST['comment'];
	$gfx_check = intval( $_POST['gfx_check'] );
	if ( ! isset($lid) || $lid == 0 )
	{
		Header( "Location: index.php" );
		exit;
	}
	$f = 40;
	$e = explode( " ", $comment );
	for ( $a = 0; $a < sizeof($e); $a++ )
	{
		$o = strlen( $e[$a] );
	}
	$result = 0;
	if ( $subject == "" )
	{
		$result = 1;
		$eror = "" . _ACEROR1 . "";
	} elseif ( $comment == "" )
	{
		$result = 1;
		$eror = "" . _ACEROR2 . "";
	} elseif ( $o > 40 )
	{
		$result = 1;
		$eror = "" . _ACEROR3 . "";
	} elseif ( $postemail == "" )
	{
		$result = 1;
		$eror = "" . _ACEROR4 . "";
	} elseif ( $postname == "" )
	{
		$result = 1;
		$eror = "" . _YOURNAME . "?";
	}
		if ( $eror == "" )
		{
			if ( extension_loaded("gd") and (! defined('IS_ADMMOD') OR ! defined('IS_USER')) )
			{
				if ( ! nv_capcha_txt($gfx_check) )
				{
					$result = 1;
					$eror = "" . _WRONGCHECKNUM. "";
				}
			}
		}
	if ( $result != 0 )
	{
		include ( "header.php" );
		echo "<center><b>$eror<br><br></b></center>";
		echo "<form action=\"modules.php?name=$module_name\" method=\"post\">";
		echo "<font class=option><b>" . _YOURNAME . ":</b></font><br>";
		echo "<input type=\"text\" name=\"postname\" size=\"62\" value=\"" . $postname . "\"><br>";
		echo "<font class=option><b>" . _FYOUREMAIL . "</b></font><br>";
		echo "<input type=\"text\" name=\"postemail\" size=\"62\" value=\"" . $postemail . "\"><br>";
		echo "<font class=option><b>" . _URL . ":</b></font><br>";
		echo "<input type=\"text\" name=\"posturl\" size=\"62\" value=\"" . $posturl . "\"><br>\n";
		echo "<font class=\"option\"><b>" . _SUBJECT . ":</b></font><br>";
		echo "<input type=\"text\" name=\"subject\" size=\"62\" value=\"" . $subject . "\"><br>";
		echo "<font class=\"option\"><b>" . _UCOMMENT . ":</b></font><br>" 
		. "<textarea wrap=\"virtual\" cols=\"62\" rows=\"5\" name=\"comment\">".$comment."</textarea><br>";
		if ( extension_loaded("gd") and (! defined('IS_ADMMOD') OR ! defined('IS_USER')) )
		{
			echo "<b>" . _TYPESECCODE . ":</b><br>\n";
			echo "<img style=\"vertical-align: middle;\" width=\"73\" height=\"17\" src='?gfx=gfx' border='1' alt='" . _SECURITYCODE . "' title='" . _SECURITYCODE . "'>\n";
			echo "<input type='text' name='gfx_check' id='gfx_check' size='11' maxlength='6'><br><br>\n";
		}
		echo "<input type=\"hidden\" name=\"lid\" value=\"$lid\">\n" 
		. "<input type=\"hidden\" name=\"go\" value=\"savecomments\">\n" 
		. "<input type=\"submit\" value=\"" . _COMMENTREPLY . "\"></form>\n";
		include ( "footer.php" );
		// End hien thi lai form gui
	} else {
	$comment = nl2br( FixQuotes(filter_text($comment, "nohtml")) );
	$ip = $client_ip;
	$db->sql_query( "INSERT INTO " . $prefix . "_files_comments VALUES (NULL, '$lid', now(), '$postname', '$postemail', '$posturl', '$ip', '$subject', '$comment')" );
	$db->sql_query( "UPDATE " . $prefix . "_files SET totalcomments=totalcomments+1 WHERE lid='$lid'" );
	Header( "Location: modules.php?name=$module_name&go=view_file&lid=$lid#com" );
	}
}

/**
 * show()
 * 
 * @return
 */
function show()
{
	global $adminfold, $adminfile, $datafold, $prefix, $db, $module_name, $anonpost, $bgcolor1, $bgcolor2, $addcomments;
	$lid = intval( $_GET['lid'] );
	if ( ! defined('IS_ADMMOD') && $addcomments == 0 )
	{
		Header( "Location: index.php" );
		exit;
	}
	if ( ! isset($lid) || $lid == 0 )
	{
		Header( "Location: index.php" );
		exit();
	}
	$sql = "SELECT title FROM " . $prefix . "_files WHERE lid='$lid'";
	$result = $db->sql_query( $sql );
	if ( $numrows = $db->sql_numrows($result) != 1 )
	{
		Header( "Location: index.php" );
		exit;
	}
	$row = $db->sql_fetchrow( $result );
	$title = $row['title'];
	include ( "header.php" );
	echo "<br><b>" . _COMMENTSAR . ":</b> <a href=\"modules.php?name=$module_name&go=view_file&lid=$lid\"><b>$title</b></a><br><br><hr>";
	$subject = "Re: $title";
	@include ( "" . $datafold . "/ulist.php" );
	if ( ! defined('IS_USER') and $anonpost == 0 )
	{
		OpenTable();
		echo "<center><b>" . _NOANONCOMMENTS . "<b></center>";
		CloseTable();
	}
	else
	{
		OpenTable();
		echo "<form action=\"modules.php?name=$module_name\" method=\"post\">";
		if ( ! defined('IS_USER') )
		{
			echo "<font class=option><b>" . _YOURNAME . ":</b></font><br>";
			echo "<input type=\"text\" name=\"postname\" size=\"62\"><br>";
			echo "<font class=option><b>" . _FYOUREMAIL . ":</b></font><br>";
			echo "<input type=\"text\" name=\"postemail\" size=\"62\"><br>";
			echo "<font class=option><b>" . _URL . ":</b></font><br>";
			echo "<input type=\"text\" name=\"posturl\" size=\"62\"><br>\n";
		}
		else
		{
			global $user_ar;
			$tentv = explode( "|", $udt[intval($user_ar[0])] );
			echo "<font class=option><b>" . _YOURNAME . ":</b> $tentv[1]</font><br>";
			echo "<input type=\"hidden\" name=\"postname\" value=\"$tentv[1]\">\n" . "<input type=\"hidden\" name=\"postemail\" value=\"$tentv[2]\">\n" . "<input type=\"hidden\" name=\"posturl\" value=\"\">\n";
		}
		echo "<font class=\"option\"><b>" . _SUBJECT . ":</b></font><br>";
		echo "<input type=\"text\" name=\"subject\" size=\"62\" value=\"$subject\"><br>";
		echo "<font class=\"option\"><b>" . _UCOMMENT . ":</b></font><br>" . "<textarea wrap=\"virtual\" cols=\"62\" rows=\"5\" name=\"comment\"></textarea><br><br>" . "<input type=\"hidden\" name=\"lid\" value=\"$lid\">\n" . "<input type=\"hidden\" name=\"go\" value=\"savecomments\">\n" . "<input type=\"submit\" value=\"" . _COMMENTREPLY . "\"></form>\n";
		CloseTable();
	}

	$numf = $db->sql_fetchrow( $db->sql_query("SELECT COUNT(*) FROM " . $prefix . "_files_comments WHERE lid='$lid'") );
	$page = ( isset($_GET['page']) ) ? intval( $_GET['page'] ) : 0;
	$all_page = ( $numf[0] ) ? $numf[0] : 1;
	$per_page = 10;
	$base_url = "modules.php?name=$module_name&go=show&lid=$lid";
	echo "<hr><br>";
	$sql = "SELECT tid, UNIX_TIMESTAMP(date) as formatted, name, email, url, host_name, subject, comment FROM " . $prefix . "_files_comments WHERE lid='$lid' ORDER BY date desc LIMIT $page,$per_page";
	$result = $db->sql_query( $sql );
	$a = 1;
	while ( $row = $db->sql_fetchrow($result) )
	{
		$tid = intval( $row['tid'] );
		$send_date = viewtime( $row['formatted'], 2 );
		$sender_name = $row['name'];
		$sender_email = $row['email'];
		$sender_page = $row['url'];
		$sender_host = $row['host_name'];
		$com_title = $row['subject'];
		$com_text = $row['comment'];
		if ( $sender_email != "" )
		{
			$sender_email = "<a href=\"mailto:$sender_email\"><img border=\"0\" src=\"images/email.gif\" width=\"16\" height=\"16\"></a>";
		}
		else
		{
			$sender_email = "<img border=\"0\" src=\"images/email.gif\" width=\"16\" height=\"16\" title=\"" . _NOEMAIL . "\">";
		}
		if ( $sender_page != "" )
		{
			$sender_page = "<a href=\"$sender_page\" target=\"_blank\"><img border=\"0\" src=\"images/www.gif\" width=\"16\" height=\"16\"></a>";
		}
		else
		{
			$sender_page = "<img border=\"0\" src=\"images/www.gif\" width=\"16\" height=\"16\" title=\"" . _NOURL . "\">";
		}

		echo "<table border=\"0\" cellpadding=\"2\" cellspacing=\"1\" width=\"100%\" bgcolor=\"$bgcolor2\">\n" . "<tr><td bgcolor=\"$bgcolor1\">$a</td><td bgcolor=\"$bgcolor1\">$sender_email</td>\n" . "<td bgcolor=\"#FFFFFF\">$sender_page</td><td width=\"70%\" bgcolor=\"$bgcolor1\"><b>$sender_name</b></td>\n" . "<td width=\"30%\" bgcolor=\"$bgcolor1\"><p align=\"right\">$send_date</td>\n" . "</tr><tr><td width=\"100%\" bgcolor=\"$bgcolor1\" colspan=\"5\"><b>$com_title</b><br>$com_text</td></tr>\n";
		if ( defined('IS_ADMMOD') )
		{
			echo "<tr><td width=\"100%\" bgcolor=\"$bgcolor1\" colspan=\"5\" align=\"center\">[ <a href=\"" . $adminfold . "/" . $adminfile . ".php?op=ConfigureBan&bad_ip=$sender_host\">$sender_host</a> | <a href=\"" . $adminfold . "/" . $adminfile . ".php?op=delit_file_comment&tid=$tid&lid=$lid\">" . _DELETE . "</a> ]</td></tr>";
		}
		echo "</table><br>";
		$a++;
	}
	echo @generate_page( $base_url, $all_page, $per_page, $page );
	include ( "footer.php" );
}

//Binh chon file
/**
 * pool()
 * 
 * @return
 */
function pool()
{
	global $client_ip, $prefix, $db, $filesvote;
	$lid = intval( $_POST['lid'] );
	$send_reiting = intval( $_POST['send_reiting'] );
	if ( ($filesvote == 0) || ((! defined('IS_ADMMOD')) and (! defined('IS_USER')) and ($filesvote == 2)) )
	{
		info_exit( "._NOUPOOLFILES." );
	}
	if ( ! $lid || $lid == 0 )
	{
		info_exit( "<b>" . _FEROR . "</b><br>" . _NONFILE . "" );
	}

	$ip = $client_ip;
	$past = time() - 86400;
	$db->sql_query( "DELETE FROM " . $prefix . "_files_poolchec WHERE time < '$past'" );
	$num = $db->sql_numrows( $db->sql_query("SELECT * FROM " . $prefix . "_files_poolchec WHERE lid='$lid' AND host_addr='$ip'") );
	if ( $num )
	{
		info_exit( "<br><br><br><center><b>" . _RATENOCOMPLECT . "</b><br><br>" . _RATENOTE . "<br><br><b>" . _GOBACK . "</b></center><br><br><br>" );
	}
	$ctime = time();
	$db->sql_query( "INSERT INTO " . $prefix . "_files_poolchec (lid, time, host_addr) VALUES ('$lid', '$ctime', '$ip')" );
	$db->sql_query( "UPDATE " . $prefix . "_files SET votes=votes+1, totalvotes=totalvotes+$send_reiting WHERE lid='$lid'" );
	info_exit( "<br><br><br><center><b>" . _RATECOMPLECT . "</b><br><br><b>" . _GOBACK . "</b></center><br><br><br>\n<META HTTP-EQUIV=\"Refresh\"  CONTENT=\"2; URL=javascript:history.go(-1)\">" );
}

/**
 * broken()
 * 
 * @return
 */
function broken()
{
	global $prefix, $db, $brokewarning;
	if ( ($brokewarning == 0) || ((! defined('IS_ADMMOD')) and (! defined('IS_USER')) and ($brokewarning == 2)) )
	{
		Header( "Location: index.php" );
		exit;
	}
	$lid = intval( $_GET['lid'] );
	if ( ! $lid || $lid == 0 )
	{
		info_exit( "<b>" . _FEROR . "</b><br>" . _NONFILE . "" );
	}
	$db->sql_query( "UPDATE " . $prefix . "_files SET status='2' WHERE lid='$lid'" );
	info_exit( "<br><br><center><b>" . _SENBROC . "</b><br><br><br>" . _BROCNOTE . "<br><br>" . _BROCNOTE2 . "<br><br><b>" . _GOBACK . "</b></center><br><br><br><META HTTP-EQUIV=\"Refresh\"  CONTENT=\"2; URL=javascript:history.go(-1)\">" );
}

//Giao dien them file
/**
 * add_file()
 * 
 * @return
 */
function add_file()
{
	global $db, $prefix, $module_name, $addfiles, $uploadfiles, $maxfilesize;
	if ( ($addfiles == 0) || ((! defined('IS_ADMMOD')) and (! defined('IS_USER')) and ($addfiles == 2)) )
	{
		Header( "Location: index.php" );
		exit;
	}

	include ( "header.php" );
	files_head();
	OpenTable();
	echo "<center><font class=\"indexhometext\"><b>" . _ADDFILE . "</b><br><br>" . _ADDFNOTE . "";
	echo "</center>";
	CloseTable();
	echo "<br>";
	// Kiểm tra việc điền dữ liệu
	echo "
<script type=\"text/javascript\">
	function dissc() {
		document.settform.B1.disabled = true;
		return true;
	}
	function Check_S() {
		var a = document.getElementById('l_text');
		var b = document.getElementById('l_email');\n";
	if ( $uploadfiles == 0 and ! defined('IS_ADMMOD') )
	{
		echo "var c = document.getElementById('l_url');\n";
	}
	echo "		var d = document.getElementById('l_longtext');

		var filtermail  = /^[A-Za-z0-9]+([_\.\-]{1}[A-Za-z0-9]+)*@[A-Za-z0-9]+([_\.\-]{1}[A-Za-z0-9]+)*\.[A-Za-z]{2,6}$/;

		if(a.value.length>2 && b.value.length>7 && filtermail.test(b.value) && 	\n";
	if ( $uploadfiles == 0 and ! defined('IS_ADMMOD') )
	{
		echo "c.value.length>14 && c.value.length<300 && ";
	}
	echo "d.value.length>5) {
			document.settform.B1.disabled = false;
		} else {
			document.settform.B1.disabled = true;
		}
	}

</script>\n";
	// end

	OpenTable();
	echo "<form name=\"settform\" id=\"settform\" onsubmit=\"return dissc();\" enctype=\"multipart/form-data\" action=\"modules.php?name=$module_name\" method=\"post\">" . "<b>" . _FTITLE . ":</b><br><input id=\"l_text\" type=\"text\" name=\"title\" size=\"50\" maxlength=\"100\" onkeypress=\"Check_S();\" onkeyup=\"Check_S();\" onblur=\"Check_S();\"><br><br>" . "<b>" . _FCATEGORY . ":</b><br>" . "<select name=\"cid\"><option value=\"0\">" . _FHOMEFILES . "</option>";
	$sql = "SELECT cid, title, parentid FROM " . $prefix . "_files_categories ORDER BY parentid,title";
	$result = $db->sql_query( $sql );
	while ( $row = $db->sql_fetchrow($result) )
	{
		$cid = intval( $row['cid'] );
		$title = $row['title'];
		$parentid = intval( $row['parentid'] );
		if ( $parentid != 0 ) $title = getparent( $parentid, $title );
		echo "<option value=\"$cid\">$title</option>";
	}
	echo "</select><br><br>";
	echo "<b>" . _SUBTITLE . ":</b><br>" . "<textarea id=\"l_longtext\" name=\"description\" cols=\"50\" rows=\"10\" onkeypress=\"Check_S();\" onkeyup=\"Check_S();\" onblur=\"Check_S();\"></textarea><br><br>" . "<b>" . _FILEAUTOR . "</b><br><input type=\"text\" name=\"author\" size=\"50\" maxlength=\"100\" onblur=\"if (this.value=='') this.value='0';\" onfocus=\"if (this.value=='0') this.value='';\" value=\"0\"><br><br>" . "<b>" . _FAUEMAIL . "</b><br><input id=\"l_email\" type=\"text\" name=\"authormail\" size=\"50\" maxlength=\"100\" onkeypress=\"Check_S();\" onkeyup=\"Check_S();\" onblur=\"Check_S();\"><br><br>" . "<b>" . _FAUURL . "</b><br><input type=\"text\" name=\"authorurl\" size=\"50\" maxlength=\"300\" value=\"http://\"><br><br>";

	if ( defined('IS_ADMMOD') || ($uploadfiles == 1) || ((defined('IS_USER')) and ($uploadfiles == 2)) )
	{
		echo "<b>" . _FILE . "</b> (< " . files_size( $maxfilesize ) . "):<br>";
		echo "" . _ADDFNOTE2 . " <b>" . files_size( $maxfilesize ) . "</b> " . _ADDFNOTE3 . " <b><a href=\"#filelink\">" . _FILELINK . "</a></b><br>";
		echo "<input name=\"userfile\" type=\"file\" size=\"40\"><br><br>";
	}

	if ( $uploadfiles == 0 and ! defined('IS_ADMMOD') )
	{
		echo "<b><a name=\"filelink\">" . _FILELINK . "</a>:</b><br>" . _ADDFNOTE4 . "<br><input id=\"l_url\" type=\"text\" name=\"filelink\" size=\"50\" maxlength=\"300\" value=\"http://\" onkeypress=\"Check_S();\" onkeyup=\"Check_S();\" onblur=\"Check_S();\"><br><br>";
	}
	else
	{
		echo "<b><a name=\"filelink\">" . _FILELINK . "</a>:</b><br>" . _ADDFNOTE4 . "<br><input type=\"text\" name=\"filelink\" size=\"50\" maxlength=\"300\" value=\"http://\"><br><br>";
	}
	echo "<b>" . _FILEVERSION . "</b><br><input type=\"text\" name=\"version\" size=\"10\" maxlength=\"10\" onblur=\"if (this.value=='') this.value='0';\" onfocus=\"if (this.value=='0') this.value='';\" value=\"0\"><br><br>" . "<b>" . _FILESIZE . ":</b><br><input type=\"text\" name=\"file_size\" size=\"10\" maxlength=\"10\" onblur=\"if (this.value=='') this.value='0';\" onfocus=\"if (this.value=='0') this.value='';\" value=\"0\">";
	if ( defined('IS_ADMMOD') || ($uploadfiles == 1) || ((defined('IS_USER')) and ($uploadfiles == 2)) )
	{
		echo " (<i>" . _SIZENOTE . "</i>)";
	}
	else
	{
		echo " (<i>Byte</i>)";
	}
	echo "<br><br><input type=\"hidden\" name=\"go\" value=\"file_send\">" . "<input name=\"B1\" id=\"B1\" disabled=\"disabled\" type=\"submit\" value=\"" . _FADD . "\">" . "</form>";
	CloseTable();
	files_foot();
	include ( "footer.php" );
}

//Thuc hien them file vao CSDL
/**
 * file_send()
 * 
 * @return
 */
function file_send()
{
	global $client_ip, $files_mime, $prefix, $db, $module_name, $user, $path, $temp_path, $nukeurl, $maxfilesize, $addfiles;
	if ( ($addfiles == 0) || ((! defined('IS_ADMMOD')) and (! defined('IS_USER')) and ($addfiles == 2)) )
	{
		Header( "Location: index.php" );
		exit;
	}
	if ( isset($_GET['go']) )
	{
		Header( "Location: index.php" );
		exit;
	}
	$filelink = stripslashes( FixQuotes($_POST['filelink']) );
	if ( $filelink == "http://" )
	{
		$filelink = "";
	}
	$authorurl = stripslashes( FixQuotes($_POST['authorurl']) );
	if ( $authorurl == "http://" )
	{
		$authorurl = "";
	}
	$title = stripslashes( FixQuotes($_POST['title']) );
	$description = stripslashes( FixQuotes($_POST['description']) );
	$author = stripslashes( FixQuotes($_POST['author']) );
	$authormail = stripslashes( FixQuotes($_POST['authormail']) );
	$version = stripslashes( FixQuotes($_POST['version']) );
	$cid = intval( $_POST['cid'] );
	$file_size = intval( $_POST['file_size'] );

	if ( ($title == "") or ($description == "") or ($author == "") or ($authormail == "") or ($authorurl == "") )
	{
		info_exit( "<center><br><br>" . _UPLOADEROR . "<br><br>" . _ERGOBACK . "<br><br></center>" );
	}

	if ( (is_uploaded_file($_FILES['userfile']['tmp_name'])) )
	{
		if ( ((file_exists("" . $path . "/" . $_FILES['userfile']['name'] . ""))) or ((file_exists("" . $temp_path . "/" . $_FILES['userfile']['name'] . ""))) )
		{
			info_exit( "<center><br><br>" . _FILEEXIST . "<br>" . _GOBACK . "<br></center>" );
		}
		$file_name = $_FILES['userfile']['name'];
		$file_size = $_FILES['userfile']['size'];
		$file_type = $_FILES['userfile']['type'];
		$f_name = end( explode(".", $file_name) );
		$f_extension = strtolower( $f_name );

		if ( $file_size > $maxfilesize )
		{
			info_exit( "<center><br><br>" . _FILESIZEBAD . "<br>" . _GOBACK . "<br></center>" );
		}

		$files_mime = explode( ",", $files_mime );
		if ( ! in_array($f_extension, $files_mime) )
		{
			info_exit( "<center><br><br>" . _FILETYPEBAD . "<br>" . _GOBACK . "<br></center>" );
		}
		if ( $f_extension == "pdf" )
		{
			$ctype = "application/pdf";
		} elseif ( $f_extension == "zip" )
		{
			$ctype = "application/x-zip-compressed";
		} elseif ( $f_extension == "rar" )
		{
			$ctype = "application/octet-stream";
		} elseif ( $f_extension == "gz" )
		{
			$ctype = "application/gzip";
		} elseif ( $f_extension == "tgz" )
		{
			$ctype = "application/tgz";
		} elseif ( $f_extension == "tar" )
		{
			$ctype = "application/tar";
		} elseif ( $f_extension == "bz2" )
		{
			$ctype = "application/bz2";
		} elseif ( $f_extension == "doc" )
		{
			$ctype = "application/msword";
		} elseif ( $f_extension == "xls" )
		{
			$ctype = "application/vnd.ms-excel";
		} elseif ( $f_extension == "ppt" )
		{
			$ctype = "application/vnd.ms-powerpoint";
		} elseif ( $f_extension == "gif" )
		{
			$ctype = "image/gif";
		} elseif ( $f_extension == "png" )
		{
			$ctype = "image/png";
		} elseif ( $f_extension == "jpeg" || $f_extension == "jpg" )
		{
			$ctype = "image/jpg";
		} elseif ( $f_extension == "mp3" )
		{
			$ctype = "audio/mp3";
		} elseif ( $f_extension == "wav" )
		{
			$ctype = "audio/x-wav";
		} elseif ( $f_extension == "mpeg" || $f_extension == "mpg" || $f_extension == "mpe" )
		{
			$ctype = "video/mpeg";
		} elseif ( $f_extension == "mov" )
		{
			$ctype = "video/quicktime";
		} elseif ( $f_extension == "wmv" || $f_extension == "avi" )
		{
			$ctype = "video/x-msvideo";
		}
		else
		{
			$ctype = "application/force-download";
		}

		if ( $file_type != "$ctype" )
		{
			info_exit( "<center><br><br>" . _FILETYPEBAD . "<br>" . _GOBACK . "<br></center>" );
		}

		if ( ! @copy($_FILES['userfile']['tmp_name'], "$temp_path/$file_name") )
		{
			if ( ! move_uploaded_file($_FILES['userfile']['tmp_name'], $temp_path . "/" . $file_name) )
			{
				info_exit( "<center><br><br>" . _UPLOADEROR1 . " $path .<br>" . _GOBACK . "<br></center>" );
			}
		}
		$file_name = "" . $nukeurl . "/" . $temp_path . "/" . $file_name . "";
	}
	else
	{
		if ( $filelink == "" )
		{
			info_exit( "<center><br><br>" . _UPLOADEROR2 . "<br><br>" . _ERGOBACK . "<br><br></center>" );
		}
		$file_name = $filelink;
		$file_size = intval( $file_size );
	}
	$ip = $client_ip;
	$add = $db->sql_query( "INSERT INTO " . $prefix . "_files (lid, cid, title, description, url, date, filesize, version, name, email, homepage, ip_sender, status) VALUES (NULL, '$cid', '$title', '$description', '$file_name', now(), '$file_size', '$version', '$author', '$authormail', '$authorurl', '$ip', '0')" );
	if ( $add )
	{
		info_exit( "<center><br><br>" . _UPLOADFINISH . "<br><br></center><META HTTP-EQUIV=\"refresh\" content=\"6;URL=modules.php?name=$module_name\">" );
	}
	else
	{
		info_exit( "<center><br><br>" . _UPLOADEROR3 . "<br>" . _GOBACK . "<br></center>" );
	}
}

$go = addslashes( trim((isset($_POST['go'])) ? $_POST['go'] : $_GET['go']) );
if ( eregi("[^a-zA-Z0-9_]", $go) )
{
	Header( "Location:index.php" );
	exit;
}

switch ( $go )
{
	case "gfx":
		gfx( $random_num );
		break;

	case "showcat":
		showcat();
		break;

	case "cat":
		cat();
		break;

	case "view_file":
		view_file();
		break;

	case "pool":
		pool();
		break;

	case "getit":
		getit();
		break;

	case "broken":
		broken();
		break;

	case "add_file":
		add_file();
		break;

	case "popular":
		popular();
		break;

	case "file_send":
		file_send();
		break;

	case "savecomments":
		savecomments();
		break;

	case "show":
		show();
		break;

	default:
		mainfiles();
		break;

}

?>