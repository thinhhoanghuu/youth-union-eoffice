<?php

/*
* @Program:		NukeViet CMS v2.0 RC1
* @File name: 	Support Module
* @Author: 		Nguyen Anh Tu
* @Version: 	1.1
* @Date: 		02.07.2008
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
#############################################

$disable_functions = ini_get( "disable_functions" );
if ( empty($disable_functions) )
{
	$disable_functions = array();
}
else
{
	$disable_functions = split( ',\s*', $disable_functions );
	$disable_functions = array_map( 'trim', $disable_functions );
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
 * Support_CList()
 * 
 * @return
 */
function Support_CList()
{
	global $db, $prefix, $currentlang;
	$list = array();
	$num = array();
	$sql = "SELECT * FROM " . $prefix . "_nvsupport_cat WHERE language='" . $currentlang . "' AND active=1 ORDER BY subid, weight"; //040708
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
		}
	}
	return $list2;
}

/**
 * Support_Header()
 * 
 * @param mixed $listcat
 * @param mixed $checknum
 * @param integer $cat
 * @param string $q
 * @return
 */
function Support_Header( $listcat, $checknum, $cat = 0, $q = '' )
{
	global $module_name;
	echo "<script type=\"text/javascript\">\n";
	echo "	function ssearch(){\n";
	echo "		if(document.getElementById('s_q').value.length > 2 && document.getElementById('s_q').value.length < 100) {\n";
	echo "			document.getElementById('search_incat').value = document.getElementById('s_cat').selectedIndex;\n";
	echo "			document.getElementById('search_query').value = document.getElementById('s_q').value;\n";
	echo "			document.getElementById('search_action').value = '" . $checknum . "';\n";
	echo "			document.getElementById('op').value = 'search';\n";
	echo "			document.getElementById('search_form').submit();\n";
	echo "		} else {\n";
	echo "			alert(\"" . _SUPPORT05 . "\");\n";
	echo "			document.getElementById('s_q').focus();\n";
	echo "		}\n";
	echo "	}\n";
	echo "</script>\n";
	echo "<form id=\"search_form\" method=\"post\" action=\"modules.php?name=" . $module_name . "\">\n";
	echo "<input type=\"hidden\" id=\"search_action\" name=\"search_action\" value=\"\" />\n";
	echo "<input type=\"hidden\" id=\"search_incat\" name=\"search_incat\" value=\"\" />\n";
	echo "<input type=\"hidden\" id=\"search_query\" name=\"search_query\" value=\"\" />\n";
	echo "<input type=\"hidden\" id=\"op\" name=\"op\" value=\"\" />\n";
	echo "</form>\n";
	echo "<table style=\"width: 100%\">\n";
	echo "<tr>\n";
	echo "<td style=\"height: 105px;\">\n";
	echo "<div style=\"margin: 10px; text-align: center; font-family: Arial, Helvetica, sans-serif; font-size: 13px; font-weight: bold\">\n";
	echo _SUPPORT03 . "</div>\n";
	echo "<div style=\"padding: 10px; background-color: #FFC23B;border: 1px solid #CCCCCC;\">\n";
	echo "<div style=\"margin-bottom: 10px; text-align: center;\">\n";
	echo "<input id=\"s_q\" name=\"s_q\" type=\"text\" value=\"" . $q . "\" style=\"border: 1px solid #CCCCCC;width: 90%;\" /></div>\n";
	echo "<div style=\"text-align: center\">\n";
	echo "<select name=\"s_cat\" id=\"s_cat\" style=\"border: 1px solid #CCCCCC;vertical-align: middle\">\n";
	echo "<option value=\"0\">" . _SUPPORT04 . "</option>\n";
	foreach ( $listcat as $k => $v )
	{
		echo "<option value=\"" . $k . "\"" . ( ($k == $cat) ? " selected=\"selected\"" : "" ) . ">" . $v['name'] . "</option>\n";
	}
	echo "</select>\n";
	echo "<button name=\"search_button\" style=\"vertical-align: middle\" onclick=\"ssearch();\">" . _SEARCH . "</button>\n";
	echo "</div>\n";
	echo "</div>\n";

	echo "</td>\n";
	echo "<td style=\"width: 120px;height: 105px; text-align: center; font-family: Arial, Helvetica, sans-serif; font-size: 13px; font-weight: bold;background-color: #E8E8E8;border: 1px solid #CCCCCC;\">\n";
	echo "<a href=\"modules.php?name=" . $module_name . "&amp;op=ulist\"><img alt=\"\" style=\"border-width: 0px\" src=\"images/modules/" . $module_name . "/support.png\" width=\"80\" height=\"82\" /><br />\n";
	echo _SUPPORT02 . "</a></td>\n";
	echo "</tr>\n";
	echo "</table>";
}

/**
 * Support_Main()
 * 
 * @return
 */
