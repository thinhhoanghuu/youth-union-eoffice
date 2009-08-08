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

if ( ! defined('NV_ADMIN') )
{
	die( "Access Denied" );
}

$checkmodname = "Your_Account";
$adm_access = checkmodac( "" . $checkmodname . "" );
if ( $adm_access == 1 )
{
	if ( file_exists("language/" . $checkmodname . "_" . $currentlang . ".php") )
	{
		include_once ( "language/" . $checkmodname . "_" . $currentlang . ".php" );
	}
	if ( file_exists("../$datafold/config_" . $checkmodname . ".php") )
	{
		include_once ( "../$datafold/config_" . $checkmodname . ".php" );
	}
	define( "IMG_PATH", "../images/modules/" . $checkmodname . "/" );


	/**
	 * admlistuser()
	 * 
	 * @return
	 */
	function admlistuser()
	{

		global $datafold, $adminfile, $db, $prefix, $user_prefix;
		include ( "../header.php" );

		admusertitle();

		$limitshow = 50;

		$show = $_GET['show'];
		if ( ($show == "") || ($show == 0) )
		{
			$show = 1;
		}
		$sqlshow = $show - 1;
		$sqlshow *= $limitshow;
		$result = $db->sql_query( "SELECT * FROM " . $user_prefix . "_users WHERE user_id!=1 ORDER BY user_id ASC LIMIT $sqlshow,$limitshow" );
		$rowvid = $db->sql_numrows( $result );
		$result2 = $db->sql_query( "SELECT * FROM " . $user_prefix . "_users WHERE user_id!=1 ORDER BY user_id ASC" );
		$rowvid2 = $db->sql_numrows( $result2 );
		if ( $rowvid != "0" )
		{
			OpenTable();
			echo "<center><B>" . _MEMBERS_LIST . "</b></center></br>";
			echo "<Table border=1 align=\"Center\" size=\"100%\">";
			echo "<tr><td height=\"25\" align=\"center\" width=\"20\"><b>ID</b></td><td align=\"center\" width=\"200\"><b>&nbsp; UserID</B></td>" . "<td align=\"center\" width=\"200\"><b>&nbsp; User name</b></td>" . "<td align=\"center\" colspan=\"2\"><b>&nbsp;Function</b></td></tr> ";
			while ( $row = $db->sql_fetchrow($result) )
			{

				echo "<tr><td height=\"25\" align=\"center\" width=\"20\">$row[user_id]</td><td width=\"200\">&nbsp; $row[username]</td>" . "<td width=\"200\">&nbsp; $row[lastname] $row[name]</td>" . "<td>&nbsp;<a href=\"" . $adminfile . ".php?op=modifyUser&chng_uid=$row[user_id] \"" . " title=\"ID: $row[user_id]\nEmail: $row[user_email]\">" . _NV_USERINFORMATION . "</a>&nbsp;</td>" . "<td>&nbsp;<a href=\"" . $adminfile . ".php?op=delUser&chng_uid=$row[user_id] \"" . " title=\"ID: $row[user_id]\nEmail: $row[user_email]\">" . _NV_USERDELETE . "</a>&nbsp;" . "</td></tr> ";

			}
			echo "</table><BR>";

			$pages = ceil( $rowvid2 / $limitshow );
			echo "<center><b>\n";

			if ( $show > 1 )
			{
				$p = $show - 1;
				echo "      <a href=\"admin.php?op=listUser&amp;show=" . $p . "\">" . _PREVIOUS . "</a> \n";
			}
			else
			{
				echo "      &nbsp;\n";
			}

			pnav( $show, $pages );

			if ( $show < $pages )
			{
				$show += 1;
				echo "      <a href=\"admin.php?op=listUser&amp;show=" . $show . "\">" . _NEXT . "</a>\n";
			}
			else
			{
				echo "      &nbsp;\n";
			}
			echo "      <br><strong>" . _PAGE . " " . $show . "/" . $pages . "</strong>\n";
			echo "</center></b>\n";
			CloseTable();
		}
		include ( "../footer.php" );

	}

	/**
	 * pnav()
	 * 
	 * @param mixed $d
	 * @param mixed $pages
	 * @return
	 */
	function pnav( $d, $pages )
	{

		$pageB = $d;
		$pageF = $d;
		$countB = 0;
		$content = "";

		if ( $_GET['op'] == "listUser" )
		{
			$navlink = "admin.php?op=listUser";
		}

		while ( ($countB != 3) && ($pageB != 1) )
		{
			$countB += 1;
			$pageB -= 1;
			$content = "<a href=\"" . $navlink . "&amp;show=$pageB\">$pageB</a> " . $content . "";
		}

		$content = "" . $content . " <a href=\"" . $navlink . "&amp;show=$d\">[$d]</a>";

		$count2 = 3;
		$remainder = 3 - $countB;
		$count2 += $remainder;
		$countF = 0;

		while ( ($countF != $count2) && ($pageF != $pages) )
		{
			$countF += 1;
			$pageF += 1;
			$content = "" . $content . " <a href=\"" . $navlink . "&amp;show=$pageF\">$pageF</a>";
		}
		echo "      " . $content . "\n";
	}

	/**
	 * admusertitle()
	 * 
	 * @return
	 */
	function admusertitle()
	{
		global $adminfile;
		OpenTable();
		echo "<a name='conf'></a>";
		echo "<center><b><a href=\"" . $adminfile . ".php?op=mod_users\">" . _NV_USERADMIN . "</a></b><br>\n<br>\n" . "<a href=\"" . $adminfile . ".php?op=UsersConfig\"><img border=\"0\" src=\"" . IMG_PATH . "user_config.gif\" width=\"16\" height=\"16\"> " . _NV_USERCONFIG . "</a> \n" . "<a href=\"" . $adminfile . ".php?op=addUser\"><img border=\"0\" src=\"" . IMG_PATH . "user_add.gif\" width=\"16\" height=\"16\"> " . _ADDUSER . "</a> \n" . "<a href=\"" . $adminfile . ".php?op=modifyUser\"><img border=\"0\" src=\"" . IMG_PATH . "user_information.gif\" width=\"16\" height=\"16\"> " . _NV_USERINFORMATION . "</a> \n" . "<br>\n" . "<a href=\"" . $adminfile . ".php?op=delUser\"><img border=\"0\" src=\"" . IMG_PATH . "user_delete.gif\" width=\"16\" height=\"16\"> " . _NV_USERDELETE . "</a> \n" . "<a href=\"" . $adminfile . ".php?op=modifyUserTemp\"><img border=\"0\" src=\"" . IMG_PATH . "user_active.gif\" width=\"16\" height=\"16\"> " . _NV_USERACTIVE . "</a> \n" . "<a href=\"" . $adminfile .
			".php?op=listUser\"><img border=\"0\" src=\"" . IMG_PATH . "user_active.gif\" width=\"16\" height=\"16\"> " . _MEMBERS_LIST . "</a> \n" . "</center>\n";
		CloseTable();
		echo "<br>\n";
	}

	/**
	 * search_user()
	 * 
	 * @param mixed $sqltable
	 * @param mixed $go_result
	 * @return
	 */
	function search_user( $sqltable, $go_result )
	{
		global $adminfile;
		OpenTable();
		echo "<center><font class=\"title\"><b>" . _USERSEARCH . "</b></font></center><br>\n";
		echo "<form method=\"POST\" action=\"" . $adminfile . ".php\">\n" . "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" align=\"center\">\n<tr>\n" . "<td><select name=\"check\">\n" . "<option name=\"check\" value=\"\">" . _SEARCHUNAME . "</option>\n" . "<option name=\"check\" value=\"1\">" . _SEARCHEMAIL . "</option>\n" . "<option name=\"check\" value=\"2\">" . _SEARCHID . "</option>\n" . "</select></td>\n" . "<td><input type=\"text\" name=\"key\" size=\"25\"></td>\n" . "<input type=\"hidden\" name=\"sqltable\" value=\"$sqltable\">" . "<input type=\"hidden\" name=\"go_result\" value=\"$go_result\">" . "<input type=\"hidden\" name=\"op\" value=\"go_search_user\">" . "<td><input type=\"submit\" value=\"Tìm ki?m\"></td>\n" . "</tr>\n</table>\n</form>\n";
		CloseTable();
		echo "<br>\n";
	}

	/**
	 * go_search_user()
	 * 
	 * @param mixed $check
	 * @param mixed $key
	 * @param mixed $sqltable
	 * @param mixed $go_result
	 * @return
	 */
	function go_search_user( $check, $key, $sqltable, $go_result )
	{
		global $adminfile, $user_prefix, $db;
		if ( (! $sqltable) or ($sqltable == "") or (! $go_result) or ($go_result == "") )
		{
			Header( "Location: " . $adminfile . ".php" );
		}
		if ( $sqltable != "users_temp" )
		{
			$sqltable = "users";
		}
		if ( (! $key) or ($key == "") )
		{
			Header( "Location: " . $adminfile . ".php?op=$go_result" );
		}

		if ( $check == 1 )
		{
			$check = "user_email like '%$key%'";
		}
		else
			if ( $check == 2 )
			{
				if ( $sqltable != "users_temp" )
				{
					$check = "user_id like '%$key%' AND user_id!='1'";
				}
				else
				{
					$check = "user_id like '%$key%'";
				}
			}
			else
			{
				$check = "username like '%$key%' AND username!='Anonymous'";
			}
			$sql = "SELECT * FROM " . $user_prefix . "_$sqltable WHERE $check";
		$result = $db->sql_query( $sql );
		$num = $db->sql_numrows( $result );
		if ( $num == 0 )
		{
			include ( "../header.php" );

			admusertitle();
			search_user( $sqltable, $go_result );
			OpenTable();
			echo "<center><font class=\"title\"><b>" . _NOUSERSEARCHRESULT . "</b></font></center>";
			CloseTable();
			include ( "../footer.php" );
		}
		else
			if ( $num == 1 )
			{
				$row = $db->sql_fetchrow( $result );
				Header( "Location: " . $adminfile . ".php?op=$go_result&chng_uid=$row[user_id]" );
			}
			else
			{
				include ( "../header.php" );

				admusertitle();
				search_user( $sqltable, $go_result );
				OpenTable();
				echo "<center><font class=\"title\"><b>" . _USERSEARCHRESULT . "</b></font></center><br><br><b>" . _USERSEARCHRESULT2 . "</b>:<br>\n";
				echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"90%\" align=\"center\">";
				$a = 1;
				while ( $row = $db->sql_fetchrow($result) )
				{
					echo "<tr><td>$a.</td><td><a href=\"" . $adminfile . ".php?op=$go_result&chng_uid=$row[user_id]\">$row[username]</a></td><td>ID: $row[user_id]</td><td>Email: $row[user_email]</td></tr>\n";
					$a++;
				}
				echo "</table>";
				CloseTable();
				include ( "../footer.php" );
			}
	}

	/**
	 * NV_userCheck()
	 * 
	 * @param mixed $username
	 * @return
	 */
	function NV_userCheck( $username )
	{
		global $datafold, $nick_max, $nick_min, $stop, $user_prefix, $db, $bad_nick;
		if ( (! $username) || ($username == "") || (ereg("[^a-zA-Z0-9_-]", $username)) ) $stop = "<center>" . _ERRORINVNICK . "</center><br>";
		if ( strlen($username) > $nick_max ) $stop = "<center>" . _NICK2LONG . "</center>";
		if ( strlen($username) < $nick_min ) $stop = "<center>" . _NICKADJECTIVE . "</center>";
		$bad_nicks = explode( "|", $bad_nick );
		foreach ( $bad_nicks as $bad_nick )
		{
			if ( eregi($bad_nick, $username) ) $stop = "<center>" . _NAMERESTRICTED . "</center><br>";
		}
		if ( strrpos($username, ' ') > 0 ) $stop = "<center>" . _NICKNOSPACES . "</center>";
		if ( $db->sql_numrows($db->sql_query("SELECT username FROM " . $user_prefix . "_users WHERE username='$username'")) > 0 ) $stop = "<center>" . _NICKTAKEN . "</center><br>";
		if ( $db->sql_numrows($db->sql_query("SELECT username FROM " . $user_prefix . "_users_temp WHERE username='$username'")) > 0 ) $stop = "<center>" . _NICKTAKEN . "</center><br>";
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
		global $bad_mail, $datafold, $stop, $user_prefix, $db;
		$user_email = strtolower( $user_email );
		if ( (! $user_email) || ($user_email == "") || (! eregi("^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,6}$", $user_email)) ) $stop = "<center>" . _ERRORINVEMAIL . "</center><br>";
		if ( strrpos($user_email, ' ') > 0 ) $stop = "<center>" . _ERROREMAILSPACES . "</center>";
		$bad_email = explode( "|", $bad_mail );
		foreach ( $bad_email as $bad_mail )
		{
			if ( eregi($bad_mail, $user_email) ) $stop = "<center>" . _MAILBLOCKED . " <b>$user_email</b></center><br>";
		}
		if ( $db->sql_numrows($db->sql_query("SELECT user_email FROM " . $user_prefix . "_users WHERE user_email='$user_email'")) > 0 ) $stop = "<center>" . _EMAILREGISTERED . "</center><br>";
		if ( $db->sql_numrows($db->sql_query("SELECT user_email FROM " . $user_prefix . "_users WHERE user_email='" . md5($user_email) . "'")) > 0 ) $stop = "<center>" . _EMAILNOTUSABLE . "</center><br>";
		if ( $db->sql_numrows($db->sql_query("SELECT user_email FROM " . $user_prefix . "_users_temp WHERE user_email='$user_email'")) > 0 ) $stop = "<center>" . _EMAILREGISTERED . "</center><br>";
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
		global $datafold, $pass_max, $pass_min, $stop;
		if ( strlen($user_pass1) > $pass_max ) $stop = "<center>" . _PASSLENGTH . "</center><br>";
		if ( strlen($user_pass1) < $pass_min ) $stop = "<center>" . _PASSLENGTH1 . "</center><br>";
		if ( $user_pass1 != $user_pass2 ) $stop = "<center>" . _PASSWDNOMATCH . "</center><br>";
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
	 * displayUsers()
	 * 
	 * @return
	 */
	function displayUsers()
	{
		global $datafold, $adminfile, $db, $prefix, $user_prefix;
		include ( "../header.php" );
		GraphicAdmin();
		admusertitle();
		search_user( $sqltable = "users", $go_result = "modifyUser" );
		$udt = array();
		if ( file_exists("../$datafold/ulist.php") )
		{
			include_once ( "../$datafold/ulist.php" );
		}
		$numrows = sizeof( $udt );
		$Today = time() - 86400;

		$sql = "SELECT user_id, username, user_email FROM " . $user_prefix . "_users WHERE user_regdate >= $Today  AND user_id!=1 ORDER BY user_id DESC";
		$result = $db->sql_query( $sql );
		$userCount = $db->sql_numrows( $result );
		$tusersname = "";
		if ( $userCount > 0 )
		{
			$tusersname .= ": ";
			$z = 1;
			while ( $row = $db->sql_fetchrow($result) )
			{
				if ( $z < $userCount )
				{
					$tusersname .= "<a href=\"" . $adminfile . ".php?op=modifyUser&chng_uid=$row[user_id] \" title=\"ID: $row[user_id]\nEmail: $row[user_email]\">$row[username]</a>, ";
				}
				else
					if ( $z == $userCount )
					{
						$tusersname .= "<a href=\"" . $adminfile . ".php?op=modifyUser&chng_uid=$row[user_id]\" title=\"ID: $row[user_id]\nEmail: $row[user_email]\">$row[username]</a>.";
					}
				$z++;
			}
		}
		$homqua = $Today - 86400;
		$sql2 = "SELECT user_id, username, user_email FROM " . $user_prefix . "_users WHERE user_regdate > $homqua AND user_regdate < $Today AND user_id!=1 ORDER BY user_id DESC";
		$result2 = $db->sql_query( $sql2 );
		$userCount2 = $db->sql_numrows( $result2 );
		$tusersname2 = "";
		if ( $userCount2 > 0 )
		{
			$tusersname2 .= ": ";
			$a = 1;
			while ( $row2 = $db->sql_fetchrow($result2) )
			{
				if ( $a < $userCount2 )
				{
					$tusersname2 .= "<a href=\"" . $adminfile . ".php?op=modifyUser&chng_uid=$row2[user_id]\" title=\"ID: $row2[user_id]\nEmail: $row2[user_email]\">$row2[username]</a>, ";
				}
				else
					if ( $a == $userCount2 )
					{
						$tusersname2 .= "<a href=\"" . $adminfile . ".php?op=modifyUser&chng_uid=$row2[user_id]\" title=\"ID: $row2[user_id]\nEmail: $row2[user_email]\">$row2[username]</a>.";
					}
				$a++;
			}
		}
		$sqltemp = "SELECT user_id, username, user_email FROM " . $user_prefix . "_users_temp";
		$resulttemp = $db->sql_query( $sqltemp );
		$numrowstemp = $db->sql_numrows( $resulttemp );
		$tempusers = "";
		if ( $numrowstemp > 0 )
		{
			$tempusers .= "<br>";
			$o = 1;
			while ( $rowtemp = $db->sql_fetchrow($resulttemp) )
			{
				if ( $o < $numrowstemp )
				{
					$tempusers .= "<a href=\"" . $adminfile . ".php?op=modifyUserTemp&chng_uid=$rowtemp[user_id]\" title=\"ID: $rowtemp[user_id]\nEmail: $rowtemp[user_email]\">$rowtemp[username]</a>, ";
				}
				else
					if ( $o == $numrowstemp )
					{
						$tempusers .= "<a href=\"" . $adminfile . ".php?op=modifyUserTemp&chng_uid=$rowtemp[user_id]\" title=\"ID: $rowtemp[user_id]\nEmail: $rowtemp[user_email]\">$rowtemp[username]</a>.";
					}
				$o++;
			}
		}

		if ( ($numrows > 0) or ($numrowstemp > 0) )
		{
			OpenTable();
			echo "<center><font class=\"option\"><b>" . _NV_INFOUSERS . "</b></font></center><br>";
			echo "<table border=\"1\" cellpadding=\"3\" cellspacing=\"3\" width=\"100%\">\n<tr>\n";
			if ( $numrows > 0 )
			{
				echo "<td valign=\"top\">\n" . "<b>" . _NV_TOTALUSERS . "</b>: $numrows<br>\n" . "<b>" . _NV_TD . "</b> ($userCount)$tusersname<br>\n" . "<b>" . _NV_YD . "</b> ($userCount2)$tusersname2\n" . "</td>\n";
			}
			if ( $numrowstemp > 0 )
			{
				echo "<td valign=\"top\">\n" . "<b>" . _NV_TOTALUSERSTEMP . "</b>: $numrowstemp$tempusers\n" . "</td>\n";
			}
			echo "</tr>\n</table>\n";
			CloseTable();
			echo "<br>";
		}
		include ( "../footer.php" );
	}

	/**
	 * UsersConfig()
	 * 
	 * @return
	 */
	function UsersConfig()
	{
		global $allowuserreg, $allowuserlogin, $useactivate, $nick_max, $nick_min, $pass_max, $pass_min, $expiring, $userredirect, $sendmailuser, $send2mailuser, $allowmailchange, $bad_mail, $bad_nick, $suspend_nick, $adminfile, $datafold;
		include ( "../header.php" );

		admusertitle();
		OpenTable();
		echo "<center><font class=\"option\"><b>" . _GENSITEINFO . "</b></font><br>" . "<form action=\"" . $adminfile . ".php\" method=\"post\">" . "<table border=\"0\"><tr><td>" . "" . _NV_ALLOWUSERREG . ":</td><td>";
		if ( $allowuserreg == 1 )
		{
			echo "<input type=\"radio\" name=\"allowuserreg\" value=\"1\" checked>" . _YES . " &nbsp;" . "<input type=\"radio\" name=\"allowuserreg\" value=\"0\">" . _NO . "";
		}
		else
		{
			echo "<input type=\"radio\" name=\"allowuserreg\" value=\"1\">" . _YES . " &nbsp;" . "<input type=\"radio\" name=\"allowuserreg\" value=\"0\" checked>" . _NO . "";
		}
		echo "</td></tr><tr><td>" . "" . _NV_ALLOWUSERLOGIN . ":</td><td>";
		if ( $allowuserlogin == 1 )
		{
			echo "<input type=\"radio\" name=\"allowuserlogin\" value=\"1\" checked>" . _YES . " &nbsp;" . "<input type=\"radio\" name=\"allowuserlogin\" value=\"0\">" . _NO . "";
		}
		else
		{
			echo "<input type=\"radio\" name=\"allowuserlogin\" value=\"1\">" . _YES . " &nbsp;" . "<input type=\"radio\" name=\"allowuserlogin\" value=\"0\" checked>" . _NO . "";
		}
		echo "</td></tr><tr><td>" . "" . _NV_USEACTIVATE . ":</td><td><select name=\"useactivate\">";
		$xuseactivate = array( _NV_USEACTIVATE0, _NV_USEACTIVATE1, _NV_USEACTIVATE2 );
		for ( $d = 0; $d <= 2; $d++ )
		{
			$seld = "";
			if ( $d == $useactivate )
			{
				$seld = " selected";
			}
			echo "<option name=\"useactivate\" value=\"$d\" $seld>$xuseactivate[$d]</option>\n";
		}
		echo "</select></td></tr><tr><td>" . "" . _NV_NICKMAX . ":</td><td><select name=\"nick_max\">";
		for ( $i = 8; $i <= 30; $i++ )
		{
			$sel = "";
			if ( $i == $nick_max )
			{
				$sel = " selected";
			}
			echo "<option name=\"nick_max\"$sel>$i</option>\n";
		}
		echo "</select></td></tr><tr><td>" . "" . _NV_NICKMIN . ":</td><td><select name=\"nick_min\">";
		for ( $a = 1; $a <= 10; $a++ )
		{
			$selm = "";
			if ( $a == $nick_min )
			{
				$selm = " selected";
			}
			echo "<option name=\"nick_min\"$selm>$a</option>\n";
		}
		echo "</select></td></tr><tr><td>" . "" . _NV_PASSMAX . ":</td><td><select name=\"pass_max\">";
		for ( $b = 8; $b <= 30; $b++ )
		{
			$selb = "";
			if ( $b == $pass_max )
			{
				$selb = " selected";
			}
			echo "<option name=\"pass_max\"$selb>$b</option>\n";
		}
		echo "</select></td></tr><tr><td>" . "" . _NV_PASSMIN . ":</td><td><select name=\"pass_min\">";
		for ( $c = 1; $c <= 10; $c++ )
		{
			$selc = "";
			if ( $c == $pass_min )
			{
				$selc = " selected";
			}
			echo "<option name=\"pass_min\"$selc>$c</option>\n";
		}
		echo "</select></td></tr><tr><td>" . "" . _NV_EXPIRING . ":</td><td><select name=\"expiring\">";
		for ( $e = 1; $e <= 72; $e++ )
		{
			$sele = "";
			if ( $e == $expiring / 3600 )
			{
				$sele = " selected";
			}
			echo "<option name=\"expiring\"$sele>$e</option>\n";
		}
		echo "</select></td></tr><tr><td>" . "" . _NV_USERREDIRECT . ":</td><td><select name=\"userredirect\">" . "<option name=\"userredirect\" value=\"\" ";
		if ( $userredirect == "" ) echo "selected";
		echo ">" . _NV_USERREDIRECT0 . "</option>\n" . "<option name=\"userredirect\" value=\"home\" ";
		if ( $userredirect == "home" ) echo "selected";
		echo ">" . _NV_USERREDIRECT1 . "</option>\n";
		$handle = opendir( '../modules' );
		while ( $file = readdir($handle) )
		{
			if ( ! ereg("[.]", $file) )
			{
				$ModulFound = $file;
				$moduleslist .= "$ModulFound ";
			}
		}
		closedir( $handle );
		$moduleslist = explode( " ", $moduleslist );
		sort( $moduleslist );
		for ( $i = 0; $i < sizeof($moduleslist); $i++ )
		{
			if ( $moduleslist[$i] != "" )
			{
				echo "<option name=\"userredirect\" value=\"$moduleslist[$i]\" ";
				if ( $moduleslist[$i] == $userredirect ) echo "selected";
				echo ">" . ucfirst( $moduleslist[$i] ) . "\n";
			}
		}
		echo "</select></td></tr><tr><td>" . "" . _NV_ADMINEMAILUSER . ":</td><td>";
		if ( $sendmailuser == 1 )
		{
			echo "<input type=\"radio\" name=\"sendmailuser\" value=\"1\" checked>" . _YES . " &nbsp;" . "<input type=\"radio\" name=\"sendmailuser\" value=\"0\">" . _NO . "";
		}
		else
		{
			echo "<input type=\"radio\" name=\"sendmailuser\" value=\"1\">" . _YES . " &nbsp;" . "<input type=\"radio\" name=\"sendmailuser\" value=\"0\" checked>" . _NO . "";
		}
		echo "</td></tr><tr><td>" . "" . _NV_ADMINEMAILUSER2 . ":</td><td>";
		if ( $send2mailuser == 1 )
		{
			echo "<input type=\"radio\" name=\"send2mailuser\" value=\"1\" checked>" . _YES . " &nbsp;" . "<input type=\"radio\" name=\"send2mailuser\" value=\"0\">" . _NO . "";
		}
		else
		{
			echo "<input type=\"radio\" name=\"send2mailuser\" value=\"1\">" . _YES . " &nbsp;" . "<input type=\"radio\" name=\"send2mailuser\" value=\"0\" checked>" . _NO . "";
		}
		echo "</td></tr><tr><td>";
		echo "" . _ALLOWMAILCHANGE . "</td><td>";
		if ( $allowmailchange == 1 )
		{
			echo "<input type=\"radio\" name=\"allowmailchange\" value=\"1\" checked>" . _YES . " &nbsp;" . "<input type=\"radio\" name=\"allowmailchange\" value=\"0\">" . _NO . "";
		}
		else
		{
			echo "<input type=\"radio\" name=\"allowmailchange\" value=\"1\">" . _YES . " &nbsp;" . "<input type=\"radio\" name=\"allowmailchange\" value=\"0\" checked>" . _NO . "";
		}
		$bad_mail = str_replace( "|", ", ", $bad_mail );
		echo "</td></tr><tr><td valign=\"top\">" . "" . _BADMAILLIST . ":</td><td>";
		echo "<textarea rows=\"4\" name=\"bad_mail\" cols=\"40\">$bad_mail</textarea>";
		$bad_nick = str_replace( "|", ", ", $bad_nick );
		echo "</td></tr><tr><td valign=\"top\">" . "" . _BADNICKLIST . ":</td><td>";
		echo "<textarea rows=\"4\" name=\"bad_nick\" cols=\"40\">$bad_nick</textarea>";
		$suspend_nick = str_replace( "|", ", ", $suspend_nick );
		if ( isset($_GET['chng_uid']) )
		{
			$udt = array();
			if ( file_exists("../$datafold/ulist.php") )
			{
				include_once ( "../$datafold/ulist.php" );
			}
			$unm = explode( "|", $udt[intval($_GET['chng_uid'])] );
			$unm = $unm[0];
			if ( $unm != "" and ! eregi("$unm", $suspend_nick) )
			{
				if ( $suspend_nick != "" )
				{
					$suspend_nick .= ", " . $unm . "";
				}
				else
				{
					$suspend_nick .= "" . $unm . "";
				}
			}
		}
		echo "<a name='susp'></a>";
		echo "</td></tr><tr><td valign=\"top\">" . "" . _SUSPENDNICKLIST . ":</td><td>";
		echo "<textarea rows=\"4\" name=\"suspend_nick\" cols=\"40\">$suspend_nick</textarea>";
		echo "</td></tr></table><br><br>" . "<input type=\"hidden\" name=\"op\" value=\"UsersConfigSave\">" . "<center><input type=\"submit\" value=\"" . _SAVECHANGES . "\"></center>" . "</form></center>";
		CloseTable();
		include ( "../footer.php" );
	}

	/**
	 * UsersConfigSave()
	 * 
	 * @return
	 */
	function UsersConfigSave()
	{
		global $adminfile, $datafold, $checkmodname;
		$allowuserreg = intval( $_POST['allowuserreg'] );
		$allowuserlogin = intval( $_POST['allowuserlogin'] );
		$useactivate = intval( $_POST['useactivate'] );
		$nick_max = intval( $_POST['nick_max'] );
		$nick_min = intval( $_POST['nick_min'] );
		$pass_max = intval( $_POST['pass_max'] );
		$pass_min = intval( $_POST['pass_min'] );
		$expiring = intval( $_POST['expiring'] );
		$userredirect = FixQuotes( $_POST['userredirect'] );
		$sendmailuser = intval( $_POST['sendmailuser'] );
		$send2mailuser = intval( $_POST['send2mailuser'] );
		$allowmailchange = intval( $_POST['allowmailchange'] );
		$bad_mail = trim( $_POST['bad_mail'] );
		$bad_nick = trim( $_POST['bad_nick'] );
		$suspend_nick = trim( $_POST['suspend_nick'] );

		$expiring = $expiring * 3600;
		$bad_mail = str_replace( " ", "", $bad_mail );
		$bad_mail = str_replace( ",", "|", $bad_mail );
		$bad_nick = str_replace( " ", "", $bad_nick );
		$bad_nick = str_replace( ",", "|", $bad_nick );
		$suspend_nick = str_replace( " ", "", $suspend_nick );
		$suspend_nick = str_replace( ",", "|", $suspend_nick );

		@chmod( "../$datafold/config_" . $checkmodname . ".php", 0777 );
		@$file = fopen( "../$datafold/config_" . $checkmodname . ".php", "w" );

		$content = "<?php\n\n";
		$fctime = date( "d-m-Y H:i:s", filectime("../$datafold/config_" . $checkmodname . ".php") );
		$fmtime = date( "d-m-Y H:i:s" );
		$content .= "// File: config_" . $checkmodname . ".php.\n// Created: $fctime.\n// Modified: $fmtime.\n// Do not change anything in this file!\n\n";
		$content .= "if ((!defined('NV_SYSTEM')) AND (!defined('NV_ADMIN'))) {\n";
		$content .= "die();\n";
		$content .= "}\n";
		$content .= "\n";
		$content .= "\$allowuserreg = $allowuserreg;\n";
		$content .= "\$allowuserlogin = $allowuserlogin;\n";
		$content .= "\$useactivate = $useactivate;\n";
		$content .= "\$nick_max = $nick_max;\n";
		$content .= "\$nick_min = $nick_min;\n";
		$content .= "\$pass_max = $pass_max;\n";
		$content .= "\$pass_min = $pass_min;\n";
		$content .= "\$expiring = $expiring;\n";
		$content .= "\$userredirect = \"$userredirect\";\n";
		$content .= "\$sendmailuser = $sendmailuser;\n";
		$content .= "\$send2mailuser = $send2mailuser;\n";
		$content .= "\$allowmailchange = $allowmailchange;\n";
		$content .= "\$bad_mail = \"$bad_mail\";\n";
		$content .= "\$bad_nick = \"$bad_nick\";\n";
		$content .= "\$suspend_nick = \"$suspend_nick\";\n";
		$content .= "\n";
		$content .= "?>";

		@$writefile = fwrite( $file, $content );
		@fclose( $file );
		@chmod( "../$datafold/config_" . $checkmodname . ".php", 0604 );
		include ( "../header.php" );
		GraphicAdmin();
		admusertitle();
		OpenTable();
		echo "<center><b>" . _NV_SAVECONFIG . "</b></center>";
		echo "<META HTTP-EQUIV=\"refresh\" content=\"2;URL=" . $adminfile . ".php?op=UsersConfig#conf\">";
		CloseTable();
		include ( "../footer.php" );
	}

	/**
	 * addUser()
	 * 
	 * @return
	 */
	function addUser()
	{
		global $adminfile, $datafold;
		include ( "../header.php" );

		admusertitle();
		OpenTable();
		echo "<center><font class=\"option\"><b>" . _ADDUSER . "</b></font><br><br>" . "<form action=\"" . $adminfile . ".php\" method=\"post\">" . "<table border=\"0\" width=\"100%\">" . "<tr><td width=\"100\">" . _NICKNAME . ": </td>" . "<td><input type=\"text\" name=\"add_uname\" size=\"30\" maxlength=\"$nick_max\"> <font class=\"tiny\">" . _REQUIRED . "</font></td></tr>" . "<tr><td>" . _FIRSTNAME . ": </td>" . "<td><input type=\"text\" name=\"add_name\" size=\"30\" maxlength=\"50\"></td></tr>" . "<tr><td>" . _LASTNAME . ": </td>" . "<td><input type=\"text\" name=\"add_lastname\" size=\"30\" maxlength=\"50\"></td></tr>" . "<tr><td>" . _VIEWNAME . "</td>" . "<td><input type=\"text\" name=\"add_viewuname\" size=\"30\" maxlength=\"50\"></td></tr>" . "<tr><td>" . _EMAIL . ": </td>" . "<td><input type=\"text\" name=\"add_email\" size=\"30\" maxlength=\"60\"> <font class=\"tiny\">" . _REQUIRED . "</font></td></tr>" . "<tr><td>" . _URL . ": </td>" . "<td><input type=\"text\" name=\"add_url\" size=\"30\" maxlength=\"60\"></td></tr>" .
			"<tr><td>" . _ICQ . ": </td>" . "<td><input type=\"text\" name=\"add_user_icq\" size=\"20\" maxlength=\"20\"></td></tr>" . "<tr><td>" . _TELEPHONE . ": </td>" . "<td><input type=\"text\" name=\"add_user_telephone\" size=\"20\" maxlength=\"20\"></td></tr>" . "<tr><td>" . _LOCATION . ": </td>" . "<td><input type=\"text\" name=\"add_user_from\" size=\"25\" maxlength=\"60\"></td></tr>" . "<tr><td>" . _INTERESTS . ": </td>" . "<td><input type=\"text\" name=\"add_user_intrest\" size=\"25\" maxlength=\"255\"></td></tr>" . "<tr><td>" . _OPTION . ": </td>" . "<td><input type=\"checkbox\" name=\"add_user_viewemail\" VALUE=\"1\"> " . _ALLOWUSERS . "</td></tr>" . "<tr><td>" . _SIGNATURE . ": </td>" . "<td><textarea name=\"add_user_sig\" rows=\"6\" cols=\"45\"></textarea></td></tr>" . "<tr><td>" . _PASSWORD . ": </td>" . "<td><input type=\"password\" name=\"add_pass\" size=\"12\" maxlength=\"$pass_max\"> <font class=\"tiny\">" . _REQUIRED . "</font></td></tr>" . "<tr><td>" . _RETYPEPASSWORD .
			": </td>" . "<td><input type=\"password\" name=\"add_pass2\" size=\"12\" maxlength=\"$pass_max\"> <font class=\"tiny\">" . _REQUIRED . "</font></td></tr>" . "<input type=\"hidden\" name=\"op\" value=\"addUserSave\">" . "<tr><td></td><td><br><input type=\"submit\" value=\"" . _ADDUSERBUT . "\"></form></td></tr>" . "</table>";
		CloseTable();
		include ( "../footer.php" );
	}

	/**
	 * addUserSave()
	 * 
	 * @param mixed $add_uname
	 * @param mixed $add_name
	 * @param mixed $add_lastname
	 * @param mixed $add_viewuname
	 * @param mixed $add_email
	 * @param mixed $add_url
	 * @param mixed $add_user_icq
	 * @param mixed $add_user_telephone
	 * @param mixed $add_user_from
	 * @param mixed $add_user_intrest
	 * @param mixed $add_user_viewemail
	 * @param mixed $add_user_sig
	 * @param mixed $add_pass
	 * @param mixed $add_pass2
	 * @return
	 */
	function addUserSave( $add_uname, $add_name, $add_lastname, $add_viewuname, $add_email, $add_url, $add_user_icq, $add_user_telephone, $add_user_from, $add_user_intrest, $add_user_viewemail, $add_user_sig, $add_pass, $add_pass2 )
	{
		global $adminfile, $datafold, $stop, $user_prefix, $db, $adminmail, $sitename;
		include ( "../header.php" );
		$add_email = strtolower( $add_email );
		NV_userCheck( $add_uname );
		NV_mailCheck( $add_email );
		NV_passCheck( $add_pass, $add_pass2 );
		if ( ! isset($stop) )
		{
			$add_uname = check_html( $add_uname, nohtml );
			$add_name = check_html( $add_name, nohtml );
			$add_lastname = check_html( $add_lastname, nohtml );
			$add_viewuname = check_html( $add_viewuname, nohtml );
			$add_email = check_html( $add_email, nohtml );
			$add_url = check_html( $add_url, nohtml );
			if ( ! eregi("http://", $add_url) and $add_url != "" )
			{
				$add_url = "http://$add_url";
			}
			$add_user_icq = check_html( $add_user_icq, nohtml );
			$add_user_telephone = check_html( $add_user_telephone, nohtml );
			$add_user_from = check_html( $add_user_from, nohtml );
			$add_user_intrest = check_html( $add_user_intrest, nohtml );
			$add_user_viewemail = intval( $add_user_viewemail );
			$add_user_sig = htmlspecialchars( $add_user_sig );
			$user_password = $add_pass;
			$add_pass = md5( $add_pass );
			$user_regdate = time();
			$a = $db->sql_query( "INSERT INTO " . $user_prefix . "_users (user_id, name, lastname, username, viewuname, user_email, user_website, user_regdate, user_icq, user_telephone, user_from, user_interests, user_viewemail, user_sig, user_password, user_avatar, user_avatar_type) VALUES (NULL, '" . $add_name . "', '$add_lastname', '$add_uname', '$add_viewuname', '$add_email', '$add_url', '$user_regdate', '$add_user_icq', '$add_user_telephone', '$add_user_from', '$add_user_intrest', '$add_user_viewemail', '$add_user_sig', '$add_pass', 'gallery/blank.gif', '3')" );
			if ( ! $a )
			{

				admusertitle();
				OpenTable();
				echo "'name = $add_name', '$add_lastname', '$add_uname', '$add_viewuname', '$add_email', '$add_url', '$user_regdate', '$add_user_icq', '$add_user_telephone', '$add_user_from', '$add_user_intrest', '$add_user_viewemail', '$add_user_sig', '$add_pass'";
				echo "<center><b>" . _ERRORSQL . "</b><br><br>" . _GOBACK . "</center>";
				CloseTable();
				include ( "../footer.php" );
				return;
			}
			else
			{
				ulist();
				if ( $sendmailuser == 1 )
				{
					$message = _WELCOMETO . " $sitename!\r\n\r\n";
					$message .= _YOUUSEDEMAIL . " ($add_email) " . _TOREGISTER . " $sitename.\r\n\r\n";
					$message .= _FOLLOWINGMEM . "\r\n" . _UNICKNAME . " $add_uname\r\n" . _UPASSWORD . " $user_password";
					$subject = _ACCOUNTCREATED;
					$mailhead = "From: $sitename <$adminmail>\n";
					$mailhead .= "Content-Type: text/plain; charset= " . _CHARSET . "\n";
					@mail( $add_email, $subject, $message, $mailhead );
				}
			}

			admusertitle();
			OpenTable();
			echo "<center><b>" . _ACCOUNTCREATED2 . "</b></center>";
			echo "<META HTTP-EQUIV=\"refresh\" content=\"2;URL=" . $adminfile . ".php?op=mod_users\">";
			CloseTable();
			include ( "../footer.php" );
		}
		else
		{

			admusertitle();
			OpenTable();
			echo "<center><br>$stop<br><br>" . _GOBACK . "<br></center>";
			CloseTable();
			include ( "../footer.php" );
			return;
		}
	}

	/**
	 * modifyUser()
	 * 
	 * @param mixed $chng_uid
	 * @return
	 */
	function modifyUser( $chng_uid )
	{
		global $adminfile, $datafold, $user_prefix, $db;
		include ( "../header.php" );

		admusertitle();
		search_user( $sqltable = "users", $go_result = "modifyUser" );
		$chng_uid = intval( $chng_uid );
		$sql = "SELECT * FROM " . $user_prefix . "_users WHERE user_id='$chng_uid'";
		$result = $db->sql_query( $sql );
		$member_num = $db->sql_numrows( $result );
		$row = $db->sql_fetchrow( $result );
		if ( $member_num == 1 )
		{
			$chng_uid = $row[user_id];
			$chng_name = $row[name];
			$chng_lastname = $row[lastname];
			$chng_uname = $row[username];
			$chng_viewuname = $row[viewuname];
			$chng_email = $row[user_email];
			$chng_url = $row[user_website];
			$chng_user_icq = $row[user_icq];
			$chng_user_telephone = $row[user_telephone];
			$chng_user_from = $row[user_from];
			$chng_user_intrest = $row[user_interests];
			$chng_user_viewemail = $row[user_viewemail];
			$chng_user_sig = $row[user_sig];
			$chng_pass = $row[user_password];
			OpenTable();
			echo "<center><font class=\"option\"><b>" . _USERUPDATE . ": $chng_uid</b></font></center><br>" . "<center><form action=\"" . $adminfile . ".php\" method=\"post\">" . "<table border=\"0\">" . "<tr><td>" . _NICKNAME . "</td>" . "<td><input type=\"text\" name=\"chng_uname\" value=\"$chng_uname\"> <font class=\"tiny\">" . _REQUIRED . "</font></td></tr>" . "<tr><td>" . _FIRSTNAME . "</td>" . "<td><input type=\"text\" name=\"chng_name\" value=\"$chng_name\"></td></tr>" . "<tr><td>" . _LASTNAME . "</td>" . "<td><input type=\"text\" name=\"chng_lastname\" value=\"$chng_lastname\"></td></tr>" . "<tr><td>" . _VIEWNAME . "</td>" . "<td><input type=\"text\" name=\"chng_viewuname\" value=\"$chng_viewuname\"></td></tr>" . "<tr><td>" . _URL . "</td>" . "<td><input type=\"text\" name=\"chng_url\" value=\"$chng_url\" size=\"30\" maxlength=\"60\"></td></tr>" . "<tr><td>" . _EMAIL . "</td>" . "<td><input type=\"text\" name=\"chng_email\" value=\"$chng_email\" size=\"30\" maxlength=\"60\"> <font class=\"tiny\">" .
				_REQUIRED . "</font></td></tr>" . "<tr><td>" . _ICQ . "</td>" . "<td><input type=\"text\" name=\"chng_user_icq\" value=\"$chng_user_icq\" size=\"20\" maxlength=\"20\"></td></tr>" . "<tr><td>" . _TELEPHONE . "</td>" . "<td><input type=\"text\" name=\"chng_user_telephone\" value=\"$chng_user_telephone\" size=\"20\" maxlength=\"20\"></td></tr>" . "<tr><td>" . _LOCATION . "</td>" . "<td><input type=\"text\" name=\"chng_user_from\" value=\"$chng_user_from\" size=\"25\" maxlength=\"60\"></td></tr>" . "<tr><td>" . _INTERESTS . "</td>" . "<td><input type=\"text\" name=\"chng_user_intrest\" value=\"$chng_user_intrest\" size=\"25\" maxlength=\"255\"></td></tr>" . "<tr><td>" . _OPTION . "</td>";
			if ( $chng_user_viewemail == 1 )
			{
				echo "<td><input type=\"checkbox\" name=\"chng_user_viewemail\" value=\"1\" checked> " . _ALLOWUSERS . "</td></tr>";
			}
			else
			{
				echo "<td><input type=\"checkbox\" name=\"chng_user_viewemail\" value=\"1\"> " . _ALLOWUSERS . "</td></tr>";
			}
			echo "<tr><td>" . _SIGNATURE . "</td>" . "<td><textarea name=\"chng_user_sig\" rows=\"6\" cols=\"45\">$chng_user_sig</textarea></td></tr>" . "<tr><td>" . _PASSWORD . "</td>" . "<td><input type=\"password\" name=\"chng_pass\" size=\"12\" maxlength=\"$pass_max\"> <font class=\"tiny\">" . _FORCHANGES . "</font></td></tr>" . "<tr><td>" . _RETYPEPASSWD . "</td>" . "<td><input type=\"password\" name=\"chng_pass2\" size=\"12\" maxlength=\"$pass_max\"></td></tr>" . "<input type='hidden' name='old_uname' value='" . $row['username'] . "'>\n" . "<input type='hidden' name='old_email' value='" . $row['user_email'] . "'>\n" . "<input type=\"hidden\" name=\"chng_uid\" value=\"$chng_uid\">" . "<input type=\"hidden\" name=\"op\" value=\"updateUser\">" . "<tr><td>&nbsp;</td><td><input type=\"submit\" value=\"" . _SAVECHANGES . "\"></form></td></tr>" . "</table></center>";
			CloseTable();
		}
		include ( "../footer.php" );
	}

	/**
	 * updateUser()
	 * 
	 * @param mixed $chng_uid
	 * @param mixed $chng_uname
	 * @param mixed $chng_name
	 * @param mixed $chng_lastname
	 * @param mixed $chng_viewuname
	 * @param mixed $chng_url
	 * @param mixed $chng_email
	 * @param mixed $chng_user_icq
	 * @param mixed $chng_user_telephone
	 * @param mixed $chng_user_from
	 * @param mixed $chng_user_intrest
	 * @param mixed $chng_user_viewemail
	 * @param mixed $chng_user_sig
	 * @param mixed $chng_pass
	 * @param mixed $chng_pass2
	 * @param mixed $old_uname
	 * @param mixed $old_email
	 * @return
	 */
	function updateUser( $chng_uid, $chng_uname, $chng_name, $chng_lastname, $chng_viewuname, $chng_url, $chng_email, $chng_user_icq, $chng_user_telephone, $chng_user_from, $chng_user_intrest, $chng_user_viewemail, $chng_user_sig, $chng_pass, $chng_pass2, $old_uname, $old_email )
	{
		global $adminfile, $stop, $user_prefix, $db;
		if ( $chng_uname != $old_uname )
		{
			NV_userCheck( $chng_uname );
		}
		if ( $chng_email != $old_email )
		{
			NV_mailCheck( $chng_email );
		}
		if ( $chng_pass != "" )
		{
			NV_passCheck( $chng_pass, $chng_pass2 );
		}

		if ( $stop == "" )
		{
			$chng_uname = check_html( $chng_uname, nohtml );
			$chng_name = check_html( $chng_name, nohtml );
			$chng_lastname = check_html( $chng_lastname, nohtml );
			$chng_viewuname = check_html( $chng_viewuname, nohtml );
			$chng_url = check_html( $chng_url, nohtml );
			$chng_email = check_html( $chng_email, nohtml );
			$chng_user_icq = check_html( $chng_user_icq, nohtml );
			$chng_user_telephone = check_html( $chng_user_telephone, nohtml );
			$chng_user_from = check_html( $chng_user_from, nohtml );
			$chng_user_intrest = check_html( $chng_user_intrest, nohtml );
			$chng_user_sig = htmlspecialchars( $chng_user_sig );
			$chng_user_viewemail = intval( $chng_user_viewemail );
			$chng_uid = intval( $chng_uid );
			if ( ! eregi("http://", $chng_url) and $chng_url != "" )
			{
				$chng_url = "http://$chng_url";
			}
			if ( $chng_pass != "" )
			{
				$cpass = md5( $chng_pass );
				$db->sql_query( "update " . $user_prefix . "_users set username='$chng_uname', name='$chng_name', lastname='$chng_lastname', viewuname='$chng_viewuname', user_email='$chng_email', user_website='$chng_url', user_icq='$chng_user_icq', user_telephone='$chng_user_telephone', user_from='$chng_user_from', user_interests='$chng_user_intrest', user_viewemail='$chng_user_viewemail', user_sig='$chng_user_sig', user_password='$cpass' where user_id='$chng_uid'" );
			}
			else
			{
				$db->sql_query( "update " . $user_prefix . "_users set username='$chng_uname', name='$chng_name', lastname='$chng_lastname', viewuname='$chng_viewuname', user_email='$chng_email', user_website='$chng_url', user_icq='$chng_user_icq', user_telephone='$chng_user_telephone', user_from='$chng_user_from', user_interests='$chng_user_intrest', user_viewemail='$chng_user_viewemail', user_sig='$chng_user_sig' where user_id='$chng_uid'" );
			}
			ulist();
			Header( "Location: " . $adminfile . ".php?op=modifyUser&chng_uid=$chng_uid" );
		}
		else
		{
			include ( "../header.php" );

			admusertitle();
			OpenTable();
			echo "<center><br>$stop<br><br>" . _GOBACK . "<br></center>";
			CloseTable();
			include ( "../footer.php" );
			return;
		}
	}

	/**
	 * delUser()
	 * 
	 * @param mixed $chng_uid
	 * @return
	 */
	function delUser( $chng_uid )
	{
		global $adminfile, $user_prefix, $db;
		include ( "../header.php" );

		admusertitle();
		search_user( $sqltable = "users", $go_result = "delUser" );
		$sql = "SELECT username FROM " . $user_prefix . "_users WHERE user_id='" . intval( $chng_uid ) . "'";
		$result = $db->sql_query( $sql );
		$member_num = $db->sql_numrows( $result );
		if ( $member_num == 1 )
		{
			$row = $db->sql_fetchrow( $result );
			OpenTable();
			echo "<center><font class=\"option\"><b>" . _DELETEUSER . "</b></font><br><br>" . "" . _NOYESDELUSER . " <b>$row[username]</b> (ID " . intval( $chng_uid ) . ") ?<br><br>" . "[ <a href=\"" . $adminfile . ".php?op=delUserConf&amp;del_uid=$chng_uid\">" . _YES . "</a> | <a href=\"" . $adminfile . ".php?op=mod_users\">" . _NO . "</a> ]</center>";
			CloseTable();
		}
		include ( "../footer.php" );
	}

	/**
	 * delUserConf()
	 * 
	 * @param mixed $del_uid
	 * @return
	 */
	function delUserConf( $del_uid )
	{
		global $send2mailuser, $adminfile, $datafold, $user_prefix, $db, $sitename, $adminmail;
		if ( (! $del_uid) or ($del_uid == "") )
		{
			Header( "Location: " . $adminfile . ".php?op=delUser" );
			exit();
		}
		$sql = "SELECT * FROM " . $user_prefix . "_users WHERE user_id='" . intval( $del_uid ) . "'";
		$result = $db->sql_query( $sql );
		$member_num = $db->sql_numrows( $result );
		if ( $member_num == 1 )
		{
			if ( $send2mailuser == 1 )
			{
				$row = $db->sql_fetchrow( $result );
				$del_mail = $row[user_email];
				$message = "$sitename " . _DELUSERMAIL1 . ":\r\n\r\n";
				$message .= "" . _DELUSERMAIL2 . " ($row[username]) " . _DELUSERMAIL3 . " $adminmail.\r\n\r\n";
				$subject = "" . _DELUSERMAIL4 . "";
				$mailhead = "From: $sitename <$adminmail>\n";
				$mailhead .= "Content-Type: text/plain; charset= " . _CHARSET . "\n";
				mail( $del_mail, $subject, $message, $mailhead );
			}
			$db->sql_query( "delete from " . $user_prefix . "_users where user_id='$del_uid'" );
			ulist();
		}
		Header( "Location: " . $adminfile . ".php?op=mod_users" );
	}

	/**
	 * modifyUserTemp()
	 * 
	 * @param mixed $chng_uid
	 * @return
	 */
	function modifyUserTemp( $chng_uid )
	{
		global $pass_max, $adminfile, $datafold, $user_prefix, $db;
		include ( "../header.php" );

		admusertitle();
		search_user( $sqltable = "users_temp", $go_result = "modifyUserTemp" );
		if ( isset($chng_uid) )
		{
			$sql = "SELECT * FROM " . $user_prefix . "_users_temp WHERE user_id='" . intval( $chng_uid ) . "'";
			$result = $db->sql_query( $sql );
			$member_num = $db->sql_numrows( $result );
			$row = $db->sql_fetchrow( $result );
			if ( $member_num == 1 )
			{
				$chng_uid = $row[user_id];
				$chng_uname = $row[username];
				$chng_viewuname = $row[viewuname];
				$chng_email = $row[user_email];
				$chng_pass = $row[user_password];
				OpenTable();
				echo "<center><font class=\"option\"><b>" . _USERTEMPUPDATE . ": $chng_uid2</b></font><br>" . "<form action=\"" . $adminfile . ".php\" method=\"post\">" . "<table border=\"0\">" . "<tr><td>" . _NICKNAME . "</td>" . "<td><input type=\"text\" name=\"chng_uname\" value=\"$chng_uname\"> <font class=\"tiny\">" . _REQUIRED . "</font></td></tr>" . "<tr><td>" . _VIEWNAME . "</td>" . "<td><input type=\"text\" name=\"chng_viewuname\" value=\"$chng_viewuname\"></td></tr>" . "<tr><td>" . _EMAIL . "</td>" . "<td><input type=\"text\" name=\"chng_email\" value=\"$chng_email\"> <font class=\"tiny\">" . _REQUIRED . "</font></td></tr>" . "<tr><td>" . _PASSWORD . "</td>" . "<td><input type=\"password\" name=\"chng_pass\" size=\"12\" maxlength=\"$pass_max\"> <font class=\"tiny\">" . _FORCHANGES . "</font></td></tr>" . "<tr><td>" . _RETYPEPASSWD . "</td>" . "<td><input type=\"password\" name=\"chng_pass2\" size=\"12\" maxlength=\"$pass_max\"></td></tr>" . "<input type='hidden' name='old_uname' value='" .
					$row['username'] . "'>\n" . "<input type='hidden' name='old_email' value='" . $row['user_email'] . "'>\n" . "<input type='hidden' name='chng_opros' value='" . $row['opros'] . "'>\n" . "<input type=\"hidden\" name=\"chng_uid\" value=\"$chng_uid\">" . "<tr><td>&nbsp;</td><td><select name=\"op\">\n" . "<option name=\"op\" value=\"updateUserTemp\">" . _SAVECHANGES . "</option>\n" . "<option name=\"op\" value=\"actUserTemp\">" . _ACTIVUSERTEMP . "</option>\n" . "<option name=\"op\" value=\"delUserTemp\">" . _DELUSERTEMP . "</option>\n" . "</select>&nbsp;<input type=\"submit\" value=\"" . _OPTION . "\"></form></td></tr>" . "</table></center>";
				CloseTable();
			}
		}
		include ( "../footer.php" );
	}

	/**
	 * updateUserTemp()
	 * 
	 * @return
	 */
	function updateUserTemp()
	{
		global $adminfile, $stop, $user_prefix, $db;
		$chng_uname = check_html( $_POST['chng_uname'], nohtml );
		$old_uname = check_html( $_POST['old_uname'], nohtml );
		$chng_viewuname = check_html( $_POST['chng_viewuname'], nohtml );
		$chng_email = check_html( $_POST['chng_email'], nohtml );
		$old_email = check_html( $_POST['old_email'], nohtml );
		$chng_pass = check_html( $_POST['chng_pass'], nohtml );
		$chng_pass2 = check_html( $_POST['chng_pass2'], nohtml );
		$chng_opros = check_html( $_POST['chng_opros'], nohtml );
		$chng_uid = intval( $_POST['chng_uid'] );
		if ( $chng_uname != $old_uname )
		{
			NV_userCheck( $chng_uname );
		}
		if ( $chng_email != $old_email )
		{
			NV_mailCheck( $chng_email );
		}
		if ( $chng_pass != "" or $chng_pass2 != "" )
		{
			NV_passCheck( $chng_pass, $chng_pass2 );
		}

		if ( $stop == "" )
		{
			if ( $chng_pass != "" )
			{
				$cpass = md5( $chng_pass );
				$db->sql_query( "update " . $user_prefix . "_users_temp set username='$chng_uname', viewuname='$chng_viewuname', user_email='$chng_email', user_password='$cpass', opros='$chng_opros' where user_id='$chng_uid'" );
			}
			else
			{
				$db->sql_query( "update " . $user_prefix . "_users_temp set username='$chng_uname', viewuname='$chng_viewuname', user_email='$chng_email', opros='$chng_opros' where user_id='$chng_uid'" );
			}
			Header( "Location: " . $adminfile . ".php?op=modifyUserTemp" );
		}
		else
		{
			include ( "../header.php" );

			admusertitle();
			OpenTable();
			echo "<center><br>$stop<br><br>" . _GOBACK . "<br></center>";
			CloseTable();
			include ( "../footer.php" );
			return;
		}
	}

	/**
	 * delUserTemp()
	 * 
	 * @param mixed $chng_uid
	 * @return
	 */
	function delUserTemp( $chng_uid )
	{
		global $adminfile, $user_prefix, $db;
		include ( "../header.php" );

		admusertitle();
		search_user( $sqltable = "users_temp", $go_result = "delUserTemp" );
		$sql = "SELECT * FROM " . $user_prefix . "_users_temp WHERE user_id='" . intval( $chng_uid ) . "'";
		$result = $db->sql_query( $sql );
		$member_num = $db->sql_numrows( $result );
		if ( $member_num == 1 )
		{
			$row = $db->sql_fetchrow( $result );
			OpenTable();
			echo "<center><font class=\"option\"><b>" . _DELETEUSER . "</b></font><br><br>" . "" . _NOYESDELUSER2 . " <b>" . $row[username] . "</b>?<br><br>" . "[ <a href=\"" . $adminfile . ".php?op=delUserTempConf&amp;del_uid=" . intval( $chng_uid ) . "\">" . _YES . "</a> | <a href=\"" . $adminfile . ".php?op=mod_users\">" . _NO . "</a> ]</center>";
			CloseTable();
		}
		include ( "../footer.php" );
	}

	/**
	 * delUserTempConf()
	 * 
	 * @param mixed $del_uid
	 * @return
	 */
	function delUserTempConf( $del_uid )
	{
		global $send2mailuser, $adminfile, $user_prefix, $db, $sitename, $adminmail;
		if ( (! $del_uid) or ($del_uid == "") )
		{
			Header( "Location: " . $adminfile . ".php?op=delUserTemp" );
			exit();
		}
		$sql = "SELECT * FROM " . $user_prefix . "_users_temp WHERE user_id='" . intval( $del_uid ) . "'";
		$result = $db->sql_query( $sql );
		$member_num = $db->sql_numrows( $result );
		if ( $member_num == 1 )
		{
			if ( $send2mailuser == 1 )
			{
				$row = $db->sql_fetchrow( $result );
				$del_mail = $row[user_email];
				$message = "$sitename " . _DELUSERMAIL1 . ":\r\n\r\n";
				$message .= "" . _DELUSERMAIL5 . " (" . $row[username] . ") " . _DELUSERMAIL6 . " $adminmail.\r\n\r\n";
				$subject = "" . _DELUSERMAIL7 . "";
				$mailhead = "From: $sitename <$adminmail>\n";
				$mailhead .= "Content-Type: text/plain; charset= " . _CHARSET . "\n";
				mail( $del_mail, $subject, $message, $mailhead );
			}
			$db->sql_query( "delete from " . $user_prefix . "_users_temp where user_id='" . intval($del_uid) . "'" );
			$db->sql_query( "OPTIMIZE TABLE " . $user_prefix . "_users_temp" );
		}
		Header( "Location: " . $adminfile . ".php?op=delUserTemp" );
	}

	/**
	 * actUserTemp()
	 * 
	 * @return
	 */
	function actUserTemp()
	{
		global $sendmailuser, $adminfile, $datafold, $stop, $user_prefix, $db, $language, $sitename, $adminmail;
		$chng_uname = check_html( $_POST['chng_uname'], nohtml );
		$old_uname = check_html( $_POST['old_uname'], nohtml );
		$chng_viewuname = check_html( $_POST['chng_viewuname'], nohtml );
		$chng_email = check_html( $_POST['chng_email'], nohtml );
		$old_email = check_html( $_POST['old_email'], nohtml );
		$chng_pass = check_html( $_POST['chng_pass'], nohtml );
		$chng_pass2 = check_html( $_POST['chng_pass2'], nohtml );
		$chng_opros = check_html( $_POST['chng_opros'], nohtml );
		$chng_uid = intval( $_POST['chng_uid'] );
		$sql = "SELECT * FROM " . $user_prefix . "_users_temp WHERE user_id='$chng_uid'";
		$result = $db->sql_query( $sql );
		$check = $db->sql_numrows( $result );
		if ( $check != 1 )
		{
			Header( "Location: " . $adminfile . ".php" );
			exit();
		}

		if ( $chng_uname != $old_uname )
		{
			NV_userCheck( $chng_uname );
		}
		if ( $chng_email != $old_email )
		{
			NV_mailCheck( $chng_email );
		}
		if ( $chng_pass != "" or $chng_pass2 != "" )
		{
			NV_passCheck( $chng_pass, $chng_pass2 );
		}

		if ( $stop == "" )
		{
			$row = $db->sql_fetchrow( $result );
			$user_regdate = time();
			$cpass = $row[user_password];
			if ( $chng_pass != "" )
			{
				$cpass = md5( $chng_pass );
			}
			$db->sql_query( "INSERT INTO " . $user_prefix . "_users (user_id, username, viewuname, user_email, user_regdate, user_password, opros, user_avatar, user_avatar_type) VALUES (NULL, '$chng_uname', '$chng_viewuname', '$chng_email', '$user_regdate', '$cpass', '$chng_opros', 'gallery/blank.gif', '3')" );
			$db->sql_query( "DELETE FROM " . $user_prefix . "_users_temp WHERE user_id='$chng_uid'" );
			$db->sql_query( "OPTIMIZE TABLE " . $user_prefix . "_users_temp" );
			ulist();
			if ( $sendmailuser == 1 )
			{
				$message = "" . _WELCOMETO . " $sitename!\r\n\r\n";
				$message .= "" . _YOUUSEDEMAIL . " ($chng_email) " . _TOREGISTER . " $sitename.\r\n\r\n";
				$message .= "" . _FOLLOWINGMEM . "\r\n" . _UNICKNAME . " $chng_uname";
				$subject = "" . _ACCOUNTCREATED . "";
				$mailhead = "From: $sitename <$adminmail>\n";
				$mailhead .= "Content-Type: text/plain; charset= " . _CHARSET . "\n";
				@mail( $chng_email, $subject, $message, $mailhead );
			}
			include ( "../header.php" );

			admusertitle();
			OpenTable();
			echo "<center><b>" . _ACCOUNTCREATED2 . "</b></center>";
			echo "<META HTTP-EQUIV=\"refresh\" content=\"2;URL=" . $adminfile . ".php?op=mod_users\">";
			CloseTable();
			include ( "../footer.php" );
		}
		else
		{
			include ( "../header.php" );

			admusertitle();
			OpenTable();
			echo "<center><br>$stop<br><br>" . _GOBACK . "<br></center>";
			CloseTable();
			include ( "../footer.php" );
			return;
		}
	}

	switch ( $op )
	{

		case "go_search_user":
			go_search_user( $check, $key, $sqltable, $go_result );
			break;

		case "UsersConfig":
			UsersConfig();
			break;

		case "listUser":
			admlistuser();
			break;

		case "UsersConfigSave":
			UsersConfigSave();
			break;

		case "modifyUser":
			modifyUser( $chng_uid );
			break;

		case "updateUser":
			updateUser( $chng_uid, $chng_uname, $chng_name, $chng_lastname, $chng_viewuname, $chng_url, $chng_email, $chng_user_icq, $chng_user_telephone, $chng_user_from, $chng_user_intrest, $chng_user_viewemail, $chng_user_sig, $chng_pass, $chng_pass2, $old_uname, $old_email );
			break;

		case "delUser":
			delUser( $chng_uid );
			break;

		case "delUserConf":
			delUserConf( $del_uid );
			break;

		case "addUser":
			addUser();
			break;

		case "addUserSave":
			addUserSave( $add_uname, $add_name, $add_lastname, $add_viewuname, $add_email, $add_url, $add_user_icq, $add_user_telephone, $add_user_from, $add_user_intrest, $add_user_viewemail, $add_user_sig, $add_pass, $add_pass2 );
			break;

		case "modifyUserTemp":
			modifyUserTemp( $chng_uid );
			break;

		case "updateUserTemp":
			updateUserTemp();
			break;

		case "delUserTemp":
			delUserTemp( $chng_uid );
			break;

		case "delUserTempConf":
			delUserTempConf( $del_uid );
			break;

		case "actUserTemp":
			actUserTemp( $chng_uid2, $chng_uname, $chng_name, $chng_lastname, $chng_url, $chng_email, $chng_user_icq, $chng_user_telephone, $chng_user_from, $chng_user_intrest, $chng_user_sig, $chng_pass, $chng_pass2, $old_uname, $old_email );
			break;

		case "mod_users":
			displayUsers();
			break;

	}

}
else
{
	echo "Access Denied";
}

?>