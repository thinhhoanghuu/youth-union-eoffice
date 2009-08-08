<?php

/*
* @Program:		NukeViet CMS
* @File name: 	admin.php
* @Version: 	2.0 RC2
* @Date: 		15.06.2009
* @author: 		Nguyen Anh Tu (Nukeviet Group)
* @contact: 	anht@mail.ru
* @Website: 	www.nukeviet.vn
* @Copyright: 	(C) 2009
* @License: 	http://opensource.org/licenses/gpl-license.php GNU Public License
*/

if ( file_exists("../install/install.php") )
{
	Header( "Location:../install/install.php" );
	exit;
}
if ( ! file_exists("../mainfile.php") ) exit();
define( 'NV_ADMIN', true );
@require_once ( "../mainfile.php" );
get_lang( 'admin' );
$module_title = _ADMINPAGE;
@require ( "../" . $datafold . "/config_admin.php" );
if ( $editor )
{
	if ( file_exists("spaw2/spaw.inc.php") ) @require_once ( "spaw2/spaw.inc.php" );
	elseif ( file_exists(INCLUDE_PATH . "spaw/spaw_control.class.php") ) @require_once ( INCLUDE_PATH . "spaw/spaw_control.class.php" );
}

$op = ( isset($_POST['op']) and ! empty($_POST['op']) ) ? $_POST['op'] : $_GET['op'];
if ( ! empty($op) ) $op = trim( $op );

/**
 * Kiem tra IP
 */
if ( $block_admin_ip and ! empty($allowed_admin_ip) )
{
	switch ( $ip_test_fields )
	{
		case 1:
			$ip_mask = "/\.[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}$/";
			break;
		case 2:
			$ip_mask = "/\.[0-9]{1,3}.[0-9]{1,3}$/";
			break;
		case 3:
			$ip_mask = "/\.[0-9]{1,3}$/";
			break;
		default:
			$ip_mask = "//";
	}
	$client_ip_tmp = preg_replace( $ip_mask, "", $client_ip );
	$allowed_admin_array = explode( "|", $allowed_admin_ip );
	$allowed_admin_ip_tmp = array();
	foreach ( $allowed_admin_array as $ip_tmp )
	{
		$allowed_admin_ip_tmp = preg_replace( $ip_mask, "", $ip_tmp );
	}
	if ( ! in_array($client_ip_tmp, $allowed_admin_ip_tmp) ) die();
}

/**
 * Tuong lua
 */
