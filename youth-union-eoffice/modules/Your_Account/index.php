<?php

/*
* @Program:		NukeViet CMS v2.0 RC1
* @File name: 	Module Your_Account /Youth Union Account 
* @Version: 	1.1
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
 * NV_userCheck()
 * 
 * @param mixed $username
 * @return
 */
function NV_userCheck( $username )
{
	global $user_prefix, $db, $nick_max, $nick_min, $bad_nick;
	$bad_nicks = explode( "|", $bad_nick );
	if ( (! $username) || ($username == "") || (ereg("[^a-zA-Z0-9_-]", $username)) )
	{
		$stop = "" . _ERRORINVNICK . "";
	} elseif ( strlen($username) > $nick_max )
	{
		$stop = "" . _NICK2LONG . "";
	} elseif ( strlen($username) < $nick_min )
	{
		$stop = "" . _NICKADJECTIVE . "";
	} elseif ( strrpos($username, ' ') > 0 )
	{
		$stop = "" . _NICKNOSPACES . "";
	} elseif ( $db->sql_numrows($db->sql_query("SELECT username FROM " . $user_prefix . "_users WHERE username='$username'")) > 0 )
	{
		$stop = "" . _NICKTAKEN . "";
	} elseif ( $db->sql_numrows($db->sql_query("SELECT username FROM " . $user_prefix . "_users_temp WHERE username='$username'")) > 0 )
	{
		$stop = "" . _NICKTAKEN . "";
	}
	else
	{
		$stop = "";
	}
	foreach ( $bad_nicks as $bad_nick )
	{
		if ( eregi($bad_nick, $username) )
		{
			$stop = "" . _NAMERESTRICTED . "";
			break;
		}
	}
	return ( $stop );
}

/**
 * NV_userCheck2()
 * 
 * @param mixed $username
 * @return
 */
function NV_userCheck2( $username )
{
	global $nick_max, $nick_min, $bad_nick;
	$bad_nicks = explode( "|", $bad_nick );
	if ( (! $username) || ($username == "") || (ereg("[^a-zA-Z0-9_-]", $username)) )
	{
		$stop = "" . _ERRORINVNICK . "";
	} elseif ( strlen($username) > $nick_max )
	{
		$stop = "" . _NICK2LONG . "";
	} elseif ( strlen($username) < $nick_min )
	{
		$stop = "" . _NICKADJECTIVE . "";
	} elseif ( strrpos($username, ' ') > 0 )
	{
		$stop = "" . _NICKNOSPACES . "";
	}
	else
	{
		$stop = "";
	}
	foreach ( $bad_nicks as $bad_nick )
	{
		if ( eregi($bad_nick, $username) )
		{
			$stop = "" . _NAMERESTRICTED . "";
			break;
		}
	}
	return ( $stop );
}

/**
 * NV_mailCheck()
 * 
 * @param mixed $user_email
 * @return
 */
function NV_mailCheck( $user_email )
{
	global $user_prefix, $db, $bad_mail;
	$user_email = strtolower( $user_email );
	$bad_email = explode( "|", $bad_mail );
	if ( (! $user_email) || ($user_email == "") || strlen($user_email) < 7 || (! eregi("^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,6}$", $user_email)) )
	{
		$stop = "" . _ERRORINVEMAIL . "";
	} elseif ( strrpos($user_email, ' ') > 0 )
	{
		$stop = "" . _ERROREMAILSPACES . "";
	} elseif ( $db->sql_numrows($db->sql_query("SELECT user_email FROM " . $user_prefix . "_users WHERE user_email='$user_email'")) > 0 )
	{
		$stop = "" . _EMAILREGISTERED . "";
	} elseif ( $db->sql_numrows($db->sql_query("SELECT user_email FROM " . $user_prefix . "_users WHERE user_email='" . md5($user_email) . "'")) > 0 )
	{
		$stop = "" . _EMAILNOTUSABLE . "";
	} elseif ( $db->sql_numrows($db->sql_query("SELECT user_email FROM " . $user_prefix . "_users_temp WHERE user_email='$user_email'")) > 0 )
	{
		$stop = "" . _EMAILREGISTERED . "";
	}
	else
	{
		$stop = "";
	}
	foreach ( $bad_email as $bad_mail )
	{
		if ( eregi($bad_mail, $user_email) )
		{
			$stop = "" . _MAILBLOCKED . " <b>$user_email</b>";
			break;
		}
	}
	return ( $stop );
}

/**
 * NV_mailCheck2()
 * 
 * @param mixed $user_email
 * @return
 */
function NV_mailCheck2( $user_email )
{
	global $bad_mail;
	$user_email = strtolower( $user_email );
	$bad_email = explode( "|", $bad_mail );
	if ( (! $user_email) || ($user_email == "") || strlen($user_email) < 7 || (! eregi("^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,6}$", $user_email)) )
	{
		$stop = "" . _ERRORINVEMAIL . "";
	} elseif ( strrpos($user_email, ' ') > 0 )
	{
		$stop = "" . _ERROREMAILSPACES . "";
	}
	else
	{
		$stop = "";
	}
	foreach ( $bad_email as $bad_mail )
	{
		if ( eregi($bad_mail, $user_email) )
		{
			$stop = "" . _MAILBLOCKED . " <b>$user_email</b>";
			break;
		}
	}
	return ( $stop );
}

/**
 * NV_passCheck()
 * 
 * @param mixed $user_pass1
 * @param mixed $user_pass2
 * @return
 */
function NV_passCheck( $user_pass1, $user_pass2 )
{
	global $pass_max, $pass_min;
	if ( strlen($user_pass1) > $pass_max )
	{
		$stop = "" . _PASSLENGTH . "";
	} elseif ( strlen($user_pass1) < $pass_min )
	{
		$stop = "" . _PASSLENGTH1 . "";
	} elseif ( $user_pass1 != $user_pass2 )
	{
		$stop = "" . _PASSWDNOMATCH . "";
	}
	else
	{
		$stop = "";
	}
	return ( $stop );
}

/**
 * NV_makePass()
 * 
 * @return
 */
function NV_makePass()
{
	$makepass = "";
	$strs = "abc2deQLTVf3ghj4kmn5opqDEF6rst7uvw8xyz9CBA";
	for ( $x = 0; $x < 7; $x++ )
	{
		mt_srand( (double)microtime() * 1000000 );
		$str[$x] = substr( $strs, mt_rand(0, strlen($strs) - 1), 1 );
		$makepass = $makepass . $str[$x];
	}
	return ( $makepass );
}

/**
 * disabled()
 * 
 * @return
 */
function disabled()
{
	include ( "header.php" );
	OpenTable();
	echo "<br><br><center><b><font class='option'>" . _ACTDISABLED . "</font></b></center><br><br>";
	CloseTable();
	include ( "footer.php" );
}


/**
 * activate()
 * 
 * @return
 */
