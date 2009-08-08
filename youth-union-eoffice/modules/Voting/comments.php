<?php

/*
* @Program:		NukeViet CMS v2.0 RC1
* @File name: 	comment.php @ Module Voting
* @Version: 	2.0
* @Date: 		17.05.2009
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

/**
 * main()
 * 
 * @param mixed $pollid
 * @return
 */
function main( $pollid )
{
	global $prefix, $user_prefix, $db, $module_name, $bgcolor1, $bgcolor2, $adminfold, $adminfile;
	$pollid = intval( $pollid );
	if ( ! isset($pollid) || $pollid == 0 )
	{
		Header( "Location: index.php" );
		exit();
	}
	$sql = "SELECT question, acomm,time FROM " . $prefix . "_nvvotings WHERE pollid='$pollid'";
	$result = $db->sql_query( $sql );
	if ( $numrows = $db->sql_numrows($result) != 1 )
	{
		Header( "Location: index.php" );
		exit;
	}
	$row = $db->sql_fetchrow( $result );
	$xquestion = explode( '|', $row['question'] );
	$question = stripslashes( FixQuotes($xquestion[0]) );
	$acomm = $row['acomm'];
	$expire = $xquestion[1];
	$mdate = $row['time'];
	$formcomment = "<br><b>" . _COMMENTSQ . "</b><hr>";
	$subject = "Re: $question";
	if ( $acomm == 0 )
	{
		$formcomment .= "<center>" . _MENUDISABLE . "</center>";
	} elseif ( ! defined('IS_USER') && ($acomm == 2) )
	{
		$formcomment .= "<center>" . _MEMBERRIQUIRED . "</center>";
	}
	else
	{
		$formcomment .= "<table align=\"center\" width=\"80%\">";

		$formcomment .= "<form onsubmit=\"return check_pform(this)\" action=\"modules.php?name=$module_name&amp;file=comments\" method=\"post\">";
		if ( defined('IS_USER') )
		{
			global $mbrow;
			$username = $mbrow['username'];
			$formcomment .= "<tr><td><font class=option><b>" . _YOURNAME . ":</b></font></td><td align=\"left\"> $username";
			$formcomment .= "<input type=\"hidden\" name=\"postname\" value=\"$username\">\n" . "<input type=\"hidden\" name=\"postemail\" value=\"\">\n" . "<input type=\"hidden\" name=\"posturl\" value=\"\">\n" . "<input type=\"hidden\" name=\"sender\" value=\"user\"></td></tr>\n";
		}
		else
		{
			$formcomment .= "<tr><td><font class=option><b>" . _YOURNAME . ":</b></font></td>";
			$formcomment .= "<td align=\"left\"><input type=\"text\" name=\"postname\" size=\"30\"></td></tr>";
			$formcomment .= "<tr><td><font class=option><b>" . _FYOUREMAIL . ":</b></font></td>";
			$formcomment .= "<td><input type=\"text\" name=\"postemail\" size=\"30\"></td></tr>";
			$formcomment .= "<tr><td><font class=option><b>" . _URL . ":</b></font></td>";
			$formcomment .= "<td><input type=\"text\" name=\"posturl\" size=\"30\">" . "<input type=\"hidden\" name=\"sender\" value=\"anon\"></td></tr>\n";
		}
		$formcomment .= "<tr><td><font class=\"option\"><b>" . _SUBJECT . ":</b></font></td>";
		$formcomment .= "<td><input type=\"text\" name=\"subject\" size=\"50\" value=\"$subject\"></td>";
		$formcomment .= "<tr><td><font class=\"option\"><b>" . _UCOMMENT . ":</b></font></td>" . "<td><textarea wrap=\"virtual\" cols=\"50\" rows=\"5\" name=\"comment\"></textarea><br><br>" . "<input type=\"hidden\" name=\"pollid\" value=\"$pollid\">\n" . "<input type=\"hidden\" name=\"op\" value=\"commreply\"></td></tr>\n";
		// add Scode
		if ( extension_loaded("gd") and (! defined('IS_USER')) )
		{
			$formcomment .= "<tr><td><b>" . _SECURITYCODE . ":</b></td><td><img width=\"73\" height=\"17\" src='?gfx=gfx' border='1' alt='" . _SECURITYCODE . "' title='" . _SECURITYCODE . "'></td>\n";
			$formcomment .= "</tr><tr>";
			$formcomment .= "<td><b>" . _TYPESECCODE . ":</b></td><td><input type=\"text\" NAME=\"gfx_checknew\" SIZE=\"11\" MAXLENGTH=\"6\"></td>\n";
			$formcomment .= "</tr>";
		}
		$formcomment .= "<tr><td align=\"left\">&nbsp;</td><td><input type=\"submit\" value=\"" . _COMMENTREPLY . "\"></td>" . "</tr></table>";
		$formcomment .= "</form>\n";
		$formcomment .= "<br><hr>";
	}
	if ( $expire == 0 )
	{
		if ( $row['acomm'] != 0 )
		{
			echo $formcomment;
		}

	}
	else
	{
		$etime = ( ($mdate + $expire) - time() ) / 3600;
		$etime = ( int )$etime;
		if ( $etime < 1 )
		{

		}
		else
		{
			if ( $row['acomm'] != 0 )
			{
				//			echo "<br><b>"._COMMENTSQ."</b><hr>";
				echo $formcomment;
			}
		}
	}

	$a = 1;

	$sql = "SELECT tid, date, name, email, url, host_name, subject, comment FROM " . $prefix . "_nvvoting_comments WHERE pollid='$pollid' ORDER BY date desc LIMIT 5";
	$result = $db->sql_query( $sql );

	while ( $row = $db->sql_fetchrow($result) )
	{
		$tid = $row['tid'];
		$send_date = $row['date'];
		$sender_name = $row['name'];

		$sql2 = "SELECT user_id, username FROM " . $user_prefix . "_users WHERE username='$sender_name'";
		$result2 = $db->sql_query( $sql2 );
		if ( $db->sql_numrows($result2) == 1 )
		{
			$row2 = $db->sql_fetchrow( $result2 );
			$user_id = $row2[user_id];
			$sender_name = "<a href=\"modules.php?name=Your_Account&op=userinfo&user_id=$user_id\">$sender_name</a>";
		}
		$sender_email = $row['email'];
		$sender_page = $row['url'];
		$sender_host = $row['host_name'];
		$com_title = $row['subject'];
		$com_text = $row['comment'];
		if ( $sender_email != "" )
		{
			$sender_email = "<a href=\"mailto:$sender_email\"><img border=\"0\" src=\"images/email.gif\" width=\"16\" height=\"16\"></a>";
		}
		else
		{
			$sender_email = "<img border=\"0\" src=\"images/email.gif\" width=\"16\" height=\"16\" title=\"" . _NOEMAIL . "\">";
		}
		if ( $sender_page != "" )
		{
			$sender_page = "<a href=\"$sender_page\" target=\"_blank\"><img border=\"0\" src=\"images/www.gif\" width=\"16\" height=\"16\"></a>";
		}
		else
		{
			$sender_page = "<img border=\"0\" src=\"images/www.gif\" width=\"16\" height=\"16\" title=\"" . _NOURL . "\">";
		}

		$commenttxt = "<table border=\"0\" cellpadding=\"2\" cellspacing=\"1\" width=\"100%\" bgcolor=\"$bgcolor2\">\n" . "<tr><td bgcolor=\"$bgcolor1\">$a</td><td bgcolor=\"$bgcolor1\">$sender_email</td>\n" . "<td bgcolor=\"$bgcolor1\">$sender_page</td><td width=\"70%\" bgcolor=\"$bgcolor1\"><b>$sender_name</b></td>\n" . "<td width=\"30%\" bgcolor=\"$bgcolor1\"><p align=\"right\">$send_date</td>\n" . "</tr><tr><td width=\"100%\" bgcolor=\"$bgcolor1\" colspan=\"5\"><b>$com_title</b><br>$com_text</td></tr>\n";
		if ( defined('IS_ADMIN') )
		{
			if ( $sender_host ) $blink = "<a href=\"" . $adminfold . "/" . $adminfile . ".php?op=ConfigureBan&bad_ip=$sender_host\">$sender_host</a> | ";
			else  $blink = "";
			$commenttxt .= "<tr><td width=\"100%\" bgcolor=\"$bgcolor1\" colspan=\"5\" align=\"center\">[ $blink<a href=\"modules.php?name=$module_name&amp;file=comments&op=poll_del_comm&tid=$tid&pollid=$pollid\">" . _DELETE . "</a> ]</td></tr>";
		}
		$commenttxt .= "</table><br><br>";
		if ( $expire == 0 )
		{
			echo $commenttxt;
		}
		else
		{
			$etime = ( ($mdate + $expire) - time() ) / 3600;
			$etime = ( int )$etime;
			if ( $etime < 1 )
			{
				echo "<hr><b>" . _COMMENTSQ . "</b><br><br>";
				echo $commenttxt;
			}
			else
			{
				echo $commenttxt;
			}
		}
		$a++;
	}
	$num = $db->sql_numrows( $db->sql_query("SELECT tid FROM " . $prefix . "_nvvoting_comments WHERE pollid='$pollid'") );
	if ( $num > 5 )
	{
		echo "<b><a href=\"modules.php?name=$module_name&amp;file=comments&op=show&pollid=$pollid\">" . _READREST . "</a></b> (" . _ALL . ": $num)";
		echo "<hr>";

	}
	echo "\n<script language=\"Javascript\">\n";
	echo "function check_pform(Forma) {\n";
	echo "if (Forma.postname.value == \"\") {\n";
	echo "alert(\"" . _ERRUNAME . "\");\n";
	echo "Forma.postname.focus();\n";
	echo "return false;\n";
	echo "}\n";
	echo "dc = Forma.postname.value.length;\n";
	echo "if(dc > 30) {\n";
	echo "alert(\"" . _ERRUNAME2LONG . "\");\n";
	echo "Forma.postname.focus();\n";
	echo "return false;\n";
	echo "}\n";
	echo "if (Forma.comment.value == \"\") {\n";
	echo "alert(\"" . _ERRCOMMENTS . " \");\n";
	echo "Forma.comment.focus();\n";
	echo "return false;\n";
	echo "}\n";
	if ( extension_loaded("gd") and (! defined('IS_USER')) )
	{

		echo "if (Forma.postemail.value == \"\")  {\n";
		echo "alert(\"" . _ERRMAIL1 . "\");\n";
		echo "Forma.postemail.focus();\n";
		echo "return false;\n";
		echo "}";
		echo "var t = Forma.postemail.value.search(\"@\");\n";
		echo "var k = Forma.postemail.value.search(\" \");\n";
		echo "if(Forma.postemail.value.indexOf('.') < 2) {\n";
		echo "alert(\"" . _ERRMAIL2 . "\");\n";
		echo "Forma.postemail.focus();\n";
		echo "return false;\n";
		echo "}\n";
		echo "if(k >= 0) {\n";
		echo "alert(\"" . _ERRMAIL2 . "\");\n";
		echo "Forma.postemail.focus();\n";
		echo "return false;\n";
		echo "}\n";
		echo "if(t <= -1) {\n";
		echo "alert(\"" . _ERRMAIL2 . "\");\n";
		echo "Forma.postemail.focus();\n";
		echo "return false;\n";
		echo "}\n";
		echo "if (Forma.gfx_checknew.value == \"\") {\n";
		echo "alert(\"" . _SECURITYCODE . " ?\");\n";
		echo "Forma.gfx_checknew.focus();\n";
		echo "return false;\n";
		echo "}\n";
	}
	echo "return true; \n";
	echo "}\n";
	echo "</script>\n";
}