function Support_Main()
{
	global $db, $prefix, $currentlang, $module_name;
	include ( "header.php" );
	$listcat = Support_CList();
	if ( $listcat != array() )
	{
		mt_srand( (double)microtime() * 1000000 );
		$maxran = 1000000;
		$checknum = mt_rand( 0, $maxran );
		$checknum = md5( $checknum );
		$_SESSION['support_secure'] = $checknum;
		Support_Header( $listcat, $checknum );
		echo "<br />\n";
		echo "<table style=\"width: 100%\" cellspacing=\"4\" cellpadding=\"4\">\n";
		echo "<tr>\n";
		$a = 0;
		foreach ( $listcat as $key => $value )
		{
			if ( $value['subid'] == '0' )
			{
				$a++;
				echo "<td style=\"width: 50%;vertical-align: top;\">\n";
				echo "<div style=\"margin-bottom: 5px;font-family: Tahoma; font-size: 14px; font-weight: bold\">\n";
				echo "<img alt=\"\" src=\"images/modules/" . $module_name . "/question.gif\" width=\"16\" height=\"16\" style=\"vertical-align: middle; border-width: 0px; margin-right: 5px\" /><a href=\"modules.php?name=" . $module_name . "&amp;op=viewcat&amp;cat=" . $key . "\"><strong>" . $value['title'] . "</strong></a></div>\n";
				$sql = "SELECT id, question FROM " . $prefix . "_nvsupport_all WHERE catid=" . $key . " AND language='" . $currentlang . "' ORDER BY id DESC LIMIT 3";
				$result = $db->sql_query( $sql );
				if ( $db->sql_numrows($result) != 0 )
				{
					while ( $row = $db->sql_fetchrow($result) )
					{
						echo "<div style=\"margin-left: 15px;font-family: Arial; font-size: 12px\">\n";
						echo "<img alt=\"\" src=\"images/modules/" . $module_name . "/list.gif\" width=\"9\" height=\"9\" style=\"vertical-align: middle; border-width: 0px; margin-right: 5px\" /><a href=\"modules.php?name=" . $module_name . "&amp;op=asp&amp;id=" . intval( $row['id'] ) . "\">" . stripslashes( $row['question'] ) . "</a><br />\n";
						echo "</div>\n";
					}
					echo "<div style=\"margin-top: 6px; margin-bottom: 5px; margin-left: 15px\"><a style=\"text-decoration: underline\" href=\"modules.php?name=" . $module_name . "&amp;op=viewcat&amp;cat=" . $key . "\">" . _SUPPORT01 . "&nbsp;&raquo;</a></div>\n";
				}
				echo "</td>\n";
				if ( $a == 2 )
				{
					echo "</tr>\n<tr>\n";
					$a = 0;
				}
			}
		}
		echo "</tr>\n";
		echo "</table>\n";
		$listcatkeys = implode( ",", array_keys($listcat) ); //040708
		$sql = "SELECT id, question, view FROM " . $prefix . "_nvsupport_all WHERE language='" . $currentlang . "' AND catid IN (" . $listcatkeys . ") ORDER BY view DESC LIMIT 10"; //040708
		$result = $db->sql_query( $sql );
		echo "<table style=\"width: 100%\">\n";
		echo "<tr>\n";
		echo "<td style=\"border-style: solid; border-width: 1px 0px 0px 0px; border-color: #CCCCCC;vertical-align: top;\">\n";
		echo "<div style=\"margin-top: 5px;margin-bottom: 5px;font-family: Arial, Helvetica, sans-serif; font-size: 13px; font-weight: bold\">" . _SUPPORT06 . "</div>\n";
		while ( $row = $db->sql_fetchrow($result) )
		{
			echo "<div style=\"margin-left: 15px;margin-bottom: 3px;font-family: Arial; font-size: 12px\">\n";
			echo "<img alt=\"\" src=\"images/modules/" . $module_name . "/hits.gif\" width=\"10\" height=\"10\" style=\"vertical-align: middle; margin-right: 5px\" />\n";
			echo "<a href=\"modules.php?name=" . $module_name . "&amp;op=asp&amp;id=" . intval( $row['id'] ) . "\">" . stripslashes( $row['question'] ) . "</a>";
			if ( defined('IS_ADMMOD') )
			{
				echo " (" . _SUPPORT15 . ": " . intval( $row['view'] ) . ")";
			}
			echo "</div>";
		}
		echo "</td>\n";
		if ( defined('IS_USER') )
		{
			echo "<td style=\"border: 1px solid #CCCCCC;width: 260px; vertical-align: top; background-color: #E8E8E8;\">\n";
			echo "<script type=\"text/javascript\">\n";
			echo "	function uquestion(Forma){\n";
			echo "		if(Forma.u_subject.value.length <= 2) {\n";
			echo "			alert(\"" . _SUPPORT11 . "\");\n";
			echo "			Forma.u_subject.focus();\n";
			echo "			return false;\n";
			echo "		} else if(Forma.u_question.value.length <= 2) {\n";
			echo "			alert(\"" . _SUPPORT12 . "\");\n";
			echo "			Forma.u_question.focus();\n";
			echo "			return false;\n";
			echo "		} else {\n";
			echo "			Forma.op.value=\"addnew\";\n";
			echo "			Forma.save.value=\"1\";\n";
			echo "			Forma.u_action.value=\"" . $checknum . "\";\n";
			echo "			Forma.u_submit.disabled=true;\n";
			echo "			return true;\n";
			echo "		}\n";
			echo "	}\n";
			echo "</script>\n";
			echo "<form onsubmit=\"return uquestion(this)\" method=\"post\" action=\"modules.php?name=" . $module_name . "\">\n";
			echo "<input type=\"hidden\" id=\"op\" name=\"op\" value=\"\" />\n";
			echo "<input type=\"hidden\" id=\"save\" name=\"save\" value=\"0\" />\n";
			echo "<input type=\"hidden\" id=\"u_action\" name=\"u_action\" value=\"\" />\n";
			echo "<div style=\"margin: 5px;font-family: Arial, Helvetica, sans-serif; font-size: 13px; font-weight: bold\">" . _SUPPORT07 . "</div>\n";
			echo "<div style=\"padding: 5px; margin-bottom: 2px\">" . _SUPPORT08 . ":<br />\n";
			echo "<input name=\"u_subject\" id=\"u_subject\" type=\"text\" style=\"border: 1px solid #CCCCCC; width: 250px\" /></div>\n";
			echo "<div style=\"padding: 5px; margin-bottom: 2px\">" . _SUPPORT10 . ":<br />\n";
			echo "<select name=\"u_catid\" id=\"u_catid\" style=\"border: 1px solid #CCCCCC;vertical-align: middle\">\n";
			foreach ( $listcat as $k => $v )
			{
				echo "<option value=\"" . $k . "\">" . $v['name'] . "</option>\n";
			}
			echo "</select></div>\n";
			echo "<div style=\"padding: 5px; margin-bottom: 2px\">" . _SUPPORT09 . ":<br />\n";
			echo "<textarea name=\"u_question\" id=\"u_question\" cols=\"20\" rows=\"10\" style=\"border: 1px solid #CCCCCC; width: 250px\"></textarea></div>\n";
			echo "<div style=\"padding: 5px;text-align:center\">\n";
			echo "<input type=\"submit\" id=\"u_submit\" name=\"u_submit\" value=\"&nbsp;" . _SUPPORT13 . "&nbsp;\" style=\"border: 1px solid #CCCCCC;\" /></div>\n";
			echo "</form>\n";
			echo "</td>\n";
		}
		echo "</tr>\n";
		echo "</table>\n";
	}
	include ( "footer.php" );
}

