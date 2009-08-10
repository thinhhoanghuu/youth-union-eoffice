<?php
/*
* @Program:		NukeViet CMS v2.0 RC1
* @File name: 	Module Base Union Mangager 
* @Version: 	1.0
* @Date: 		09.08.2009
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
/**
 * docookie()
 * 
 * @param mixed $setuid
 * @param mixed $setusername
 * @param mixed $setpass
 * @return
 */
function docookie( $setuid, $setpass )
{
	$info = base64_encode( "$setuid:$setpass" );
	setcookie( MAN_COOKIE, "$info", time() + 2592000 );
}

/**
 * docookie2()
 * 
 * @param mixed $setuid
 * @param mixed $setusername
 * @param mixed $setpass
 * @return
 */
function docookie2( $setuid, $setpass )
{
	$info = base64_encode( "$setuid:$setpass" );
	setcookie( MAN_COOKIE, "$info" );
}

/**
 * UserCheck()
 * 
 * @param mixed $username
 * @return
 */
function UserCheck( $username )
{
	global $nick_max, $nick_min;	
	if ( (! $username) || ($username == "") || (ereg("[^a-zA-Z0-9_-]", $username)) )
	{
		$stop = "" . _USERWRONG . "";
	} elseif ( strlen($username) > $nick_max )
	{
		$stop = "" . _USERWRONG . "";
	} elseif ( strlen($username) < $nick_min )
	{
		$stop = "" . _USERWRONG . "";
	} elseif ( strrpos($username, ' ') > 0 )
	{
		$stop = "" . _USERWRONG . "";
	}
	else
	{
		$stop = "";
	}
	return ( $stop );
}


/**
 * login()
 * 
 * @param mixed $username
 * @param mixed $user_password
 * @param mixed $gfx_check
 * @param mixed $remember
 * @param mixed $nvforw
 * @return
 */
function login( $username, $user_password, $remember )
{
	global  $db, $module_name ;
	if ( defined('IS_USER') )
	{
		header( "Location: modules.php?name=Union_Manager" );
		exit();
	}
	$username = check_html( $_POST['username'], nohtml );
	$username = substr( htmlspecialchars(str_replace("\'", "'", trim($username))), 0, $nick_max );
	$username = rtrim( $username, "\\" );
	$username = str_replace( "'", "\'", $username );
	$user_password = htmlspecialchars( $user_password );
	$stop= UserCheck($username);
	if ($stop=="")
	{
		include ( "header.php" );
		OpenTable();
		echo "<br><br><p align=\"center\"><b>" . $stop . "</b><br><br>" . _GOBACK . "</p><br><br>";
		CloseTable();
		include ( "footer.php" );
		exit();
		
	}
	else
	{
		$sql="SELECT base_id,password FROM sc_base_union where base_id='$username'";
		
		if ($db->sql_numrows($sql)==0)
		{
			include ( "header.php" );
			OpenTable();
			echo "<br><br><p align=\"center\"><b>" . _USERWRONG . "</b><br><br>" . _GOBACK . "</p><br><br>";
			CloseTable();
			exit();
		}
		$result=$db->sql_query($sql);
		$info=$db->sql_fetchrow($result);
		if ($info['password']!=$user_password)
		{
			include ( "header.php" );
			OpenTable();
			echo "<br><br><p align=\"center\"><b>" . _PASSWRONG . "</b><br><br>" . _GOBACK . "</p><br><br>";
			CloseTable();
			exit();			
		}
		if ($remember==1)
		{
			docookie($username, $user_password);
		}
		else
		{
			docookie2($username, $user_password);
		}
		
	}
	
}
/**
 * name: MainMenu()
 * 
 * @return
 * /
 */
function MainMenu()
{
	OpenTable();
	echo "<center><font class=\"option\">" . _UNIONMANAGER . ": <b>" . $mbrow['viewuname'] . "</b></font></center><br><br>";
	echo "<table border=\"0\" style=\"border-collapse: collapse\" width=\"100%\" cellspacing=\"3\">\n";
	echo "<tr>\n";
	echo "<td width=\"20\">\n";
	echo "<a href=\"modules.php?name=Your_Account&amp;op=edituser\"><img border=\"0\" src=\"images/in.gif\" width=\"20\" height=\"20\"></a></td>\n";
	echo "<td><a href=\"modules.php?name=Your_Account&amp;op=edituser\">" . _FINDYU . "</a></td>\n";
	echo "<td width=\"20\">\n";
	echo "<a href=\"modules.php?name=Your_Account\"><img border=\"0\" src=\"images/in.gif\" width=\"20\" height=\"20\"></a></td>\n";
	echo "<td><a href=\"modules.php?name=Your_Account\">" . _EVENT . "</a></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td width=\"20\">\n";
	echo "<a href=\"index.php\"><img border=\"0\" src=\"images/in.gif\" width=\"20\" height=\"20\"></a></td>\n";
	echo "<td><a href=\"index.php\">" . _HOMEPAGE . "</a></td>\n";
	echo "<td width=\"20\">\n";
	echo "<a href=\"modules.php?name=Your_Account&amp;op=logout\"><img border=\"0\" src=\"images/out.gif\" width=\"20\" height=\"20\"></a></td>\n";
	echo "<td><a href=\"modules.php?name=Your_Account&amp;op=logout\">" . _LOGOUTEXIT . "</a></td>\n";	
	echo "</tr>\n";
	echo "</table>\n";	
	CloseTable();
	echo "<br>\n";	
}

?>

