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

$index = 0;
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
 * @return
 */
function Login( )
{
	global  $db, $module_name, $yu_prefix ;		
	if ( !defined('IS_USER') )
	{
		header( "Location: index.php" );
		exit();
	}	
	if (!isset($_POST['username']) or !isset($_POST['password']) or empty($_POST['username']) or empty($_POST['password']))
	{
		include("header.php");
		OpenTable2();
		echo "<form class=action action=\"modules.php?name=$module_name\" method=\"POST\">\n";
		echo "<table border=\"0\"><tr><td>\n";
		echo "<b>"._BASEID."</b></td><td><input type=text name=\"username\" /></td></tr>\n";
		echo "<tr><td><b>"._PASSWORD."</b></td><td><input type=password name=\"password\" /></td></tr>\n";
		echo "<tr><td><input type=checkbox name=\"remember\"/>"._REMEMBER."</td></tr>";
		echo "</table>";
		echo "<center><input type=submit name=submit value="._LOGIN."</center>";
		echo "</form>";
		CloseTable2();
		include("footer.php");
	}	
	else
	{
		$username=$_POST['username'];
		$user_password=$_POST['password'];
		$remember= $_POST['remember'];
		$username = check_html( $username, nohtml );		
		$username = rtrim( $username, "\\" );
		$username = str_replace( "'", "\'", $username );
		$user_password = htmlspecialchars( $user_password );
		$stop= UserCheck($username);
		if ($stop!="")
		{
			include ( "header.php" );
			OpenTable();
			echo "<br><br><p align=\"center\"><b>" . $stop . "</b><br><br>" . _GOBACK . "</p><br><br>";
			CloseTable();
			include ( "footer.php" );
			exit;
				
		}
		else
		{
			include ( "header.php" );	
			OpenTable();		
			$sql="SELECT base_id,id_password FROM ". $yu_prefix."_baseuion where base_id='$username'";
						
			$result=$db->sql_query($sql);
			$info=$db->sql_fetchrow($result);
			if ($info['base_id']!=$username)
			{	
				echo "<br><br><p align=\"center\"><b>" . _NOTUSER . "</b><br><br>" . _GOBACK . "</p><br><br>";
				exit;
			}			
			if ($info['id_password']!=$user_password)
			{	
				echo "<br><br><p align=\"center\"><b>" . _PASSWRONG . "</b><br><br>" . _GOBACK . "</p><br><br>";
				CloseTable();
				exit;			
			}
			if ($remember==1)
			{
				docookie($username, $user_password);
			}
			else
			{
				docookie2($username, $user_password);
			}			
			header("Location: modules.php?name=$module_name&op=mainmenu")	;
			echo "<br><p align=\"center\"><b>" . _LOGINSUCCESS . "</b>";						
			echo "<br><a href=\"modules.php?name=Union_Manager&amp;op=mainmenu\">"._GOTOMAIN."</a>";
			CloseTable();
			include("footer.php");
			
		}
	}
	
}
/**
 * name: MainMenu()
 * 
 * @return
 * /
 */
function Main()
{	
	include("header.php");
	OpenTable();
	echo "<center><font class=\"option\">" . _UNIONMANAGER . ": <b></b></font></center><br><br>";
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
	include("footer.php");
}

switch ( $op )
{

	case "logout":
		logout( $redirect, $nvforw );
		break;

	case "mainmenu":
		Main();
		break;

	case "userinfo":
		userinfo();
		break;

	case "login":
		Login();
		break;

	case "edituser":
		edituser();
		break;

	case "saveuser":
		saveuser();
		break;

	case "pass_lost":
		pass_lost();
		break;


	case "activate":
		activate();
		break;

	case "changpass":
		changpass();
		break;

	case "savechangpass":
		savechangpass();
		break;

	case "checkop":
		checkop();
		break;
	case "yuinfo":
		YUInfo();
		break;
	default:
		Login();	
		break;

}
?>

