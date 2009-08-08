<?php

/*
* @Program:		NukeViet CMS v2.0 RC1
* @File name: 	Module News
* @Version: 	2.0
* @Date: 		13.06.2009
* @Website: 	www.nukeviet.vn
* @Copyright: 	(C) 2009
* @License: 	http://opensource.org/licenses/gpl-license.php GNU Public License
*/

if ( ! defined('NV_SYSTEM') )
{
	die( "You can't access this file directly..." );
}

$module_name = basename( dirname(__file__) );
@require_once ( "mainfile.php" );
if ( file_exists("" . $datafold . "/" . $module_name . "_config.php") )
{
	@require_once ( "" . $datafold . "/" . $module_name . "_config.php" );
}
get_lang( $module_name );
if ( file_exists("" . $datafold . "/config_" . $module_name . ".php") )
{
	@require_once ( "" . $datafold . "/config_" . $module_name . ".php" );
}
if ( defined('_MODTITLE') ) $module_title = _MODTITLE;
/********************************************/

$index = ( defined('MOD_BLTYPE') ) ? MOD_BLTYPE : 1;

if ( file_exists("" . $datafold . "/ncatlist.php") )
{
	@require_once ( "" . $datafold . "/ncatlist.php" );
}

/**
 * main()
 * 
 * @return
 */
function main()
{
	global $nvcat, $nvcat2, $adminfold, $adminfile, $newspagenum, $home, $module_name, $linkshome, $sizecatnewshomeimg, $path, $catnewshomeimg, $catimgnewshome, $newshome, $news2cot, $adminfold, $db, $prefix, $multilingual, $currentlang, $articlecomm, $pagenum;
	if ( $multilingual == 1 )
	{
		$querylang = "AND (alanguage='$currentlang' OR alanguage='')";
	}
	else
	{
		$querylang = "";
	}
	include ( "header.php" );
	automated_news();
	$mau = 0;
	$rong = 120; // for News 2 cot
	if ( ($newshome == 1) and ($home == 1) )
	{
		// Begin 1/3 - News 2 cot
		if ($news2cot==1)
		{      
		$tabcolumn = 2;
		$cont = 0;
		$tdwidth = intval(100/$tabcolumn);
		echo "<center><table border=\"0\" cellspacing=\"3\" cellpadding=\"3\"><tr><td valign=\"top\" width=\"".$tdwidth."%\">";
		}
		// End 1/3 - News 2 cot
		for ( $nvc = 0; $nvc < sizeof($nvcat); $nvc++ )
		{
			if ( $nvcat[$nvc] != "" )
			{
				$nvcat_ar = explode( "|", $nvcat[$nvc] );
				if ( $nvcat_ar[1] == '0' && $nvcat_ar[5] == '1' )
				{
					$cat_id = intval( $nvcat_ar[0] );
					$cat_title = stripslashes( check_html($nvcat_ar[2], "nohtml") );
					$linkshome = intval( $nvcat_ar[7] );
					$storieshome = intval( $nvcat_ar[6] );
					$catimage = $nvcat_ar[4];
					$whe = "WHERE (catid='$cat_id'";
					$subcat = explode( ",", $nvcat_ar[8] );
					for ( $i = 0; $i < sizeof($subcat); $i++ )
					{
						if ( $subcat[$i] != "" )
						{
							$nvcat2_ar = explode( "|", $nvcat2[$subcat[$i]] );
							if ( $nvcat2_ar[5] == 1 ) $whe .= "OR catid='" . intval( $subcat[$i] ) . "'";
						}
					}
					$whe .= ")";
					if ( $storieshome != 0 )
					{
						$xstorieshome = $storieshome;
					}
					else
					{
						$liststhm = array();
						$m = 0;
						for ( $n = 0; $n < sizeof($subcat); $n++ )
						{
							$nvcat2_ar2 = explode( "|", $nvcat2[$subcat[$n]] );
							if ( $nvcat2_ar2[5] == 1 and $nvcat2_ar2[6] != '0' )
							{
								$liststhm[$m] = intval( $nvcat2_ar2[6] );
								$m++;
							}
						}
						rsort( $liststhm );
						$xstorieshome = intval( $liststhm[0] );
					}
					$numx = $db->sql_fetchrow( $db->sql_query("SELECT COUNT(*) FROM " . $prefix . "_stories WHERE ihome='1' AND sid='$xstorieshome' AND newsst!='1' $querylang") );
					if ( $numx[0] == 1 )
					{
						$result2 = $db->sql_query( "SELECT * FROM " . $prefix . "_stories WHERE sid='$xstorieshome'" );
					}
					else
					{
						$result2 = $db->sql_query( "SELECT * FROM " . $prefix . "_stories $whe AND ihome='1' AND newsst!='1' $querylang ORDER BY sid DESC LIMIT 0,1" );
					}
					if ( $db->sql_numrows($result2) != 0 )
					{
						$cat_name = "<a href=\"modules.php?name=$module_name&op=viewcat&catid=$cat_id\" class=\"A_white\">$cat_title</a>";
						categoryname( $cat_name );
						$row2 = $db->sql_fetchrow( $result2 );
						$sid = intval( $row2['sid'] );
						$aid = stripslashes( $row2['aid'] );
						$title = stripslashes( check_html($row2['title'], "nohtml") );
						$time = formatTimestamp( $row2['time'], 2 );
						$hometext = stripslashes( $row2['hometext'] );
						$bodytext = stripslashes( $row2['bodytext'] );
						$images = $row2['images'];
						$comments = stripslashes( $row2['comments'] );
						$counter = intval( $row2['counter'] );
						$notes = stripslashes( $row2['notes'] );
						$acomm = intval( $row2['acomm'] );
						$imgtext = stripslashes( check_html($row2['imgtext'], "nohtml") );
						$source = stripslashes( check_html($row2['source'], "nohtml") );
						$topicid = intval( $row2['topicid'] );
						if ( $catimgnewshome == 1 )
						{
							if ( $catimage == "" )
							{
								$catimage = "AllTopics.gif";
							}
							if ( $catnewshomeimg == "right" )
							{
								$fl = "left";
							}
							if ( $catnewshomeimg == "left" )
							{
								$fl = "right";
							}
							$size2 = @getimagesize( "images/cat/$catimage" );
							$title = "<a href=\"modules.php?name=$module_name&op=viewcat&catid=$cat_id\" title=\"$cat_title\"><img border=\"0\" src=\"images/cat/$catimage\" width=\"$size2[0]\" style=\"float: $fl\"></a> <a href=\"modules.php?name=$module_name&op=viewst&sid=$sid\">$title</a>";
						}
						else
						{
							$title = "<a href=\"modules.php?name=$module_name&op=viewst&sid=$sid\">$title</a>\n";
						}
						if ( $bodytext != "" )
						{
							$story_link = "<a href=\"modules.php?name=$module_name&op=viewst&sid=$sid\"><img src='images/more.gif' border='0' width=\"47\" height=\"9\" alt=\"" . _READMORE . "\" align=\"right\"></a>";
						}
						else
						{
							$story_link = "";
						}
						$com_link = "";
						if ( defined('IS_ADMMOD') )
						{
							$com_link .= " (" . _COMMENTSQ . ": $comments  | ";
							$tot_hits = "" . _TOTHITS . ": $counter | <a href=\"" . $adminfold . "/" . $adminfile . ".php?op=EditStory&sid=$sid\">" . _EDIT . "</a> | <a href=\"" . $adminfold . "/" . $adminfile . ".php?op=RemoveStory&sid=$sid\">" . _DELETE . "</a>)";
						}
						else
						{
							$tot_hits = " <font class=grey>(" . _TOTHITS . ": $counter)</font>"; // Hiển thị lượt xem, add by http://mangvn.org 01-03-2008
						}
						if ( $images != "" and file_exists("" . $path . "/" . $images . "") )
						{
							if ( file_exists("" . $path . "/small_" . $images . "") )
							{
								$images = "small_" . $images . "";
							}
							$size2 = @getimagesize( "$path/$images" );
							$widthimg = $size2[0];
							if ( $size2[0] > $sizecatnewshomeimg )
							{
								$widthimg = $sizecatnewshomeimg;
							}
							$xtitle = stripslashes( check_html($row2['title'], "nohtml") );
							$story_pic = "<table border=\"0\" width=\"$widthimg\" cellpadding=\"0\" cellspacing=\"3\" align=\"$catnewshomeimg\">\n<tr>\n<td>\n<a href=\"modules.php?name=$module_name&op=viewst&sid=$sid\"><img border=\"0\" src=\"$path/$images\" width=\"$widthimg\" alt=\"$xtitle\"></a></td>\n</tr>\n</table>\n";
						}
						else
						{
							$story_pic = "";
						}
						$notes = "";
						$result3 = $db->sql_query( "SELECT sid, time, title FROM " . $prefix . "_stories $whe AND (ihome='1' AND sid!='$sid' AND newsst!='1') $querylang ORDER BY sid DESC LIMIT $linkshome" );
						$num3 = $db->sql_numrows( $result3 );
						if ( $num3 != 0 )
						{
							$notes .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\"><tr><td style=\"text-align: justify\" valign=\"top\">\n";
							$notes .= "<p class=topmore style=\"margin-top: 5px; margin-bottom: 5px\">" . _MORENEWS2 . "</p>";
							while ( $row3 = $db->sql_fetchrow($result3) )
							{
								$sid3 = intval( $row3['sid'] );
								$title3 = stripslashes( check_html($row3['title'], "nohtml") );
								$time3 = formatTimestamp( $row3['time'], 2 );
								//						$notes .= "<img src='images/modules/$module_name/arrow3.gif' border='0' width=\"10\"><a href='modules.php?name=$module_name&op=viewst&sid=$sid3' class=indexlink>$title3</a>  <font class=grey>[$time3]</font><br>";
								$notes .= "<img src='images/modules/$module_name/arrow3.gif' border='0' width=\"10\"><a href='modules.php?name=$module_name&op=viewst&sid=$sid3' class=indexlink>$title3</a><br>";
							}
							$notes .= "</td></tr></table>";
						}

						themeindex( $aid, $time, $title, $hometext, $story_pic, $notes, $story_link, $com_link, $tot_hits, $mau );
						$mau++;
					}
					//  Begin 2/3 - News 2 cot
					if ($news2cot==1){
					$cont++;
					if ($cont < $tabcolumn) { echo "</td><td valign=\"top\" width=\"".$tdwidth."%\">"; }
					if ($cont == $tabcolumn) { echo "</td></tr><tr><td valign=\"top\" width=\"".$tdwidth."%\">"; $cont = 0; }
					}
					// End 2/3 - News 2 cot
				}
			}
		}
		// Begin 3/3 - News 2 cot
		if ($news2cot==1)
		{      
		echo "</td></tr></table><center>";
		}
		// End 3/3 - News 2 cot
	}
	else
	{

		// Begin - Thêm phần tra cứu trên News! - http://mangvn.org - 01-03-2008

		OpenTable();
		echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" align=\"center\">\n" . "<form method=\"POST\" action=\"modules.php?name=$module_name\">\n" . "<tr>\n<td align=\"center\"><b>" . _POISK . "</b>\n<br><br><select name=\"pozit\">\n" . "<option name=\"pozit\" value=\"1\" selected>" . _POZIT1 . "</option>\n" . "<option name=\"pozit\" value=\"2\">" . _POZIT2 . "</option>\n" . "<option name=\"pozit\" value=\"3\">" . _POZIT3 . "</option>\n" . "</select> <select name=\"day\">\n";
		for ( $i = 1; $i <= 31; $i++ )
		{
			echo "<option value=\"" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "\"";
			if ( $i == date("d", time() + $hourdiff * 60) )
			{
				echo " selected";
			}
			echo ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
		}
		echo "</select>\n<select name=\"month\">\n";
		for ( $i = 1; $i <= 12; $i++ )
		{
			echo "<option value=\"" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "\"";
			if ( $i == date("m", time() + $hourdiff * 60) )
			{
				echo " selected";
			}
			echo ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
		}
		echo "</select>\n<select name=\"year\">\n";
		$z = date( "Y", time() + $hourdiff * 60 );
		for ( $i = $z; $i >= $z - 4; $i-- )
		{
			echo "<option value=\"$i\"";
			if ( $i == $z )
			{
				echo " selected";
			}
			echo ">$i</option>\n";
		}
		echo "</select>\n<br>\n<select name=\"catid\">\n";
		echo "<option name=\"catid\" value=\"\">" . _ALLCATEGORIES . "</option>\n";
		$sql = "SELECT catid, parentid, title FROM " . $prefix . "_stories_cat order by parentid, weight";
		$result = $db->sql_query( $sql );
		while ( $row = $db->sql_fetchrow($result) )
		{
			$catid = $row[catid];
			$title = $row[title];
			$parentid = $row['parentid'];
			if ( $parentid != 0 )
			{
				list( $ptitle ) = $db->sql_fetchrow( $db->sql_query("select title from " . $prefix . "_stories_cat where catid='$parentid'") );
				$title = "$ptitle &raquo; $title";
			}
			echo "<option name=\"catid\" value=\"$catid\">$title</option>\n";
		}
		echo "</select>\n<input type=\"hidden\" name=\"op\" value=\"archive\"><input type=\"submit\" value=\"" . _SEARCH . "\"></td>\n" . "</tr>\n</form>\n</table>\n";
		CloseTable();
		// End - Thêm phần tra cứu trên News! - http://mangvn.org - 01-03-2008

		$numf = $db->sql_fetchrow( $db->sql_query("SELECT COUNT(*) FROM " . $prefix . "_stories WHERE ihome='1' AND newsst!='1' $querylang") );
		$page = ( isset($_GET['page']) ) ? intval( $_GET['page'] ) : 0;
		$all_page = ( $numf[0] ) ? $numf[0] : 1;
		$per_page = intval( $newspagenum );
		$base_url = "modules.php?name=$module_name";
		if ( $numf[0] > 0 )
		{
			$result = $db->sql_query( "SELECT * FROM " . $prefix . "_stories WHERE (ihome='1' AND newsst!='1') $querylang ORDER BY sid DESC LIMIT $page,$per_page" );
			while ( $row = $db->sql_fetchrow($result) )
			{
				$sid = intval( $row['sid'] );
				$catid = intval( $row['catid'] );
				$aid = stripslashes( $row['aid'] );
				$title = stripslashes( check_html($row['title'], "nohtml") );
				$time = formatTimestamp( $row['time'], 2 );
				$hometext = stripslashes( $row['hometext'] );
				$bodytext = stripslashes( $row['bodytext'] );
				$images = $row['images'];
				$comments = stripslashes( $row['comments'] );
				$counter = intval( $row['counter'] );
				$notes = stripslashes( $row['notes'] );
				$acomm = intval( $row['acomm'] );
				$imgtext = stripslashes( check_html($row['imgtext'], "nohtml") );
				$source = stripslashes( check_html($row['source'], "nohtml") );
				$topicid = intval( $row['topicid'] );
				if ( $catid > 0 )
				{
					$cat_inf = explode( "|", $nvcat2[$catid] );
					$cattitle = stripslashes( check_html($cat_inf[2], "nohtml") );
					$catimage = $cat_inf[4];
					if ( $catimgnewshome == 1 )
					{
						if ( $catimage == "" )
						{
							$catimage = "AllTopics.gif";
						}
						if ( $catnewshomeimg == "right" )
						{
							$fl = "left";
						}
						if ( $catnewshomeimg == "left" )
						{
							$fl = "right";
						}
						$size2 = @getimagesize( "images/cat/$catimage" );
						$title = "<a href=\"modules.php?name=$module_name&op=viewcat&catid=$catid\" title=\"$cattitle\"><img border=\"0\" src=\"images/cat/$catimage\" width=\"$size2[0]\" style=\"float: $fl\"></a> <a href=\"modules.php?name=$module_name&op=viewst&sid=$sid\">$title</a>";
					}
					else
					{
						$title = "<a href=\"modules.php?name=$module_name&op=viewcat&catid=$catid\">$cattitle:</a> <a href=\"modules.php?name=$module_name&op=viewst&sid=$sid\">$title</a>";
					}
				}
				else
				{
					if ( $catimgnewshome == 1 )
					{
						$catimage = "AllTopics.gif";
						if ( $catnewshomeimg == "right" )
						{
							$fl = "left";
						}
						if ( $catnewshomeimg == "left" )
						{
							$fl = "right";
						}
						$size2 = @getimagesize( "images/cat/$catimage" );
						$title = "<a href=\"modules.php?name=$module_name&op=viewcat&catid=0\"><img border=\"0\" src=\"images/cat/$catimage\" width=\"$size2[0]\" style=\"float: $fl\"></a> <a href=\"modules.php?name=$module_name&op=viewst&sid=$sid\">$title</a>";
					}
					else
					{
						$title = "<a href=\"modules.php?name=$module_name&op=viewst&sid=$sid\">$title</a>";
					}
				}
				if ( $bodytext != "" )
				{
					$story_link = "<a href=\"modules.php?name=$module_name&op=viewst&sid=$sid\"><img src=\"images/more.gif\" width=\"47\" border=\'0\'  alt=\"" . _READMORE . "\" align=\"right\"></a>";
				}
				else
				{
					$story_link = "";
				}
				$com_link = "";
				$tot_hits = "<font class=grey><br>[" . _TOTHITS . ": $counter]</font>"; // Hiển thị số lần đọc - http://mangvn.org 01-03-2008
				if ( defined('IS_ADMMOD') )
				{
					$tot_hits = " [" . _TOTHITS . ": $counter | <a href=\"" . $adminfold . "/" . $adminfile . ".php?op=EditStory&sid=$sid\">" . _EDIT . "</a> | <a href=\"" . $adminfold . "/" . $adminfile . ".php?op=RemoveStory&sid=$sid\">" . _DELETE . "</a>]";
				}

				if ( $images != "" and file_exists("" . $path . "/" . $images . "") )
				{
					if ( file_exists("" . $path . "/small_" . $images . "") )
					{
						$images = "small_" . $images . "";
					}
					$size2 = @getimagesize( "$path/$images" );
					$widthimg = $size2[0];
					if ( $size2[0] > $sizecatnewshomeimg )
					{
						$widthimg = $sizecatnewshomeimg;
					}
					$xtitle = stripslashes( check_html($row2['title'], "nohtml") );
					$story_pic = "<table border=\"0\" width=\"$widthimg\" cellpadding=\"0\" cellspacing=\"3\" align=\"$catnewshomeimg\">\n<tr>\n<td>\n<a href=\"modules.php?name=$module_name&op=viewst&sid=$sid\"><img border=\"0\" src=\"$path/$images\" width=\"$widthimg\" alt=\"$xtitle\"></a></td>\n</tr>\n</table>\n";
				}
				else
				{
					$story_pic = "";
				}
				$notes = "";
				themeindex( $aid, $time, $title, $hometext, $story_pic, $notes, $story_link, $com_link, $tot_hits, $mau );
				$mau++;
			}
			echo @generate_page( $base_url, $all_page, $per_page, $page );
			echo "<br><br>";

		}
		// Tim kiem theo tu khoa
		Opentable();
		echo "<center><form method=\"post\" action=\"modules.php?name=Search\"/>";
		echo "<input type=\"text\" style=\"text-align: center;\" value=\"" . _SEARCH . "\" onfocus=\"if (this.value=='" . _SEARCH . "') {this.value = '';}\" onblur=\"if (this.value==''){this.value='" . _SEARCH . "';}\" maxlength=\"57\" size=\"23\" name=\"query\"/>";
		echo "<input type=\"hidden\" value=\"$module_name\" name=\"modname\">";
		echo "<input type=\"hidden\" style=\"border: 0pt none ;\" value=\"" . _SEARCH . "\">";
		echo "</center></form>";
		Closetable();
		// end
	}
	include ( "footer.php" );
}
// hết phần xem trên trang chính News

