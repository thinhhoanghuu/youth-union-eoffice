<?php

/*
* @Program:		NukeViet CMS v2.0 RC1
* @File name: 	Block Menu V 2.0
* @Author: 		Nguyen The Hung (Nukeviet Group)
* @Version: 	2.0
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
############# PHẦN KHAI BÁO ###########
// Chiều cao một ô menu
$cao_menu = "20px";

// Có hiển thị cột màu bên trái menu không? (có/không)
$mau_menu = "có";

// Màu nền menu Quản trị site, trang chủ, trang thành viên
$nen_qts = "#DBEEF9";

// Màu nền các menu khác
$nen_menu = "#EAF5FB";
// Một số màu mẫu:	#dcdccf		#EFF2EE		#A0C2FE     #DBEEF9      #EAF5FB

// Màu cạnh menu Quản trị site
$canh_qts = "#f07c26";

// Màu cạnh menu Trang chủ
$canh_home = "#bb3500";

// Màu cạnh menu Trang thành viên
$canh_user = "#649703";

// Màu cạnh các menu khác
$canh_khac = "#6DA6FF";
// Một số màu mẫu:	#6DA6FF		#EFF2EE

// Màu cạnh menu Sitemap (menu này nằm dưới cùng danh sách)
$canh_news = "#6DA6FF";
// #f07c26

############# HẾT KHAI BÁO #############

global $adminfold, $adminfile, $prefix, $db, $admin, $Home_Module;
$main_module = $Home_Module;

$content = "<TABLE cellSpacing=0 cellPadding=0 width=\"100%\" border=\"0\"  style=\"border-collapse: collapse\">\n";
$content .= "<TBODY>\n";
$content .= "<TR>\n";
$content .= "<TD height=\"$cao_menu\" bgColor=$nen_qts style=\"border-style: solid; border-width: 1px\">\n";

if ( defined('IS_ADMIN') )
{
	$content .= "<DIV align=right><b><A href=\"" . INCLUDE_PATH . "" . $adminfold . "/" . $adminfile . ".php\">" . _ADMINPAGE . "</A> / <A href=\"" . INCLUDE_PATH . "" . $adminfold . "/" . $adminfile . ".php?op=logout\">" . _LOGOUT . "</A></b></DIV></TD>\n";
	if ( $mau_menu != "không" )
	{
		$content .= "<TD width=8 bgColor=$canh_qts style=\"border-left-style: solid; border-left-width: 1px\">\n";
		$content .= "<IMG src=\"images/arrow2.gif\" height=5 width=10 border=0></TD>\n";
	}
	$content .= "</TR><TR>\n";
	$content .= "<TD height=\"$cao_menu\" bgColor=$nen_qts style=\"border-style: solid; border-width: 1px\">\n";
}
$content .= "<DIV align=right><b><A href=\"" . INCLUDE_PATH . "index.php\">" . _HOME . "</A></b></DIV></TD>\n";
if ( $mau_menu != "không" )
{
	$content .= "<TD width=8 bgColor=$canh_home style=\"border-left-style: solid; border-left-width: 1px\">\n";
	$content .= "<IMG src=\"images/arrow2.gif\" height=5 width=10 border=0></TD>\n";
}
$content .= "</TR><TR>\n";
$content .= "<TD height=\"$cao_menu\" bgColor=$nen_qts style=\"border-style: solid; border-width: 1px\">\n";
if ( defined('IS_USER') )
{
	$content .= "<DIV align=right><b><A href=\"" . INCLUDE_PATH . "modules.php?name=Your_Account\">" . _USERPAGE . "</A> / <A href=\"" . INCLUDE_PATH . "modules.php?name=Your_Account&op=logout\">" . _LOGOUT . "</A></b></DIV></TD>\n";
	if ( $mau_menu != "không" )
	{
		$content .= "<TD width=8 bgColor=$canh_user style=\"border-left-style: solid; border-left-width: 1px\">\n";
		$content .= "<IMG src=\"images/arrow2.gif\" height=5 width=10 border=0></TD>\n";
	}
	$content .= "</TR><TR>\n";
	$content .= "<TD height=\"$cao_menu\" bgColor=$nen_menu style=\"border-style: solid; border-width: 1px\">\n";
}
else
{
	$content .= "<DIV align=right><b><A href=\"" . INCLUDE_PATH . "modules.php?name=Your_Account&op=new_user\">" . _NEWREG . "</A> / <A href=\"" . INCLUDE_PATH . "modules.php?name=Your_Account\">" . _LOGIN . "</A></b></DIV></TD>\n";
	if ( $mau_menu != "không" )
	{
		$content .= "<TD width=8 bgColor=$canh_user style=\"border-left-style: solid; border-left-width: 1px\">\n";
		$content .= "<IMG src=\"images/arrow2.gif\" height=5 width=10 border=0></TD>\n";
	}
	$content .= "</TR><TR>\n";
	$content .= "<TD height=\"$cao_menu\" bgColor=$nen_menu style=\"border-style: solid; border-width: 1px\">\n";
}
$sql = "SELECT title, custom_title, view FROM " . $prefix . "_modules WHERE active='1' AND title!='Sitemap' AND title!='Your_Account' AND (inmenu='1' OR inmenu='2' OR inmenu='3' OR inmenu='4' OR inmenu='5' OR inmenu='6' OR inmenu='7' OR inmenu='8' OR inmenu='9') ORDER BY custom_title ASC";
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
		if ( (defined('IS_ADMIN') and $view == 2) or $view != 2 )
		{
			$content .= "<DIV align=right><b><A href=\"" . INCLUDE_PATH . "modules.php?name=$m_title\">$m_title2</A></b></DIV></TD>\n";
			if ( $mau_menu != "không" )
			{
				$content .= "<TD width=8 bgColor=$canh_khac style=\"border-left-style: solid; border-left-width: 1px\">\n";
				$content .= "<IMG src=\"images/arrow2.gif\" height=5 width=10 border=0></TD>\n";
			}
			$content .= "</TR><TR>\n";
			$content .= "<TD height=\"$cao_menu\" bgColor=$nen_menu style=\"border-style: solid; border-width: 1px\">\n";
		}
	}
}

$content .= "<DIV align=right><b><A href=\"" . INCLUDE_PATH . "modules.php?name=Sitemap\">" . _SITEMAP . "</A></b></DIV></TD>\n";
if ( $mau_menu != "không" )
{
	$content .= "<TD width=8 bgColor=$canh_news style=\"border-left-style: solid; border-left-width: 1px\">\n";
	$content .= "<IMG src=\"images/arrow2.gif\" height=5 width=10 border=0></TD>\n";
}
$content .= "</TR></TBODY></TABLE>\n";

?>