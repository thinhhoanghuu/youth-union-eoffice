<?php

/*
* @Program:		NukeViet CMS
* @File name: 	NukeViet System
* @Version: 	2.0 RC2
* @Date: 		09.06.2009
* @Website: 	www.nukeviet.vn
* @Copyright: 	(C) 2009
* @License: 	http://opensource.org/licenses/gpl-license.php GNU Public License
*/

if ( ! defined('NV_ADMIN') ) die( "Access Denied" );

$aid = trim( $aid );
$sql = "select radminsuper from " . $prefix . "_authors where aid='$aid'";
$result = $db->sql_query( $sql );
$row = $db->sql_fetchrow( $result );
$radminsuper = $row[radminsuper];
if ( $radminsuper == 1 )
{

	/**
	 * modules()
	 * 
	 * @return
	 */
	function modules()
	{
		global $adminfile, $prefix, $db, $multilingual, $bgcolor2, $Home_Module;
		include ( "../header.php" );
		GraphicAdmin();
		OpenTable();
		echo "<center><font class=\"title\"><b>" . _MODULESADMIN . "</b></font></center>";
		CloseTable();
		OpenTable();
		echo "<br><center><font class=\"option\">" . _MODULESADDONS . "</font><br><br>" . "<font class=\"content\">" . _MODULESACTIVATION . "</font></center><br><br>" . "<form action=\"" . $adminfile . ".php\" method=\"post\">" . "<table border=\"1\" align=\"center\" width=\"90%\"><tr><td align=\"center\" bgcolor=\"$bgcolor2\">" . "<b>" . _TITLE . "</b></td><td align=\"center\" bgcolor=\"$bgcolor2\"><b>" . _CUSTOMTITLE . "</b><sup>(*)</sup></td><td align=\"center\" bgcolor=\"$bgcolor2\"><b>" . _STATUS . "</b></td><td align=\"center\" bgcolor=\"$bgcolor2\"><b>" . _VIEW . "</b></td><td align=\"center\" bgcolor=\"$bgcolor2\"><b>" . _BLOCK_TYPE . "</b><sup>(**)</sup></td><td align=\"center\" bgcolor=\"$bgcolor2\"><b>" . _MODSKIN . "</b><sup>(***)</sup></td><td align=\"center\" bgcolor=\"$bgcolor2\"><b>" . _FUNCTIONS . "</b></td></tr>";

		$sql = "select mid, title, custom_title, active, view, bltype, inmenu, theme from " . $prefix . "_modules order by title ASC";
		$result = $db->sql_query( $sql );
		while ( $row = $db->sql_fetchrow($result) )
		{
			$mid = $row[mid];
			$title = $row[title];
			$custom_title = $row[custom_title];
			$active = $row[active];
			$view = $row[view];
			$bltype = $row[bltype];
			$inmenu = $row[inmenu];
			$theme = $row['theme'];
			$mid = intval( $mid );
			if ( $custom_title == "" )
			{
				$custom_title = ereg_replace( "_", " ", $title );
				$db->sql_query( "update " . $prefix . "_modules set custom_title='$custom_title' where mid='$mid'" );
			}
			if ( $active == 1 )
			{
				$active = _ACTIVE;
				$change = _DEACTIVATE;
				$act = 0;
			}
			else
			{
				$active = "<i>" . _INACTIVE . "</i>";
				$change = _ACTIVATE;
				$act = 1;
			}
			if ( $custom_title == "" )
			{
				$custom_title = ereg_replace( "_", " ", $title );
			}
			if ( $view == 0 )
			{
				$who_view = _MVALL;
			} elseif ( $view == 1 )
			{
				$who_view = _MVUSERS;
			} elseif ( $view == 2 )
			{
				$who_view = _MVADMIN;
			}
			if ( $theme == "" )
			{
				$theme = "--";
			}
			if ( $bltype == 0 )
			{
				$tt_bltype = _BLOCK_TYPE0;
			} elseif ( $bltype == 1 )
			{
				$tt_bltype = _BLOCK_TYPE1;
			} elseif ( $bltype == 2 )
			{
				$tt_bltype = _BLOCK_TYPE2;
			} elseif ( $bltype == 3 )
			{
				$tt_bltype = _BLOCK_TYPE3;
			} elseif ( $bltype == 4 )
			{
				$tt_bltype = _BLOCK_TYPE4;
			} elseif ( $bltype == 5 )
			{
				$tt_bltype = _BLOCK_TYPE5;
			}
			$custom_title = "[ <sub><strong>$inmenu</strong></sub> ] $custom_title";
			if ( $title == $Home_Module )
			{
				$title = "<b>$title</b>";
				$custom_title = "<b>$custom_title</b>";
				$active = "<b>$active (" . _INHOME . ")</b>";
				$who_view = "<b>$who_view</b>";
				$change_status = "<i>$change</i>";
				$background = "bgcolor=\"$bgcolor2\"";
			}
			else
			{
				$change_status = "<a href=\"" . $adminfile . ".php?op=module_status&mid=$mid&active=$act\">$change</a>";
				$background = "";
			}

			echo "<tr><td $background>&nbsp;$title</td><td $background>$custom_title</td><td align=\"center\" $background>$active</td><td align=\"center\" $background>$who_view</td><td align=\"center\" $background>$tt_bltype</td><td align=\"center\" $background>$theme</td><td align=\"center\" $background nowrap>[ <a href=\"" . $adminfile . ".php?op=module_edit&mid=$mid\">" . _EDIT . "</a> | $change_status ]</td></tr>";
		}
		echo "</table>";
		echo "\n" . _MODULEHOMENOTE . " <a href=$adminfile.php?op=Configure>" . _MODULEHOMENOTE2 . "</a>!<br><br>";

		echo "\n<b>" . _NOTE1 . ":</b><br>";
		echo "\n<sup>(*)</sup> " . _INMENU . "";
		echo "<ul>";
		echo "<li>[0]: " . _INMENU0 . "</li><li>[1]: " . _INMENU1 . "</li><li>[2]: " . _INMENU2 . "</li><li>[3]: " . _INMENU3 . "</li><li>[4]: " . _INMENU4 . "</li><li>[5]: " . _INMENU5 . "</li><li>[6]: " . _INMENU6 . "</li><li>[7]: " . _INMENU7 . "</li><li>[8]: " . _INMENU8 . "</li><li>[9]: " . _INMENU9 . "</li>\n";
		echo "</ul>";
		echo "<sup>(**)</sup> " . _BLOCK_TYPENOTE . "<br>";
		echo "<sup>(***)</sup> " . _MODSKIN2 . "<br>";
		CloseTable();
		include ( "../footer.php" );
	}


	/**
	 * module_status()
	 * 
	 * @param mixed $mid
	 * @param mixed $active
	 * @return
	 */
	function module_status( $mid, $active )
	{
		global $adminfile, $prefix, $db;
		$mid = intval( $mid );
		$db->sql_query( "update " . $prefix . "_modules set active='$active' where mid='$mid'" );
		Header( "Location: " . $adminfile . ".php?op=modules" );
	}

	/**
	 * module_edit()
	 * 
	 * @param mixed $mid
	 * @return
	 */
	function module_edit( $mid )
	{
		global $adminfile, $prefix, $db, $Home_Module;

		$main_module = $Home_Module;
		$mid = intval( $mid );
		$sql = "SELECT * FROM `" . $prefix . "_modules` WHERE `mid`='" . $mid . "'";
		$result = $db->sql_query( $sql );
		$row = $db->sql_fetchrow( $result );
		$title = $row['title'];
		$custom_title = $row['custom_title'];
		$view = $row['view'];
		$inmenu = $row['inmenu'];
		$bltype = $row['bltype'];
		$theme = $row['theme'];
		$active = $row['active'];

		include ( "../header.php" );
		GraphicAdmin();
		title( _MODULEEDIT );
		OpenTable();
		$a = ( $title == $main_module ) ? " - " . _INHOME : "";
		echo "<center><b>" . _CHANGEMODNAME . "</b><br>(" . $title . $a . ")</center><br><br>\n";
		echo "<form action=\"" . $adminfile . ".php\" method=\"post\">\n";
		echo "<table border=\"0\">\n";
		echo "<tr>\n";
		echo "<td>" . "" . _CUSTOMMODNAME . "</td>\n";
		echo "<td><input style=\"width:300px;\" type=\"text\" name=\"custom_title\" value=\"$custom_title\"></td>\n";
		echo "</tr>";

//		begin edit 08.06.2009 - laser
		echo "<tr>\n";
		echo "<td>" . _VIEWPRIV . "</td>\n";
		echo "<td>";
		
		if ( $title == $main_module )
		{
			echo ""._MVALL."<input type=\"hidden\" name=\"view\" value=\"0\">\n";
		}
		else
		{
			echo "<select style=\"width:300px;\" name=\"view\">\n";
			$array = array( _MVALL, _MVUSERS, _MVADMIN );
			foreach ( $array as $k => $v )
			{
				echo "<option value=\"" . $k . "\"" . ( $k == $view ? " selected=\"selected\"" : "" ) . ">" . $v . "</option>\n";
			}
			echo "</select>";
		}
		echo "</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td>" . _BLOCK_TYPE . "</td>\n";
		echo "<td><select style=\"width:300px;\" name=\"bltype\">\n";
		$ybltype = array( _BLOCK_TYPE0, _BLOCK_TYPE1, _BLOCK_TYPE2, _BLOCK_TYPE3, _BLOCK_TYPE4, _BLOCK_TYPE5 );
		foreach ( $ybltype as $k => $v )
		{
			echo "<option name=\"bltype\" value=\"" . $k . "\"" . ( $k == $bltype ? " selected=\"selected\"" : "" ) . ">" . $v . "</option>\n";
		}
		echo "</select>\n";
		echo "</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td>" . _SHOWINMENU . "</td>\n";
		echo "<td><select style=\"width:300px;\" name=\"inmenu\">\n";
		$yinmenu = array( _INMENU0, _INMENU1, _INMENU2, _INMENU3, _INMENU4, _INMENU5, _INMENU6, _INMENU7, _INMENU8, _INMENU9 );
		foreach ( $yinmenu as $k => $v )
		{
			echo "<option name=\"inmenu\" value=\"" . $k . "\"" . ( $k == $inmenu ? " selected=\"selected\"" : "" ) . ">" . $v . "</option>\n";
		}
		echo "</select>\n";
		echo "</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td>" . _MODSKIN . " (" . _MODSKIN2 . ")</td>\n";
		echo "<td><select style=\"width:300px;\" name=\"theme\">\n";
		echo "<option name=\"theme\" value=\"\">---</option>\n";
		$handle = opendir( '../themes' );
		while ( $file = readdir($handle) )
		{
			if ( ! empty($file) and ! ereg("[.]", $file) )
			{
				echo "<option name=\"theme\" value=\"" . $file . "\"" . ( $file == $theme ? " selected=\"selected\"" : "" ) . ">" . $file . "</option>\n";
			}
		}
		closedir( $handle );
		echo "</select>\n";
		echo "</td>\n";
		echo "</tr>\n";
		
		if ( $title == $main_module )
		{
			echo "<tr>\n";
			echo "<td>"._ACTIVE.": " . _YES . "</td>\n";
			echo "<td>\n";
			echo "<input type=\"hidden\" name=\"active\" value=\"1\">\n";
		}
		else
		{
			echo "<tr>\n";
			echo "<td>"._ACTIVE."</td>\n";
			echo "<td>\n";
			if ( $active == 1 )
			{
				echo "<input type=\"radio\" name=\"active\" value=\"1\" checked>" . _YES . " &nbsp;
	        <input type=\"radio\" name=\"active\" value=\"0\">" . _NO . "";
			}
			else
			{
				echo "<input type=\"radio\" name=\"active\" value=\"1\">" . _YES . " &nbsp;
	        <input type=\"radio\" name=\"active\" value=\"0\" checked>" . _NO . "";
			}
		}
//		end edit 08.06.2009 - laser
		echo "<input type=\"hidden\" name=\"mid\" value=\"" . $mid . "\">\n";
		echo "<input type=\"hidden\" name=\"op\" value=\"module_edit_save\">\n";
		echo "<input type=\"submit\" value=\"" . _SAVECHANGES . "\">\n";
		echo "</td>\n";
		echo "</tr>\n";
		echo "</table><br><br>\n";
		echo "</form><br><br>\n";
		echo "<center>" . _GOBACK . "</center>";
		CloseTable();
		include ( "../footer.php" );
	}

	/**
	 * module_edit_save()
	 * 
	 * @param mixed $mid
	 * @param mixed $custom_title
	 * @param mixed $view
	 * @param mixed $bltype
	 * @param mixed $inmenu
	 * @param mixed $theme
	 * @param mixed $active
	 * @return
	 */
	function module_edit_save( $mid, $custom_title, $view, $bltype, $inmenu, $theme, $active )
	{
		global $adminfile, $prefix, $db;
		$mid = intval( $mid );
		$db->sql_query( "UPDATE `" . $prefix . "_modules` SET `custom_title`='" . $custom_title . "', view='" . $view . "', bltype='" . $bltype . "', inmenu='" . $inmenu . "', `theme`='" . $theme . "', `active`='" . $active . "' WHERE `mid`=" . $mid );
		Header( "Location: " . $adminfile . ".php?op=modules" );
	}

	switch ( $op )
	{
		case "modules":
			modules();
			break;

		case "module_status":
			module_status( $mid, $active );
			break;

		case "module_edit":
			module_edit( $mid );
			break;

		case "module_edit_save":
			module_edit_save( $mid, $custom_title, $view, $bltype, $inmenu, $theme, $active );
			break;
	}

}
else
{
	echo "Access Denied";
}

?>