function activate()
{
	global $db, $user_prefix, $module_name, $expiring, $allowuserlogin;
	if ( defined('IS_USER') )
	{
		header( "Location: modules.php?name=$module_name" );
		exit();
	}
	if ( $expiring != 0 )
	{
		$past = time() - $expiring;
		$db->sql_query( "DELETE FROM " . $user_prefix . "_users_temp WHERE time < '$past'" );
		$db->sql_query( "OPTIMIZE TABLE " . $user_prefix . "_users_temp" );
	}
	$user_id = intval( $_GET['user_id'] );
	$check_num = $_GET['check_num'];
	$sql = "SELECT * FROM " . $user_prefix . "_users_temp WHERE user_id='" . intval( $user_id ) . "'";
	$result = $db->sql_query( $sql );
	if ( $db->sql_numrows($result) == 1 )
	{
		$row = $db->sql_fetchrow( $result );
		$username = $row['username'];
		$new_pass = $row['user_password'];
		if ( $check_num == $row['check_num'] )
		{
			$user_regdate = time();
			$user_avatar = "gallery/blank.gif";
			$user_avatar_type = 3;
			$db->sql_query( "INSERT INTO " . $user_prefix . "_users (user_id, username, viewuname, user_email, user_regdate, user_password, opros, user_avatar, user_avatar_type) VALUES (NULL, '$row[username]', '$row[viewuname]', '$row[user_email]', '$user_regdate', '$row[user_password]', '$row[opros]', '$user_avatar', '$user_avatar_type')" );
			$db->sql_query( "DELETE FROM " . $user_prefix . "_users_temp WHERE user_id='" . intval($user_id) . "'" );
			$db->sql_query( "OPTIMIZE TABLE " . $user_prefix . "_users_temp" );
			ulist();
			if ( $allowuserlogin == 1 )
			{
				info_exit( _ALLOWUSERLOGIN );
			}
			$sql = "SELECT user_password, user_id FROM " . $user_prefix . "_users WHERE username='$username'";
			$result = $db->sql_query( $sql );
			$setinfo = $db->sql_fetchrow( $result );
			docookie( $setinfo[user_id], $username, $new_pass );
			$db->sql_query( "UPDATE " . $user_prefix . "_users SET remember='1'  WHERE username='$username'" );
			$uname = $_SERVER["REMOTE_ADDR"];
			del_online( $uname );
			include ( "header.php" );
			OpenTable();
			echo "<br><br><center><font class=\"option\"><b>" . _ACTIVATIONYES . "<br>" . _YOUARELOGGED . " " . _MEMBERS . "</b></font></center>";
			echo "<p align=\"center\"><img border=\"0\" src=\"images/load_bar.gif\" width=\"97\" height=\"19\"></p><br><br>";
			echo "<META HTTP-EQUIV=\"refresh\" content=\"2;URL=modules.php?name=$module_name&op=edituser\">";
			CloseTable();
			include ( "footer.php" );
			exit();
		}
		else
		{
			info_exit( _ACTERROR1 );
		}
	}
	else
	{
		info_exit( _ACTERROR2 );
	}
}

/**
 * userinfo()
 * 
 * @return
 */
function userinfo()
{
	global $allowuserlogin, $adminfold, $adminfile, $onls_m, $user_prefix, $db, $user_ar, $module_name, $suspend_nick;
	if ( isset($_GET['user_id']) )
	{
		$user_id = intval( $_GET['user_id'] );
	} elseif ( defined('IS_USER') )
	{
		$user_id = intval( $user_ar[0] );
	}
	else
	{
		Header( "Location: modules.php?name=$module_name" );
		exit();
	}

	if ( $user_id <= 1 )
	{
		Header( "Location: modules.php?name=$module_name" );
		exit();
	}

	$sql = "SELECT * FROM " . $user_prefix . "_users WHERE user_id='" . intval( $user_id ) . "'";
	$result = $db->sql_query( $sql );
	$userinfo = $db->sql_fetchrow( $result );
	if ( ! $userinfo )
	{
		info_exit( _ACCNOFIND );
	}
	$username = $userinfo['username'];
	include ( "header.php" );
	OpenTable();
	echo "<center>";
	$suspend_nick = explode( "|", $suspend_nick );
	if ( $userinfo['username'] != "" and in_array($userinfo['username'], $suspend_nick) )
	{
		echo "<br><font class=\"option\">" . _SUSPENDUSER1 . " <b>$username</b> " . _SUSPENDUSER2 . "</font><br><br>";
		if ( defined('IS_USER') and $userinfo['user_id'] == $user_ar[0] )
		{
			Header( "Location: modules.php?name=$module_name&op=logout" );
			exit();
		}
		CloseTable();
		include ( "footer.php" );
		exit;
	}
	if ( defined('IS_USER') and $userinfo['user_id'] == $user_ar[0] and $userinfo['user_password'] == $user_ar[2] )
	{
		echo "<font class=\"option\">" . _PERSONALINFO . ": <b>" . $userinfo['viewuname'] . "</b></font></center><br><br>";
		echo "<table border=\"0\" style=\"border-collapse: collapse\" width=\"100%\" cellspacing=\"3\">\n";
		if ( $allowuserlogin != 1 )
		{
			echo "<tr>\n";
			echo "<td width=\"20\">\n";
			echo "<a href=\"modules.php?name=Your_Account&amp;op=edituser\"><img border=\"0\" src=\"images/in.gif\" width=\"20\" height=\"20\"></a></td>\n";
			echo "<td><a href=\"modules.php?name=Your_Account&amp;op=edituser\">" . _CHANGEYOURINFO . "</a></td>\n";
			echo "<td width=\"20\">\n";
			echo "<a href=\"modules.php?name=Your_Account&amp;op=changpass\"><img border=\"0\" src=\"images/in.gif\" width=\"20\" height=\"20\"></a></td>\n";
			echo "<td><a href=\"modules.php?name=Your_Account&amp;op=changpass\">" . _CHPASSW . "</a></td>\n";
			echo "<td width=\"20\">\n";
			echo "<a href=\"modules.php?name=Your_Account&amp;op=yuinfo\"><img border=\"0\" src=\"images/in.gif\" width=\"20\" height=\"20\"></a></td>\n";
			echo "<td><a href=\"modules.php?name=Your_Account&amp;op=yuinfo\">" . _YUINFO . "</a></td>\n";
			echo "</tr>\n";
		}
		echo "<tr>\n";
		echo "<td width=\"20\">\n";
		echo "<a href=\"index.php\"><img border=\"0\" src=\"images/in.gif\" width=\"20\" height=\"20\"></a></td>\n";
		echo "<td><a href=\"index.php\">" . _HOMEPAGE . "</a></td>\n";
		echo "<td width=\"20\">\n";
		echo "<a href=\"modules.php?name=Your_Account&amp;op=logout\"><img border=\"0\" src=\"images/out.gif\" width=\"20\" height=\"20\"></a></td>\n";
		echo "<td><a href=\"modules.php?name=Your_Account&amp;op=logout\">" . _LOGOUTEXIT . "</a></td>\n";
		echo "</tr>\n";
		echo "</table>\n";
	}
	else
	{
		if ( $userinfo[viewuname] != "" )
		{
			echo "<font class=\"option\">" . _PERSONALINFO . ": <b>$userinfo[viewuname]</b></font></center><br><br>";
		}
		else
		{
			echo "<font class=\"option\">" . _PERSONALINFO . ": <b>$userinfo[username]</b></font></center><br><br>";
		}
		if ( ! eregi("http://", $userinfo[user_website]) and $userinfo[user_website] != "" )
		{
			$userinfo[user_website] = "http://$userinfo[user_website]";
		}
		if ( $userinfo[name] || $userinfo[lastname] || $userinfo[user_email] || $userinfo[user_regdate] || $userinfo[user_website] || $userinfo[user_icq] || $userinfo[user_telephone] || $userinfo[user_location] || $userinfo[user_interests] || $userinfo[user_sig] || $userinfo[user_viewemail] )
		{
			echo "<center><table cellpadding=\"3\" border=\"0\" width=\"100%\">";
			if ( $userinfo[name] )
			{
				echo "<tr><td><b>" . _NAME . ":</b></td><td>$userinfo[name]</td></tr>\n";
			}
			if ( $userinfo[lastname] )
			{
				echo "<tr><td><b>" . _LASTNAME . ":</b></td><td>$userinfo[lastname]</td></tr>\n";
			}
			if ( $userinfo[user_website] != "http://" and $userinfo[user_website] != "" )
			{
				echo "<tr><td><b>" . _MYHOMEPAGE . ":</b></td><td><a href=\"$userinfo[user_website]\" target=\"new\">$userinfo[user_website]</a></td></tr>\n";
			}
			if ( ($userinfo[user_email]) and ($userinfo[user_viewemail] == 1) )
			{
				echo "<tr><td><b>" . _EMAIL . ":</b></td><td><a href=\"mailto:$userinfo[user_email]\">$userinfo[user_email]</a></td></tr>\n";
			}
			if ( $userinfo[user_icq] )
			{
				echo "<tr><td><b>" . _ICQ . ":</b></td><td>$userinfo[user_icq]</td></tr>\n";
			}
			if ( $userinfo[user_telephone] )
			{
				echo "<tr><td><b>" . _TELEPHONE . ":</b></td><td>$userinfo[user_telephone]</td></tr>\n";
			}
			if ( $userinfo[user_from] )
			{
				echo "<tr><td><b>" . _LOCATION . ":</b></td><td>$userinfo[user_from]</td></tr>\n";
			}
			if ( $userinfo[user_interests] )
			{
				echo "<tr><td><b>" . _INTERESTS . ":</b></td><td>$userinfo[user_interests]</td></tr>\n";
			}
			$userinfo[user_sig] = nl2br( $userinfo[user_sig] );
			if ( $userinfo[user_sig] )
			{
				echo "<tr><td><b>" . _SIGNATURE . ":</b></td><td>$userinfo[user_sig]</td></tr>\n";
			}
			echo "<tr><td><b>" . _REGDATE . ":</b></td><td>" . viewtime( $userinfo[user_regdate], 1 ) . "</td></tr>\n";
			$uonline = _OFFLINE;
			$onls_m = explode( "|", $onls_m );
			for ( $l = 0; $l < sizeof($onls_m); $l++ )
			{
				$onls_m1 = explode( ":", $onls_m[$l] );
				if ( $onls_m1[0] == $userinfo['user_id'] )
				{
					$uonline = _ONLINE;
					break;
				}
			}
			echo "<tr><td><b>" . _USERSTATUS . ":</b></td><td><b>$uonline</b></td></tr>\n";
			if ( defined('IS_ADMMOD') )
			{
				echo "<tr><td><b>" . _ADMINF . ":</b></td><td>[ <a href=\"" . $adminfold . "/" . $adminfile . ".php?op=modifyUser&chng_uid=$user_id\">" . _EDITUSER . "</a> | <a href=\"" . $adminfold . "/" . $adminfile . ".php?op=UsersConfig&chng_uid=$user_id#susp\">" . _SUSPENDUSER . "</a> | <a href=\"" . $adminfold . "/" . $adminfile . ".php?op=delUser&amp;chng_uid=$user_id\">" . _DELITUSER . "</a> ]</td></tr>";
			}
			echo "</center></font></table>";
		}
		else
		{
			echo "<center>" . _NOINFOFOR . " $username</center>";
		}
	}
	CloseTable();
	include ( "footer.php" );
}

