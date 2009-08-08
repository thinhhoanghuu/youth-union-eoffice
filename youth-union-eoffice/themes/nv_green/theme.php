<?php
/*
 * @Program:	NukeViet CMS v2.0 RC1
 * @File name: 	Theme Nv_silver
 * @Author: 	Boder - Nguyen Minh Giap
 * @Version: 	1.0
 * @Date: 		13.06.2009
 * @Website: 	www.nukeviet.vn
 * @Copyright: 	(C) 2009
 * @License: 	http://opensource.org/licenses/gpl-license.php GNU Public License
 */

/******/
/* Những gam màu chủ đạo */
/******/

$bgcolor1 = "#ffffff";
$bgcolor2 = "#DBDEE3";
$bgcolor3 = "#E1E4E8";
$bgcolor4 = "#246494";
$textcolor1 = "#000000";
$textcolor2 = "#000000";

/******/
/* Xác lập Mở và Đóng bảng */
/******/

function OpenTable() {
    echo "<table border=\"1\" width=\"100%\" cellspacing=\"0\" cellpadding=\"5\" bgcolor=\"#DCEAFA\" style=\"border-collapse: collapse\" bordercolor=\"#BBBBBB\"><tr><td>";
}

function CloseTable() {
    echo"</td></tr></table>\n";
}

function OpenTable2() {
    global $bgcolor1, $bgcolor2;
    echo "<table border=\"0\" cellspacing=\"1\" cellpadding=\"0\" bgcolor=\"$bgcolor2\" align=\"center\"><tr><td>\n";
    echo "<table border=\"0\" cellspacing=\"1\" cellpadding=\"10\" bgcolor=\"$bgcolor1\"><tr><td>\n";
}

function CloseTable2() {
    echo "</td></tr></table></td></tr></table>\n";
}

/******/
/* Thể hiện tên module hiện hành */
/******/

function mod_title() {
    global $module_name, $adminfile, $home, $module_title, $page_title2, $currentlang,$ThemeSel;
    if(defined('NV_ADMIN')) { $tentrang =  "<a href=\"".$adminfile.".php\">".$module_title."</a>"; }
    elseif($home==1) { $tentrang =  "<a href=\"index.php\">".$module_title."</a>"; }
    else {
    	$tentrang = "<a href=\"modules.php?name=$module_name\">$module_title</a>";
    	if($page_title2!="") $tentrang = "$tentrang &raquo; $page_title2";
    }//END
echo "<table border=\"0\" width=\"100%\" cellpadding=\"3\" style=\"border-collapse: collapse\">\n";
echo "<tr>\n";
echo "<td><img border=\"0\" src=\"".INCLUDE_PATH."themes/".$ThemeSel."/images/".$currentlang."/mod_titl.gif\" width=\"8\" height=\"9\"> <b>$tentrang</b></td>\n";
echo "<td align=\"right\">".viewtime(time(), 2)."</td>\n";
echo "</tr>\n";
echo "</table>\n";
}

/******/
/* Thể hiện tiêu đề */
/******/

function title($text) {
    OpenTable();
    echo "<center><font class=\"title\"><b>$text</b></font></center>";
    CloseTable();
    echo "<br>";
}

/******/
/* Thể hiện tên chủ đề */
/******/

