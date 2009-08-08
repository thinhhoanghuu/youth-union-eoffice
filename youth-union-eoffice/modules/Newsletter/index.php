<?php

/*
* @Program:		NukeViet CMS
* @File name: 	NukeViet System
* @Version: 	2.0 RC1
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

$index = ( defined('MOD_BLTYPE') ) ? MOD_BLTYPE : 1;


/**
 * Newsletter()
 * 
 * @return
 */
function Newsletter()
{
	global $module_name;
	include ( "header.php" );
	OpenTable();
	echo "<center><b>" . _NEW_TITLE . "</b></center><br><br>" . "" . _NEW_WELCOME . "<br><br>" . "<center><form action=\"modules.php?name=$module_name\" method=\"post\">" . "<table border=\"0\"><tr><td>" . _NEW_EMAIL . ":</td><td colspan=\"2\">" . "<input type=\"text\" name=\"new_email\" value=\"\" size=\"41\" maxlength=\"30\"></td></tr><tr><td>" . "" . _NEW_CHOOSETYPE . ":</td><td><select name =\"new_type\">" . "<option name =\"new_type\" value=\"0\">" . _NEW_TYPETEXT . "</option>" . "<option name =\"new_type\" value=\"1\">" . _NEW_TYPEHTML . "</option>" . "</select></td><td align=\"right\">" . "<input type=\"hidden\" name=\"func\" value=\"action\">" . "<input type=\"submit\" value=\"" . _NEW_SENDIT . "\"></td></tr></table></form></center>";
	CloseTable();
	include ( "footer.php" );
}

/**
 * Action()
 * 
 * @param mixed $new_email
 * @param mixed $new_type
 * @return
 */
function Action( $new_email, $new_type )
{
	global $db, $prefix, $nukeurl, $adminmail, $module_name;
	$new_email = strtolower( $new_email );
	$actionletter = 1;
	if ( (! $new_email) || ($new_email == "") || (! eregi("^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,6}$", $new_email)) || (strrpos($new_email, ' ') > 0) )
	{
		$info = "" . _NEW_NOEMAIL . "";
		$actionletter = 0;
	}
	$numrow = $db->sql_numrows( $db->sql_query("SELECT * FROM " . $prefix . "_newsletter WHERE email='$new_email'") );
	if ( $numrow != 0 )
	{
		$info = "" . _NEW_ALREADY . "";
		$actionletter = 0;
	}
	if ( $actionletter == 0 )
	{
		include ( "header.php" );
		OpenTable();
		echo "<center>$info<br><br>" . "<a href=\"modules.php?name=$module_name&amp;func=def\"><b>" . _NEW_CLICKHERE . "</b></a></center>";
		echo "<META HTTP-EQUIV=\"refresh\" content=\"3;URL=modules.php?name=$module_name&amp;func=def\">";
		CloseTable();
		include ( "footer.php" );
		return;
	} elseif ( $actionletter == 1 )
	{
		srand( (double)microtime() * 1000000 );
		$mycode = rand();
		$time = time();
		list( $newest_uid ) = $db->sql_fetchrow( $db->sql_query("SELECT max(id) AS newest_uid FROM " . $prefix . "_newsletter") );
		if ( $newest_uid == "-1" )
		{
			$new_uid = 1;
		}
		else
		{
			$new_uid = $newest_uid + 1;
		}
		$result = $db->sql_query( "INSERT INTO " . $prefix . "_newsletter (id, email, status, html, checkkey, time, newsletterid) VALUES ('$new_uid', '$new_email', '1', '$new_type', '$mycode', '$time', '')" );
		if ( ! $result )
		{
			return;
		}
		$buildlink = "$nukeurl/modules.php?name=$module_name&func=confirm&new_email=$new_email&new_check=$mycode";
		$message = "" . _NEW_CONFTEXT . "\n\n$buildlink";
		$subject = "" . _NEW_SUBJECT . "";
		$mailhead = "From: $sitename <$adminmail>\n";
		$mailhead .= "Content-Type: text/plain; charset= " . _CHARSET . "\n";
		mail( $new_email, $subject, $message, $mailhead );
		include ( "header.php" );
		OpenTable();
		echo "<center>" . _NEW_SENOK . "<br><br>" . "<a href=\"modules.php?name=$module_name&amp;func=def\"><b>" . _NEW_GOINDEX . "</b></a></center>";
		CloseTable();
		include ( "footer.php" );
	}
}

/**
 * Confirm()
 * 
 * @param mixed $new_email
 * @param mixed $new_check
 * @return
 */
function Confirm( $new_email, $new_check )
{
	global $db, $prefix, $module_name;
	$past = time() - 86400;
	$db->sql_query( "DELETE FROM " . $prefix . "_newsletter WHERE (time < '$past' AND status='1')" );
	$db->sql_query( "OPTIMIZE TABLE " . $prefix . "_newsletter" );
	if ( $db->sql_numrows($db->sql_query("SELECT * FROM " . $prefix . "_newsletter WHERE (status='1' AND email='$new_email' AND checkkey = '$new_check')")) != 1 )
	{
		include ( "header.php" );
		OpenTable();
		echo "<center>" . _NEW_SUBNOT . "<br><br>" . "<a href=\"modules.php?name=$module_name&amp;func=def\">" . _NEW_CLICKHERE . "</a></center>";
		CloseTable();
		include ( "footer.php" );
		return;
	}
	srand( (double)microtime() * 1000000 );
	$mycode = rand();
	$query = $db->sql_query( "UPDATE " . $prefix . "_newsletter SET status='2', checkkey = '$mycode' WHERE email='$new_email'" );
	if ( ! $query )
	{
		return;
	}
	include ( "header.php" );
	OpenTable();
	echo "<center>" . _NEW_SUBOK . "<br>" . "<a href=\"modules.php?name=$module_name&amp;func=def\"><b>" . _NEW_GOINDEX . "</b></a></center>";
	CloseTable();
	include ( "footer.php" );
}

/**
 * Delletter()
 * 
 * @param mixed $del_email
 * @param mixed $del_check
 * @return
 */
function Delletter( $del_email, $del_check )
{
	global $db, $prefix, $module_name;
	if ( $db->sql_numrows($db->sql_query("SELECT * FROM " . $prefix . "_newsletter WHERE (email='$del_email' AND checkkey = '$del_check')")) != 1 )
	{
		include ( "header.php" );
		OpenTable();
		echo "<center>" . _NEW_UNSUBNOT . "</center>";
		CloseTable();
		echo "<META HTTP-EQUIV=\"refresh\" content=\"3;URL=index.php\">";
		include ( "footer.php" );
		return;
	}
	$query = $db->sql_query( "DELETE FROM " . $prefix . "_newsletter WHERE (email='$del_email' AND checkkey = '$del_check')" );
	if ( ! $query )
	{
		return;
	}
	include ( "header.php" );
	OpenTable();
	echo "<center>" . _NEW_UNSBUOK . "</center>";
	CloseTable();
	echo "<META HTTP-EQUIV=\"refresh\" content=\"3;URL=index.php\">";
	include ( "footer.php" );
}

switch ( $func )
{
	case 'confirm':
		Confirm( $new_email, $new_check );
		break;
	case 'action':
		Action( $new_email, $new_type );
		break;
	case 'delletter':
		Delletter( $del_email, $del_check );
		break;
	default:
		Newsletter();
		break;
}

?>