// Trình bày phần bản tin liên quan ở cuối mỗi bài (nếu có)
/**
 * bottomblock_tlq()
 * 
 * @param mixed $sid
 * @param mixed $topicid
 * @param mixed $querylang
 * @return
 */
function bottomblock_tlq( $sid, $topicid, $querylang )
{
	global $datafold, $module_name, $prefix, $db, $bgcolor3, $hienthi_tlq;
	$sid = intval( $sid );
	$topicid = intval( $topicid );
	if ( $topicid != 0 )
	{
		$xhienthi_tlq = $hienthi_tlq + 1;
		$resulttopic = $db->sql_query( "select sid, time, title FROM " . $prefix . "_stories where topicid='$topicid' AND sid!='$sid' $querylang ORDER BY sid DESC LIMIT 0,$xhienthi_tlq" );
		if ( $db->sql_numrows($resulttopic) > 0 )
		{
			list( $topictitle ) = $db->sql_fetchrow( $db->sql_query("select topictitle from " . $prefix . "_stories_topic where topicid='$topicid'") );
			echo "<table border=\"0\" cellpadding=\"5\" cellspacing=\"5\" width=\"100%\" bgcolor=\"$bgcolor3\">\n" . "<tr><td><div style=\"margin-bottom: 3px\"><font class=\"topmore\">" . _TINLQ . ": $topictitle</font>\n</div>\n";
			$q = 1;
			while ( $rowtopic = $db->sql_fetchrow($resulttopic) )
			{
				$tsid = intval( $rowtopic['sid'] );
				$ttitle = stripslashes( check_html($rowtopic['title'], "nohtml") );
				$ttime = formatTimestamp( $rowtopic['time'], 2 );
				if ( $q <= $hienthi_tlq )
				{
					echo "<img border=\"0\" src=\"images/arrow2.gif\" width=\"10\" height=\"5\"><a href=\"modules.php?name=News&op=viewst&sid=$tsid\"><font  class=\"indexhometext\">$ttitle</font></a><br>\n"; // http://mangvn.org edited 19.07.2008
				}
				if ( $q == $xhienthi_tlq )
				{
					echo "<div style=\"margin-top: 3px\" align=\"right\"><a href=\"modules.php?name=News&op=viewtop&topicid=$topicid\"><b><u><font  class=\"indexhometext\">" . _READMORE . "</font></u></b></a></div>\n";
				}
				$q++;
			}
			echo "</td>\n</tr>\n</table>\n<br><br>\n";
		}
	}
}

/**
 * bottomblock_ccd()
 * 
 * @param mixed $sid
 * @param mixed $catid
 * @param mixed $querylang
 * @return
 */
function bottomblock_ccd( $sid, $catid, $querylang )
{
	global $hienthi_ccd, $datafold, $module_name, $prefix, $db;
	$sid = intval( $sid );
	$catid = intval( $catid );
	if ( $catid != 0 )
	{
		$xhienthi_ccd = $hienthi_ccd + 1;
		$resultcat = $db->sql_query( "select sid, time, title FROM " . $prefix . "_stories where catid='$catid' AND sid!='$sid' $querylang ORDER BY sid DESC LIMIT 0,$xhienthi_ccd" );
		if ( $db->sql_numrows($resultcat) > 0 )
		{
			echo "<font class=\"topmore\">" . _TINCCD . ":</font>\n<br>\n";
			$c = 1;
			while ( $rowcat = $db->sql_fetchrow($resultcat) )
			{
				$csid = intval( $rowcat['sid'] );
				$ctitle = stripslashes( check_html($rowcat['title'], "nohtml") );
				$ctime = formatTimestamp( $rowcat['time'], 2 );
				if ( $c <= $hienthi_ccd )
				{
					echo "<div style=\"margin-top: 3px; margin-bottom: 3px\"><img border=\"0\" src=\"images/arrow2.gif\" width=\"10\" height=\"5\"><a href=\"modules.php?name=News&op=viewst&sid=$csid\"><font  class=\"indexhometext\">$ctitle</font></a></div>\n";
				}
				if ( $c == $xhienthi_ccd )
				{
					echo "<div style=\"margin-top: 3px\" align=\"right\"><a href=\"modules.php?name=News&op=viewcat&catid=$catid\"  onMouseOut=\"window.status=''; return true;\" onMouseOver=\"window.status='" . _READMORE . "'; return true;\"><b><u><font  class=\"indexhometext\">" . _READMORE . "</font></u></b></a></div>\n";
				}
				$c++;
			}
			echo "<br><hr style=\"border-style: dotted; border-width: 1px\"><br>\n";
		}
	}
}