/**
 * Support_ViewCat()
 * 
 * @return
 */
function Support_ViewCat()
{
	global $db, $prefix, $currentlang, $module_name, $module_title;
	$listcat = Support_CList();
	if ( $listcat == array() )
	{
		Header( "Location: modules.php?name=" . $module_name );
		exit();
	}
	$cat = intval( $_GET['cat'] );
	if ( ! $cat || ! isset($listcat[$cat]) )
	{
		Header( "Location: modules.php?name=" . $module_name );
		exit();
	}
	include ( "header.php" );
	mt_srand( (double)microtime() * 1000000 );
	$maxran = 1000000;
	$checknum = mt_rand( 0, $maxran );
	$checknum = md5( $checknum );
	$_SESSION['support_secure'] = $checknum;
	Support_Header( $listcat, $checknum, $cat );
	echo "<br />\n";
	echo "<div style=\"font-weight: bold; font-size: 12px; padding: 5px; margin-top: 5px; margin-bottom: 3px\">\n";
	echo "<a href=\"modules.php?name=" . $module_name . "\">" . $module_title . "</a> &raquo; ";
	if ( $listcat[$cat]['subid'] )
	{
		$subid = intval( $listcat[$cat]['subid'] );
		echo "<a href=\"modules.php?name=" . $module_name . "&amp;op=viewcat&amp;cat=" . $subid . "\">" . $listcat[$subid]['title'] . "</a> &raquo; ";
	}
	echo $listcat[$cat]['title'];
	echo "</div>\n";
	if ( isset($listcat[$cat]['sublist']) and $listcat[$cat]['sublist'] != array() )
	{
		echo "<div style=\"padding: 5px;margin-bottom: 10px\">" . _SUPPORT14 . ":</div>\n";
		foreach ( $listcat[$cat]['sublist'] as $key )
		{
			echo "<div style=\"margin-left: 5px;margin-bottom: 3px;font-family: Tahoma; font-size: 12px; font-weight: bold\">\n";
			echo "<img alt=\"\" src=\"images/modules/" . $module_name . "/question.gif\" width=\"16\" height=\"16\" style=\"vertical-align: middle; border-width: 0px; margin-right: 5px\" /><a href=\"modules.php?name=" . $module_name . "&amp;op=viewcat&amp;cat=" . $key . "\"><strong>" . $listcat[$key]['title'] . "</strong></a></div>\n";
		}
	}
	$sql = "SELECT id, question FROM " . $prefix . "_nvsupport_all WHERE catid=" . $cat . " AND language='" . $currentlang . "'";
	$result = $db->sql_query( $sql );
	if ( $db->sql_numrows($result) != 0 )
	{
		echo "<div style=\"padding: 5px;margin-top: 15px\">\n";
		while ( $row = $db->sql_fetchrow($result) )
		{
			echo "<div style=\"margin-left: 15px;font-family: Arial; font-size: 12px\">\n";
			echo "<img alt=\"\" src=\"images/modules/" . $module_name . "/list.gif\" width=\"9\" height=\"9\" style=\"vertical-align: middle; border-width: 0px; margin-right: 5px\" /><a href=\"modules.php?name=" . $module_name . "&amp;op=asp&amp;id=" . intval( $row['id'] ) . "\">" . stripslashes( $row['question'] ) . "</a><br />\n";
			echo "</div>\n";
		}
		echo "</div>\n";
	}
	include ( "footer.php" );
}

/**
 * Support_AllSupport()
 * 
 * @return
 */
