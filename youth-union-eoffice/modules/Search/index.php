<?php

/*
* @Program:		NukeViet CMS v2.0 RC1
* @File name: 	Module Search
* @Version: 	2.0
* @Date: 		01.05.2009
* @Website: 	www.nukeviet.vn
* @Copyright: 	(C) 2009
* @License: 	http://opensource.org/licenses/gpl-license.php GNU Public License
*/

if ( ! defined('NV_SYSTEM') )
{
	die( "You can't access this file directly...<br>Rat tiec, ban khong the truy cap truc tiep file nay!<br><hr><center>Copyright 2009 by NukeViet<br><br><a href='javascript:history.back(1)'><b>[Quay lai]</b></a></center>" );
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
/********************************************/


include ( "header.php" );

$query = cheonguoc( stripslashes(check_html($query, nohtml)) );
$handle = opendir( "modules/" . $module_name . "/mod" );
while ( $file = readdir($handle) )
{
	if ( $file == '.' || $file == '..' ) continue;
	$themelist .= "$file ";
}
closedir( $handle );
$themelist = explode( " ", $themelist );
sort( $themelist );
if ( ($query == "") or ($query == "" . _SEARCH . "") )
{
	OpenTable();
	echo "<fieldset style=\"border: 1px solid #3F1D80; padding-left: 5px; padding-right: 5px; padding-top: 5px; padding-bottom: 5px\">\n" . "<legend><b>" . _NSHEADER . "</b></legend>\n" . "<center><br><br>" . _NSHEADER2 . "<br><br><table>\n" . "<form enctype=\"multipart/form-data\" action=\"modules.php?name=Search\" method=\"post\" style=\"display: inline\">\n" . "<tr><td width=\"100\" align=\"right\">" . _TUTK . ":</td>\n" . "<td>&nbsp;&nbsp;<input type=\"text\" name=\"query\" size=\"30\" maxlength=\"25\" style=\"width=200px\"></td></tr>\n" . "<tr><td width=\"100\" align=\"right\">" . _TIMKIEMTAI . ":</td>\n" . "<td>&nbsp;&nbsp;<select name=\"modname\" style=\"width=200px\">\n" . "<option name=\"modname\" value=\"\">" . _NSHEADER . "</option>\n";
	for ( $i = 0; $i < sizeof($themelist); $i++ )
	{
		if ( $themelist[$i] != "" )
		{
			$mod_title = str_replace( ".php", "", $themelist[$i] );
			$sql3 = "SELECT custom_title  FROM " . $prefix . "_modules WHERE title='$mod_title'";
			$result3 = $db->sql_query( $sql3 );
			$row = $db->sql_fetchrow( $result3 );
			$m_title = $row[custom_title];
			echo "<option name=\"modname\" value=\"$mod_title\">$m_title</option>\n";
		}
	}
	echo "</select>";
	echo "</td></tr>\n" . "<tr><td width=\"100\"></td><td>&nbsp;&nbsp;<input type=\"submit\" value=\"" . _NSSEARCH . "\" style=\"width=200px\"></td>\n" . "</tr></form></table><br>\n" . "</center></fieldset><br>\n";
	CloseTable();
}
else
{
	$query = cheonguoc( stripslashes(check_html($query, nohtml)) );

	OpenTable();
	echo "<fieldset style=\"border: 1px solid #3F1D80; padding-left: 5px; padding-right: 5px; padding-top: 5px; padding-bottom: 5px\">\n" . "<legend><b>" . _NSRETRY . "</b></legend>\n" . "<center><br><table>\n" . "<form enctype=\"multipart/form-data\" action=\"modules.php?name=Search\" method=\"post\" style=\"display: inline\">\n" . "<tr><td width=\"100\" align=\"right\">" . _TUTK . ":</td>\n" . "<td>&nbsp;&nbsp;<input type=\"text\" name=\"query\" value=\"$query\" size=\"30\" maxlength=\"25\" style=\"width=200px\"></td></tr>\n" . "<tr><td width=\"100\" align=\"right\">" . _TIMKIEMTAI . ":</td>\n" . "<td>&nbsp;&nbsp;<select name=\"modname\" style=\"width=200px\">\n" . "<option name=\"modname\" value=\"\">" . _NSHEADER . "</option>\n";
	for ( $i = 0; $i < sizeof($themelist); $i++ )
	{
		if ( $themelist[$i] != "" )
		{
			$mod_title = str_replace( ".php", "", $themelist[$i] );
			$sql3 = "SELECT custom_title  FROM " . $prefix . "_modules WHERE title='$mod_title'";
			$result3 = $db->sql_query( $sql3 );
			$row = $db->sql_fetchrow( $result3 );
			$m_title = $row[custom_title];
			echo "<option name=\"modname\" value=\"$mod_title\">$m_title</option>\n";
		}
	}
	echo "</select>";
	echo "</td></tr>\n" . "<tr><td width=\"100\"></td><td>&nbsp;&nbsp;<input type=\"submit\" value=\"" . _NSSEARCH . "\" style=\"width=200px\"></td>\n" . "</tr></form></table><br>\n" . "</center></fieldset>\n";
	CloseTable();
	echo "<br>\n";

	OpenTable();
	if ( $modname != "" )
	{
		for ( $i = 0; $i < sizeof($themelist); $i++ )
		{
			$mod_title = str_replace( ".php", "", $themelist[$i] );
			if ( ($themelist[$i] != "") and ($modname == $mod_title) )
			{
				$sql3 = "SELECT custom_title  FROM " . $prefix . "_modules WHERE title='$mod_title'";
				$result3 = $db->sql_query( $sql3 );
				$row = $db->sql_fetchrow( $result3 );
				$m_title = $row[custom_title];
				$offset = 15;
				if ( ! isset($min) ) $min = 0;
				if ( ! isset($max) ) $max = $min + $offset;
				include ( "modules/Search/mod/$themelist[$i]" );
				echo "<br>\n";
			}
		}
	}
	else
	{
		$nrowsall = 0;
		for ( $i = 0; $i < sizeof($themelist); $i++ )
		{
			if ( $themelist[$i] != "" )
			{
				$mod_title = str_replace( ".php", "", $themelist[$i] );
				$sql3 = "SELECT custom_title  FROM " . $prefix . "_modules WHERE title='$mod_title'";
				$result3 = $db->sql_query( $sql3 );
				$row = $db->sql_fetchrow( $result3 );
				$m_title = $row[custom_title];
				//Số kết quả hiển thị cho một mục trên trang tìm kiếm cho toàn site
				$offset = 4;
				if ( ! isset($min) ) $min = 0;
				if ( ! isset($max) ) $max = $min + $offset;
				include ( "modules/Search/mod/$themelist[$i]" );
				// cộng kết quả tìm kiếm từ các module
				$nrowsall = $nrowsNews + $nrowsNvmusic + $nrowsFiles + $nrowsPages + $nrowsWeb_Links;
				if ( $offset > 1 )
				{
					if ( $nrowsall > 4 )
					{
						echo "<p align=\"right\"><i><b><a href=\"modules.php?name=Search&modname=$mod_title&amp;query=$query\">" . _KQALL . "</a></i></b></p>";
					}
				}
				echo "<hr>\n";

			}
		}

		if ( $nrowsall == 0 )
		{
			echo "<center><b>" . _NOMATCHES . "</b></center>";
		}
	}
	if ( $nrowsall > 0 )
	{
		echo "<center><b><i>" . _KQTONG . " $nrowsall " . _KQTK . "</i></b></center>";
	}
	CloseTable();
	/*
	* echo "<br>";
	* OpenTable();
	* echo "<b class=storytitle>"._NSRETRY2."</b><br><br>";
	* echo "<center><A href=\"modules.php?name=Forums&file=search&search_keywords=$query\">"._KQF." <b>''$query''</b></A>";
	* echo " | <A href=\"modules.php?name=Forums&file=search\">"._KQFD."</A></center>";
	* CloseTable();
	*/
	echo "<br>";
	OpenTable();
	echo "<b class=storytitle>" . _NSRETRY3 . ":</b><center><br>";

	echo "<!-- SiteSearch Google -->" . "<form method=\"get\" action=\"http://www.google.com.vn/custom\" target=\"_top\">" . "<table border=\"0\">" . "<tr><td nowrap=\"nowrap\" valign=\"top\" align=\"left\" height=\"32\">" . "<img src=\"http://www.google.com/logos/Logo_25wht.gif\" border=\"0\" alt=\"Google\" align=\"middle\"></img>" . "</td>" . "<td nowrap=\"nowrap\">" . "<input type=\"hidden\" name=\"domains\" value=\"$nukeurl\"></input>" . "<label for=\"sbi\" style=\"display: none\">Nhập tìm kiếm</label>" . "<input type=\"text\" name=\"q\" size=\"38\" maxlength=\"255\" value=\"$query\" id=\"sbi\"></input>" . "</td></tr>" . "<tr>" . "<td>&nbsp;</td>" . "<td nowrap=\"nowrap\">" . "<table>" . "<tr>" . "<td>" . "<input type=\"radio\" name=\"sitesearch\" value=\"\" checked id=\"ss0\"></input>" . "<label for=\"ss0\" title=\"Search the Web\"><font size=\"-1\" color=\"black\">" . _KQINTER . "</font></label></td>" . "<td>" . "<input type=\"radio\" name=\"sitesearch\" value=\"$nukeurl\" id=\"ss1\"></input>" .
		"<label for=\"ss1\" title=\"Search $sitename\"><font size=\"-1\" color=\"black\">" . _KQRIENG . " $sitename</font></label></td>" . "</tr>" . "</table>" . "<label for=\"sbb\" style=\"display: none\">Submit search form</label>" . "<input type=\"submit\" name=\"sa\" value=\"" . _NSSEARCH . "\" id=\"sbb\"></input>" . "<input type=\"hidden\" name=\"client\" value=\"pub-1710694852538675\"></input>" . "<input type=\"hidden\" name=\"forid\" value=\"1\"></input>" . "<input type=\"hidden\" name=\"channel\" value=\"7652744018\"></input>" . "<input type=\"hidden\" name=\"ie\" value=\"UTF-8\"></input>" . "<input type=\"hidden\" name=\"oe\" value=\"UTF-8\"></input>" . "<input type=\"hidden\" name=\"cof\" value=\"L:$nukeurl/images/logo.gif;S:$nukeurl\"></input>" . "<input type=\"hidden\" name=\"hl\" value=\"" . _KQLANG . "\"></input>" . "</td></tr></table>" . "</form>" . "<!-- SiteSearch Google -->";


	echo "</center>";
	//echo "<A href=\"#top\">"._KQLEN."</a>";

	CloseTable();
}


include ( "footer.php" );

?>