/**
 * viewimg()
 * 
 * @param mixed $imglink
 * @return
 */
function viewimg( $imglink )
{
	global $datafold, $module_name;
	echo "<html>\n<head>\n" . "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=" . _CHARSET . "\">\n" . "<title>" . _VIEWIMG . "</title>\n</head>\n<body bgcolor=\"#ECF3FB\" topmargin=\"0\" leftmargin=\"0\" rightmargin=\"0\" bottommargin=\"0\">\n" . "<p><a href=\"\" onclick=\"window.close();\"><img border=\"0\" src=\"$imglink\" alt=\"" . _CLOSEWIN . "\"></a></p>\n" . "</body>\n</html>";
}

/**
 * userbar()
 * 
 * @param mixed $sid
 * @param mixed $acomm
 * @param mixed $comments
 * @return
 */
function userbar( $sid, $acomm, $comments )
{
	global $newsfriend, $newssave, $newsprint, $adminfold, $adminfile, $datafold, $module_name, $articlecomm;
	$sid = intval( $sid );
	$test = "";
	if ( (defined('IS_ADMMOD')) || ($newsprint == 1) || ($newsprint == 2 and (defined('IS_USER'))) )
	{
		$test .= "&nbsp;<a href=\"#\" onClick=\"MM_openBrWindow('modules.php?name=$module_name&file=print&sid=$sid','','scrollbars=yes,width=640,height=500')\" onMouseOut=\"window.status=''; return true;\" onMouseOver=\"window.status='" . _PRINTER . "'; return true;\"><img src=\"images/print.gif\" border=\"0\" alt=\"" . _PRINTER . "\" title=\"" . _PRINTER . "\"></a>\n";
	}
	if ( (defined('IS_ADMMOD')) || ($newssave == 1) || ($newssave == 2 and (defined('IS_USER'))) )
	{
		$test .= "&nbsp;<a href=\"modules.php?name=$module_name&file=save&sid=$sid\" onMouseOut=\"window.status=''; return true;\" onMouseOver=\"window.status='" . _SAVE . "'; return true;\"><img src=\"images/save.gif\" border=\"0\" alt=\"" . _SAVE . "\" title=\"" . _SAVE . "\"></a>\n";
	}
	if ( (defined('IS_ADMMOD')) || ($newsfriend == 1) || ($newsfriend == 2 and (defined('IS_USER'))) )
	{
		$test .= "&nbsp;<a href=\"#\" onClick=\"MM_openBrWindow('modules.php?name=$module_name&file=friend&op=FriendSend&sid=$sid','','scrollbars=no,width=400,height=400')\" onMouseOut=\"window.status=''; return true;\" onMouseOver=\"window.status='" . _FRIEND . "'; return true;\"><img src=\"images/friend.gif\" border=\"0\" alt=\"" . _FRIEND . "\" title=\"" . _FRIEND . "\"></a>\n";
	}

	if ( $test != "" )
	{
		echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n<tr>\n<td>\n";
		echo "$test\n";
		echo "</td>\n";
		if ( ($comments != 0) and ((defined('IS_ADMMOD')) || ($articlecomm == 1 and $acomm == 0) || ($articlecomm == 2 and $acomm == 0 and (defined('IS_USER')))) )
		{
			echo "<td align=\"right\"><b><a href=\"modules.php?name=$module_name&op=showcomm&sid=$sid\">" . _READREST . "</a></b> (" . _ALL . ": $comments)</td>";
		}
		echo "</tr></table>\n";
		if ( defined('IS_ADMMOD') )
		{
			echo "<p align=\"right\">[ <a href=\"" . $adminfold . "/" . $adminfile . ".php?op=adminnews\">" . _ADD . "</a> | <a href=\"" . $adminfold . "/" . $adminfile . ".php?op=EditStory&sid=$sid\">" . _EDIT . "</a> | <a href=\"" . $adminfold . "/" . $adminfile . ".php?op=RemoveStory&sid=$sid\">" . _DELETE . "</a> ]</p>\n";
		}
		echo "<hr style=\"border-style: dotted; border-width: 1px\"><br>\n";
	}
}

/**
 * combl_bar()
 * 
 * @param mixed $catid
 * @return
 */
function combl_bar( $catid )
{
	global $nvcat, $nvcat2, $hourdiff, $addnews, $datafold, $prefix, $db, $module_name, $user, $anonpost, $bgcolor2;

	echo "<table border=\"0\" cellpadding=\"3\" cellspacing=\"3\" width=\"100%\" align=\"center\" bgcolor=\"$bgcolor2\">\n<tr>\n";
	if ( (defined('IS_ADMMOD')) || ($addnews == 1) || (($addnews == 2) and (defined('IS_USER'))) )
	{
		$size3 = getimagesize( "images/modules/$module_name/submitnews.gif" );
		echo "<td width=\"75\">\n<a href=\"modules.php?name=Addnews\">\n" . "<img border=\"0\" src=\"images/modules/$module_name/submitnews.gif\" width=\"$size3[0]\" height=\"$size3[1]\" title=\"" . _SUBMITNEWS . "\" alt=\"" . _SUBMITNEWS . "\"></a></td>\n";
	}
	echo "<td align=\"right\">\n" . "<form method=\"POST\" action=\"modules.php?name=$module_name\">\n" . "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n<tr>\n<td><select name=\"day\">\n";
	for ( $i = 1; $i <= 31; $i++ )
	{
		echo "<option value=\"" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "\"";
		if ( $i == date("d", time() + $hourdiff * 60) )
		{
			echo " selected";
		}
		echo ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
	}

	echo "</select></td>\n<td><select name=\"month\">\n";
	for ( $i = 1; $i <= 12; $i++ )
	{
		echo "<option value=\"" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "\"";
		if ( $i == date("m", time() + $hourdiff * 60) )
		{
			echo " selected";
		}
		echo ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
	}
	echo "</select></td>\n<td><select name=\"year\">\n";
	$z = date( "Y", time() + $hourdiff * 60 );
	for ( $i = $z; $i >= ($z - 4); $i-- )
	{
		echo "<option value=\"$i\"";
		if ( $i == $z )
		{
			echo " selected";
		}
		echo ">$i</option>\n";
	}
	echo "</select></td>\n" . "<input type=\"hidden\" name=\"pozit\" value=\"1\">\n" . "<input type=\"hidden\" name=\"catid\" value=\"$catid\">\n" . "<input type=\"hidden\" name=\"op\" value=\"archive\">\n";
	$size3 = @getimagesize( "images/modules/$module_name/archive.gif" );
	echo "<td width=\"$size3[0]\"><input type=\"image\" src=\"images/modules/$module_name/archive.gif\" alt=\"" . _SEARCH . "\" value=\"" . _SEARCH . "\" style=\"border: 0\"></td>\n" . "</tr>\n</table>\n</form>\n</td>\n" . "</tr>\n</table>\n";
	echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"margin-top: 4\" align=\"center\">\n<tr>\n";
	if ( isset($nvcat) )
	{
		$numcatc = sizeof( $nvcat );
	}
	else
	{
		$numcatc = 0;
	}
	if ( $numcatc > 0 )
	{
		echo "<script language=\"JavaScript\">\n" . "<!-- Hide the script from old browsers --\n" . "function JumpToo(form) { \n" . "var myindex=form.catid.selectedIndex;\n" . "if (form.catid.options[myindex].value != \"0\") {\n" . "parent.location=form.catid.options[myindex].value; \n" . "}\n" . "}\n" . "//--> \n" . "</script>\n";
		echo "<td>\n<form method=\"post\">\n" . "<select name=\"catid\" onChange=\"JumpToo(this.form)\"><option value=\"0\">" . _EXPRESSCAT . "</option>\n";
		for ( $b = 0; $b < sizeof($nvcat); $b++ )
		{
			$ecat_inf = explode( "|", $nvcat[$b] );
			if ( $ecat_inf[1] == '0' )
			{
				$ecatid = intval( $ecat_inf[0] );
				$etitle = stripslashes( check_html($ecat_inf[2], "nohtml") );
				echo "<option value=\"modules.php?name=$module_name&op=viewcat&catid=$ecatid\">$etitle</option>\n";
				if ( $ecat_inf[8] != "" )
				{
					$esubcat_inf = explode( ",", $ecat_inf[8] );
					for ( $k = 0; $k < sizeof($esubcat_inf); $k++ )
					{
						if ( $esubcat_inf[$k] != "" )
						{
							$esubcat_inf2 = explode( "|", $nvcat2[intval($esubcat_inf[$k])] );
							$ecatid2 = intval( $esubcat_inf2[0] );
							$etitle2 = stripslashes( check_html($esubcat_inf2[2], "nohtml") );
							$etitle2 = "$etitle &raquo; $etitle2";
							echo "<option value=\"modules.php?name=$module_name&op=viewcat&catid=$ecatid2\">$etitle2</option>\n";
						}
					}
				}
			}
		}
		echo "</select></form></td>\n";
	}
	$size3 = @getimagesize( "images/modules/$module_name/up.gif" );
	echo "<td align=\"center\" width=\"100%\" valign=\"bottom\">\n" . "<a href=\"#top\" onMouseOut=\"window.status=''; return true;\" onMouseOver=\"window.status='" . _TOPPAGE . "'; return true;\"><img border=\"0\" src=\"images/modules/$module_name/up.gif\" width=\"$size3[0]\" height=\"$size3[1]\" alt=\"" . _TOPPAGE . "\"></a></td>\n";
	echo "<form action=\"modules.php?name=Search\" method=\"post\">" . "<td align=\"right\">" . "<input type=\"text\" name=\"query\" size=\"23\" maxLength=\"57\" onblur=\"if (this.value==''){this.value='" . _SEARCH . "';}\" onfocus=\"if (this.value=='" . _SEARCH . "') {this.value = '';}\" value=\"" . _SEARCH . "\" style=\"text-align: center\">" . "</td>" . "<td width=\"19\">";
	echo "<input type=\"image\" src=\"images/modules/$module_name/go.gif\" alt=\"" . _SEARCH . "\" value=\"" . _SEARCH . "\" style=\"border: 0\">" . "</td>" . "</form>";
	echo "</tr>\n</table>\n";
}

/**
 * showcomm()
 * 
 * @param mixed $sid
 * @return
 */