/**
 * NV_main()
 * 
 * @return
 */
function NV_main()
{
	global $nick_max, $nick_min, $pass_max, $pass_min, $allowuserlogin, $gfx_chk;
	global $module_name;
	if ( $allowuserlogin == 1 )
	{
		info_exit( _ALLOWUSERLOGIN );
	}
	if ( ! defined('IS_USER') )
	{
		include ( "header.php" );
		if ( isset($_GET['stop']) and intval($_GET['stop']) == 1 )
		{
			title( _LOGININCOR );
		}
		else
		{
			title( _USERREGLOGIN );
		}
		OpenTable();
		echo "\n<script>\n";
		echo "function check_data(Forma) {\n";
		echo "if (Forma.username.value == \"\") {\n";
		echo "alert(\"" . _NICKNAME . " ?\");\n";
		echo "Forma.username.focus();\n";
		echo "return false;\n";
		echo "}\n";
		echo "dc = Forma.username.value.length;\n";
		echo "if(dc < " . $nick_min . "){\n";
		echo "alert(\"" . _NICKADJECTIVE . "\");\n";
		echo "Forma.username.focus();\n";
		echo "return false;\n";
		echo "}\n";
		echo "if(dc > " . $nick_max . "){\n";
		echo "alert(\"" . _NICK2LONG . "\");\n";
		echo "Forma.username.focus();\n";
		echo "return false;\n";
		echo "}\n";
		if ( extension_loaded("gd") and ($gfx_chk == 2 or $gfx_chk == 4 or $gfx_chk == 5 or $gfx_chk == 7) )
		{
			echo "if (Forma.gfx_check.value == \"\") {\n";
			echo "alert(\"" . _SECURITYCODE . " ?\");\n";
			echo "Forma.gfx_check.focus();\n";
			echo "return false;\n";
			echo "}\n";
		}
		echo "if (Forma.user_password.value == \"\") {\n";
		echo "alert(\"" . _PASSWORD . " ?\");\n";
		echo "Forma.user_password.focus();\n";
		echo "return false;\n";
		echo "}\n";
		echo "if (Forma.user_password.value != \"\") {\n";
		echo "dp = Forma.user_password.value.length;\n";
		echo "if(dp < " . $pass_min . "){\n";
		echo "alert(\"" . _PASSLENGTH1 . ". " . _YOUPASSMUSTBE . " " . $pass_min . " " . _YOUPASSMUSTBE2 . " " . $pass_max . " " . _YOUPASSMUSTBE3 . "\");\n";
		echo "Forma.user_password.focus();\n";
		echo "return false;\n";
		echo "}\n";
		echo "if(dc > " . $pass_max . "){\n";
		echo "alert(\"" . _PASSLENGTH . ". " . _YOUPASSMUSTBE . " " . $pass_min . " " . _YOUPASSMUSTBE2 . " " . $pass_max . " " . _YOUPASSMUSTBE3 . "\");\n";
		echo "Forma.user_password.focus();\n";
		echo "return false;\n";
		echo "}\n";
		echo "}\n";
		echo "return true; \n";
		echo "}\n";
		echo "</script>\n";

		echo "<center><form onsubmit=\"return check_data(this)\" action=\"modules.php?name=$module_name\" method=\"post\">\n";
		echo "<b>" . _USERLOGIN . "</b><br><br>\n";
		echo "<table border=\"0\"><tr><td>\n";
		echo "<b>" . _NICKNAME . ":</b></td><td><input type=\"text\" name=\"username\" size=\"20\" maxlength=\"$nick_max\"></td></tr>\n";
		echo "<tr><td><b>" . _PASSWORD . ":</b></td><td><input type=\"password\" name=\"user_password\" size=\"20\" maxlength=\"$pass_max\"></td></tr>\n";
		if ( extension_loaded("gd") and ($gfx_chk == 2 or $gfx_chk == 4 or $gfx_chk == 5 or $gfx_chk == 7) )
		{
			echo "<tr><td><b>" . _SECURITYCODE . ":</b></td><td><img width=\"73\" height=\"17\" src='?gfx=gfx' border='1' alt='" . _SECURITYCODE . "' title='" . _SECURITYCODE . "'></td></tr>\n";
			echo "<tr><td><b>" . _TYPESECCODE . ":</b></td><td><input type=\"text\" NAME=\"gfx_check\" SIZE=\"11\" MAXLENGTH=\"6\"></td></tr>\n";
		}
		echo "<tr><td colspan=2>" . _REMEMBER . " <input type=\"checkbox\" name=\"remember\" value=\"1\"></td></tr>";
		$redirect = "";
		$mode = "";
		$f = "";
		$t = "";
		if ( isset($_GET['redirect']) and $_GET['redirect'] != "" )
		{
			$redirect = stripslashes( FixQuotes($_GET['redirect']) );
			if ( eregi("[^a-zA-Z0-9_]", $redirect) )
			{
				Header( "Location:index.php" );
				exit;
			}
		}
		if ( isset($_GET['mode']) and $_GET['mode'] != "" )
		{
			$mode = stripslashes( FixQuotes($_GET['mode']) );
			if ( eregi("[^a-zA-Z0-9_]", $mode) )
			{
				Header( "Location:index.php" );
				exit;
			}
		}
		if ( isset($_GET['f']) and $_GET['f'] != "" )
		{
			$f = stripslashes( FixQuotes($_GET['f']) );
			if ( eregi("[^a-zA-Z0-9_]", $f) )
			{
				Header( "Location:index.php" );
				exit;
			}
		}
		if ( isset($_GET['t']) and $_GET['t'] != "" )
		{
			$t = stripslashes( FixQuotes($_GET['t']) );
			if ( eregi("[^a-zA-Z0-9_]", $t) )
			{
				Header( "Location:index.php" );
				exit;
			}
		}
		echo "</table><input type=\"hidden\" name=\"redirect\" value=$redirect>\n";
		echo "<input type=\"hidden\" name=\"mode\" value=$mode>\n";
		echo "<input type=\"hidden\" name=\"f\" value=$f>\n";
		echo "<input type=\"hidden\" name=\"t\" value=$t>\n";
		echo "<input type=\"hidden\" name=\"op\" value=\"login\">\n";
		echo "<br><input type=\"submit\" value=\"" . _LOGIN . "\"></form></center><br><br><br>\n\n";
		echo "<center><font class=\"content\">[ <a href=\"modules.php?name=$module_name&amp;op=pass_lost\">" . _PASSWORDLOST . "</a> | <a href=\"modules.php?name=$module_name&amp;op=new_user\">" . _REGNEWUSER . "</a> ]</font></center>\n";
		CloseTable();
		include ( "footer.php" );
	}
	else
	{
		header( "Location: modules.php?name=Your_Account&op=userinfo" );
		exit();
	}
}

