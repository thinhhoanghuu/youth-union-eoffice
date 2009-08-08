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

if ( ! defined('NV_ADMIN') ) die( "Access Denied" );

if ( defined('IS_SPADMIN') )
{


	/**
	 * not_admin()
	 * 
	 * @return
	 */
	function not_admin()
	{
		include ( '../header.php' );
		GraphicAdmin();
		OpenTable();
		echo "<center><font class=\"title\"><b>" . _AUTHORSADMIN . "</b></font></center>";
		CloseTable();
		echo "<br>";
		OpenTable();
		echo "<center><b>" . _NOTAUTHORIZED . "</b><br><br>" . "" . _GOBACK . "";
		CloseTable();
		include ( "../footer.php" );
	}

	/**
	 * displayadmins()
	 * 
	 * @return
	 */
	function displayadmins()
	{
		global $allowed_admin_ip, $client_ip, $ip_test_fields, $block_admin_ip, $expiring_login, $adv_admin, $firewall, $bgcolor2, $adminfile, $user_prefix, $prefix, $db, $language, $multilingual, $radminname, $aid, $superadmin_add_superadmin, $superadmin_chg_superadmin, $superadmin_chg_level, $superadmin_edit, $superadmin_edit_level, $superadmin_add_modadmin, $superadmin_edit_modadmin, $emailgodadmin, $listmods;
		if ( defined('IS_ADMIN') )
		{
			include ( "../header.php" );
			GraphicAdmin();
			OpenTable();
			echo "<center><font class=\"title\"><b>" . _AUTHORSADMIN . "</b></font></center>";
			CloseTable();
			echo "<br>";
			if ( $radminname == "God" )
			{
				OpenTable();
				echo "<center><font class=\"title\"><b>" . _GODADMINSECTOR . "</b><br>(" . _GODADMINSECTOR2 . ")</font></center><br>" . "<form action=\"" . $adminfile . ".php\" method=\"post\">" . "<table border=\"1\" cellpadding=\"3\" cellspacing=\"3\" width=\"100%\"><tr><td width=\"80%\">" . "" . _SUPERADMINADD . ":</td><td width=\"20%\">";
				if ( $superadmin_add_superadmin == 1 )
				{
					echo "<input type=\"radio\" name=\"xsuperadmin_add_superadmin\" value=\"1\" checked>" . _YES . " &nbsp;<input type=\"radio\" name=\"xsuperadmin_add_superadmin\" value=\"0\">" . _NO . "";
				}
				else
				{
					echo "<input type=\"radio\" name=\"xsuperadmin_add_superadmin\" value=\"1\">" . _YES . " &nbsp;<input type=\"radio\" name=\"xsuperadmin_add_superadmin\" value=\"0\" checked>" . _NO . "";
				}
				echo "</td></tr><tr><td>" . _SUPERADMINCHG . ":</td><td>";
				if ( $superadmin_chg_superadmin == 1 )
				{
					echo "<input type=\"radio\" name=\"xsuperadmin_chg_superadmin\" value=\"1\" checked>" . _YES . " &nbsp;<input type=\"radio\" name=\"xsuperadmin_chg_superadmin\" value=\"0\">" . _NO . "";
				}
				else
				{
					echo "<input type=\"radio\" name=\"xsuperadmin_chg_superadmin\" value=\"1\">" . _YES . " &nbsp;<input type=\"radio\" name=\"xsuperadmin_chg_superadmin\" value=\"0\" checked>" . _NO . "";
				}
				echo "</td></tr><tr><td>" . _SUPERADMINLEVEL . ":</td><td>";
				if ( $superadmin_chg_level == 1 )
				{
					echo "<input type=\"radio\" name=\"xsuperadmin_chg_level\" value=\"1\" checked>" . _YES . " &nbsp;<input type=\"radio\" name=\"xsuperadmin_chg_level\" value=\"0\">" . _NO . "";
				}
				else
				{
					echo "<input type=\"radio\" name=\"xsuperadmin_chg_level\" value=\"1\">" . _YES . " &nbsp;<input type=\"radio\" name=\"xsuperadmin_chg_level\" value=\"0\" checked>" . _NO . "";
				}
				echo "</td></tr><tr><td>" . _SUPERADMINEDIT . ":</td><td>";
				if ( $superadmin_edit == 1 )
				{
					echo "<input type=\"radio\" name=\"xsuperadmin_edit\" value=\"1\" checked>" . _YES . " &nbsp;<input type=\"radio\" name=\"xsuperadmin_edit\" value=\"0\">" . _NO . "";
				}
				else
				{
					echo "<input type=\"radio\" name=\"xsuperadmin_edit\" value=\"1\">" . _YES . " &nbsp;<input type=\"radio\" name=\"xsuperadmin_edit\" value=\"0\" checked>" . _NO . "";
				}
				echo "</td></tr><tr><td>" . _SUPERADMINEDITLEVEL . ":</td><td>";
				if ( $superadmin_edit_level == 1 )
				{
					echo "<input type=\"radio\" name=\"xsuperadmin_edit_level\" value=\"1\" checked>" . _YES . " &nbsp;<input type=\"radio\" name=\"xsuperadmin_edit_level\" value=\"0\">" . _NO . "";
				}
				else
				{
					echo "<input type=\"radio\" name=\"xsuperadmin_edit_level\" value=\"1\">" . _YES . " &nbsp;<input type=\"radio\" name=\"xsuperadmin_edit_level\" value=\"0\" checked>" . _NO . "";
				}
				echo "</td></tr><tr><td>" . _SUPERADMINADDMODADMIN . ":</td><td>";
				if ( $superadmin_add_modadmin == 1 )
				{
					echo "<input type=\"radio\" name=\"xsuperadmin_add_modadmin\" value=\"1\" checked>" . _YES . " &nbsp;<input type=\"radio\" name=\"xsuperadmin_add_modadmin\" value=\"0\">" . _NO . "";
				}
				else
				{
					echo "<input type=\"radio\" name=\"xsuperadmin_add_modadmin\" value=\"1\">" . _YES . " &nbsp;<input type=\"radio\" name=\"xsuperadmin_add_modadmin\" value=\"0\" checked>" . _NO . "";
				}
				echo "</td></tr><tr><td>" . _SUPERADMINEDITMODADMIN . ":</td><td>";
				if ( $superadmin_edit_modadmin == 1 )
				{
					echo "<input type=\"radio\" name=\"xsuperadmin_edit_modadmin\" value=\"1\" checked>" . _YES . " &nbsp;<input type=\"radio\" name=\"xsuperadmin_edit_modadmin\" value=\"0\">" . _NO . "";
				}
				else
				{
					echo "<input type=\"radio\" name=\"xsuperadmin_edit_modadmin\" value=\"1\">" . _YES . " &nbsp;<input type=\"radio\" name=\"xsuperadmin_edit_modadmin\" value=\"0\" checked>" . _NO . "";
				}
				echo "</td></tr><tr><td>" . _GODADMINEMAIL . ":</td><td>";
				if ( $emailgodadmin == 1 )
				{
					echo "<input type=\"radio\" name=\"xemailgodadmin\" value=\"1\" checked>" . _YES . " &nbsp;<input type=\"radio\" name=\"xemailgodadmin\" value=\"0\">" . _NO . "";
				}
				else
				{
					echo "<input type=\"radio\" name=\"xemailgodadmin\" value=\"1\">" . _YES . " &nbsp;<input type=\"radio\" name=\"xemailgodadmin\" value=\"0\" checked>" . _NO . "";
				}
				echo "</td></tr></table>";
				echo "<br>";
				echo "<center><font class=\"option\"><b>" . _GENERALCONFIGADMINSECTOR . "</b></font></center><br>" . "<table border=\"1\" cellpadding=\"3\" cellspacing=\"3\" align=\"center\"><tr><td>" . "" . _ADMINSECTOR00 . "</td><td>";
				if ( $firewall == 1 )
				{
					echo "<input type=\"radio\" name=\"xfirewall\" value=\"1\" checked>" . _YES . " &nbsp;<input type=\"radio\" name=\"xfirewall\" value=\"0\">" . _NO . "";
				}
				else
				{
					echo "<input type=\"radio\" name=\"xfirewall\" value=\"1\">" . _YES . " &nbsp;<input type=\"radio\" name=\"xfirewall\" value=\"0\" checked>" . _NO . "";
				}
				echo "</td></tr><tr><td valign=\"top\">" . _ADMINSECTOR07 . "</td><td>";
				$adv_admin_array = explode( "|", $adv_admin );
				$i = 0;
				while ( $i <= (count($adv_admin_array) + 2) )
				{
					$adv_admin_s = explode( ":", $adv_admin_array[$i] );
					echo "" . _NICKNAME . ":&nbsp;<input type='text' name='adv_admin_name[]'  value=\"$adv_admin_s[0]\" maxlength='30' size='15'>&nbsp;&nbsp;" . _PASSWORD . ":&nbsp;<input type='text' name='adv_admin_pass[]' value=\"$adv_admin_s[1]\" maxlength='30' size='10'><br>";
					$i++;
				}
				echo "</td></tr><tr><td colspan=\"2\">" . _ADMINSECTOR01 . " <select name=\"xexpiring_login\">";
				for ( $e = 1; $e <= 72; $e++ )
				{
					$sele = "";
					if ( $e == $expiring_login / 3600 )
					{
						$sele = " selected";
					}
					echo "<option name=\"xexpiring_login\" value=\"$e\"$sele>$e " . _HOUR . "</option>\n";
				}
				echo "</select>&nbsp;&nbsp;" . _ADMINSECTOR01a . "</td></tr></table>";
				echo "<br>";
				echo "<center><font class=\"option\"><b>" . _GENERALCONFIGADMINSECTOR2 . "</b></font></center><br>" . _ADMINSECTORINFO . "<br><br>" . "<table border=\"1\" cellpadding=\"3\" cellspacing=\"3\" align=\"center\"><tr><td>" . "" . _ADMINSECTOR04 . "</td><td>";
				if ( $block_admin_ip == 1 )
				{
					echo "<input type=\"radio\" name=\"xblock_admin_ip\" value=\"1\" checked>" . _YES . " &nbsp<input type=\"radio\" name=\"xblock_admin_ip\" value=\"0\">" . _NO . "";
				}
				else
				{
					echo "<input type=\"radio\" name=\"xblock_admin_ip\" value=\"1\">" . _YES . " &nbsp;<input type=\"radio\" name=\"xblock_admin_ip\" value=\"0\" checked>" . _NO . "";
				}
				echo "</td></tr><tr><td>" . _ADMINSECTOR05 . "</td><td><select name=\"xip_test_fields\">";
				$yip_test_fields = array( '', '255.xxx.xxx.xxx', '255.255.xxx.xxx', '255.255.255.xxx', '255.255.255.255' );
				for ( $e = 1; $e <= 4; $e++ )
				{
					$sel = "";
					if ( $e == $ip_test_fields )
					{
						$sel = " selected";
					}
					echo "<option name=\"xip_test_fields\" value=\"$e\" $sel>$yip_test_fields[$e]</option>\n";
				}
				echo "</select></td></tr><tr><td  valign=\"top\">" . _ADMINSECTOR06 . "<br>";
				$you_ip = trim( $client_ip );
				echo "(" . _ADMINSECTOR06d . " $you_ip)</td><td>";
				$allowed_admin_ip_list = ereg_replace( "\|", ", ", $allowed_admin_ip );
				if ( $allowed_admin_ip_list != "" )
				{
					echo "" . _ADMINSECTOR06a . " $allowed_admin_ip_list<br><br>";
				}
				echo "" . _ADMINSECTOR06b . "&nbsp;<input type=\"text\" size=\"15\" maxlength='20' name=\"xallowed_admin_add\" value=\"\">";
				if ( $allowed_admin_ip_list != "" )
				{
					echo "&nbsp;&nbsp;" . _ADMINSECTOR06c . "&nbsp;<select name=\"xallowed_admin_del\"><option name=\"xallowed_admin_del\" value=\"\" selected></option>\n";
					$allowed_admin_array = explode( "|", $allowed_admin_ip );
					$ip_num = count( $allowed_admin_array ) - 1;
					$i = 0;
					while ( $i <= $ip_num )
					{
						echo "<option name=\"xallowed_admin_del\" value=\"$allowed_admin_array[$i]\">$allowed_admin_array[$i]</option>\n";
						$i++;
					}
					echo "</select>";
				}
				else
				{
					echo "<input type=\"hidden\" name=\"xallowed_admin_del\" value=\"\">";
				}
				echo "</td></tr></table>";
				echo "<br>";
				echo "<input type=\"hidden\" name=\"op\" value=\"AdminsConfigSave\">";
				echo "<center><input type=\"submit\" value=\"" . _SAVECHANGES . "\"></center></form>";
				CloseTable();
				echo "<br>";
			}
			OpenTable();
			echo "<center><font class=\"option\"><b>" . _ADMINSITE . "</b></font></center><br>\n" . "<table border=\"1\" cellpadding=\"3\" cellspacing=\"3\" width=\"100%\">\n" . "<tr>\n<td align=\"center\"><b>" . _NAME . "</b></td>\n" . "<td align=\"center\"><b>" . _NICKNAME . "</b></td>\n<td align=\"center\"><b>" . _EMAIL . "</b></td>\n" . "<td align=\"center\"><b>" . _PERMISSIONS . "</b></td>\n<td align=\"center\"><b>" . _FUNCTIONS . "</b></td>\n</tr>\n";
			$sql1 = "select aid, name, email from " . $prefix . "_authors WHERE name='God' AND radminsuper=1";
			$result1 = $db->sql_query( $sql1 );
			while ( $row1 = $db->sql_fetchrow($result1) )
			{
				$bg = "";
				if ( $row1[aid] == $aid )
				{
					$bg = " bgcolor=$bgcolor2";
				}
				$func1 = "***";
				if ( $radminname == "God" )
				{
					$func1 = "<a href=\"" . $adminfile . ".php?op=modifyadmin&chng_aid=$row1[aid]\">" . _MODIFYINFO . "</a>";
				}
				$tr_admin .= "<tr>\n<td" . $bg . " align=center>$row1[name]</td>\n<td$bg align=center>$row1[aid]</td>\n<td$bg align=center><a href=\"mailto:$row1[email]\">$row1[email]</td>\n<td$bg align=center>" . _GODADMIN . "</td>\n<td$bg align=center>$func1</td>\n</tr>\n";
			}
			$sql2 = "select aid, name, email, radminsuper from " . $prefix . "_authors WHERE name!='God' ORDER BY radminsuper DESC";
			$result2 = $db->sql_query( $sql2 );
			while ( $row2 = $db->sql_fetchrow($result2) )
			{
				$bg = "";
				if ( $row2[aid] == $aid )
				{
					$bg = " bgcolor=$bgcolor2";
				}
				if ( $row2[radminsuper] == 1 )
				{
					$level = "" . _SUPERUSER . "";
				}
				else
				{
					$level = "";
					$result3 = $db->sql_query( "SELECT mid, title, admins FROM " . $prefix . "_modules ORDER BY title ASC" );
					while ( $row3 = $db->sql_fetchrow($result3) )
					{
						$stitle = strtolower( $row3[title] );
						if ( (($row3[title] == "News") and file_exists("modules/stories.php") and file_exists("links/links.stories.php") and file_exists("case/case.stories.php")) or (($row3[title] == "Your_Account") and file_exists("modules/users.php") and file_exists("links/links.editusers.php") and file_exists("case/case.users.php")) or (file_exists("modules/" . $stitle . ".php") and file_exists("links/links." . $stitle . ".php") and file_exists("case/case." . $stitle . ".php")) )
						{
							$admins = explode( ",", $row3[admins] );
							if ( in_array($row2[name], $admins) )
							{
								if ( $level != "" )
								{
									$level .= "&nbsp;|&nbsp;";
								}
								$level .= "<a href=\"../modules.php?name=$row3[title]\">$row3[title]</a>";
							}
						}
					}
				}
				if ( $level == "" )
				{
					$level = "&nbsp;";
				}
				$func2 = "***";
				if ( ($radminname == "God") || ($row2[radminsuper] == 0 and $superadmin_edit_modadmin == 1) )
				{
					$func2 = "<a href=\"" . $adminfile . ".php?op=modifyadmin&chng_aid=$row2[aid]\">" . _MODIFYINFO . "</a> | <a href=\"" . $adminfile . ".php?op=deladmin&amp;del_aid=$row2[aid]\">" . _DELAUTHOR . "</a>";
				} elseif ( ($row2[aid] == $aid and $superadmin_edit == 1) || ($row2[aid] != $aid and $superadmin_chg_superadmin == 1 and $row2[radminsuper] == 1) )
				{
					$func2 = "<a href=\"" . $adminfile . ".php?op=modifyadmin&chng_aid=$row2[aid]\">" . _MODIFYINFO . "</a>";
				}
				$tr_admin .= "<tr>\n<td$bg align=center>$row2[name]</td>\n<td$bg align=center>$row2[aid]</td>\n<td$bg align=center><a href=\"mailto:$row2[email]\">$row2[email]</td>\n<td$bg align=center>$level</td>\n<td$bg align=center>$func2</td>\n</tr>\n";
			}
			if ( $radminname == "God" )
			{
				$leveladmininfo = "" . _GODADMININFO . "";
			}
			else
			{
				$leveladmininfo = "" . _SUPERADMININFO . "";
				if ( $superadmin_add_superadmin == 0 )
				{
					$leveladmininfo .= "" . _SUPERADMININFO2 . "";
				}
				if ( $superadmin_chg_superadmin == 0 )
				{
					$leveladmininfo .= "" . _SUPERADMININFO3 . "";
				}
				if ( $superadmin_chg_level == 0 )
				{
					$leveladmininfo .= "" . _SUPERADMININFO4 . "";
				}
				if ( $superadmin_edit == 0 )
				{
					$leveladmininfo .= "" . _SUPERADMININFO5 . "";
				}
				if ( $superadmin_edit_level == 0 )
				{
					$leveladmininfo .= "" . _SUPERADMININFO6 . "";
				}
				if ( $superadmin_add_modadmin == 0 )
				{
					$leveladmininfo .= "" . _SUPERADMININFO7 . "";
				}
				if ( $superadmin_edit_modadmin == 0 )
				{
					$leveladmininfo .= "" . _SUPERADMININFO8 . "";
				}
			}
			echo "$tr_admin</table><br><font class=\"tiny\">$leveladmininfo</font>";
			CloseTable();
			if ( ($radminname == "God") || (($radminname != "God") and (($superadmin_add_superadmin == 1) || ($superadmin_add_modadmin == 1))) )
			{
				echo "<br>";
				OpenTable();
				echo "<a name=\"add_adm\"></a>";
				echo "<center><font class=\"option\"><b>" . _ADDAUTHOR . "</b></font><br>";
				echo "<form action=\"" . $adminfile . ".php?op=mod_authors\" method=\"get\">\n";
				echo "<table border=\"0\">\n";
				echo "<tr><td>" . _SELECTACC . ":</td>" . "<td><select name=\"add_account\" onChange=\"top.location.href=this.options[this.selectedIndex].value\">";
				echo "<option value=\"\">------------------------</option>";
				$result4 = $db->sql_query( "SELECT user_id, username FROM " . $user_prefix . "_users ORDER BY username ASC" );
				while ( $row4 = $db->sql_fetchrow($result4) )
				{
					if ( $row4[user_id] != 1 ) echo "<option value=\"" . $adminfile . ".php?op=mod_authors&user_id=$row4[user_id]#add_adm\">$row4[username]</option>";
				}
				echo "</select></td></tr>";
				echo "</table></form></center>";
				if ( isset($_GET['user_id']) )
				{
					$user_id = intval( $_GET['user_id'] );
					list( $username, $user_email ) = $db->sql_fetchrow( $db->sql_query("SELECT username, user_email FROM " . $user_prefix . "_users WHERE user_id='" . $user_id . "'") );
				}
				echo "<form action=\"" . $adminfile . ".php\" method=\"post\">" . "<table border=\"0\">" . "<tr><td>" . _NAME . ":</td>" . "<td><input type=\"text\" name=\"add_name\" size=\"30\" maxlength=\"50\" value='$username'> <font class=\"tiny\">" . _REQUIRED . "</font></td></tr>" . "<tr><td>" . _NICKNAME . ":</td>" . "<td><input type=\"text\" name=\"add_aid\" size=\"30\" maxlength=\"30\" value='$username'> <font class=\"tiny\">" . _REQUIRED . "</font></td></tr>" . "<tr><td>" . _EMAIL . ":</td>" . "<td><input type=\"text\" name=\"add_email\" size=\"30\" maxlength=\"60\" value='$user_email'> <font class=\"tiny\">" . _REQUIRED . "</font></td></tr>";
				if ( $multilingual == 1 )
				{
					echo "<tr><td>" . _LANGUAGE . ":</td><td>" . "<select name=\"add_admlanguage\">";
					echo select_language( $language );
					echo "<option value=\"\">" . _ALL . "</option></select></td></tr>";
				}
				else
				{
					echo "<input type=\"hidden\" name=\"add_admlanguage\" value=\"\">";
				}
				if ( ($radminname == "God") || (($radminname != "God") and ($superadmin_add_modadmin == 1)) )
				{
					echo "<tr><td>" . _PERMISSIONS . ":</td><td>";
					for ( $l = 0; $l < sizeof($listmods); $l++ )
					{
						$title = ereg_replace( "_", " ", $listmods[$l] );
						$xstitle = strtolower( $listmods[$l] );
						if ( (($listmods[$l] == "News") and file_exists("modules/stories.php") and file_exists("links/links.stories.php") and file_exists("case/case.stories.php")) or (($listmods[$l] == "Your_Account") and file_exists("modules/users.php") and file_exists("links/links.editusers.php") and file_exists("case/case.users.php")) or (file_exists("modules/$xstitle.php") and file_exists("links/links.$xstitle.php") and file_exists("case/case.$xstitle.php")) )
						{
							echo "<input type=\"checkbox\" name=\"auth_modules[]\" value=\"$listmods[$l]\"> $title ";
						}
					}
					echo "</td></tr>";
				}
				else
				{
					echo "<input type=\"hidden\" name=\"auth_modules[]\" value=\"\">";
				}
				if ( ($radminname == "God") || (($radminname != "God") and ($superadmin_add_superadmin == 1)) )
				{
					echo "<tr><td>&nbsp;</td><td><input type=\"checkbox\" name=\"add_radminsuper\" value=\"1\"> <b>" . _SUPERUSER . "</b></td></tr>";
				}
				else
				{
					echo "<input type=\"hidden\" name=\"add_radminsuper\" value=\"0\">";
				}
				echo "<tr><td>" . _PASSWORD . "</td>" . "<td><input type=\"password\" name=\"add_pwd\" size=\"12\" maxlength=\"12\"> <font class=\"tiny\">" . _REQUIRED . "</font></td></tr>" . "<input type=\"hidden\" name=\"add_url\" value=\"\">" . "<input type=\"hidden\" name=\"op\" value=\"AddAuthor\">" . "<tr><td>&nbsp;</td><td><input type=\"submit\" value=\"" . _ADDAUTHOR2 . "\"></td></tr>" . "</table></form>";
				CloseTable();
			}
			include ( "../footer.php" );
		}
		else
		{
			not_admin();
		}
	}


	/**
	 * AdminsConfigSave()
	 * 
	 * @return
	 */
	function AdminsConfigSave()
	{
		global $datafold, $adminfile, $radminname, $allowed_admin_ip;
		if ( (defined('IS_ADMIN')) and ($radminname == "God") )
		{
			$xsuperadmin_add_superadmin = intval( $_POST['xsuperadmin_add_superadmin'] );
			$xsuperadmin_chg_superadmin = intval( $_POST['xsuperadmin_chg_superadmin'] );
			$xsuperadmin_chg_level = intval( $_POST['xsuperadmin_chg_level'] );
			$xsuperadmin_edit = intval( $_POST['xsuperadmin_edit'] );
			$xsuperadmin_edit_level = intval( $_POST['xsuperadmin_edit_level'] );
			$xsuperadmin_add_modadmin = intval( $_POST['xsuperadmin_add_modadmin'] );
			$xsuperadmin_edit_modadmin = intval( $_POST['xsuperadmin_edit_modadmin'] );
			$xemailgodadmin = intval( $_POST['xemailgodadmin'] );
			$xexpiring_login = intval( $_POST['xexpiring_login'] );
			$xfirewall = intval( $_POST['xfirewall'] );
			$xblock_admin_ip = intval( $_POST['xblock_admin_ip'] );
			$xip_test_fields = intval( $_POST['xip_test_fields'] );
			$xallowed_admin_add = FixQuotes( $_POST['xallowed_admin_add'] );
			$xallowed_admin_del = FixQuotes( $_POST['xallowed_admin_del'] );
			$adv_admin_name = $_POST['adv_admin_name'];
			$adv_admin_pass = $_POST['adv_admin_pass'];

			$xexpiring_login = $xexpiring_login * 3600;

			$xallowed_admin_add = trim( $xallowed_admin_add );
			$xallowed_admin_del = trim( $xallowed_admin_del );
			$allowed_admin_ip = trim( $allowed_admin_ip );
			if ( $xallowed_admin_del != "" )
			{
				$allowed_admin_ip = ereg_replace( "\|$xallowed_admin_del", "", $allowed_admin_ip );
				$allowed_admin_ip = ereg_replace( "" . $xallowed_admin_del . "\|", "", $allowed_admin_ip );
				$allowed_admin_ip = ereg_replace( "$xallowed_admin_del", "", $allowed_admin_ip );
			}

			$allowed_admin_ip_net = explode( "|", $allowed_admin_ip );
			foreach ( $allowed_admin_ip_net as $allowed_ip )
			{
				if ( $xallowed_admin_add == $allowed_ip )
				{
					$xallowed_admin_add = "";
				}
			}
			if ( $xallowed_admin_add != "" )
			{
				if ( $allowed_admin_ip != "" )
				{
					$xallowed_admin_add = "" . $allowed_admin_ip . "|$xallowed_admin_add";
				}
			}
			else
			{
				$xallowed_admin_add = $allowed_admin_ip;
			}

			@chmod( "../$datafold/config_admin.php", 0777 );
			@$file = fopen( "../$datafold/config_admin.php", "w" );
			$content = "<?php\n\n";
			$fctime = date( "d-m-Y H:i:s", filectime("../$datafold/config_admin.php") );
			$fmtime = date( "d-m-Y H:i:s" );
			$content .= "// File: config_admin.php.\n// Created: $fctime.\n// Modified: $fmtime.\n// Do not change anything in this file!\n\n";
			$content .= "if (!defined('NV_ADMIN')) {\n";
			$content .= "die();\n";
			$content .= "}\n";
			$content .= "\n";
			$content .= "\$superadmin_add_superadmin = $xsuperadmin_add_superadmin;\n";
			$content .= "\$superadmin_chg_superadmin = $xsuperadmin_chg_superadmin;\n";
			$content .= "\$superadmin_chg_level = $xsuperadmin_chg_level;\n";
			$content .= "\$superadmin_edit = $xsuperadmin_edit;\n";
			$content .= "\$superadmin_edit_level = $xsuperadmin_edit_level;\n";
			$content .= "\$superadmin_add_modadmin = $xsuperadmin_add_modadmin;\n";
			$content .= "\$superadmin_edit_modadmin = $xsuperadmin_edit_modadmin;\n";
			$content .= "\$emailgodadmin = $xemailgodadmin;\n";
			$content .= "\n";
			$content .= "\$firewall = $xfirewall;\n";
			$content .= "\$adv_admin = \"";
			if ( $xfirewall != 0 )
			{
				$adv_admin_num = count( $adv_admin_name ) - 1;
				$i = 0;
				while ( $i <= $adv_admin_num )
				{
					if ( $adv_admin_name[$i] != "" )
					{
						if ( $i > 0 )
						{
							$content .= "|";
						}
						$content .= "$adv_admin_name[$i]:$adv_admin_pass[$i]";
					}
					$i++;
				}
			}
			$content .= "\";\n";
			$content .= "\$expiring_login = $xexpiring_login;\n";
			$content .= "\$block_admin_ip = $xblock_admin_ip;\n";
			if ( $xblock_admin_ip != 0 )
			{
				$content .= "\$allowed_admin_ip = \"$xallowed_admin_add\";\n";
				$content .= "\$ip_test_fields = $xip_test_fields;\n";
			}
			else
			{
				$content .= "\$allowed_admin_ip = \"\";\n";
				$content .= "\$ip_test_fields = 1;\n";
			}
			$content .= "\n";
			$content .= "?>";

			@$writefile = fwrite( $file, $content );
			@fclose( $file );
			@chmod( "../$datafold/config_admin.php", 0604 );
			Header( "Location: " . $adminfile . ".php?op=mod_authors" );

		}
		else
		{
			not_admin();
		}
	}

	/**
	 * AddAuthor()
	 * 
	 * @param mixed $add_name
	 * @param mixed $add_aid
	 * @param mixed $add_email
	 * @param mixed $add_url
	 * @param mixed $add_admlanguage
	 * @param mixed $auth_modules
	 * @param mixed $add_radminsuper
	 * @param mixed $add_pwd
	 * @return
	 */
	function AddAuthor( $add_name, $add_aid, $add_email, $add_url, $add_admlanguage, $auth_modules, $add_radminsuper, $add_pwd )
	{
		global $client_ip, $emailgodadmin, $superadmin_add_modadmin, $superadmin_add_superadmin, $adminfile, $prefix, $db, $radminname, $adminmail, $sitename;
		if ( defined('IS_ADMIN') )
		{
			$add_aid = strtolower( substr("$add_aid", 0, 25) );
			$add_name = substr( "$add_name", 0, 25 );
			$add_pwd = substr( "$add_pwd", 0, 12 );
			$add_email = strtolower( $add_email );
			$add_radminsuper = intval( $add_radminsuper );
			$badaddadmin = 0;
			if ( ($radminname != "God") and ($superadmin_add_superadmin == 0) and ($add_radminsuper == 1) )
			{
				$badaddadmin = 1;
				$badinfo = "" . _SUPERADMININFO2A . "";
			} elseif ( ($radminname != "God") and ($superadmin_add_modadmin == 0) and ($add_radminsuper != 1) )
			{
				$badaddadmin = 1;
				$badinfo = "" . _SUPERADMININFO7A . "";
			} elseif ( ! ($add_aid && $add_name && $add_email && $add_pwd) )
			{
				$badaddadmin = 1;
				$badinfo = "" . _COMPLETEFIELDS . "";
			} elseif ( $add_name == "God" )
			{
				$badaddadmin = 1;
				$badinfo = "" . _BADNAMEADMIN . "";
			} elseif ( $db->sql_numrows($db->sql_query("SELECT * FROM " . $prefix . "_authors WHERE name='$add_name'")) > 0 )
			{
				$badaddadmin = 1;
				$badinfo = "" . _BADNAMEADMIN2 . "";
			} elseif ( (ereg("[^a-zA-Z0-9_-]", $add_aid)) || (strrpos($add_aid, ' ') > 0) || ($db->sql_numrows($db->sql_query("SELECT * FROM " . $prefix . "_authors WHERE aid='$add_aid'")) > 0) )
			{
				$badaddadmin = 1;
				$badinfo = "" . _BADNICKADMIN . "";
			} elseif ( (! eregi("^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,6}$", $add_email)) || (strrpos($add_email, ' ') > 0) || ($db->sql_numrows($db->sql_query("SELECT * FROM " . $prefix . "_authors WHERE email='$add_email'")) > 0) )
			{
				$badaddadmin = 1;
				$badinfo = "" . _BADMAILADMIN . "";
			} elseif ( (strlen($add_pwd) > 12) || (strlen($add_pwd) < 5) || (ereg("[^a-zA-Z0-9_-]", $add_pwd)) )
			{
				$badaddadmin = 1;
				$badinfo = "" . _BADPASSADMIN . "";
			}
			if ( $badaddadmin == 1 )
			{
				include ( "../header.php" );
				GraphicAdmin();
				OpenTable();
				echo "<center><font class=\"title\"><b>" . _AUTHORSADMIN . "</b></font></center>";
				CloseTable();
				echo "<br>";
				OpenTable();
				echo "<center><font class=\"option\"><b>" . _CREATIONERROR . "</b></font><br><br>" . "$badinfo<br><br>" . "" . _GOBACK . "</center>";
				CloseTable();
				include ( "../footer.php" );
				return;
			} elseif ( $badaddadmin == 0 )
			{
				$add_pwd2 = $add_pwd;
				$add_pwd = md5( $add_pwd );
				if ( $add_radminsuper != 1 )
				{
					for ( $i = 0; $i < sizeof($auth_modules); $i++ )
					{
						$row = $db->sql_fetchrow( $db->sql_query("SELECT admins FROM " . $prefix . "_modules WHERE title='$auth_modules[$i]'") );
						$adm = "$row[admins]$add_name";
						$db->sql_query( "UPDATE " . $prefix . "_modules SET admins='$adm,' WHERE title='$auth_modules[$i]'" );
					}
				}
				$result = $db->sql_query( "INSERT INTO " . $prefix . "_authors (aid, name, url, email, pwd, radminsuper, admlanguage) VALUES ('$add_aid', '$add_name', '$add_url', '$add_email', '$add_pwd', '$add_radminsuper','$add_admlanguage')" );
				if ( ! $result )
				{
					return;
				}
				if ( ($emailgodadmin == 1) and ($radminname != "God") )
				{
					$ip = $client_ip;
					$subject = "" . _MAILADMININFO1 . "";
					$content = "" . _MAILADMININFO2 . "\n\n" . _NAME . ": $add_name\n" . _NICKNAME . ": $add_aid\n" . _EMAIL . ": $add_email\n" . _PASSWORD . ": $add_pwd2\n\n" . _MAILADMININFO3 . ":\n\n" . _NAME . ": $radminname\nIP: $ip\n\n" . _MAILADMININFO4 . "\n\nWebmaster";
					$mailheader = "From: $sitename <$adminmail>\n";
					$mailheader .= "Content-Type: text/plain; charset= " . _CHARSET . "\n";
					@mail( $adminmail, $subject, $content, $mailheader );
				}
				include ( "../header.php" );
				GraphicAdmin();
				OpenTable();
				echo "<center><font class=\"title\"><b>" . _AUTHORSADMIN . "</b></font></center>";
				CloseTable();
				echo "<br>";
				OpenTable();
				echo "<center><font class=\"option\"><b>" . _ADDADMINOK . "</b></font></center>";
				CloseTable();
				echo "<META HTTP-EQUIV=\"refresh\" content=\"2;URL=" . $adminfile . ".php?op=mod_authors\">";
				include ( "../footer.php" );
			}
		}
		else
		{
			not_admin();
		}
	}


	/**
	 * modifyadmin()
	 * 
	 * @param mixed $chng_aid
	 * @return
	 */
	function modifyadmin( $chng_aid )
	{
		global $listmods, $listadmins, $superadmin_chg_level, $superadmin_edit_level, $superadmin_edit_modadmin, $superadmin_edit, $superadmin_chg_superadmin, $adminfile, $datafold, $admin, $prefix, $db, $language, $multilingual, $radminname, $aid;
		if ( defined('IS_ADMIN') )
		{
			$adm_aid = $chng_aid;
			$adm_aid = trim( $adm_aid );
			$sql = "select aid, name, url, email, pwd, radminsuper, admlanguage from " . $prefix . "_authors where aid='$chng_aid'";
			$result = $db->sql_query( $sql );
			$num = $db->sql_numrows( $result );
			if ( ! $chng_aid or $chng_aid == "" or $num == 0 )
			{
				Header( "Location: " . $adminfile . ".php?op=mod_authors" );
			}
			$row = $db->sql_fetchrow( $result );
			$chng_name = $row['name'];
			$chng_radminsuper = intval( $row['radminsuper'] );
			if ( ($radminname != "God") and (($chng_name == "God") or (($superadmin_chg_superadmin == 0) and ($aid != $chng_aid) and ($chng_radminsuper == 1)) or (($superadmin_edit == 0) and ($aid == $chng_aid)) or (($superadmin_edit_modadmin == 0) and ($chng_radminsuper == 0))) )
			{
				include ( "../header.php" );
				GraphicAdmin();
				OpenTable();
				echo "<center><font class=\"title\"><b>" . _AUTHORSADMIN . "</b></font></center>";
				CloseTable();
				echo "<br>";
				OpenTable();
				echo "<center><font class=\"option\"><b>" . _NOLEVELCHGADMIN . "</b></font><br><br>" . "" . _GOBACK . "</center>";
				CloseTable();
				include ( "../footer.php" );
				return;
			}
			include ( "../header.php" );
			GraphicAdmin();
			OpenTable();
			echo "<center><font class=\"title\"><b>" . _AUTHORSADMIN . "</b></font></center>";
			CloseTable();
			echo "<br>";
			OpenTable();
			echo "<center><font class=\"option\"><b>" . _MODIFYINFO . "</b></font></center><br><br>";
			$chng_aid = $row['aid'];
			$chng_url = stripslashes( $row['url'] );
			$chng_email = stripslashes( $row['email'] );
			$chng_pwd = $row['pwd'];
			$chng_admlanguage = $row['admlanguage'];
			$chng_aid = substr( "$chng_aid", 0, 25 );
			echo "<form action=\"" . $adminfile . ".php\" method=\"post\">" . "<table border=\"0\">" . "<tr><td>" . _NAME . ":</td>" . "<td><b>$chng_name</b><input type=\"hidden\" name=\"chng_name\" value=\"$chng_name\"></td></tr>" . "<tr><td>" . _NICKNAME . ":</td>" . "<td><input type=\"text\" name=\"chng_aid\" value=\"$chng_aid\"> <font class=\"tiny\">" . _REQUIRED . "</font></td></tr>" . "<tr><td>" . _EMAIL . ":</td>" . "<td><input type=\"text\" name=\"chng_email\" value=\"$chng_email\" size=\"30\" maxlength=\"60\"> <font class=\"tiny\">" . _REQUIRED . "</font></td></tr>" . "<tr><td>" . _URL . ":</td>" . "<td><input type=\"text\" name=\"chng_url\" value=\"$chng_url\" size=\"30\" maxlength=\"60\"></td></tr>";
			if ( $multilingual == 1 )
			{
				echo "<tr><td>" . _LANGUAGE . ":</td><td>" . "<select name=\"chng_admlanguage\">";
				echo select_language( $chng_admlanguage );
				echo "<option value=\"\" $allsel>" . _ALL . "</option></select></td></tr>";
			}
			else
			{
				echo "<input type=\"hidden\" name=\"chng_admlanguage\" value=\"\">";
			}
			if ( ($row[name] != "God") and (($radminname == "God") or (($radminname != "God") and ((($aid != $chng_aid) and ($superadmin_chg_level == 1)) or (($aid == $chng_aid) and ($superadmin_edit_level == 1)) or (($chng_radminsuper == 0) and ($superadmin_edit_modadmin == 1))))) )
			{
				echo "<tr><td>" . _PERMISSIONS . ":</td><td>";
				for ( $l = 0; $l < sizeof($listmods); $l++ )
				{
					$title = ereg_replace( "_", " ", $listmods[$l] );
					$xstitle = strtolower( $listmods[$l] );
					if ( (($listmods[$l] == "News") and file_exists("modules/stories.php") and file_exists("links/links.stories.php") and file_exists("case/case.stories.php")) or (($listmods[$l] == "Your_Account") and file_exists("modules/users.php") and file_exists("links/links.editusers.php") and file_exists("case/case.users.php")) or (file_exists("modules/$xstitle.php") and file_exists("links/links.$xstitle.php") and file_exists("case/case.$xstitle.php")) )
					{
						$admins = explode( ",", $listadmins[$l] );
						$sel = "";
						if ( in_array($chng_name, $admins) )
						{
							$sel = "checked";
						}
						echo "<input type=\"checkbox\" name=\"auth_modules[]\" value=\"$listmods[$l]\" $sel> $title ";
						$sel = "";
					}
				}
				echo "</td></tr>";
				if ( ($radminname == "God") || ($chng_radminsuper == 1) || (($chng_radminsuper != 1) and ($superadmin_chg_level == 1)) )
				{
					if ( $chng_radminsuper == 1 )
					{
						$sel1 = "checked";
					}
					echo "<tr><td>&nbsp;</td><td><input type=\"checkbox\" name=\"chng_radminsuper\" value=\"1\" $sel1> <b>" . _SUPERUSER . "</b></td></tr>" . "<tr><td>&nbsp;</td><td><font class=\"tiny\"><i>" . _SUPERWARNING . "</i></font></td></tr>";
				}
				else
				{
					echo "<input type=\"hidden\" name=\"chng_radminsuper\" value=\"$chng_radminsuper\">";
				}
			}
			else
			{
				for ( $l = 0; $l < sizeof($listmods); $l++ )
				{
					$stitle = strtolower( $listmods[$l] );
					if ( (($listmods[$l] == "News") and file_exists("modules/stories.php") and file_exists("links/links.stories.php") and file_exists("case/case.stories.php")) or (($listmods[$l] == "Your_Account") and file_exists("modules/users.php") and file_exists("links/links.editusers.php") and file_exists("case/case.users.php")) or (file_exists("modules/$xstitle.php") and file_exists("links/links.$xstitle.php") and file_exists("case/case.$xstitle.php")) )
					{
						$admins = explode( ",", $listadmins[$l] );
						if ( in_array($chng_name, $admins) )
						{
							echo "<input type=\"hidden\" name=\"auth_modules[]\" value=\"$listmods[$l]\">";
						}

					}
				}
				echo "<input type=\"hidden\" name=\"chng_radminsuper\" value=\"$chng_radminsuper\">";
			}
			echo "<tr><td colspan=2>" . _FORCHANGES . "</td></tr>";
			echo "<tr><td>" . _YOURPASSWORD . ":</td>" . "<td><input type=\"password\" name=\"olpwd\" size=\"12\" maxlength=\"12\"></td></tr>";
			echo "<tr><td>" . _NEWPASS . ":</td>" . "<td><input type=\"password\" name=\"chng_pwd\" size=\"12\" maxlength=\"12\"></td></tr>" . "<tr><td>" . _RETYPEPASSWD . ":</td>" . "<td><input type=\"password\" name=\"chng_pwd2\" size=\"12\" maxlength=\"12\"></td></tr>" . "<input type=\"hidden\" name=\"adm_aid\" value=\"$adm_aid\">" . "<input type=\"hidden\" name=\"op\" value=\"UpdateAuthor\">" . "<tr><td>&nbsp;</td><td><input type=\"submit\" value=\"" . _SAVE . "\"> " . _GOBACK . "" . "</td></tr></table></form>";
			CloseTable();
			include ( "../footer.php" );
		}
		else
		{
			not_admin();
		}
	}

	/**
	 * updateadmin()
	 * 
	 * @param mixed $chng_aid
	 * @param mixed $chng_name
	 * @param mixed $chng_email
	 * @param mixed $chng_url
	 * @param mixed $chng_radminsuper
	 * @param mixed $chng_pwd
	 * @param mixed $chng_pwd2
	 * @param mixed $chng_admlanguage
	 * @param mixed $adm_aid
	 * @param mixed $auth_modules
	 * @param mixed $olpwd
	 * @return
	 */
	function updateadmin( $chng_aid, $chng_name, $chng_email, $chng_url, $chng_radminsuper, $chng_pwd, $chng_pwd2, $chng_admlanguage, $adm_aid, $auth_modules, $olpwd )
	{
		global $adminfile, $admin, $prefix, $db, $radminname, $aid, $superadmin_chg_superadmin, $superadmin_chg_level, $superadmin_edit_level, $superadmin_edit, $superadmin_edit_modadmin;
		if ( defined('IS_ADMIN') )
		{
			$chng_aid = trim( $chng_aid );
			$chng_radminsuper = intval( $chng_radminsuper );
			$olpwd = md5( $olpwd );
			list( $rpwd ) = $db->sql_fetchrow( $db->sql_query("SELECT pwd FROM " . $prefix . "_authors WHERE aid='$aid'") );
			if ( ! $chng_aid || $chng_aid == "" )
			{
				Header( "Location: " . $adminfile . ".php?op=mod_authors" );
				exit();
			}
			$nolevel = 0;
			$check = $db->sql_fetchrow( $db->sql_query("SELECT * FROM " . $prefix . "_authors WHERE aid='$adm_aid'") );
			if ( ! $check or (($radminname != "God") and ($check['name'] == "God")) )
			{
				$nolevel = 1;
			} elseif ( ($radminname != "God") and ($aid != $check['aid']) and ($superadmin_chg_superadmin == 0) and ($check['radminsuper'] == "1") )
			{
				$nolevel = 1;
			} elseif ( ($radminname != "God") and ($aid != $check['aid']) and ($superadmin_chg_level == 0) and ($chng_radminsuper != $check['radminsuper']) )
			{
				$nolevel = 1;
			} elseif ( ($radminname != "God") and ($aid == $check['aid']) and ($superadmin_edit == 0) )
			{
				$nolevel = 1;
			} elseif ( ($radminname != "God") and ($aid == $check['aid']) and ($superadmin_edit_level == 0) and ($chng_radminsuper != $check['radminsuper']) )
			{
				$nolevel = 1;
			} elseif ( ($radminname != "God") and ($superadmin_edit_modadmin == 0) and ($check['radminsuper'] == "0") )
			{
				$nolevel = 1;
			}
			if ( $nolevel == 1 )
			{
				info_exit( "<center><font class=\"option\"><b>" . _CREATIONERROR . "</b></font><br><br>" . _NOLEVELCHGADMIN . "<br><br>" . _GOBACK . "</center>" );
			}
			$badaddadmin = 0;
			if ( ! ($chng_aid && $chng_name && $chng_email) )
			{
				$badaddadmin = 1;
				$badinfo = "" . _COMPLETEFIELDS . "";
			} elseif ( ($chng_name != $check['name']) and ($chng_name == "God") )
			{
				$badaddadmin = 1;
				$badinfo = "" . _BADNAMEADMIN . "";
			} elseif ( ($chng_name != $check[name]) and ($db->sql_numrows($db->sql_query("SELECT * FROM " . $prefix . "_authors WHERE name='$chng_name'")) > 0) )
			{
				$badaddadmin = 1;
				$badinfo = "" . _BADNAMEADMIN2 . "";
			} elseif ( ($chng_aid != $check['aid']) and ((ereg("[^a-zA-Z0-9_-]", $chng_aid)) || (strrpos($chng_aid, ' ') > 0) || ($db->sql_numrows($db->sql_query("SELECT * FROM " . $prefix . "_authors WHERE aid='$chng_aid'")) > 0)) )
			{
				$badaddadmin = 1;
				$badinfo = "" . _BADNICKADMIN . "";
			} elseif ( ($chng_email != $check['email']) and ((! eregi("^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,6}$", $chng_email)) || (strrpos($chng_email, ' ') > 0) || ($db->sql_numrows($db->sql_query("SELECT * FROM " . $prefix . "_authors WHERE email='$chng_email'")) > 0)) )
			{
				$badaddadmin = 1;
				$badinfo = "" . _BADMAILADMIN . "";
			} elseif ( ($chng_pwd2 != "") and ($olpwd != $rpwd) )
			{
				$badaddadmin = 1;
				$badinfo = "" . _BADPASSADMIN3 . " $pwd - $rpwd";
			} elseif ( ($chng_pwd2 != "") and ($chng_pwd != $chng_pwd2) )
			{
				$badaddadmin = 1;
				$badinfo = "" . _BADPASSADMIN2 . "";
			} elseif ( ($chng_pwd2 != "") and ($chng_pwd == $chng_pwd2) and ((strlen($chng_pwd) > 12) || (strlen($chng_pwd) < 5) || (ereg("[^a-zA-Z0-9_-]", $chng_pwd))) )
			{
				$badaddadmin = 1;
				$badinfo = "" . _BADPASSADMIN . "";
			}
			if ( $badaddadmin == 1 )
			{
				info_exit( "<center><font class=\"option\"><b>" . _CREATIONERROR . "</b></font><br><br>$badinfo<br><br>" . _GOBACK . "</center>" );
			}
			if ( $chng_pwd2 != "" )
			{
				$chng_pwd = md5( $chng_pwd );
				$chng_name = trim( $chng_name );
				$chng_aid = substr( "$chng_aid", 0, 25 );
				if ( $chng_radminsuper == 1 )
				{
					$result = $db->sql_query( "SELECT mid, admins FROM " . $prefix . "_modules" );
					while ( $row = $db->sql_fetchrow($result) )
					{
						$admins = explode( ",", $row['admins'] );
						$adm = "";
						for ( $a = 0; $a < sizeof($admins); $a++ )
						{
							if ( $admins[$a] != "$chng_name" and $admins[$a] != "" )
							{
								$adm .= "$admins[$a],";
							}
						}
						if ( $adm != $row['admins'] )
						{
							$db->sql_query( "UPDATE " . $prefix . "_modules SET admins='$adm' WHERE mid='$row[mid]'" );
						}
					}
					$db->sql_query( "update " . $prefix . "_authors set aid='$chng_aid', email='$chng_email', url='$chng_url', radminsuper='$chng_radminsuper', pwd='$chng_pwd', admlanguage='$chng_admlanguage' where name='$chng_name'" );
					Header( "Location: " . $adminfile . ".php?op=mod_authors" );
				}
				else
				{
					if ( $chng_name != "God" )
					{
						$db->sql_query( "update " . $prefix . "_authors set aid='$chng_aid', email='$chng_email', url='$chng_url', radminsuper='0', pwd='$chng_pwd', admlanguage='$chng_admlanguage' where name='$chng_name'" );
					}
					$result = $db->sql_query( "SELECT mid, admins FROM " . $prefix . "_modules" );
					while ( $row = $db->sql_fetchrow($result) )
					{
						$admins = explode( ",", $row['admins'] );
						$adm = "";
						for ( $a = 0; $a < sizeof($admins); $a++ )
						{
							if ( $admins[$a] != "$chng_name" and $admins[$a] != "" )
							{
								$adm .= "$admins[$a],";
							}
						}
						$db->sql_query( "UPDATE " . $prefix . "_modules SET admins='$adm' WHERE mid='$row[mid]'" );
					}
					for ( $i = 0; $i < sizeof($auth_modules); $i++ )
					{
						$row = $db->sql_fetchrow( $db->sql_query("SELECT admins FROM " . $prefix . "_modules WHERE title='$auth_modules[$i]'") );
						$admins = explode( ",", $row['admins'] );
						for ( $a = 0; $a < sizeof($admins); $a++ )
						{
							if ( $admins[$a] == "$chng_name" )
							{
								$dummy = 1;
							}
						}
						if ( $dummy != 1 )
						{
							$adm = "$row[admins]$chng_name";
							$db->sql_query( "UPDATE " . $prefix . "_modules SET admins='$adm,' WHERE title='$auth_modules[$i]'" );
						}
						$dummy = 0;
					}
					Header( "Location: " . $adminfile . ".php?op=mod_authors" );
				}
			}
			else
			{
				if ( $chng_radminsuper == 1 )
				{
					$result = $db->sql_query( "SELECT mid, admins FROM " . $prefix . "_modules" );
					while ( $row = $db->sql_fetchrow($result) )
					{
						$admins = explode( ",", $row[admins] );
						$adm = "";
						for ( $a = 0; $a < sizeof($admins); $a++ )
						{
							if ( $admins[$a] != "$chng_name" and $admins[$a] != "" )
							{
								$adm .= "$admins[$a],";
							}
						}
						$db->sql_query( "UPDATE " . $prefix . "_modules SET admins='$adm' WHERE mid='$row[mid]'" );
					}
					$db->sql_query( "update " . $prefix . "_authors set aid='$chng_aid', email='$chng_email', url='$chng_url', radminsuper='$chng_radminsuper', admlanguage='$chng_admlanguage' where name='$chng_name'" );
					Header( "Location: " . $adminfile . ".php?op=mod_authors" );
				}
				else
				{
					if ( $chng_name != "God" )
					{
						$db->sql_query( "update " . $prefix . "_authors set aid='$chng_aid', email='$chng_email', url='$chng_url', radminsuper='0', admlanguage='$chng_admlanguage' where name='$chng_name'" );
					}
					$result = $db->sql_query( "SELECT mid, admins FROM " . $prefix . "_modules" );
					while ( $row = $db->sql_fetchrow($result) )
					{
						$admins = explode( ",", $row[admins] );
						$adm = "";
						for ( $a = 0; $a < sizeof($admins); $a++ )
						{
							if ( $admins[$a] != "$chng_name" and $admins[$a] != "" )
							{
								$adm .= "$admins[$a],";
							}
						}
						$db->sql_query( "UPDATE " . $prefix . "_modules SET admins='$adm' WHERE mid='$row[mid]'" );
					}
					for ( $i = 0; $i < sizeof($auth_modules); $i++ )
					{
						$row = $db->sql_fetchrow( $db->sql_query("SELECT admins FROM " . $prefix . "_modules WHERE title='$auth_modules[$i]'") );
						$admins = explode( ",", $row[admins] );
						for ( $a = 0; $a < sizeof($admins); $a++ )
						{
							if ( $admins[$a] == "$chng_name" )
							{
								$dummy = 1;
							}
						}
						if ( $dummy != 1 )
						{
							$adm = "$row[admins]$chng_name";
							$db->sql_query( "UPDATE " . $prefix . "_modules SET admins='$adm,' WHERE title='$auth_modules[$i]'" );
						}
						$dummy = "";
					}
					Header( "Location: " . $adminfile . ".php?op=mod_authors" );
				}
			}
			if ( $adm_aid != $chng_aid )
			{
				$result2 = $db->sql_query( "SELECT sid, aid from " . $prefix . "_stories where aid='$adm_aid'" );
				while ( $row2 = $db->sql_fetchrow($result2) )
				{
					$sid = intval( $row2['sid'] );
					$db->sql_query( "update " . $prefix . "_stories set aid='$chng_aid' WHERE sid='$sid'" );
				}
			}
		}
		else
		{
			not_admin();
		}
	}

	/**
	 * deladmin()
	 * 
	 * @param mixed $del_aid
	 * @return
	 */
	function deladmin( $del_aid )
	{
		global $adminfile, $prefix, $db, $radminname, $superadmin_edit_modadmin;
		if ( defined('IS_ADMIN') )
		{
			$del_aid = trim( $del_aid );
			$del_aid = substr( "$del_aid", 0, 25 );
			$nolevel = 0;
			$check = $db->sql_fetchrow( $db->sql_query("SELECT * FROM " . $prefix . "_authors WHERE aid='$del_aid'") );
			if ( $check[name] == "God" )
			{
				$nolevel = 1;
			} elseif ( ($radminname != "God") and ($check['radminsuper'] == 1) )
			{
				$nolevel = 1;
			} elseif ( ($radminname != "God") and ($check['radminsuper'] == 0) and ($superadmin_edit_modadmin == 0) )
			{
				$nolevel = 1;
			}
			if ( $nolevel == 1 )
			{
				include ( "../header.php" );
				GraphicAdmin();
				OpenTable();
				echo "<center><font class=\"title\"><b>" . _AUTHORSADMIN . "</b></font></center>";
				CloseTable();
				echo "<br>";
				OpenTable();
				echo "<center><font class=\"option\"><b>" . _SUPERADMININFO9 . "</b></font><br><br>" . "" . _GOBACK . "</center>";
				CloseTable();
				include ( "../footer.php" );
				return;
			}
			include ( "../header.php" );
			GraphicAdmin();
			OpenTable();
			echo "<center><font class=\"title\"><b>" . _AUTHORSADMIN . "</b></font></center>";
			CloseTable();
			echo "<br>";
			OpenTable();
			echo "<center><font class=\"option\"><b>" . _AUTHORDEL . "</b></font><br><br>" . "" . _AUTHORDELSURE . " <i>$del_aid</i>?<br><br>";
			echo "[ <a href=\"" . $adminfile . ".php?op=deladmin2&amp;del_aid=$del_aid\">" . _YES . "</a> | <a href=\"" . $adminfile . ".php?op=mod_authors\">" . _NO . "</a> ]";
			CloseTable();
			include ( "../footer.php" );
		}
		else
		{
			not_admin();
		}
	}

	/**
	 * deladmin2()
	 * 
	 * @param mixed $del_aid
	 * @return
	 */
	function deladmin2( $del_aid )
	{
		global $adminfile, $prefix, $db, $radminname, $superadmin_edit_modadmin;
		if ( defined('IS_ADMIN') )
		{
			$del_aid = substr( "$del_aid", 0, 25 );
			$nolevel = 0;
			$check = $db->sql_fetchrow( $db->sql_query("SELECT * FROM " . $prefix . "_authors WHERE aid='$del_aid'") );
			if ( $check[name] == "God" )
			{
				$nolevel = 1;
			} elseif ( ($radminname != "God") and ($check[radminsuper] == 1) )
			{
				$nolevel = 1;
			} elseif ( ($radminname != "God") and ($check[radminsuper] == 0) and ($superadmin_edit_modadmin == 0) )
			{
				$nolevel = 1;
			}
			if ( $nolevel == 1 )
			{
				include ( "../header.php" );
				GraphicAdmin();
				OpenTable();
				echo "<center><font class=\"title\"><b>" . _AUTHORSADMIN . "</b></font></center>";
				CloseTable();
				echo "<br>";
				OpenTable();
				echo "<center><font class=\"option\"><b>" . _SUPERADMININFO9 . "</b></font><br><br>" . "" . _GOBACK . "</center>";
				CloseTable();
				include ( "../footer.php" );
				return;
			}
			$result = $db->sql_query( "SELECT admins FROM " . $prefix . "_modules WHERE title='News'" );
			$row2 = $db->sql_fetchrow( $db->sql_query("SELECT name FROM " . $prefix . "_authors WHERE aid='$del_aid'") );
			while ( $row = $db->sql_fetchrow($result) )
			{
				$admins = explode( ",", $row[admins] );
				$radminarticle = 0;
				if ( in_array($row2[name], $admins) )
				{
					$radminarticle = 1;
				}
			}
			if ( $radminarticle == 1 )
			{
				$row2 = $db->sql_fetchrow( $db->sql_query("SELECT sid from " . $prefix . "_stories where aid='$del_aid'") );
				$sid = intval( $row2['sid'] );
				if ( $sid != "" )
				{
					include ( "../header.php" );
					GraphicAdmin();
					OpenTable();
					echo "<center><font class=\"title\"><b>" . _AUTHORSADMIN . "</b></font></center>";
					CloseTable();
					echo "<br>";
					OpenTable();
					echo "<center><font class=\"option\"><b>" . _PUBLISHEDSTORIES . "</b></font><br><br>" . "" . _SELECTNEWADMIN . ":<br><br>";
					$result3 = $db->sql_query( "SELECT aid from " . $prefix . "_authors where aid!='$del_aid'" );
					echo "<form action=\"" . $adminfile . ".php\" method=\"post\"><select name=\"newaid\">";
					while ( $row3 = $db->sql_fetchrow($result3) )
					{
						$oaid = $row3['aid'];
						$oaid = substr( "$oaid", 0, 25 );
						echo "<option name=\"newaid\" value=\"$oaid\">$oaid</option>";
					}
					echo "</select><input type=\"hidden\" name=\"del_aid\" value=\"$del_aid\">" . "<input type=\"hidden\" name=\"op\" value=\"assignstories\">" . "<input type=\"submit\" value=\"" . _OK . "\">" . "</form>";
					CloseTable();
					include ( "../footer.php" );
					return;
				}
			}
			Header( "Location: " . $adminfile . ".php?op=deladminconf&del_aid=$del_aid" );
		}
		else
		{
			not_admin();
		}
	}

	/**
	 * assignstories()
	 * 
	 * @param mixed $newaid
	 * @param mixed $del_aid
	 * @return
	 */
	function assignstories( $newaid, $del_aid )
	{
		global $adminfile, $prefix, $db, $radminname, $superadmin_edit_modadmin;
		if ( defined('IS_ADMIN') )
		{
			$del_aid = substr( "$del_aid", 0, 25 );
			$nolevel = 0;
			$check = $db->sql_fetchrow( $db->sql_query("SELECT * FROM " . $prefix . "_authors WHERE aid='$del_aid'") );
			if ( $check[name] == "God" )
			{
				$nolevel = 1;
			} elseif ( ($radminname != "God") and ($check[radminsuper] == 1) )
			{
				$nolevel = 1;
			} elseif ( ($radminname != "God") and ($check[radminsuper] == 0) and ($superadmin_edit_modadmin == 0) )
			{
				$nolevel = 1;
			}
			if ( $nolevel == 1 )
			{
				include ( "../header.php" );
				GraphicAdmin();
				OpenTable();
				echo "<center><font class=\"title\"><b>" . _AUTHORSADMIN . "</b></font></center>";
				CloseTable();
				echo "<br>";
				OpenTable();
				echo "<center><font class=\"option\"><b>" . _SUPERADMININFO9 . "</b></font><br><br>" . "" . _GOBACK . "</center>";
				CloseTable();
				include ( "../footer.php" );
				return;
			}
			$del_aid = trim( $del_aid );
			$result = $db->sql_query( "SELECT sid from " . $prefix . "_stories where aid='$del_aid'" );
			while ( $row = $db->sql_fetchrow($result) )
			{
				$sid = intval( $row['sid'] );
				$db->sql_query( "update " . $prefix . "_stories set aid='$newaid' where aid='$del_aid'" );
			}
			Header( "Location: " . $adminfile . ".php?op=deladminconf&del_aid=$del_aid" );
		}
		else
		{
			not_admin();
		}
	}

	/**
	 * deladminconf()
	 * 
	 * @param mixed $del_aid
	 * @return
	 */
	function deladminconf( $del_aid )
	{
		global $superadmin_edit_modadmin, $adminfile, $prefix, $db, $radminname;
		if ( defined('IS_ADMIN') )
		{
			$del_aid = substr( "$del_aid", 0, 25 );
			$nolevel = 0;
			$check = $db->sql_fetchrow( $db->sql_query("SELECT * FROM " . $prefix . "_authors WHERE aid='$del_aid'") );
			if ( $check[name] == "God" )
			{
				$nolevel = 1;
			} elseif ( ($radminname != "God") and ($check[radminsuper] == 1) )
			{
				$nolevel = 1;
			} elseif ( ($radminname != "God") and ($check[radminsuper] == 0) and ($superadmin_edit_modadmin == 0) )
			{
				$nolevel = 1;
			}
			if ( $nolevel == 1 )
			{
				include ( "../header.php" );
				GraphicAdmin();
				OpenTable();
				echo "<center><font class=\"title\"><b>" . _AUTHORSADMIN . "</b></font></center>";
				CloseTable();
				echo "<br>";
				OpenTable();
				echo "<center><font class=\"option\"><b>" . _SUPERADMININFO9 . "</b></font><br><br>" . "" . _GOBACK . "</center>";
				CloseTable();
				include ( "../footer.php" );
				return;
			}
			$del_aid = trim( $del_aid );
			$db->sql_query( "delete from " . $prefix . "_authors where aid='$del_aid' AND name!='God'" );
			$result = $db->sql_query( "SELECT mid, admins FROM " . $prefix . "_modules" );
			while ( $row = $db->sql_fetchrow($result) )
			{
				$admins = explode( ",", $row[admins] );
				$adm = "";
				for ( $a = 0; $a < sizeof($admins); $a++ )
				{
					if ( $admins[$a] != "" . $check['name'] . "" and $admins[$a] != "" )
					{
						$adm .= "$admins[$a],";
					}
				}
				$db->sql_query( "UPDATE " . $prefix . "_modules SET admins='$adm' WHERE mid='$row[mid]'" );
			}
			Header( "Location: " . $adminfile . ".php?op=mod_authors" );
		}
		else
		{
			not_admin();
		}
	}

	switch ( $op )
	{

		case "AdminsConfigSave":
			AdminsConfigSave();
			break;

		case "mod_authors":
			displayadmins();
			break;

		case "modifyadmin":
			modifyadmin( $chng_aid );
			break;

		case "UpdateAuthor":
			updateadmin( $chng_aid, $chng_name, $chng_email, $chng_url, $chng_radminsuper, $chng_pwd, $chng_pwd2, $chng_admlanguage, $adm_aid, $auth_modules, $olpwd );
			break;

		case "AddAuthor":
			AddAuthor( $add_name, $add_aid, $add_email, $add_url, $add_admlanguage, $auth_modules, $add_radminsuper, $add_pwd );
			break;

		case "deladmin":
			deladmin( $del_aid );
			break;

		case "deladmin2":
			deladmin2( $del_aid );
			break;

		case "assignstories":
			assignstories( $newaid, $del_aid );
			break;

		case "deladminconf":
			deladminconf( $del_aid );
			break;

	}

}
else
{
	echo "Access Denied";
}

?>