function Support_AllSupport()
{
	global $db, $prefix, $currentlang, $module_name, $module_title, $adminfold, $adminfile;
	$listcat = Support_CList();
	if ( $listcat == array() )
	{
		Header( "Location: modules.php?name=" . $module_name );
		exit();
	}
	$id = intval( $_GET['id'] );
	if ( ! $id )
	{
		Header( "Location: modules.php?name=" . $module_name );
		exit();
	}
	$sql = "SELECT * FROM " . $prefix . "_nvsupport_all WHERE id=" . $id . " AND language='" . $currentlang . "'";
	$result = $db->sql_query( $sql );
	if ( $db->sql_numrows($result) == 0 )
	{
		Header( "Location: modules.php?name=" . $module_name );
		exit();
	}
	$row = $db->sql_fetchrow( $result ); //040708_st
	$catid = intval( $row['catid'] );
	if ( ! $catid || ! isset($listcat[$catid]) )
	{
		Header( "Location: modules.php?name=" . $module_name );
		exit();
	}
	$db->sql_query( "UPDATE " . $prefix . "_nvsupport_all SET view=view+1 WHERE id=" . $id . " AND language='" . $currentlang . "'" ); //040708_end
	include ( "header.php" );
	mt_srand( (double)microtime() * 1000000 );
	$maxran = 1000000;
	$checknum = mt_rand( 0, $maxran );
	$checknum = md5( $checknum );
	$_SESSION['support_secure'] = $checknum;
	Support_Header( $listcat, $checknum, $catid );
	echo "<br />\n";
	echo "<div style=\"font-weight: bold; font-size: 12px; padding: 5px; margin-top: 5px; margin-bottom: 3px\">\n";
	echo "<a href=\"modules.php?name=" . $module_name . "\">" . $module_title . "</a> &raquo; ";
	if ( $listcat[$catid]['subid'] )
	{
		$subid = intval( $listcat[$cat]['subid'] );
		echo "<a href=\"modules.php?name=" . $module_name . "&amp;op=viewcat&amp;cat=" . $subid . "\">" . $listcat[$subid]['title'] . "</a> &raquo; ";
	}
	echo "<a href=\"modules.php?name=" . $module_name . "&amp;op=viewcat&amp;cat=" . $catid . "\">" . $listcat[$catid]['title'] . "</a>";
	echo "</div>\n";
	$question = stripslashes( $row['question'] );
	$answer = stripslashes( $row['answer'] );
	if ( isset($_GET['highlight']) and ! empty($_GET['highlight']) )
	{
		$highlight = nv_substr( at_htmlspecialchars(strip_tags(stripslashes(trim(rawurldecode($_GET['highlight']))))), 0, 100 );
		$pattern = "/" . $highlight . "/i";
		$replacement = "<span style=\"background-color: #FFFF00;\">" . $highlight . "</span>";
		$question = preg_replace( $pattern, $replacement, $question );
		$answer = preg_replace( $pattern, $replacement, $answer );
	}
	echo "<div style=\"padding: 5px;margin-top: 5px;font-weight: bold; font-size: 12px;\">\n";
	echo "<img alt=\"\" src=\"images/modules/" . $module_name . "/question2.gif\" width=\"16\" height=\"16\" style=\"vertical-align: middle; border-width: 0px; margin-right: 5px\" />" . $question;
	echo "</div>\n";
	echo "<div style=\"padding: 5px;margin-bottom: 10px;font-size: 12px;\">\n";
	echo $answer;
	echo "</div>\n";
	if ( defined('IS_ADMMOD') )
	{
		echo "<div style=\"padding: 5px;margin-bottom: 10px;text-align: right;font-weight: bold; font-size: 12px;\">\n";
		echo "<a href=\"" . $adminfold . "/" . $adminfile . ".php?op=Support_EditS_All&amp;id=" . $id . "\">" . _EDIT . "</a>&nbsp;|&nbsp;<a href=\"" . $adminfold . "/" . $adminfile . ".php?op=Support_DelS_All&amp;id=" . $id . "\">" . _DELETE . "</a>";
		echo "</div>\n";
	}

	$sql = "SELECT id, question, view FROM " . $prefix . "_nvsupport_all WHERE id!=" . $id . " AND catid=" . $catid . " AND language='" . $currentlang . "' ORDER BY view DESC LIMIT 10";
	$result = $db->sql_query( $sql );
	echo "<table style=\"width: 100%\">\n";
	echo "<tr>\n";
	echo "<td style=\"border-style: solid; border-width: 1px 0px 0px 0px; border-color: #CCCCCC;vertical-align: top;\">\n";
	echo "<div style=\"margin-top: 5px;margin-bottom: 5px;font-family: Arial, Helvetica, sans-serif; font-size: 13px; font-weight: bold\">" . _SUPPORT06 . "</div>\n";
	while ( $row = $db->sql_fetchrow($result) )
	{
		echo "<div style=\"margin-left: 15px;margin-bottom: 3px;font-family: Arial; font-size: 12px\">\n";
		echo "<img alt=\"\" src=\"images/modules/" . $module_name . "/hits.gif\" width=\"10\" height=\"10\" style=\"vertical-align: middle; margin-right: 5px\" />\n";
		echo "<a href=\"modules.php?name=" . $module_name . "&amp;op=asp&amp;id=" . intval( $row['id'] ) . "\">" . stripslashes( $row['question'] ) . "</a>";
		if ( defined('IS_ADMMOD') )
		{
			echo " (" . _SUPPORT15 . ": " . intval( $row['view'] ) . ")";
		}
		echo "</div>";
	}
	echo "</td>\n";
	if ( defined('IS_USER') )
	{
		echo "<td style=\"border: 1px solid #CCCCCC;width: 260px; vertical-align: top; background-color: #E8E8E8;\">\n";
		echo "<script type=\"text/javascript\">\n";
		echo "	function uquestion(Forma){\n";
		echo "		if(Forma.u_subject.value.length <= 2) {\n";
		echo "			alert(\"" . _SUPPORT11 . "\");\n";
		echo "			Forma.u_subject.focus();\n";
		echo "			return false;\n";
		echo "		} else if(Forma.u_question.value.length <= 2) {\n";
		echo "			alert(\"" . _SUPPORT12 . "\");\n";
		echo "			Forma.u_question.focus();\n";
		echo "			return false;\n";
		echo "		} else {\n";
		echo "			Forma.op.value=\"addnew\";\n";
		echo "			Forma.save.value=\"1\";\n";
		echo "			Forma.u_action.value=\"" . $checknum . "\";\n";
		echo "			Forma.u_submit.disabled=true;\n";
		echo "			return true;\n";
		echo "		}\n";
		echo "	}\n";
		echo "</script>\n";
		echo "<form onsubmit=\"return uquestion(this)\" method=\"post\" action=\"modules.php?name=" . $module_name . "\">\n";
		echo "<input type=\"hidden\" id=\"op\" name=\"op\" value=\"\" />\n";
		echo "<input type=\"hidden\" id=\"save\" name=\"save\" value=\"0\" />\n";
		echo "<input type=\"hidden\" id=\"u_action\" name=\"u_action\" value=\"\" />\n";
		echo "<div style=\"margin: 5px;font-family: Arial, Helvetica, sans-serif; font-size: 13px; font-weight: bold\">" . _SUPPORT07 . "</div>\n";
		echo "<div style=\"padding: 5px; margin-bottom: 2px\">" . _SUPPORT08 . ":<br />\n";
		echo "<input name=\"u_subject\" id=\"u_subject\" type=\"text\" style=\"border: 1px solid #CCCCCC; width: 250px\" /></div>\n";
		echo "<div style=\"padding: 5px; margin-bottom: 2px\">" . _SUPPORT10 . ":<br />\n";
		echo "<select name=\"u_catid\" id=\"u_catid\" style=\"border: 1px solid #CCCCCC;vertical-align: middle\">\n";
		foreach ( $listcat as $k => $v )
		{
			echo "<option value=\"" . $k . "\"" . ( ($k == $catid) ? " selected=\"selected\"" : "" ) . ">" . $v['name'] . "</option>\n";
		}
		echo "</select></div>\n";
		echo "<div style=\"padding: 5px; margin-bottom: 2px\">" . _SUPPORT09 . ":<br />\n";
		echo "<textarea name=\"u_question\" id=\"u_question\" cols=\"20\" rows=\"10\" style=\"border: 1px solid #CCCCCC; width: 250px\"></textarea></div>\n";
		echo "<div style=\"padding: 5px;text-align:center\">\n";
		echo "<input type=\"submit\" id=\"u_submit\" name=\"u_submit\" value=\"&nbsp;" . _SUPPORT13 . "&nbsp;\" style=\"border: 1px solid #CCCCCC;\" /></div>\n";
		echo "</form>\n";
		echo "</td>\n";
	}
	echo "</tr>\n";
	echo "</table>\n";
	echo "<br />\n";
	include ( "footer.php" );
}

