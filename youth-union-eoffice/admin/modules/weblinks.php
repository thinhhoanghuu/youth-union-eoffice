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

$checkmodname = "Weblinks";
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


	/**
	 * Weblinks_Menu()
	 * 
	 * @return
	 */
	function Weblinks_Menu()
	{
		global $adminfile;
		OpenTable();
		echo "<div style=\"font-weight:bold\">\n";
		echo "<a href=\"" . $adminfile . ".php?op=Weblinks_LinkManager\">" . _WEBLINKSADMIN . "</a> | \n";
		echo "<a href=\"" . $adminfile . ".php?op=Weblinks_CatManager\">" . _CATEGORIESADMIN . "</a>";
		echo "</div>\n";
		CloseTable();
		echo "<br>\n";
	}

	/**
	 * Weblinks_ListCat()
	 * 
	 * @return
	 */
	function Weblinks_ListCat()
	{
		global $db, $prefix, $currentlang;
		$linkcats = array();
		$result = $db->sql_query( "SELECT * FROM " . $prefix . "_weblinks_cats WHERE language='" . $currentlang . "'" );
		$numrows = $db->sql_numrows( $result );
		if ( $numrows )
		{
			while ( $row = $db->sql_fetchrow($result) )
			{
				$linkcats[intval( $row['id'] )] = array( 'id' => intval($row['id']), 'title' => stripslashes($row['title']), 'description' => stripslashes($row['description']), 'ihome' => intval($row['ihome']) );
			}
		}
		return $linkcats;
	}

	/**
	 * Weblinks_CatIhome()
	 * 
	 * @return
	 */
	function Weblinks_CatIhome()
	{
		global $db, $prefix, $currentlang, $adminfile;
		$id = intval( $_REQUEST['id'] );
		if ( ! $id )
		{
			Header( "Location: " . $adminfile . ".php?op=Weblinks_CatManager" );
			exit;
		}
		$result = $db->sql_query( "SELECT * FROM " . $prefix . "_weblinks_cats WHERE language='" . $currentlang . "' AND id='" . $id . "'" );
		$numrows = $db->sql_numrows( $result );
		if ( ! $numrows )
		{
			Header( "Location: " . $adminfile . ".php?op=Weblinks_CatManager" );
			exit;
		}
		$db->sql_query( "UPDATE " . $prefix . "_weblinks_cats SET ihome='0' WHERE language='" . $currentlang . "'" );
		$db->sql_query( "UPDATE " . $prefix . "_weblinks_cats SET ihome='1' WHERE id='" . $id . "' AND language='" . $currentlang . "'" );
		Header( "Location: " . $adminfile . ".php?op=Weblinks_CatManager" );
		exit;
	}

	/**
	 * Weblinks_CatManager()
	 * 
	 * @return
	 */
	function Weblinks_CatManager()
	{
		global $db, $prefix, $currentlang, $adminfile;
		$new_title = stripslashes( trim($_REQUEST['new_title']) );
		$new_description = stripslashes( trim($_REQUEST['new_description']) );
		$save = intval( $_REQUEST['save'] );
		$error = 0;
		if ( $save and $new_title != "" )
		{
			$result = $db->sql_query( "SELECT * FROM " . $prefix . "_weblinks_cats WHERE language='" . $currentlang . "' AND title='" . $new_title . "'" );
			$numrows = $db->sql_numrows( $result );
			if ( ! $numrows )
			{
				$result = $db->sql_query( "INSERT INTO " . $prefix . "_weblinks_cats VALUES (NULL, '" . $new_title . "', '" . $new_description . "', '" . $currentlang . "', 0)" );
				Header( "Location: " . $adminfile . ".php?op=Weblinks_CatManager" );
				exit;
			}
			else
			{
				$error = 2;
			}
		} elseif ( $save and $new_title == "" )
		{
			$error = 1;
		}
		include ( '../header.php' );
		GraphicAdmin();
		Weblinks_Menu();
		OpenTable();
		echo "<form method=\"post\" action=\"" . $adminfile . ".php\">\n";
		echo "<div>\n";
		echo "<strong style=\"padding: 3px; margin: 3px\">";
		if ( $error == 2 )
		{
			echo _CATEXISTS;
		} elseif ( $error == 1 )
		{
			echo _ERRORTHECATEGORY;
		}
		else
		{
			echo _CATEGORYADD;
		}
		echo "</strong><br>\n";
		echo "<table cellspacing=\"3\" cellpadding=\"3\">\n";
		echo "<tr>\n";
		echo "<td style=\"white-space: nowrap; text-align: left;\">" . _CATEGORYNAME . ":</td>\n";
		echo "<td>\n";
		echo "<input name=\"new_title\" type=\"text\" value=\"" . $new_title . "\" style=\"width: 400px\"></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td style=\"white-space: nowrap; text-align: left;\">" . _DESCRIPTION . ":</td>\n";
		echo "<td>\n";
		echo "<input name=\"new_description\" type=\"text\" value=\"" . $new_description . "\" style=\"width: 400px\"></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td style=\"white-space: nowrap; text-align: left;\">&nbsp;</td>\n";
		echo "<td>\n";
		echo "<input name=\"save\" type=\"hidden\" value=\"1\">\n";
		echo "<input name=\"op\" type=\"hidden\" value=\"Weblinks_CatManager\">\n";
		echo "<input name=\"Submit1\" type=\"submit\" value=\"" . _SAVE . "\">&nbsp;<input name=\"Reset1\" type=\"reset\" value=\"" . _RESET . "\"></td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		echo "</div>\n";
		echo "</form>\n";
		CloseTable();
		echo "<br>\n";
		$linkcats = Weblinks_ListCat();
		if ( $linkcats != array() )
		{
			OpenTable();
			echo "<div style=\"padding: 3px; margin: 3px\">\n";
			echo "<table style=\"background-color: #FFFFFF;border-spacing: 1px;\">\n";
			foreach ( $linkcats as $key => $value )
			{
				echo "<tr>\n";
				echo "<td style=\"border: 1px solid #999999; width: 10px;\">\n";
				if ( $value['ihome'] )
				{
					echo "<img alt=\"" . _YESHOME . "\" border=\"0\" src=\"../images/red_dot.gif\" width=\"10\" height=\"10\">";
				}
				else
				{
					echo "<a title=\"" . _ADDHOME . "\" href=\"" . $adminfile . ".php?op=Weblinks_CatIhome&amp;id=" . $key . "\"><img alt=\"" . _ADDHOME . "\" border=\"0\" src=\"../images/green_dot.gif\" width=\"10\" height=\"10\"></a>";
				}
				echo "</td>\n";
				echo "<td style=\"border: 1px solid #999999; width: 90%;white-space: nowrap;\">&nbsp;" . $value['title'] . "&nbsp;</td>\n";
				echo "<td style=\"border: 1px solid #999999; white-space: nowrap;text-align: center;\">\n";
				echo "&nbsp;<a href=\"" . $adminfile . ".php?op=Weblinks_CatEdit&amp;id=" . $value['id'] . "\">" . _EDIT . "</a>&nbsp;|&nbsp;\n";
				echo "<a href=\"" . $adminfile . ".php?op=Weblinks_CatDel&amp;id=" . $value['id'] . "\">" . _DELETE . "</a>&nbsp;</td>\n";
				echo "</tr>\n";
			}
			echo "</table>\n";
			echo "</div>\n";
			CloseTable();
			echo "<br>\n";
		}
		include ( '../footer.php' );
	}

	/**
	 * Weblinks_CatEdit()
	 * 
	 * @return
	 */
	function Weblinks_CatEdit()
	{
		global $db, $prefix, $adminfile, $currentlang;
		$id = intval( $_REQUEST['id'] );
		if ( ! $id )
		{
			Header( "Location: " . $adminfile . ".php?op=Weblinks_CatManager" );
			exit;
		}
		$result = $db->sql_query( "SELECT * FROM " . $prefix . "_weblinks_cats WHERE language='" . $currentlang . "' AND id='" . $id . "'" );
		$numrows = $db->sql_numrows( $result );
		if ( ! $numrows )
		{
			Header( "Location: " . $adminfile . ".php?op=Weblinks_CatManager" );
			exit;
		}
		$row = $db->sql_fetchrow( $result );
		$title = stripslashes( $row['title'] );
		$description = stripslashes( $row['description'] );
		$save = intval( $_REQUEST['save'] );
		$error = 0;
		if ( $save )
		{
			$new_title = stripslashes( trim($_REQUEST['new_title']) );
			$new_description = stripslashes( trim($_REQUEST['new_description']) );
			if ( $new_title != "" )
			{
				$result = $db->sql_query( "SELECT * FROM " . $prefix . "_weblinks_cats WHERE language='" . $currentlang . "' AND id!='" . $id . "' AND title='" . $new_title . "'" );
				$numrows = $db->sql_numrows( $result );
				if ( $numrows )
				{
					$error = 2;
				}
				else
				{
					$db->sql_query( "UPDATE " . $prefix . "_weblinks_cats SET title='" . $new_title . "', description='" . $new_description . "' WHERE id='" . $id . "'" );
					Header( "Location: " . $adminfile . ".php?op=Weblinks_CatManager" );
					exit;
				}
			}
			else
			{
				$error = 1;
			}
		}
		else
		{
			$new_title = $title;
			$new_description = str_replace( "<br />", "\r\n", $description );
		}
		include ( '../header.php' );
		GraphicAdmin();
		Weblinks_Menu();
		OpenTable();
		echo "<form method=\"post\" action=\"" . $adminfile . ".php\">\n";
		echo "<div>\n";
		echo "<strong style=\"padding: 3px; margin: 3px\">";
		if ( $error == 2 )
		{
			echo _CATEXISTS;
		} elseif ( $error == 1 )
		{
			echo _ERRORTHECATEGORY;
		}
		else
		{
			echo _EDITCATEGORY;
		}
		echo "</strong><br>\n";
		echo "<table cellspacing=\"3\" cellpadding=\"3\">\n";
		echo "<tr>\n";
		echo "<td style=\"white-space: nowrap; text-align: left;\">" . _CATEGORYNAME . ":</td>\n";
		echo "<td>\n";
		echo "<input name=\"new_title\" type=\"text\" value=\"" . $new_title . "\" style=\"width: 400px\"></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td style=\"white-space: nowrap; text-align: left;\">" . _DESCRIPTION . ":</td>\n";
		echo "<td>\n";
		echo "<input name=\"new_description\" type=\"text\" value=\"" . $new_description . "\" style=\"width: 400px\"></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td style=\"white-space: nowrap; text-align: left;\">&nbsp;</td>\n";
		echo "<td>\n";
		echo "<input name=\"id\" type=\"hidden\" value=\"" . $id . "\">\n";
		echo "<input name=\"save\" type=\"hidden\" value=\"1\">\n";
		echo "<input name=\"op\" type=\"hidden\" value=\"Weblinks_CatEdit\">\n";
		echo "<input name=\"Submit1\" type=\"submit\" value=\" " . _SAVE . " \">&nbsp;<input name=\"Reset1\" type=\"reset\" value=\"" . _RESET . "\"></td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		echo "</div>\n";
		echo "</form>\n";
		CloseTable();
		echo "<br>\n";
		include ( '../footer.php' );
	}

	/**
	 * Weblinks_CatDel()
	 * 
	 * @return
	 */
	function Weblinks_CatDel()
	{
		global $db, $prefix, $adminfile, $currentlang;
		$id = intval( $_REQUEST['id'] );
		if ( ! $id )
		{
			Header( "Location: " . $adminfile . ".php?op=Weblinks_CatManager" );
			exit;
		}
		$del = intval( $_REQUEST['del'] );
		if ( $del )
		{
			$result = $db->sql_query( "SELECT * FROM " . $prefix . "_weblinks_links WHERE cid!='" . $id . "'" );
			$numrows = $db->sql_numrows( $result );
			if ( $numrows )
			{
				include ( '../header.php' );
				GraphicAdmin();
				Weblinks_Menu();
				OpenTable();
				echo "<div style=\"text-align: center; font-weight: bold\">" . _DELLINKCATWARNING1 . "</div>\n";
				echo "<META HTTP-EQUIV=\"refresh\" content=\"5;URL=" . $adminfile . ".php?op=Weblinks_CatManager\">";
				CloseTable();
				echo "<br>\n";
				include ( '../footer.php' );
			}
			else
			{
				$db->sql_query( "DELETE FROM " . $prefix . "_weblinks_cats WHERE id='" . $id . "'" );
				Header( "Location: " . $adminfile . ".php?op=Weblinks_CatManager" );
				exit;
			}
		}
		else
		{
			include ( '../header.php' );
			GraphicAdmin();
			Weblinks_Menu();
			OpenTable();
			echo "<div style=\"text-align: center; font-weight: bold\">" . _DELLINKCATWARNING2 . "?</div>\n";
			echo "<div style=\"text-align: center; font-weight: bold\"><a href=\"" . $adminfile . ".php?op=Weblinks_CatDel&amp;id=" . $id . "&amp;del=1\">" . _DELETE . "</a> | <a href=\"" . $adminfile . ".php?op=Weblinks_CatManager\">" . _NO . "</a></div>\n";
			CloseTable();
			echo "<br>\n";
			include ( '../footer.php' );
		}
	}

	/**
	 * Weblinks_LinkManager()
	 * 
	 * @return
	 */
	function Weblinks_LinkManager()
	{
		global $db, $prefix, $currentlang, $adminfile;
		$linkcats = Weblinks_ListCat();
		if ( $linkcats == array() )
		{
			Header( "Location: " . $adminfile . ".php?op=Weblinks_CatManager" );
			exit;
		}
		$new_title = stripslashes( trim($_REQUEST['new_title']) );
		$new_url = stripslashes( trim($_REQUEST['new_url']) );
		$new_urlimg = stripslashes( trim($_REQUEST['new_urlimg']) );
		$new_description = stripslashes( trim($_REQUEST['new_description']) );
		$new_cat = intval( $_REQUEST['new_cat'] );
		$save = intval( $_REQUEST['save'] );
		$error = "";
		if ( ! eregi("http://", $new_url) and $new_url != "" )
		{
			$new_url = "http://" . $new_url . "";
		}
		if ( $save )
		{
			if ( $new_title == "" )
			{
				$error = _ERRORNOTITLE;
			} elseif ( $new_url == "" )
			{
				$error = _ERRORNOURL;
			}
			else
			{
				$result = $db->sql_query( "INSERT INTO " . $prefix . "_weblinks_links VALUES (NULL, " . $new_cat . ", '" . $new_title . "', '" . $new_url . "', '" . $new_urlimg . "', '" . $new_description . "', " . time() . ", 0, 1)" );
				Header( "Location: " . $adminfile . ".php?op=Weblinks_LinkManager" );
				exit;
			}
		}
		include ( '../header.php' );
		GraphicAdmin();
		Weblinks_Menu();
		OpenTable();
		echo "<form method=\"post\" action=\"" . $adminfile . ".php\">\n";
		echo "<div>\n";
		echo "<strong style=\"padding: 3px; margin: 3px\">";
		if ( $error != "" )
		{
			echo $error;
		}
		else
		{
			echo _ADDNEWLINK;
		}
		echo "</strong><br>\n";
		echo "<table cellspacing=\"3\" cellpadding=\"3\">\n";
		echo "<tr>\n";
		echo "<td style=\"white-space: nowrap; text-align: left;\">" . _LINKNAME . ":</td>\n";
		echo "<td>\n";
		echo "<input name=\"new_title\" type=\"text\" value=\"" . $new_title . "\" style=\"width: 400px\"></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td style=\"white-space: nowrap; text-align: left;\">" . _LINKURL . ":</td>\n";
		echo "<td>\n";
		echo "<input name=\"new_url\" type=\"text\" value=\"" . $new_url . "\" style=\"width: 400px\"></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td style=\"white-space: nowrap; text-align: left;\">" . _LINKURLIMG . ":</td>\n";
		echo "<td>\n";
		echo "<input name=\"new_urlimg\" type=\"text\" value=\"" . $new_urlimg . "\" style=\"width: 400px\"></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td style=\"white-space: nowrap; text-align: left;\">" . _DESCRIPTION . ":</td>\n";
		echo "<td>\n";
		echo "<input name=\"new_description\" type=\"text\" value=\"" . $new_description . "\" style=\"width: 400px\"></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td style=\"white-space: nowrap; text-align: left;\">" . _CATEGORY . ":</td>\n";
		echo "<td>\n";
		echo "<select name=\"new_cat\">\n";
		foreach ( $linkcats as $key => $value )
		{
			echo "<option value=\"" . $key . "\"";
			if ( $key == $new_cat )
			{
				echo " selected=\"selected\"";
			}
			echo ">" . $value['title'] . "</option>\n";
		}
		echo "</select></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td style=\"white-space: nowrap; text-align: left;\">&nbsp;</td>\n";
		echo "<td>\n";
		echo "<input name=\"save\" type=\"hidden\" value=\"1\">\n";
		echo "<input name=\"op\" type=\"hidden\" value=\"Weblinks_LinkManager\">\n";
		echo "<input name=\"Submit1\" type=\"submit\" value=\"" . _SAVE . "\">&nbsp;<input name=\"Reset1\" type=\"reset\" value=\"" . _RESET . "\"></td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		echo "</div>\n";
		echo "</form>\n";
		CloseTable();
		echo "<br>\n";
		$numf = $db->sql_fetchrow( $db->sql_query("SELECT COUNT(*) FROM " . $prefix . "_weblinks_links") );
		if ( $numf[0] )
		{
			$page = ( isset($_GET['page']) ) ? intval( $_GET['page'] ) : 0;
			$all_page = ( $numf[0] ) ? $numf[0] : 1;
			$per_page = 20;
			$base_url = "" . $adminfile . ".php?op=Weblinks_LinkManager";
			$sql = "SELECT * FROM " . $prefix . "_weblinks_links ORDER BY addtime DESC LIMIT $page,$per_page";
			$result = $db->sql_query( $sql );
			if ( $db->sql_numrows($result) != 0 )
			{
				OpenTable();
				echo "<div style=\"padding: 3px; margin: 3px\">\n";
				echo "<table style=\"width: 100%; background-color: #FFFFFF;border-spacing: 1px;\">\n";
				while ( $row = $db->sql_fetchrow($result) )
				{
					echo "<tr>\n";
					echo "<td style=\"border: 1px solid #999999; white-space: nowrap;\">";
					if ( $row['urlimg'] != "" )
					{
						echo "<a target=\"weblink\" href=\"" . INCLUDE_PATH . "modules.php?name=Weblinks&amp;lid=" . intval( $row['id'] ) . "\"><img alt=\"" . _LOIANH . "\" src=\"" . stripslashes( $row['urlimg'] ) . "\" border=\"0\"></a><br>\n";
					}
					else
					{
						echo "" . _LOIANH1 . "";
					}
					echo "</td>\n";
					echo "<td style=\"border: 1px solid #999999; white-space: nowrap;\">&nbsp;<a target=\"_blank\" href=\"" . INCLUDE_PATH . "modules.php?name=Weblinks&amp;lid=" . intval( $row['id'] ) . "\"><b>" . stripslashes( $row['title'] ) . "</b></a><br>";
					echo "<font class=\"grey\">&nbsp;" . stripslashes( $row['url'] ) . "<br>";
					echo "&nbsp;" . _CATEGORY . ": " . $linkcats[intval( $row['cid'] )]['title'] . " | " . _LINKCL . ": " . intval( $row['hits'] ) . "</font><br>";
					echo "&nbsp;<a href=\"" . $adminfile . ".php?op=Weblinks_LinkEdit&amp;id=" . intval( $row['id'] ) . "\">" . _EDIT . "</a>&nbsp;|&nbsp;\n";
					echo "<a href=\"" . $adminfile . ".php?op=Weblinks_LinkDel&amp;id=" . intval( $row['id'] ) . "\">" . _DELETE . "</a>&nbsp;|&nbsp;\n";
					echo "<a href=\"" . $adminfile . ".php?op=Weblinks_LinkAct&amp;id=" . intval( $row['id'] ) . "\">" . ( ($row['active'] == '1') ? _LINKDEACT : _LINKACT ) . "</a>&nbsp;\n";
					echo "</td>\n";
					echo "</tr>\n";
				}
				echo "</table>\n";
				echo "</div>\n";
				echo @generate_page( $base_url, $all_page, $per_page, $page );
				CloseTable();
			}
			echo "<br>\n";
		}
		include ( '../footer.php' );
	}

	/**
	 * Weblinks_LinkAct()
	 * 
	 * @return
	 */
	function Weblinks_LinkAct()
	{
		global $db, $prefix, $currentlang, $adminfile;
		$id = intval( $_REQUEST['id'] );
		if ( ! $id )
		{
			Header( "Location: " . $adminfile . ".php?op=Weblinks_LinkManager" );
			exit;
		}
		list( $act ) = $db->sql_fetchrow( $db->sql_query("SELECT active FROM " . $prefix . "_weblinks_links WHERE id='" . $id . "'") );
		if ( ! $act )
		{
			$db->sql_query( "UPDATE " . $prefix . "_weblinks_links SET active='1' WHERE id='" . $id . "'" );
		}
		else
		{
			$db->sql_query( "UPDATE " . $prefix . "_weblinks_links SET active='0' WHERE id='" . $id . "'" );
		}
		Header( "Location: " . $adminfile . ".php?op=Weblinks_LinkManager" );
		exit;
	}

	/**
	 * Weblinks_LinkEdit()
	 * 
	 * @return
	 */
	function Weblinks_LinkEdit()
	{
		global $db, $prefix, $adminfile, $currentlang;
		$id = intval( $_REQUEST['id'] );
		if ( ! $id )
		{
			Header( "Location: " . $adminfile . ".php?op=Weblinks_LinkManager" );
			exit;
		}
		$result = $db->sql_query( "SELECT * FROM " . $prefix . "_weblinks_links WHERE id='" . $id . "'" );
		$numrows = $db->sql_numrows( $result );
		if ( ! $numrows )
		{
			Header( "Location: " . $adminfile . ".php?op=Weblinks_LinkManager" );
			exit;
		}
		$row = $db->sql_fetchrow( $result );
		$title = stripslashes( $row['title'] );
		$cid = intval( $row['cid'] );
		$url = stripslashes( $row['url'] );
		$urlimg = stripslashes( $row['urlimg'] );
		$description = stripslashes( $row['description'] );
		$save = intval( $_REQUEST['save'] );
		$error = "";
		if ( $save )
		{
			$new_title = stripslashes( trim($_REQUEST['new_title']) );
			$new_cid = intval( $_REQUEST['new_cid'] );
			$new_url = stripslashes( trim($_REQUEST['new_url']) );
			$new_urlimg = stripslashes( trim($_REQUEST['new_urlimg']) );
			$new_description = stripslashes( trim($_REQUEST['new_description']) );
			if ( ! eregi("http://", $new_url) and $new_url != "" )
			{
				$new_url = "http://" . $new_url . "";
			}
			if ( $new_title == "" )
			{
				$error = _ERRORNOTITLE;
			} elseif ( $new_url == "" )
			{
				$error = _ERRORNOURL;
			}
			else
			{
				$db->sql_query( "UPDATE " . $prefix . "_weblinks_links SET cid=" . $new_cid . ", title='" . $new_title . "', url='" . $new_url . "', urlimg='" . $new_urlimg . "', description='" . $new_description . "' WHERE id='" . $id . "'" );
				Header( "Location: " . $adminfile . ".php?op=Weblinks_LinkManager" );
				exit;
			}
		}
		else
		{
			$new_title = $title;
			$new_cid = $cid;
			$new_url = $url;
			$new_urlimg = $urlimg;
			$new_description = str_replace( "<br />", "\r\n", $description );
		}
		$linkcats = Weblinks_ListCat();
		include ( '../header.php' );
		GraphicAdmin();
		Weblinks_Menu();
		OpenTable();
		echo "<form method=\"post\" action=\"" . $adminfile . ".php\">\n";
		echo "<div>\n";
		echo "<strong style=\"padding: 3px; margin: 3px\">";
		if ( $error != "" )
		{
			echo $error;
		}
		else
		{
			echo _EDITLINK;
		}
		echo "</strong><br>\n";
		if ( $urlimg != "" )
		{
			echo "<a target=\"weblink\" href=\"" . INCLUDE_PATH . "modules.php?name=Weblinks&amp;lid=" . $id . "\"><img src=\"$urlimg\" border=\"0\"></a><br>\n";
		}
		echo "<table cellspacing=\"3\" cellpadding=\"3\">\n";
		echo "<tr>\n";
		echo "<td style=\"white-space: nowrap; text-align: left;\">" . _LINKNAME . ":</td>\n";
		echo "<td>\n";
		echo "<input name=\"new_title\" type=\"text\" value=\"" . $new_title . "\" style=\"width: 400px\"></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td style=\"white-space: nowrap; text-align: left;\">" . _LINKURL . ":</td>\n";
		echo "<td>\n";
		echo "<input name=\"new_url\" type=\"text\" value=\"" . $new_url . "\" style=\"width: 400px\"></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td style=\"white-space: nowrap; text-align: left;\">" . _LINKURLIMG . ":</td>\n";
		echo "<td>\n";
		echo "<input name=\"new_urlimg\" type=\"text\" value=\"" . $new_urlimg . "\" style=\"width: 400px\"></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td style=\"white-space: nowrap; text-align: left;\">" . _DESCRIPTION . ":</td>\n";
		echo "<td>\n";
		echo "<input name=\"new_description\" type=\"text\" value=\"" . $new_description . "\" style=\"width: 400px\"></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td style=\"white-space: nowrap; text-align: left;\">" . _CATEGORY . ":</td>\n";
		echo "<td>\n";
		echo "<select name=\"new_cid\">\n";
		foreach ( $linkcats as $key => $value )
		{
			echo "<option value=\"" . $key . "\"";
			if ( $key == $new_cid )
			{
				echo " selected=\"selected\"";
			}
			echo ">" . $value['title'] . "</option>\n";
		}
		echo "</select></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td style=\"white-space: nowrap; text-align: left;\">&nbsp;</td>\n";
		echo "<td>\n";
		echo "<input name=\"save\" type=\"hidden\" value=\"1\">\n";
		echo "<input name=\"id\" type=\"hidden\" value=\"" . $id . "\">\n";
		echo "<input name=\"op\" type=\"hidden\" value=\"Weblinks_LinkEdit\">\n";
		echo "<input name=\"Submit1\" type=\"submit\" value=\"" . _SAVE . "\">&nbsp;<input name=\"Reset1\" type=\"reset\" value=\"" . _RESET . "\"></td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		echo "</div>\n";
		echo "</form>\n";
		CloseTable();
		echo "<br>\n";
		include ( '../footer.php' );
	}

	/**
	 * Weblinks_LinkDel()
	 * 
	 * @return
	 */
	function Weblinks_LinkDel()
	{
		global $db, $prefix, $currentlang, $adminfile;
		$id = intval( $_REQUEST['id'] );
		if ( ! $id )
		{
			Header( "Location: " . $adminfile . ".php?op=Weblinks_LinkManager" );
			exit;
		}
		$del = intval( $_REQUEST['del'] );
		if ( $del )
		{
			$db->sql_query( "DELETE FROM " . $prefix . "_weblinks_links WHERE id='" . $id . "'" );
			Header( "Location: " . $adminfile . ".php?op=Weblinks_LinkManager" );
			exit;
		}
		include ( '../header.php' );
		GraphicAdmin();
		Weblinks_Menu();
		OpenTable();
		echo "<div style=\"text-align: center; font-weight: bold\">" . _DELLINKWARNING . "?</div>\n";
		echo "<div style=\"text-align: center; font-weight: bold\"><a href=\"" . $adminfile . ".php?op=Weblinks_LinkDel&amp;id=" . $id . "&amp;del=1\">" . _DELETE . "</a> | <a href=\"" . $adminfile . ".php?op=Weblinks_LinkManager\">" . _NO . "</a></div>\n";
		CloseTable();
		echo "<br>\n";
		include ( '../footer.php' );
	}

	switch ( $op )
	{

		case "Weblinks_CatManager":
			Weblinks_CatManager();
			break;

		case "Weblinks_CatEdit":
			Weblinks_CatEdit();
			break;

		case "Weblinks_CatDel":
			Weblinks_CatDel();
			break;

		case "Weblinks_CatIhome":
			Weblinks_CatIhome();
			break;

		case "Weblinks_LinkManager":
			Weblinks_LinkManager();
			break;

		case "Weblinks_LinkAct":
			Weblinks_LinkAct();
			break;

		case "Weblinks_LinkDel":
			Weblinks_LinkDel();
			break;

		case "Weblinks_LinkEdit":
			Weblinks_LinkEdit();
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