/**
 * pass_lost()
 * 
 * @return
 */
function pass_lost()
{
	global $module_name, $nick_max, $gfx_chk;
	if ( defined('IS_USER') )
	{
		header( "Location: modules.php?name=$module_name" );
		exit();
	}
	include ( "header.php" );
	OpenTable();
	echo "<center><b>" . _PASSWORDLOST . "</b><br><br>\n";
	echo "" . _NOPROBLEM . "<br><br>\n";
	echo "<form action=\"modules.php?name=$module_name\" method=\"post\">\n";
	echo "<table border=\"0\"><tr><td>\n";
	echo "" . _NICKNAME . ":</td><td><input type=\"text\" name=\"username\" size=\"25\" maxlength=\"$nick_max\"></td></tr>\n";
	echo "<tr><td align='right'>" . _OREMAIL . ":</td><td><input type=\"text\" name=\"user_email\" size=\"25\" maxlength=\"50\"></td></tr>\n";
	if ( extension_loaded("gd") and ($gfx_chk == 2 or $gfx_chk == 4 or $gfx_chk == 5 or $gfx_chk == 7) )
	{
		echo "<tr><td align='right'>" . _SECURITYCODE . ":</td><td><img width=\"73\" height=\"17\" src='?gfx=gfx' border='1' alt='" . _SECURITYCODE . "' title='" . _SECURITYCODE . "'></td></tr>\n";
		echo "<tr><td align='right'>" . _TYPESECCODE . ":</td><td><input type=\"text\" NAME=\"gfx_check\" SIZE=\"11\" MAXLENGTH=\"6\"></td></tr>\n";
	}
	echo "</table><br>\n";
	echo "<input type=\"hidden\" name=\"op\" value=\"checkop\">\n";
	echo "<input type=\"submit\" value=\"" . _STEP2 . "\"></form><br>\n";
	echo "<font class=\"content\">[ <a href=\"modules.php?name=$module_name\">" . _USERLOGIN . "</a> | <a href=\"modules.php?name=$module_name&amp;op=new_user\">" . _REGNEWUSER . "</a> ]</font></center>\n";
	CloseTable();
	include ( "footer.php" );
}

/**
 * checkop()
 * 
 * @return
 */
function checkop()
{
	global $db, $user_prefix, $module_name, $gfx_chk, $sitekey, $sitename, $nukeurl, $adminmail, $nick_max;
	if ( defined('IS_USER') )
	{
		header( "Location: modules.php?name=$module_name" );
		exit();
	}
	$username = check_html( $_POST['username'], nohtml );
	$username = substr( htmlspecialchars(str_replace("\'", "'", trim($username))), 0, $nick_max );
	$username = rtrim( $username, "\\" );
	$username = str_replace( "'", "\'", $username );
	$user_email = check_html( $_POST['user_email'], nohtml );
	$gfx_check = intval( $_POST['gfx_check'] );
	$otviet = check_html( $_POST['otviet'], nohtml );
	if ( $username == "" && $user_email == "" )
	{
		header( "Location: modules.php?name=$module_name&op=pass_lost" );
		exit();
	}
	$stop = "";
	if ( $username != "" )
	{
		$stop = NV_userCheck2( $username );
		$ch_where = "WHERE username='$username'";
	} elseif ( $user_email != "" )
	{
		$stop = NV_mailCheck2( $user_email );
		$ch_where = "WHERE user_email='$user_email'";
	}
	if ( $stop != "" )
	{
		include ( "header.php" );
		OpenTable();
		echo "<br><br><p align=\"center\"><b>" . $stop . "</b><br><br>" . _GOBACK . "</p><br><br>";
		CloseTable();
		include ( "footer.php" );
		exit;
	}
	if ( extension_loaded("gd") and (! nv_capcha_txt($gfx_check)) and ($gfx_chk == 2 or $gfx_chk == 4 or $gfx_chk == 5 or $gfx_chk == 7) )
	{
		include ( "header.php" );
		OpenTable();
		echo "<br><br><p align=\"center\"><b>" . _SECCODEINCOR . "</b><br><br>" . _GOBACK . "</p><br><br>";
		CloseTable();
		include ( "footer.php" );
		exit;
	}
	include ( "header.php" );
	OpenTable();
	$sql = "SELECT user_email, opros FROM " . $user_prefix . "_users $ch_where";
	$result = $db->sql_query( $sql );
	$num = $db->sql_numrows( $result );
	if ( $num == 1 )
	{
		$row = $db->sql_fetchrow( $result );
		if ( $row['opros'] == "" )
		{
			header( "Location: index.php" );
			exit();
		}
		$opros = explode( "|", $row['opros'] );
		if ( $opros[1] != "" . $otviet . "" )
		{
			echo "<form method=\"POST\" action=\"modules.php?name=$module_name\">\n";
			echo "<p align=\"center\">" . $opros[0] . " ?<br><br>\n";
			echo "<input type=\"text\" name=\"otviet\" size=\"30\">\n";
			echo "<input type=\"hidden\" name=\"username\" value=\"$username\">\n";
			echo "<input type=\"hidden\" name=\"user_email\" value=\"$user_email\">\n";
			echo "<input type=\"hidden\" name=\"gfx_check\" value=\"$gfx_check\">\n";
			echo "<input type=\"hidden\" name=\"op\" value=\"checkop\">\n";
			echo "<input type=\"submit\" value=\"" . _SENDPASSWORD . "\"></p>\n";
			echo "</form>\n";
		}
		else
		{
			$newpass = NV_makePass();
			$cryptpass = md5( $newpass );
			$db->sql_query( "UPDATE " . $user_prefix . "_users SET user_password='$cryptpass' $ch_where" );
			$message = "$sitename " . _NVMAILPASS . ":\n\n " . _YOURNEWPASSWORD . " $newpass\n\n " . _YOUCANCHANGE . " $nukeurl/modules.php?name=$module_name\n\n";
			$subject = "" . _USERPASSWORD4 . "";
			$mailhead = "From: $sitename <$adminmail>\n";
			$mailhead .= "Content-Type: text/plain; charset= " . _CHARSET . "\n";
			@mail( $row['user_email'], $subject, $message, $mailhead );
			echo "<br><br><center>" . _PASSWORD4 . "</center><br><br>";
			unset( $username );
			unset( $user_email );
			echo "<meta http-equiv=\"refresh\" content=\"3;url=modules.php?name=$module_name\">";

		}
	}
	else
	{
		echo "<center>" . _SORRYNOUSERINFO . "</center>";
	}
	CloseTable();
	include ( "footer.php" );
	exit;
}