/**
 * nv_substr()
 * 
 * @param mixed $string
 * @param mixed $start
 * @param mixed $length
 * @return
 */
function nv_substr( $string, $start, $length )
{
	global $disable_functions;
	if ( function_exists('mb_substr') and ! in_array('mb_substr', $disable_functions) )
	{
		return mb_substr( $string, $start, $length, "UTF-8" );
	}
	else
	{
		return substr( $string, $start, $length );
	}
}

/**
 * nv_strlen()
 * 
 * @param mixed $string
 * @return
 */
function nv_strlen( $string )
{
	global $disable_functions;
	if ( function_exists('mb_strlen') and ! in_array('mb_strlen', $disable_functions) )
	{
		return mb_strlen( $string, "UTF-8" );
	}
	else
	{
		return strlen( $string );
	}
}

/**
 * Support_Search()
 * 
 * @return
 */
function Support_Search()
{
	global $db, $prefix, $currentlang, $module_name, $module_title;
	$listcat = Support_CList();
	if ( $listcat == array() )
	{
		Header( "Location: modules.php?name=" . $module_name );
		exit();
	}
	$search_incat = intval( $_POST['search_incat'] );
	$search_query = stripslashes( $_POST['search_query'] );
	$search_action = stripslashes( $_POST['search_action'] );
	$support_secure = $_SESSION['support_secure'];
	if ( $search_action != $support_secure )
	{
		Header( "Location: modules.php?name=" . $module_name );
		exit();
	}
	$search_query = nv_substr( at_htmlspecialchars(strip_tags(stripslashes(trim($search_query)))), 0, 100 );
	$res = "";
	$strlen = nv_strlen( $search_query );
	if ( $strlen >= 3 and $strlen <= 100 )
	{
		$listcatkeys = implode( ",", array_keys($listcat) ); //040708
		$sql = "SELECT id, question FROM " . $prefix . "_nvsupport_all WHERE language='" . $currentlang . "' AND catid IN (" . $listcatkeys . ")"; //040708
		if ( $search_incat and isset($listcat[$search_incat]) )
		{
			$sql .= " AND catid=" . $search_incat;
		}
		$sql .= " AND (question LIKE '%" . $search_query . "%' OR answer LIKE '%" . $search_query . "%') ORDER BY id DESC";
		$result = $db->sql_query( $sql );
		if ( $db->sql_numrows($result) != 0 )
		{
			while ( $row = $db->sql_fetchrow($result) )
			{
				$question = stripslashes( $row['question'] );
				$pattern = "/" . $search_query . "/i";
				$replacement = "<span style=\"background-color: #FFFF00;\">" . $search_query . "</span>";
				$question = preg_replace( $pattern, $replacement, $question );
				$res .= "<div style=\"margin-left: 15px;font-family: Arial; font-size: 12px\">\n";
				$res .= "<img alt=\"\" src=\"images/modules/" . $module_name . "/list.gif\" width=\"9\" height=\"9\" style=\"vertical-align: middle; border-width: 0px; margin-right: 5px\" /><a href=\"modules.php?name=" . $module_name . "&amp;op=asp&amp;id=" . intval( $row['id'] ) . "&amp;highlight=" . rawurlencode( $search_query ) . "\">" . $question . "</a><br />\n";
				$res .= "</div>\n";
			}
		}
	}
	include ( "header.php" );
	mt_srand( (double)microtime() * 1000000 );
	$maxran = 1000000;
	$checknum = mt_rand( 0, $maxran );
	$checknum = md5( $checknum );
	$_SESSION['support_secure'] = $checknum;
	Support_Header( $listcat, $checknum, $search_incat, $search_query );
	echo "<br />\n";
	echo "<div style=\"font-weight: bold; font-size: 12px; padding: 5px; margin-top: 5px; margin-bottom: 3px\">\n";
	echo "<a href=\"modules.php?name=" . $module_name . "\">" . $module_title . "</a> &raquo; " . _SUPPORT16;
	echo "</div>\n";
	if ( $res == "" )
	{
		echo "<div style=\"font-weight: bold;text-align:center\">" . _SUPPORT17 . "</div>\n";
	}
	else
	{
		echo $res;
	}
	include ( "footer.php" );
}

/**
 * Support_UAddNew()
 * 
 * @return
 */