if ( $firewall and ! empty($adv_admin) )
{
	$adv_admin_array = explode( "|", $adv_admin );
	if ( isset($_POST["adv_op"]) && $_POST["adv_op"] == "go" )
	{
		$adv_admin_name_post = trim( $_POST['adv_admin_name_post'] );
		$adv_admin_pass_post = trim( $_POST['adv_admin_pass_post'] );
		$adv_admin_post_tmp = $adv_admin_name_post . ":" . $adv_admin_pass_post;
		if ( ! in_array($adv_admin_post_tmp, $adv_admin_array) )
		{
			header( "Location: " . $adminfile . ".php" );
			exit;
		}
		setcookie( "adv_sdmin_test", md5($adv_admin_post_tmp), time() + $expiring_login, $cookie_path, $cookie_domain );
	}
	else
	{
		$yes_firewall = true;
		if ( isset($_COOKIE["adv_sdmin_test"]) and ! empty($_COOKIE["adv_sdmin_test"]) )
		{
			foreach ( $adv_admin_array as $adv_admin_ar )
			{
				if ( ! empty($adv_admin_ar) and $_COOKIE["adv_sdmin_test"] == md5($adv_admin_ar) )
				{
					$yes_firewall = false;
					break;
				}
			}
		}

		if ( $yes_firewall )
		{
			echo "<html>\n\n";
			echo "<head>\n";
			echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=" . _CHARSET . "\">\n";
			echo "<link rel=\"StyleSheet\" href=\"../themes/" . $ThemeSel . "/style/style.css\" type=\"text/css\">\n";
			echo "<title>Firewall</title>\n";
			echo "</head>\n\n";
			echo "<body topmargin=\"20\" leftmargin=\"20\" rightmargin=\"20\" bottommargin=\"20\">\n\n";
			echo "<table border=\"0\" cellpadding=\"0\" style=\"border-collapse: collapse\" width=\"100%\" height=\"100%\">\n";
			echo "<tr>\n";
			echo "<td align=\"center\">\n";
			echo "<table border=\"4\" cellpadding=\"5\" style=\"border-collapse: collapse\" bordercolor=\"#808000\" bgcolor=\"#FFFF9F\" cellspacing=\"5\">\n";
			echo "<tr>\n";
			echo "<td align=\"center\"><b>Firewall</b><br><br>\n";
			echo "<form method=\"POST\" action=\"" . $adminfile . ".php\">\n";
			echo "<table border=\"0\" cellpadding=\"0\" style=\"border-collapse: collapse\" width=\"100%\">\n";
			echo "<tr>\n";
			echo "<td><b>" . _NICKNAME . ":</b>&nbsp;&nbsp;</td>\n";
			echo "<td><input type=\"text\" name=\"adv_admin_name_post\" style=\"width:200px;\" maxlength=\"50\"></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "<td><b>" . _PASSWORD . ":</b>&nbsp;&nbsp;</td>\n";
			echo "<td><input type=\"password\" name=\"adv_admin_pass_post\" style=\"width:200px;\" maxlength=\"30\"></td>\n";
			echo "</tr>\n";
			echo "<tr><td>&nbsp;</td>\n";
			echo "<td><input type=\"hidden\" name=\"adv_op\" value=\"go\">\n";
			echo "<input type=\"submit\" value=\"" . _LOGIN . "\"></td>\n";
			echo "</tr>\n";
			echo "</table>\n";
			echo "</form>\n";
			echo "</td>\n";
			echo "</tr>\n";
			echo "</table>\n";
			echo "</td>\n";
			echo "</tr>\n";
			echo "</table>\n\n";
			echo "</body>\n\n";
			echo "</html>";
			exit();
		}
	}
}

/**
 * Kiem tra admin
 */
unset( $admin, $aid, $pwd, $radminsuper );
if ( defined("IS_ADMIN") or defined("IS_SPADMIN") ) die();
if ( isset($_SESSION[ADMIN_COOKIE]) && ! empty($_SESSION[ADMIN_COOKIE]) )
{
	$admin = addslashes( base64_decode($_SESSION[ADMIN_COOKIE]) );
	$admin = explode( "#:#", $admin );
	$aid = addslashes( $admin[0] );
	$pwd = $admin[1];
	$admlanguage = $admin[2];
	if ( ! empty($aid) and ! empty($pwd) and (! empty($admin[4]) and $admin[4] == substr(trim($_SERVER['HTTP_USER_AGENT']), 0, 80)) )
	{
		$aid = substr( $aid, 0, 25 );
		//310509 - Anhtu
		//$bossresult = $db->sql_query( "SELECT `name`, `pwd`, `radminsuper`, `checknum`, `agent`, `last_ip` FROM `" . $prefix . "_authors` WHERE `aid`='" . $aid . "'" );
		$bossresult = $db->sql_query( "SELECT `name`, `pwd`, `radminsuper`, `checknum`, `agent`, `last_ip`, `email` FROM `" . $prefix . "_authors` WHERE `aid`='" . $aid . "'" );
		//END
		if ( $bossresult )
		{
			//310509 - Anhtu
			//list( $radminname, $rpwd, $radminsuper, $rchecknum, $ragent, $rlast_ip ) = $db->sql_fetchrow( $bossresult );
			list( $radminname, $rpwd, $radminsuper, $rchecknum, $ragent, $rlast_ip, $radminemail ) = $db->sql_fetchrow( $bossresult );
			//END
			if ( (! empty($rpwd) and $rpwd == $pwd) and (! empty($rchecknum) and $rchecknum == $admin[3]) and (! empty($ragent) and $ragent == $admin[4]) and (! empty($rlast_ip) and $rlast_ip == $admin[5]) )
			{
				define( 'IS_ADMIN', true );
				$radminsuper = intval( $radminsuper );
				$radminname = trim( $radminname );
				//310509 - Anhtu
				$radminemail = trim( $radminemail );
				//END
				if ( $radminsuper )
				{
					define( 'IS_SPADMIN', true );
				}
			}
		}
	}

	if ( ! defined("IS_ADMIN") ) unset( $_SESSION[ADMIN_COOKIE], $admin, $aid, $pwd, $admlanguage );
}

