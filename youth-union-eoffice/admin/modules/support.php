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

$checkmodname = "Support";
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
	 * Support_Menu()
	 * 
	 * @return
	 */
	function Support_Menu()
	{
		global $adminfile;
		OpenTable();
		echo "<div style=\"text-align:center;font-weight: bold\">\n";
		echo "<a href=\"" . $adminfile . ".php?op=Support_List_Cat\">" . _SUPPORTADM01 . "</a>&nbsp;|&nbsp;\n";
		echo "<a href=\"" . $adminfile . ".php?op=Support_ListS_All\">" . _SUPPORTADM02 . "</a>&nbsp;|&nbsp;\n";
		echo "<a href=\"" . $adminfile . ".php?op=Support_ListS_User&amp;st=3\">" . _SUPPORTADM03 . "</a>\n";
		CloseTable();
		echo "<br />\n";
	}

	/**
	 * Support_CatName()
	 * 
	 * @param mixed $id
	 * @param mixed $list
	 * @return
	 */
	function Support_CatName( $id, $list )
	{
		$name = "";
		if ( isset($list[$id]) )
		{
			if ( $list[$id]['subid'] )
			{
				$name .= Support_CatName( $list[$id]['subid'], $list ) . " &raquo; ";
			}
			$name .= $list[$id]['title'];
		}
		return $name;
	}

	/**
	 * Support_SubList()
	 * 
	 * @param mixed $key
	 * @param mixed $list
	 * @return
	 */
	function Support_SubList( $key, $list )
	{
		$sublist = $list[$key]['sublist'];
		foreach ( $list[$key]['sublist'] as $sub )
		{
			if ( $list[$sub]['sublist'] )
			{
				$sublist = array_merge( $sublist, Support_SubList($sub, $list) );
			}
		}
		return $sublist;
	}

	/**
	 * Support_CList()
	 * 
	 * @return
	 */
	function Support_CList()
	{
		global $db, $prefix, $currentlang;
		$list = array();
		$num = array();
		$sql = "SELECT * FROM " . $prefix . "_nvsupport_cat WHERE language='" . $currentlang . "' ORDER BY subid, weight";
		$result = $db->sql_query( $sql );
		while ( $row = $db->sql_fetchrow($result) )
		{
			$id = intval( $row['id'] );
			$subid = intval( $row['subid'] );
			$list[$id] = array( 'subid' => $subid, 'title' => at_htmlspecialchars(stripslashes($row['title'])), 'active' => intval($row['active']), 'weight' => intval($row['weight']) );
			$num[$subid]++;
			if ( $subid )
			{
				$list[$subid]['sublist'][] = $id;
			}
		}
		$list2 = array();
		if ( $list != array() )
		{
			foreach ( $list as $key => $value )
			{
				$list2[$key] = $value;
				$list2[$key]['name'] = Support_CatName( $key, $list );
				$list2[$key]['sublist'] = array();
				if ( $value['sublist'] )
				{
					$list2[$key]['sublist'] = Support_SubList( $key, $list );
				}
				$list2[$key]['numsub'] = intval( $num[$value['subid']] );
			}
		}
		return $list2;
	}

	/**
	 * Support_FixWeightCat()
	 * 
	 * @param mixed $subid
	 * @return
	 */
	function Support_FixWeightCat( $subid )
	{
		global $db, $prefix, $currentlang;
		$sql = "SELECT id FROM " . $prefix . "_nvsupport_cat WHERE language='" . $currentlang . "' AND subid=" . $subid . " ORDER BY weight";
		$result = $db->sql_query( $sql );
		$weight = 0;
		while ( $row = $db->sql_fetchrow($result) )
		{
			$id = intval( $row['id'] );
			$weight++;
			$db->sql_query( "UPDATE " . $prefix . "_nvsupport_cat SET weight=" . $weight . " WHERE id=" . $id );
		}
	}

	/**
	 * Support_List_Cat()
	 * 
	 * @return
	 */
	function Support_List_Cat()
	{
		global $db, $prefix, $currentlang, $adminfile;
		$listcat = Support_CList();

		$new_title = at_htmlspecialchars( strip_tags(stripslashes(trim($_POST['new_title']))) );
		$new_subid = intval( $_POST['new_subid'] );
		$save = intval( $_POST['save'] );
		$error = "";
		if ( $save )
		{
			if ( $new_title == "" )
			{
				$error = _SUPPORTADM04;
			} elseif ( $new_subid and ! isset($listcat[$new_subid]) )
			{
				$error = _SUPPORTADM05;
			}
			else
			{
				$db->sql_query( "INSERT INTO " . $prefix . "_nvsupport_cat VALUES (
				NULL, " . $new_subid . ", '" . $currentlang . "', '" . $new_title . "', 1, 999999999)" );
				Support_FixWeightCat( $new_subid );
				Header( "Location: " . $adminfile . ".php?op=Support_List_Cat" );
				exit();
			}
		}

		include ( "../header.php" );
		GraphicAdmin();
		Support_Menu();
		OpenTable();
		echo "<div style=\"font-weight: bold; text-align: center;margin-bottom: 10px;\">" . _SUPPORTADM06 . "</div>\n";
		if ( ! empty($error) )
		{
			echo "<div style=\"font-weight: bold; color: #FF3300; text-align: center;margin-bottom: 10px;\">" . $error . "</div>\n";
		}
		echo "<form method=\"post\" action=\"" . $adminfile . ".php?op=Support_List_Cat\">\n";
		echo "<div style=\"text-align: center\">\n";
		echo "<input name=\"save\" type=\"hidden\" value=\"1\" />\n";
		echo _TITLE . ": <input value=\"" . $new_title . "\" name=\"new_title\" type=\"text\" maxlength=\"255\" style=\"vertical-align: middle; border: 1px solid #CCCCCC; width: 300px; margin-right: 5px;\" /><br />\n";
		echo _SUPPORTADM07 . ": <select name=\"new_subid\" style=\"vertical-align: middle; border: 1px solid #CCCCCC\">\n";
		echo "<option value=\"0\">" . _SUPPORTADM08 . "</option>\n";
		foreach ( $listcat as $key => $value )
		{
			echo "<option value=\"" . $key . "\"" . ( ($key == $new_subid) ? " selected=\"selected\"" : "" ) . ">" . $value['name'] . "</option>\n";
		}
		echo "</select><input name=\"Submit1\" type=\"submit\" value=\"&nbsp;OK&nbsp;\" style=\"vertical-align: middle; border: 1px solid #CCCCCC\" /></div>\n";
		echo "</form>\n";
		CloseTable();
		echo "<br />\n";
		if ( $listcat != array() )
		{
			echo "<div style=\"font-weight: bold; text-align: center;margin-bottom: 10px;\">" . _SUPPORTADM09 . "</div>\n";
			echo "<table style=\"border-collapse: collapse;border: 1px solid #CCCCCC; width: 100%\">\n";
			echo "<tr>\n";
			echo "<td style=\"padding:1px\">\n";
			echo "<table style=\"border-collapse: collapse;width: 100%\">\n";
			echo "<tr style=\"background-color: #E4E4E4;font-weight: bold\">\n";
			echo "<td style=\"border-style: solid; border-width: 0px 1px 0px 0px; border-color: #CCCCCC;padding: 5px; text-align: left;white-space: nowrap;width: 90%;\">\n";
			echo _TITLE . "</td>\n";
			echo "<td style=\"border-style: solid; border-width: 0px 1px 0px 0px; border-color: #CCCCCC;padding: 5px; text-align: center;white-space: nowrap;\">\n";
			echo _POSITION . "</td>\n";
			echo "<td style=\"border-style: solid; border-width: 0px 1px 0px 0px; border-color: #CCCCCC;padding: 5px; text-align: center;white-space: nowrap;\">\n";
			echo _ACTIVATE . "</td>\n";
			echo "<td style=\"padding: 5px; text-align: center;white-space: nowrap;\" colspan=\"4\">\n";
			echo _FUNCTIONS . "</td>\n";
			echo "</tr>\n";
			$a = 0;
			foreach ( $listcat as $key => $value )
			{
				$bgcolor = ( $a % 2 == 0 ) ? "#FFFFF" : "#E4E4E4";
				echo "<tr style=\"background-color: " . $bgcolor . ";\">\n";
				echo "<td style=\"border-style: solid; border-width: 1px 1px 0px 0px; border-color: #CCCCCC; padding: 5px; width: 90%; text-align: left;\">\n";
				echo "<a href=\"" . $adminfile . ".php?op=Support_ListS_All&amp;cat=" . $key . "\">" . $value['name'] . "</a></td>\n";
				echo "<td style=\"border-style: solid; border-width: 1px 1px 0px 0px; border-color: #CCCCCC;padding: 5px; text-align: center; white-space: nowrap;\">\n";
				if ( $value['numsub'] > 1 )
				{
					echo "<form method=\"get\">\n";
					echo "<select name=\"select1_" . $key . "\" onchange=\"top.location.href=this.options[this.selectedIndex].value\">\n";
					for ( $i = 1; $i <= $value['numsub']; $i++ )
					{
						echo "<option value=\"" . $adminfile . ".php?op=Support_ChgW_Cat&amp;id=" . $key . "&amp;new=" . $i . "\"" . ( ($value['weight'] == $i) ? " selected=\"selected\"" : "" ) . ">" . $i . "</option>\n";
					}
					echo "</select></form>\n";
				}
				echo "</td>\n";
				echo "<td style=\"border-style: solid; border-width: 1px 1px 0px 0px; border-color: #CCCCCC;padding: 5px; text-align: center; white-space: nowrap;\">\n";
				echo "<form method=\"get\">\n";
				echo "<select name=\"select2_" . $key . "\" onchange=\"top.location.href=this.options[this.selectedIndex].value\">\n";
				$ps = array( _NO, _YES );
				foreach ( $ps as $k => $v )
				{
					echo "<option value=\"" . $adminfile . ".php?op=Support_Act_Cat&amp;id=" . $key . "\"" . ( ($value['active'] == $k) ? " selected=\"selected\"" : "" ) . ">" . $v . "</option>\n";
				}
				echo "</select></form>\n";
				echo "</td>\n";
				echo "<td style=\"border-style: solid; border-width: 1px 1px 0px 0px; border-color: #CCCCCC;padding: 5px; text-align: center; white-space: nowrap;\">\n";
				echo "<a href=\"" . $adminfile . ".php?op=Support_ListS_All&amp;cat=" . $key . "\">" . _SHOW . "</a></td>\n";
				echo "<td style=\"border-style: solid; border-width: 1px 1px 0px 0px; border-color: #CCCCCC;padding: 5px; text-align: center; white-space: nowrap;\">\n";
				echo "<a href=\"" . $adminfile . ".php?op=Support_Edit_Cat&amp;id=" . $key . "\">" . _EDIT . "</a></td>\n";
				echo "<td style=\"border-style: solid; border-width: 1px 1px 0px 0px; border-color: #CCCCCC;padding: 5px; text-align: center; white-space: nowrap;\">\n";
				echo "<a href=\"" . $adminfile . ".php?op=Support_Del_Cat&amp;id=" . $key . "\">" . _DELETE . "</a></td>\n";
				echo "<td style=\"border-style: solid; border-width: 1px 0px 0px 0px; border-color: #CCCCCC;padding: 5px; text-align: center; white-space: nowrap;\">\n";
				echo "<a href=\"" . $adminfile . ".php?op=Support_AddS_All&amp;catid=" . $key . "\">" . _SUPPORTADM20 . "</a></td>\n";
				echo "</tr>\n";
				$a++;
			}
			echo "</table>\n";
			echo "</td>\n";
			echo "</tr>\n";
			echo "</table>\n";
			echo "<br>";
		}
		include ( "../footer.php" );
	}

	/**
	 * Support_Edit_Cat()
	 * 
	 * @return
	 */
	function Support_Edit_Cat()
	{
		global $adminfile, $db, $prefix, $currentlang;
		$id = intval( $_REQUEST['id'] );
		if ( ! $id )
		{
			Header( "Location: " . $adminfile . ".php?op=Support_List_Cat" );
			exit();
		}
		$listcat = Support_CList();
		if ( ! isset($listcat[$id]) )
		{
			Header( "Location: " . $adminfile . ".php?op=Support_List_Cat" );
			exit();
		}

		$save = intval( $_POST['save'] );
		$error = "";
		if ( $save )
		{
			$title = at_htmlspecialchars( strip_tags(stripslashes(trim($_POST['title']))) );
			$subid = intval( $_POST['subid'] );
			if ( $title == "" )
			{
				$error = _SUPPORTADM04;
			} elseif ( $subid and ! isset($listcat[$subid]) )
			{
				$error = _SUPPORTADM05;
			} elseif ( $subid and $listcat[$id]['sublist'] != array() and in_array($subid, $listcat[$id]['sublist']) )
			{
				$error = _SUPPORTADM05;
			}
			else
			{
				if ( $subid == $listcat[$id]['subid'] )
				{
					$db->sql_query( "UPDATE " . $prefix . "_nvsupport_cat SET title='" . $title . "' WHERE id=" . $id . " AND language='" . $currentlang . "'" );
				}
				else
				{
					$db->sql_query( "UPDATE " . $prefix . "_nvsupport_cat SET title='" . $title . "', subid=" . $subid . ", weight=999999999 WHERE id=" . $id . " AND language='" . $currentlang . "'" );
					Support_FixWeightCat( $listcat[$id]['subid'] );
					Support_FixWeightCat( $subid );
				}
				Header( "Location: " . $adminfile . ".php?op=Support_List_Cat" );
				exit();
			}
		}
		else
		{
			$title = $listcat[$id]['title'];
			$subid = $listcat[$id]['subid'];
		}

		include ( "../header.php" );
		GraphicAdmin();
		Support_Menu();
		OpenTable();
		echo "<div style=\"font-weight: bold; text-align: center;margin-bottom: 10px;\">" . _SUPPORTADM10 . "</div>\n";
		if ( ! empty($error) )
		{
			echo "<div style=\"font-weight: bold; color: #FF3300; text-align: center;margin-bottom: 10px;\">" . $error . "</div>\n";
		}
		echo "<form method=\"post\" action=\"" . $adminfile . ".php?op=Support_Edit_Cat&amp;id=" . $id . "\">\n";
		echo "<div style=\"text-align: center\">\n";
		echo "<input name=\"save\" type=\"hidden\" value=\"1\" />\n";
		echo _TITLE . ": <input value=\"" . $title . "\" name=\"title\" type=\"text\" maxlength=\"255\" style=\"vertical-align: middle; border: 1px solid #CCCCCC; width: 300px; margin-right: 5px;\" /><br />\n";
		echo _SUPPORTADM07 . ": <select name=\"subid\" style=\"vertical-align: middle; border: 1px solid #CCCCCC\">\n";
		echo "<option value=\"0\">" . _SUPPORTADM08 . "</option>\n";
		foreach ( $listcat as $key => $value )
		{
			if ( $key != $id and ! in_array($key, $listcat[$id]['sublist']) )
			{
				echo "<option value=\"" . $key . "\"" . ( ($key == $subid) ? " selected=\"selected\"" : "" ) . ">" . $value['name'] . "</option>\n";
			}
		}
		echo "</select><input name=\"Submit1\" type=\"submit\" value=\"&nbsp;OK&nbsp;\" style=\"vertical-align: middle; border: 1px solid #CCCCCC\" /></div>\n";
		echo "</form>\n";
		CloseTable();
		include ( "../footer.php" );
	}

	/**
	 * Support_Del_Cat()
	 * 
	 * @return
	 */
	function Support_Del_Cat()
	{
		global $db, $prefix, $currentlang, $adminfile;
		$id = intval( $_REQUEST['id'] );
		if ( ! $id )
		{
			Header( "Location: " . $adminfile . ".php?op=Support_List_Cat" );
			exit();
		}
		$listcat = Support_CList();
		if ( ! isset($listcat[$id]) )
		{
			Header( "Location: " . $adminfile . ".php?op=Support_List_Cat" );
			exit();
		}
		$ok = intval( $_REQUEST['ok'] );
		if ( $ok )
		{
			if ( isset($listcat[$id]['sublist']) and $listcat[$id]['sublist'] != array() )
			{
				include ( "../header.php" );
				GraphicAdmin();
				Support_Menu();
				OpenTable();
				echo "<div style=\"font-weight: bold; color: #FF3300; text-align: center;margin: 10px;\">" . _SUPPORTADM11 . "</div>\n";
				echo "<META HTTP-EQUIV=\"refresh\" content=\"5;URL=" . $adminfile . ".php?op=Support_List_Cat\">";
				CloseTable();
				include ( "../footer.php" );
				exit;
			}
			$numf = $db->sql_fetchrow( $db->sql_query("SELECT COUNT(*) FROM " . $prefix . "_nvsupport_all WHERE catid=" . $id) );
			if ( $numf[0] )
			{
				include ( "../header.php" );
				GraphicAdmin();
				Support_Menu();
				OpenTable();
				echo "<div style=\"font-weight: bold; color: #FF3300; text-align: center;margin: 10px;\">" . _SUPPORTADM12 . "</div>\n";
				echo "<META HTTP-EQUIV=\"refresh\" content=\"5;URL=" . $adminfile . ".php?op=Support_List_Cat\">";
				CloseTable();
				include ( "../footer.php" );
				exit;
			}
			$numf = $db->sql_fetchrow( $db->sql_query("SELECT COUNT(*) FROM " . $prefix . "_nvsupport_user WHERE catid=" . $id) );
			if ( $numf[0] )
			{
				include ( "../header.php" );
				GraphicAdmin();
				Support_Menu();
				OpenTable();
				echo "<div style=\"font-weight: bold; color: #FF3300; text-align: center;margin: 10px;\">" . _SUPPORTADM12 . "</div>\n";
				echo "<META HTTP-EQUIV=\"refresh\" content=\"5;URL=" . $adminfile . ".php?op=Support_List_Cat\">";
				CloseTable();
				include ( "../footer.php" );
				exit;
			}
			$db->sql_query( "DELETE FROM " . $prefix . "_nvsupport_cat WHERE id=" . $id );
			Support_FixWeightCat( $listcat[$id]['subid'] );
			Header( "Location: " . $adminfile . ".php?op=Support_List_Cat" );
			exit();
		}
		include ( "../header.php" );
		GraphicAdmin();
		Support_Menu();
		OpenTable();
		echo "<div style=\"font-weight: bold; text-align: center;margin: 10px;\">" . _SUPPORTADM13 . "?</div>\n";
		echo "<div style=\"text-align: center\">\n";
		echo "<button name=\"Abutton1\" style=\"width: 70px\" onclick=\"location.href='" . $adminfile . ".php?op=Support_Del_Cat&id=" . $id . "&ok=1'\">OK</button>&nbsp;\n";
		echo "<button name=\"Abutton2\" style=\"width: 70px\" onclick=\"location.href='" . $adminfile . ".php?op=Support_List_Cat'\">Cancel</button>\n";
		echo "</div>\n";
		CloseTable();
		include ( "../footer.php" );
	}

	/**
	 * Support_ChgW_Cat()
	 * 
	 * @return
	 */
	function Support_ChgW_Cat()
	{
		global $db, $prefix, $adminfile;
		$id = intval( $_REQUEST['id'] );
		$new = intval( $_REQUEST['new'] );
		if ( $id and $new )
		{
			$listcat = Support_CList();
			if ( isset($listcat[$id]) )
			{
				if ( $listcat[$id]['numsub'] > 1 )
				{
					$subid = $listcat[$id]['subid'];
					$weight = 0;
					foreach ( $listcat as $key => $value )
					{
						if ( $key != $id and $value['subid'] == $subid )
						{
							$weight++;
							if ( $weight == $new )
							{
								$weight++;
							}
							$db->sql_query( "UPDATE " . $prefix . "_nvsupport_cat SET weight=" . $weight . " WHERE id=" . $key );
						}
					}
					$db->sql_query( "UPDATE " . $prefix . "_nvsupport_cat SET weight=" . $new . " WHERE id=" . $id );
				}
			}
		}
		Header( "Location: " . $adminfile . ".php?op=Support_List_Cat" );
	}

	/**
	 * Support_Act_Cat()
	 * 
	 * @return
	 */
	function Support_Act_Cat()
	{
		global $db, $prefix, $currentlang, $adminfile;
		$id = intval( $_GET['id'] );
		if ( $id )
		{
			$sql = "SELECT active FROM " . $prefix . "_nvsupport_cat WHERE language='" . $currentlang . "' AND id=" . $id;
			$result = $db->sql_query( $sql );
			if ( $db->sql_numrows($result) == 1 )
			{
				$row = $db->sql_fetchrow( $result );
				$active = intval( $row['active'] );
				$active = ( $active ) ? 0 : 1;
				$db->sql_query( "UPDATE " . $prefix . "_nvsupport_cat SET active=" . $active . " WHERE id=" . $id );
			}
		}
		Header( "Location: " . $adminfile . ".php?op=Support_List_Cat" );
	}

	/**
	 * Support_ListS_All()
	 * 
	 * @return
	 */
	function Support_ListS_All()
	{
		global $db, $prefix, $currentlang, $adminfile;
		$sql = "SELECT * FROM " . $prefix . "_nvsupport_all WHERE language='" . $currentlang . "'";
		$cat = intval( $_REQUEST['cat'] );
		$listcat = Support_CList();
		if ( $cat and isset($listcat[$cat]) )
		{
			$sql .= " AND catid=" . $cat;
			$ptitle = _SUPPORTADM15 . ':<br />' . $listcat[$cat]['name'];
		}
		else
		{
			$ptitle = _SUPPORTADM14;
		}

		include ( "../header.php" );
		GraphicAdmin();
		Support_Menu();
		echo "<div style=\"font-weight: bold; text-align: center;margin-bottom: 10px;\">" . $ptitle . "<br />( <a href=\"" . $adminfile . ".php?op=Support_AddS_All" . ( isset($listcat[$cat]) ? "&amp;catid=" . $cat . "" : "" ) . "\">" . _SUPPORTADM20 . "</a> )</div>\n";
		$result = $db->sql_query( $sql );
		if ( $db->sql_numrows($result) != 0 )
		{
			echo "<table style=\"border-collapse: collapse;border: 1px solid #CCCCCC; width: 100%\">\n";
			echo "<tr>\n";
			echo "<td style=\"padding:1px\">\n";
			echo "<table style=\"border-collapse: collapse;width: 100%\">\n";
			echo "<tr style=\"background-color: #E4E4E4;font-weight: bold\">\n";
			echo "<td style=\"border-style: solid; border-width: 0px 1px 0px 0px; border-color: #CCCCCC;padding: 5px; text-align: left;white-space: nowrap;\">\n";
			echo _SUPPORTADM16 . "</td>\n";
			echo "<td style=\"border-style: solid; border-width: 0px 1px 0px 0px; border-color: #CCCCCC;padding: 5px; text-align: center;white-space: nowrap;\">\n";
			echo _INCAT . "</td>\n";
			echo "<td style=\"border-style: solid; border-width: 0px 1px 0px 0px; border-color: #CCCCCC;padding: 5px; text-align: center;white-space: nowrap;\">\n";
			echo _SUPPORTADM17 . "</td>\n";
			echo "<td style=\"padding: 5px; text-align: center;white-space: nowrap;\" colspan=\"2\">\n";
			echo _FUNCTIONS . "</td>\n";
			echo "</tr>\n";
			$a = 0;
			while ( $row = $db->sql_fetchrow($result) )
			{
				$id = intval( $row['id'] );
				$catid = intval( $row['catid'] );
				$bgcolor = ( $a % 2 == 0 ) ? "#FFFFF" : "#E4E4E4";
				echo "<tr style=\"background-color: " . $bgcolor . ";\">\n";
				echo "<td style=\"border-style: solid; border-width: 1px 1px 0px 0px; border-color: #CCCCCC; padding: 5px; width: 90%; text-align: left;\">\n";
				echo "<a href=\"" . $adminfile . ".php?op=Support_EditS_All&amp;id=" . $id . "\">" . stripslashes( $row['question'] ) . "</a></td>\n";
				echo "<td style=\"border-style: solid; border-width: 1px 1px 0px 0px; border-color: #CCCCCC;padding: 5px; text-align: center; white-space: nowrap;\">\n";
				echo $listcat[$catid]['title'] . "</td>\n";
				echo "<td style=\"border-style: solid; border-width: 1px 1px 0px 0px; border-color: #CCCCCC;padding: 5px; text-align: center; white-space: nowrap;\">\n";
				echo intval( $row['view'] ) . "</td>\n";
				echo "<td style=\"border-style: solid; border-width: 1px 1px 0px 0px; border-color: #CCCCCC;padding: 5px; text-align: center; white-space: nowrap;\">\n";
				echo "<a href=\"" . $adminfile . ".php?op=Support_EditS_All&amp;id=" . $id . "\">" . _EDIT . "</a></td>\n";
				echo "<td style=\"border-style: solid; border-width: 1px 0px 0px 0px; border-color: #CCCCCC;padding: 5px; text-align: center; white-space: nowrap;\">\n";
				echo "<a href=\"" . $adminfile . ".php?op=Support_DelS_All&amp;id=" . $id . "\">" . _DELETE . "</a></td>\n";
				echo "</tr>\n";
				$a++;
			}
			echo "</table>\n";
			echo "</td>\n";
			echo "</tr>\n";
			echo "</table>\n";
		}
		echo "<form method=\"post\" action=\"" . $adminfile . ".php?op=Support_ListS_All\">\n";
		echo "<div style=\"text-align: center;margin-top: 15px;\">\n";
		echo _SUPPORTADM18 . ": <select name=\"cat\" style=\"vertical-align: middle; border: 1px solid #CCCCCC\">\n";
		echo "<option value=\"0\">" . _SUPPORTADM19 . "</option>\n";
		foreach ( $listcat as $k => $v )
		{
			echo "<option value=\"" . $k . "\"" . ( ($k == $cat) ? " selected=\"selected\"" : "" ) . ">" . $v['name'] . "</option>\n";
		}
		echo "</select><input name=\"Submit1\" type=\"submit\" value=\"&nbsp;OK&nbsp;\" style=\"vertical-align: middle; border: 1px solid #CCCCCC\" /></div>\n";
		echo "</form>\n";
		include ( "../footer.php" );
	}

	/**
	 * Support_AddS_All()
	 * 
	 * @return
	 */
	function Support_AddS_All()
	{
		global $db, $prefix, $currentlang, $adminfile, $editor;
		$listcat = Support_CList();
		if ( $listcat == array() )
		{
			Header( "Location: " . $adminfile . ".php?op=Support_List_Cat" );
			exit();
		}
		$save = intval( $_POST['save'] );
		$error = "";
		if ( $save )
		{
			$catid = intval( isset($_POST['catid']) ? $_POST['catid'] : $_GET['catid'] );
			$question = cheonguoc( at_htmlspecialchars(strip_tags(stripslashes(trim($_POST['question'])))) );
			$answer = cheonguoc( stripslashes(FixQuotes(trim($_POST['answer']))) );
			if ( ! $catid || ! isset($listcat[$catid]) )
			{
				$error = _SUPPORTADM21;
			} elseif ( $question == "" )
			{
				$error = _SUPPORTADM22;
			} elseif ( $answer == "" )
			{
				$error = _SUPPORTADM23;
			}
			else
			{
				$time = time();
				$answer = ( $editor ) ? preg_replace( "/\\r\\n|\\n|\\r/", "", $answer ) : nl2br( $answer );
				$db->sql_query( "INSERT INTO " . $prefix . "_nvsupport_all VALUES (
			NULL, " . $catid . ", '" . $currentlang . "', '" . $question . "', '" . $answer . "', 0, " . $time . ")" );
				Header( "Location: " . $adminfile . ".php?op=Support_ListS_All" );
				exit();
			}
		} elseif ( isset($_GET['in']) )
		{
			$in = intval( $_GET['in'] );
			$sql = "SELECT * FROM " . $prefix . "_nvsupport_user WHERE language='" . $currentlang . "' AND id=" . $in;
			$result = $db->sql_query( $sql );
			if ( $db->sql_numrows($result) == 1 )
			{
				$row = $db->sql_fetchrow( $result );
				$catid = intval( $row['catid'] );
				$question = cheonguoc( at_htmlspecialchars(strip_tags(stripslashes(trim($row['title'])))) );
				$answer = cheonguoc( stripslashes(FixQuotes(trim($row['answer']))) );
			}
			else
			{
				$catid = 0;
				$question = $answer = "";
			}
		}
		else
		{
			$catid = 0;
			$question = $answer = "";
		}

		include ( "../header.php" );
		GraphicAdmin();
		Support_Menu();
		echo "<div style=\"font-weight: bold; text-align: center;margin-bottom: 10px;\">" . _SUPPORTADM20 . "</div>\n";
		if ( ! empty($error) )
		{
			echo "<div style=\"font-weight: bold; color: #FF3300; text-align: center;margin-bottom: 10px;\">" . $error . "</div>\n";
		}
		echo "<form method=\"post\" action=\"" . $adminfile . ".php?op=Support_AddS_All\">\n";
		echo "<table style=\"width: 100%\">\n";
		echo "<tr>\n";
		echo "<td style=\"white-space: nowrap\">" . _SUPPORTADM07 . ":</td>\n";
		echo "<td style=\"width: 90%\">\n";
		echo "<select name=\"catid\" value=\"" . $catid . "\" style=\"border: 1px solid #CCCCCC\">\n";
		foreach ( $listcat as $k => $v )
		{
			echo "<option value=\"" . $k . "\"" . ( ($k == $catid) ? " selected=\"selected\"" : "" ) . ">" . $v['name'] . "</option>\n";
		}
		echo "</select></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td style=\"white-space: nowrap\">" . _SUPPORTADM24 . ":</td>\n";
		echo "<td style=\"width: 90%\">\n";
		echo "<input name=\"question\" value=\"" . $question . "\" type=\"text\" style=\"border: 1px solid #CCCCCC; width: 500px\" /></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td style=\"white-space: nowrap;vertical-align: top\">" . _SUPPORTADM25 . ":</td>\n";
		echo "<td style=\"width: 90%\">\n";
		if ( $editor )
		{
			aleditor( "answer", $answer, 500, 300 );
		}
		else
		{
			echo "<textarea name=\"answer\" cols=\"20\" rows=\"10\" style=\"border: 1px solid #CCCCCC; width: 500px\">" . $answer . "</textarea>\n";
		}
		echo "</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td><input name=\"save\" type=\"hidden\" value=\"1\" /></td>\n";
		echo "<td>\n";
		echo "<input name=\"Submit1\" type=\"submit\" value=\"&nbsp;OK&nbsp;\" style=\"border: 1px solid #CCCCCC\" /></td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		echo "</form>\n";

		include ( "../footer.php" );
	}

	/**
	 * Support_EditS_All()
	 * 
	 * @return
	 */
	function Support_EditS_All()
	{
		global $db, $prefix, $currentlang, $adminfile, $editor;
		$id = intval( $_REQUEST['id'] );
		if ( ! $id )
		{
			Header( "Location: " . $adminfile . ".php?op=Support_ListS_All" );
			exit();
		}
		$sql = "SELECT * FROM " . $prefix . "_nvsupport_all WHERE language='" . $currentlang . "' AND id=" . $id;
		$result = $db->sql_query( $sql );
		if ( $db->sql_numrows($result) != 1 )
		{
			Header( "Location: " . $adminfile . ".php?op=Support_ListS_All" );
			exit();
		}
		$row = $db->sql_fetchrow( $result );
		$listcat = Support_CList();
		if ( $listcat == array() )
		{
			Header( "Location: " . $adminfile . ".php?op=Support_List_Cat" );
			exit();
		}
		$save = intval( $_POST['save'] );
		$error = "";
		if ( $save )
		{
			$catid = intval( $_POST['catid'] );
			$question = cheonguoc( at_htmlspecialchars(strip_tags(stripslashes(trim($_POST['question'])))) );
			$answer = cheonguoc( stripslashes(FixQuotes(trim($_POST['answer']))) );
			if ( ! $catid || ! isset($listcat[$catid]) )
			{
				$error = _SUPPORTADM21;
			} elseif ( $question == "" )
			{
				$error = _SUPPORTADM22;
			} elseif ( $answer == "" )
			{
				$error = _SUPPORTADM23;
			}
			else
			{
				$answer = ( $editor ) ? preg_replace( "/\\r\\n|\\n|\\r/", "", $answer ) : nl2br( $answer );
				$db->sql_query( "UPDATE " . $prefix . "_nvsupport_all SET 
			catid=" . $catid . ", question='" . $question . "', answer='" . $answer . "' WHERE id=" . $id . " AND language='" . $currentlang . "'" );
				Header( "Location: " . $adminfile . ".php?op=Support_ListS_All" );
				exit();
			}
		}
		else
		{
			$catid = intval( $row['catid'] );
			$question = cheonguoc( at_htmlspecialchars(strip_tags(stripslashes(trim($row['question'])))) );
			$answer = cheonguoc( stripslashes(FixQuotes(trim($row['answer']))) );
		}
		include ( "../header.php" );
		GraphicAdmin();
		Support_Menu();
		echo "<div style=\"font-weight: bold; text-align: center;margin-bottom: 10px;\">" . _SUPPORTADM26 . "</div>\n";
		if ( ! empty($error) )
		{
			echo "<div style=\"font-weight: bold; color: #FF3300; text-align: center;margin-bottom: 10px;\">" . $error . "</div>\n";
		}
		echo "<form method=\"post\" action=\"" . $adminfile . ".php?op=Support_EditS_All\">\n";
		echo "<table style=\"width: 100%\">\n";
		echo "<tr>\n";
		echo "<td style=\"white-space: nowrap\">" . _SUPPORTADM07 . ":</td>\n";
		echo "<td style=\"width: 90%\">\n";
		echo "<select name=\"catid\" value=\"" . $catid . "\" style=\"border: 1px solid #CCCCCC\">\n";
		foreach ( $listcat as $k => $v )
		{
			echo "<option value=\"" . $k . "\"" . ( ($k == $catid) ? " selected=\"selected\"" : "" ) . ">" . $v['name'] . "</option>\n";
		}
		echo "</select></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td style=\"white-space: nowrap\">" . _SUPPORTADM24 . ":</td>\n";
		echo "<td style=\"width: 90%\">\n";
		echo "<input name=\"question\" value=\"" . $question . "\" type=\"text\" style=\"border: 1px solid #CCCCCC; width: 500px\" /></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td style=\"white-space: nowrap;vertical-align: top\">" . _SUPPORTADM25 . ":</td>\n";
		echo "<td style=\"width: 90%\">\n";
		if ( $editor )
		{
			aleditor( "answer", $answer, 500, 300 );
		}
		else
		{
			echo "<textarea name=\"answer\" cols=\"20\" rows=\"10\" style=\"border: 1px solid #CCCCCC; width: 500px\">" . $answer . "</textarea>\n";
		}
		echo "</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td><input name=\"save\" type=\"hidden\" value=\"1\" />\n<input name=\"id\" type=\"hidden\" value=\"" . $id . "\" /></td>\n";
		echo "<td>\n";
		echo "<input name=\"Submit1\" type=\"submit\" value=\"&nbsp;OK&nbsp;\" style=\"border: 1px solid #CCCCCC\" /></td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		echo "</form>\n";
		include ( "../footer.php" );
	}

	/**
	 * Support_DelS_All()
	 * 
	 * @return
	 */
	function Support_DelS_All()
	{
		global $db, $prefix, $currentlang, $adminfile;
		$id = intval( $_REQUEST['id'] );
		if ( $id )
		{
			$sql = "SELECT question FROM " . $prefix . "_nvsupport_all WHERE language='" . $currentlang . "' AND id=" . $id;
			$result = $db->sql_query( $sql );
			if ( $db->sql_numrows($result) == 1 )
			{
				$row = $db->sql_fetchrow( $result );
				$question = stripslashes( $row['question'] );
				$ok = intval( $_REQUEST['ok'] );
				if ( $ok )
				{
					$db->sql_query( "DELETE FROM " . $prefix . "_nvsupport_all WHERE language='" . $currentlang . "' AND id=" . $id );
				}
				else
				{
					include ( "../header.php" );
					GraphicAdmin();
					Support_Menu();
					title( _SUPPORTADM27 );
					OpenTable();
					echo "<div style=\"font-weight: bold; text-align: center;margin: 10px;\">" . sprintf( _SUPPORTADM28, "\"<u>" . $question . "</u>\"" ) . " ?</div>\n";
					echo "<div style=\"text-align: center\">\n";
					echo "<button name=\"Abutton1\" style=\"width: 70px\" onclick=\"location.href='" . $adminfile . ".php?op=Support_DelS_All&id=" . $id . "&ok=1'\">OK</button>&nbsp;\n";
					echo "<button name=\"Abutton2\" style=\"width: 70px\" onclick=\"location.href='" . $adminfile . ".php?op=Support_ListS_All'\">Cancel</button>\n";
					echo "</div>\n";
					CloseTable();
					include ( "../footer.php" );
					exit();
				}
			}
		}
		Header( "Location: " . $adminfile . ".php?op=Support_ListS_All" );
		exit();
	}

	/**
	 * Support_ListS_User()
	 * 
	 * @return
	 */
	function Support_ListS_User()
	{
		global $db, $prefix, $currentlang, $adminfile;
		$sql = "FROM " . $prefix . "_nvsupport_user WHERE language='" . $currentlang . "'";
		$ptitle = _SUPPORTADM29;
		$base_url = $adminfile . ".php?op=Support_ListS_User";
		$listcat = Support_CList();
		$st = intval( $_REQUEST['st'] );
		if ( in_array($st, array(0, 1, 2)) )
		{
			$sql .= " AND status=" . $st;
			switch ( $st )
			{
				case 0:
					$ptitle .= _SUPPORTADM31;
					break;
				case 1:
					$ptitle .= _SUPPORTADM30;
					break;
				case 2:
					$ptitle .= _SUPPORTADM37;
					break;
			}
			$base_url .= "&amp;st=" . $st;
		}
		$catid = intval( $_REQUEST['catid'] );
		if ( isset($listcat[$catid]) )
		{
			$sql .= " AND catid=" . $catid;
			$ptitle .= ' ' . _INCAT . ': <u>' . $listcat[$catid]['title'] . '</u>';
			$base_url .= "&amp;catid=" . $catid;
		}

		include ( "../header.php" );
		GraphicAdmin();
		Support_Menu();
		echo "<div style=\"font-weight: bold; text-align: center;margin-bottom: 10px;\">" . $ptitle . "</div>\n";
		$numf = $db->sql_fetchrow( $db->sql_query("SELECT COUNT(*) " . $sql) );
		if ( $numf[0] )
		{
			$page = ( isset($_GET['page']) ) ? intval( $_GET['page'] ) : 0;
			$all_page = ( $numf[0] ) ? $numf[0] : 1;
			$per_page = 30;
			$sql = "SELECT * " . $sql . " ORDER BY id DESC LIMIT $page,$per_page";
			$result = $db->sql_query( $sql );
			if ( $db->sql_numrows($result) != 0 )
			{
				echo "<table style=\"border-collapse: collapse;border: 1px solid #CCCCCC; width: 100%\">\n";
				echo "<tr>\n";
				echo "<td style=\"padding:1px\">\n";
				echo "<table style=\"border-collapse: collapse;width: 100%\">\n";
				echo "<tr style=\"background-color: #E4E4E4;font-weight: bold\">\n";
				echo "<td style=\"border-style: solid; border-width: 0px 1px 0px 0px; border-color: #CCCCCC;padding: 5px; text-align: center;white-space: nowrap;\">\n";
				echo _SUPPORTADM32 . "</td>\n";
				echo "<td style=\"border-style: solid; border-width: 0px 1px 0px 0px; border-color: #CCCCCC;padding: 5px; text-align: left;white-space: nowrap;\">\n";
				echo _TITLE . "</td>\n";
				echo "<td style=\"border-style: solid; border-width: 0px 1px 0px 0px; border-color: #CCCCCC;padding: 5px; text-align: center;white-space: nowrap;\">\n";
				echo _INCAT . "</td>\n";
				echo "<td style=\"padding: 5px; text-align: center;white-space: nowrap;\" colspan=\"3\">\n";
				echo _FUNCTIONS . "</td>\n";
				echo "</tr>\n";
				$a = 0;
				while ( $row = $db->sql_fetchrow($result) )
				{
					$id = intval( $row['id'] );
					$bgcolor = ( $a % 2 == 0 ) ? "#FFFFF" : "#E4E4E4";
					echo "<tr style=\"background-color: " . $bgcolor . ";" . ( ($row['status'] == '0') ? "font-weight: bold" : "" ) . "\">\n";
					echo "<td style=\"border-style: solid; border-width: 1px 1px 0px 0px; border-color: #CCCCCC;padding: 5px; text-align: center; white-space: nowrap;\">\n";
					echo viewtime( $row['questiontime'], 2 ) . "</td>\n";
					echo "<td style=\"border-style: solid; border-width: 1px 1px 0px 0px; border-color: #CCCCCC; padding: 5px; width: 90%; text-align: left;\">\n";
					echo "<a href=\"" . $adminfile . ".php?op=Support_EditS_User&amp;id=" . $id . "\">" . stripslashes( $row['title'] ) . "</a>\n";
					echo " ( <a href=\"mailto:" . stripslashes( $row['senderemail'] ) . "\">" . stripslashes( $row['sendername'] ) . "</a> - <a href=\"" . $adminfile . ".php?op=ConfigureBan&amp;bad_ip=" . stripslashes( $row['senderip'] ) . "\">" . stripslashes( $row['senderip'] ) . "</a> )</td>\n";
					echo "<td style=\"border-style: solid; border-width: 1px 1px 0px 0px; border-color: #CCCCCC;padding: 5px; text-align: center; white-space: nowrap;\">\n";
					echo "<a href=\"" . $adminfile . ".php?op=Support_ListS_User&amp;catid=" . intval( $row['catid'] ) . "&amp;st=3\">" . $listcat[intval( $row['catid'] )]['title'] . "</a></td>\n";
					echo "<td style=\"border-style: solid; border-width: 1px 1px 0px 0px; border-color: #CCCCCC;padding: 5px; text-align: center; white-space: nowrap;\">\n";
					echo "<a href=\"" . $adminfile . ".php?op=Support_EditS_User&amp;id=" . $id . "\">" . ( ($row['status'] == '0') ? _SUPPORTADM33 : _EDIT ) . "</a></td>\n";
					echo "<td style=\"border-style: solid; border-width: 1px 1px 0px 0px; border-color: #CCCCCC;padding: 5px; text-align: center; white-space: nowrap;\">\n";
					echo ( ($row['status'] == '0') ? "<a href=\"" . $adminfile . ".php?op=Support_Ignore&amp;id=" . $id . "\">" . _SUPPORTADM34 . "</a>" : (($row['status'] == '1') ? "<a href=\"" . $adminfile . ".php?op=Support_AddS_All&amp;in=" . $id . "\">" . _SUPPORTADM35 . "</a>" : "") ) . "</td>\n";
					echo "<td style=\"border-style: solid; border-width: 1px 0px 0px 0px; border-color: #CCCCCC;padding: 5px; text-align: center; white-space: nowrap;\">\n";
					echo "<a href=\"" . $adminfile . ".php?op=Support_DelS_User&amp;id=" . $id . "\">" . _DELETE . "</a></td>\n";
					echo "</tr>\n";
					$a++;
				}
				echo "</table>\n";
				echo "</td>\n";
				echo "</tr>\n";
				echo "</table>\n";
				echo "<div style=\"padding: 5px 0px 5px 0px; margin: 5px 0px 5px 0px;text-align: center\">\n";
				echo @generate_page( $base_url, $all_page, $per_page, $page );
				echo "</div>\n";
				echo "<br>";
			}
		}
		echo "<form method=\"post\" action=\"" . $adminfile . ".php?op=Support_ListS_User\">\n";
		echo "<div style=\"text-align: center;margin-top: 15px;\">\n";
		echo _SUPPORTADM18 . ": <select name=\"catid\" style=\"vertical-align: middle; border: 1px solid #CCCCCC\">\n";
		echo "<option value=\"0\">" . _SUPPORTADM19 . "</option>\n";
		foreach ( $listcat as $k => $v )
		{
			echo "<option value=\"" . $k . "\"" . ( ($k == $catid) ? " selected=\"selected\"" : "" ) . ">" . $v['name'] . "</option>\n";
		}
		echo "</select>\n";
		echo _SUPPORTADM36 . ": <select name=\"st\" style=\"vertical-align: middle; border: 1px solid #CCCCCC\">\n";
		echo "<option value=\"3\">" . _SUPPORTADM19 . "</option>\n";
		$array = array( _SUPPORTADM38, _SUPPORTADM39, _SUPPORTADM40 );
		for ( $i = 0; $i <= 2; $i++ )
		{
			echo "<option value=\"" . $i . "\"" . ( ($i == $st) ? " selected=\"selected\"" : "" ) . ">" . $array[$i] . "</option>\n";
		}
		echo "</select><input name=\"Submit1\" type=\"submit\" value=\"&nbsp;OK&nbsp;\" style=\"vertical-align: middle; border: 1px solid #CCCCCC\" /></div>\n";
		echo "</form><br />\n";
		include ( "../footer.php" );
	}

	/**
	 * Support_EditS_User()
	 * 
	 * @return
	 */
	function Support_EditS_User()
	{
		global $db, $prefix, $currentlang, $adminfile, $editor, $adminmail, $sitename, $nukeurl, $checkmodname;
		$id = intval( $_REQUEST['id'] );
		if ( ! $id )
		{
			Header( "Location: " . $adminfile . ".php?op=Support_ListS_User" );
			exit();
		}
		$sql = "SELECT * FROM " . $prefix . "_nvsupport_user WHERE language='" . $currentlang . "' AND id=" . $id;
		$result = $db->sql_query( $sql );
		if ( $db->sql_numrows($result) != 1 )
		{
			Header( "Location: " . $adminfile . ".php?op=Support_ListS_User" );
			exit();
		}
		$row = $db->sql_fetchrow( $result );
		$listcat = Support_CList();
		if ( $listcat == array() )
		{
			Header( "Location: " . $adminfile . ".php?op=Support_List_Cat" );
			exit();
		}
		$save = intval( $_POST['save'] );
		$error = "";
		if ( $save )
		{
			$catid = intval( $_POST['catid'] );
			$title = at_htmlspecialchars( strip_tags(stripslashes(trim($_POST['title']))) );
			$question = stripslashes( FixQuotes(trim($_POST['question'])) );
			$answer = stripslashes( FixQuotes(trim($_POST['answer'])) );
			if ( ! $catid || ! isset($listcat[$catid]) )
			{
				$error = _SUPPORTADM21;
			} elseif ( $title == "" )
			{
				$error = _SUPPORTADM42;
			} elseif ( $question == "" )
			{
				$error = _SUPPORTADM22;
			} elseif ( $answer == "" )
			{
				$error = _SUPPORTADM23;
			}
			else
			{
				$question = nl2br( $question );
				$answer = ( $editor ) ? preg_replace( "/\\r\\n|\\n|\\r/", "", $answer ) : nl2br( $answer );
				if ( $row['status'] == '0' || $row['status'] == '2' )
				{
					$view = 0;
					$time = time();
					$subject = _SUPPORTADM41 . ' ' . $sitename;
					$message = _SUPPORTADM43 . ':\r\n' . $title . '\r\n' . _SUPPORTADM44 . ':\r\n';
					$message .= $nukeurl . '/modules.php?name=' . $checkmodname . '&op=usp&ticket=' . $id;
					$mailhead = "From: " . $row['sendername'] . " <" . $row['senderemail'] . ">\n";
					$mailhead .= "Content-Type: text/plain; charset= " . _CHARSET . "\n";
					@mail( $adminmail, $subject, $message, $mailhead );
				}
				else
				{
					$view = $row['view'];
					$time = $row['answertime'];
				}
				$db->sql_query( "UPDATE " . $prefix . "_nvsupport_user SET 
			catid=" . $catid . ", status=1, view=" . $view . ", title='" . $title . "', question='" . $question . "', answer='" . $answer . "', answertime=" . $time . " WHERE id=" . $id . " AND language='" . $currentlang . "'" );
				Header( "Location: " . $adminfile . ".php?op=Support_ListS_User" );
				exit();
			}
		}
		else
		{
			$catid = intval( $row['catid'] );
			$title = at_htmlspecialchars( strip_tags(stripslashes(trim($row['title']))) );
			$question = stripslashes( FixQuotes(trim($row['question'])) );
			$answer = stripslashes( FixQuotes(trim($row['answer'])) );
		}
		include ( "../header.php" );
		GraphicAdmin();
		Support_Menu();
		echo "<div style=\"font-weight: bold; text-align: center;margin-bottom: 10px;\">" . ( ($row['status'] == '0' || $row['status'] == '2') ? _SUPPORTADM45 : _SUPPORTADM27 ) . "</div>\n";
		if ( ! empty($error) )
		{
			echo "<div style=\"font-weight: bold; color: #FF3300; text-align: center;margin-bottom: 10px;\">" . $error . "</div>\n";
		}
		echo "<form method=\"post\" action=\"" . $adminfile . ".php?op=Support_EditS_User\">\n";
		echo "<table style=\"width: 100%\">\n";
		echo "<tr>\n";
		echo "<td style=\"white-space: nowrap\">" . _SUPPORTADM07 . ":</td>\n";
		echo "<td style=\"width: 90%\">\n";
		echo "<select name=\"catid\" value=\"" . $catid . "\" style=\"border: 1px solid #CCCCCC\">\n";
		foreach ( $listcat as $k => $v )
		{
			echo "<option value=\"" . $k . "\"" . ( ($k == $catid) ? " selected=\"selected\"" : "" ) . ">" . $v['name'] . "</option>\n";
		}
		echo "</select></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td style=\"white-space: nowrap\">" . _TITLE . ":</td>\n";
		echo "<td style=\"width: 90%\">\n";
		echo "<input name=\"title\" value=\"" . $title . "\" type=\"text\" style=\"border: 1px solid #CCCCCC; width: 500px\" /></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td style=\"white-space: nowrap; vertical-align: top\">" . _SUPPORTADM24 . ":</td>\n";
		echo "<td style=\"width: 90%\">\n";
		echo "<textarea name=\"question\" cols=\"20\" rows=\"10\" style=\"border: 1px solid #CCCCCC; width: 500px\">" . $question . "</textarea></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td style=\"white-space: nowrap;vertical-align: top\">" . _SUPPORTADM25 . ":</td>\n";
		echo "<td style=\"width: 90%\">\n";
		if ( $editor )
		{
			aleditor( "answer", $answer, 500, 300 );
		}
		else
		{
			echo "<textarea name=\"answer\" cols=\"20\" rows=\"10\" style=\"border: 1px solid #CCCCCC; width: 500px\">" . $answer . "</textarea>\n";
		}
		echo "</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td><input name=\"save\" type=\"hidden\" value=\"1\" />\n<input name=\"id\" type=\"hidden\" value=\"" . $id . "\" /></td>\n";
		echo "<td>\n";
		echo "<input name=\"Submit1\" type=\"submit\" value=\"&nbsp;OK&nbsp;\" style=\"border: 1px solid #CCCCCC\" /></td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		echo "</form>\n";
		include ( "../footer.php" );
	}

	/**
	 * Support_DelS_User()
	 * 
	 * @return
	 */
	function Support_DelS_User()
	{
		global $db, $prefix, $currentlang, $adminfile;
		$id = intval( $_REQUEST['id'] );
		if ( $id )
		{
			$sql = "SELECT title FROM " . $prefix . "_nvsupport_user WHERE language='" . $currentlang . "' AND id=" . $id;
			$result = $db->sql_query( $sql );
			if ( $db->sql_numrows($result) == 1 )
			{
				$row = $db->sql_fetchrow( $result );
				$title = stripslashes( $row['title'] );
				$ok = intval( $_REQUEST['ok'] );
				if ( $ok )
				{
					$db->sql_query( "DELETE FROM " . $prefix . "_nvsupport_user WHERE language='" . $currentlang . "' AND id=" . $id );
				}
				else
				{
					include ( "../header.php" );
					GraphicAdmin();
					Support_Menu();
					title( _SUPPORTADM27 );
					OpenTable();
					echo "<div style=\"font-weight: bold; text-align: center;margin: 10px;\">" . sprintf( _SUPPORTADM28, "\"<u>" . $title . "</u>\"" ) . " ?</div>\n";
					echo "<div style=\"text-align: center\">\n";
					echo "<button name=\"Abutton1\" style=\"width: 70px\" onclick=\"location.href='" . $adminfile . ".php?op=Support_DelS_User&id=" . $id . "&ok=1'\">OK</button>&nbsp;\n";
					echo "<button name=\"Abutton2\" style=\"width: 70px\" onclick=\"location.href='" . $adminfile . ".php?op=Support_ListS_User'\">Cancel</button>\n";
					echo "</div>\n";
					CloseTable();
					include ( "../footer.php" );
					exit();
				}
			}
		}
		Header( "Location: " . $adminfile . ".php?op=Support_ListS_User" );
		exit();
	}

	/**
	 * Support_Ignore()
	 * 
	 * @return
	 */
	function Support_Ignore()
	{
		global $db, $prefix, $currentlang, $adminfile;
		$id = intval( $_REQUEST['id'] );
		if ( $id )
		{
			$db->sql_query( "UPDATE " . $prefix . "_nvsupport_user SET status=2 WHERE id=" . $id . " AND language='" . $currentlang . "'" );
		}
		Header( "Location: " . $adminfile . ".php?op=Support_ListS_User" );
		exit();
	}

	switch ( $op )
	{

		case "Support_List_Cat":
			Support_List_Cat();
			break;
		case "Support_Edit_Cat":
			Support_Edit_Cat();
			break;
		case "Support_Del_Cat":
			Support_Del_Cat();
			break;
		case "Support_ChgW_Cat":
			Support_ChgW_Cat();
			break;
		case "Support_Act_Cat":
			Support_Act_Cat();
			break;
		case "Support_ListS_All":
			Support_ListS_All();
			break;
		case "Support_AddS_All":
			Support_AddS_All();
			break;
		case "Support_EditS_All":
			Support_EditS_All();
			break;
		case "Support_DelS_All":
			Support_DelS_All();
			break;
		case "Support_ListS_User":
			Support_ListS_User();
			break;
		case "Support_EditS_User":
			Support_EditS_User();
			break;
		case "Support_Ignore":
			Support_Ignore();
			break;
		case "Support_DelS_User":
			Support_DelS_User();
			break;
	}

}
else
{
	echo "Access Denied";
}

?>