/**
 * show()
 * 
 * @param mixed $pollid
 * @return
 */
function show( $pollid )
{
	global $prefix, $user_prefix, $db, $user, $cookie, $module_name, $bgcolor1, $bgcolor2, $pagenum, $adminfold, $adminfile, $index;
	$pollid = intval( $pollid );
	$index = 1;
	if ( ! isset($pollid) || $pollid == 0 )
	{
		Header( "Location: index.php" );
		exit();
	}
	$sql = "SELECT question, acomm FROM " . $prefix . "_nvvotings WHERE pollid='$pollid'";
	$result = $db->sql_query( $sql );
	if ( $numrows = $db->sql_numrows($result) != 1 )
	{
		Header( "Location: index.php" );
		exit;
	}
	$row = $db->sql_fetchrow( $result );
	$xquestion = explode( '|', $row['question'] );
	$title = stripslashes( FixQuotes($xquestion[0]) );
	$acomm = $row['acomm'];
	include ( "header.php" );

	echo "<br><b>" . _COMMENTSSUR . ":</b> <a href=\"modules.php?name=$module_name&op=pollvote&pollid=$pollid\"><b>$title</b></a><br><br><hr>";
	$subject = "Re: $title";
	OpenTable();
	if ( $acomm == 0 )
	{
		title( _MENUDISABLE );
	} elseif ( ! defined('IS_USER') && ($acomm == 2) )
	{
		echo "<center>" . _MEMBERRIQUIRED . "</center>";
	}
	else
	{
		global $mbrow;
		$username = $mbrow['username'];
		echo "<form action=\"modules.php?name=$module_name&amp;file=comments\" method=\"post\">";
		if ( $username == "" )
		{
			echo "<font class=option><b>" . _YOURNAME . ":</b></font><br>";
			echo "<input type=\"text\" name=\"postname\" size=\"62\"><br>";
			echo "<font class=option><b>" . _FYOUREMAIL . ":</b></font><br>";
			echo "<input type=\"text\" name=\"postemail\" size=\"62\"><br>";
			echo "<font class=option><b>" . _URL . ":</b></font><br>";
			echo "<input type=\"text\" name=\"posturl\" size=\"62\"><br>" . "<input type=\"hidden\" name=\"sender\" value=\"anon\">\n";
		}
		else
		{
			echo "<font class=option><b>" . _YOURNAME . ":</b> $username</font><br>";
			echo "<input type=\"hidden\" name=\"postname\" value=\"$username\">\n" . "<input type=\"hidden\" name=\"postemail\" value=\"\">\n" . "<input type=\"hidden\" name=\"posturl\" value=\"\">\n" . "<input type=\"hidden\" name=\"sender\" value=\"user\">\n";
		}
		echo "<font class=\"option\"><b>" . _SUBJECT . ":</b></font><br>";
		echo "<input type=\"text\" name=\"subject\" size=\"62\" value=\"$subject\"><br>";
		echo "<font class=\"option\"><b>" . _UCOMMENT . ":</b></font><br>" . "<textarea wrap=\"virtual\" cols=\"62\" rows=\"5\" name=\"comment\"></textarea><br><br>" . "<input type=\"hidden\" name=\"pollid\" value=\"$pollid\">\n" . "<input type=\"hidden\" name=\"op\" value=\"commreply\">\n";
		// add Scode
		if ( extension_loaded("gd") and (! defined('IS_USER')) )
		{
			echo "<b>" . _SECURITYCODE . ":</b> <img align=\"absmiddle\" width=\"73\" height=\"17\" src='?gfx=gfx' border='1' alt='" . _SECURITYCODE . "' title='" . _SECURITYCODE . "'><br\n";
			echo "<b>" . _TYPESECCODE . ":</b> <input type=\"text\" NAME=\"gfx_checknew\" SIZE=\"11\" MAXLENGTH=\"6\"><br>\n";
		}
		echo "<input type=\"submit\" value=\"" . _COMMENTREPLY . "\"></form>\n";
	}
	CloseTable();

	echo "<hr><br>";
	$numf = $db->sql_fetchrow( $db->sql_query("SELECT COUNT(*) FROM " . $prefix . "_nvvoting_comments WHERE pollid='$pollid'") );
	$page = ( isset($_GET['page']) ) ? intval( $_GET['page'] ) : 0;
	$all_page = ( $numf[0] ) ? $numf[0] : 1;
	$per_page = 20;
	$base_url = "modules.php?name=$module_name&file=comments&op=show&pollid=$pollid";
	if ( $numf[0] > 0 )
	{
		$sql = "SELECT tid, date, name, email, url, host_name, subject, comment FROM " . $prefix . "_nvvoting_comments WHERE pollid='$pollid' ORDER BY date desc LIMIT $page,$per_page";
		$result = $db->sql_query( $sql );


		while ( $row = $db->sql_fetchrow($result) )
		{
			$tid = $row['tid'];
			$send_date = $row['date'];
			$sender_name = $row['name'];
			$sql2 = "SELECT user_id, username FROM " . $user_prefix . "_users WHERE username='$sender_name'";
			$result2 = $db->sql_query( $sql2 );
			if ( $db->sql_numrows($result2) == 1 )
			{
				$row2 = $db->sql_fetchrow( $result2 );
				$user_id = $row2[user_id];
				$sender_name = "<a href=\"modules.php?name=Your_Account&op=userinfo&user_id=$user_id\">$sender_name</a>";

			}


			$sender_email = $row['email'];
			$sender_page = $row['url'];
			$sender_host = $row['host_name'];
			$com_title = $row['subject'];
			$com_text = $row['comment'];
			if ( $sender_email != "" )
			{
				$sender_email = "<a href=\"mailto:$sender_email\"><img border=\"0\" src=\"images/email.gif\" width=\"16\" height=\"16\"></a>";
			}
			else
			{
				$sender_email = "<img border=\"0\" src=\"images/email.gif\" width=\"16\" height=\"16\" title=\"" . _NOEMAIL . "\">";
			}
			if ( $sender_page != "" )
			{
				$sender_page = "<a href=\"$sender_page\" target=\"_blank\"><img border=\"0\" src=\"images/www.gif\" width=\"16\" height=\"16\"></a>";
			}
			else
			{
				$sender_page = "<img border=\"0\" src=\"images/www.gif\" width=\"16\" height=\"16\" title=\"" . _NOURL . "\">";
			}

			echo "<table border=\"0\" cellpadding=\"2\" cellspacing=\"1\" width=\"100%\" bgcolor=\"$bgcolor2\">\n" . "<tr><td bgcolor=\"$bgcolor1\">$a</td><td bgcolor=\"$bgcolor1\">$sender_email</td>\n" . "<td bgcolor=\"#FFFFFF\">$sender_page</td><td width=\"70%\" bgcolor=\"$bgcolor1\"><b>$sender_name</b></td>\n" . "<td width=\"30%\" bgcolor=\"$bgcolor1\"><p align=\"right\">$send_date</td>\n" . "</tr><tr><td width=\"100%\" bgcolor=\"$bgcolor1\" colspan=\"5\"><b>$com_title</b><br>$com_text</td></tr>\n";
			if ( defined('IS_ADMIN') )
			{
				echo "<tr><td width=\"100%\" bgcolor=\"$bgcolor1\" colspan=\"5\" align=\"center\">[ <a href=\"" . $adminfold . "/" . $adminfile . ".php?op=ConfigureBan&bad_ip=$sender_host\">$sender_host</a> | <a href=\"" . $adminfold . "/" . $adminfile . ".php?op=poll_del_comm&tid=$tid&pollid=$pollid\">" . _DELETE . "</a> ]</td></tr>";
			}
			echo "</table><br><br>";
			$a++;
		}
		echo "<br>";
		echo @generate_page( $base_url, $all_page, $per_page, $page );
	}
	include ( "footer.php" );
}

