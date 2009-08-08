<?php

/*
* @Program:	NukeViet CMS
* @File name: 	NukeViet System
* @Version: 	2.0 RC2
* @Date: 		31.05.2009
* @Website: 	www.nukeviet.vn
* @Copyright: 	(C) 2009
* @License: 	http://opensource.org/licenses/gpl-license.php GNU Public License
*/

if ( file_exists("install/install.php") )
{
	Header( "Location:install/install.php" );
	exit;
}

define( 'NV_SYSTEM', true );
if ( ! file_exists("mainfile.php") ) exit();
@require_once ( "mainfile.php" );
$module = 1;

$name = "";
if ( isset($_POST['name']) and ! empty($_POST['name']) ) $name = $_POST['name'];
elseif ( isset($_GET['name']) and ! empty($_GET['name']) ) $name = $_GET['name'];
$name = trim( $name );

if ( empty($name) || eregi("[^a-zA-Z0-9_]", $name) || ! file_exists("modules/" . $name . "/index.php") || filesize("modules/" . $name . "/index.php") == 0 )
{
	header( "Location: index.php" );
	exit;
}

if ( $name == "Private_Messages" or $name == "Forums" or $name == "Members_List" )
{
	$modstring = strtolower( $_SERVER['QUERY_STRING'] );
	if ( stripos_clone($modstring, "&file=nickpage") || stripos_clone($modstring, "&user=") )
	{
		header( "Location: index.php" );
		exit();
	}
}
$nukeuser = ( defined('IS_USER') ) ? addslashes( base64_decode($user) ) : "";

list( $module_title, $mod_active, $view, $adm_mod, $mod_theme, $bltype ) = $db->sql_fetchrow( $db->sql_query("SELECT `custom_title`, `active`, `view`, `admins`, `theme`, `bltype` FROM `" . $prefix . "_modules` WHERE `title`='" . $name . "'") );

define( "MOD_BLTYPE", $bltype );

if ( ! $changtheme )
{
	if ( ! empty($mod_theme) and file_exists("themes/" . $mod_theme . "/theme.php") ) $ThemeSel = $mod_theme;
}

$adm_mod = ( ! empty($adm_mod) ) ? explode( ",", $adm_mod ) : array();
if ( defined('IS_ADMIN') )
{
	if ( $adm_super == 1 )
	{
		define( 'IS_ADMMOD', true );
	} elseif ( ! empty($adm_mod) )
	{
		if ( ! empty($adm_name) and in_array($adm_name, $adm_mod) ) define( 'IS_ADMMOD', true );
	}
}

$mod_active = intval( $mod_active );
if(defined('IS_ADMMOD')) $mod_active = 1;
$view = intval( $view );

if ( $mod_active )
{
	$mop = ( isset($_POST['mop']) and ! empty($_POST['mop']) ) ? $_POST['mop'] : ( (isset($_GET['mop']) and ! empty($_GET['mop'])) ? $_GET['mop'] : "modload" );
	$file = ( isset($_POST['file']) and ! empty($_POST['file']) ) ? $_POST['file'] : ( (isset($_GET['file']) and ! empty($_GET['file'])) ? $_GET['file'] : "index" );
	if ( stripos_clone($name, "..") || (isset($file) && stripos_clone($file, "..")) || stripos_clone($mop, "..") ) die( "You are so cool..." );
	if ( eregi("[^a-zA-Z0-9\_\.]", $file) or eregi("[^a-zA-Z0-9\_]", $mop) ) die( "You are so cool..." );	$set_view = false;
	if ( defined('IS_ADMMOD') )
	{
		$set_view = true;
	} elseif ( $view == 0 )
	{
		$set_view = true;
	} elseif ( $view == 1 and defined('IS_USER') )
	{
		$set_view = true;
	}

    if ( ! $set_view )
       {
          if ( $view == 1 )
          {
             include ( "header.php" );
             title( $sitename . ": " . _ACCESSDENIED );
             OpenTable();
             echo "<center><b>" . _RESTRICTEDAREA . "</b><br><br>" . _MODULEUSERS . _GOBACK;
             echo "<meta http-equiv=\"refresh\" content=\"10;url=index.php\">";
             CloseTable();
             include ( "footer.php" );
          }
          else
          {
             header( "Location: index.php" );
          }
          die();
       }

	$modpath .= "modules/" . $name . "/" . $file . ".php";
	if ( ! file_exists($modpath) )
	{
		include ( "header.php" );
		OpenTable();
		echo "<br><center>Sorry, such file doesn't exist...</center><br>";
		CloseTable();
		include ( "footer.php" );
		die();
	}

	include ( $modpath );
}
else
{
   include ( "header.php" );
   OpenTable();
   echo "<center>" . _MODULENOTACTIVE . "<br><br>" . _GOBACK . "</center>";
   CloseTable();
   include ( "footer.php" );
   die();
}

?>