function showcomm( $sid )
{
	global $newspagenum, $articlecomm, $adminfold, $adminfile, $datafold, $prefix, $user_prefix, $db, $module_name, $page_title, $pagenum, $index, $bgcolor2, $bgcolor3;
	$sid = intval( $sid );
	$result = $db->sql_query( "SELECT title, acomm, topicid, catid FROM " . $prefix . "_stories WHERE sid='$sid'" );
	$row = $db->sql_fetchrow( $result );
	$title = stripslashes( check_html($row['title'], "nohtml") );
	$acomm = intval( $row['acomm'] );
	$topicid = intval( $row['topicid'] );
	$catid = intval( $row['catid'] );
	if ( (! defined('IS_ADMMOD')) and (($db->sql_numrows($result) != 1) or ($articlecomm == 0) or ($acomm == 1)) )
	{
		Header( "Location: index.php" );
		exit;
	}
	$page_title = "" . _COMMENTSAR . " | $title";
	$numf = $db->sql_fetchrow( $db->sql_query("SELECT COUNT(*) FROM " . $prefix . "_stories_comments WHERE sid='$sid'") );
	$page = ( isset($_GET['page']) ) ? intval( $_GET['page'] ) : 0;
	$all_page = ( $numf[0] ) ? $numf[0] : 1;
	$per_page = intval( $newspagenum );
	$base_url = "modules.php?name=$module_name&op=showcomm&sid=$sid";
	$result = $db->sql_query( "SELECT * FROM " . $prefix . "_stories_comments WHERE sid='$sid' ORDER BY date desc LIMIT $page,$per_page" );
	include ( "header.php" );
	echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n<tr>\n<td valign=\"top\">" . "<table border=\"0\" cellpadding=\"3\" cellspacing=\"3\" width=\"100%\">\n" . "<tr>\n<td align=\"center\">\n<a name=\"top\"></a>\n<h2>$title\n" . "</td>\n</tr><tr>\n<td align=\"center\"><a href=\"modules.php?name=$module_name&op=viewst&sid=$sid\" onMouseOut=\"window.status=''; return true;\" onMouseOver=\"window.status='" . _ARTICLECONTENT . "'; return true;\">" . "" . _ARTICLECONTENT . "\n<a> | <a href=\"modules.php?name=" . $module_name . "&op=addcomment&sid=" . $sid . "\">" . _COMMENTSQ . "</a>\n</td>\n</tr>\n</table>\n" . "<hr style=\"border-style: dotted; border-width: 1px\">";
	echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
	$a = 0;
	while ( $row = $db->sql_fetchrow($result) )
	{
		$tid = intval( $row['tid'] );
		$send_date = formatTimestamp( $row['date'], 2 );
		$sender_name = $row['name'];
		$sender_email = "Email: <a href=\"mailto:$row[email]\">$row[email]</a>";
		$result2 = $db->sql_query( "SELECT user_id, user_viewemail FROM " . $user_prefix . "_users WHERE username='$sender_name'" );
		$row2 = $db->sql_fetchrow( $result2 );
		$viewmail = $row2['user_viewemail'];
		if ( $db->sql_numrows($result2) == 1 )
		{
			$sender_name = "<a href=\"modules.php?name=Your_Account&op=userinfo&user_id=" . $row2['user_id'] . "\">$sender_name</a>";
			if ( (! defined('IS_ADMMOD')) && ($viewmail == 0) )
			{
				$sender_email = "";
			}
		}
		$sender_host = $row['host_name'];
		$com_text = $row['comment'];
		$ip = "<a title=\"" . _TOP_PAGE . "\" href=\"#top\" onMouseOut=\"window.status=''; return true;\" onMouseOver=\"window.status='" . _TOP_PAGE . "'; return true;\"><font face=\"Arial\" color=\"#DC0312\" size=\"2\"><span style=\"text-decoration: none\">&#9650;</span></font></a>\n";
		$delcomm = "<img border=\"0\" src=\"images/modules/$module_name/comm_element.gif\" width=\"24\" height=\"24\" hspace=\"3\" vspace=\"3\" alt=\"" . _COMMENTSQ . "\" title=\"" . _COMMENTSQ . "\">";
		if ( defined('IS_ADMMOD') )
		{
			$ip = "IP: <a href=\"" . $adminfold . "/" . $adminfile . ".php?op=ConfigureBan&bad_ip=$sender_host\">$sender_host</a>\n";
			$delcomm = "<a href=\"" . $adminfold . "/" . $adminfile . ".php?op=EditStoriesComment&tid=$tid\" title=\"" . _EDIT . "\"><img border=\"0\" src=\"images/modules/$module_name/comm_element.gif\" width=\"24\" height=\"24\" hspace=\"3\" vspace=\"3\"></a>";
		}
		$bgcolor = $bgcolor2;
		if ( $a % 2 == 1 )
		{
			$bgcolor = $bgcolor3;
		}
		echo "<tr>\n<td>\n<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n" . "<tr>\n<td style=\"border-left:2px solid #DC0312\">\n" . "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n" . "<tr>\n<td width=\"30\" style=\"border-top:2px solid #DC0312\">$delcomm</td>\n<td>\n" . "<li type=\"circle\">" . _SENDCOMM . ": $sender_name, $sender_email</li>\n" . "<li type=\"circle\">" . _COMMDATE . ": $send_date</li>\n</td>\n" . "<td align=\"right\">$ip</td>\n</tr>\n</table>\n</td>\n</tr>\n<tr>\n" . "<td style=\"border-left:2px solid #DC0312; border-bottom:2px solid #FFDB29; background-color: $bgcolor\">\n" . "<table border=\"0\" cellpadding=\"3\" cellspacing=\"3\" width=\"100%\">\n" . "<tr>\n<td class=\"indexhometext\">$com_text</td>\n</tr>\n</table>\n" . "</td>\n</tr>\n<tr>\n<td>&nbsp;</td>\n</tr>\n</table>\n</td>\n</tr>\n";
		$a++;
	}
	echo "</table>";
	echo @generate_page( $base_url, $all_page, $per_page, $page );
	echo "<br><br>";
	echo "</td>\n";
	echo "</tr>\n</table>";
	include ( "footer.php" );
}

/**
 * viewst()
 * 
 * @return
 */