function categoryname($cat_name) {
  global $currentlang,$ThemeSel;
echo "<table border=\"0\" width=\"100%\" cellpadding=\"0\" style=\"border-collapse: collapse\">\n";
echo "<tr>\n";
echo "<td height=\"5\">\n";
echo "   <TR><TD style=\"BORDER-RIGHT: #c6c6c6 1px solid; BORDER-TOP: #c6c6c6 0px solid; BACKGROUND-IMAGE: url(".INCLUDE_PATH."themes/".$ThemeSel."/images/".$currentlang."/menu_bg.gif); BORDER-LEFT: #c6c6c6 1px solid; BORDER-BOTTOM: #c6c6c6 0px solid; HEIGHT: 23px\">\n"; 
echo "    <DIV class=bltl1 style=\"PADDING-RIGHT: 2px; PADDING-LEFT: 2px; PADDING-BOTTOM: 2px; MARGIN: 2px; WIDTH: 190px; PADDING-TOP: 2px\">\n"; 
echo "    <IMG style=\"VERTICAL-ALIGN: middle\" height=16 alt=\"\" src=\"".INCLUDE_PATH."themes/".$ThemeSel."/images/".$currentlang."/large_icons_16x16.gif\" width=16> \n"; 
echo "$cat_name";
echo "    </DIV></TD></TR>\n"; 
echo "</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td bgcolor=\"#D2D6D7\" height=\"1\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td height=\"3\"></td>\n";
echo "</tr>\n";
echo "</table>\n";
}
/******/
/* Thay doi banner theo module */
/******/
function showBanner() {
      global $module_name, $currentlang, $ThemeSel;
      $width = "889"; //chieu dai banner
      $height = "107"; //chieu cao banner
      $banner = $banner_link = $bannerShow ="";
      if (file_exists("".INCLUDE_PATH."themes/".$ThemeSel."/images/".$currentlang."/banner_{$module_name}.jpg")) {
	      $banner ="".INCLUDE_PATH."themes/".$ThemeSel."/images/".$currentlang."/banner_{$module_name}.jpg";
	      $banner_link ="index.php";
      } else {
	      $banner ="".INCLUDE_PATH."images/banner/banner.jpg";
	      $banner_link ="index.php";
      }
      $bannerShow = "<a href=\"".INCLUDE_PATH."$banner_link\"><img border=\"0\" src=\"$banner\" width=\"$width\" height=\"$height\" alt=\"Trang chính\" /></a>";   
      echo $bannerShow;
}

/******/
/* The hien block trai */
/******/	 
function showblockleft() {
 global $datafold, $adminfile, $module_title, $module_name, $name, $currentlang,$ThemeSel, $index;
echo "<!-- block trai -->\n";
if ($index == "4") {
echo "<td valign=\"top\" width=\"2\">\n";
echo "<img border=\"0\" src=\"".INCLUDE_PATH."themes/".$ThemeSel."/images/".$currentlang."/spacer.gif\" width=\"2\" height=\"1\"></td>\n";
}else{
}
echo "<td valign=\"top\" width=\"190\" bgcolor=\"#FFFFFF\">\n";
echo "<table border=\"0\" width=\"100%\" cellpadding=\"0\" style=\"border-collapse: collapse\">\n";
echo "<tr>\n";
echo "<td>";
blocks(left,$name);
echo"</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td>&nbsp;</td>\n";
echo "</tr>\n";
echo "</table>\n";
echo "</td>\n";
echo "<!--// block trai -->\n";
if ($index != "4") { 
echo "<td valign=\"top\" width=\"3\">\n";
echo "<img border=\"0\" src=\"".INCLUDE_PATH."themes/".$ThemeSel."/images/".$currentlang."/spacer.gif\" width=\"3\" height=\"1\"></td>\n";
}}
/******/
/* Thể hiện cột block phải */
/******/	
 	 