/**
 * Neu khong phai la admin
 */
if ( ! defined("IS_ADMIN") )
{
	$error = "";
	if ( $op == "login" )
	{
		$aid = strip_tags( trim($_POST['aid']) );
		$aid = substr( $aid, 0, 25 );
		$pwd = strip_tags( trim($_POST['pwd']) );
		$pwd = substr( $pwd, 0, 25 );
		$email = strip_tags( trim($_POST['email']) );
		$gfx_check = strip_tags( trim($_POST['gfx_check']) );
		if ( extension_loaded("gd") and ($gfx_chk == 1 or $gfx_chk == 5 or $gfx_chk == 6 or $gfx_chk == 7) and ! nv_capcha_txt($gfx_check) )
		{
			$gfx_check = "";
			$error = "Seurity Code is incorrect";
		} elseif ( empty($aid) or ereg("[^a-zA-Z0-9_-]", $aid) )
		{
			$aid = "";
			$error = "NickName is empty or invalid";
		} elseif ( empty($pwd) or ereg("[^a-zA-Z0-9_-]", $pwd) )
		{
			$pwd = "";
			$error = "Password is empty or invalid";
		} elseif ( empty($email) || ! eregi("^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,6}$", $email) || strrpos($email, ' ') > 0 )
		{
			$email = "";
			$error = "E-mail is empty or invalid";
		}
		else
		{
			list( $rpwd, $admlanguage, $bemail ) = $db->sql_fetchrow( $db->sql_query("SELECT `pwd`, `admlanguage`, `email` FROM `" . $prefix . "_authors` WHERE `aid`='" . $aid . "'") );
			{
				if ( $rpwd != md5($pwd) or $bemail != $email )
				{
					$aid = $email = $pwd = "";
					$error = "You have declared wrong nickname, email or password";
				}
				else
				{
					$admlanguage = addslashes( $admlanguage );
					mt_srand( (double)microtime() * 1000000 );
					$maxran = 1000000;
					$checknum = md5( mt_rand(0, $maxran) );
					$agent = substr( trim($_SERVER['HTTP_USER_AGENT']), 0, 80 );
					$addr_ip = substr( trim($client_ip), 0, 15 );
					$query = "UPDATE `" . $prefix . "_authors` SET `checknum` = '" . $checknum . "', `last_login` = '" . time() . "', `last_ip` = '" . $addr_ip . "', agent = '" . $agent . "' WHERE `aid`='" . $aid . "'";
					$db->sql_query( $query );
					$_SESSION[ADMIN_COOKIE] = base64_encode( $aid . "#:#" . md5($pwd) . "#:#" . $admlanguage . "#:#" . $checknum . "#:#" . $agent . "#:#" . $addr_ip );
					Header( "Location: " . $adminfile . ".php" );
					exit;
				}
			}
		}
	}

	$html = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"\n";
	$html .= "\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
	$html .= "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
	$html .= "<head>\n";
	$html .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n";
	$html .= "<title>" . _ADMINLOGIN . "</title>\n";
	$html .= "<style type=\"text/css\">\n";
	$html .= "*{MARGIN:0;PADDING:0;}\n";
	$html .= "BODY{BACKGROUND:#002D59 none repeat scroll 0% 0%;COLOR:#666;DISPLAY:block;FONT:normal normal 400 10pt/normal Arial,sans-serif;HEIGHT:auto;}\n";
	$html .= "#adminlogin{background:#FFF;border:2px solid #DFDFDF;margin:200px auto auto auto;width:400px;}\n";
	$html .= "#adminlogin DIV.div1{margin:1em;}\n";
	$html .= "#adminlogin DIV.div2{color:#036;font:bold 1.2em Arial,Helvetica,sans-serif;margin-bottom:0.6em;text-align:center;}\n";
	$html .= "#adminlogin DIV.div3{color:#800000;font:bold 0.9em Arial,Helvetica,sans-serif;margin-bottom:0.6em;text-align:center;}\n";
	$html .= "#adminlogin DIV.div4{background:#F5F5F5;margin-bottom:2px;margin-top:2px;padding:0.5em;}\n";
	$html .= "#adminlogin DIV.div4 IMG{float:right;margin-right:1em;vertical-align:middle;}\n";
	$html .= "#adminlogin DIV.div4 INPUT.t{float:right;width:200px;}\n";
	$html .= "#adminlogin DIV.div4 INPUT.t2{float:right;width:80px;}\n";
	$html .= "#adminlogin DIV.div4 LABEL{font-weight:bold;}\n";
	$html .= "#adminlogin DIV.div5{margin-top:1em;text-align:center;}\n";
	$html .= "</style>\n";
	$html .= "</head>\n";
	$html .= "<body>\n";
	$html .= "<div id=\"adminlogin\">\n";
	$html .= "<div class=\"div1\">\n";
	$html .= "<div class=\"div2\">" . _ADMINLOGIN . "</div>\n";
	if ( ! empty($error) ) $html .= "<div class=\"div3\">" . $error . "</div>\n";
	$html .= "<form method=\"post\" action=\"" . $adminfile . ".php\">\n";
	$html .= "<input name=\"op\" type=\"hidden\" value=\"login\" />\n";
	$html .= "<div class=\"div4\">\n";
	$html .= "<input name=\"aid\" type=\"text\" class=\"t\" value=\"" . $aid . "\" id=\"aid\" maxlength=\"25\" /> <label>" . _NICKNAME . "</label>\n";
	$html .= "</div>\n";
	$html .= "<div class=\"div4\">\n";
	$html .= "<input name=\"email\" type=\"text\" class=\"t\" value=\"" . $email . "\" id=\"email\" maxlength=\"255\" /> <label>" . _EMAIL . "</label>\n";
	$html .= "</div>\n";
	$html .= "<div class=\"div4\">\n";
	$html .= "<input name=\"pwd\" type=\"password\" class=\"t\" value=\"" . $pwd . "\" id=\"pwd\" maxlength=\"25\" /> <label>" . _PASSWORD . "</label>\n";
	$html .= "</div>\n";
	if ( extension_loaded("gd") and ($gfx_chk == 1 or $gfx_chk == 5 or $gfx_chk == 6 or $gfx_chk == 7) )
	{
		$html .= "<div class=\"div4\">\n";
		$html .= "<input name=\"gfx_check\" type=\"text\" class=\"t2\" id=\"gfx_check\" maxlength=\"6\" /> <img alt=\"" . _SECURITYCODE . "\" title=\"" . _SECURITYCODE . "\" src=\"?gfx=gfx\" /> <label>" . _SECURITYCODE . "</label>\n";
		$html .= "</div>\n";
	}
	$html .= "<div class=\"div5\">\n";
	$html .= "<input type=\"submit\" name=\"submit\" />\n";
	$html .= "</div>\n";
	$html .= "</form>\n";
	$html .= "</div>\n";
	$html .= "</div>\n";
	$html .= "</body>\n";
	$html .= "</html>\n";
	echo $html;
	exit();
}