function Support_UAddNew()
{
	global $db, $prefix, $currentlang, $module_name, $module_title;
	if ( ! defined('IS_USER') )
	{
		Header( "Location: modules.php?name=" . $module_name );
		exit();
	}
	$listcat = Support_CList();
	if ( $listcat == array() )
	{
		Header( "Location: modules.php?name=" . $module_name );
		exit();
	}
	$u_action = stripslashes( trim($_POST['u_action']) );
	$u_subject = at_htmlspecialchars( strip_tags(stripslashes(trim($_POST['u_subject']))) );
	$u_catid = intval( $_POST['u_catid'] );
	$u_question = cheonguoc( stripslashes(FixQuotes(trim($_POST['u_question']))) );
	$save = intval( $_POST['save'] );
	$error = "";
	if ( $save )
	{
		if ( $u_action != $_SESSION['support_secure'] )
		{
			Header( "Location: modules.php?name=" . $module_name );
			exit();
		} elseif ( $u_subject == "" )
		{
			$error = _SUPPORT11;
		} elseif ( ! $u_catid || ! isset($listcat[$u_catid]) )
		{
			$error = _SUPPORT18;
		} elseif ( $u_question == "" )
		{
			$error = _SUPPORT12;
		}
		else
		{
			global $mbrow, $client_ip;
			$sendername = stripslashes( $mbrow['username'] );
			$senderemail = stripslashes( $mbrow['user_email'] );
			$senderip = $client_ip;
			$questiontime = time();
			list( $lastsend ) = $db->sql_fetchrow( $db->sql_query("SELECT questiontime FROM " . $prefix . "_nvsupport_user WHERE sendername='" . $sendername . "' ORDER BY id DESC LIMIT 1") );
			$lastsend = intval( $lastsend );
			if ( $questiontime - $lastsend < 60 )
			{
				$error = _SUPPORT19;
			}
			else
			{
				$u_question = nl2br( $u_question );
				$db->sql_query( "INSERT INTO " . $prefix . "_nvsupport_user VALUES (
				NULL, " . $u_catid . ", 0, 1, '" . $currentlang . "', '" . $u_subject . "', '" . $u_question . "', '', " . $questiontime . ", 0, '" . $sendername . "', '" . $senderemail . "', '" . $senderip . "')" );
				Header( "Location: modules.php?name=" . $module_name . "&op=ulist" );
				exit();
			}
		}
	}

	include ( "header.php" );
	mt_srand( (double)microtime() * 1000000 );
	$maxran = 1000000;
	$checknum = mt_rand( 0, $maxran );
	$checknum = md5( $checknum );
	$_SESSION['support_secure'] = $checknum;
	echo "<script type=\"text/javascript\">\n";
	echo "	function uquestion(Forma){\n";
	echo "		if(Forma.u_subject.value.length <= 2) {\n";
	echo "			alert(\"" . _SUPPORT11 . "\");\n";
	echo "			Forma.u_subject.focus();\n";
	echo "			return false;\n";
	echo "		} else if(Forma.u_question.value.length <= 2) {\n";
	echo "			alert(\"" . _SUPPORT12 . "\");\n";
	echo "			Forma.u_question.focus();\n";
	echo "			return false;\n";
	echo "		} else {\n";
	echo "			Forma.op.value=\"addnew\";\n";
	echo "			Forma.save.value=\"1\";\n";
	echo "			Forma.u_action.value=\"" . $checknum . "\";\n";
	echo "			Forma.u_submit.disabled=true;\n";
	echo "			return true;\n";
	echo "		}\n";
	echo "	}\n";
	echo "</script>\n";
	echo "<div style=\"font-weight: bold; font-size: 12px; padding: 5px; margin-top: 5px; margin-bottom: 3px\">\n";
	echo "<a href=\"modules.php?name=" . $module_name . "\">" . $module_title . "</a> &raquo; " . _SUPPORT07 . "</div>\n";
	echo "<form onsubmit=\"return uquestion(this)\" method=\"post\" action=\"modules.php?name=" . $module_name . "\">\n";
	echo "<input type=\"hidden\" id=\"op\" name=\"op\" value=\"\" />\n";
	echo "<input type=\"hidden\" id=\"save\" name=\"save\" value=\"0\" />\n";
	echo "<input type=\"hidden\" id=\"u_action\" name=\"u_action\" value=\"\" />\n";
	if ( $error != "" )
	{
		echo "<div style=\"font-weight: bold; color: #FF3300; text-align: center;margin-bottom: 10px;\">" . $error . "</div>\n";
	}
	echo "<div style=\"text-align: center;padding: 5px; margin-bottom: 2px\">" . _SUPPORT08 . ":<br />\n";
	echo "<input name=\"u_subject\" id=\"u_subject\" value=\"" . $u_subject . "\" type=\"text\" style=\"border: 1px solid #CCCCCC; width: 250px\" /></div>\n";
	echo "<div style=\"text-align: center;padding: 5px; margin-bottom: 2px\">" . _SUPPORT10 . ":<br />\n";
	echo "<select name=\"u_catid\" id=\"u_catid\" style=\"border: 1px solid #CCCCCC;vertical-align: middle\">\n";
	foreach ( $listcat as $k => $v )
	{
		echo "<option value=\"" . $k . "\"" . ( ($k == $u_catid) ? " selected=\"selected\"" : "" ) . ">" . $v['name'] . "</option>\n";
	}
	echo "</select></div>\n";
	echo "<div style=\"text-align: center;padding: 5px; margin-bottom: 2px\">" . _SUPPORT09 . ":<br />\n";
	echo "<textarea name=\"u_question\" id=\"u_question\" cols=\"20\" rows=\"10\" style=\"border: 1px solid #CCCCCC; width: 250px\">" . $u_question . "</textarea></div>\n";
	echo "<div style=\"padding: 5px;text-align:center\">\n";
	echo "<input type=\"submit\" id=\"u_submit\" name=\"u_submit\" value=\"&nbsp;" . _SUPPORT13 . "&nbsp;\" style=\"border: 1px solid #CCCCCC;\" /></div>\n";
	echo "</form>\n";
	include ( "footer.php" );
}

/**
 * Support_Ulist()
 * 
 * @return
 */