function showblockright() {
 global $datafold, $adminfile, $module_title, $module_name, $name, $currentlang, $ThemeSel, $index; 
echo "<!-- block phai -->\n";	
if ($index == "5") { 
}else{
echo "<td valign=\"top\" width=\"3\">\n";
echo "<img border=\"0\" src=\"".INCLUDE_PATH."themes/".$ThemeSel."/images/".$currentlang."/spacer.gif\" width=\"3\" height=\"1\"></td>\n";
}
echo "<td valign=\"top\" width=\"160\">";
blocks(right,$module_name);
echo"</td>\n";
if ($index == "5") {
echo "<td valign=\"top\" width=\"3\">\n";
echo "<img border=\"0\" src=\"".INCLUDE_PATH."themes/".$ThemeSel."/images/".$currentlang."/spacer.gif\" width=\"3\" height=\"1\"></td>\n";
}else{
}
echo "<!-- block phai -->\n";
}
/******/
/* Thể hiện phần đuôi trang */
/******/	
function showfooter() { 
	global $adminfile, $index, $module_name, $currentlang,$ThemeSel;
echo "                  <TABLE style=\"BORDER-COLLAPSE: collapse\" cellPadding=0 \n"; 
echo "                  width=\"100%\" border=0>\n"; 
echo "                    <TBODY>\n"; 
echo "<TR><TD style=\"BACKGROUND-IMAGE: url(".INCLUDE_PATH."themes/".$ThemeSel."/images/".$currentlang."/menu_bg.gif); HEIGHT: 25px\">\n"; 
// block menu 3
if (file_exists("".INCLUDE_PATH."themes/".$ThemeSel."/blocks/block-Menu3.php"))
{
	include("".INCLUDE_PATH."themes/".$ThemeSel."/blocks/block-Menu3.php");
}
echo "</TD></TR>\n"; 
echo "                    <TR>\n"; 
echo "                      <TD style=\"HEIGHT: 1px; BACKGROUND-COLOR: #fff\"><IMG \n"; 
echo "                        height=1 src=\"".INCLUDE_PATH."themes/".$ThemeSel."/images/".$currentlang."/spacer.gif\" width=1 \n"; 
echo "                        border=0></TD></TR>\n"; 
echo "                    <TR>\n"; 
echo "                      <TD style=\"HEIGHT: 60px; BACKGROUND-COLOR: #FFFFFF\">\n"; 
echo "                        <TABLE style=\"BORDER-COLLAPSE: collapse\" cellPadding=0 \n"; 
echo "                        width=\"100%\" border=0>\n"; 
echo "                          <TBODY>\n"; 
echo "                          <TR>\n"; 
echo "                            <TD \n"; 
echo "                            style=\"FONT-SIZE: 11px; WIDTH: 50%; COLOR: #666666; FONT-FAMILY: Tahoma, Arial, sans-serif; TEXT-ALIGN: center\">";
footmsg();
echo"</TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE>\n";
}
/******/
/* Thể hiện phần đầu trang */
/******/

function themeheader() {
global $datafold, $adminfile, $module_title, $module_name, $name, $index, $currentlang,$ThemeSel;
echo"<body bgcolor=\"#E7E7E7\" topmargin=\"0\" leftmargin=\"0\" rightmargin=\"0\" bottommargin=\"0\">\n\n";
echo "<table border=\"0\" width=\"900\" cellpadding=\"0\" style=\"border-collapse: collapse\" align=\"center\">\n";
echo "<tr>\n";
echo "<td>\n";
echo "<table border=\"0\" width=\"100%\" cellpadding=\"0\" style=\"border-collapse: collapse\">\n";
echo "<tr>\n";
echo "<td background=\"".INCLUDE_PATH."themes/".$ThemeSel."/images/".$currentlang."/kleft.gif\" width=\"6\">\n";
echo "<img border=\"0\" src=\"".INCLUDE_PATH."themes/".$ThemeSel."/images/".$currentlang."/spacer.gif\" width=\"6\" height=\"1\"></td>\n";
echo "<td>\n";
echo "<table border=\"0\" width=\"100%\" cellpadding=\"0\" bgcolor=\"#FFFFFF\" style=\"border-collapse: collapse\">\n";
showBanner();
echo "<tr>\n";
echo "<td height=\"2\">\n";
echo "<img border=\"0\" src=\"".INCLUDE_PATH."themes/".$ThemeSel."/images/".$currentlang."/spacer.gif\" width=\"1\" height=\"2\"></td>\n";
echo "</tr>\n";
// menu ngang
if (file_exists("".INCLUDE_PATH."themes/".$ThemeSel."/blocks/block-Menu2.php"))
{
	echo "<tr><td>\n";
	include("".INCLUDE_PATH."themes/".$ThemeSel."/blocks/block-Menu2.php");
	echo "</td></tr>\n";
}
if ($module_name == "Forums") {
echo"<tr><td>";
}else{
echo "<!-- phan than -->\n";
echo "<tr>\n";
echo "<td>\n";
echo "<table border=\"0\" width=\"100%\" cellpadding=\"0\" style=\"border-collapse: collapse\">\n";
echo "<tr>\n";
if (!defined('NV_ADMIN')  AND $index == 1 || $index == 2) {
showblockleft();
}elseif ($index == 5) {
	showblockleft();
	showblockright();
}
echo "<td valign=\"top\">\n";
echo "<table border=\"0\" width=\"100%\" cellpadding=\"0\" style=\"border-collapse: collapse\">\n";
echo "<tr>\n";
echo "<td bgcolor=\"#EEEEEE\" height=\"20\">";
mod_title();
echo"</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td height=\"3\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td>";
}
}