/**
 * savecomments()
 * 
 * @return
 */
function savecomments()
{
	global $prefix, $db, $user_prefix, $module_name, $index, $sitekey;
	//$pollid, $postname, $postemail, $posturl, $sender, $subject, $comment
	$pollid = intval( $_POST['pollid'] );
	$postname = FixQuotes( filter_text($_POST['postname'], "nohtml") );
	$postemail = FixQuotes( filter_text($_POST['postemail'], "nohtml") );
	$posturl = FixQuotes( filter_text($_POST['posturl'], "nohtml") );
	$subject = FixQuotes( filter_text($_POST['subject'], "nohtml") );
	$sender = FixQuotes( filter_text($_POST['sender'], "nohtml") );
	$comment = $_POST['comment'];
	$gfx_check = intval( $_POST['gfx_checknew'] );
	//them phan xu ly scode
	if ( extension_loaded("gd") and (! defined('IS_USER')) and (! nv_capcha_txt($gfx_check)) )
	{
		include ( "header.php" );
		$index = 1;
		OpenTable();
		echo "<br><br><p align=\"center\"><b>" . _SECCODEINCOR . "</b><br><br>" . _GOBACK . "</p><br><br>";
		CloseTable();
		include ( "footer.php" );
		exit;
	}
	//-----------------------
	if ( ! isset($pollid) || $pollid == 0 )
	{
		Header( "Location: index.php" );
		exit();
	}
	$f = 40;
	$e = explode( " ", $comment );
	for ( $a = 0; $a < sizeof($e); $a++ )
	{
		$o = strlen( $e[$a] );
	}
	$result = 0;
	if ( $subject == "" )
	{
		$result = 1;
		$eror = "" . _ACEROR1 . "";
	} elseif ( $comment == "" )
	{
		$result = 1;
		$eror = "" . _ACEROR2 . "";
	} elseif ( $o > 40 )
	{
		$result = 1;
		$eror = "" . _ACEROR3 . "";
	} elseif ( $postname == "" )
	{
		$result = 4;
		$eror = "" . _ACEROR4 . "";
	}
	if ( ($postname != "") && ($sender != "user") )
	{
		if ( $db->sql_numrows($db->sql_query("SELECT username FROM " . $user_prefix . "_users WHERE username='" . addslashes($postname) . "'")) > 0 )
		{
			$result = 4;
			$eror = "" . _NICKTAKEN . "";
		}
	}
	if ( $result != 0 )
	{
		$index = 1;
		info_exit( "<center><b>$eror<br><br>" . _GOBACK . "</b></center>" );
	}
	$comment = nl2brStrict( FixQuotes(filter_text($comment, "nohtml")) );
	$ip = $client_ip;
	if ( $sender == "user" )
	{
		$sql = "SELECT user_email, user_website, user_viewemail FROM " . $user_prefix . "_users WHERE username='" . addslashes( $postname ) . "'";
		$result = $db->sql_query( $sql );
		$row = $db->sql_fetchrow( $result );
		$posturl = $row['user_website'];
		$viewmail = $row['user_viewemail'];
		if ( $viewmail == 1 )
		{
			$postemail = $row['user_email'];
		}
	}
	$sql = "INSERT INTO " . $prefix . "_nvvoting_comments VALUES (NULL, '$pollid', now(), '$postname', '$postemail', '$posturl', '$ip', '$subject', '$comment')";
	$db->sql_query( $sql );
	$sql = "UPDATE " . $prefix . "_nvvotings SET totalcomm=totalcomm+1 WHERE pollid='$pollid'";
	$db->sql_query( $sql );
	Header( "Location: modules.php?name=$module_name&op=pollvote&pollid=$pollid" );
}

