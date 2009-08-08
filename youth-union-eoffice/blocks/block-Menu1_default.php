<?php

/*
* @Program:		NukeViet CMS v2.0 RC1
* @File name: 	Block Menu
* @Author: 		Nguyen The Hung (Nukeviet Group)
* @Version: 	3.1
* @Date: 		29.06.2009
* @Website: 	http://mangvn.org
* @Copyright: 	(C) 2009
* @License: 	http://opensource.org/licenses/gpl-license.php GNU Public License
*/

if ( (! defined('NV_SYSTEM')) and (! defined('NV_ADMIN')) )
{
	Header( "Location: ../index.php" );
	exit;
}

global $adminfold, $adminfile, $prefix, $db, $admin, $Home_Module, $module_name, $home, $bgcolor1, $bgcolor2, $bgcolor3, $bgcolor4;
$main_module = $Home_Module;
############# PHẦN KHAI BÁO ###########
// Chiều cao một ô menu
$cao_menu = "20px";

// Không/Có hiển thị cột màu bên trái/phải menu? (0/1/2)
$mau_menu = "2";

// Màu nền menu mặc định
//$nen_menu = $bgcolor1;
// màu mẫu: #FAFAFA

// Màu nền menu khi đưa chuột qua
$nen_mouse = $bgcolor2;
// Một số màu mẫu:	#DBDEE3	#dcdccf		#EFF2EE		#A0C2FE     #DBEEF9      #EAF5FB

// Màu cạnh menu Quản trị site
$canh_qts = "";

// Màu cạnh menu hiện tại
$canh_menu = "#FF6600";
// màu mẫu: #DBDEE3	#FF6600

// Màu cạnh các menu khác
$canh_khac = "";
// Một số màu mẫu:	#6DA6FF		#EFF2EE

// Chữ in hay thường
$chu = "in";

// Định dạng style các ô menu
$stylemenu = "style=\"border-bottom-style: solid; border-bottom-width: 1px\"";
// Mẫu:

// Định dạng style các ô cạnh menu
if ( $mau_menu == "1" )
{
	$stylecanh = "style=\"border-left-style: solid; border-left-width: 1px\"";
}
else
{
	$stylecanh = "";
}
// Mẫu:

############# HẾT KHAI BÁO #############

if ( $home == "1" )
{
	$canh_home = $canh_menu;
}
else
{
	$canh_home = $canh_khac;
}

if ( $module_name == "Your_Account" )
{
	$canh_user = $canh_menu;
}
else
{
	$canh_user = $canh_khac;
}

if ( $module_name == "Sitemap" )
{
	$canh_sitemap = $canh_menu;
}
else
{
	$canh_sitemap = $canh_khac;
}

if ( $chu == "in" )
{
	$dinhdang = "style=\"text-transform: uppercase\"";
}
else
{
	$dinhdang = "";
}

if ( $mau_menu == "1" )
{
	$canhle = "align=left";
}
else
{
	$canhle = "align=left";
}

$content = "<TABLE cellSpacing=0 cellPadding=0 width=\"100%\" border=\"0\"  style=\"border-collapse: collapse\">\n";
$content .= "<TBODY>\n";

$content .= "<TR>";
if ( $mau_menu == "2" )
{
	$content .= "<TD $stylecanh width=8 bgColor=$canh_home>\n";
	$content .= "<IMG src=\"images/arrow2.gif\" height=5 width=10 border=0></TD>\n";
}
$content .= "<TD $stylemenu height=\"$cao_menu\" onMouseOver=\"this.style.background='$nen_mouse'\" onMouseOut=\"this.style.background='$nen_menu'\">\n";
$content .= "<DIV $canhle><b><A $dinhdang href=\"" . INCLUDE_PATH . "index.php\">" . _HOME . "</A></b></DIV></TD>\n";
if ( $mau_menu == "1" )
{
	$content .= "<TD $stylecanh width=8 bgColor=$canh_home>\n";
	$content .= "<IMG src=\"images/arrow2.gif\" height=5 width=10 border=0></TD>\n";
}
$content .= "</TR>\n";

if ( defined('IS_ADMIN') )
{
	$content .= "<TR>";
	if ( $mau_menu == "2" )
	{
		$content .= "<TD $stylecanh width=8 bgColor=$canh_qts>\n";
		$content .= "<IMG src=\"images/arrow2.gif\" height=5 width=10 border=0></TD>\n";
	}
	$content .= "<TD $stylemenu height=\"$cao_menu\" onMouseOver=\"this.style.background='$nen_mouse'\" onMouseOut=\"this.style.background='$nen_menu'\">\n";
	$content .= "<DIV $canhle><b><A $dinhdang href=\"" . INCLUDE_PATH . "" . $adminfold . "/" . $adminfile . ".php\">" . _ADMINPAGE . "</A> / <A $dinhdang href=\"" . INCLUDE_PATH . "" . $adminfold . "/" . $adminfile . ".php?op=logout\">" . _LOGOUT . "</A></b></DIV></TD>\n";
	if ( $mau_menu == "1" )
	{
		$content .= "<TD $stylecanh width=8 bgColor=$canh_qts>\n";
		$content .= "<IMG src=\"images/arrow2.gif\" height=5 width=10 border=0></TD>\n";
	}
	$content .= "</TR>\n";
}

