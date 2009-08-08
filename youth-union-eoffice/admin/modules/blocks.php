<?php

/*
* @Program:		NukeViet CMS
* @File name: 	NukeViet System
* @Version: 	2.0 RC2
* @Date: 		05.07.2009
* @Website: 	www.nukeviet.vn
* @Copyright: 	(C) 2009
* @License: 	http://opensource.org/licenses/gpl-license.php GNU Public License
*/

if ( ! defined('NV_ADMIN') ) die( "Access Denied" );

if ( defined('IS_SPADMIN') )
{
	/**
	 * fixweight2()
	 * 
	 * @param mixed $blanguage
	 * @return
	 */
	function fixweight2( $blanguage )
	{
		global $adminfile;
		fixweight();
		blist();
		Header( "Location: " . $adminfile . ".php?op=BlocksAdmin&blanguage=$blanguage" );
		exit();
	}

	/**
	 * BlocksAdmin()
	 * 
	 * @return
	 */
	function BlocksAdmin()
	{
		global $editor, $prefix, $db, $currentlang, $multilingual, $adminfile, $listmods;
		include ( "../header.php" );
//		GraphicAdmin();
		OpenTable();
		echo "<center><font class=\"title\"><b><a href=" . $adminfile . ".php?op=BlocksAdmin>"._BLOCKSADMIN."</a> | <a href=" . $adminfile . ".php?op=HeadlinesAdmin>RSS Reader Block Admin</a></b></font></center>";
		CloseTable();
		echo "<br>";
		$blanguage2 = ( isset($_POST['blanguage']) ) ? $_POST['blanguage'] : $_GET['blanguage'];
		if ( $multilingual == 1 )
		{
			if ( ! isset($blanguage2) || $blanguage2 == "" )
			{
				echo "<center><form action=\"" . $adminfile . ".php\" method=\"POST\"><select name=\"blanguage\">";
				echo select_language( $currentlang );
				echo "</select>";
				echo "<input type=\"hidden\" name=\"op\" value=\"BlocksAdmin\"><input type=\"submit\" value=\"" . _BLOCKPREVIEW . "\"></form></center><br><br>";
				CloseTable();
				include ( "../footer.php" );
				exit();
			}
		}
		if ( isset($blanguage2) and (file_exists(INCLUDE_PATH . "language/lang-" . $blanguage2 . ".php")) and (! eregi("\.", "" . $blanguage2 . "")) )
		{
			$blanguage = $blanguage2;
		}
		else
		{
			$blanguage = $currentlang;
		}

		echo "<table style=\"border-collapse: collapse;border: 1px solid #CCCCCC; width: 100%\">\n";
		echo "<tr>\n";
		echo "<td style=\"padding:1px\">\n";
		echo "<table style=\"border-collapse: collapse;width: 100%\"><tr  style=\"background-color: #E4E4E4;font-weight: bold\">
		" . "<td style=\"border-style: solid; border-width: 0px 1px 0px 0px; border-color: #CCCCCC;padding: 5px; text-align: center;white-space: nowrap;\"><b>" . _TITLE . "</b></td>
		" . "<td style=\"border-style: solid; border-width: 0px 1px 0px 0px; border-color: #CCCCCC;padding: 5px; text-align: center;white-space: nowrap;\"><b>" . _POSITION . "</b></td>
		" . "<td style=\"border-style: solid; border-width: 0px 1px 0px 0px; border-color: #CCCCCC;padding: 5px; text-align: center;white-space: nowrap;\"><b>" . _WEIGHT . "</b></td>
		" . "<td style=\"border-style: solid; border-width: 0px 1px 0px 0px; border-color: #CCCCCC;padding: 5px; text-align: center;white-space: nowrap;\"><b>" . _TYPE . "</b></td>
		" . "<td style=\"border-style: solid; border-width: 0px 1px 0px 0px; border-color: #CCCCCC;padding: 5px; text-align: center;white-space: nowrap;\"><b>" . _STATUS . "</b></td>
		" . "<td style=\"border-style: solid; border-width: 0px 1px 0px 0px; border-color: #CCCCCC;padding: 5px; text-align: center;white-space: nowrap;\"><b>" . _VIEW . "</b></td>
		<td style=\"border-style: solid; border-width: 0px 1px 0px 0px; border-color: #CCCCCC;padding: 5px; text-align: center;white-space: nowrap;\"><b>" . _FUNCTIONS . "</b></tr>";
		$sql = "select bid, bkey, title, url, bposition, weight, active, blockfile, view, link, module from " . $prefix . "_blocks WHERE blanguage='$blanguage' order by bposition, weight";
		$result = $db->sql_query( $sql );
		$a = 1;
		while ( $row = $db->sql_fetchrow($result) )
		{
			$a++;
			$bid = $row[bid];
			$bkey = intval( $row[bkey] );
			$title = $row[title];
			$url = $row[url];
			$bposition = $row[bposition];
			$weight = $row[weight];
			$active = $row[active];
			$blockfile = $row[blockfile];
			$view = $row[view];
			$link = $row[link];
			$sql = "select bid from " . $prefix . "_blocks where bposition='$bposition' AND blanguage='$blanguage'";
			$res2 = $db->sql_query( $sql );
			$row = $db->sql_numrows( $res2 );
			$bgcolor = ( $a % 2 == 0 ) ? "#FFFFF" : "#E4E4E4";
			echo "<tr style=\"background-color: " . $bgcolor . ";\">" . "<td style=\"border-style: solid; border-width: 0px 1px 0px 0px; border-color: #CCCCCC; padding: 5px; text-align: left;\" title=\"Url: $link\">$title</td>";
			if ( $bposition == "l" )
			{
				$bposition = "<img src=\"../images/center_r.gif\" border=\"0\" alt=\"" . _LEFTBLOCK . "\" title=\"" . _LEFTBLOCK . "\" hspace=\"5\"> " . _LEFT . "";
			} elseif ( $bposition == "r" )
			{
				$bposition = "" . _RIGHT . " <img src=\"../images/center_l.gif\" border=\"0\" alt=\"" . _RIGHTBLOCK . "\" title=\"" . _RIGHTBLOCK . "\" hspace=\"5\">";
			} elseif ( $bposition == "c" )
			{
				$bposition = "<img src=\"../images/center_l.gif\" border=\"0\" alt=\"" . _CENTERBLOCK . "\" title=\"" . _CENTERBLOCK . "\">&nbsp;" . _CENTERUP . "&nbsp;<img src=\"../images/center_r.gif\" border=\"0\" alt=\"" . _CENTERBLOCK . "\" title=\"" . _CENTERBLOCK . "\">";
			} elseif ( $bposition == "d" )
			{
				$bposition = "<img src=\"../images/center_l.gif\" border=\"0\" alt=\"" . _CENTERBLOCK . "\" title=\"" . _CENTERBLOCK . "\">&nbsp;" . _CENTERDOWN . "&nbsp;<img src=\"../images/center_r.gif\" border=\"0\" alt=\"" . _CENTERBLOCK . "\" title=\"" . _CENTERBLOCK . "\">";
			}
			
		echo "<td style=\"border-style: solid; border-width: 0px 1px 0px 0px; border-color: #CCCCCC;padding: 5px; text-align: center;white-space: nowrap;\">$bposition</td>" ;	
		echo "<td style=\"border-style: solid; border-width: 0px 1px 0px 0px; border-color: #CCCCCC;padding: 5px; text-align: center;white-space: nowrap;\">\n";
			echo "<form method=\"get\">\n";
			echo "<select name=\"select1_" . $bid . "\" onchange=\"top.location.href=this.options[this.selectedIndex].value\">\n";
			for ( $i = 1; $i <= $row; $i++ )
			{
				echo "<option value=\"" . $adminfile . ".php?op=BlockOrder&amp;id=" . $bid . "&amp;new=" . $i . "&amp;blanguage=$blanguage\"" . ( ($weight == $i) ? " selected=\"selected\"" : "" ) . ">" . $i . "</option>\n";
			}
			echo "</select></form>\n";
				echo "</td>\n";
			if ( $bkey == "2" )
			{
				$type = "RSS/RDF";
			} elseif ( $bkey == "1" )
			{
				$type = "HTML";
			}
			else
			{
				$type = _BLOCKFILE2;
			}
			echo "<td style=\"border-style: solid; border-width: 0px 1px 0px 0px; border-color: #CCCCCC;padding: 5px; text-align: center;white-space: nowrap;\">$type</td>";
			$block_act = $active;
			if ( $active == 1 )
			{
				$active = _ACTIVE;
				$change = _DEACTIVATE;
			} elseif ( $active == 0 )
			{
				$active = "<i>" . _INACTIVE . "</i>";
				$change = _ACTIVATE;
			}
			echo "<td style=\"border-style: solid; border-width: 0px 1px 0px 0px; border-color: #CCCCCC;padding: 5px; text-align: center;white-space: nowrap;\">$active</td>";
			if ( $view == 0 )
			{
				$who_view = _MVALL;
			} elseif ( $view == 1 )
			{
				$who_view = _MVUSERS;
			} elseif ( $view == 2 )
			{
				$who_view = _MVADMIN;
			} elseif ( $view == 3 )
			{
				$who_view = _MVANON;
			}
			echo "<td style=\"border-style: solid; border-width: 0px 1px 0px 0px; border-color: #CCCCCC;padding: 5px; text-align: center;white-space: nowrap;\">$who_view</td>";
			echo "<td style=\"border-style: solid; border-width: 0px 1px 0px 0px; border-color: #CCCCCC;padding: 5px; text-align: center;white-space: nowrap;\"><font class=\"content\">[ <a href=\"" . $adminfile . ".php?op=BlocksEdit&amp;bid=$bid\">" . _EDIT . "</a> | <a href=\"" . $adminfile . ".php?op=ChangeStatus&amp;bid=$bid\">$change</a> | ";
			echo "<a href=\"" . $adminfile . ".php?op=BlocksDelete&amp;bid=$bid\">" . _DELETE . "</a> | <a href=\"" . $adminfile . ".php?op=block_show&bid=$bid\">" . _SHOW . "</a> ]</font></td></tr>";
		}
		echo "</table></table><br>";
		echo "<center>[ <a href=\"" . $adminfile . ".php?op=fixweight2&blanguage=$blanguage\">" . _FIXBLOCKS . "</a> ]</center><br>";
		
		echo "<br>";
		OpenTable();
		echo "<a name='add'></a>";
		echo "<center><font class=\"option\"><b><a href='" . $adminfile . ".php?op=BlocksAdmin&blanguage=$blanguage#add'>" . _ADDNEWBLOCK . "</a></b></font></center><br><br>";
		if ( ! isset($_POST['tip']) || $_POST['tip'] == "" )
		{
			echo "<center><form action=\"" . $adminfile . ".php#add\" method=\"POST\"><select name=\"tip\">";
			$tip_ar = array( _BLOCKFILE2, 'HTML', 'RSS/RDF' );
			echo "<option value=\"\">" . _TYPE . "</option>\n";
			for ( $i = 0; $i < sizeof($tip_ar); $i++ )
			{
				echo "<option value=\"$i\">" . ucfirst( $tip_ar[$i] ) . "</option>\n";
			}
			echo "</select>";
			echo "<input type=\"hidden\" name=\"blanguage\" value=\"$blanguage\">";
			echo "<input type=\"hidden\" name=\"op\" value=\"BlocksAdmin\"><input type=\"submit\" value=\"" . _ADD . "\"></form></center><br><br>";
			CloseTable();
			include ( "../footer.php" );
			exit();
		}
		$tip = intval( $_POST['tip'] );
		if ( $tip != '1' and $tip != '2' )
		{
			$tip = '0';
		}

		echo "<form action=\"" . $adminfile . ".php\" method=\"post\">" . "<table border=\"0\" width=\"100%\">" . "<tr><td>" . _TITLE . ":</td><td><input type=\"text\" name=\"title\" size=\"30\" maxlength=\"60\"></td></tr>";
		if ( $tip == 2 )
		{
			echo "<tr><td>" . _RSSFILE . ":</td><td><input type=\"text\" name=\"url\" size=\"30\" maxlength=\"200\">&nbsp;&nbsp;" . "<select name=\"headline\">" . "<option name=\"headline\" value=\"0\" selected>" . _CUSTOM . "</option>";
			$sql = "select hid, sitename from " . $prefix . "_headlines";
			$res = $db->sql_query( $sql );
			while ( $row = $db->sql_fetchrow($res) )
			{
				$hid = $row[hid];
				$htitle = $row[sitename];
				echo "<option name=\"headline\" value=\"$hid\">$htitle</option>";
			}
			echo "</select>&nbsp;[ <a href=\"" . $adminfile . ".php?op=HeadlinesAdmin\">Setup</a> ]<br><font class=\"tiny\">";
			echo "" . _SETUPHEADLINES . "</font></td></tr>";
		}
		else
		{
			echo "<input type=\"hidden\" name=\"url\" value=\"\">";
			echo "<input type=\"hidden\" name=\"headline\" value=\"0\">";
		}

		if ( $tip != 2 )
		{
			echo "<tr><td>" . _BLOCKLINK . ":</td><td align=\"left\">&nbsp;<input type='text' name='link' size='50'>&nbsp;&nbsp;<font class=\"tiny\">" . _LINKINCLUDE . "</font></td></tr>";
		}
		else
		{
			echo "<input type=\"hidden\" name=\"link\" value=\"\">";
		}


		echo "<input type=\"hidden\" name=\"blanguage\" value=\"$blanguage\">";
		if ( $tip == 0 )
		{
			echo "<tr><td>" . _FILENAME . ":</td><td>" . "<select name=\"blockfile\">" . "<option name=\"blockfile\" value=\"\" selected>" . _NONE . "</option>";
			$blockslist = array();
			$blocksdir = dir( "../blocks" );
			while ( $func = $blocksdir->read() )
			{
				if ( substr($func, 0, 6) == "block-" )
				{
					$blockslist[] = $func;
				}
			}
			closedir( $blocksdir->handle );
			sort( $blockslist );

			$blockslist2 = $blockslist;
			$sql = "SELECT * FROM `" . $prefix . "_blocks` WHERE `blanguage`='" . $blanguage . "'";
			$result = $db->sql_query( $sql );
			while ( $row = $db->sql_fetchrow($result) )
			{
				if ( $row['blockfile'] != "" and in_array($row['blockfile'], $blockslist2) ) $blockslist = array_diff( $blockslist, array($row['blockfile']) );
			}
			foreach ( $blockslist as $block )
			{
				$bl = ereg_replace( "block-", "", $block );
				$bl = ereg_replace( ".php", "", $bl );
				$bl = ereg_replace( "_", " ", $bl );
				echo "<option value=\"" . $block . "\">" . $bl . "</option>\n";
			}
			echo "</select>&nbsp;&nbsp;<font class=\"tiny\">" . _FILEINCLUDE . "</font></td></tr>";
		}
		else
		{
			echo "<input type=\"hidden\" name=\"blockfile\" value=\"\">";
		}
		if ( $tip == 1 )
		{
			echo "<tr><td>" . _CONTENT . ":</td><td>";
			if ( $editor == 1 )
			{
				aleditor( "content", "", 350, 250 );
			}
			else
			{
				echo "<textarea name=\"content\" cols=\"50\" rows=\"10\"></textarea>";
			}
			echo "<br><font class=\"tiny\">" . _IFRSSWARNING . "</font></td></tr>";
		}
		else
		{
			echo "<input type=\"hidden\" name=\"content\" value=\"\">";
		}
		echo "<tr><td>" . _POSITION . ":</td><td><select name=\"bposition\"><option name=\"bposition\" value=\"l\">" . _LEFT . "</option>" . "<option name=\"bposition\" value=\"c\">" . _CENTERUP . "</option>" . "<option name=\"bposition\" value=\"d\">" . _CENTERDOWN . "</option>" . "<option name=\"bposition\" value=\"r\">" . _RIGHT . "</option></select></td></tr>";
		echo "<tr><td>" . _ACTIVATE2 . "</td><td><input type=\"radio\" name=\"active\" value=\"1\" checked>" . _YES . " &nbsp;&nbsp;" . "<input type=\"radio\" name=\"active\" value=\"0\">" . _NO . "</td></tr>" . "<tr><td>" . _EXPIRATION . ":</td><td><input type=\"text\" name=\"expire\" size=\"4\" maxlength=\"3\" value=\"0\"> " . _DAYS . "</td></tr>" . "<tr><td>" . _AFTEREXPIRATION . ":</td><td><select name=\"action\">" . "<option name=\"action\" value=\"d\">" . _DEACTIVATE . "</option>" . "<option name=\"action\" value=\"r\">" . _DELETE . "</option></select></td></tr>";
		if ( $tip == 2 )
		{
			echo "<tr><td>" . _REFRESHTIME . ":</td><td><select name=\"refresh\">" . "<option name=\"refresh\" value=\"1800\">1/2 " . _HOUR . "</option>" . "<option name=\"refresh\" value=\"3600\" selected>1 " . _HOUR . "</option>" . "<option name=\"refresh\" value=\"18000\">5 " . _HOURS . "</option>" . "<option name=\"refresh\" value=\"36000\">10 " . _HOURS . "</option>" . "<option name=\"refresh\" value=\"86400\">24 " . _HOURS . "</option></select>&nbsp;<font class=\"tiny\">" . _ONLYHEADLINES . "</font></td></tr>";
		}
		else
		{
			echo "<input type=\"hidden\" name=\"refresh\" value=\"0\">";
		}
		echo "<tr><td>" . _VIEWPRIV . "</td><td><select name=\"view\">" . "<option value=\"0\" >" . _MVALL . "</option>" . "<option value=\"1\" >" . _MVUSERS . "</option>" . "<option value=\"2\" >" . _MVADMIN . "</option>" . "<option value=\"3\" >" . _MVANON . "</option>" . "</select>" . "</td></tr>" . "<tr valign=\"top\">\n<td><b>" . _DISPLAYAREA . ": </b></td>\n<td>";

		echo "<table border=\"1\" style=\"border-collapse: collapse\" cellpadding=\"2\" cellspacing=\"2\" bordercolor=\"$bgcolor2\"><tr>\n";
		echo "<td><input type=\"checkbox\" name=\"module[]\" value=\"all\" checked=\"checked\"> " . _ALL . "</td>\n<td><input type=\"checkbox\" name=\"module[]\" value=\"home\"> " . _HOME . "</td>\n<td><input type=\"checkbox\" name=\"module[]\" value=\"acp\"> " . _ACP . "</td>\n" . "</tr></table>\n";
		echo "<table border=\"1\" style=\"border-collapse: collapse\" cellpadding=\"2\" cellspacing=\"2\" bordercolor=\"$bgcolor2\"><tr>\n";
		$a = 1;
		foreach ( $listmods as $mod )
		{
			echo "<td><input name=\"module[]\" type=\"checkbox\" value=\"" . $mod . "\"> " . $mod . "</td>\n";
			if ( ($a % 5 == 0) and ($a != sizeof($listmods)) ) echo "</tr><tr>";
			$a++;
		}
		echo "</table>" . "</td>\n</tr>\n" . "</table><br><br>" . "<input type=\"hidden\" name=\"bkey\" value=\"$tip\">" . "<input type=\"hidden\" name=\"op\" value=\"BlocksAdd\">" . "<input type=\"submit\" value=\"" . _CREATEBLOCK . "\"></form>";
		CloseTable();
		include ( "../footer.php" );
	}

	/**
	 * block_show()
	 * 
	 * @param mixed $bid
	 * @return
	 */
	function block_show( $bid )
	{
		global $prefix, $db, $adminfile, $datafold;
		include ( "../header.php" );
//		GraphicAdmin();
		OpenTable2();
		$bid = intval( $bid );
		$sql = "select * from " . $prefix . "_blocks where bid='$bid'";
		$result = $db->sql_query( $sql );
		$row = $db->sql_fetchrow( $result );
		$bid = $row[bid];
		$bkey = $row[bkey];
		$title = $row[title];
		$url = $row[url];
		$bposition = $row[bposition];
		$blockfile = $row[blockfile];
		$link = $row[link];
		$blanguage = $row['blanguage'];
		$active = $row[active];

		title( "<a href=\"" . $adminfile . ".php?op=BlocksAdmin&blanguage=$blanguage\">" . _BLOCKSADMIN . "</a>" );
		if ( $active == 1 )
		{
			$change = _DEACTIVATE;
		} elseif ( $active == 0 )
		{
			$change = _ACTIVATE;
		}
		if ( $bkey == 0 )
		{
			$block_path = "" . INCLUDE_PATH . "blocks/";
		}
		else
		{
			$block_path = "" . INCLUDE_PATH . "" . $datafold . "/";
		}
		$file = @file( "" . $block_path . "" . $blockfile . "" );
		if ( ! $file )
		{
			$content = _BLOCKPROBLEM;
		}
		else
		{
			include ( "" . $block_path . "" . $blockfile . "" );
		}
		if ( $bkey != 0 )
		{
			$content = html_entity_decode( $content );
		}
		$content = ereg_replace( "src=\"images/arrow2.gif\"", "src=\"../images/arrow2.gif\"", $content );
		if ( $bposition == "c" )
		{
			themecenterbox( $title, $content, $link );
		} elseif ( $bposition == "d" )
		{
			themecenterbox( $title, $content, $link );
		}
		else
		{
			themesidebox( $title, $content, $link );
		}
		CloseTable2();
		echo "<br>";
		OpenTable();
		echo "<center><font class=\"option\"><b>" . _FUNCTIONS . "</b></font><br><br>" . "[ <a href=\"" . $adminfile . ".php?op=ChangeStatus&bid=$bid\">" . $change . "</a> | <a href=\"" . $adminfile . ".php?op=BlocksEdit&bid=$bid\">" . _EDIT . "</a> | ";
		echo "<a href=\"" . $adminfile . ".php?op=BlocksDelete&bid=$bid\">" . _DELETE . "</a> | ";
		echo "<a href=\"" . $adminfile . ".php?op=BlocksAdmin&blanguage=$blanguage\">" . _BLOCKSADMIN . "</a> ]</center>";
		CloseTable();
		echo "<br>";
		include ( "../footer.php" );
	}

	/**
	 * BlockOrder()
	 * @return
	 */
	 
	function BlockOrder()
	{
		global $db, $prefix, $adminfile, $currentlang, $multilingual;
		$blanguage2 = ( isset($_POST['blanguage']) ) ? $_POST['blanguage'] : $_GET['blanguage'];
		if ( isset($blanguage2) and (file_exists(INCLUDE_PATH . "language/lang-" . $blanguage2 . ".php")) and (! eregi("\.", "" . $blanguage2 . "")) )
		{
			$blanguage = $blanguage2;
		}
		else
		{
			$blanguage = $currentlang;
		}
		$id = intval( $_REQUEST['id'] );
		$newk = intval( $_REQUEST['new'] );
		if ( $id and $newk )
		{
			$result = $db->sql_query(" select bposition from " . $prefix . "_blocks WHERE bid = '$id'");
			$row = $db->sql_fetchrow($result);
			if ($row)
			{
					$subid = $row['bposition'];
					$weight = 0;
					$chami = $db->sql_query( "select * from " . $prefix . "_blocks WHERE bposition = '$subid' AND bid != '$id'AND blanguage = '$blanguage2' ORDER BY weight" );
 					while ($row2 = $db->sql_fetchrow($chami)) {
						$weight++;
						if ( $weight == $newk ){	$weight++; }
						$db->sql_query( "UPDATE " . $prefix . "_blocks SET weight=" . $weight . " WHERE bid=" . $row2['bid'] );
						}
					$db->sql_query( "UPDATE " . $prefix . "_blocks SET weight=" . $newk . " WHERE bid=" . $id );
				
	}
	}
		Header( "Location: " . $adminfile . ".php?op=BlocksAdmin&blanguage=$blanguage" );
	}
	


	/**
	 * rssfail()
	 * 
	 * @return
	 */
	function rssfail()
	{
		include ( "../header.php" );
		GraphicAdmin();
		OpenTable();
		echo "<center><font class=\"title\"><b>" . _BLOCKSADMIN . "</b></font></center>";
		CloseTable();
		echo "<br>";
		OpenTable();
		echo "<center><b>" . _RSSFAIL . "</b><br><br>" . "" . _RSSTRYAGAIN . "<br><br>" . "" . _GOBACK . "</center>";
		CloseTable();
		include ( "../footer.php" );
		die;
	}

	/**
	 * BlocksAdd()
	 * 
	 * @param mixed $title
	 * @param mixed $content
	 * @param mixed $url
	 * @param mixed $bposition
	 * @param mixed $active
	 * @param mixed $refresh
	 * @param mixed $headline
	 * @param mixed $blanguage
	 * @param mixed $blockfile
	 * @param mixed $view
	 * @param mixed $expire
	 * @param mixed $action
	 * @param mixed $link
	 * @param mixed $module
	 * @param mixed $bkey
	 * @return
	 */
	function BlocksAdd( $title, $content, $url, $bposition, $active, $refresh, $headline, $blanguage, $blockfile, $view, $expire, $action, $link, $module, $bkey )
	{
		global $prefix, $db, $datafold, $adminfile;
		$bkey = intval( $bkey );
		$title = stripslashes( FixQuotes($title) );
		if ( $bkey == 0 and $blockfile != "" )
		{
			$url = "";
			$headline = 0;
			$content = "";
			$refresh = 0;
			if ( $title == "" )
			{
				$title = ereg_replace( "block-", "", $blockfile );
				$title = ereg_replace( ".php", "", $title );
				$title = ereg_replace( "_", " ", $title );
			}
		} elseif ( $bkey == 1 and $content != "" )
		{
			$url = "";
			$headline = 0;
			$blockfile = "";
			$refresh = 0;
		} elseif ( $bkey == 2 and ($url != "" || $headline != 0) )
		{
			$content = "";
			$blockfile = "";
			if ( $headline != 0 )
			{
				$sql = "select sitename, headlinesurl from " . $prefix . "_headlines where hid='$headline'";
				$result = $db->sql_query( $sql );
				$row = $db->sql_fetchrow( $result );
				$title = $row[sitename];
				$url = $row[headlinesurl];
			}
			if ( $url != "" )
			{
				if ( ! ereg("http://", $url) )
				{
					$url = "http://$url";
				}
				$rdf = parse_url( $url );
				$fp = fsockopen( $rdf['host'], 80, $errno, $errstr, 15 );
				if ( ! $fp )
				{
					rssfail();
					exit;
				}
				if ( $fp )
				{
					fputs( $fp, "GET " . $rdf['path'] . "?" . $rdf['query'] . " HTTP/1.0\r\n" );
					fputs( $fp, "HOST: " . $rdf['host'] . "\r\n\r\n" );
					$string = "";
					while ( ! feof($fp) )
					{
						$pagetext = fgets( $fp, 228 );
						$string .= chop( $pagetext );
					}
					fputs( $fp, "Connection: close\r\n\r\n" );
					fclose( $fp );
					$items = explode( "</item>", $string );
					$content = "<font class=\"content\">";
					for ( $i = 0; $i < 10; $i++ )
					{
						$link = ereg_replace( ".*<link>", "", $items[$i] );
						$link = ereg_replace( "</link>.*", "", $link );
						$title2 = ereg_replace( ".*<title>", "", $items[$i] );
						$title2 = ereg_replace( "</title>.*", "", $title2 );
						if ( $items[$i] == "" and $cont != 1 )
						{
							$content = "";
						}
						else
						{
							if ( strcmp($link, $title2) and $items[$i] != "" )
							{
								$cont = 1;
								$content .= "<img border=\"0\" src=\"images/arrow2.gif\" width=\"10\" height=\"5\">&nbsp;<a href=\"$link\" target=\"new\">$title2</a><br>\n";
							}
						}
					}
				}
			}
		}
		else
		{
			Header( "Location: " . $adminfile . ".php?op=BlocksAdmin&blanguage=$blanguage" );
			exit();
		}

		$title = stripslashes( FixQuotes($title) );
		$content = FixQuotes( $content );
		if ( $title == "" )
		{
			Header( "", "" );
			exit();
		}
		$btime = time();
		if ( $content != "" )
		{
			@chmod( "" . INCLUDE_PATH . "" . $datafold . "/block-" . $btime . ".php", 0777 );
			@$file = fopen( "" . INCLUDE_PATH . "" . $datafold . "/block-" . $btime . ".php", "w" );
			$content2 = "<?php\n\n";
			$fctime = date( "d-m-Y H:i:s", filectime("" . INCLUDE_PATH . "" . $datafold . "/block-" . $btime . ".php") );
			$fmtime = date( "d-m-Y H:i:s" );
			$content2 .= "// File: block-" . $btime . ".php.\n// Created: $fctime.\n// Modified: $fmtime.\n// Do not change anything in this file!\n\n";
			$content2 .= "if ((!defined('NV_SYSTEM')) AND (!defined('NV_ADMIN'))) {\n";
			$content2 .= "die('Stop!!!');\n";
			$content2 .= "}\n";
			$content2 .= "\n";
			$content2 .= "\$content = \"" . htmlspecialchars( stripslashes($content) ) . "\";\n";
			$content2 .= "\n";
			$content2 .= "?>";
			@$writefile = fwrite( $file, $content2 );
			@fclose( $file );
			@chmod( "" . INCLUDE_PATH . "" . $datafold . "/block-" . $btime . ".php", 0604 );
			$blockfile = "block-" . $btime . ".php";
		}

		$sql = "SELECT weight FROM " . $prefix . "_blocks WHERE bposition='$bposition' ORDER BY weight DESC";
		$result = $db->sql_query( $sql );
		$row = $db->sql_fetchrow( $result );
		$weight = $row[weight];
		$weight++;

		$link2 = "";
		if ( $link != "" )
		{
			if ( ! ereg("http://", $link) )
			{
				$link2 = "http://$link";
			}
			else
			{
				$link2 = $link;
			}
		}

		if ( empty($module) ) $module = "all";
		elseif ( in_array("all", $module) ) $module = "all";
		else  $module = implode( "|", $module );

		$expire = intval( $expire );
		if ( $expire != 0 ) $expire = time() + ( $expire * 86400 );

		$db->sql_query( "INSERT INTO " . $prefix . "_blocks (bid, bkey, title, url, bposition, weight, active, refresh, time, blanguage, blockfile, view, expire, action, link, module) VALUES (NULL, '$bkey', '$title', '$url', '$bposition', '$weight', '$active', '$refresh', '$btime', '$blanguage', '$blockfile', '$view', '$expire', '$action', '$link2', '$module')" );
		blist();
		Header( "Location: " . $adminfile . ".php?op=BlocksAdmin&blanguage=$blanguage" );
	}

	/**
	 * BlocksEdit()
	 * 
	 * @param mixed $bid
	 * @return
	 */
	function BlocksEdit( $bid )
	{
		global $editor, $prefix, $db, $multilingual, $datafold, $listmods, $adminfile;
		include ( "../header.php" );
//		GraphicAdmin();
		OpenTable();
		echo "<center><font class=\"title\"><b><a href=\"" . $adminfile . ".php?op=BlocksAdmin\">" . _BLOCKSADMIN . "</a><br>" . _EDITBLOCK . "</b></font></center>";
		CloseTable();
		echo "<br>";
		$bid = intval( $bid );
		$sql = "SELECT * FROM `" . $prefix . "_blocks` WHERE `bid`='" . $bid . "'";
		$result = $db->sql_query( $sql );
		$row = $db->sql_fetchrow( $result );
		$bkey = $row[bkey];
		$title = $row[title];
		$url = $row[url];
		$bposition = $row[bposition];
		$weight = $row[weight];
		$active = $row[active];
		$refresh = $row[refresh];
		$blanguage = $row[blanguage];
		$blockfile = $row[blockfile];
		$view = $row[view];
		$expire = $row[expire];
		$action = $row[action];
		$link = $row[link];
		$module = $row[module];

		OpenTable();
		echo "<center><font class=\"option\"><b>" . _BLOCK . ": $title</b></font></center><br><br>" . "<form action=\"" . $adminfile . ".php\" method=\"post\">" . "<table border=\"0\" width=\"100%\">" . "<tr><td>" . _TITLE . ":</td><td><input type=\"text\" name=\"title\" size=\"30\" maxlength=\"60\" value=\"$title\"></td></tr>";

		if ( $bkey != 2 )
		{
			echo "<tr><td>" . _BLOCKLINK . ":</td><td>&nbsp;<input type='text' name='link' size='50' value=\"$link\">&nbsp;&nbsp;<font class=\"tiny\">" . _LINKINCLUDE . "</font></td></tr>";
		}
		else
		{
			echo "<input type=\"hidden\" name=\"link\" value=\"$link\">";
		}

		if ( $bkey == 0 )
		{
			echo "<tr><td>" . _FILENAME . ":</td><td>" . "<select name=\"blockfile\">";
			$blockslist = array();
			$blocksdir = dir( "../blocks" );
			while ( $func = $blocksdir->read() )
			{
				if ( substr($func, 0, 6) == "block-" )
				{
					$blockslist[] = $func;
				}
			}
			closedir( $blocksdir->handle );
			sort( $blockslist );

			$blockslist2 = $blockslist;
			$sql = "SELECT * FROM `" . $prefix . "_blocks` WHERE `blockfile`!='" . $blockfile . "' AND `blanguage`='" . $blanguage . "'";
			$result = $db->sql_query( $sql );
			while ( $row = $db->sql_fetchrow($result) )
			{
				if ( $row['blockfile'] != "" and in_array($row['blockfile'], $blockslist2) ) $blockslist = array_diff( $blockslist, array($row['blockfile']) );
			}
			foreach ( $blockslist as $block )
			{
				$bl = ereg_replace( "block-", "", $block );
				$bl = ereg_replace( ".php", "", $bl );
				$bl = ereg_replace( "_", " ", $bl );
				echo "<option value=\"" . $block . "\" ";
				if ( $blockfile == $block ) echo "selected";
				echo ">$bl</option>\n";
			}
			echo "</select>&nbsp;&nbsp;<font class=\"tiny\">" . _FILEINCLUDE . "</font></td></tr>";
		}
		else
		{
			echo "<input type=\"hidden\" name=\"blockfile\" value=\"" . $blockfile . "\">";
		}
		if ( $bkey == 1 )
		{
			include_once ( "../" . $datafold . "/" . $blockfile . "" );
			echo "<tr><td>" . _CONTENT . ":</td><td>";
			if ( $editor == 1 )
			{
				aleditor( "content", html_entity_decode($content), 350, 250 );
			}
			else
			{
				echo "<textarea name=\"content\" cols=\"50\" rows=\"10\">" . html_entity_decode( $content ) . "</textarea>";
			}
			echo "</td></tr>";
		} elseif ( $bkey == 2 )
		{
			include_once ( "../" . $datafold . "/" . $blockfile . "" );
			echo "<input type=\"hidden\" name=\"content\" value=\"" . $content . "\">";
		}
		else
		{

			echo "<input type=\"hidden\" name=\"content\" value=\"" . $content . "\">";
		}
		if ( $bkey == 2 )
		{
			echo "<tr><td>" . _RSSFILE . ":</td><td><input type=\"text\" name=\"url\" size=\"30\" maxlength=\"200\" value=\"$url\">&nbsp;&nbsp;<font class=\"tiny\">" . _ONLYHEADLINES . "</font></td></tr>";
		}
		else
		{
			echo "<input type=\"hidden\" name=\"url\" value=\"$url\">";
		}
		$oldposition = $bposition;
		echo "<input type=\"hidden\" name=\"oldposition\" value=\"$oldposition\">";
		$pos_array = array( "l", "c", "r", "d" );
		$posname_array = array( _LEFT, _CENTERUP, _RIGHT, _CENTERDOWN );
		echo "<tr><td>" . _POSITION . ":</td><td><select name=\"bposition\">";
		for ( $pc = 0; $pc < sizeof($pos_array); $pc++ )
		{
			echo "<option name = \"bposition\" value=\"$pos_array[$pc]\"";
			if ( $bposition == "$pos_array[$pc]" )
			{
				echo " selected";
			}
			echo ">$posname_array[$pc]</option>\n";
		}
		echo "</select></td></tr>";
		echo "<input type=\"hidden\" name=\"blanguage\" value=\"$blanguage\">";

		if ( $active == 1 )
		{
			echo "<tr><td>" . _ACTIVATE2 . "</td><td><input type=\"radio\" name=\"active\" value=\"1\" checked>" . _YES . " &nbsp;&nbsp;" . "<input type=\"radio\" name=\"active\" value=\"0\">" . _NO . "</td></tr>";
		} elseif ( $active == 0 )
		{
			echo "<tr><td>" . _ACTIVATE2 . "</td><td><input type=\"radio\" name=\"active\" value=\"1\">" . _YES . " &nbsp;&nbsp;" . "<input type=\"radio\" name=\"active\" value=\"0\" checked>" . _NO . "</td></tr>";
		}
		echo "<tr><td>" . _EXPIRATION . ":</td><td>";
		if ( $expire != 0 )
		{
			$oldexpire = $expire;
			$expire = intval( ($expire - time()) / 3600 );
			$exp_day = $expire / 24;
			echo "<input type=\"hidden\" name=\"expire\" value=\"$oldexpire\"><b>$expire " . _HOURS . " (" . substr( $exp_day, 0, 5 ) . " " . _DAYS . ")</b>";
		}
		else
		{
			echo "<input type=\"text\" name=\"expire\" value=\"0\" size=\"4\" maxlength=\"3\"> " . _DAYS . "";
		}
		echo "</td></tr>";
		echo "<tr><td>" . _AFTEREXPIRATION . ":</td><td><select name=\"action\">";
		if ( $action == "d" )
		{
			echo "<option name=\"action\" value=\"d\" selected>" . _DEACTIVATE . "</option>" . "<option name=\"action\" value=\"r\">" . _DELETE . "</option>";
		} elseif ( $action == "r" )
		{
			echo "<option name=\"action\" value=\"d\">" . _DEACTIVATE . "</option>" . "<option name=\"action\" value=\"r\"  selected>" . _DELETE . "</option>";
		}
		echo "</select></td></tr>";
		if ( $url != "" )
		{
			$refr_array = array( 1800, 3600, 18000, 36000, 86400 );
			$refrnm_array = array( "1/2", "1", "5", "10", "24" );
			echo "<tr><td>" . _REFRESHTIME . ":</td><td><select name=\"refresh\">";
			for ( $ri = 0; $ri < sizeof($refr_array); $ri++ )
			{
				echo "<option name=\"refresh\" value=\"$refr_array[$ri]\"";
				if ( $refresh == $refr_array[$ri] )
				{
					echo " selected";
				}
				echo ">" . $refrnm_array[$ri] . " " . _HOUR . "</option>";
			}
			echo "</select>&nbsp;<font class=\"tiny\">" . _ONLYHEADLINES . "</font>";
		}
		else
		{
			echo "<input type=\"hidden\" name=\"refresh\" value=\"$refresh\">";
		}
		$view_array = array( _MVALL, _MVUSERS, _MVADMIN, _MVANON );
		echo "</td></tr><tr><td>" . _VIEWPRIV . "</td><td><select name=\"view\">";
		for ( $vi = 0; $vi < sizeof($view_array); $vi++ )
		{
			echo "<option value=\"$vi\"";
			if ( $view == $vi )
			{
				echo " selected";
			}
			echo ">$view_array[$vi]</option>";
		}
		echo "</select></td></tr>" . "<tr valign=\"top\">\n<td><b>" . _DISPLAYAREA . ": </b></td>\n<td>";

		$module = ( ! empty($module) ) ? explode( "|", $module ) : array( "all" );
		if ( in_array("all", $module) ) $module = array( "all" );
		$mod_array = array( 'all' => _ALL, 'home' => _HOME, 'acp' => _ACP );
		echo "<table border=\"1\" style=\"border-collapse: collapse\" cellpadding=\"2\" cellspacing=\"2\"><tr>\n";
		foreach ( $mod_array as $k => $v )
		{
			echo "<td><input name=\"module[]\" type=\"checkbox\" value=\"" . $k . "\"" . ( (in_array($k, $module)) ? " checked=\"checked\"" : "" ) . "> " . $v . "</td>\n";
		}
		echo "</tr></table>\n";
		echo "<table border=\"1\" style=\"border-collapse: collapse\" cellpadding=\"2\" cellspacing=\"2\"><tr>\n";
		$a = 1;
		foreach ( $listmods as $mod )
		{
			echo "<td><input name=\"module[]\" type=\"checkbox\" value=\"" . $mod . "\"" . ( (in_array($mod, $module)) ? " checked=\"checked\"" : "" ) . "> " . $mod . "</td>\n";
			if ( ($a % 5 == 0) and ($a != sizeof($listmods)) ) echo "</tr><tr>";
			$a++;
		}
		echo "</table>" . "</td>\n</tr>\n" . "</table><br><br>" . "<input type=\"hidden\" name=\"bid\" value=\"$bid\">" . "<input type=\"hidden\" name=\"bkey\" value=\"$bkey\">" . "<input type=\"hidden\" name=\"weight\" value=\"$weight\">" . "<input type=\"hidden\" name=\"op\" value=\"BlocksEditSave\">" . "<input type=\"submit\" value=\"" . _SAVEBLOCK . "\"></form>";
		CloseTable();
		include ( "../footer.php" );
	}

	/**
	 * BlocksEditSave()
	 * 
	 * @param mixed $bid
	 * @param mixed $bkey
	 * @param mixed $title
	 * @param mixed $content
	 * @param mixed $url
	 * @param mixed $oldposition
	 * @param mixed $bposition
	 * @param mixed $active
	 * @param mixed $refresh
	 * @param mixed $weight
	 * @param mixed $blanguage
	 * @param mixed $blockfile
	 * @param mixed $view
	 * @param mixed $expire
	 * @param mixed $action
	 * @param mixed $link
	 * @param mixed $module
	 * @return
	 */
	function BlocksEditSave( $bid, $bkey, $title, $content, $url, $oldposition, $bposition, $active, $refresh, $weight, $blanguage, $blockfile, $view, $expire, $action, $link, $module )
	{
		global $prefix, $db, $datafold, $adminfile;
		$bid = intval( $bid );
		$bkey = intval( $bkey );
		$title = stripslashes( FixQuotes($title) );
		if ( empty($title) )
		{
			Header( "Location: " . $adminfile . ".php?op=BlocksEdit&bid=" . $bid );
			exit();
		}
		if ( $bkey == 0 and $blockfile != "" )
		{
			$url = "";
			$content = "";
			$refresh = 0;
		} elseif ( $bkey == 1 and $content != "" )
		{
			$url = "";
		} elseif ( $bkey == 2 and $url != "" )
		{
			if ( ! ereg("http://", $url) )
			{
				$url = "http://$url";
			}
			$rdf = parse_url( $url );
			$fp = fsockopen( $rdf['host'], 80, $errno, $errstr, 15 );
			if ( ! $fp )
			{
				rssfail();
				exit;
			}
			if ( $fp )
			{
				fputs( $fp, "GET " . $rdf['path'] . "?" . $rdf['query'] . " HTTP/1.0\r\n" );
				fputs( $fp, "HOST: " . $rdf['host'] . "\r\n\r\n" );
				$string = "";
				while ( ! feof($fp) )
				{
					$pagetext = fgets( $fp, 228 );
					$string .= chop( $pagetext );
				}
				fputs( $fp, "Connection: close\r\n\r\n" );
				fclose( $fp );
				$items = explode( "</item>", $string );
				$content = "<font class=\"content\">";
				for ( $i = 0; $i < 10; $i++ )
				{
					$link = ereg_replace( ".*<link>", "", $items[$i] );
					$link = ereg_replace( "</link>.*", "", $link );
					$title2 = ereg_replace( ".*<title>", "", $items[$i] );
					$title2 = ereg_replace( "</title>.*", "", $title2 );
					if ( $items[$i] == "" and $cont != 1 )
					{
						$content = "";
					}
					else
					{
						if ( strcmp($link, $title2) and $items[$i] != "" )
						{
							$cont = 1;
							$content .= "<img border=\"0\" src=\"images/arrow2.gif\" width=\"10\" height=\"5\">&nbsp;<a href=\"$link\" target=\"new\">$title2</a><br>\n";
						}
					}
				}
			}
		}
		else
		{
			exit();
		}
		$content = FixQuotes( $content );
		$btime = time();
		if ( $content != "" )
		{
			@chmod( "" . INCLUDE_PATH . "" . $datafold . "/" . $blockfile . "", 0777 );
			@$file = fopen( "" . INCLUDE_PATH . "" . $datafold . "/" . $blockfile . "", "w" );
			$content2 = "<?php\n\n";
			$fctime = date( "d-m-Y H:i:s", filectime("" . INCLUDE_PATH . "" . $datafold . "/" . $blockfile . "") );
			$fmtime = date( "d-m-Y H:i:s" );
			$content2 .= "// File: " . $blockfile . ".\n// Created: $fctime.\n// Modified: $fmtime.\n// Do not change anything in this file!\n\n";
			$content2 .= "if ((!defined('NV_SYSTEM')) AND (!defined('NV_ADMIN'))) {\n";
			$content2 .= "die('Stop!!!');\n";
			$content2 .= "}\n";
			$content2 .= "\n";
			$content2 .= "\$content = \"" . htmlspecialchars( stripslashes($content) ) . "\";\n";
			$content2 .= "\n";
			$content2 .= "?>";
			@$writefile = fwrite( $file, $content2 );
			@fclose( $file );
			@chmod( "" . INCLUDE_PATH . "" . $datafold . "/" . $blockfile . "", 0604 );
		}

		$link2 = "";
		if ( $link != "" )
		{
			if ( ! ereg("http://", $link) )
			{
				$link2 = "http://$link";
			}
			else
			{
				$link2 = $link;
			}
		}

		if ( empty($module) ) $module = "all";
		elseif ( in_array("all", $module) ) $module = "all";
		else  $module = implode( "|", $module );

		$expire = intval( $expire );
		if ( $expire != 0 ) $expire = time() + ( $expire * 86400 );

		if ( $oldposition != $bposition )
		{
			$sql = "SELECT `weight` FROM `" . $prefix . "_blocks` WHERE `bposition`='" . $bposition . "' ORDER BY `weight` DESC";
			$result = $db->sql_query( $sql );
			$row = $db->sql_fetchrow( $result );
			$weight = $row[weight];
			$weight++;
		}

		$db->sql_query( "UPDATE `" . $prefix . "_blocks` SET `bkey`='$bkey', `title`='$title', `url`='$url', `bposition`='$bposition', `weight`='$weight', `active`='$active', `refresh`='$refresh', `blanguage`='$blanguage', `blockfile`='$blockfile', `view`='$view', `expire`='$expire', `action`='$action', `link`='$link2', `module`='$module' WHERE `bid`='$bid'" );
		fixweight();
		blist();
		Header( "Location: " . $adminfile . ".php?op=BlocksAdmin&blanguage=$blanguage" );
	}

	/**
	 * ChangeStatus()
	 * 
	 * @param mixed $bid
	 * @param mixed $blanguage
	 * @param integer $ok
	 * @return
	 */
	function ChangeStatus( $bid, $blanguage, $ok = 0 )
	{
		global $prefix, $db, $adminfile;
		$bid = intval( $bid );
		$sql = "select active, blanguage from " . $prefix . "_blocks where bid='$bid'";
		$result = $db->sql_query( $sql );
		$row = $db->sql_fetchrow( $result );
		$active = $row[active];
		$blanguage = $row['blanguage'];
		if ( ($ok) or ($active == 1) )
		{
			if ( $active == 0 )
			{
				$active = 1;
			} elseif ( $active == 1 )
			{
				$active = 0;
			}
			$db->sql_query( "UPDATE " . $prefix . "_blocks set active='$active' where bid='$bid'" );
			blist();
			Header( "Location: " . $adminfile . ".php?op=BlocksAdmin&blanguage=$blanguage" );
		}
		else
		{
			$sql = "select title, content from " . $prefix . "_blocks where bid='$bid'";
			$result = $db->sql_query( $sql );
			$row = $db->sql_fetchrow( $result );
			$title = $row[title];
			$content = $row[content];
			include ( "../header.php" );
//			GraphicAdmin();
			echo "<br>";
			OpenTable();
			echo "<center><font class=\"option\"><b>" . _BLOCKACTIVATION . "</b></font></center>";
			CloseTable();
			echo "<br>";
			OpenTable();
			if ( $content != "" )
			{
				echo "<center>" . _BLOCKPREVIEW . " <i>$title</i><br><br>";
				themesidebox( $title, $content );
			}
			else
			{
				echo "<center><i>$title</i><br><br>";
			}
			echo "<br>" . _WANT2ACTIVATE . "<br><br>" . "[ <a href=\"" . $adminfile . ".php?op=BlocksAdmin&blanguage=$blanguage\">" . _NO . "</a> | <a href=\"" . $adminfile . ".php?op=ChangeStatus&amp;bid=$bid&amp;ok=1\">" . _YES . "</a> ]" . "</center>";
			CloseTable();
			include ( "../footer.php" );
		}
	}

	/**
	 * BlocksDelete()
	 * 
	 * @param mixed $bid
	 * @param integer $ok
	 * @return
	 */
	function BlocksDelete( $bid, $ok = 0 )
	{
		global $prefix, $db, $adminfile, $datafold;
		$bid = intval( $bid );
		if ( $ok )
		{
			$sql = "select blockfile, blanguage from " . $prefix . "_blocks where bid='$bid'";
			$result = $db->sql_query( $sql );
			$row = $db->sql_fetchrow( $result );
			$blockfile = $row[blockfile];
			$blanguage = $row['blanguage'];
			$db->sql_query( "delete from " . $prefix . "_blocks where bid='$bid'" );
			$db->sql_query( "OPTIMIZE TABLE " . $prefix . "_blocks" );
			@unlink( "" . INCLUDE_PATH . "" . $datafold . "/" . $blockfile . "" );
			fixweight();
			blist();
			Header( "Location: " . $adminfile . ".php?op=BlocksAdmin&blanguage=$blanguage" );
		}
		else
		{
			$sql = "select title, blanguage from " . $prefix . "_blocks where bid='$bid'";
			$result = $db->sql_query( $sql );
			$row = $db->sql_fetchrow( $result );
			$title = $row[title];
			$blanguage = $row['blanguage'];
			include ( "../header.php" );
//			GraphicAdmin();
			OpenTable();
			echo "<center><font class=\"title\"><b>" . _BLOCKSADMIN . "</b></font></center>";
			CloseTable();
			echo "<br>";
			OpenTable();
			echo "<center>" . _ARESUREDELBLOCK . " <i>$title</i>?";
			echo "<br><br>[ <a href=\"" . $adminfile . ".php?op=BlocksAdmin&blanguage=$blanguage\">" . _NO . "</a> | <a href=\"" . $adminfile . ".php?op=BlocksDelete&amp;bid=$bid&amp;ok=1\">" . _YES . "</a> ]</center>";
			CloseTable();
			include ( "../footer.php" );
		}
	}

	/**
	 * HeadlinesAdmin()
	 * 
	 * @return
	 */
	function HeadlinesAdmin()
	{
		global $bgcolor1, $bgcolor2, $prefix, $db, $adminfile;
		include ( "../header.php" );
//		GraphicAdmin();
		OpenTable();
		echo "<center><font class=\"title\"><b><a href=" . $adminfile . ".php?op=BlocksAdmin>"._BLOCKSADMIN."</a> | <a href=" . $adminfile . ".php?op=HeadlinesAdmin>RSS Reader Block Admin</a></b><br>" . _HEADLINESADMIN . "</font></center>";
		CloseTable();
		echo "<br>";
		OpenTable();
		echo "<form action=\"" . $adminfile . ".php\" method=\"post\">" . "<table border=\"1\" width=\"100%\" align=\"center\"><tr>" . "<td align=\"center\"><b>" . _SITENAME . "</b></td>" . "<td align=\"center\"><b>" . _URL . "</b></td>" . "<td align=\"center\"><b>" . _FUNCTIONS . "</b></td><tr>";

		$sql = "select hid, sitename, headlinesurl from " . $prefix . "_headlines order by hid";
		$result = $db->sql_query( $sql );
		while ( $row = $db->sql_fetchrow($result) )
		{
			$hid = $row[hid];
			$sitename = $row[sitename];
			$headlinesurl = $row[headlinesurl];
			echo "<td bgcolor=\"$bgcolor1\" align=\"center\">$sitename</td>" . "<td bgcolor=\"$bgcolor1\" align=\"center\"><a href=\"$headlinesurl\" target=\"new\">$headlinesurl</a></td>" . "<td bgcolor=\"$bgcolor1\" align=\"center\">[ <a href=\"" . $adminfile . ".php?op=HeadlinesEdit&amp;hid=$hid\">" . _EDIT . "</a> | <a href=\"" . $adminfile . ".php?op=HeadlinesDel&amp;hid=$hid&amp;ok=0\">" . _DELETE . "</a> ]</td><tr>";
		}
		echo "</form></td></tr></table>";
		CloseTable();
		echo "<br>";
		OpenTable();
		echo "<font class=\"option\"><b>" . _ADDHEADLINE . "</b></font><br><br>" . "<font class=\"content\">" . "<form action=\"" . $adminfile . ".php\" method=\"post\">" . "<table border=\"0\" width=\"100%\"><tr><td>" . "" . _SITENAME . ":</td><td><input type=\"text\" name=\"xsitename\" size=\"31\" maxlength=\"30\"></td></tr><tr><td>" . "" . _RSSFILE . ":</td><td><input type=\"text\" name=\"headlinesurl\" size=\"50\" maxlength=\"200\"></td></tr><tr><td>" . "</td></tr></table>" . "<input type=\"hidden\" name=\"op\" value=\"HeadlinesAdd\">" . "<input type=\"submit\" value=\"" . _ADD . "\">" . "</form>";
		CloseTable();
		include ( "../footer.php" );
	}

	/**
	 * HeadlinesEdit()
	 * 
	 * @param mixed $hid
	 * @return
	 */
	function HeadlinesEdit( $hid )
	{
		global $prefix, $db, $adminfile;
		include ( "../header.php" );
//		GraphicAdmin();
		OpenTable();
		echo "<center><font class=\"title\"><b><a href=" . $adminfile . ".php?op=HeadlinesAdmin>RSS Reader Block Admin</a></b><br>" . _HEADLINESADMIN . "</font></center>";
		CloseTable();
		echo "<br>";
		$sql = "select sitename, headlinesurl from " . $prefix . "_headlines where hid='$hid'";
		$result = $db->sql_query( $sql );
		$row = $db->sql_fetchrow( $result );
		$xsitename = $row[sitename];
		$headlinesurl = $row[headlinesurl];
		OpenTable();
		echo "<center><font class=\"option\"><b>" . _EDITHEADLINE . "</b></font></center>
	<form action=\"" . $adminfile . ".php\" method=\"post\">
	<input type=\"hidden\" name=\"hid\" value=\"$hid\">
	<table border=\"0\" width=\"100%\"><tr><td>
	" . _SITENAME . ":</td><td><input type=\"text\" name=\"xsitename\" size=\"31\" maxlength=\"30\" value=\"$xsitename\"></td></tr><tr><td>
	" . _RSSFILE . ":</td><td><input type=\"text\" name=\"headlinesurl\" size=\"50\" maxlength=\"200\" value=\"$headlinesurl\"></td></tr><tr><td>
	</select></td></tr></table>
	<input type=\"hidden\" name=\"op\" value=\"HeadlinesSave\">
	<input type=\"submit\" value=\"" . _SAVECHANGES . "\">
	</form>";
		CloseTable();
		include ( "../footer.php" );
	}

	/**
	 * HeadlinesSave()
	 * 
	 * @param mixed $hid
	 * @param mixed $xsitename
	 * @param mixed $headlinesurl
	 * @return
	 */
	function HeadlinesSave( $hid, $xsitename, $headlinesurl )
	{
		global $prefix, $db, $adminfile;
		$xsitename = ereg_replace( " ", "", $xsitename );
		$db->sql_query( "UPDATE " . $prefix . "_headlines set sitename='$xsitename', headlinesurl='$headlinesurl' where hid='$hid'" );
		Header( "Location: " . $adminfile . ".php?op=HeadlinesAdmin" );
	}

	/**
	 * HeadlinesAdd()
	 * 
	 * @param mixed $xsitename
	 * @param mixed $headlinesurl
	 * @return
	 */
	function HeadlinesAdd( $xsitename, $headlinesurl )
	{
		global $prefix, $db, $adminfile;
		$xsitename = ereg_replace( " ", "", $xsitename );
		$db->sql_query( "insert into " . $prefix . "_headlines values (NULL, '$xsitename', '$headlinesurl')" );
		Header( "Location: " . $adminfile . ".php?op=HeadlinesAdmin" );
	}

	/**
	 * HeadlinesDel()
	 * 
	 * @param mixed $hid
	 * @param integer $ok
	 * @return
	 */
	function HeadlinesDel( $hid, $ok = 0 )
	{
		global $prefix, $db, $adminfile;
		if ( $ok == 1 )
		{
			$db->sql_query( "delete from " . $prefix . "_headlines where hid='$hid'" );
			$db->sql_query( "OPTIMIZE TABLE " . $prefix . "_headlines" );
			Header( "Location: " . $adminfile . ".php?op=HeadlinesAdmin" );
		}
		else
		{
			include ( "../header.php" );
			GraphicAdmin();
			OpenTable();
			echo "<center><br>";
			echo "<font class=\"option\">";
			echo "<b>" . _SURE2DELHEADLINE . "</b></font><br><br>";
		}
		echo "[ <a href=\"" . $adminfile . ".php?op=HeadlinesDel&amp;hid=$hid&amp;ok=1\">" . _YES . "</a> | <a href=\"" . $adminfile . ".php?op=HeadlinesAdmin\">" . _NO . "</a> ]<br><br>";
		CloseTable();
		include ( "../footer.php" );
	}

	switch ( $op )
	{

		case "BlocksAdmin":
			BlocksAdmin();
			break;

		case "BlocksAdd":
			BlocksAdd( $title, $content, $url, $bposition, $active, $refresh, $headline, $blanguage, $blockfile, $view, $expire, $action, $link, $module, $bkey );
			break;

		case "BlocksEdit":
			BlocksEdit( $bid );
			break;

		case "BlocksEditSave":
			BlocksEditSave( $bid, $bkey, $title, $content, $url, $oldposition, $bposition, $active, $refresh, $weight, $blanguage, $blockfile, $view, $expire, $action, $link, $module );
			break;

		case "ChangeStatus":
			ChangeStatus( $bid, $blanguage, $ok, $de );
			break;

		case "BlocksDelete":
			BlocksDelete( $bid, $ok );
			break;

		case "HeadlinesDel":
			HeadlinesDel( $hid, $ok );
			break;

		case "HeadlinesAdd":
			HeadlinesAdd( $xsitename, $headlinesurl );
			break;

		case "HeadlinesSave":
			HeadlinesSave( $hid, $xsitename, $headlinesurl );
			break;

		case "HeadlinesAdmin":
			HeadlinesAdmin();
			break;

		case "HeadlinesEdit":
			HeadlinesEdit( $hid );
			break;

		case "BlockOrder":
			BlockOrder();
			break;
			
		case "fixweight2":
			fixweight2( $blanguage );
			break;

		case "block_show":
			block_show( $bid );
			break;

	}

}
else
{
	include ( "../header.php" );
	GraphicAdmin();
	OpenTable();
	echo "<center><b>" . _ERROR . "</b><br><br>" . _NOTAUTHORIZED . "</center>";
	CloseTable();
	include ( "../footer.php" );
}

?> 