/**
 * logout()
 * 
 * @param mixed $redirect
 * @param mixed $nvforw
 * @return
 */
function logout( $redirect, $nvforw )
{
	global $prefix, $db;
	if ( defined('IS_USER') )
	{
		global $mbrow;
		$r_uid = $mbrow['user_id'];
		$user = "";
		setcookie( USER_COOKIE );
		unset( $user );
		del_online( $r_uid );
		$db->sql_query( "DELETE FROM " . $prefix . "_bbsessions WHERE session_user_id='$r_uid'" );
		$db->sql_query( "OPTIMIZE TABLE " . $prefix . "_bbsessions" );
		include ( "header.php" );
		OpenTable();
		echo "<br><br><center><font class=\"option\"><b>" . _YOUARELOGGEDOUT . "</b></font></center>";
		echo "<p align=\"center\"><img border=\"0\" src=\"images/load_bar.gif\" width=\"97\" height=\"19\"></p><br><br>";
		if ( $nvforw != "" )
		{
			echo "<META HTTP-EQUIV=\"refresh\" content=\"3;URL=$nvforw\">";
		}
		else
			if ( $redirect != "" )
			{
				echo "<META HTTP-EQUIV=\"refresh\" content=\"3;URL=modules.php?name=$redirect\">";
			}
			else
			{
				echo "<META HTTP-EQUIV=\"refresh\" content=\"3;URL=index.php\">";
			}
			CloseTable();
		include ( "footer.php" );
	}
	else
	{
		header( "Location: index.php" );
		exit();
	}
}

/**
 * docookie()
 * 
 * @param mixed $setuid
 * @param mixed $setusername
 * @param mixed $setpass
 * @return
 */
function docookie( $setuid, $setusername, $setpass )
{
	$info = base64_encode( "$setuid:$setusername:$setpass" );
	setcookie( USER_COOKIE, "$info", time() + 2592000 );
}

/**
 * docookie2()
 * 
 * @param mixed $setuid
 * @param mixed $setusername
 * @param mixed $setpass
 * @return
 */