/******/
/* Thể hiện phần dưới trang */
/******/

function themefooter() {
    global $adminfile, $index, $module_name, $currentlang,$ThemeSel;
    if ($module_name == "Forums") {
    echo"</td></tr>";
  }else{
   echo"</td>\n";
echo "</tr>\n";
echo "</table>\n";
echo "</td>\n";
if (!defined('NV_ADMIN')  AND $index == 1 || $index == 3) {
showblockright();
}elseif ($index == 4) {
	showblockleft();
	showblockright();
}
echo "</tr>\n";
echo "</table>\n";
echo "</td>\n";
echo "</tr>\n";
echo "<!-- het than -->\n";
}
echo "<tr>\n";
echo "<td height=\"2\">\n";
echo "<img border=\"0\" src=\"".INCLUDE_PATH."themes/".$ThemeSel."/images/".$currentlang."/spacer.gif\" width=\"1\" height=\"2\"></td>\n";
echo "</tr>\n";
echo "</table>\n";
showfooter();
echo "</td>\n";
echo "<td background=\"".INCLUDE_PATH."themes/".$ThemeSel."/images/".$currentlang."/kright.gif\" width=\"6\">\n";
echo "<img border=\"0\" src=\"".INCLUDE_PATH."themes/".$ThemeSel."/images/".$currentlang."/spacer.gif\" width=\"6\" height=\"1\"></td>\n";
echo "</tr>\n";
echo "</table>\n";
echo "</td>\n";
echo "</tr>\n";
echo "</table>\n";
}

/******/
/* Thể hiện tin tức trên trang nhất */
/******/

function themeindex ($aid, $datetime, $title, $hometext, $story_pic, $notes, $story_link, $com_link, $tot_hits,$mau = 0) {
    global $catnewshomeimg, $sizecatnewshomeimg, $newshome, $catnewshome, $datafold, $sitename, $print, $home, $currentlang,$ThemeSel;
    $bg = ($mau%2==1) ? "bgcolor='#EBEBEB'" : "";
echo"<table border=\"0\" cellpadding=\"0\" style=\"border-collapse: collapse\" width=\"100%\"$bg><tr>";
if (($newshome ==1) AND ($catnewshome==1) AND (($home==1) || (isset($_GET['op']) AND $_GET['op']=="viewcat"))) {
echo"<td style=\"text-align: justify\" valign=\"top\">\n"
  . "<p class=storytitle style=\"margin-top: 5px; margin-bottom: 2px\">$story_pic$title</p>\n"
  . "<font class=grey style=\"margin-top: 2px; margin-bottom: 10px\">[$datetime]</font><br>\n"
  . "<div><font class=indexhometext>$hometext</font>".$story_link."".$print."".$com_link."".$tot_hits."</div></td>\n";
  if ($notes!="") {
echo"<td><img border=\"0\" src=\"".INCLUDE_PATH."themes/".$ThemeSel."/images/".$currentlang."/spacer.gif\" width=\"4\"></td>\n"
  ."<td style=\"border-left-style: solid; border-left-width: 1px; border-right-width: 1px; border-top-width: 1px; border-bottom-width: 1px\"><img border=\"0\" src=\"".INCLUDE_PATH."themes/".$ThemeSel."/images/".$currentlang."/spacer.gif\" width=\"4\"></td>\n"
  ."<td valign=\"top\" width=\"30%\">$notes</td>\n";
  }
} elseif (($newshome == 1) AND ($catnewshome==2) AND (($home==1) || (isset($_GET['op']) AND $_GET['op']=="viewcat"))) {
  if ($notes!="") {
echo"<td valign=\"top\" width=\"30%\">$notes</td>\n"
  ."<td><img border=\"0\" src=\"".INCLUDE_PATH."themes/".$ThemeSel."/images/".$currentlang."/spacer.gif\" width=\"4\"></td>\n"
  ."<td style=\"border-left-style: solid; border-left-width: 1px; border-right-width: 1px; border-top-width: 1px; border-bottom-width: 1px\"><img border=\"0\" src=\"".INCLUDE_PATH."themes/".$ThemeSel."/images/".$currentlang."/spacer.gif\" width=\"4\"></td>\n";
  }
echo"<td style=\"text-align: justify\" valign=\"top\">\n"
  . "<p class=storytitle style=\"margin-top: 5px; margin-bottom: 2px\">$story_pic$title</p>\n"
  . "<font class=grey style=\"margin-top: 2px; margin-bottom: 10px\">[$datetime]</font><br>\n"
  . "<div><font class=indexhometext>$hometext</font>".$story_link."".$print."".$com_link."".$tot_hits."</div></td>\n";
} else {
echo "<td valign=\"top\">\n";
echo "<table border=\"0\" cellpadding=\"1\" style=\"border-collapse: collapse\" width=\"100%\" cellspacing=\"1\"><tr>\n";
echo "<td colspan=\"2\"><p class=storytitle style=\"margin-top: 5px; margin-bottom: 2px\">$title</p>\n</td>\n</tr>\n<tr>\n";
if($catnewshomeimg=="left" && $story_pic!="") {
//	echo "<td valign=\"top\" width=\"$sizecatnewshomeimg\">".$story_pic."</td>\n";
}
echo"<td style=\"text-align: justify\" valign=\"top\">\n"
  . "<font class=grey style=\"margin-top: 2px; margin-bottom: 10px\">[$datetime]</font><br>\n"
  . "<font class=indexhometext>$story_pic$hometext</font>".$print."".$com_link."".$tot_hits."".$story_link."</td>\n";
if($catnewshomeimg=="right" && $story_pic!="") {
//	echo "<td valign=\"top\" width=\"$sizecatnewshomeimg\">".$story_pic."</td>\n";
}
if($notes!="") {
	echo "</tr><tr><td colspan=\"2\">$notes</td>\n";
}
echo "</tr>\n";
echo "</table>\n";
echo "</td>\n";
}
echo"</tr></table>\n";
}