/**
 * Duoi day chi danh cho Admin
 */

if ( empty($op) ) $op = "adminMain";

/**
 * Kiem tra modules
 */
$testmodhandle = @opendir( '../modules' );
$modlist = array();
while ( $file = @readdir($testmodhandle) )
{
	if ( (! ereg("[.]", $file)) ) $modlist[] = $file;
}
closedir( $testmodhandle );
sort( $modlist );

$listmods = array();
$listadmins = array();
$ml2result = $db->sql_query( "SELECT `title`, `admins` FROM `" . $prefix . "_modules`" );
while ( $rowmod = $db->sql_fetchrow($ml2result) )
{
	$titlemod = $rowmod['title'];
	if ( in_array($titlemod, $modlist) )
	{
		$listmods[] = $titlemod;
		$listadmins[] = $rowmod['admins'];
	}
	else
	{
		$db->sql_query( "DELETE FROM `" . $prefix . "_modules` WHERE `title`='" . $titlemod . "'" );
		$db->sql_query( "OPTIMIZE TABLE `" . $prefix . "_modules`" );
	}
}

$newmods = array();
foreach ( $modlist as $mod )
{
	if ( $mod != "" and ! in_array($mod, $listmods) )
	{
		$db->sql_query( "INSERT INTO `" . $prefix . "_modules` VALUES (NULL, '" . $mod . "', '" . $mod . "', 0, 0, 1, 1, '', '')" );
		$newmods[] = $mod;
		$listadmins[] = "";
	}
}
$listmods = array_merge( $listmods, $newmods );