function viewst()
{
	global $comblbarstat, $hienthi_ccd, $acomm, $hienthi_tlq, $sizenewsarticleimg, $newsarticleimg, $path, $page_title, $page_title2, $key_words, $header_page_keyword, $datafold, $currentlang, $multilingual, $prefix, $user_prefix, $db, $anonymous, $articlecomm, $module_name, $index, $bgcolor2, $ThemeSel, $nukeurl;
	$sid = ( isset($_GET['sid']) ) ? intval( $_GET['sid'] ) : intval( $_POST['sid'] );
	if ( ! $sid || $sid == 0 )
	{
		Header( "Location: index.php" );
		exit();
	}
	$result = $db->sql_query( "select * FROM " . $prefix . "_stories where sid='$sid' AND (alanguage='$currentlang' OR alanguage='')" );
	if ( $numrows = $db->sql_numrows($result) != 1 )
	{
		Header( "Location: index.php" );
		die();
	}
	$row = $db->sql_fetchrow( $result );
	$catid = intval( $row['catid'] );
	$aid = stripslashes( $row['aid'] );
	$time = formatTimestamp( $row['time'], 2 );
	$title = stripslashes( check_html($row['title'], "nohtml") );
	$hometext = stripslashes( $row['hometext'] );
	$bodytext = stripslashes( $row['bodytext'] );
	$images = $row['images'];
	$imgtext = stripslashes( check_html($row['imgtext'], "nohtml") );
	$notes = stripslashes( $row['notes'] );
	$acomm = intval( $row['acomm'] );
	$comments = stripslashes( $row['comments'] );
	$topicid = intval( $row['topicid'] );
	$source = stripslashes( check_html($row['source'], "nohtml") );

	$db->sql_query( "UPDATE " . $prefix . "_stories SET counter=counter+1 where sid=$sid" );
	$header_page_keyword = "$title $hometext $bodytext $notes";
	$key_words = $title;
	list( $cattitle, $catparentid ) = $db->sql_fetchrow( $db->sql_query("select title, parentid from " . $prefix . "_stories_cat where catid='$catid'") );
	$page_title2 = "<a href=\"modules.php?name=$module_name&op=viewcat&catid=$catid\">$cattitle</a>";
	$page_title = "$cattitle | $title";
	if ( $catparentid != 0 )
	{
		list( $cattitle2 ) = $db->sql_fetchrow( $db->sql_query("select title from " . $prefix . "_stories_cat where catid='$catparentid'") );
		$page_title2 = "<a href=\"modules.php?name=$module_name&op=viewcat&catid=$catparentid\">$cattitle2</a> &raquo; <a href=\"modules.php?name=$module_name&op=viewcat&catid=$catid\">$cattitle</a>";
		$page_title = "$cattitle2 | $cattitle | $title";
	}
	include ( "header.php" );
	if ( $source != "" )
	{
		$source = "(" . _ISTOK . " <i>$source</i>)";
	}
	if ( $images != "" )
	{
		$ximages = "$path/$images";
		$size2 = @getimagesize( "$ximages" );
		if ( file_exists("" . $path . "/small_" . $images . "") )
		{
			$images = "small_" . $images . "";
		}
		if ( $size2[0] > $sizenewsarticleimg )
		{ // hien thi anh dang hilight.javascript - http://mangvn.org
			$story_pic = "<table border=\"0\" width=\"$sizenewsarticleimg\" cellpadding=\"0\" cellspacing=\"3\" align=\"$newsarticleimg\">\n<tr>\n<td>\n
        <script type=\"text/javascript\" src=\"js/highslide/highslide.js\"></script>
        <script type=\"text/javascript\">
        hs.graphicsDir = 'js/highslide/graphics/';
        hs.outlineType = 'rounded-white';
        </script>
        <div><A id=\"thumb1\" class=highslide onclick=\"return hs.expand(this)\" href=\"$ximages\"><img border=\"0\" src=\"$path/$images\" width=\"$sizenewsarticleimg\" alt=\"" . _VIEWIMG . "\" class=\"img-detail\"></a></div>
        </td>\n</tr>\n<tr>\n<td align=\"center\">$imgtext</td>\n</tr>\n</table>\n";
		}
		else
		{
			$story_pic = "<table border=\"0\" width=\"$size2[0]\" cellpadding=\"0\" cellspacing=\"3\" align=\"$newsarticleimg\">\n<tr>\n<td>\n
        <img border=\"0\" src=\"$path/$images\" width=\"$size2[0]\" alt=\"$imgtext\" class=\"img-detail\"></td>\n</tr>\n<tr>\n<td align=\"center\">$imgtext</td>\n</tr>\n</table>\n";
		}
	}
	else
	{
		$story_pic = "";
	}

	echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n<tr>\n<td valign=\"top\" width=\"100%\">";
	themearticle( $aid, $time, $title, $hometext, $bodytext, $notes, $story_pic, $source );
	// add Send to Yahoo - www.mangvn.org - 04-09-2008
	echo "<p align=\"right\">";
	echo "<a href=\"ymsgr:im?+&amp;msg=" . _REDSTORY . ": " . $nukeurl . "modules%2Ephp%3Fname%3D$module_name%26op%3Dviewst%26sid%3D$sid%20\" onMouseOut=\"window.status=''; return true;\" onMouseOver=\"window.status='" . _FRIEND1 . "'; return true;\"><img src=\"images/send_ym.gif\" border=\"0\" alt=\"" . _FRIEND1 . "\" title=\"" . _FRIEND1 . "\"></a>\n";
	echo "<!-- AddThis Button BEGIN -->\n" . "<script type=\"text/javascript\">addthis_pub  = 'mangvn';</script>\n" . "<a href=\"http://www.addthis.com/bookmark.php\" onmouseover=\"return addthis_open(this, '', '[URL]', '[TITLE]')\" onmouseout=\"addthis_close()\" onclick=\"return addthis_sendto()\"><img src=\"".INCLUDE_PATH."images/button1-bm.gif\" width=\"125\" height=\"16\" border=\"0\" alt=\"\" /></a><script type=\"text/javascript\" src=\"http://s7.addthis.com/js/152/addthis_widget.js\"></script>\n" . "<!-- AddThis Button END -->\n";
	echo "</p>\n";
	userbar( $sid, $acomm, $comments );
	//
	//---- the hien comments ----------------
	if ( ! $acomm )
	{
		if ( $articlecomm == 1 or ($articlecomm == 2 and defined('IS_USER')) )
		{
			if ( ! isset($_SESSION['floodtime']) )
			{
				$_SESSION['floodtime'] = 0;
			}
			if ( $comments )
			{
				$all_page = $comments ? $comments : 1;
				$perpage = 15;
				$base_url = "modules.php?name=$module_name&op=viewst&sid=$sid";

				$sql_comment = "SELECT tid, name, email, comment, host_name, MONTH(date) as month, DAYOFMONTH(date) as day, YEAR(date) as year FROM " . $prefix . "_stories_comments WHERE sid='$sid' AND online='1' ORDER BY date DESC LIMIT $page, $perpage";

				$res_comment = $db->sql_query( $sql_comment );
				if ( $db->sql_numrows($res_comment) > 0 )
				{
					echo "<div align='center' class='A_white1' style='padding: 1px'><b>" . _COMCON . "</b></div><br>\n";
					echo "<center><font  class='tieudiem'>(" . _COMCONNO . ")</font></center><br>\n";
					echo "<div>\n";
					echo "<table width='100%' border='1' bordercolor='#C0C0C0' cellpadding='3' style='border-collapse: collapse'>\n";
					$k = $all_page - $page;
					while ( $row_comment = $db->sql_fetchrow($res_comment) )
					{
						echo "<tr>\n";
						echo "<td  bgcolor='#d5df55' align='left' class='tieudiem'><span>" . $k . "&nbsp;-&nbsp;" . stripslashes( trim($row_comment['name']) ) . "</span> | <span><a class='tinmoi' href='mailto:" . stripslashes( trim($row_comment['email']) ) . "'>" . stripslashes( trim($row_comment['email']) ) . "</a></span> | <span>" . intval( $row_comment['day'] ) . "." . intval( $row_comment['month'] ) . "." . intval( $row_comment['year'] ) . "</span></td>";
						echo "</tr>\n";
						echo "<tr>\n";
						echo "<td bgcolor='#d9ffff'>" . stripslashes( trim($row_comment['comment']) ) . "";
						if ( defined('IS_ADMMOD') )
						{
							global $adminfold, $adminfile;
							$ip = ( $row_comment['host_name'] != "" ) ? "IP: " . trim( $row_comment['host_name'] ) . " | " : "";
							echo "<br /><div class=\"tieudiem\" style=\"text-align:right\">" . $ip . "<a class=\"tieudiem\" href=\"" . $adminfold . "/" . $adminfile . ".php?op=EditStoriesComment&amp;tid=" . intval( $row_comment['tid'] ) . "\">" . _EDIT . "</a> | \n";
							echo "<a class=\"tieudiem\" href=\"" . $adminfold . "/" . $adminfile . ".php?op=Commentdel&amp;tid=" . intval( $row_comment['tid'] ) . "\">" . _DELETE . "</a></div>\n";
						}
						echo "</td>\n";
						echo "</tr>\n";
						$k--;
					}
					echo "</table>\n";
					echo "</div>\n";
					if ( $all_page > $perpage )
					{
						echo "<div style='padding-top: 5px'>" . generate_page( $base_url, $all_page, $perpage, $page ) . "</div>\n";
					}
					echo "<div><hr style=\"border-style: dotted; border-width: 1px\"></div>\n";
				}
			}
			//---- form thao luan ------
			echo "<script language=\"Javascript\" type=\"text/javascript\">\n";
			echo "   var cfilter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;\n";
			echo "function checkMail(field){\n";
			echo "   if (cfilter.test(field)) {\n";
			echo "      return true;\n";
			echo "   }\n";
			echo "   return false;\n";
			echo "}\n";
			echo "function checkformempty(form){\n";
			echo "   if(form.postname.value.length < 3 || form.postname.value.length > 50){\n";
			echo "      alert('" . _ERCOM1 . "');\n";
			echo "    form.postname.focus();\n";
			echo "    return false;\n";
			echo "   }\n";
			echo "   if(checkMail(form.postemail.value)==false){\n";
			echo "      alert('" . _ERCOM2 . "');\n";
			echo "    form.postemail.focus();\n";
			echo "    return false;\n";
			echo "   }\n";
			echo "   if(form.postcomment.value.length < 3){\n";
			echo "      alert('" . _ERCOM3 . "');\n";
			echo "    form.postcomment.focus();\n";
			echo "    return false;\n";
			echo "   }\n";
			if ( extension_loaded("gd") )
			{
				echo "   if(form.gfx_check.value.length != 6){\n";
				echo "      alert('" . _SECCODEINCOR . "');\n";
				echo "    form.gfx_check.focus();\n";
				echo "    return false;\n";
				echo "   }\n";
			}
			echo " return true; \n";
			echo "}\n";
			echo "</script>\n";
			//    echo "<div align='center'>\n";
			//    echo "<div class='A_white1' style='padding: 5px'><b>"._COMMTIT.":<br /><font class=\"tieude_vuaphai1\">".$title."</font></b></div>\n";
			echo "<img src=\"images/bl.gif\" alt=\"" . _COMMTIT . "\" border=\"0\">";
			Opentable();
			echo "<form style='padding: 0px;margin: 0px;' action='modules.php?name=$module_name&amp;op=addcomment&amp;sid=$sid' onsubmit='return checkformempty(this);' method='post'>\n";
			echo "<table width='100%' border='0' bordercolor='#C0C0C0' cellpadding='1' cellspacing='2' style='border-collapse: collapse'>\n";
			if ( ! defined('IS_USER') )
			{
				echo "<tr>\n";
				echo "<td width=\"70\" align='center'><b>" . _COMNAME . " <font color=\"#ff0000\">*</font></b></td>\n";
				echo "<td><input type='text' name='postname'  id='postname' size='20' maxlength='50'>&nbsp;(" . _COMNAMENOTE . ")</td>\n";
				echo "</tr>\n";
				echo "<tr>\n";
				echo "<td width=\"70\" align='center'><b>" . _COMMAIL . "</b> <font color=\"#ff0000\">*</font></td>\n";
				echo "<td><input type='text' name='postemail' id='postemail' size='20' maxlength='60'> &nbsp;(" . _COMMAILNOTE . ")</td>\n";
				echo "</tr>\n";
			}
			else
			{
				global $mbrow;
				echo "<tr>\n";
				echo "<td width=\"70\" align='center'><b>" . _COMNAME . " <font color=\"#ff0000\">*</font></b></td>\n";
				echo "<td><input type='hidden' name='postname'  id='postname' value='" . stripslashes( trim($mbrow['username']) ) . "'><b>" . stripslashes( trim($mbrow['username']) ) . "</b></td>\n";
				echo "</tr>\n";
				echo "<tr>\n";
				echo "<td width=\"70\" align='center'><b>" . _COMMAIL . " <font color=\"#ff0000\">*</font></b></td>\n";
				echo "<td><input type='hidden' name='postemail' id='postemail' value='" . stripslashes( trim($mbrow['user_email']) ) . "'><b>" . stripslashes( trim($mbrow['user_email']) ) . "</b></td>\n";
				echo "</tr>\n";
			}
			echo "<tr>\n";
			echo "<td width=\"70\" align='center'><b>" . _COMMENTND . " <font color=\"#ff0000\">*</font></b><BR>(" . _COMCONNOTE . ")</td>\n";
			echo "<td><textarea wrap='virtual' cols='65' rows='11' name='postcomment' id='postcomment'></textarea></td>\n";
			echo "</tr>\n";
			if ( extension_loaded("gd") )
			{
				echo "<tr>\n";
				echo "<td width=\"70\" align='center'><b>" . _SECURITYCODE . "</b></td>\n";
				echo "<td><img width=\"73\" height=\"17\" src='?gfx=gfx' border='1' alt='" . _SECURITYCODE . "' title='" . _SECURITYCODE . "'></td>\n";
				echo "</tr>\n";
				echo "<tr>\n";
				echo "<td width=\"70\" align='center'><b>" . _TYPESECCODE . " <font color=\"#ff0000\">*</font></b></td>\n";
				echo "<td><input type='text' name='gfx_check' id='gfx_check' size='11' maxlength='6'></td>\n";
				echo "</tr>\n";
			}
			echo "<tr>\n";
			echo "<td align='center' colspan='2'>\n";
			echo "<input type='hidden' name='save' value='1'>\n";
			echo "<input type='submit' name='submit' value='" . _COMSUB . "'></td>\n";
			echo "</tr>\n";
			echo "</table>\n</form>\n";
			Closetable();
			echo "</div>\n";
			//---- //form thao luan ----
			echo "<div><hr style=\"border-style: dotted; border-width: 1px\"><br></div>\n";
		}
	}
	//---- //the hien comments --------------


	if ( $multilingual == 1 )
	{
		$querylang = "AND (alanguage='$currentlang' OR alanguage='')";
	}
	else
	{
		$querylang = "";
	}
	if ( $hienthi_tlq > 0 )
	{
		bottomblock_tlq( $sid, $topicid, $querylang );
	}
	if ( $hienthi_ccd > 0 )
	{
		bottomblock_ccd( $sid, $catid, $querylang );
	}
	if ( $comblbarstat )
	{
		combl_bar( $catid );
	}
	echo "</td>\n";
	echo "</tr>\n</table>";
	include ( "footer.php" );
}

// add comment
/**
 * addcomment()
 * 
 * @return
 */