/******/
/* Thể hiện toàn bộ bài viết */
/******/

function themearticle ($aid, $time, $title, $hometext, $bodytext, $notes, $story_pic, $source) {
echo"<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n"
  . "<tr>\n<td class=\"indexhometext\"><div class=\"storytitle\">$title</div>\n"
  . "<div class=\"grey\">$time</div>\n"
  . "<p class=\"articlehometext\" align=\"justify\">$story_pic$hometext\n"
  . "<p class=\"indexhometext\" align=\"justify\">$bodytext\n<br><br>";
  if($notes!="") {
  OpenTable();
  echo $notes;
  CloseTable();
}
  echo "<p align=\"right\"><b>$aid</b> $source</td>\n"
  . "</tr>\n"
  . "</table>\n<br>\n<br>\n";
}

function themenewsst($title,$image,$hometext, $story_link) {
	OpenTable();
    		echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\"><tr><td valign=\"top\" style=\"text-align: justify\">";
		echo "<p class=storytitle style=\"margin-top: 0px; margin-bottom: 5px\">$title</p>";
		echo "$image<span class=indexhometext>$hometext</span> $story_link";
		echo "</td></tr></table>";
	CloseTable();
		echo "<br>";
}

/******/
/* Thể hiện blocks trái và phải */
/******/

