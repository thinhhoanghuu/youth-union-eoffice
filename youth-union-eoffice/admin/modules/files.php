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
$checkmodname = "Files";
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

	$apath = "../" . $path . "";
	$temp_apath = "../" . $temp_path . "";

	/**
	 * getparent()
	 * 
	 * @param mixed $parentid
	 * @param mixed $title
	 * @return
	 */
	function getparent( $parentid, $title )
	{
		global $prefix, $db;
		$sql = "select cid, title, parentid from " . $prefix . "_files_categories where cid='$parentid'";
		$result = $db->sql_query( $sql );
		$row = $db->sql_fetchrow( $result );
		$cid = $row[cid];
		$ptitle = $row[title];
		$pparentid = $row[parentid];
		if ( $ptitle != "" ) $title = $ptitle . "/" . $title;
		if ( $pparentid != 0 )
		{
			$title = getparent( $pparentid, $title );
		}
		return $title;
	}

	/**
	 * fileadminmenu()
	 * 
	 * @return
	 */
	function fileadminmenu()
	{
		global $adminfile;
		title( "<a href=\"" . $adminfile . ".php?op=ListFilesAdded\">" . _FILESADMIN . "</a>" );
		OpenTable();
		echo "<center>
	    <a href=\"" . $adminfile . ".php?op=FilesSetting\">" . _FILESCONFIG . "</a>&nbsp;|&nbsp;
	   <a href=\"" . $adminfile . ".php?op=files\">" . _FILESMANAGERCAT . "</a>&nbsp;|&nbsp;
 	   <a href=\"" . $adminfile . ".php?op=ListFilesAdded\">" . _FILESMANAGER . "</a>&nbsp;|&nbsp;
 	   <a href=\"" . $adminfile . ".php?op=FilesComment\">" . _DUYETCOMM . "</a>
 	   </center>";
		CloseTable();
	}

	/**
	 * filesmanagermenu()
	 * 
	 * @return
	 */
	function filesmanagermenu()
	{
		global $adminfile, $prefix, $db;
		$newfiles = $db->sql_numrows( $db->sql_query("SELECT * FROM " . $prefix . "_files WHERE status='0'") );
		$brocfiles = $db->sql_numrows( $db->sql_query("SELECT * FROM " . $prefix . "_files WHERE status='2'") );
		if ( $newfiles > 0 )
		{
			$fileswaiting = "<a href=" . $adminfile . ".php?op=FilesWaiting>" . _ADDNEWFILES . "</a>&nbsp;(<b>$newfiles</b>)";
		}
		else
		{
			$fileswaiting = "" . _ADDNEWFILES . " &nbsp;<b>(" . _NO . ")</b>";
		}
		if ( $brocfiles > 0 )
		{
			$brocknote = "<a href=" . $adminfile . ".php?op=brocen_files>" . _ADDBROCFILES . "</a>&nbsp;(<b>$brocfiles</b>)";
		}
		else
		{
			$brocknote = "" . _ADDBROCFILES . " &nbsp;(<b>" . _NO . "</b>)";
		}

		OpenTable();
		echo "<center>
	    <a href=\"" . $adminfile . ".php?op=AddNewFile\">" . _ADDFILE . "</a>&nbsp;|&nbsp;
	   <a href=\"" . $adminfile . ".php?op=ListFilesAdded\">" . _LISTFILEADDED . "</a>&nbsp;|&nbsp;
	  <a href=\"" . $adminfile . ".php?op=MoveFilesCat\">" . _MOVEFILESCAT . "</a>&nbsp;|&nbsp;

 	  " . $brocknote . "&nbsp;|&nbsp;
 	  " . $fileswaiting . "</center>";

		CloseTable();


	}

	/**
	 * FilesSetting()
	 * 
	 * @return
	 */
	function FilesSetting()
	{
		global $fhomemsg, $prefix, $db, $checkmodname, $datafold, $temp_path, $files_mime, $path, $maxfilesize, $uploadfiles, $addfiles, $filesvote, $brokewarning, $addcomments, $adminfile, $fnote, $showsub, $tabcolumn, $filesperpage, $download, $fchecknum;
		if ( file_exists("" . INCLUDE_PATH . "" . $datafold . "/config_" . $checkmodname . ".php") )
		{
			include ( "" . INCLUDE_PATH . "" . $datafold . "/config_" . $checkmodname . ".php" );
		}
		include ( "" . INCLUDE_PATH . "header.php" );

		fileadminmenu();
		title( _FILESETING );
		echo "<form action=\"" . $adminfile . ".php\" method=\"post\">";
		OpenTable();
		echo "<table border=\"0\">"

		. "<tr><td>" . _FINEWS . "";
		echo "</td><td><select name=\"xnewscatid\">";
		$sql = "select catid, parentid, title from " . $prefix . "_stories_cat order by parentid, weight";
		$result = $db->sql_query( $sql );
		echo "<option name=\"xnewscatid\" value=\"0\">" . _FINEWS0 . "</option>\n";
		while ( $row = $db->sql_fetchrow($result) )
		{
			$ctitle = $row['title'];
			if ( $row['parentid'] != 0 )
			{
				list( $ptitle ) = $db->sql_fetchrow( $db->sql_query("select title from " . $prefix . "_stories_cat where catid='" . $row['parentid'] . "'") );
				$ctitle = "$ptitle &raquo; $ctitle";
			}
			echo "<option name=\"xnewscatid\" value=\"$row[catid]\"";
			if ( $row['catid'] == $newscatid )
			{
				echo " selected";
			}
			echo ">$ctitle</option>";
		}
		echo "</select></td></tr>";
		echo "<tr><td>" . "- " . _FINEWS1 . ":</td><td><select name=\"xnumnews\">";
		for ( $d = 1; $d <= 8; $d++ )
		{
			echo "<option name=\"xnumnews\" value=\"$d\"";
			if ( $d == $numnews ) echo " selected";
			echo ">$d</option>\n";
		}
		echo "</select></td></tr>"

		. "<tr><td>" . _CATNGAN . "";
		echo "</td><td><select name=\"xchoncatngan\">";
		$ychoncatngan = array( _CATNGAN0, _CATNGANYES );
		for ( $d = 0; $d <= 1; $d++ )
		{
			$seld = "";
			if ( $d == $choncatngan )
			{
				$seld = " selected";
			}
			echo "<option name=\"xchoncatngan\" value=\"$d\" $seld>$ychoncatngan[$d]</option>\n";
		}
		echo "</select></td></tr>" . "<tr><td>" . "- " . _CATNGAN1 . ":</td><td><input name=\"xcatngan\" size=\"10\" value=\"$catngan\"></td></tr>"

		. "<tr><td>" . _TTT . "";
		echo "</td><td><select name=\"xttt\">";
		$yttt = array( _NO, _YES );
		for ( $d = 0; $d <= 1; $d++ )
		{
			$seld = "";
			if ( $d == $ttt )
			{
				$seld = " selected";
			}
			echo "<option name=\"xttt\" value=\"$d\" $seld>$yttt[$d]</option>\n";
		}
		echo "</select> " . _TTTHD . "\n";

		if ( $ttt == 0 )
		{
			echo "<tr><td>" . _RENAMEFILE . "";
			echo "</td><td>" . _PREFILENAME . " <input type=\"text\" name=\"xprefilename\" value=\"$prefilename\" size=\"30\"><br>\n";
			echo "" . _AUTOFILENAME . "";
			if ( $autofilename == 1 )
			{
				echo "<input type=\"radio\" name=\"xautofilename\" value=\"1\" checked>" . _YES . " &nbsp;" . "<input type=\"radio\" name=\"xautofilename\" value=\"0\">" . _NO . "";
			}
			else
			{
				echo "<input type=\"radio\" name=\"xautofilename\" value=\"1\">" . _YES . " &nbsp;" . "<input type=\"radio\" name=\"xautofilename\" value=\"0\" checked>" . _NO . "";
			}
		}
		echo "</td></tr>"

		. "<tr><td>" . "" . _FHOMENOTE . ":</td><td>";
		if ( $fnote == 1 )
		{
			echo "<input type=\"radio\" name=\"xfnote\" value=\"1\" checked>" . _YES . " &nbsp;" . "<input type=\"radio\" name=\"xfnote\" value=\"0\">" . _NO . "";
		}
		else
		{
			echo "<input type=\"radio\" name=\"xfnote\" value=\"1\">" . _YES . " &nbsp;" . "<input type=\"radio\" name=\"xfnote\" value=\"0\" checked>" . _NO . "";
		}
		echo "</td></tr>" . "<tr><td>" . "" . _SHOWSUBCAT . ":</td><td>";
		if ( $showsub == 1 )
		{
			echo "<input type=\"radio\" name=\"xshowsub\" value=\"1\" checked>" . _YES . " &nbsp;" . "<input type=\"radio\" name=\"xshowsub\" value=\"0\">" . _NO . "";
		}
		else
		{
			echo "<input type=\"radio\" name=\"xshowsub\" value=\"1\">" . _YES . " &nbsp;" . "<input type=\"radio\" name=\"xshowsub\" value=\"0\" checked>" . _NO . "";
		}
		echo "</td></tr>\n" . "<tr><td>" . _TABCOLUMN . ":</td><td><select name=\"xtabcolumn\">";
		for ( $d = 1; $d <= 5; $d++ )
		{
			$seld = "";
			if ( $d == $tabcolumn )
			{
				$seld = " selected";
			}
			echo "<option name=\"xtabcolumn\" value=\"$d\" $seld>$d</option>\n";
		}
		echo "</select></td></tr>";

		echo "<tr><td>" . _FILESPERPAGE . ":</td><td><select name=\"xfilesperpage\">";
		for ( $d = 6; $d <= 40; $d++ )
		{
			$seld = "";
			if ( $d == $filesperpage )
			{
				$seld = " selected";
			}
			echo "<option name=\"xfilesperpage\" value=\"$d\" $seld>$d</option>\n";
		}
		echo "</select></td></tr>" . "<tr><td>" . "" . _DOWNLOAD . ":</td><td><select name=\"xdownload\">";
		$ydownload = array( _ALL, _MEMBER );
		for ( $d = 0; $d <= 1; $d++ )
		{
			$seld = "";
			if ( $d == $download )
			{
				$seld = " selected";
			}
			echo "<option name=\"xdownload\" value=\"$d\" $seld>$ydownload[$d]</option>\n";
		}
		echo "</select></td></tr>" . "<tr><td>" . "" . _FCHECKNUM . ":</td><td>";
		if ( $fchecknum == 1 )
		{
			echo "<input type=\"radio\" name=\"xfchecknum\" value=\"1\" checked>" . _YES . " &nbsp;" . "<input type=\"radio\" name=\"xfchecknum\" value=\"0\">" . _NO . "";
		}
		else
		{
			echo "<input type=\"radio\" name=\"xfchecknum\" value=\"1\">" . _YES . " &nbsp;" . "<input type=\"radio\" name=\"xfchecknum\" value=\"0\" checked>" . _NO . "";
		}
		echo "</td></tr>" . "<tr><td>" . "" . _ADDCOMMENTS . ":</td><td><select name=\"xaddcomments\">";
		$yaddcomments = array( _NO, _ALL, _MEMBER );
		for ( $d = 0; $d < 3; $d++ )
		{
			$seld = "";
			if ( $d == $addcomments )
			{
				$seld = " selected";
			}
			echo "<option name=\"xaddcomments\" value=\"$d\" $seld>$yaddcomments[$d]</option>\n";
		}
		echo "</select></td></tr>" . "<tr><td>" . "" . _BROKELINK . ":</td><td><select name=\"xbrokewarning\">";
		$ybrokewarning = array( _NO, _ALL, _MEMBER );
		for ( $d = 0; $d <= 2; $d++ )
		{
			$seld = "";
			if ( $d == $brokewarning )
			{
				$seld = " selected";
			}
			echo "<option name=\"xpostmusic\" value=\"$d\" $seld>$ybrokewarning[$d]</option>\n";
		}
		echo "</select></td></tr>" . "<tr><td>" . "" . _FVOTES . ":</td><td><select name=\"xfilesvote\">";
		$yfilesvote = array( _NO, _ALL, _MEMBER );
		for ( $d = 0; $d <= 2; $d++ )
		{
			$seld = "";
			if ( $d == $filesvote )
			{
				$seld = " selected";
			}
			echo "<option name=\"xfilesvote\" value=\"$d\" $seld>$yfilesvote[$d]</option>\n";
		}
		echo "</select></td></tr>" . "<tr><td>" . "" . _ADDFILES . ":</td><td><select name=\"xaddfiles\">";
		$yaddfiles = array( _NO, _ALL, _MEMBER );
		for ( $d = 0; $d <= 2; $d++ )
		{
			$seld = "";
			if ( $d == $addfiles )
			{
				$seld = " selected";
			}
			echo "<option name=\"xaddfiles\" value=\"$d\" $seld>$yaddfiles[$d]</option>\n";
		}
		echo "</select></td></tr>" . "<tr><td>" . "" . _UPLOADFILES . ":</td><td><select name=\"xuploadfiles\">";
		$yuploadfiles = array( _NO, _ALL, _MEMBER );
		for ( $d = 0; $d <= 2; $d++ )
		{
			$seld = "";
			if ( $d == $uploadfiles )
			{
				$seld = " selected";
			}
			echo "<option name=\"xuploadfiles\" value=\"$d\" $seld>$yuploadfiles[$d]</option>\n";
		}
		echo "</select></td></tr>" . "<tr><td>" . "<i>" . _IFUPLOADFILES . "</i>";
		echo "</td></tr>" . "<tr><td>" . "- " . _MAXFILESIZE . ":</td><td>" . "<input name=\"xmaxfilesize\" size=\"10\" value=\"$maxfilesize\">" . "</td></tr>" . "<tr><td>" . "" . _FILEADMINDIR . ":</td><td>" . "<input type=\"text\" name=\"xpath\" value=\"$path\" size=\"30\">";
		echo "</td></tr>" . "<tr><td>" . "" . _FILEUSERDIR . ":</td><td>" . "<input type=\"text\" name=\"xtemp_path\" value=\"$temp_path\" size=\"30\">";
		echo "</td></tr>" . "<tr><td>" . "" . _FILESMIME . ":</td><td>" . "<textarea wrap=\"virtual\" cols=\"50\" rows=\"6\" name=\"xfiles_mime\">$files_mime</textarea>";
		echo "</td></tr>" . "<tr><td>" . "" . _FHOMEMSG . ":</td><td>";
		$fhomemsg = str_replace( "<br />", "", html_entity_decode($fhomemsg) );
		echo "<textarea wrap=\"virtual\" cols=\"50\" rows=\"6\" name=\"xfhomemsg\">$fhomemsg</textarea>";
		echo "</td></tr>" . "</table>";
		CloseTable();
		echo "<br>" . "<input type=\"hidden\" name=\"op\" value=\"FilesSettingSave\">" . "<center><input type=\"submit\" value=\"" . _SAVECHANGES . "\"></center>" . "</form>";
		include ( "../footer.php" );
	}

	/**
	 * FilesSettingSave()
	 * 
	 * @return
	 */
	function FilesSettingSave()
	{
		global $checkmodname, $datafold, $adminfile;

		$xnewscatid = intval( $_POST['xnewscatid'] );
		$xnumnews = intval( $_POST['xnumnews'] );


		$xcatngan = intval( $_POST['xcatngan'] );
		$xchoncatngan = intval( $_POST['xchoncatngan'] );


		$xttt = intval( $_POST['xttt'] );

		$xprefilename = FixQuotes( $_POST['xprefilename'] );
		$xautofilename = intval( $_POST['xautofilename'] );

		$xfnote = intval( $_POST['xfnote'] );
		$xshowsub = intval( $_POST['xshowsub'] );
		$xtabcolumn = intval( $_POST['xtabcolumn'] );
		$xfilesperpage = intval( $_POST['xfilesperpage'] );
		$xdownload = intval( $_POST['xdownload'] );
		$xfchecknum = intval( $_POST['xfchecknum'] );
		$xaddcomments = intval( $_POST['xaddcomments'] );
		$xbrokewarning = intval( $_POST['xbrokewarning'] );
		$xfilesvote = intval( $_POST['xfilesvote'] );
		$xaddfiles = intval( $_POST['xaddfiles'] );
		$xuploadfiles = intval( $_POST['xuploadfiles'] );
		$xmaxfilesize = intval( $_POST['xmaxfilesize'] );
		$xpath = FixQuotes( $_POST['xpath'] );
		$xfiles_mime = FixQuotes( $_POST['xfiles_mime'] );
		$xtemp_path = FixQuotes( $_POST['xtemp_path'] );
		$xfhomemsg = nl2brStrict( stripslashes(FixQuotes($_POST['xfhomemsg'])) );

		@chmod( "" . INCLUDE_PATH . "" . $datafold . "/config_" . $checkmodname . ".php", 0777 );
		@$file = fopen( "" . INCLUDE_PATH . "" . $datafold . "/config_" . $checkmodname . ".php", "w" );

		$content = "<?php\n\n";
		$fctime = date( "d-m-Y H:i:s", filectime("" . INCLUDE_PATH . "" . $datafold . "/config_" . $checkmodname . ".php") );
		$fmtime = date( "d-m-Y H:i:s" );
		$content .= "// File: config_" . $checkmodname . ".php.\n// Created: $fctime.\n// Modified: $fmtime.\n// Do not change anything in this file!\n\n";
		$content .= "if ((!defined('NV_SYSTEM')) AND (!defined('NV_ADMIN'))) {\n";
		$content .= "die('Stop!!!');\n";
		$content .= "}\n";
		$content .= "\n";

		$content .= "\$newscatid = $xnewscatid;\n";
		$content .= "\$numnews = $xnumnews;\n";


		$content .= "\$catngan = $xcatngan;\n";
		$content .= "\$choncatngan = $xchoncatngan;\n";


		$content .= "\$ttt = $xttt;\n";

		$content .= "\$prefilename = \"$xprefilename\";\n";
		$content .= "\$autofilename = $xautofilename;\n";


		$content .= "\$fnote = $xfnote;\n";
		$content .= "\$showsub = $xshowsub;\n";
		$content .= "\$tabcolumn = $xtabcolumn;\n";
		$content .= "\$filesperpage = $xfilesperpage;\n";
		$content .= "\$download = $xdownload;\n";
		$content .= "\$fchecknum = $xfchecknum;\n";
		$content .= "\$addcomments = $xaddcomments;\n";
		$content .= "\$brokewarning = $xbrokewarning;\n";
		$content .= "\$filesvote = $xfilesvote;\n";
		$content .= "\$addfiles = $xaddfiles;\n";
		$content .= "\$uploadfiles = $xuploadfiles;\n";
		$content .= "\$maxfilesize = $xmaxfilesize;\n";
		$content .= "\$files_mime = \"$xfiles_mime\";\n";
		$content .= "\$path = \"$xpath\";\n";
		$content .= "\$temp_path = \"$xtemp_path\";\n";
		$content .= "\$fhomemsg = \"$xfhomemsg\";\n";
		$content .= "\n";
		$content .= "?>";
		@fwrite( $file, $content );
		@fclose( $file );
		@chmod( "" . INCLUDE_PATH . "" . $datafold . "/config_" . $checkmodname . ".php", 0604 );
		include ( "../header.php" );

		OpenTable();
		echo "<center><b>" . _SAVECONFIG . "</b></center>";
		echo "<META HTTP-EQUIV=\"refresh\" content=\"2;URL=" . $adminfile . ".php?op=FilesSetting\">";
		CloseTable();
		include ( "../footer.php" );
	}

	/**
	 * ListFilesAdded()
	 * 
	 * @param mixed $booknum
	 * @return
	 */
	function ListFilesAdded( $booknum )
	{
		global $adminfile, $prefix, $user_prefix, $db, $radminsuper, $anonymous, $bgcolor1;
		include ( "../header.php" );
		GraphicAdmin();
		fileadminmenu();
		filesmanagermenu();
		$num = $db->sql_fetchrow( $db->sql_query("SELECT COUNT(*) FROM " . $prefix . "_files WHERE status!='0'") );
		$page = ( isset($_GET['page']) ) ? intval( $_GET['page'] ) : 0;
		$all_page = ( $num[0] ) ? $num[0] : 1;
		$per_page = 20;
		$base_url = "" . $adminfile . ".php?op=ListFilesAdded";
		$sql3 = "SELECT lid, title FROM " . $prefix . "_files WHERE status!='0' ORDER BY lid DESC LIMIT $page,$per_page";
		$result3 = $db->sql_query( $sql3 );
		if ( $num[0] > 0 )
		{
			OpenTable();
			echo "<center><b>" . _LISTFILEADDED . "</b><br>";
			echo "<table border=\"1\" width=\"90%\" bgcolor=\"$bgcolor1\">";
			while ( $row3 = $db->sql_fetchrow($result3) )
			{
				$lid = $row3['lid'];
				$title = $row3['title'];
				echo "<tr><td align=\"right\"><b>$lid</b>" . "</td><td align=\"left\" width=\"100%\"><a href=\"../modules.php?name=Files&go=view_file&lid=$lid\">$title</a>" . "</td><td align=\"right\" nowrap>(<a href=\"" . $adminfile . ".php?op=edit_files&lid=$lid\">" . _EDIT . "</a>-<a href=\"" . $adminfile . ".php?op=delit_file&lid=$lid\">" . _DELETE . "</a>)" . "</td></tr>";
			}
			echo "</table></center><br>";
			echo @generate_page( $base_url, $all_page, $per_page, $page );

			echo "<center>" . "<form action=\"" . $adminfile . ".php\" method=\"post\">" . "" . _STORYID . " <input type=\"text\" NAME=\"lid\" SIZE=\"10\"> " . "<select name=\"op\">" . "<option value=\"edit_files\" SELECTED>" . _EDIT . "</option>" . "<option value=\"delit_file\">" . _DELETE . "</option>" . "</select> " . "<input type=\"submit\" value=\"" . _GO . "\">" . "</form></center>";
			CloseTable();
		}

		include ( "../footer.php" );
	}

	/**
	 * files()
	 * 
	 * @return
	 */
	function files()
	{
		global $adminfile, $prefix, $db, $multilingual, $bgcolor2;
		include ( "../header.php" );

		fileadminmenu();


		OpenTable();
		echo "<center><b>" . _ADDCATEGORY . "</b></center><br><br>" . "<form action=\"" . $adminfile . ".php\" method=\"post\">" . "<b>" . _TITLE . ":</b><br><input type=\"text\" name=\"title\" size=\"50\"><br><br>" . "<b>" . _SUBTITLE . ":</b><br><textarea name=\"description\" rows=\"3\" cols=\"50\"></textarea><br><br>" . "<input type=\"hidden\" name=\"op\" value=\"add_files_category\">" . "<input type=\"submit\" value=\"" . _ADD . "\">" . "</form>";
		CloseTable();
		echo "<br>";


		$sql = "select cid, title, parentid from " . $prefix . "_files_categories order by parentid,title";
		$result = $db->sql_query( $sql );
		if ( $numrows = $db->sql_numrows($result) > 0 )
		{
			OpenTable();
			echo "<form method=\"post\" action=\"" . $adminfile . ".php\">" . "<center><b>" . _ADDSUBCATEGORY . "</b></center><br><br>" . "<b>" . _INCAT . ":</b><br>" . "<select name=\"cid\">";

			while ( $row = $db->sql_fetchrow($result) )
			{
				$cid2 = $row['cid'];
				$title = $row['title'];
				$parentid2 = $row['parentid'];
				if ( $parentid2 != 0 ) $title = getparent( $parentid2, $title );
				echo "<option value=\"$cid2\">$title</option>";
			}
			echo "</select><br><br>" . "<b>" . _TITLE . ":</b><br><input type=\"text\" name=\"title\" size=\"50\" maxlength=\"100\"><br><br>" . "<b>" . _SUBTITLE . ":</b><br><textarea name=\"description\" cols=\"50\" rows=\"3\"></textarea><br><br>" . "<input type=\"hidden\" name=\"op\" value=\"add_files_sub_cat\">" . "<input type=\"submit\" value=\"" . _ADD . "\"></form>";
			CloseTable();
			echo "<br>";
		}


		$sql = "select cid, title, parentid from " . $prefix . "_files_categories order by parentid,title";
		$result = $db->sql_query( $sql );
		if ( $numrows = $db->sql_numrows($result) > 0 )
		{
			OpenTable();
			echo "<center><b>" . _EDITFCAT . "</b></center><br><br>" . "<form action=\"" . $adminfile . ".php\" method=\"post\">" . "<b>" . _CATEGORY . ":</b> " . "<select name=\"cid\">";
			while ( $row = $db->sql_fetchrow($result) )
			{
				$cid2 = $row['cid'];
				$title = $row['title'];
				$parentid2 = $row['parentid'];
				if ( $parentid2 != 0 ) $title = getparent( $parentid2, $title );
				echo "<option value=\"$cid2\">$title</option>";
			}
			echo "</select>&nbsp;&nbsp;" . "<input type=\"hidden\" name=\"op\" value=\"edit_files_category\">" . "<input type=\"submit\" value=\"" . _EDIT . "\">" . "</form>";
			CloseTable();
		}
		echo "<br>";

		include ( "../footer.php" );
	}

	/**
	 * add_files_category()
	 * 
	 * @param mixed $title
	 * @param mixed $description
	 * @return
	 */
	function add_files_category( $title, $description )
	{
		global $adminfile, $prefix, $db;
		$db->sql_query( "INSERT INTO " . $prefix . "_files_categories (cid, title, cdescription) VALUES (NULL, '$title', '$description')" );
		Header( "Location: " . $adminfile . ".php?op=files" );
	}

	/**
	 * add_files_sub_cat()
	 * 
	 * @param mixed $cid
	 * @param mixed $title
	 * @param mixed $description
	 * @return
	 */
	function add_files_sub_cat( $cid, $title, $description )
	{
		global $adminfile, $prefix, $db;
		$db->sql_query( "INSERT INTO " . $prefix . "_files_categories (cid, title, cdescription, parentid) VALUES (NULL, '$title', '$description', '$cid')" );
		Header( "Location: " . $adminfile . ".php?op=files" );
	}

	/**
	 * edit_files_category()
	 * 
	 * @param mixed $cid
	 * @return
	 */
	function edit_files_category( $cid )
	{
		global $adminfile, $prefix, $db, $multilingual;
		include ( "../header.php" );

		fileadminmenu();
		title( "" . _EDITFCAT . "" );
		OpenTable();
		$sql = "SELECT title, cdescription, parentid FROM " . $prefix . "_files_categories WHERE cid='$cid'";
		$result = $db->sql_query( $sql );
		$row = $db->sql_fetchrow( $result );
		$title = $row['title'];
		$description = $row['cdescription'];
		$parentid = $row['parentid'];
		echo "<center><b>" . _EDITCATEGORY . "</b></center><br><br>" . "<form action=\"" . $adminfile . ".php\" method=\"post\">" . "<b>" . _TITLE . "</b><br>" . "<input type=\"text\" name=\"title\" value=\"$title\" size=\"50\"><br><br>";
		if ( $parentid != 0 )
		{
			$sql2 = "select cid, title, parentid from " . $prefix . "_files_categories where parentid != '$parentid'";
			$result2 = $db->sql_query( $sql2 );
			echo "<b>" . _INCAT . ":</b><br><select name=\"parentid\">";
			while ( $row = $db->sql_fetchrow($result2) )
			{
				$cid2 = $row['cid'];
				$ctitle2 = $row['title'];
				$parentid2 = $row['parentid'];
				if ( $parentid2 != 0 ) $ctitle2 = getparent( $parentid2, $ctitle2 );
				if ( $parentid == $cid2 )
				{

					$shel = "selected";
				}
				else
				{
					$shel = "";
				}

				echo "<option value=\"$cid2\" $shel>$ctitle2</option>";

			}
			echo "</select><br><br>";
		}
		else
		{
			echo "<input type=\"hidden\" name=\"parentid\" value=\"0\">";
		}

		echo "<b>" . _SUBTITLE . "</b><br>" . "<textarea cols=\"50\" rows=\"3\" name=\"description\">$description</textarea><br><br>" . "<input type=\"hidden\" name=\"cid\" value=\"$cid\">" . "<input type=\"hidden\" name=\"op\" value=\"save_files_category\">" . "<input type=\"submit\" value=\"" . _SAVECHANGES . "\">&nbsp;&nbsp;" . "[ <a href=\"" . $adminfile . ".php?op=del_files_cat&amp;cid=$cid\">" . _DELETE . "</a> ]" . "</form>";
		CloseTable();
		include ( "../footer.php" );
	}


	/**
	 * save_files_category()
	 * 
	 * @param mixed $cid
	 * @param mixed $title
	 * @param mixed $description
	 * @param mixed $parentid
	 * @return
	 */
	function save_files_category( $cid, $title, $description, $parentid )
	{
		global $adminfile, $prefix, $db;
		$db->sql_query( "update " . $prefix . "_files_categories set title='$title', cdescription='$description', parentid='$parentid' where cid='$cid'" );
		Header( "Location: " . $adminfile . ".php?op=files" );
	}


	/**
	 * del_files_cat()
	 * 
	 * @param mixed $cid
	 * @param integer $ok
	 * @return
	 */
	function del_files_cat( $cid, $ok = 0 )
	{
		global $adminfile, $prefix, $db;
		if ( $ok == 1 )
		{
			$db->sql_query( "delete from " . $prefix . "_files_categories where cid='$cid'" );
			$db->sql_query( "delete from " . $prefix . "_files_categories where parentid='$cid'" );
			$sql = "SELECT lid FROM " . $prefix . "_files WHERE cid='$cid'";
			$result = $db->sql_query( $sql );
			while ( $row = $db->sql_fetchrow($result) )
			{
				$lid = $row[lid];
				$db->sql_query( "delete from " . $prefix . "_files_comments where lid=$lid" );
			}
			$db->sql_query( "delete from " . $prefix . "_files where cid=$cid" );

			Header( "Location: " . $adminfile . ".php?op=files" );
		}
		else
		{
			include ( "../header.php" );

			fileadminmenu();
			OpenTable();
			echo "<center><b>" . _DELFCAT . "</b></center><br><br>";
			$sql = "select title from " . $prefix . "_files_categories where cid='$cid'";
			$result = $db->sql_query( $sql );
			$row = $db->sql_fetchrow( $result );
			$title = $row[title];

			echo "<center><b>" . _DELFCATEGORY . ": $title</b><br><br>" . "" . _DELFCATNOTE . "<br><br>" . "[ <a href=\"" . $adminfile . ".php?op=files\">" . _NO . "</a> | <a href=\"" . $adminfile . ".php?op=del_files_cat&amp;cid=$cid&amp;ok=1\">" . _YES . "</a> ]</center>";
			CloseTable();
			include ( "../footer.php" );
		}
	}


	/**
	 * MoveFilesCat()
	 * 
	 * @return
	 */
	function MoveFilesCat()
	{
		global $adminfile, $prefix, $db;

		include ( "../header.php" );

		fileadminmenu();
		filesmanagermenu();
		echo "<br>";

		$numfiles = $db->sql_numrows( $db->sql_query("SELECT * FROM " . $prefix . "_files") );
		if ( $numfiles > 0 )
		{
			$sql = "select cid, title, parentid from " . $prefix . "_files_categories order by parentid,title";
			$result = $db->sql_query( $sql );
			if ( $numrows = $db->sql_numrows($result) > 0 )
			{
				echo "<br>";
				OpenTable();
				echo "<center><b>" . _MOVEFCAT . "</b></center><br><br>" . "<form action=\"" . $adminfile . ".php\" method=\"post\">" . "<table><tr><td><b>" . _FROMCATEGORY . ":</b></td>" . "<td><select name=\"fromcid\">" . "<option value=\"0\">" . _HOMEFILES . "</option>";
				while ( $row = $db->sql_fetchrow($result) )
				{
					$cid2 = $row[cid];
					$title = $row[title];
					$parentid2 = $row[parentid];
					if ( $parentid2 != 0 ) $title = getparent( $parentid2, $title );
					echo "<option value=\"$cid2\">$title</option>";
				}
				echo "</select></td></tr>";
				echo "<tr><td><b>" . _INCATEGORY . ":</b></td>" . "<td><select name=\"incid\">" . "<option value=\"0\">" . _HOMEFILES . "</option>";
				$sql2 = "select cid, title, parentid from " . $prefix . "_files_categories order by parentid,title";
				$result2 = $db->sql_query( $sql2 );
				while ( $row = $db->sql_fetchrow($result2) )
				{
					$i_cid2 = $row['cid'];
					$i_title = $row['title'];
					$i_parentid2 = $row['parentid'];
					if ( $i_parentid2 != 0 ) $i_title = getparent( $i_parentid2, $i_title );
					echo "<option value=\"$i_cid2\">$i_title</option>";
				}
				echo "</select></td></tr></table>" . "<input type=\"hidden\" name=\"op\" value=\"MoveFilesCatSave\">" . "<br><input type=\"submit\" value=\"" . _MOVEFCATS . "\">" . "</form>";
				CloseTable();
			}
		}

		include ( "../footer.php" );
	}

	/**
	 * MoveFilesCatSave()
	 * 
	 * @param mixed $fromcid
	 * @param mixed $incid
	 * @return
	 */
	function MoveFilesCatSave( $fromcid, $incid )
	{
		global $adminfile, $prefix, $db;
		$db->sql_query( "UPDATE " . $prefix . "_files SET cid='$incid' WHERE cid='$fromcid'" );
		Header( "Location: " . $adminfile . ".php?op=ListFilesAdded" );
	}

	/**
	 * AddNewFile()
	 * 
	 * @return
	 */
	function AddNewFile()
	{
		global $adminfile, $prefix, $db, $editor;

		include ( "../header.php" );

		fileadminmenu();
		filesmanagermenu();
		echo "<br>";
		OpenTable();
		echo "<center><b>" . _ADDFILE . "</b></center><br><br>" . "<form enctype=\"multipart/form-data\" action=\"" . $adminfile . ".php\" method=\"post\">" . "<b>" . _TITLE . ":</b><br><input type=\"text\" name=\"title\" size=\"50\" maxlength=\"100\"><br><br>" . "<b>" . _CATEGORY . ":</b><br>" . "<select name=\"cid\"><option value=\"0\">" . _HOMEFILES . "</option>";
		$sql = "select cid, title, parentid from " . $prefix . "_files_categories order by parentid,title";
		$result = $db->sql_query( $sql );
		while ( $row = $db->sql_fetchrow($result) )
		{
			$cid2 = $row['cid'];
			$title = $row['title'];
			$parentid2 = $row['parentid'];
			if ( $parentid2 != 0 ) $title = getparent( $parentid2, $title );
			echo "<option value=\"$cid2\">$title</option>";
		}
		echo "</select><br><br>";
		echo "<b>" . _SUBTITLE . ":</b><br>";
		if ( $editor == 1 )
		{
			aleditor( "description", "", 500, 250 );
		}
		else
		{
			echo "<textarea name=\"description\" cols=\"50\" rows=\"10\"></textarea><br><br>";
		}
		echo "<b>" . _FILEAUTOR . "</b><br><input type=\"text\" name=\"author\" size=\"50\" maxlength=\"100\"><br><br>" . "<b>" . _FAUEMAIL . "</b><br><input type=\"text\" name=\"authormail\" size=\"50\" maxlength=\"100\"><br><br>" . "<b>" . _FAUURL . "</b><br><input type=\"text\" name=\"authorurl\" size=\"50\" maxlength=\"100\" value=\"http://\"><br><br>" . "<b>" . _FILE . ":</b><br><input name=\"userfile\" type=\"file\" size=\"40\"><br><br>" . "<b>" . _FILELINK . ":</b><br><input type=\"text\" name=\"filelink\" size=\"50\" maxlength=\"500\" value=\"http://\"><br><br>" . "<b>" . _FILEVERSION . "</b><br><input type=\"text\" name=\"version\" size=\"10\" maxlength=\"10\"><br><br>" . "<b>" . _FILESIZE . ":</b><br><input type=\"text\" name=\"filesize\" size=\"10\" maxlength=\"10\"> (<i>bytes, " . _SIZENOTE . "</i>)<br><br>" . "<input type=\"hidden\" name=\"op\" value=\"file_save\">" . "<input type=\"submit\" value=\"" . _ADD . "\">" . "</form>";
		CloseTable();
		include ( "../footer.php" );
	}

	/**
	 * file_save()
	 * 
	 * @param mixed $title
	 * @param mixed $description
	 * @param mixed $cid
	 * @param mixed $filelink
	 * @param mixed $author
	 * @param mixed $authormail
	 * @param mixed $authorurl
	 * @param mixed $version
	 * @param mixed $filesize
	 * @return
	 */
	function file_save( $title, $description, $cid, $filelink, $author, $authormail, $authorurl, $version, $filesize )
	{
		global $adminfile, $prefix, $db, $path, $apath, $nukeurl;

		if ( $filelink == "http://" )
		{
			$filelink = "";
		}
		if ( $authorurl == "http://" )
		{
			$authorurl = "";
		}
		$title = stripslashes( FixQuotes($title) );
		$description = stripslashes( FixQuotes($description) );
		$filelink = stripslashes( FixQuotes($filelink) );
		$author = stripslashes( FixQuotes($author) );
		$authormail = stripslashes( FixQuotes($authormail) );
		$authorurl = stripslashes( FixQuotes($authorurl) );
		$version = stripslashes( FixQuotes($version) );
		$cid = intval( $cid );


		if ( (is_uploaded_file($_FILES['userfile']['tmp_name'])) )
		{
			if ( (file_exists($apath . $_FILES['userfile']['name'])) )
			{
				include ( "../header.php" );

				fileadminmenu();
				filesmanagermenu();
				OpenTable();
				echo "<center><br><br>" . _FILEEXIST . "<br><br></center>\n";
				CloseTable();
				include ( "../footer.php" );
				exit;
			}
			$filename = $_FILES['userfile']['name'];
			$filesize = $_FILES['userfile']['size'];
			if ( ! @copy($_FILES['userfile']['tmp_name'], "$apath/$filename") )
			{
				if ( ! move_uploaded_file($_FILES['userfile']['tmp_name'], $apath . "/" . $filename) )
				{
					include ( "../header.php" );

					fileadminmenu();
					filesmanagermenu();
					OpenTable();
					echo "<center><br><br>" . _UPLOADEROR . " $path .<br><br></center>\n";
					CloseTable();
					include ( "../footer.php" );
					exit;
				}
			}
			$filename = "" . $nukeurl . "/" . $path . "/" . $filename . "";
		}
		else
		{
			if ( $filelink == "" )
			{
				include ( "../header.php" );

				fileadminmenu();
				filesmanagermenu();
				OpenTable();
				echo "<center><br><br>" . _UPLOADEROR2 . "<br><br></center>\n";
				CloseTable();
				include ( "../footer.php" );
			}
			$filename = $filelink;
			$filesize = intval( $filesize );
		}

		$db->sql_query( "INSERT INTO " . $prefix . "_files (lid, cid, title, description, url, date, filesize, version, name, email, homepage, status) VALUES (NULL, '$cid', '$title', '$description', '$filename', now(), '$filesize', '$version', '$author', '$authormail', '$authorurl', '1')" );
		Header( "Location: " . $adminfile . ".php?op=AddNewFile" );
	}

	/**
	 * edit_files()
	 * 
	 * @param mixed $lid
	 * @return
	 */
	function edit_files( $lid )
	{
		global $adminfile, $prefix, $db, $editor;

		$sql = "SELECT cid, title, description, url, filesize, version, name, email, homepage, status FROM " . $prefix . "_files WHERE lid='$lid'";
		$result = $db->sql_query( $sql );
		$row = $db->sql_fetchrow( $result );
		$f_cid = $row['cid'];
		$f_title = $row['title'];
		$f_description = $row['description'];
		$f_url = $row['url'];
		$f_filesize = $row['filesize'];
		$f_version = $row['version'];
		$f_name = $row['name'];
		$f_email = $row['email'];
		$f_homepage = $row['homepage'];
		$f_status = $row['status'];
		include ( "../header.php" );

		fileadminmenu();
		filesmanagermenu();
		OpenTable();
		echo "<center><b>" . _FILESADMIN . "</b></center>\n";
		CloseTable();
		echo "<br>";
		OpenTable();
		echo "<center><b>" . _FILEEDIT . "</b></center><br><br>" . "<form action=\"" . $adminfile . ".php\" method=\"post\">" . "<b>" . _TITLE . ":</b><br><input type=\"text\" name=\"title\" size=\"50\" value=\"$f_title\"><br><br>" . "<b>" . _CATEGORY . ":</b><br>" . "<select name=\"cid\"><option value=\"0\">" . _HOMEFILES . "</option>";
		$sql = "select cid, title, parentid from " . $prefix . "_files_categories order by parentid,title";
		$result = $db->sql_query( $sql );
		while ( $row = $db->sql_fetchrow($result) )
		{
			$cid2 = $row['cid'];
			$title = $row['title'];
			$parentid2 = $row['parentid'];
			if ( $parentid2 != 0 ) $title = getparent( $parentid2, $title );
			if ( $cid2 == $f_cid )
			{
				$shel = "selected";
			}
			else
			{
				$shel = "";
			}
			echo "<option value=\"$cid2\" $shel>$title</option>";

		}
		echo "</select><br><br>";
		echo "<b>" . _SUBTITLE . ":</b><br>";
		if ( $editor == 1 )
		{
			aleditor( "description", $f_description, 500, 250 );
		}
		else
		{
			echo "<textarea wrap=\"virtual\" cols=\"50\" rows=\"10\" name=\"description\">$f_description</textarea><br><br>";
		}
		echo "<b>" . _FILEAUTOR . "</b><br><input type=\"text\" name=\"author\" size=\"50\" value=\"$f_name\"><br><br>" . "<b>" . _FAUEMAIL . "</b><br><input type=\"text\" name=\"authormail\" size=\"50\" value=\"$f_email\"><br><br>" . "<b>" . _FAUURL . "</b><br><input type=\"text\" name=\"authorurl\" size=\"50\" value=\"$f_homepage\"><br><br>" . "<b>" . _FILELINK . ":</b><br><input type=\"text\" name=\"filelink\" size=\"50\" value=\"$f_url\">  [ <a href=\"$f_url\" target=\"_blank\">" . _TESTLINK . "</a>  ]<br><br>" . "<b>" . _FILEVERSION . "</b><br><input type=\"text\" name=\"version\" size=\"10\" value=\"$f_version\"><br><br>" . "<b>" . _FILESIZE . ":</b><br><input type=\"text\" name=\"filesize\" size=\"10\" value=\"$f_filesize\"><br><br>" . "<input type=\"hidden\" name=\"lid\" value=\"$lid\">" . "<input type=\"hidden\" name=\"op\" value=\"file_edit_save\">" . "<input type=\"submit\" value=\"" . _SAVEFILEED . "\">" . "</form>";
		CloseTable();
		echo "<br>";
		include ( "../footer.php" );
	}

	/**
	 * file_edit_save()
	 * 
	 * @param mixed $lid
	 * @param mixed $title
	 * @param mixed $description
	 * @param mixed $cid
	 * @param mixed $filelink
	 * @param mixed $author
	 * @param mixed $authormail
	 * @param mixed $authorurl
	 * @param mixed $version
	 * @param mixed $filesize
	 * @return
	 */
	function file_edit_save( $lid, $title, $description, $cid, $filelink, $author, $authormail, $authorurl, $version, $filesize )
	{
		global $adminfile, $prefix, $db;
		$db->sql_query( "UPDATE " . $prefix . "_files SET cid='$cid', title='$title', description='$description', url='$filelink', filesize='$filesize', version='$version', name='$author', email='$authormail', homepage='$authorurl'  WHERE lid='$lid'" );
		Header( "Location: " . $adminfile . ".php?op=ListFilesAdded" );
	}

	/**
	 * delit_file()
	 * 
	 * @param mixed $lid
	 * @param integer $ok
	 * @return
	 */
	function delit_file( $lid, $ok = 0 )
	{
		global $adminfile, $prefix, $db, $temp_apath, $apath;
		if ( $ok == 1 )
		{
			$sql = "SELECT url FROM " . $prefix . "_files where lid=$lid";
			$result = $db->sql_query( $sql );
			$row = $db->sql_fetchrow( $result );
			$filelink = $row[url];
			$filel = array_reverse( explode("/", $filelink) );
			if ( file_exists("" . $temp_apath . "/" . $filel[0] . "") )
			{
				$delf = "" . $temp_apath . "/" . $filel[0] . "";
				@unlink( $delf );
			}
			if ( file_exists("" . $apath . "/" . $filel[0] . "") )
			{
				$delf = "" . $apath . "/" . $filel[0] . "";
				@unlink( $delf );
			}
			$db->sql_query( "delete from " . $prefix . "_files_comments where lid=$lid" );
			$db->sql_query( "delete from " . $prefix . "_files where lid=$lid" );
			Header( "Location: " . $adminfile . ".php?op=ListFilesAdded" );
		}
		else
		{
			include ( "../header.php" );

			fileadminmenu();
			filesmanagermenu();
			OpenTable();
			echo "<center><b>" . _DELFILES . "</b></center><br><br>";
			$sql = "select title from " . $prefix . "_files where lid='$lid'";
			$result = $db->sql_query( $sql );
			$row = $db->sql_fetchrow( $result );
			$title = $row['title'];

			echo "<center><b>" . _DELFILE . ": $title</b><br><br>" . "" . _DELFILENOTE . "<br><br>" . "[ <a href=\"" . $adminfile . ".php?op=ListFilesAdded\">" . _NO . "</a> | <a href=\"" . $adminfile . ".php?op=delit_file&amp;lid=$lid&amp;ok=1\">" . _YES . "</a> ]</center>";
			CloseTable();
			include ( "../footer.php" );
		}
	}

	/**
	 * FilesWaiting()
	 * 
	 * @return
	 */
	function FilesWaiting()
	{
		global $adminfile, $prefix, $db, $bgcolor1, $bgcolor2;
		include ( "../header.php" );

		fileadminmenu();
		filesmanagermenu();
		echo "<center><b>" . _ADDNEWFILES . "</b></center>";
		echo "<br>";
		$sql = "SELECT lid, title, description, ip_sender  FROM " . $prefix . "_files WHERE status='0'";
		$result = $db->sql_query( $sql );
		$num = $db->sql_numrows( $result );
		if ( $num > 0 )
		{
			echo "<table border=\"0\" cellpadding=\"5\" cellspacing=\"1\" width=\"100%\" bgcolor=\"$bgcolor2\">";
			echo "<td width=\"20%\" bgcolor=\"$bgcolor1\" align=\"center\"><b>" . _TITLE . "</b></td>";
			echo "<td width=\"45%\" bgcolor=\"$bgcolor1\" align=\"center\"><b>" . _SUBTITLE . "<b></td>";
			echo "<td width=\"15%\" bgcolor=\"$bgcolor1\" align=\"center\"><b>" . _IPSENDER . "<b></td>";
			echo "<td width=\"20%\" bgcolor=\"$bgcolor1\" align=\"center\"><b>" . _FUNCTIONS . "</b></td></tr>";
			while ( $row = $db->sql_fetchrow($result) )
			{
				$lid = $row['lid'];
				$title = $row['title'];
				$description = $row['description'];
				$ip_sender = $row['ip_sender'];
				echo "<td width=\"20%\" bgcolor=\"$bgcolor1\" align=\"center\"><a href=\"" . $adminfile . ".php?op=view_add_file&lid=$lid\" title=\"" . _DETFILES . "\"><b>$title</b></td>";
				echo "<td width=\"45%\" bgcolor=\"$bgcolor1\">$description</td>";
				echo "<td width=\"15%\" bgcolor=\"$bgcolor1\" align=\"center\"><a href=\"" . $adminfile . ".php?op=ConfigureBan&bad_ip=$ip_sender\" title=\"" . _BANIPSENDER . "\"><b>$ip_sender</b></a></td>" . "<td width=\"20%\" bgcolor=\"$bgcolor1\" align=\"center\">" . "<a href=\"" . $adminfile . ".php?op=view_add_file&lid=$lid\" title=\"" . _EDIT . "\">" . _EDIT . "&nbsp;|&nbsp;" . "<a href=\"" . $adminfile . ".php?op=delit_file&lid=$lid\" title=\"" . _DELETE . "\">" . _DELETE . "" . "</td>" . "</tr>";
			}
			echo "</table><br><br>";
		}
		else
		{
			echo "<br><br><center>" . _NONEWFILES . "</center><br><br>";
		}
		include ( "../footer.php" );
	}

	/**
	 * brocen_files()
	 * 
	 * @return
	 */
	function brocen_files()
	{
		global $adminfile, $prefix, $db, $bgcolor1, $bgcolor2;
		include ( "../header.php" );

		fileadminmenu();
		OpenTable();
		echo "<center><b>" . _ADDBROCFILES . "</b></center>";
		CloseTable();
		echo "<br>";
		OpenTable();
		$sql = "SELECT lid, title, url, ip_sender  FROM " . $prefix . "_files WHERE status='2'";
		$result = $db->sql_query( $sql );
		$num = $db->sql_numrows( $result );
		if ( $num > 0 )
		{
			echo "<table border=\"0\" cellpadding=\"5\" cellspacing=\"1\" width=\"100%\" bgcolor=\"$bgcolor2\">";
			echo "<td width=\"20%\" bgcolor=\"$bgcolor1\" align=\"center\"><b>" . _TITLE . "</b></td>";
			echo "<td width=\"80%\" colspan=\"4\" bgcolor=\"$bgcolor1\" align=\"center\"><b>" . _FADMINST . "</b></td></tr>";
			while ( $row = $db->sql_fetchrow($result) )
			{
				$lid = $row['lid'];
				$title = $row['title'];
				$url = $row['url'];
				$ip_sender = $row['ip_sender'];
				echo "<td width=\"20%\" bgcolor=\"$bgcolor1\" align=\"center\"><a href=\"$url\" target=\"_blank\"><b>$title</b></a></td>";
				echo "<td width=\"20%\" bgcolor=\"$bgcolor1\" align=\"center\"><a href=\"" . $adminfile . ".php?op=edit_files&lid=$lid\"><b>" . _EDITFILE . "</b></a></td>";
				echo "<td width=\"20%\" bgcolor=\"$bgcolor1\" align=\"center\"><a href=\"" . $adminfile . ".php?op=delit_file&lid=$lid\"><b>" . _DELITFILE . "</b></a></td>";
				echo "<td width=\"20%\" bgcolor=\"$bgcolor1\" align=\"center\"><a href=\"" . $adminfile . ".php?op=ignore_broc&lid=$lid\"><b>" . _IGNORE . "</b></a></td>";
				echo "<td width=\"20%\" bgcolor=\"$bgcolor1\" align=\"center\"><a href=\"" . $adminfile . ".php?op=ConfigureBan&bad_ip=$ip_sender\" title=\"" . _BANIPSENDER . "\"><b>$ip_sender</b></a></td></tr>";
			}
			echo "</table><br><br>";
		}
		else
		{
			echo "<br><br><center>" . _NOBROCFILES . "</center><br><br>";
		}
		CloseTable();
		include ( "../footer.php" );
	}

	/**
	 * ignore_broc()
	 * 
	 * @param mixed $lid
	 * @return
	 */
	function ignore_broc( $lid )
	{
		global $adminfile, $prefix, $db;
		$db->sql_query( "UPDATE " . $prefix . "_files SET status='1' WHERE lid='$lid'" );
		Header( "Location: " . $adminfile . ".php?op=ListFilesAdded" );
	}

	/**
	 * view_add_file()
	 * 
	 * @param mixed $lid
	 * @return
	 */
	function view_add_file( $lid )
	{
		global $adminfile, $prefix, $db;
		$sql = "SELECT cid, title, description, url, filesize, version, name, email, homepage, status FROM " . $prefix . "_files WHERE lid='$lid'";
		$result = $db->sql_query( $sql );
		$row = $db->sql_fetchrow( $result );
		$f_cid = $row['cid'];
		$f_title = $row['title'];
		$f_description = $row['description'];
		$f_url = $row['url'];
		$f_filesize = $row['filesize'];
		$f_version = $row['version'];
		$f_name = $row['name'];
		$f_email = $row['email'];
		$f_homepage = $row['homepage'];
		$f_status = $row['status'];
		include ( "../header.php" );

		fileadminmenu();
		filesmanagermenu();
		echo "<br>";
		OpenTable();
		echo "<center><b>" . _FILEEDIT . "</b></center><br><br>" . "<form action=\"" . $adminfile . ".php\" method=\"post\">" . "<b>" . _TITLE . ":</b><br><input type=\"text\" name=\"title\" size=\"50\" value=\"$f_title\"><br><br>" . "<b>" . _CATEGORY . ":</b><br>" . "<select name=\"cid\"><option value=\"0\">" . _HOMEFILES . "</option>";
		$sql = "select cid, title, parentid from " . $prefix . "_files_categories order by parentid,title";
		$result = $db->sql_query( $sql );
		while ( $row = $db->sql_fetchrow($result) )
		{
			$cid2 = $row['cid'];
			$title = $row['title'];
			$parentid2 = $row['parentid'];
			if ( $parentid2 != 0 ) $title = getparent( $parentid2, $title );
			if ( $cid2 == $f_cid )
			{
				$shel = "selected";
			}
			echo "<option value=\"$cid2\" $shel>$title</option>";
		}
		echo "</select><br><br>";
		echo "<b>" . _SUBTITLE . ":</b><br>" . "<textarea name=\"description\" cols=\"50\" rows=\"10\">$f_description</textarea><br><br>" . "<b>" . _FILEAUTOR . "</b><br><input type=\"text\" name=\"author\" size=\"50\" value=\"$f_name\"><br><br>" . "<b>" . _FAUEMAIL . "</b><br><input type=\"text\" name=\"authormail\" size=\"50\" value=\"$f_email\"><br><br>" . "<b>" . _FAUURL . "</b><br><input type=\"text\" name=\"authorurl\" size=\"50\" value=\"$f_homepage\"><br><br>" . "<b>" . _FILELINK . ":</b><br><input type=\"text\" name=\"filelink\" size=\"50\" value=\"$f_url\">  [ <a href=\"$f_url\" target=\"_blank\">" . _TESTLINK . "</a>  ]<br><br>" . "<b>" . _FILEVERSION . "</b><br><input type=\"text\" name=\"version\" size=\"10\" value=\"$f_version\"><br><br>" . "<b>" . _FILESIZE . ":</b><br><input type=\"text\" name=\"filesize\" size=\"10\" value=\"$f_filesize\"><br><br>" . "<input type=\"hidden\" name=\"lid\" value=\"$lid\">" . "<input type=\"hidden\" name=\"op\" value=\"file_add_save\">" .
			"<input type=\"submit\" value=\"" . _ADDFILE . "\"> [ <a href=" . $adminfile . ".php?op=delit_file&lid=$lid>" . _FDELETE . "</a> ]" . "</form>";
		CloseTable();
		echo "<br>";
		include ( "../footer.php" );
	}

	/**
	 * file_add_save()
	 * 
	 * @param mixed $lid
	 * @param mixed $title
	 * @param mixed $description
	 * @param mixed $cid
	 * @param mixed $filelink
	 * @param mixed $author
	 * @param mixed $authormail
	 * @param mixed $authorurl
	 * @param mixed $version
	 * @param mixed $filesize
	 * @return
	 */
	function file_add_save( $lid, $title, $description, $cid, $filelink, $author, $authormail, $authorurl, $version, $filesize )
	{
		global $adminfile, $prefix, $db, $temp_apath, $path, $apath, $nukeurl;
		$filel = array_reverse( explode("/", $filelink) );
		if ( file_exists("" . $temp_apath . "/" . $filel[0] . "") )
		{
			$oldfile = "" . $temp_apath . "/" . $filel[0] . "";
			$newfile = "" . $apath . "/" . $filel[0] . "";
			@rename( $oldfile, $newfile );
			$filelink = "" . $nukeurl . "/" . $path . "/" . $filel[0] . "";
		}
		$db->sql_query( "UPDATE " . $prefix . "_files SET cid='$cid', title='$title', description='$description', url='$filelink', filesize='$filesize', version='$version', name='$author', email='$authormail', homepage='$authorurl', status='1'  WHERE lid='$lid'" );
		Header( "Location: " . $adminfile . ".php?op=ListFilesAdded" );

	}

	/**
	 * delit_file_comment()
	 * 
	 * @param mixed $tid
	 * @param mixed $lid
	 * @param integer $ok
	 * @return
	 */
	function delit_file_comment( $tid, $lid, $ok = 0 )
	{
		global $adminfile, $prefix, $db;
		if ( $ok == 1 )
		{
			$db->sql_query( "delete from " . $prefix . "_files_comments where tid=$tid" );
			$db->sql_query( "UPDATE " . $prefix . "_files SET totalcomments=totalcomments-1 WHERE lid='$lid'" );
			Header( "Location: " . $adminfile . ".php?op=FilesComment" );
		}
		else
		{
			include ( "../header.php" );

			fileadminmenu();
			OpenTable();
			echo "<center><b>" . _DELFILEC . "</b></center><br><br>";
			$sql = "select * from " . $prefix . "_files_comments where tid='$tid'";
			$result = $db->sql_query( $sql );
			$row = $db->sql_fetchrow( $result );
			$f_comment = $row['comment'];
			$f_subject = $row['subject'];
			$f_lid = $row['lid'];
			echo "<center><b>" . _DELFILESCOM . ": </b>$f_lid";
			Opentable();
			echo "<b>" . _SUBJECT . "</b>: $f_subject<br><b>" . _CONTENT . "</b>: $f_comment";
			Closetable();
			echo "" . _DELFILECOMNOTE . "<br><br>" . "[ <a href=\"" . $adminfile . ".php?op=FilesComment\">" . _NO . "</a> | <a href=\"" . $adminfile . ".php?op=delit_file_comment&amp;tid=$tid&&lid=$lid&amp;ok=1\">" . _YES . "</a> ]</center>";
			CloseTable();
			include ( "../footer.php" );
		}
	}

	/**
	 * FilesComment()
	 * 
	 * @return
	 */
	function FilesComment()
	{
		global $adminfile, $db, $prefix;
		include ( "../header.php" );

		fileadminmenu();
		OpenTable();
		echo "<div align='center'><b>" . _COMMENTADMIN . "</b></div>\n";
		CloseTable();
		echo "<br>";
		OpenTable();
		echo "<br><br><center>" . "<form action=\"" . $adminfile . ".php\" method=\"post\">" . "" . _COMMID . ": <input type=\"text\" NAME=\"tid\" SIZE=\"10\"> " . "<input type=\"hidden\" NAME=\"lid\" SIZE=\"10\">" . "<select name=\"op\">" . "<option value=\"EditFilesComment\" SELECTED>" . _EDIT . "</option>" . "<option value=\"delit_file_comment\">" . _DELETE . "</option>" . "</select> " . "<input type=\"submit\" value=\"" . _GO . "\">" . "</form></center>";
		CloseTable();
		echo "<br>";
		OpenTable();
		$num_comno = $db->sql_fetchrow( $db->sql_query("SELECT COUNT(tid) FROM " . $prefix . "_files_comments") );
		$all_page = $num_comno[0] ? $num_comno[0] : 1;
		$page = isset( $_GET['page'] ) ? intval( $_GET['page'] ) : 0;
		$perpage = 10;
		$base_url = "" . $adminfile . ".php?op=FilesComment";
		$sql_comno = "SELECT a.tid as tid, a.lid as lid, a.name as name, a.comment as comment, b.title as title FROM " . $prefix . "_files_comments a, " . $prefix . "_files b WHERE b.lid=a.lid ORDER BY a.tid DESC LIMIT $page, $perpage";
		$res_comno = $db->sql_query( $sql_comno );
		echo "<table width='100%' border='0' cellpadding='5' style='border-collapse: collapse'>\n";
		echo "<tr>\n";
		echo "<td align='center'><b>" . _COMMOK . "</b></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td align='center'>\n";
		echo "<table width='90%' border='1' cellpadding='3' cellspacing='1' style='border-collapse: collapse'>\n";
		echo "<tr>\n";
		echo "<td width='26' align='center'><b>TID</b></td>\n";
		echo "<td width='100' align='center'><b>" . _COMMNAME . "</b></td>\n";
		echo "<td align='center'><b>" . _COMMTITLE . "</b></td>\n";
		echo "<td width='150' align='center'><b>" . _FUNCTIONS . "</b></td>\n";
		echo "</tr>\n";
		$j = 1;
		while ( $row_comno = $db->sql_fetchrow($res_comno) )
		{
			echo "<tr>\n";
			echo "<td align='center'>" . $row_comno['tid'] . "</td>\n";
			echo "<td>" . $row_comno['name'] . "</td>\n";
			echo "<td><a href=\"../modules.php?name=Files&go=view_file&lid=" . $row_comno['lid'] . "\">" . $row_comno['title'] . "</a><br><i>" . $row_comno['comment'] . "</i></td>\n";
			echo "<td align='center'><a href='" . $adminfile . ".php?op=EditFilesComment&amp;tid=" . $row_comno['tid'] . "'>" . _EDIT . "</a>&nbsp;|&nbsp;<a href='" . $adminfile . ".php?op=delit_file_comment&tid=" . $row_comno['tid'] . "&lid=" . $row_comno['lid'] . "'>" . _DELETE . "</a></td>\n";
			echo "</tr>\n";
			$j++;
		}
		echo "</table>\n";
		echo "</td>\n";
		echo "</tr>\n";
		if ( $all_page > $perpage )
		{
			echo "<tr>\n";
			echo "<td>" . generate_page( $base_url, $all_page, $perpage, $page ) . "</td>\n";
			echo "</tr>\n";
		}
		echo "</table>\n";
		CloseTable();

		include ( "../footer.php" );
	}

	/**
	 * EditFilesComment()
	 * 
	 * @param mixed $tid
	 * @return
	 */
	function EditFilesComment( $tid )
	{
		global $adminfile, $prefix, $db;
		$result = $db->sql_query( "SELECT * FROM " . $prefix . "_files_comments where tid='" . intval($tid) . "'" );
		if ( $db->sql_numrows($result) == 0 )
		{
			Header( "Location: " . $adminfile . ".php?op=FilesComment" );
			exit;
		}
		$row = $db->sql_fetchrow( $result );
		$lid = intval( $row['lid'] );
		$sender_name = $row['name'];
		$sender_email = $row['email'];
		$sender_url = $row['url'];
		$sender_host = $row['host_name'];


		$com_subject = htmlspecialchars( trim($row['subject']) );
		$com_text = cheonguoc( stripslashes($row['comment']) );
		include ( '../header.php' );

		fileadminmenu();
		OpenTable();
		echo "<center><font class=\"title\"><b>" . _EDITSTORIESCOMMENT . "</b></font></center>";
		CloseTable();
		echo "<br>";
		OpenTable();
		echo "<form enctype=\"multipart/form-data\" action=\"" . $adminfile . ".php\" method=\"post\">\n" . "<b>" . _SUBJECT . "</b><br><input type=\"text\" name=\"com_subject\" size=\"70\" value=\"$com_subject\"><br><br>\n" . "<b>" . _CONTENT . "</b><br><textarea wrap=\"virtual\" cols=\"70\" rows=\"7\" name=\"com_text\">$com_text</textarea><br><br>\n" . "<b>" . _SENDERNAME . "</b><br><input type=\"text\" name=\"sender_name\" size=\"20\" value=\"$sender_name\"><br><br>\n" . "<b>" . _EMAIL . "</b><br><input type=\"text\" name=\"sender_email\" size=\"20\" value=\"$sender_email\"><br><br>\n" . "<b>" . _URL . "</b><br><input type=\"text\" name=\"sender_url\" size=\"20\" value=\"$sender_url\"><br><br>\n" . "<input type=\"hidden\" name=\"sid\" value=\"$sid\">\n" . "<input type=\"hidden\" name=\"tid\" value=\"$tid\">\n" . "<select name=\"op\">\n" . "<option value=\"SaveEditFilesComment\" selected>" . _SAVE . "</option>\n" . "<option value=\"delit_file_comment\">" . _REMOVECOMMENTS . "</option>\n" .
			"</select>\n" . "<input type=\"submit\" value=\"" . _OK . "\">\n</form><br>\n" . "[ <a href='" . $adminfile . ".php?op=ConfigureBan&bad_ip=$sender_host'>" . _IPBANLIST . ": $sender_host</a> ]\n";
		CloseTable();
		include ( "../footer.php" );
	}

	/**
	 * SaveEditFilesComment()
	 * 
	 * @param mixed $tid
	 * @param mixed $lid
	 * @param mixed $sender_name
	 * @param mixed $sender_email
	 * @param mixed $sender_url
	 * @param mixed $com_subject
	 * @param mixed $com_text
	 * @return
	 */
	function SaveEditFilesComment( $tid, $lid, $sender_name, $sender_email, $sender_url, $com_subject, $com_text )
	{
		global $adminfile, $prefix, $db;
		$tid = intval( $tid );
		$lid = intval( $lid );
		$com_subject = stripslashes( FixQuotes($com_subject) );
		$com_text = cheonguoc( nl2brStrict(stripslashes(FixQuotes($com_text, "nohtml"))) );
		if ( $db->sql_numrows($db->sql_query("SELECT * FROM " . $prefix . "_files_comments where tid=$tid")) == 0 )
		{
			Header( "Location: " . $adminfile . ".php?op=newsadminhome" );
			exit;
		}
		$result2 = $db->sql_query( "UPDATE " . $prefix . "_files_comments SET name='$sender_name', email='$sender_email', url='$sender_url', subject='$com_subject', comment='$com_text' WHERE tid='$tid'" );
		if ( ! $result2 )
		{
			return;
		}
		info_exit( "<br><br><center><b>" . _NEWSCOMSAVED . "</b></center><br><br><META HTTP-EQUIV=\"refresh\" content=\"2;" . $adminfile . ".php?op=FilesComment\">" );
	}


	switch ( $op )
	{

		case "FilesComment":
			FilesComment();
			break;

		case "EditFilesComment":
			EditFilesComment( $tid );
			break;

		case "SaveEditFilesComment":
			SaveEditFilesComment( $tid, $lid, $sender_name, $sender_email, $sender_url, $com_subject, $com_text );
			break;

		case "ListFilesAdded":
			ListFilesAdded( $booknum );
			break;

		case "files":
			files();
			break;

		case "FilesSetting":
			FilesSetting();
			break;

		case "FilesSettingSave":
			FilesSettingSave();
			break;

		case "add_files_category":
			add_files_category( $title, $description );
			break;

		case "add_files_sub_cat":
			add_files_sub_cat( $cid, $title, $description );
			break;

		case "edit_files_category":
			edit_files_category( $cid );
			break;

		case "save_files_category":
			save_files_category( $cid, $title, $description, $parentid );
			break;

		case "del_files_cat":
			del_files_cat( $cid, $ok );
			break;

		case "AddNewFile":
			AddNewFile();
			break;

		case "file_save":
			file_save( $title, $description, $cid, $filelink, $author, $authormail, $authorurl, $version, $filesize );
			break;

		case "FilesWaiting":
			FilesWaiting();
			break;

		case "brocen_files":
			brocen_files();
			break;

		case "edit_files":
			edit_files( $lid );
			break;

		case "file_edit_save":
			file_edit_save( $lid, $title, $description, $cid, $filelink, $author, $authormail, $authorurl, $version, $filesize );
			break;

		case "delit_file":
			delit_file( $lid, $ok );
			break;

		case "view_add_file":
			view_add_file( $lid );
			break;

		case "ignore_broc":
			ignore_broc( $lid );
			break;

		case "MoveFilesCat":
			MoveFilesCat();
			break;


		case "MoveFilesCatSave":
			MoveFilesCatSave( $fromcid, $incid );
			break;

		case "file_add_save":
			file_add_save( $lid, $title, $description, $cid, $filelink, $author, $authormail, $authorurl, $version, $filesize );
			break;

		case "delit_file_comment":
			delit_file_comment( $tid, $lid, $ok );
			break;

	}

}
else
{
	include ( "../header.php" );

	OpenTable();
	echo "<center><b>" . _ERROR . "</b><br><br>" . _NOTAUTHORIZED . "</center>";
	CloseTable();
	include ( "../footer.php" );
}

?>