function addcomment()
{
	global $db, $prefix, $module_name, $articlecomm, $client_ip, $commentcheck, $sitekey;
	if ( ! $articlecomm or ($articlecomm == 2 and ! defined('IS_USER')) )
	{
		Header( "Location: modules.php?name=$module_name" );
		die();
	}

	$sid = intval( $_GET['sid'] );
	if ( ! $sid )
	{
		Header( "Location: modules.php?name=$module_name" );
		die();
	}

	$result = $db->sql_query( "SELECT title, acomm FROM " . $prefix . "_stories WHERE sid=$sid" );
	if ( $numrows = $db->sql_numrows($result) != 1 )
	{
		Header( "Location: modules.php?name=$module_name" );
		die();
	}

	$row = $db->sql_fetchrow( $result );
	$acomm = intval( $row['acomm'] );
	if ( $acomm )
	{
		Header( "Location: modules.php?name=$module_name" );
		die();
	}

	$gfx_check = intval( $_POST['gfx_check'] );
	$postname = at_htmlspecialchars( strip_tags(trim($_POST['postname'])) );
	$postemail = strip_tags( trim($_POST['postemail']) );
	if ( defined('IS_USER') )
	{
		global $mbrow;
		$postname = at_htmlspecialchars( trim($mbrow['username']) );
		$postemail = stripslashes( trim($mbrow['user_email']) );
	}
	//   $postcomment = FixQuotes(trim($_POST['postcomment']));
	$postcomment = cheonguoc( nl2brStrict(stripslashes(FixQuotes(trim($_POST['postcomment'])))) );
	$error = "";
	$save = intval( $_POST['save'] );
	if ( $save )
	{
		if ( ! isset($_SESSION['floodtime']) )
		{
			Header( "Location: modules.php?name=$module_name&op=addcomment&sid=$sid" );
			die();
		}
		$floodtime = intval( $_SESSION['floodtime'] );
		if ( time() - $floodtime < 60 )
		{
			$error = _FLOODMESS;
		}
		if ( $error == "" )
		{
			if ( extension_loaded("gd") )
			{
				if ( ! nv_capcha_txt($gfx_check) )
				{
					$error = _SECCODEINCOR;
				}
			}
		}
		if ( $error == "" )
		{
			if ( strlen($postname) < 3 || (strlen($postname) > 50) )
			{
				$error = _ERCOM1;
			} elseif ( ! eregi("^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,6}$", $postemail) )
			{
				$error = _ERCOM2;
			} elseif ( strlen($postcomment) < 3 )
			{
				$error = _ERCOM3;
			}
			else
			{
				$date = date( "Y-m-d H:i:s" );
				$online = ( $commentcheck ) ? 0 : 1;
				$db->sql_query( "INSERT INTO " . $prefix . "_stories_comments VALUES (
            NULL, '$sid', '$date', '$postname', '$postemail', '', '$client_ip', '', '$postcomment', '$online')" );
				if ( ! $commentcheck )
				{
					$db->sql_query( "UPDATE " . $prefix . "_stories SET comments=comments+1 WHERE sid='$sid'" );
				}
				$_SESSION['floodtime'] = time();
				include ( "header.php" );
				echo "<META HTTP-EQUIV=\"refresh\" content=\"2;URL=modules.php?name=$module_name&op=viewst&sid=$sid\">\n";
				echo "<div style='padding: 5px' align='center'><b>" . _COMGOOD . "</b></div>";
				include ( "footer.php" );
				exit();
			}
		}
	}
	if ( ! isset($_SESSION['floodtime']) )
	{
		$_SESSION['floodtime'] = 0;
	}
	$title = ( $error != "" ) ? "<font color=\"#ff0000\">" . $error . "</font>" : _COMMTIT . ':<br />' . stripslashes( check_html($row['title'], "nohtml") );
	include ( "header.php" );
	//---- form thao luan ------
	echo "<script language=\"Javascript\" type=\"text/javascript\">\n";
	echo "   var cfilter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;\n";
	echo "function checkMail(field){\n";
	echo "   if (cfilter.test(field)) {\n";
	echo "      return true;\n";
	echo "   }\n";
	echo "   return false;\n";
	echo "}\n";
	echo "function checkformempty(form){\n";
	echo "   if(form.postname.value.length < 3 || form.postname.value.length > 50){\n";
	echo "      alert('" . _ERCOM1 . "');\n";
	echo "    form.postname.focus();\n";
	echo "    return false;\n";
	echo "   }\n";
	echo "   if(checkMail(form.postemail.value)==false){\n";
	echo "      alert('" . _ERCOM2 . "');\n";
	echo "    form.postemail.focus();\n";
	echo "    return false;\n";
	echo "   }\n";
	echo "   if(form.postcomment.value.length < 3){\n";
	echo "      alert('" . _ERCOM3 . "');\n";
	echo "    form.postcomment.focus();\n";
	echo "    return false;\n";
	echo "   }\n";
	if ( extension_loaded("gd") )
	{
		echo "   if(form.gfx_check.value.length != 6){\n";
		echo "      alert('" . _SECCODEINCOR . "');\n";
		echo "    form.gfx_check.focus();\n";
		echo "    return false;\n";
		echo "   }\n";
	}
	echo " return true; \n";
	echo "}\n";
	echo "</script>\n";
	echo "<div align='center'>\n";
	echo "<div class='A_white1' style='padding: 5px'><b>" . $title . "</b></div><a href=\"modules.php?name=$module_name&amp;op=viewst&amp;sid=$sid\">[" . _VIEWAGAIN . "]</a><br><br>\n";
	Opentable();
	echo "<form style='padding: 0px;margin: 0px;' action='modules.php?name=$module_name&amp;op=addcomment&amp;sid=$sid' onsubmit='return checkformempty(this);' method='post'>\n";
	echo "<table width='100%' border='0' bordercolor='#C0C0C0' cellpadding='1' cellspacing='5' style='border-collapse: collapse'>\n";
	if ( ! defined('IS_USER') )
	{
		echo "<tr>\n";
		echo "<td width=\"70\" align='center'><b>" . _COMNAME . " <font color=\"#ff0000\">*</font></b></td>\n";
		echo "<td><input type='text' name='postname'  id='postname' value='$postname' size='20' maxlength='50'>&nbsp;(" . _COMNAMENOTE . ")</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td width=\"70\" align='center'><b>" . _COMMAIL . "</b> <font color=\"#ff0000\">*</font></td>\n";
		echo "<td><input type='text' name='postemail' id='postemail' value='$postemail' size='20' maxlength='60'> &nbsp;(" . _COMMAILNOTE . ")</td>\n";
		echo "</tr>\n";
	}
	else
	{
		echo "<tr>\n";
		echo "<td width=\"70\" align='center'><b>" . _COMNAME . " <font color=\"#ff0000\">*</font></b></td>\n";
		echo "<td><input type='hidden' name='postname'  id='postname' value='$postname'><b>" . $postname . "</b></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td width=\"70\" align='center'><b>" . _COMMAIL . " <font color=\"#ff0000\">*</font></b></td>\n";
		echo "<td><input type='hidden' name='postemail' id='postemail' value='$postemail'><b>" . $postemail . "</b></td>\n";
		echo "</tr>\n";
	}
	echo "<tr>\n";
	echo "<td width=\"70\" align='center'><b>" . _COMMENTND . "</b><BR>(" . _COMCONNOTE . ")</td>\n";
	echo "<td><textarea wrap='virtual' cols='65' rows='11' name='postcomment' id='postcomment'>" . $postcomment . "</textarea></td>\n";
	echo "</tr>\n";
	if ( extension_loaded("gd") )
	{
		echo "<tr>\n";
		echo "<td width=\"70\" align='center'><b>" . _SECURITYCODE . "</b></td>\n";
		echo "<td><img width=\"73\" height=\"17\" src='?gfx=gfx' border='1' alt='" . _SECURITYCODE . "' title='" . _SECURITYCODE . "'></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td width=\"70\" align='center'><b>" . _TYPESECCODE . " <font color=\"#ff0000\">*</font></b></td>\n";
		echo "<td><input type='text' name='gfx_check' id='gfx_check' size='11' maxlength='6'></td>\n";
		echo "</tr>\n";
	}
	echo "<tr>\n";
	echo "<td align='center' colspan='2'>\n";
	echo "<input type='hidden' name='save' value='1'>\n";
	echo "<input type='submit' name='submit' value='" . _COMSUB . "'></td>\n";
	echo "</tr>\n";
	echo "</table>\n</form>\n";
	Closetable();
	echo "</div>\n";
	//---- //form thao luan ----
	include ( "footer.php" );
}


/**
 * viewcat()
 * 
 * @param mixed $catid
 * @return
 */