function docookie2( $setuid, $setusername, $setpass )
{
	$info = base64_encode( "$setuid:$setusername:$setpass" );
	setcookie( USER_COOKIE, "$info" );
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
function login( $username, $user_password, $gfx_check, $remember, $nvforw )
{
	global $gfx_chk, $sitekey, $datafold, $user_prefix, $db, $module_name, $userredirect, $allowuserlogin, $suspend_nick, $nick_max;
	if ( defined('IS_USER') )
	{
		header( "Location: modules.php?name=Your_Account" );
		exit();
	}
	if ( $allowuserlogin == 1 )
	{
		info_exit( _ALLOWUSERLOGIN );
	}
	$username = check_html( $_POST['username'], nohtml );
	$username = substr( htmlspecialchars(str_replace("\'", "'", trim($username))), 0, $nick_max );
	$username = rtrim( $username, "\\" );
	$username = str_replace( "'", "\'", $username );
	$user_password = htmlspecialchars( $user_password );
	$suspend_nick = explode( "|", $suspend_nick );
	if ( $username != "" and in_array($username, $suspend_nick) )
	{
		include ( "header.php" );
		OpenTable();
		echo "<br><center><font class=\"option\">" . _SUSPENDUSER1 . " <b>$username</b> " . _SUSPENDUSER2 . "</font></center><br><br>";
		CloseTable();
		include ( "footer.php" );
		exit();
	}
	$stop = NV_userCheck2( $username );
	if ( $stop != "" )
	{
		include ( "header.php" );
		OpenTable();
		echo "<br><br><p align=\"center\"><b>" . $stop . "</b><br><br>" . _GOBACK . "</p><br><br>";
		CloseTable();
		include ( "footer.php" );
		exit;
	}

	$sql = "SELECT user_password, user_id FROM " . $user_prefix . "_users WHERE username='$username'";
	$result = $db->sql_query( $sql );
	$setinfo = $db->sql_fetchrow( $result );
	$redirect = addslashes( trim((isset($_POST['redirect'])) ? $_POST['redirect'] : $_GET['redirect']) );
	$mode = addslashes( trim((isset($_POST['mode'])) ? $_POST['mode'] : $_GET['mode']) );
	$f = intval( (isset($_POST['f'])) ? $_POST['f'] : $_GET['f'] );
	$t = intval( (isset($_POST['t'])) ? $_POST['t'] : $_GET['t'] );
	if ( eregi("[^a-zA-Z0-9_]", $redirect) )
	{
		Header( "Location:index.php" );
		exit;
	}
	if ( eregi("[^a-zA-Z0-9_]", $mode) )
	{
		Header( "Location:index.php" );
		exit;
	}
	$forward = ereg_replace( "redirect=", "", "$redirect" );
	if ( ereg("privmsg", $forward) )
	{
		$pm_login = "active";
	}

	if ( ($db->sql_numrows($result) == 1) and ($setinfo[user_id] != 1) and ($setinfo[user_password] != "") )
	{
		$dbpass = $setinfo[user_password];
		$non_crypt_pass = $user_password;
		$old_crypt_pass = crypt( $user_password, substr($dbpass, 0, 2) );
		$new_pass = md5( $user_password );
		if ( ($dbpass == $non_crypt_pass) or ($dbpass == $old_crypt_pass) )
		{
			$db->sql_query( "UPDATE " . $user_prefix . "_users SET user_password='$new_pass' WHERE username='$username'" );
			$sql = "SELECT user_password FROM " . $user_prefix . "_users WHERE username='$username'";
			$result = $db->sql_query( $sql );
			$row = $db->sql_fetchrow( $result );
			$dbpass = $row['user_password'];
		}
		if ( $dbpass != $new_pass )
		{
			Header( "Location: modules.php?name=$module_name&stop=1" );
			return;
		}
		if ( extension_loaded("gd") and (! nv_capcha_txt($gfx_check)) and ($gfx_chk == 2 or $gfx_chk == 4 or $gfx_chk == 5 or $gfx_chk == 7) )
		{
			Header( "Location: modules.php?name=$module_name&stop=1" );
			die();
		}
		else
		{
			if ( $remember == 1 )
			{
				docookie( $setinfo[user_id], $username, $new_pass );
				$db->sql_query( "UPDATE " . $user_prefix . "_users SET remember='1'  WHERE username='$username'" );
			}
			else
			{
				docookie2( $setinfo[user_id], $username, $new_pass );
				$db->sql_query( "UPDATE " . $user_prefix . "_users SET remember='0'  WHERE username='$username'" );
			}
			$uname = $_SERVER["REMOTE_ADDR"];
			del_online( $uname );
		}
		include ( "header.php" );
		OpenTable();
		echo "<br><br><center><font class=\"option\"><b>" . _YOUARELOGGED . " " . _MEMBERS . "</b></font></center>";
		echo "<p align=\"center\"><img border=\"0\" src=\"images/load_bar.gif\" width=\"97\" height=\"19\"></p>";
		if ( $nvforw != "" and $nvforw != "index.php" )
		{
			$nvurl = "" . $nvforw . "";
		} elseif ( $pm_login != "" )
		{
			$nvurl = "modules.php?name=Private_Messages&file=index&folder=inbox";
		}
		else
			if ( $redirect == "" )
			{
				if ( $userredirect == "" )
				{
					$nvurl = "modules.php?name=Your_Account&op=userinfo";
				} elseif ( $userredirect == "home" )
				{
					$nvurl = "index.php";
				}
				else
				{
					$nvurl = "modules.php?name=$userredirect";
				}
			} elseif ( $mode == "" )
			{
				$nvurl = "modules.php?name=Forums&file=$forward";
			} elseif ( $t != "" )
			{
				$nvurl = "modules.php?name=Forums&file=$forward&mode=$mode&t=$t";
			}
			else
			{
				$nvurl = "modules.php?name=Forums&file=$forward&mode=$mode&f=$f";
			}
			echo "<META HTTP-EQUIV=\"refresh\" content=\"2;URL=" . $nvurl . "\">";
		echo "<p align=\"center\">[<a href=\"" . $nvurl . "\">" . _QLOGIN . "</a>]</p><br><br>";
		CloseTable();
		include ( "footer.php" );
		exit();
	}
	else
	{
		Header( "Location: modules.php?name=$module_name&stop=1" );
	}
}

/**
 * edituser()
 * 
 * @return
 */
function edituser()
{
	global $allowuserlogin, $allowmailchange, $suspend_nick, $module_name;
	if ( $allowuserlogin == 1 )
	{
		info_exit( _ALLOWUSERLOGIN );
	}
	if ( defined('IS_USER') )
	{
		global $mbrow;
		$suspend_nick = explode( "|", $suspend_nick );
		if ( $mbrow['username'] != "" and in_array($mbrow['username'], $suspend_nick) )
		{
			Header( "Location: modules.php?name=$module_name&op=logout" );
			exit;
		}
		include ( "header.php" );
		MainMenu();
		OpenTable();
		echo "<table cellpadding=\"3\" border=\"0\" width='100%'><tr><td>\n";
		echo "<form action=\"modules.php?name=$module_name\" method=\"post\">\n";
		echo "<b>" . _USRNICKNAME . "</b>:</td><td><b>" . $mbrow[username] . "</b></td></tr><tr>\n";
		echo "<tr><td><b>" . _VIEWNAME . "</b>:<br>" . _OPTIONAL . "</td><td>\n";
		echo "<input type=\"text\" name=\"viewuname\" value=\"" . $mbrow[viewuname] . "\" size=\"50\" maxlength=\"100\"></td></tr>\n";
		echo "<tr><td><b>" . _UREALNAME . "</b>:<br>" . _OPTIONAL . "</td><td>\n";
		echo "<input type=\"text\" name=\"realname\" value=\"" . $mbrow[name] . "\" size=\"50\" maxlength=\"60\"></td></tr>\n";
		echo "<tr><td><b>" . _UREALLASTNAME . "</b>:<br>" . _OPTIONAL . "</td><td>\n";
		echo "<input type=\"text\" name=\"reallastname\" value=\"" . $mbrow[lastname] . "\" size=\"50\" maxlength=\"60\"></td></tr>\n";
		if ( $allowmailchange == 1 )
		{
			echo "<tr><td><b>" . _UREALEMAIL . ":</b><br>" . _REQUIRED . "</td>\n";
			echo "<td><input type=\"text\" name=\"user_email\" value=\"" . $mbrow[user_email] . "\" size=\"50\" maxlength=\"255\"></td></tr>\n";
		}
		else
		{
			echo "<tr><td><b>" . _UREALEMAIL . ":</b></td>\n";
			echo "<td>" . $mbrow[user_email] . "</td></tr>\n";
			echo "<input type=\"hidden\" name=\"user_email\" value=\"" . $mbrow[user_email] . "\">\n";
		}
		echo "<tr><td><b>" . _YOURHOMEPAGE . ":</b><br>" . _OPTIONAL . "</td>\n";
		echo "<td><input type=\"text\" name=\"user_website\" value=\"" . $mbrow[user_website] . "\" size=\"50\" maxlength=\"255\"></td></tr>\n";
		echo "<tr><td><b>" . _YICQ . ":</b><br>" . _OPTIONAL . "</td>\n";
		echo "<td><input type=\"text\" name=\"user_icq\" value=\"" . $mbrow[user_icq] . "\" size=\"30\" maxlength=\"100\"></td></tr>\n";
		echo "<tr><td><b>" . _YTELEPHONE . ":</b><br>" . _OPTIONAL . "</td>\n";
		echo "<td><input type=\"text\" name=\"user_telephone\" value=\"" . $mbrow[user_telephone] . "\" size=\"30\" maxlength=\"100\"></td></tr>\n";
		echo "<tr><td><b>" . _YLOCATION . ":</b><br>" . _OPTIONAL . "</td>\n";
		echo "<td><input type=\"text\" name=\"user_from\" value=\"" . $mbrow[user_from] . "\" size=\"30\" maxlength=\"100\"></td></tr>\n";
		echo "<tr><td><b>" . _YINTERESTS . ":</b><br>" . _OPTIONAL . "</td>\n";
		echo "<td><input type=\"text\" name=\"user_interests\" value=\"" . $mbrow[user_interests] . "\" size=\"30\" maxlength=\"100\"></td></tr>\n";
		echo "<tr><td><b>" . _YSIGNATURE . ":</b><br>" . _OPTIONAL . "</td>\n";
		echo "<td><textarea wrap=\"virtual\" cols=\"50\" rows=\"5\" name=\"user_sig\">" . $mbrow[user_sig] . "</textarea><br>" . _255CHARMAX . "</td></tr>\n";
		echo "<tr><td><b>" . _ALWAYSSHOWEMAIL . ":</b></td><td>\n";
		if ( $mbrow[user_viewemail] == 1 )
		{
			echo "<input type=\"radio\" name=\"user_viewemail\" value=\"1\" checked>" . _YES . " &nbsp;\n";
			echo "<input type=\"radio\" name=\"user_viewemail\" value=\"0\">" . _NO . "";
		} elseif ( $mbrow[user_viewemail] == 0 )
		{
			echo "<input type=\"radio\" name=\"user_viewemail\" value=\"1\">" . _YES . " &nbsp;\n";
			echo "<input type=\"radio\" name=\"user_viewemail\" value=\"0\" checked>" . _NO . "";
		}
		echo "</td></tr>\n";

		echo "<tr><td colspan='2' align='center'>\n";
		echo "<input type=\"hidden\" name=\"opros\" value=\"" . $mbrow['opros'] . "\">\n";
		echo "<input type=\"hidden\" name=\"user_id\" value=\"" . $mbrow['user_id'] . "\">\n";
		echo "<input type=\"hidden\" name=\"op\" value=\"saveuser\">\n";
		echo "<input type=\"submit\" value=\"" . _SAVECHANGES . "\">\n";
		echo "</form></td></tr></table>\n";
		CloseTable();
		include ( "footer.php" );
	}
	else
	{
		Header( "Location: modules.php?name=$module_name" );
		exit();
	}
}

/**
 * saveuser()
 * 
 * @return
 */
function saveuser()
{
	global $suspend_nick, $allowmailchange, $allowuserlogin, $user_prefix, $db, $module_name;
	if ( $allowuserlogin == 1 )
	{
		info_exit( _ALLOWUSERLOGIN );
	}
	if ( defined('IS_USER') )
	{
		global $mbrow, $user_ar;
		$suspend_nick = explode( "|", $suspend_nick );
		if ( $mbrow['username'] != "" and in_array($mbrow['username'], $suspend_nick) )
		{
			Header( "Location: modules.php?name=$module_name&op=logout" );
			exit;
		}
		$viewuname = check_html( $_POST['viewuname'], nohtml );
		$realname = check_html( $_POST['realname'], nohtml );
		$reallastname = check_html( $_POST['reallastname'], nohtml );
		$user_email = check_html( $_POST['user_email'], nohtml );
		$user_website = check_html( $_POST['user_website'], nohtml );
		$user_icq = check_html( $_POST['user_icq'], nohtml );
		$user_telephone = check_html( $_POST['user_telephone'], nohtml );
		$user_from = check_html( $_POST['user_from'], nohtml );
		$user_interests = check_html( $_POST['user_interests'], nohtml );
		$user_sig = htmlspecialchars( $_POST['user_sig'] );
		$user_viewemail = intval( $_POST['user_viewemail'] );
		$user_id = intval( $_POST['user_id'] );
		$opros = check_html( $_POST['opros'], nohtml );
		list( $user_password, $user_email2 ) = $db->sql_fetchrow( $db->sql_query("SELECT user_password, user_email FROM " . $user_prefix . "_users WHERE user_id='" . intval($user_id) . "'") );
		if ( $user_id != "" . $user_ar[0] . "" || $user_password != "" . $user_ar[2] . "" )
		{
			Header( "Location: index.php" );
			exit();
		}
		if ( $allowmailchange == 0 )
		{
			$nemail = $user_email2;
		}
		else
		{
			$nemail = $user_email;
		}
		$stop = "";
		if ( ($allowmailchange == 1) and ($nemail != $user_email2) )
		{
			$stop = NV_mailCheck( $nemail );
		}
		if ( $stop != "" )
		{
			info_exit( "" . $stop . "<br><br>" . _GOBACK . "" );
		}
		else
		{
			if ( ! preg_match('#^http[s]?:\/\/#i', $user_website) )
			{
				$user_website = "http://" . $user_website;
			}
			if ( ! preg_match('#^http[s]?\\:\\/\\/[a-z0-9\-]+\.([a-z0-9\-]+\.)?[a-z]+#i', $user_website) )
			{
				$user_website = '';
			}
			if ( $viewuname == "" )
			{
				$viewuname = $user_ar[1];
			}
			$db->sql_query( "UPDATE " . $user_prefix . "_users SET viewuname='$viewuname', name='$realname', lastname='$reallastname', user_email='$nemail', user_website='$user_website', user_icq='$user_icq', user_telephone='$user_telephone', user_from='$user_from', user_interests='$user_interests', user_sig='$user_sig', user_viewemail='$user_viewemail', opros='$opros' WHERE user_id='$user_id'" );
			ulist();
		}
		Header( "Location: modules.php?name=$module_name&op=edituser" );
	}
	else
	{
		info_exit( _MUSTBEUSER );
	}
}
/**
 * 
 *
 *  @return 
 */
function MainMenu()
{
	OpenTable();
	echo "<center><font class=\"option\">" . _PERSONALINFO . ": <b>" . $mbrow['viewuname'] . "</b></font></center><br><br>";
	echo "<table border=\"0\" style=\"border-collapse: collapse\" width=\"100%\" cellspacing=\"3\">\n";
	echo "<tr>\n";
	echo "<td width=\"20\">\n";
	echo "<a href=\"modules.php?name=Your_Account&amp;op=edituser\"><img border=\"0\" src=\"images/in.gif\" width=\"20\" height=\"20\"></a></td>\n";
	echo "<td><a href=\"modules.php?name=Your_Account&amp;op=edituser\">" . _CHANGEYOURINFO . "</a></td>\n";
	echo "<td width=\"20\">\n";
	echo "<a href=\"modules.php?name=Your_Account\"><img border=\"0\" src=\"images/in.gif\" width=\"20\" height=\"20\"></a></td>\n";
	echo "<td><a href=\"modules.php?name=Your_Account\">" . _USERPAGE . "</a></td>\n";
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
/**
 * changpass()
 * 
 * @return
 */
function changpass()
{
	global $suspend_nick, $allowuserlogin, $module_name, $pass_max;
	if ( $allowuserlogin == 1 )
	{
		info_exit( _ALLOWUSERLOGIN );
	}
	if ( defined('IS_USER') )
	{
		global $mbrow;
		$suspend_nick = explode( "|", $suspend_nick );
		if ( $mbrow['username'] != "" and in_array($mbrow['username'], $suspend_nick) )
		{
			Header( "Location: modules.php?name=$module_name&op=logout" );
			exit;
		}
		include ( "header.php" );
		MainMenu();
		OpenTable();
		echo "<table cellpadding=\"3\" border=\"0\" width='100%'><tr><td>\n";
		echo "<form action=\"modules.php?name=$module_name\" method=\"post\">\n";
		echo "<b>" . _PASSWORD . "</b>:</td><td><input type=\"password\" name=\"pass\" size=\"22\" maxlength=\"$pass_max\"></td></tr><tr>\n";
		echo "<tr><td><b>" . _TYPENEWPASSWORD . "</b>:</td><td><input type=\"password\" name=\"newpass1\" size=\"22\" maxlength=\"$pass_max\"></td></tr><tr>\n";
		echo "<tr><td><b>" . _RETYPEPASSWORD . "</b>:</td><td><input type=\"password\" name=\"newpass2\" size=\"22\" maxlength=\"$pass_max\"></td></tr>\n";
		echo "<tr><td colspan='2' align='center'>\n";
		echo "<input type=\"hidden\" name=\"op\" value=\"savechangpass\">\n";
		echo "<input type=\"submit\" value=\"" . _SAVECHANGES . "\">\n";
		echo "</form></td></tr></table>\n";
		CloseTable();
		include ( "footer.php" );
	}
	else
	{
		Header( "Location: modules.php?name=$module_name" );
		exit();
	}
}

/**
 * savechangpass()
 * 
 * @return
 */
function savechangpass()
{
	global $allowuserlogin, $suspend_nick, $module_name, $user_prefix, $db, $pass_max;
	if ( $allowuserlogin == 1 )
	{
		info_exit( _ALLOWUSERLOGIN );
	}
	if ( defined('IS_USER') )
	{
		global $mbrow;
		$suspend_nick = explode( "|", $suspend_nick );
		if ( $mbrow['username'] != "" and in_array($mbrow['username'], $suspend_nick) )
		{
			Header( "Location: modules.php?name=$module_name&op=logout" );
			exit;
		}
		$ok = intval( $_POST['ok'] );
		$pass = md5( substr(addslashes($_POST['pass']), 0, $pass_max) );
		if ( $pass != "" . $mbrow['user_password'] . "" )
		{
			info_exit( "<br><br><p align=\"center\">" . _LOGININCOR . "</p><br><br><META HTTP-EQUIV=\"refresh\" content=\"2;URL=modules.php?name=$module_name&op=changpass\">" );
		}
		$newpass1 = substr( addslashes($_POST['newpass1']), 0, $pass_max );
		$newpass2 = substr( addslashes($_POST['newpass2']), 0, $pass_max );
		$newpass1a = md5( $newpass1 );
		$stop = NV_passCheck( $newpass1, $newpass2 );
		if ( $stop != "" )
		{
			info_exit( "<br><br><p align=\"center\"><b>" . _NOCHPASSW . "</b><br><br>$stop</p><br><br><meta http-equiv=\"refresh\" content=\"3;url=modules.php?name=$module_name&op=changpass\">" );
		}
		if ( $newpass1a == "" . $mbrow['user_password'] . "" )
		{
			Header( "Location: modules.php?name=$module_name&op=changpass" );
			exit;
		}

		if ( $ok == 1 )
		{
			$db->sql_query( "UPDATE " . $user_prefix . "_users SET user_password = '$newpass1a' WHERE user_id='" . $mbrow['user_id'] . "'" );
			del_online( $mbrow['user_id'] );
			$user = "";
			setcookie( USER_COOKIE );
			include ( "header.php" );
			OpenTable();
			echo "<br><br><p align=\"center\"><img border=\"0\" src=\"images/load_bar.gif\" width=\"97\" height=\"19\"></p><br><br>";
			echo "<META HTTP-EQUIV=\"refresh\" content=\"2;URL=modules.php?name=$module_name\">";
			CloseTable();
			include ( "footer.php" );
			exit;
		}
		else
		{
			include ( "header.php" );
			OpenTable();
			echo "<br><br><p align=\"center\">" . _YOURNEWPASSWORD . " <b>" . $newpass1 . "</b><br>" . _CHPASSINF . "</p>";
			echo "<form method=\"POST\" action=\"modules.php?name=$module_name\">\n";
			echo "<p align=\"center\"><input type=\"hidden\" name=\"pass\" value=\"" . $_POST['pass'] . "\">\n<input type=\"hidden\" name=\"newpass1\" value=\"" . $newpass1 . "\">\n<input type=\"hidden\" name=\"newpass2\" value=\"" . $newpass2 . "\">\n<input type=\"hidden\" name=\"ok\" value=\"1\">\n<input type=\"hidden\" name=\"op\" value=\"savechangpass\">\n<input type=\"submit\" value=\"" . _GO . "\"></p>\n<br><br>"; //Y08 / 22.07.06
			echo "</form>\n";
			CloseTable();
			include ( "footer.php" );
			exit;
		}
	}
	else
	{
		Header( "Location: modules.php?name=$module_name" );
		exit();
	}
}

/**
 * 
 * 
 * 
 * */
function YUInfo()
{
	global $allowuserlogin, $allowmailchange, $suspend_nick, $module_name, $db;
	if ( $allowuserlogin == 1 )
	{
		info_exit( _ALLOWUSERLOGIN );
	}
	if ( defined('IS_USER') )
	{
		global $mbrow;
		$sql="SELECT * FROM sc_yumember WHERE member_id='".$mbrow['username']."'";
		$result= $db->sql_query($sql);
		$YUinfo= $db->sql_fetchrow($result);	
		
		
		$suspend_nick = explode( "|", $suspend_nick );
		if ( $mbrow['username'] != "" and in_array($mbrow['username'], $suspend_nick))
		{
			Header( "Location: modules.php?name=$module_name&op=logout" );
			exit;
		}		
		include ( "header.php" );
		MainMenu();
		OpenTable();
		if (! $YUinfo)
		{
			echo "Không có thông tin về đoàn viên";						
		}
		else
		{
			echo "<table cellpadding=\"3\" border=\"0\" width='100%'><tr><td>\n";
			echo "<form action=\"modules.php?name=$module_name\" method=\"post\">\n";			
			echo "<tr><td><b>"._MEMBERID."</b>:</td><td><b><i>".$YUinfo['member_id']."</i></b></td></tr><tr>\n";
			echo "<td><b>"._NAME."</b>:</td><td><input type=text name=username value=\"".$YUinfo['name']."\"/></td></tr><tr>\n";
			echo "<td><b>"._FEMALE."</b>:</td><td>";
			if ($YUinfo['female']==1)
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
			echo "<tr><td colspan='2' align='center'>\n";			
			echo "<input type=\"hidden\" name=\"memid\" value=\"" . $YUinfo['member_id'] . "\">\n";
			echo "<input type=\"hidden\" name=\"op\" value=\"yusave\">\n";
			echo "<input type=\"submit\" value=\"" . _SAVECHANGES . "\">\n";
			echo "</tr></form></table>";
			
		}
		CloseTable();
		include("footer.php");		
	}
}
/**
 * YUSave()
 * @return
 * */
function YUSave($memid)
{
	global $module_name, $db, $yu_prefix;
	if (!isset($_POST['username']) or empty($_POST['username']))
	{			
		header("Location: modules.php?name=$module_name");
		exit;
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
	$sql="UPDATE ".$yu_prefix."_yumember SET name='$name', female='$female', native_land='$native_land', birthday='$birthday', join_date='$joinday', status='$status', current_branch='$current_branch', fee_yu='$feeunion' WHERE member_id='$memid'";
	$result=$db->sql_query($sql) or die("Erro connect to database");	
	include("header.php");
	OpenTable();	
	echo "<b><center>"._UDSUCCESS."</b><br><br>"._GOBACK."</center>";	
	CloseTable();
	include("footer.php");
}
switch ( $op )
{

	case "logout":
		logout( $redirect, $nvforw );
		break;

	case "lost_pass":
		lost_pass();
		break;

	case "userinfo":
		userinfo();
		break;

	case "login":
		login( $username, $user_password, $gfx_check, $remember, $nvforw );
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
	case "yusave":
		YUSave($memid);
		break;
	default:
		NV_main();
		break;

}

?>