/**
 * poll_del_comm()
 * 
 * @param mixed $tid
 * @param mixed $pollid
 * @param integer $ok
 * @return
 */
function poll_del_comm( $tid, $pollid, $ok = 0 )
{
	global $prefix, $db, $user_prefix, $module_name, $index;
	if ( IS_ADMIN )
	{
		include_once ( "header.php" );
		$index = 1;
		if ( $ok == 1 )
		{
			$db->sql_query( "DELETE FROM " . $prefix . "_nvvoting_comments WHERE tid='$tid'" );
			$db->sql_query( "UPDATE " . $prefix . "_nvvotings SET totalcomm=totalcomm-1 WHERE pollid='$pollid'" );

			Header( "Location: modules.php?name=Voting&op=pollvote&pollid=$pollid" );
		}
		else
		{
			OpenTable();
			echo "<center><font class=\"title\"><b>" . _REMOVECOMMENTS . "</b></font></center>";
			CloseTable();
			echo "<br>";
			OpenTable();
			echo "<center>" . _SURETODELCOMMENTS . "";
			echo "<br><br>[ <a href=\"javascript:history.go(-1)\">" . _NO . "</a> | <a href=\"modules.php?name=$module_name&file=comments&op=poll_del_comm&tid=$tid&pollid=$pollid&ok=1\">" . _YES . "</a> ]</center>";
			CloseTable();
			include_once ( "footer.php" );
		}
	}
	else
	{
		Header( "Location: index.php" );
		exit();
	}
}

switch ( $op )
{

	case "commreply":
		savecomments();
		break;
	case "poll_del_comm":
		poll_del_comm( $tid, $pollid, $ok );
		break;
	case "show":
		show( $pollid );
		break;

	default:
		main( $pollid );
		break;

}

?>