function viewcat( $catid )
{
	global $nvcat, $nvcat2, $sizeimgskqa, $page_title, $page_title2, $key_words, $header_page_keyword, $catimgnewshome, $catnewshomeimg, $module_name, $sizecatnewshomeimg, $path, $newspagenum, $adminfold, $adminfile, $db, $prefix, $multilingual, $currentlang, $pagenum, $admin;
	$catid = intval( $catid );
	if ( ! isset($nvcat2[$catid]) || $nvcat2[$catid] == "" )
	{
		Header( "Location: modules.php?name=$module_name" );
		exit;
	}
	$cat_inf = explode( "|", $nvcat2[$catid] );
	$catparentid = intval( $cat_inf[1] );
	$cattitle = stripslashes( check_html($cat_inf[2], "nohtml") );
	$storieshome2 = intval( $cat_inf[6] );
	$page_title = $key_words = $header_page_keyword = $cattitle;
	$page_title2 = "<a href=\"modules.php?name=$module_name&op=viewcat&catid=$catid\">" . $cattitle . "</a>";
	if ( $catparentid != 0 )
	{
		$cat_inf2 = explode( "|", $nvcat2[$catparentid] );
		$mcattitle = stripslashes( check_html($cat_inf2[2], "nohtml") );
		$page_title = "$mcattitle | $cattitle";
		$page_title2 = "<a href=\"modules.php?name=$module_name&op=viewcat&catid=$catparentid\">" . $mcattitle . "</a> &raquo; <a href=\"modules.php?name=$module_name&op=viewcat&catid=$catid\">" . $cattitle . "</a>";
		$key_words = $header_page_keyword = "$mcattitle, $cattitle";
	}
	include ( "header.php" );
	$whe = "WHERE (catid='$catid'";
	$subcat = explode( ",", $cat_inf[8] );
	$numsubcat = sizeof( $subcat );
	if ( $cat_inf[8] == "" ) $numsubcat = 0;
	for ( $i = 0; $i < sizeof($subcat); $i++ )
	{
		if ( $subcat[$i] != "" )
		{
			$whe .= "OR catid='" . intval( $subcat[$i] ) . "'";
		}
	}
	$whe .= ")";
	if ( $storieshome2 != 0 )
	{
		$xstorieshome = $storieshome2;
	}
	else
	{
		$liststhm = array();
		$m = 0;
		for ( $n = 0; $n < sizeof($subcat); $n++ )
		{
			$nvcat2_ar2 = explode( "|", $nvcat2[$subcat[$n]] );
			if ( $nvcat2_ar2[6] != 0 )
			{
				$liststhm[$m] = intval( $nvcat2_ar2[6] );
				$m++;
			}
		}
		rsort( $liststhm );
		$xstorieshome = intval( $liststhm[0] );
	}
	if ( $multilingual == 1 )
	{
		$querylang = "AND (alanguage='$currentlang' OR alanguage='')";
	}
	else
	{
		$querylang = "";
	}
	$numx = $db->sql_fetchrow( $db->sql_query("SELECT COUNT(*) FROM " . $prefix . "_stories WHERE sid='$xstorieshome' $querylang") );
	if ( $numx[0] == 1 )
	{
		$result2 = $db->sql_query( "SELECT * FROM " . $prefix . "_stories WHERE sid='$xstorieshome'" );
	}
	else
	{
		$result2 = $db->sql_query( "SELECT * FROM " . $prefix . "_stories $whe $querylang ORDER BY sid DESC LIMIT 0,1" );
	}
	if ( $db->sql_numrows($result2) != 0 )
	{
		$row2 = $db->sql_fetchrow( $result2 );
		$sid_st = intval( $row2['sid'] );
		$title_st = "<a href=\"modules.php?name=News&amp;op=viewst&amp;sid=$sid_st\">" . stripslashes( check_html($row2['title'], "nohtml") ) . "</a>";
		$hometext_st = stripslashes( $row2['hometext'] );
		$image_st = $row2['images'];
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
			$image_st01 = "<table border=\"0\" width=\"$widthimg\" cellpadding=\"0\" cellspacing=\"3\" align=\"$catnewshomeimg\">\n<tr>\n<td>\n<a href=\"modules.php?name=News&amp;op=viewst&amp;sid=$sid_st\" title=\"" . stripslashes( check_html($row2['title'], "nohtml") ) . "\"><img border=\"0\" src=\"$path/$image_st\" width=\"$widthimg\"></a></td>\n</tr>\n</table>\n";
		}
		else
		{
			$image_st01 = "";
		}
		$story_link = "<a href=\"modules.php?name=News&amp;op=viewst&amp;sid=$sid_st\"><img src='images/more.gif' border='0'  alt=\"" . _READMORE . "\" align=\"right\"></a>";
		$numf = $db->sql_fetchrow( $db->sql_query("SELECT COUNT(*) FROM " . $prefix . "_stories WHERE catid='$catid' AND sid!='$sid_st' $querylang") );
		$page = ( isset($_GET['page']) ) ? intval( $_GET['page'] ) : 0;
		$all_page = ( $numf[0] ) ? $numf[0] : 1;
		$per_page = intval( $newspagenum );
		$base_url = "modules.php?name=$module_name&op=viewcat&catid=$catid";
		if ( $page == 0 ) themenewsst( $title_st, $image_st01, $hometext_st, $story_link );

		echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n<tr>\n<td valign=\"top\">";
		$a = 0;
		if ( $numsubcat > 0 and $numf[0] > 0 && $page == 0 )
		{
			Opentable();
			echo "<table border=\"0\" cellpadding=\"2\" style=\"border-collapse: collapse\" width=\"100%\">\n";
		}
		$result = $db->sql_query( "SELECT * FROM " . $prefix . "_stories WHERE catid='$catid' AND sid!='$sid_st' $querylang ORDER BY sid DESC LIMIT $page,$per_page" );
		while ( $row = $db->sql_fetchrow($result) )
		{
			$sid = intval( $row['sid'] );
			$aid = stripslashes( $row['aid'] );
			$time = formatTimestamp( $row['time'], 2 );
			$title = stripslashes( check_html($row['title'], "nohtml") );
			$hometext = stripslashes( $row['hometext'] );
			$bodytext = stripslashes( $row['bodytext'] );
			$images = $row['images'];
			$imgtext = stripslashes( check_html($row['imgtext'], "nohtml") );
			$notes = stripslashes( $row['notes'] );
			$acomm = intval( $row['acomm'] );
			$counter = intval( $row['counter'] );
			$comments = stripslashes( $row['comments'] );
			$source = stripslashes( check_html($row['source'], "nohtml") );
			$title = "<a href=\"modules.php?name=News&op=viewst&sid=$sid\">$title</a>";
			$story_link = "";
			if ( $bodytext != "" )
			{
				$story_link = "<a href=\"modules.php?name=News&op=viewst&sid=$sid\"><img src='images/more.gif' border='0'  alt=\"" . _READMORE . "\" align=\"right\"></a>";
			}
			$com_link = "";
			$tot_hits = "<font class=grey><br>[" . _TOTHITS . ": $counter]</font>"; // Hiển thị số lần đọc - http://mangvn.org 03-03-2008
			if ( defined('IS_ADMMOD') )
			{
				$com_link .= " (" . _COMMENTSQ . ": $comments  | ";
				$tot_hits = "" . _TOTHITS . ": $counter | <a href=\"" . $adminfold . "/" . $adminfile . ".php?op=EditStory&sid=$sid\">" . _EDIT . "</a> | <a href=\"" . $adminfold . "/" . $adminfile . ".php?op=RemoveStory&sid=$sid\">" . _DELETE . "</a>)";
			}
			$story_pic = "";
			if ( $images != "" and file_exists("" . $path . "/" . $images . "") )
			{
				if ( file_exists("" . $path . "/small_" . $images . "") )
				{
					$images = "small_" . $images . "";
				}
				$size2 = @getimagesize( "$path/$images" );
				$widthimg = $size2[0];
				if ( $size2[0] > $sizecatnewshomeimg )
				{
					$widthimg = $sizecatnewshomeimg;
				}
				$story_pic = "<table border=\"0\" width=\"$widthimg\" cellpadding=\"0\" cellspacing=\"3\" align=\"$catnewshomeimg\">\n<tr>\n<td>\n<a href=\"modules.php?name=$module_name&op=viewst&sid=$sid\"><img border=\"0\" src=\"$path/$images\" width=\"$widthimg\"></a></td>\n</tr>\n</table>\n";
			}
			$notes = "";
			if ( $numsubcat > 0 and $numf[0] > 0 && $page == 0 )
			{
				echo "<tr>\n";
				echo "<td width=\"7\">\n";
				echo "<img border=\"0\" src=\"images/dot.gif\" width=\"7\" height=\"10\"></td>\n";
				echo "<td><b>$title</b></td>\n";
				echo "<td width=\"120\" align=\"right\" class=grey>$time</td>\n";
				echo "</tr>\n";
			}
			if ( $numf[0] > 0 and (($numsubcat > 0 and $page != 0) || $numsubcat == 0) )
			{
				themeindex( $aid, $time, $title, $hometext, $story_pic, $notes, $story_link, $com_link, $tot_hits, $a );
				$a++;
			}
		}
		if ( $numsubcat > 0 and $numf[0] > 0 && $page == 0 )
		{
			echo "</table>\n";
			CloseTable();
		}
		echo "<p align=\"right\">" . @generate_page( $base_url, $all_page, $per_page, $page ) . "</p>";
		if ( $numsubcat > 0 && $page == 0 )
		{
			$mau = 0;
			for ( $v = 0; $v < sizeof($subcat); $v++ )
			{
				if ( $nvcat2[$subcat[$v]] != "" )
				{
					$subcat_inf = explode( "|", $nvcat2[$subcat[$v]] );
					$cat_id = intval( $subcat_inf[0] );
					$cat_title = stripslashes( check_html($subcat_inf[2], "nohtml") );
					$linkshome = intval( $subcat_inf[7] );
					$storieshome = intval( $subcat_inf[6] );
					$catimage = $subcat_inf[4];
					if ( $storieshome != 0 and $storieshome != $sid_st )
					{
						$result3 = $db->sql_query( "SELECT * FROM " . $prefix . "_stories WHERE sid='$storieshome'" );
					}
					else
					{
						$result3 = $db->sql_query( "SELECT * FROM " . $prefix . "_stories WHERE catid='$cat_id' AND sid!='$sid_st' $querylang ORDER BY sid DESC LIMIT 0,1" );
					}
					if ( $db->sql_numrows($result3) != 0 )
					{
						$cat_name = "<a href=\"modules.php?name=$module_name&op=viewcat&catid=$cat_id\" class=\"A_white\">$cat_title</a>";
						categoryname( $cat_name );
						$row3 = $db->sql_fetchrow( $result3 );
						$sid3 = intval( $row3['sid'] );
						$aid3 = stripslashes( $row3['aid'] );
						$title3 = stripslashes( check_html($row3['title'], "nohtml") );
						$time3 = formatTimestamp( $row3['time'], 2 );
						$hometext3 = stripslashes( $row3['hometext'] );
						$bodytext3 = stripslashes( $row3['bodytext'] );
						$images3 = $row3['images'];
						$comments3 = stripslashes( $row3['comments'] );
						$counter3 = intval( $row3['counter'] );
						$notes3 = stripslashes( $row3['notes'] );
						$acomm3 = intval( $row3['acomm'] );
						$imgtext3 = stripslashes( check_html($row3['imgtext'], "nohtml") );
						$source3 = stripslashes( check_html($row3['source'], "nohtml") );
						$topicid3 = intval( $row3['topicid'] );
						if ( $catimgnewshome == 1 )
						{
							if ( $catimage == "" )
							{
								$catimage = "AllTopics.gif";
							}
							if ( $catnewshomeimg == "right" )
							{
								$fl = "left";
							}
							if ( $catnewshomeimg == "left" )
							{
								$fl = "right";
							}
							$size2 = @getimagesize( "images/cat/$catimage" );
							$title3 = "<a href=\"modules.php?name=$module_name&op=viewcat&catid=$cat_id\" title=\"$cat_title\"><img border=\"0\" src=\"images/cat/$catimage\" width=\"$size2[0]\" style=\"float: $fl\"></a> <a href=\"modules.php?name=$module_name&op=viewst&sid=$sid3\">$title3</a>";
						}
						else
						{
							$title3 = "<a href=\"modules.php?name=$module_name&op=viewst&sid=$sid3\">$title3</a>\n";
						}
						$story_link3 = "";
						if ( $bodytext3 != "" )
						{
							$story_link3 = "<a href=\"modules.php?name=$module_name&op=viewst&sid=$sid3\"><img src='images/more.gif' border='0' width=\"47\" height=\"9\" alt=\"" . _READMORE . "\" align=\"right\"></a>";
						}
						$com_link3 = "";
						$tot_hits3 = "<font class=grey><br>[" . _TOTHITS . ": $counter3]</font>"; // Hiển thị số lần đọc - http://mangvn.org 03-03-2008
						if ( defined('IS_ADMMOD') )
						{
							$com_link3 .= " (" . _COMMENTSQ . ": $comments3  | ";
							$tot_hits3 = "" . _TOTHITS . ": $counter3 | <a href=\"" . $adminfold . "/" . $adminfile . ".php?op=EditStory&sid=$sid3\">" . _EDIT . "</a> | <a href=\"" . $adminfold . "/" . $adminfile . ".php?op=RemoveStory&sid=$sid3\">" . _DELETE . "</a>)";
						}
						$story_pic3 = "";
						if ( $images3 != "" and file_exists("" . $path . "/" . $images3 . "") )
						{
							if ( file_exists("" . $path . "/small_" . $images3 . "") )
							{
								$images3 = "small_" . $images3 . "";
							}
							$size2 = @getimagesize( "$path/$images3" );
							$widthimg = $size2[0];
							if ( $size2[0] > $sizecatnewshomeimg )
							{
								$widthimg = $sizecatnewshomeimg;
							}
							$story_pic3 = "<table border=\"0\" width=\"$widthimg\" cellpadding=\"0\" cellspacing=\"3\" align=\"$catnewshomeimg\">\n<tr>\n<td>\n<a href=\"modules.php?name=$module_name&op=viewst&sid=$sid3\"><img border=\"0\" src=\"$path/$images3\" width=\"$widthimg\"></a></td>\n</tr>\n</table>\n";
						}
						$notes3 = "";
						$result4 = $db->sql_query( "SELECT sid, time, title FROM " . $prefix . "_stories WHERE catid='$cat_id' AND sid!='$sid3' $querylang ORDER BY sid DESC LIMIT $linkshome3" );
						if ( $db->sql_numrows($result4) != 0 )
						{
							$notes3 .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\"><tr><td style=\"text-align: justify\" valign=\"top\">\n";
							$notes3 .= "<p class=topmore style=\"margin-top: 5px; margin-bottom: 5px\">" . _MORENEWS2 . "</p>";
							while ( $row4 = $db->sql_fetchrow($result4) )
							{
								$sid4 = intval( $row4['sid'] );
								$title4 = stripslashes( check_html($row4['title'], "nohtml") );
								$time4 = formatTimestamp( $row4['time'], 2 );
								$notes3 .= "<img src='images/modules/$module_name/arrow3.gif' border='0' width=\"10\"><a href='modules.php?name=$module_name&op=viewst&sid=$sid4' class=indexlink>$title4</a>  <font class=grey>[$time4]</font><br>";
							}
							$notes3 .= "</td></tr></table>";
						}
						themeindex( $aid3, $time3, $title3, $hometext3, $story_pic3, $notes3, $story_link3, $com_link3, $tot_hits3, $mau );
						$mau++;
					}
				}
			}
		}
		echo "</td>\n</tr>\n</table>";
	}
	include ( "footer.php" );
}

/**
 * viewtop()
 * 
 * @param mixed $topicid
 * @return
 */
function viewtop( $topicid )
{
	global $catnewshomeimg, $module_name, $sizecatnewshomeimg, $path, $newspagenum, $adminfold, $adminfile, $db, $prefix, $time, $multilingual, $currentlang, $pagenum, $admin;
	$topicid = intval( $topicid );
	if ( $topicid == 0 )
	{
		Header( "Location: modules.php?name=$module_name" );
		exit;
	}
	if ( $multilingual == 1 )
	{
		$querylang = "AND (alanguage='$currentlang' OR alanguage='')";
	}
	else
	{
		$querylang = "";
	}
	$numf = $db->sql_fetchrow( $db->sql_query("SELECT COUNT(*) FROM " . $prefix . "_stories WHERE topicid='$topicid' $querylang") );
	$page = ( isset($_GET['page']) ) ? intval( $_GET['page'] ) : 0;
	$all_page = ( $numf[0] ) ? $numf[0] : 1;
	$per_page = intval( $newspagenum );
	$base_url = "modules.php?name=$module_name&op=viewtop&topicid=$topicid";
	include ( "header.php" );
	echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n<tr>\n<td valign=\"top\">";
	list( $topictitle ) = $db->sql_fetchrow( $db->sql_query("select topictitle from " . $prefix . "_stories_topic where topicid='$topicid'") );
	echo "<center><b>" . _TINLQ . ": $topictitle</b></center><br>";
	$result = $db->sql_query( "SELECT * FROM " . $prefix . "_stories WHERE topicid='$topicid' $querylang ORDER BY sid DESC LIMIT $page,$per_page" );
	$a = 0;
	while ( $row = $db->sql_fetchrow($result) )
	{
		$sid = intval( $row['sid'] );
		$aid = stripslashes( $row['aid'] );
		$time = formatTimestamp( $row['time'], 2 );
		$title = stripslashes( check_html($row['title'], "nohtml") );
		$hometext = stripslashes( $row['hometext'] );
		$bodytext = stripslashes( $row['bodytext'] );
		$images = $row['images'];
		$imgtext = stripslashes( check_html($row['imgtext'], "nohtml") );
		$notes = stripslashes( $row['notes'] );
		$acomm = intval( $row['acomm'] );
		$counter = intval( $row['counter'] );
		$comments = stripslashes( $row['comments'] );
		$source = stripslashes( check_html($row['source'], "nohtml") );
		$title = "<a href=\"modules.php?name=News&op=viewst&sid=$sid\">$title</a>";
		$story_link = "";
		if ( $bodytext != "" )
		{
			$story_link = "<a href=\"modules.php?name=News&op=viewst&sid=$sid\"><img src='images/more.gif' border='0'  alt=\"" . _READMORE . "\" align=\"right\"></a>";
		}
		$com_link = "";
		if ( defined('IS_ADMMOD') )
		{
			$com_link .= " (" . _COMMENTSQ . ": $comments  | ";
			$tot_hits = "" . _TOTHITS . ": $counter | <a href=\"" . $adminfold . "/" . $adminfile . ".php?op=EditStory&sid=$sid\">" . _EDIT . "</a> | <a href=\"" . $adminfold . "/" . $adminfile . ".php?op=RemoveStory&sid=$sid\">" . _DELETE . "</a>)";
		}
		$story_pic = "";
		if ( $images != "" and file_exists("" . $path . "/" . $images . "") )
		{
			if ( file_exists("" . $path . "/small_" . $images . "") )
			{
				$images = "small_" . $images . "";
			}
			$size2 = @getimagesize( "$path/$images" );
			$widthimg = $size2[0];
			if ( $size2[0] > $sizecatnewshomeimg )
			{
				$widthimg = $sizecatnewshomeimg;
			}
			$xtitle = stripslashes( check_html($row2['title'], "nohtml") );
			$story_pic = "<table border=\"0\" width=\"$widthimg\" cellpadding=\"0\" cellspacing=\"3\" align=\"$catnewshomeimg\">\n<tr>\n<td>\n<a href=\"modules.php?name=$module_name&op=viewst&sid=$sid\"><img border=\"0\" src=\"$path/$images\" width=\"$widthimg\" alt=\"$xtitle\"></a></td>\n</tr>\n<tr>\n<td align=\"center\">$imgtext</td>\n</tr>\n</table>\n";
		}
		$notes = "";
		themeindex( $aid, $time, $title, $hometext, $story_pic, $notes, $story_link, $com_link, $tot_hits, $a );
		$a++;
	}
	echo @generate_page( $base_url, $all_page, $per_page, $page );
	echo "</td>\n";
	echo "</tr>\n</table>";
	include ( "footer.php" );
}