/**
 * checkmodac()
 * 
 * @param mixed $checkmodname
 * @return
 */
function checkmodac( $checkmodname )
{
	global $radminname, $radminsuper, $listmods, $listadmins;
	if ( $radminsuper ) return true;
	if ( $checkmodname == "stories" ) $checkmodname = "News";
	if ( $checkmodname == "editusers" ) $checkmodname = "Your_Account";
	if ( $checkmodname == "forums" ) $checkmodname = "Forums";
	$checkmodname = ucfirst( $checkmodname );
	$auth_user = false;
	foreach ( $listmods as $key => $mod )
	{
		if ( $checkmodname == $mod and ! empty($listadmins[$key]) )
		{
			$admins = explode( ",", $listadmins[$key] );
			if ( ! empty($radminname) and in_array($radminname, $admins) ) $auth_user = true;
		}
	}
	return $auth_user;
}

/**
 * adminmenu()
 * 
 * @param mixed $url
 * @param mixed $title
 * @param mixed $image
 * @return
 */
function adminmenu( $url, $title, $image )
{
	global $admingraphic;
	$image = ( $admingraphic ) ? "<a href=\"" . $url . "\"><img src=\"../images/admin/" . $image . "\" border=\"0\" alt=\"" . $title . "\" title=\"" . $title . "\"></a><br>" : "";
	echo "<td align=\"center\" width=\"17%\">" . $image . "<a href=\"" . $url . "\" class=\"content\"><b>" . $title . "</b></a><br><br></td>\n";
}

/**
 * GraphicAdmin()
 * 
 * @return
 */
function GraphicAdmin()
{
	global $adminfile, $radminsuper, $radminname, $listmods, $listadmins;
	$handle = opendir( "links" );
	$links = array();
	while ( $file = readdir($handle) )
	{
		if ( substr($file, 0, 6) == "links." )
		{
			$explode = explode( ".", $file );
			if ( checkmodac($explode[1]) )
			{
				ob_start();
				include ( "links/" . $file );
				$links[] = ob_get_contents();
				ob_end_clean();
			}
		}
	}
	closedir( $handle );
	sort( $links );

	ob_start();
	adminmenu( $adminfile . ".php?op=logout", _ADMINLOGOUT, "logout.gif" );
	$links[] = ob_get_contents();
	ob_end_clean();

	OpenTable();
	echo "<center>\n";
	echo "<a href=\"" . $adminfile . ".php\" class=\"title\"><b>" . _ADMINMENU . "</b></a><br><br>\n";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"1\">\n";
	echo "<tr>\n";
	$a = 1;
	foreach ( $links as $link )
	{
		echo $link;
		if ( $a % 5 == 0 ) echo "</tr>\n<tr>\n";
		$a++;
	}
	echo "</tr>\n";
	echo "</table>\n";
	echo "</center>";
	CloseTable();
	echo "<br>";
}