function themesidebox($title, $content, $link) {
  global $block_side,$ThemeSel,$currentlang;
  if ($block_side == "l") {
echo "    <TABLE style=\"MARGIN-BOTTOM: 2px; PADDING-BOTTOM: 2px; WIDTH: 100%; BACKGROUND-COLOR: #DCEAFA\" cellSpacing=0 cellPadding=0>\n"; 
echo "    <TBODY><TR><TD style=\"BORDER-RIGHT: #2F80D4 1px solid; BORDER-TOP: #2F80D4 0px solid; BACKGROUND-IMAGE: url(".INCLUDE_PATH."themes/".$ThemeSel."/images/".$currentlang."/menu_bg.gif); BORDER-LEFT: #2F80D4 1px solid; BORDER-BOTTOM: #2F80D4 0px solid; HEIGHT: 23px\">\n"; 
echo "    <DIV class=bltl1 style=\"PADDING-RIGHT: 2px; PADDING-LEFT: 2px; PADDING-BOTTOM: 2px; MARGIN: 2px; WIDTH: 190px; PADDING-TOP: 2px\">\n"; 
echo "    <IMG style=\"VERTICAL-ALIGN: middle\" height=16 alt=\"\" src=\"".INCLUDE_PATH."themes/".$ThemeSel."/images/".$currentlang."/large_icons_16x16.gif\" width=16> \n"; 
if ($link != "") { 
  echo "<a class=\"A_white\" href=\"".$link."\">&nbsp;".$title."&nbsp;</a>";
  }else{
  echo "<font class=A_white>&nbsp;".$title."&nbsp;</font>";
}
echo "    </DIV></TD></TR>\n"; 
echo "    <TR><TD style=\"BORDER-RIGHT: #2F80D4 1px solid; BORDER-TOP: #2F80D4 0px solid; BORDER-LEFT: #2F80D4 1px solid; BORDER-BOTTOM: #2F80D4 1px solid\">\n"; 
echo "    <DIV style=\"PADDING-RIGHT: 2px; PADDING-LEFT: 2px; PADDING-BOTTOM: 2px; MARGIN: 2px; WIDTH: 190px; PADDING-TOP: 2px\">\n"; 
echo "    $content\n"; 
echo "    </DIV></TD></TR></TBODY></TABLE>\n";
}
  else {
echo "  <TABLE style=\"MARGIN-BOTTOM: 2px; PADDING-BOTTOM: 2px; WIDTH: 100%; BACKGROUND-COLOR: #DCEAFA\" cellSpacing=0 cellPadding=0>\n"; 
echo "  <TBODY><TR><TD style=\"BORDER-RIGHT: #2F80D4 1px solid; BORDER-TOP: #2F80D4 0px solid; BACKGROUND-IMAGE: url(".INCLUDE_PATH."themes/".$ThemeSel."/images/".$currentlang."/menu_bg.gif); BORDER-LEFT: #2F80D4 1px solid; BORDER-BOTTOM: #2F80D4 0px solid; HEIGHT: 23px\">\n"; 
echo "  <DIV class=bltl1 style=\"PADDING-RIGHT: 2px; PADDING-LEFT: 2px; PADDING-BOTTOM: 2px; MARGIN: 2px; WIDTH: 160px; PADDING-TOP: 2px\">\n"; 
echo "  <IMG style=\"VERTICAL-ALIGN: middle\" height=16 alt=\"\" src=\"".INCLUDE_PATH."themes/".$ThemeSel."/images/".$currentlang."/large_icons_16x16.gif\" width=16> \n"; 
if ($link != "") { 
  echo "<a class=\"A_white\" href=\"".$link."\">&nbsp;".$title."&nbsp;</a>";
  }else{
  echo "<font class=A_white>&nbsp;".$title."&nbsp;</font>";
}
echo "  </DIV></TD></TR>\n"; 
echo "  <TR><TD style=\"BORDER-RIGHT: #2F80D4 1px solid; BORDER-TOP: #2F80D4 0px solid; BORDER-LEFT: #2F80D4 1px solid; BORDER-BOTTOM: #2F80D4 1px solid\">\n"; 
echo "  <DIV style=\"PADDING-RIGHT: 2px; PADDING-LEFT: 2px; PADDING-BOTTOM: 2px; MARGIN: 2px; WIDTH: 160px; PADDING-TOP: 2px\">\n"; 
echo "  <TABLE cellSpacing=0 cellPadding=0 width=\"100%\" border=0>\n"; 
echo "  <TBODY>\n"; 
echo "  <TR><TD>\n"; 
echo " $content\n"; 
echo "  </TD></TR>\n"; 
echo "  </TBODY></TABLE></DIV></TD></TR></TBODY></TABLE>\n";
  }

}

/******/
/* Thể hiện blocks giữa */
/******/