/**
 * archive()
 * 
 * @param mixed $catid
 * @param mixed $pozit
 * @param mixed $day
 * @param mixed $month
 * @param mixed $year
 * @return
 */
function archive( $catid, $pozit, $day, $month, $year )
{
	global $module_name, $hourdiff, $sizecatnewshomeimg, $path, $adminfold, $adminfile, $catnewshomeimg, $newspagenum, $catimgnewshome, $db, $prefix, $multilingual, $currentlang;
	$catid = intval( $catid );
	if ( ($pozit > 3) || (! $day) || (strlen($day) > 2) || (! $month) || (strlen($month) > 2) || (! $year) || (strlen($year) > 4) || (strlen($catid) > 4) )
	{
		Header( "Location: modules.php?name=$module_name" );
		exit;
	}

	if ( $catid != 0 )
	{
		$c = "catid='$catid' and";
		$v = "&catid=$catid&pozit=$pozit&day=$day&month=$month&year=$year";
	}
	else
	{
		$c = "";
		$v = "&pozit=$pozit&day=$day&month=$month&year=$year";
	}
	if ( $pozit == 1 )
	{
		$pz = "time <= '$year-$month-$day 23:59:59'";
		$pzs = "" . _DATENEWS . "";
	} elseif ( $pozit == 2 )
	{
		$pz = "time >= '$year-$month-$day 00:00:00'";
		$pzs = "" . _DATENEWS2 . "";
	}
	else
	{
		$pz = "time >= '$year-$month-$day 00:00:00' and time <= '$year-$month-$day 23:59:59'";
		$pzs = "" . _DATENEWS3 . "";
	}
	$p = "$c $pz";
	if ( $multilingual == 1 )
	{
		$querylang = "AND (alanguage='$currentlang' OR alanguage='')";
	}
	else
	{
		$querylang = "";
	}
	$numf = $db->sql_fetchrow( $db->sql_query("SELECT COUNT(*) FROM " . $prefix . "_stories WHERE $p $querylang") );
	$page = ( isset($_GET['page']) ) ? intval( $_GET['page'] ) : 0;
	$all_page = ( $numf[0] ) ? $numf[0] : 1;
	$per_page = intval( $newspagenum );
	$base_url = "modules.php?name=$module_name&op=viewtop&topicid=$topicid";
	$result = $db->sql_query( "SELECT * FROM " . $prefix . "_stories WHERE $p $querylang order by sid DESC LIMIT $page,$per_page" );
	include ( "header.php" );
	echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n<tr>\n<td valign=\"top\">";
	$numrows = $db->sql_numrows( $result );
	if ( $numrows == 0 )
	{
		OpenTable();
		echo "<center><br><br><b>" . _NONEWSDATE . "!</b><br><br></center>";
		CloseTable();
	}
	else
	{
		OpenTable();
		if ( ($catid != '') and ($catid != 0) )
		{
			$result_m = $db->sql_query( "SELECT title FROM " . $prefix . "_stories_cat WHERE catid='$catid'" );
			$row_m = $db->sql_fetchrow( $result_m );
			$title_m = stripslashes( check_html($row_m['title'], "nohtml") );
			echo "<center>" . _NEWSDATE . " <b>$title_m</b> $pzs: $day.$month.$year</center>";
		}
		else
		{
			echo "<center><b>" . _NEWSDATE2 . " $pzs: $day.$month.$year</b></center>";
		}
		CloseTable();
		echo "<br>";
		$a = 0;
		while ( $row = $db->sql_fetchrow($result) )
		{
			$sid = intval( $row['sid'] );
			$catid = intval( $row['catid'] );
			$aid = stripslashes( $row['aid'] );
			$title = stripslashes( check_html($row['title'], "nohtml") );
			$time = formatTimestamp( $row['time'] );
			$hometext = stripslashes( $row['hometext'] );
			$bodytext = stripslashes( $row['bodytext'] );
			$images = $row['images'];
			$imgtext = stripslashes( check_html($row['imgtext'], "nohtml") );
			$comments = stripslashes( $row['comments'] );
			$counter = intval( $row['counter'] );
			$notes = stripslashes( $row['notes'] );
			$acomm = intval( $row['acomm'] );
			if ( $catid > 0 )
			{
				$result2 = $db->sql_query( "SELECT title, catimage FROM " . $prefix . "_stories_cat WHERE catid='$catid'" );
				$row2 = $db->sql_fetchrow( $result2 );
				$cattitle = stripslashes( check_html($row2['title'], "nohtml") );
				$catimage = $catimage = $row2['catimage'];
				if ( $catimgnewshome == 1 )
				{
					if ( $catimage == "" )
					{
						$catimage = "AllTopics.gif";
					}
					if ( $catnewshomeimg == "right" )
					{
						$fl = "left";
					}
					if ( $catnewshomeimg == "left" )
					{
						$fl = "right";
					}
					$size2 = @getimagesize( "images/cat/$catimage" );
					$title = "<a href=\"modules.php?name=News&op=viewcat&catid=$catid\" title=\"$cattitle\"><img border=\"0\" src=\"images/cat/$catimage\" width=\"$size2[0]\" style=\"float: $fl\"></a> <a href=\"modules.php?name=News&op=viewst&sid=$sid\">$title</a>";
				}
				else
				{
					$title = "<a href=\"modules.php?name=News&op=viewcat&catid=$catid\">$cattitle</a>: <a href=\"modules.php?name=News&op=viewst&sid=$sid\">$title</a>";
				}
			}
			$story_link = "";
			if ( $bodytext != "" )
			{
				$story_link = "<a href=\"modules.php?name=News&op=viewst&sid=$sid\"><img src='images/more.gif' border='0'  alt=\"" . _READMORE . "\" style=\"float: right\"></a>";
			}
			$print = "";
			$com_link = "";
			$tot_hits = " (" . _COMMENTSQ . ": $comments  | " . _TOTHITS . ": $counter)";
			if ( defined('IS_ADMMOD') )
			{
				$com_link .= " (" . _COMMENTSQ . ": $comments  | ";
				$tot_hits = "" . _TOTHITS . ": $counter | <a href=\"" . $adminfold . "/" . $adminfile . ".php?op=EditStory&sid=$sid\">" . _EDIT . "</a> | <a href=\"" . $adminfold . "/" . $adminfile . ".php?op=RemoveStory&sid=$sid\">" . _DELETE . "</a>)";
			}

			if ( $images != "" and file_exists("" . $path . "/" . $images . "") )
			{
				if ( file_exists("" . $path . "/small_" . $images . "") )
				{
					$images = "small_" . $images . "";
				}
				$size2 = @getimagesize( "$path/$images" );
				$widthimg = $size2[0];
				if ( $size2[0] > $sizecatnewshomeimg )
				{
					$widthimg = $sizecatnewshomeimg;
				}
				$xtitle = stripslashes( check_html($row['title'], "nohtml") );
				$story_pic = "<table border=\"0\" width=\"$widthimg\" cellpadding=\"0\" cellspacing=\"3\" align=\"$catnewshomeimg\">\n<tr>\n<td>\n<a href=\"modules.php?name=$module_name&op=viewst&sid=$sid\"><img border=\"0\" src=\"$path/$images\" width=\"$widthimg\" alt=\"$xtitle\"></a></td>\n</tr>\n<tr>\n<td align=\"center\">$imgtext</td>\n</tr>\n</table>\n";
			}
			else
			{
				$story_pic = "";
			}
			$notes = "";
			themeindex( $aid, $time, $title, $hometext, $story_pic, $notes, $story_link, $com_link, $tot_hits, $a );
			$a++;
		}
		echo @generate_page( $base_url, $all_page, $per_page, $page );
		echo "<br><br>";
	}
	echo "<br>";
	OpenTable();
	echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" align=\"center\">\n" . "<form method=\"POST\" action=\"modules.php?name=$module_name\">\n" . "<tr>\n<td align=\"center\"><b>" . _POISK . "</b>\n<br><br><select name=\"pozit\">\n" . "<option name=\"pozit\" value=\"1\" selected>" . _POZIT1 . "</option>\n" . "<option name=\"pozit\" value=\"2\">" . _POZIT2 . "</option>\n" . "<option name=\"pozit\" value=\"3\">" . _POZIT3 . "</option>\n" . "</select> <select name=\"day\">\n";
	for ( $i = 1; $i <= 31; $i++ )
	{
		echo "<option value=\"" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "\"";
		if ( $i == date("d", time() + $hourdiff * 60) )
		{
			echo " selected";
		}
		echo ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
	}
	echo "</select>\n<select name=\"month\">\n";
	for ( $i = 1; $i <= 12; $i++ )
	{
		echo "<option value=\"" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "\"";
		if ( $i == date("m", time() + $hourdiff * 60) )
		{
			echo " selected";
		}
		echo ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
	}
	echo "</select>\n<select name=\"year\">\n";
	$z = date( "Y", time() + $hourdiff * 60 );
	for ( $i = $z; $i >= $z - 4; $i-- )
	{
		echo "<option value=\"$i\"";
		if ( $i == $z )
		{
			echo " selected";
		}
		echo ">$i</option>\n";
	}
	echo "</select>\n<br>\n<select name=\"catid\">\n";
	echo "<option name=\"catid\" value=\"\">" . _ALLCATEGORIES . "</option>\n";
	$sql = "SELECT catid, parentid, title FROM " . $prefix . "_stories_cat order by parentid, weight";
	$result = $db->sql_query( $sql );
	while ( $row = $db->sql_fetchrow($result) )
	{
		$catid = $row[catid];
		$title = $row[title];
		$parentid = $row['parentid'];
		if ( $parentid != 0 )
		{
			list( $ptitle ) = $db->sql_fetchrow( $db->sql_query("select title from " . $prefix . "_stories_cat where catid='$parentid'") );
			$title = "$ptitle &raquo; $title";
		}
		echo "<option name=\"catid\" value=\"$catid\">$title</option>\n";
	}
	echo "</select>\n<input type=\"hidden\" name=\"op\" value=\"archive\"><input type=\"submit\" value=\"" . _SEARCH . "\"></td>\n" . "</tr>\n</form>\n</table>\n";
	CloseTable();
	echo "</td>\n";
	echo "</tr>\n</table>";
	include ( "footer.php" );
}

switch ( $op )
{

	case "addcomment":
		addcomment( $sid, $postname, $postemail, $postcomment );
		break;

	case "viewst":
		viewst();
		break;

	case "showcomm":
		showcomm( $sid );
		break;

	case "viewcat":
		viewcat( $catid );
		break;

	case "viewtop":
		viewtop( $topicid );
		break;

	case "archive":
		archive( $catid, $pozit, $day, $month, $year );
		break;

	default:
		main();
		break;

}

?>