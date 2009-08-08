<?php

/*
* @Program:		NukeViet CMS v2.0 RC1
* @File name: 	frend.php @ Module News
* @Version: 	2.0
* @Date: 		01.05.2009
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
############################################

if ( (defined('IS_ADMMOD')) || ($newsfriend == 1) || ($newsfriend == 2 and (defined('IS_USER'))) )
{

	/**
	 * FriendSend()
	 * 
	 * @param mixed $sid
	 * @return
	 */
	function FriendSend( $sid )
	{
		global $mbrow, $prefix, $db, $user_prefix, $module_name, $sitename, $newsfriend;
		if ( (defined('IS_ADMMOD')) || ($newsfriend == 1) || ($newsfriend == 2 and (defined('IS_USER'))) )
		{
			$sid = intval( $sid );
			if ( (stristr($REQUEST_URI, "mainfile")) || (! isset($sid)) )
			{
				exit;
			}
			$result = $db->sql_query( "SELECT title FROM " . $prefix . "_stories WHERE sid='$sid'" );
			if ( $numrows = $db->sql_numrows($result) != 1 )
			{
				exit;
			}
			$row = $db->sql_fetchrow( $result );
			$title = stripslashes( check_html($row['title'], "nohtml") );
			echo "<html>\n<head>\n" . "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=" . _CHARSET . "\">\n" . "<title>" . _FRIEND . "</title>\n</head>\n<body bgcolor=\"#ECF3FB\">\n<h3>$title</h3>\n" . "<hr size=\"1\" color=\"#DC0312\"><center>$sitename - " . _FRIEND . "<hr size=\"1\" color=\"#DC0312\">\n" . "<form method=\"POST\" action=\"modules.php?name=$module_name&amp;file=friend\">\n" . "<input type=\"hidden\" name=\"sid\" value=\"$sid\">\n";
			if ( defined('IS_USER') )
			{
				$ye = $mbrow['user_email'];
				echo "<input type=\"hidden\" name=\"yname\" value=\"$mbrow[username]\">\n" . "<input type=\"hidden\" name=\"ymail\" value=\"$ye\">\n";
			}
			else
			{
				echo "" . _FYOURNAME . ":<br>\n<input type=\"text\" name=\"yname\" size=\"30\" maxlength=\"30\"><br>\n" . "" . _FYOUREMAIL . ":<br>\n<input type=\"text\" name=\"ymail\" size=\"30\" maxlength=\"30\"><br>\n";
			}
			echo "" . _FFRIENDEMAIL . ":<br>\n" . "<input type=\"text\" name=\"fmail\" size=\"30\" maxlength=\"30\"><br>" . "<input type=\"hidden\" name=\"op\" value=\"SendStory\">\n" . "<input type=\"submit\" value=\"" . _SEND . "\"><input type=\"reset\" value=\"" . _RESET . "\">\n" . "</form>\n<hr size=\"1\" color=\"#DC0312\"><button onclick=\"window.close();\">" . _CLOSEWIN . "</button></center><hr size=\"1\" color=\"#DC0312\">\n" . "</body>\n</html>";
		}
		else
		{
			exit;
		}
	}

	/**
	 * SendStory()
	 * 
	 * @param mixed $sid
	 * @param mixed $yname
	 * @param mixed $ymail
	 * @param mixed $fmail
	 * @return
	 */
	function SendStory( $sid, $yname, $ymail, $fmail )
	{
		global $admin, $user, $nukeurl, $sitename, $datetime, $prefix, $db, $module_name, $adminmail, $newsfriend;
		if ( (defined('IS_ADMMOD')) || ($newsfriend == 1) || ($newsfriend == 2 and (defined('IS_USER'))) )
		{
			$sid = intval( $sid );
			if ( (stristr($REQUEST_URI, "mainfile")) || (! isset($sid)) )
			{
				exit;
			}
			if ( (strlen($yname) > 30) || (! $yname) || ($yname == "") || (! $ymail) || ($ymail == "") || (! eregi("^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,6}$", $ymail)) || (strrpos($ymail, ' ') > 0) || (! $fmail) || ($fmail == "") || (! eregi("^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,6}$", $fmail)) || (strrpos($fmail, ' ') > 0) )
			{
				echo "<html>\n<head>\n" . "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=" . _CHARSET . "\">\n" . "<title>" . _FRIEND . "</title>\n</head>\n<body bgcolor=\"#ECF3FB\">\n" . "<hr size=\"1\" color=\"#DC0312\"><center>$sitename - " . _FRIEND . "<hr size=\"1\" color=\"#DC0312\">\n" . "<br><br><center><font class=\"content\">" . _ACEROR5 . "<br><br>" . _GOBACK . "</font></center><br><br>\n" . "</form>\n<hr size=\"1\" color=\"#DC0312\"><button onclick=\"window.close();\">" . _CLOSEWIN . "</button></center><hr size=\"1\" color=\"#DC0312\">\n" . "</body>\n</html>";
				exit();
			}
			$result = $db->sql_query( "SELECT * FROM " . $prefix . "_stories WHERE sid='$sid'" );
			if ( $numrows = $db->sql_numrows($result) != 1 )
			{
				exit;
			}
			$row = $db->sql_fetchrow( $result );
			$title = stripslashes( check_html($row['title'], "nohtml") );
			$time = formatTimestamp( $row['time'] );
			$hometext = stripslashes( $row['hometext'] );
			$bodytext = stripslashes( $row['bodytext'] );
			$notes = stripslashes( $row['notes'] );
			$fmail = stripslashes( removecrlf($fmail) );
			$yname = stripslashes( removecrlf($yname) );
			$ymail = stripslashes( removecrlf($ymail) );
			$message = "
<html>
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=" . _CHARSET . "\">
<title>$sitename</title>
</head>
<body link=\"#4D5764\" vlink=\"#4D5764\" alink=\"#4D5764\" text=\"#4D5764\">
<center>
<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\">
<tr>
<td><b><font size=\"4\">$sitename: " . _FRIEND . "</font></b></td>
<td align=\"right\"><font size=\"4\"><a href=\"$nukeurl\">$nukeurl</a></font></td>
</tr>
</table>
<hr color=\"#DC0312\" width=\"600\">
<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\">
<tr>
<td><b><font size=\"5\">$title</font></b>
<p align=\"justify\"><b>$hometext</b><br>
$bodytext<br>
<i>$notes</i></td>
</tr>
</table>
<hr color=\"#DC0312\" width=\"600\">
<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\">
<tr>
<td align=\"center\">" . _THEURL . ":<a href=\"$nukeurl/modules.php?name=$module_name&file=article&sid=$sid\">$nukeurl/modules.php?name=$module_name&amp;file=article&amp;sid=$sid</a></td>
</tr>
<tr>
<td align=\"center\">" . _YOURFRIEND . ": <b>$yname (<a href=\"mailto:$ymail\">$ymail</a>)</b></td>
</tr>
</table>
<hr color=\"#4D5764\" width=\"600\">
<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\">
<tr>
<td><font size=\"2\">&copy; </font> <a href=\"$nukeurl\"><font size=\"2\">$sitename</font></a></td>
<td align=\"right\"><font size=\"2\">contact: </font> <a href=\"mailto:$adminmail\"><font size=\"2\">$adminmail</font></a></td>
</tr>
</table>
</center>
</body>
</html>
    ";
			$subject = "" . _INTERESTING . " $sitename";
			$mailhead = "From: \"$yname\" <$ymail>\n";
			$mailhead .= "Content-Type: text/html; charset= " . _CHARSET . "\n";
			mail( $fmail, $subject, $message, $mailhead );
			Header( "Location: modules.php?name=$module_name&file=friend&op=StorySent" );
		}
		else
		{
			exit;
		}
	}

	/**
	 * StorySent()
	 * 
	 * @return
	 */
	function StorySent()
	{
		global $sitename, $admin, $user, $newsfriend;
		if ( (defined('IS_ADMMOD')) || ($newsfriend == 1) || ($newsfriend == 2 and (defined('IS_USER'))) )
		{
			$title = urldecode( $title );
			$fmail = urldecode( $fmail );
			echo "<html>\n<head>\n" . "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=" . _CHARSET . "\">\n" . "<title>" . _FRIEND . "</title>\n</head>\n<body bgcolor=\"#ECF3FB\">\n<h3>$title</h3>\n" . "<hr size=\"1\"><center>$sitename - " . _FRIEND . "<hr size=\"1\">\n" . "<br><br><center><font class=\"content\">" . _THANKS . "</font></center><br><br>\n" . "<hr size=\"1\"><button onclick=\"window.close();\">" . _CLOSEWIN . "</button></center><hr size=\"1\">\n" . "</body>\n</html>";
		}
		else
		{
			exit;
		}
	}

	switch ( $op )
	{

		case "SendStory":
			SendStory( $sid, $yname, $ymail, $fmail );
			break;

		case "StorySent":
			StorySent();
			break;

		case "FriendSend":
			FriendSend( $sid );
			break;

	}
}
else
{
	exit;
}

?>