function themecenterbox($title, $content, $link) {
 global $ThemeSel,$currentlang;
echo "  <TABLE style=\"MARGIN-BOTTOM: 2px; PADDING-BOTTOM: 2px; WIDTH: 100%; BACKGROUND-COLOR: #DCEAFA\" cellSpacing=0 cellPadding=0>\n"; 
echo "  <TBODY><TR><TD style=\"BORDER-RIGHT: #2F80D4 1px solid; BORDER-TOP: #2F80D4 0px solid; BACKGROUND-IMAGE: url(".INCLUDE_PATH."themes/".$ThemeSel."/images/".$currentlang."/menu_bg.gif); BORDER-LEFT: #2F80D4 1px solid; BORDER-BOTTOM: #2F80D4 0px solid; HEIGHT: 23px\">\n"; 
echo "  <DIV class=bltl1 style=\"PADDING-RIGHT: 2px; PADDING-LEFT: 2px; PADDING-BOTTOM: 2px; MARGIN: 2px; WIDTH: 100%; PADDING-TOP: 2px\">\n"; 
echo "  <IMG style=\"VERTICAL-ALIGN: middle\" height=16 alt=\"\" src=\"".INCLUDE_PATH."themes/".$ThemeSel."/images/".$currentlang."/large_icons_16x16.gif\" width=16> \n"; 
if ($link != "") { 
  echo "<a class=\"A_white\" href=\"".$link."\">&nbsp;".$title."&nbsp;</a>";
  }else{
  echo "<font class=A_white>&nbsp;".$title."&nbsp;</font>";
}
echo "  </DIV></TD></TR>\n"; 
echo "  <TR><TD style=\"BORDER-RIGHT: #2F80D4 1px solid; BORDER-TOP: #2F80D4 0px solid; BORDER-LEFT: #2F80D4 1px solid; BORDER-BOTTOM: #2F80D4 1px solid\">\n"; 
echo "  <DIV style=\"PADDING-RIGHT: 2px; PADDING-LEFT: 2px; PADDING-BOTTOM: 2px; MARGIN: 2px; WIDTH: 100%; PADDING-TOP: 2px\">\n"; 
echo "  <TABLE cellSpacing=0 cellPadding=0 width=\"100%\" border=0>\n"; 
echo "  <TBODY>\n"; 
echo "  <TR><TD>\n"; 
echo "$content\n"; 
echo "  </TD></TR>\n"; 
echo "  </TBODY></TABLE></DIV></TD></TR></TBODY></TABLE>\n";
}

/******/
/* Thể hiện blocks giữa 2 (Nếu cần) */
/******/

function themecenterbox2($title_2, $content_2) {
 global $ThemeSel,$currentlang;
echo "  <TABLE style=\"MARGIN-BOTTOM: 2px; PADDING-BOTTOM: 2px; WIDTH: 100%; BACKGROUND-COLOR: #DCEAFA\" cellSpacing=0 cellPadding=0>\n"; 
echo "  <TBODY><TR><TD style=\"BORDER-RIGHT: #2F80D4 1px solid; BORDER-TOP: #2F80D4 0px solid; BACKGROUND-IMAGE: url(".INCLUDE_PATH."themes/".$ThemeSel."/images/".$currentlang."/menu_bg.gif); BORDER-LEFT: #2F80D4 1px solid; BORDER-BOTTOM: #2F80D4 0px solid; HEIGHT: 23px\">\n"; 
echo "  <DIV class=bltl1 style=\"PADDING-RIGHT: 2px; PADDING-LEFT: 2px; PADDING-BOTTOM: 2px; MARGIN: 2px; WIDTH: 100%; PADDING-TOP: 2px\">\n"; 
echo "  <IMG style=\"VERTICAL-ALIGN: middle\" height=16 alt=\"\" src=\"".INCLUDE_PATH."themes/".$ThemeSel."/images/".$currentlang."/large_icons_16x16.gif\" width=16> \n"; 
echo "".$title_2."";
echo "  </DIV></TD></TR>\n"; 
echo "  <TR><TD style=\"BORDER-RIGHT: #2F80D4 1px solid; BORDER-TOP: #2F80D4 0px solid; BORDER-LEFT: #2F80D4 1px solid; BORDER-BOTTOM: #2F80D4 1px solid\">\n"; 
echo "  <DIV style=\"PADDING-RIGHT: 2px; PADDING-LEFT: 2px; PADDING-BOTTOM: 2px; MARGIN: 2px; WIDTH: 100%; PADDING-TOP: 2px\">\n"; 
echo "  <TABLE cellSpacing=0 cellPadding=0 width=\"100%\" border=0>\n"; 
echo "  <TBODY>\n"; 
echo "  <TR><TD align=middle>\n"; 
echo " $content_2\n"; 
echo "  </TD></TR>\n"; 
echo "  </TBODY></TABLE></DIV></TD></TR></TBODY></TABLE>\n";
}

?>