/**
 * adminMain()
 * 
 * @return
 */
function adminMain()
{
	global $prefix, $db, $sitename, $user_prefix, $bgcolor2, $nukeurl, $startdate, $aid, $pwd, $adminfile, $radminemail;
	$save = intval( $_POST['save'] );
	//310509 - Anhtu
	$error = "";
	if ( $save )
	{
		$admin_email = trim( $_POST['admin_email'] );
		$check_pass = strip_tags( trim($_POST['check_pass']) );
		$check_pass = substr( $check_pass, 0, 25 );
		$admin_password1 = substr( strip_tags(trim($_POST['admin_password1'])), 0, 15 );
		$admin_password2 = substr( strip_tags(trim($_POST['admin_password2'])), 0, 15 );
		if(empty($check_pass) OR md5($check_pass)!=$pwd)
		{
			$error = _BADPASSADMIN3;
		}
		elseif ( ! eregi("^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,6}$", $admin_email) )
		{
			$error = _BADMAILADMIN;
		} elseif ( $db->sql_numrows($db->sql_query("SELECT * FROM `" . $prefix . "_authors` WHERE (`email`='" . $admin_email . "' AND `aid`!='" . $aid . "')")) > 0 )
		{
			$error = _BADMAILADMIN;
		} elseif ( ! empty($admin_password1) and (strlen($admin_password1) < 5 or ereg("[^a-zA-Z0-9_-]", $admin_password1)) )
		{
			$error = _BADPASSADMIN;
		} elseif ( ! empty($admin_password1) and $admin_password1 != $admin_password2 )
		{
			$error = _BADPASSADMIN2;
		}
		else
		{
			$query = "UPDATE `" . $prefix . "_authors` SET `email`='" . $admin_email . "'";
			if ( ! empty($admin_password1) ) $query .= ", `pwd`='" . md5( $admin_password1 ) . "'";
			$query .= " WHERE `aid`='" . $aid . "'";
			$db->sql_query( $query );
			Header( "Location: " . $adminfile . ".php" );
			exit;
		}
	}
	else
	{
		$admin_email = $radminemail;
	}
	//END
	$resulsadmin = $db->sql_query( "SELECT `aid`, `name`, `radminsuper`, `email` FROM `" . $prefix . "_authors`" );
	$admintc = $adminsuper = $adminmods = "";
	while ( $rowadmin = $db->sql_fetchrow($resulsadmin) )
	{
		$redadmin = "<a href=\"mailto:" . $rowadmin['email'] . "\">" . $rowadmin['aid'] . "</a>";
		if ( $rowadmin['name'] == "God" )
		{
			if ( ! empty($admintc) ) $admintc .= ", ";
			$admintc .= $redadmin;
		} elseif ( $rowadmin['radminsuper'] )
		{
			if ( ! empty($adminsuper) ) $adminsuper .= ", ";
			$adminsuper .= $redadmin;
		}
		else
		{
			if ( ! empty($adminmods) ) $adminmods .= ", ";
			$adminmods .= $redadmin;
		}
	}
	if ( empty($adminsuper) ) $adminsuper = "&nbsp;";
	if ( empty($adminmods) ) $adminmods = "&nbsp;";

	$usertotal = $db->sql_numrows( $db->sql_query("SELECT * FROM `" . $user_prefix . "_users` WHERE `user_id`!=1") );
	include ( "../header.php" );
	GraphicAdmin();
	OpenTable();
	echo "<p align=\"center\"><b>" . _SITEINFO . "</b></p>\n";
	echo "<table border=\"1\" cellpadding=\"3\" cellspacing=\"3\" width=\"100%\">\n";
	echo "<tr>\n";
	echo "<td colspan=\"2\" bgcolor=\"" . $bgcolor2 . "\"><b>" . _GENSITEINFO . "</b></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td>" . _SITENAME . "</td>\n";
	echo "<td>" . $sitename . "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td>" . _SITEURL . "</td>\n";
	echo "<td><a href=\"" . $nukeurl . "\">" . $nukeurl . "</a></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td>" . _STARTDATE . "</td>\n";
	echo "<td>" . $startdate . "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td colspan=\"2\" bgcolor=\"" . $bgcolor2 . "\"><b>" . _ADMINSITE . "</b></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td>" . _MAINACCOUNT . "</td>\n";
	echo "<td>" . $admintc . "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td>" . _SUPERUSER . "</td>\n";
	echo "<td>" . $adminsuper . "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td>" . _MODADMIN . "</td>\n";
	echo "<td>" . $adminmods . "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td colspan=\"2\" bgcolor=\"" . $bgcolor2 . "\"><b>" . _USERS . "</b></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td>" . _TOTALUSERS . "</td>\n";
	echo "<td>" . $usertotal . "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	CloseTable();
	//310509 - Anhtu
	echo "<br>\n";
	OpenTable();
	echo "<p align=\"center\"><b>" . _CHANGEACCOUNT . "</b></p>\n";
	if ( ! empty($error) ) echo "<p align=\"center\" style=\"color: #FF0000;\"><b>" . $error . "</b></p>\n";
	echo "<form method=\"post\" action=\"" . $adminfile . ".php\">\n";
	echo "<input type=\"hidden\" name=\"save\" value=\"1\">\n";
	echo "<div style=\"text-align:center;\">\n";
	echo "<table border=\"1\" cellpadding=\"3\" cellspacing=\"3\">\n";
	echo "<tr>\n";
	echo "<td>" . _ADMINEMAIL . ":</td><td><input style=\"width:200px;\" type=\"text\" name=\"admin_email\" value=\"" . $admin_email . "\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td>" . _NEWPASS . ":</td><td><input style=\"width:200px;\" type=\"password\" name=\"admin_password1\" value=\"" . $admin_password1 . "\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td>" . _RETYPEPASSWD . ":</td><td><input style=\"width:200px;\" type=\"password\" name=\"admin_password2\" value=\"" . $admin_password2 . "\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td>" . _CURRENTPASSW . ":</td><td><input style=\"width:200px;\" type=\"password\" name=\"check_pass\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td colspan=\"2\" style=\"text-align:center;\"><input type=\"submit\"></td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</div>\n";
	echo "</form>\n";
	CloseTable();
	//END
	include ( "../footer.php" );
}