function Support_Ulist()
{
	global $db, $prefix, $currentlang, $module_name, $module_title;
	$listcat = Support_CList();
	if ( $listcat == array() )
	{
		Header( "Location: modules.php?name=" . $module_name );
		exit();
	}
	include ( "header.php" );
	if ( ! defined('IS_USER') )
	{
		OpenTable();
		echo "<div style=\"font-family: Arial, Helvetica, sans-serif; font-size: 13px;font-weight: bold; text-align: center;margin: 10px;\">" . _SUPPORT20 . "</div>\n";
		CloseTable();
	}
	else
	{
		$ptitle = _SUPPORT21;
		$base_url = "modules.php?name=" . $module_name . "&amp;op=ulist";
		global $mbrow;
		$sendername = stripslashes( $mbrow['username'] );
		$listcatkeys = implode( ",", array_keys($listcat) ); //040708
		$sql = "FROM " . $prefix . "_nvsupport_user WHERE sendername='" . $sendername . "' AND language='" . $currentlang . "' AND catid IN (" . $listcatkeys . ")"; //040708
		$wh = intval( $_REQUEST['wh'] );
		if ( $wh == 1 )
		{
			$sql .= " AND status=1";
			$ptitle .= ' ' . _SUPPORT22;
			$base_url .= "&amp;wh=1";
		} elseif ( $wh == 2 )
		{
			$sql .= " AND status!=1";
			$ptitle .= ' ' . _SUPPORT23;
			$base_url .= "&amp;wh=2";
		}
		echo "<div style=\"font-weight: bold; font-size: 12px; padding: 5px; margin-top: 5px; margin-bottom: 3px\">\n";
		echo "<a href=\"modules.php?name=" . $module_name . "\">" . $module_title . "</a> &raquo; " . $ptitle . "</div>\n";
		echo "<div style=\"font-family: Arial, Helvetica, sans-serif; font-size: 13px;font-weight: bold; margin: 10px;\">( <a href=\"modules.php?name=" . $module_name . "&amp;op=addnew\">" . _SUPPORT07 . "</a> )</div>\n";
		$numf = $db->sql_fetchrow( $db->sql_query("SELECT COUNT(*) " . $sql) );
		if ( $numf[0] )
		{
			$page = ( isset($_GET['page']) ) ? intval( $_GET['page'] ) : 0;
			$all_page = ( $numf[0] ) ? $numf[0] : 1;
			$per_page = 30;
			$sql = "SELECT * " . $sql . " ORDER BY view ASC, id DESC LIMIT $page,$per_page";
			$result = $db->sql_query( $sql );
			if ( $db->sql_numrows($result) != 0 )
			{
				echo "<table style=\"border-collapse: collapse;border: 1px solid #CCCCCC; width: 100%\">\n";
				echo "<tr>\n";
				echo "<td style=\"padding:1px\">\n";
				echo "<table style=\"border-collapse: collapse;width: 100%\">\n";
				echo "<tr style=\"background-color: #E4E4E4;font-weight: bold\">\n";
				echo "<td style=\"border-style: solid; border-width: 0px 1px 0px 0px; border-color: #CCCCCC;padding: 5px; text-align: center;white-space: nowrap;\">\n";
				echo _SUPPORT24 . "</td>\n";
				echo "<td style=\"border-style: solid; border-width: 0px 1px 0px 0px; border-color: #CCCCCC;padding: 5px; text-align: left;white-space: nowrap;\">\n";
				echo _SUPPORT08 . "</td>\n";
				echo "<td style=\"border-style: solid; border-width: 0px 1px 0px 0px; border-color: #CCCCCC;padding: 5px; text-align: left;white-space: nowrap;\">\n";
				echo _SUPPORT10 . "</td>\n";
				echo "<td style=\"padding: 5px; text-align: center;white-space: nowrap;\">\n";
				echo _SUPPORT25 . "</td>\n";
				echo "</tr>\n";
				$a = 0;
				while ( $row = $db->sql_fetchrow($result) )
				{
					$id = intval( $row['id'] );
					$bgcolor = ( $a % 2 == 0 ) ? "#FFFFF" : "#E4E4E4";
					echo "<tr style=\"background-color: " . $bgcolor . ";" . ( ($row['view'] == '0') ? "font-weight: bold" : "" ) . "\">\n";
					echo "<td style=\"border-style: solid; border-width: 1px 1px 0px 0px; border-color: #CCCCCC;padding: 5px; text-align: center; white-space: nowrap;\">\n";
					echo viewtime( $row['questiontime'], 2 ) . "</td>\n";
					echo "<td style=\"border-style: solid; border-width: 1px 1px 0px 0px; border-color: #CCCCCC; padding: 5px; width: 90%; text-align: left;\">\n";
					echo "<a href=\"modules.php?name=" . $module_name . "&amp;op=usp&amp;ticket=" . $id . "\">" . stripslashes( $row['title'] ) . "</a></td>\n";
					echo "<td style=\"border-style: solid; border-width: 1px 1px 0px 0px; border-color: #CCCCCC;padding: 5px; text-align: center; white-space: nowrap;\">\n";
					echo "<a href=\"modules.php?name=" . $module_name . "&amp;op=viewcat&amp;cat=" . intval( $row['catid'] ) . "\">" . $listcat[intval( $row['catid'] )]['title'] . "</a></td>\n";
					echo "<td style=\"border-style: solid; border-width: 1px 0px 1px 0px; border-color: #CCCCCC;padding: 5px; text-align: center; white-space: nowrap;\">\n";
					echo ( ($row['status'] != '1') ? _SUPPORT23 : _SUPPORT22 ) . "</td>\n";
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
			echo "<form method=\"post\" action=\"modules.php?name=" . $module_name . "\">\n";
			echo "<input type=\"hidden\" id=\"op\" name=\"op\" value=\"ulist\" />\n";
			echo "<div style=\"text-align: center;margin-top: 15px;\">\n";
			echo _SUPPORT26 . ": <select name=\"wh\" style=\"vertical-align: middle; border: 1px solid #CCCCCC\">\n";
			echo "<option value=\"3\">" . _SUPPORT27 . "</option>\n";
			$array = array( '', _SUPPORT22, _SUPPORT23 );
			for ( $i = 1; $i <= 2; $i++ )
			{
				echo "<option value=\"" . $i . "\"" . ( ($i == $wh) ? " selected=\"selected\"" : "" ) . ">" . $array[$i] . "</option>\n";
			}
			echo "</select><input name=\"Submit1\" type=\"submit\" value=\"&nbsp;OK&nbsp;\" style=\"vertical-align: middle; border: 1px solid #CCCCCC\" /></div>\n";
			echo "</form><br />\n";
		}
	}
	include ( "footer.php" );
}

/**
 * Support_USupport()
 * 
 * @return
 */
function Support_USupport()
{
	global $db, $prefix, $currentlang, $module_name, $module_title;
	if ( ! defined('IS_USER') )
	{
		include ( "header.php" );
		OpenTable();
		echo "<div style=\"font-family: Arial, Helvetica, sans-serif; font-size: 13px;font-weight: bold; text-align: center;margin: 10px;\">" . _SUPPORT20 . "</div>\n";
		CloseTable();
		include ( "footer.php" );
		exit;
	}
	$listcat = Support_CList();
	if ( $listcat == array() )
	{
		Header( "Location: modules.php?name=" . $module_name );
		exit();
	}
	$id = intval( $_GET['ticket'] );
	if ( ! $id )
	{
		Header( "Location: modules.php?name=" . $module_name );
		exit();
	}
	global $mbrow;
	$sendername = stripslashes( $mbrow['username'] );
	$sql = "SELECT * FROM " . $prefix . "_nvsupport_user WHERE id=" . $id . " AND sendername='" . $sendername . "' AND language='" . $currentlang . "'";
	$result = $db->sql_query( $sql );
	if ( $db->sql_numrows($result) != 1 )
	{
		Header( "Location: modules.php?name=" . $module_name );
		exit();
	}
	$row = $db->sql_fetchrow( $result ); //040708_st
	$catid = intval( $row['catid'] );
	if ( ! $catid || ! isset($listcat[$catid]) )
	{
		Header( "Location: modules.php?name=" . $module_name );
		exit();
	}
	if ( $row['view'] != '1' )
	{
		$db->sql_query( "UPDATE " . $prefix . "_nvsupport_user SET view=1 WHERE id=" . $id . " AND language='" . $currentlang . "'" );
	} //040708_end
	include ( "header.php" );
	echo "<div style=\"font-weight: bold; font-size: 12px; padding: 5px; margin-top: 5px; margin-bottom: 3px\">\n";
	echo "<a href=\"modules.php?name=" . $module_name . "\">" . $module_title . "</a> &raquo; ";
	echo "<a href=\"modules.php?name=" . $module_name . "&amp;op=ulist\">" . _SUPPORT21 . "</a>";
	echo "</div>\n";
	echo "<div style=\"padding: 5px;margin-top: 5px;font-weight: bold; font-size: 12px;\">\n";
	echo "<img alt=\"\" src=\"images/modules/" . $module_name . "/question2.gif\" width=\"16\" height=\"16\" style=\"vertical-align: middle; border-width: 0px; margin-right: 5px\" />" . stripslashes( $row['question'] );
	echo "</div>\n";
	echo "<div style=\"padding: 5px;margin-bottom: 10px;font-size: 12px;\">\n";
	echo stripslashes( $row['answer'] );
	echo "</div>\n";
	echo "<hr />\n";
	mt_srand( (double)microtime() * 1000000 );
	$maxran = 1000000;
	$checknum = mt_rand( 0, $maxran );
	$checknum = md5( $checknum );
	$_SESSION['support_secure'] = $checknum;
	echo "<script type=\"text/javascript\">\n";
	echo "	function uquestion(Forma){\n";
	echo "		if(Forma.u_subject.value.length <= 2) {\n";
	echo "			alert(\"" . _SUPPORT11 . "\");\n";
	echo "			Forma.u_subject.focus();\n";
	echo "			return false;\n";
	echo "		} else if(Forma.u_question.value.length <= 2) {\n";
	echo "			alert(\"" . _SUPPORT12 . "\");\n";
	echo "			Forma.u_question.focus();\n";
	echo "			return false;\n";
	echo "		} else {\n";
	echo "			Forma.op.value=\"addnew\";\n";
	echo "			Forma.save.value=\"1\";\n";
	echo "			Forma.u_action.value=\"" . $checknum . "\";\n";
	echo "			Forma.u_submit.disabled=true;\n";
	echo "			return true;\n";
	echo "		}\n";
	echo "	}\n";
	echo "</script>\n";
	echo "<form onsubmit=\"return uquestion(this)\" method=\"post\" action=\"modules.php?name=" . $module_name . "\">\n";
	echo "<input type=\"hidden\" id=\"op\" name=\"op\" value=\"\" />\n";
	echo "<input type=\"hidden\" id=\"save\" name=\"save\" value=\"0\" />\n";
	echo "<input type=\"hidden\" id=\"u_action\" name=\"u_action\" value=\"\" />\n";
	echo "<div style=\"font-family: Arial, Helvetica, sans-serif; font-size: 13px;font-weight: bold; text-align: center;margin: 10px;\">" . _SUPPORT07 . "</div>\n";
	echo "<div style=\"text-align: center;padding: 5px; margin-bottom: 2px\">" . _SUPPORT08 . ":<br />\n";
	echo "<input name=\"u_subject\" id=\"u_subject\" type=\"text\" style=\"border: 1px solid #CCCCCC; width: 250px\" /></div>\n";
	echo "<div style=\"text-align: center;padding: 5px; margin-bottom: 2px\">" . _SUPPORT10 . ":<br />\n";
	echo "<select name=\"u_catid\" id=\"u_catid\" style=\"border: 1px solid #CCCCCC;vertical-align: middle\">\n";
	foreach ( $listcat as $k => $v )
	{
		echo "<option value=\"" . $k . "\">" . $v['name'] . "</option>\n";
	}
	echo "</select></div>\n";
	echo "<div style=\"text-align: center;padding: 5px; margin-bottom: 2px\">" . _SUPPORT09 . ":<br />\n";
	echo "<textarea name=\"u_question\" id=\"u_question\" cols=\"20\" rows=\"10\" style=\"border: 1px solid #CCCCCC; width: 250px\"></textarea></div>\n";
	echo "<div style=\"padding: 5px;text-align:center\">\n";
	echo "<input type=\"submit\" id=\"u_submit\" name=\"u_submit\" value=\"&nbsp;" . _SUPPORT13 . "&nbsp;\" style=\"border: 1px solid #CCCCCC;\" /></div>\n";
	echo "</form>\n";

	include ( "footer.php" );
}

switch ( $op )
{
	case "viewcat":
		Support_ViewCat();
		break;
	case "asp":
		Support_AllSupport();
		break;
	case "search":
		Support_Search();
		break;
	case "ulist":
		Support_Ulist();
		break;
	case "usp":
		Support_USupport();
		break;
	case "addnew":
		Support_UAddNew();
		break;
	default:
		Support_Main();
}

?>