if ( !defined('IS_ADMIN') AND file_exists("admin/admin.php") )
{
	$content .= "<TR>";
	if ( $mau_menu == "2" )
	{
		$content .= "<TD $stylecanh width=8 bgColor=$canh_qts>\n";
		$content .= "<IMG src=\"images/arrow2.gif\" height=5 width=10 border=0></TD>\n";
	}
	$content .= "<TD $stylemenu height=\"$cao_menu\" onMouseOver=\"this.style.background='$nen_mouse'\" onMouseOut=\"this.style.background='$nen_menu'\">\n";
	$content .= "<DIV $canhle><b><A $dinhdang href=\"" . INCLUDE_PATH . "" . $adminfold . "/" . $adminfile . ".php\">" . _ADMINPAGE . "</A></b></DIV></TD>\n";
	if ( $mau_menu == "1" )
	{
		$content .= "<TD $stylecanh width=8 bgColor=$canh_qts>\n";
		$content .= "<IMG src=\"images/arrow2.gif\" height=5 width=10 border=0></TD>\n";
	}
	$content .= "</TR>\n";
}

if ( defined('IS_USER') )
{
	$content .= "<TR>";
	if ( $mau_menu == "2" )
	{
		$content .= "<TD $stylecanh width=8 bgColor=$canh_user>\n";
		$content .= "<IMG src=\"images/arrow2.gif\" height=5 width=10 border=0></TD>\n";
	}
	$content .= "<TD $stylemenu height=\"$cao_menu\" onMouseOver=\"this.style.background='$nen_mouse'\" onMouseOut=\"this.style.background='$nen_menu'\">\n";
	$content .= "<DIV $canhle><b><A $dinhdang href=\"" . INCLUDE_PATH . "modules.php?name=Your_Account\">" . _USERPAGE . "</A> / <A $dinhdang href=\"" . INCLUDE_PATH . "modules.php?name=Your_Account&op=logout\">" . _LOGOUT . "</A></b></DIV></TD>\n";
	if ( $mau_menu == "1" )
	{
		$content .= "<TD $stylecanh width=8 bgColor=$canh_user>\n";
		$content .= "<IMG src=\"images/arrow2.gif\" height=5 width=10 border=0></TD>\n";
	}
	$content .= "</TR>\n";
}
else
{
	$content .= "<TR>";
	if ( $mau_menu == "2" )
	{
		$content .= "<TD $stylecanh width=8 bgColor=$canh_user>\n";
		$content .= "<IMG src=\"images/arrow2.gif\" height=5 width=10 border=0></TD>\n";
	}
	$content .= "<TD $stylemenu height=\"$cao_menu\" onMouseOver=\"this.style.background='$nen_mouse'\" onMouseOut=\"this.style.background='$nen_menu'\">\n";
	$content .= "<DIV $canhle><b><A $dinhdang href=\"" . INCLUDE_PATH . "modules.php?name=Your_Account\">" . _LOGIN . "</A></b></DIV></TD>\n";
	if ( $mau_menu == "1" )
	{
		$content .= "<TD $stylecanh width=8 bgColor=$canh_user>\n";
		$content .= "<IMG src=\"images/arrow2.gif\" height=5 width=10 border=0></TD>\n";
	}
	$content .= "</TR>\n";
}

$sql = "SELECT title, custom_title, view FROM " . $prefix . "_modules WHERE active='1' AND title!='Sitemap' AND title!='Your_Account' AND (inmenu='1' OR inmenu='4') ORDER BY custom_title ASC";
$result = $db->sql_query( $sql );
while ( $row = $db->sql_fetchrow($result) )
{
	$m_title = $row[title];
	$custom_title = $row[custom_title];
	$view = $row[view];
	$m_title2 = ereg_replace( "_", " ", $m_title );
	if ( $custom_title != "" )
	{
		$m_title2 = $custom_title;
	}
	if ( $m_title != $main_module )
	{
		if ( $module_name == $m_title )
		{
			$canh = $canh_menu;
		}
		else
		{
			$canh = $canh_khac;
		}
		if ( (defined('IS_ADMIN') and $view == 2) or $view != 2 )
		{
			// tạo menu cho module
			$content .= "<TR>";
			if ( $mau_menu == "2" )
			{
				$content .= "<TD $stylecanh width=8 bgColor=$canh>\n";
				$content .= "<IMG src=\"images/arrow2.gif\" height=5 width=10 border=0></TD>\n";
			}
			$content .= "<TD $stylemenu height=\"$cao_menu\" onMouseOver=\"this.style.background='$nen_mouse'\" onMouseOut=\"this.style.background='$nen_menu'\">\n";
			$content .= "<DIV $canhle><b><A $dinhdang href=\"" . INCLUDE_PATH . "modules.php?name=$m_title\">$m_title2</A></b></DIV></TD>\n";
			// tạo màu cạnh menu phải
			if ( $mau_menu == "1" )
			{
				$content .= "<TD $stylecanh width=8 bgColor=$canh>\n";
				$content .= "<IMG src=\"images/arrow2.gif\" height=5 width=10 border=0></TD>\n";
			}
			$content .= "</TR>\n";
		}
	}
}
// tạo menu Sitemap
$content .= "<TR>";
if ( $mau_menu == "2" )
{
	$content .= "<TD $stylecanh width=8 bgColor=$canh_sitemap>\n";
	$content .= "<IMG src=\"images/arrow2.gif\" height=5 width=10 border=0></TD>\n";
}
$content .= "<TD $stylemenu height=\"$cao_menu\" onMouseOver=\"this.style.background='$nen_mouse'\" onMouseOut=\"this.style.background='$nen_menu'\">\n";
$content .= "<DIV $canhle><b><A $dinhdang href=\"" . INCLUDE_PATH . "modules.php?name=Sitemap\">" . _SITEMAP . "</A></b></DIV></TD>\n";
if ( $mau_menu == "1" )
{
	$content .= "<TD $stylecanh width=8 bgColor=$canh_sitemap>\n";
	$content .= "<IMG src=\"images/arrow2.gif\" height=5 width=10 border=0></TD>\n";
}
$content .= "</TR>\n";

//
$content .= "</TBODY></TABLE>\n";

?>