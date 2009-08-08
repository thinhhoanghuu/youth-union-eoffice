<?php
  /*
* @Program:		NukeViet CMS v2.0 RC1
* @File name: 	Module Your_Account
* @Version: 	1.0
* @Date: 		09.05.2009
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
if ( defined('_MODTITLE') ) $module_title = _MODTITLE;

$index = ( defined('MOD_BLTYPE') ) ? MOD_BLTYPE : 1;
/********************************************/

if (!isset($_REQUEST['username']) && !isset($_REQUEST['password']))
{
	include ("header.php");
	echo "<object width=\"550\" height=\"400\">";
	echo "<param name=\"movie\" value=\"LoginSystem.swf\">";
	echo "<embed src=\"../uploads/LoginSystem.swf\" width=\"550\" height=\"400\">";
	echo "</embed>";
	echo "</object>";
	include ("footer.php");
}
else
{
	echo "<loginsuccess>yes</loginsuccess>";	
}

//function Login($username, $password)
//{
	
//	header("Location: module.php?name=Login");
/*	global $db;
	$result= $db->sql_query("select * from doanvien where iddv='$username'");
	$info= $db->sql_fetchrow($result);
	$string="<loginsuccess>";
	if ($db->sql_numrows("select * from doanvien where iddv='$username'"))
	{
		$string .="no";
	}
	else 
	{
		$string .="yes";
	}
	$string .="</loginsuccess>";
	$string .="<password>";
	if ($info['password']!=$password)
	{
		$string .="wrong";
	}
	else
	{
		$string .="true";
	}
	$string .="</password>";
	echo "<loginsuccess>yes</loginsuccess>";	*/
//}

?>