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
			$sql="SELECT base_id,id_password FROM ". $yu_prefix."_baseunion where base_id='$username'";					
			$result=$db->sql_query($sql);
			$info=$db->sql_fetchrow($result);
			if ($info['base_id']!=$username)
			{				
				echo $sql;
				echo "<br><br><p align=\"center\"><b>" . _NOTUSER . "</b><br><br>" . _GOBACK . "</p><br><br>";
				exit;
			}			
			if ($info['id_password']!=$user_password)
			{	
				echo "<br><br><p align=\"center\"><b>" . _PASSWRONG . "</b><br><br>" . _GOBACK . "</p><br><br>";				
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
				
			header("Location: modules.php?name=$module_name&op=mainmenu");
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
	echo "<td><a href=\"modules.php?name=YU_Manager&amp;op=yufind\">" . _FINDYU . "</a></td>\n";
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
/**
 * YUFind()
 * 
 * @return
 * */
function YUFind()
{
	global $module_name;
	include("header.php");
	OpenTable2();
	echo "<form class=action method=GET action=\"modules.php\">\n ";
	echo _MEMBERID." : <input type=text name=memid />\n";
	echo "<input type=hidden name=op value=find />\n";	
	echo "<input type=hidden name=name value=$module_name />\n";
	echo "<center><input type=submit name=submit value=find /></center>";
	echo "</form>";
	CloseTable2();
	include("footer.php");
}
/**
 * FindResult()
 * 
 * */
function FindResult()
{
	global $db,$module_name, $yu_prefix;
	if (!isset($_GET['memid']) or empty($_GET['memid']))
	{
		header("Location: modules.php?name=YU_Manager&op=yufind");
		exit;
	}
	include("header.php");
	$memid= $_GET['memid'];
	$sql="SELECT * FROM ".$yu_prefix."_yumember WHERE member_id='$memid'";
	$result= $db->sql_query($sql);
	$row= $db->sql_fetchrow($result);
	$numrow= $db->sql_numrows($result);
	
	OpenTable2();
	if (!$row)
	{
		echo "<b>"._NOTFOUND."</b><br><br>"._GOBACK;
	}
	else
	{	
		echo "<form class=action action=\"modules.php\" method=POST>\n";	
		YUDetailForm($row);
		echo "<input type=hidden name=name value=$module_name />\n";
		echo "<input type=hidden name=op value=yusave />\n";
		echo "<input type=hidden name=memid value=\"".$memid."\"/>";
		echo "<center><input type=submit value=\""._CHANGE."\"/></center>";
		echo "</form>";
	}
	CloseTable2();
	include("footer.php");
}
/**
 * YUDetailForm()
 * 
 * */
function YUDetailForm($YUinfo=null)
{
	echo "<table border=0>";
	echo "<tr><td><b>"._MEMBERID."</b>:</td><td><b><i>".$YUinfo['member_id']."</i></b></td></tr><tr>\n";
	echo "<td><b>"._NAME."</b>:</td><td><input type=text name=username value=\"".$YUinfo['name']."\"/></td></tr><tr>\n";
	echo "<td><b>"._FEMALE."</b>:</td><td>";
	if ($YUinfo['female']==true)
	{
		echo "<input type=radio name=female value=1 checked />Nam ";
		echo "<input type=radio name=female value=0 />Nữ";
	}
	else
	{
		echo "<input type=radio name=female  />Nam ";
		echo "<input type=radio name=female checked />Nữ"; 
	}
	echo "</b></td></tr><tr>\n";
	echo "<td><b>"._NATIVELAND."</b>:</td><td><input type= text name=native_land value=\"".$YUinfo['native_land']."\"/></td></tr><tr>\n";
	echo "<td><b>"._BIRTHDAY."</b>:</td><td><input type=text name=birthday value=\"".$YUinfo['birthday']."\"/></td></tr><tr>\n";
	echo "<td><b>"._JOINDAY."</b>:</td><td><input type=text name=joinday value=\"".$YUinfo['join_date']."\"/></td></tr><tr>\n";
	echo "<td><b>"._STATUS."</b>:</td><td><input type=text name=status value=\"".$YUinfo['status']."\"/></td></tr><tr>\n";
	echo "<td><b>"._CURRENTBRANCH."</b>:</td><td><input type=text name=currentbranch value=\"".$YUinfo['current_branch']."\"/></td></tr><tr>\n";
	echo "<td><b>"._FEEUNION."</b>:</td><td><input type=text name=feeunion value=\"".$YUinfo['fee_union']."\"/></td></tr>\n";
	echo "</table>";
}
/**
 * YUSave()
 * @return
 * */
function YUSave($memid="")
{
	global $module_name, $db, $yu_prefix;
	if (!isset($_POST['name']) or empty($_POST['name']))
	{		
		echo "not save";
	//	header("Location: modules.php?name=$module_name");
	//	exit;
	}
	if ($memid=="")
	{
		$memid=check_html( $_POST['memid'], nohtml );
	}
	$name=check_html( $_POST['username'], nohtml );
	if ($_POST['female']==1)
	{
		$female=1;
	}
	else
	{
		$female=0;
	}
	$native_land=check_html( $_POST['native_land'], nohtml );
	$birthday=check_html( $_POST['birthday'], nohtml );
	$joinday=check_html( $_POST['joinday'], nohtml );
	$status=check_html( $_POST['status'], nohtml );
	$current_branch=check_html( $_POST['currentbranch'], nohtml);
	$feeunion=check_html( $_POST['feeunion'], nohtml );
	$sql="UPDATE ".$yu_prefix."_yumember SET name='$name', female='$female', native_land='$native_land', birthday='$birthday', join_date='$joinday', status='$status', current_branch='$current_branch', fee_union='$feeunion' WHERE member_id='$memid'";
	$db->sql_query($sql);
	ulist();
	echo $sql;	
//	header("Location:modules.php?name=YU_Manager");	
//	exit;
}


switch ( $op )
{

	case "logout":
		logout( $redirect, $nvforw );
		break;

	case "mainmenu":
		Main();
		break;

	case "yufind":
		YUFind();
		break;

	case "login":
		Login();
		break;

	case "find":
		FindResult();
		break;

	case "yusave":
		YUSave($memid);
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