/**
 * logout()
 * 
 * @return void
 */
function admin_logout()
{
	global $cookie_path, $cookie_domain;
	unset( $_SESSION[ADMIN_COOKIE] );
	setcookie( "adv_sdmin_test", '', 0, $cookie_path, $cookie_domain );
	include ( "../header.php" );
	OpenTable();
	echo "<META HTTP-EQUIV=\"refresh\" content=\"3;URL=../index.php\">";
	echo "<center><font class=\"title\"><b>" . _YOUARELOGGEDOUT . "</b></font>";
	echo "<br><img border=\"0\" src=\"../images/indicator.gif\" width=\"31\" height=\"31\" align=\"absmiddle\"></p>\n";
	echo "<p>" . _LOGINOK2 . "</p> \n";
	echo "<a href=\"../\"><b>" . _LOGINOK3 . " " . _LOGINOK4 . "</b></a></td></center>\n";
	CloseTable();
	include ( "../footer.php" );
	exit();
}

switch ( $op )
{
	case "adminMain":
		adminMain();
		break;

	case "logout":
		admin_logout();
		break;

	default:
		$casedir = dir( "case" );
		while ( $func = $casedir->read() )
		{
			if ( substr($func, 0, 5) == "case." ) include ( $casedir->path . "/" . $func );
		}
		closedir( $casedir->handle );
		break;
}

?>