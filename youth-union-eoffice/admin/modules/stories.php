<?php

/*
* @Program:		NukeViet CMS
* @File name: 	NukeViet System
* @Version: 	2.0 RC2
* @Date: 		16.06.2009
* @Website: 	www.nukeviet.vn
* @Copyright: 	(C) 2009
* @License: 	http://opensource.org/licenses/gpl-license.php GNU Public License
*/

if ( ! defined('NV_ADMIN') )
{
	die( "Access Denied" );
}

$checkmodname = "News";
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
	 * AddTTD()
	 * 
	 * @return
	 */
	function AddTTD()
	{
		global $adminfile, $path, $sizeimgskqa, $prefix, $db;
		$t = intval( $_GET['t'] );
		$sid = intval( $_GET['sid'] );
		list( $alanguage, $images ) = $db->sql_fetchrow( $db->sql_query("select alanguage, images from " . $prefix . "_stories where sid='" . $sid . "'") );
		list( $images_del ) = $db->sql_fetchrow( $db->sql_query("select images from " . $prefix . "_stories where newsst='1' AND alanguage='$alanguage'") );
		@unlink( "" . INCLUDE_PATH . "" . $path . "/nst_" . $images_del . "" );
		if ( $t == 1 )
		{
			if ( $images != "" and file_exists("" . INCLUDE_PATH . "" . $path . "/" . $images . "") )
			{
				$size2 = @getimagesize( "" . INCLUDE_PATH . "" . $path . "/" . $images . "" );
				$widthimg = $size2[0];
				$f_name = explode( ".", $images );
				$f_num = sizeof( $f_name ) - 1;
				$f_extension = strtolower( $f_name[$f_num] );
				if ( ($widthimg > $sizeimgskqa) and ($f_extension == "jpg") and (extension_loaded("gd")) )
				{
					$outpath = "" . INCLUDE_PATH . "" . $path . "";
					$inpath = "" . INCLUDE_PATH . "" . $path . "";
					$outimg = "" . $images . "";
					$inimg = "nst_" . $images . "";
					$insize = "" . $sizeimgskqa . "";
					thumbs( $outpath, $inpath, $outimg, $inimg, $insize );
				}
			}
		}
		$db->sql_query( "UPDATE " . $prefix . "_stories SET newsst='0' WHERE alanguage='$alanguage'" );
		$db->sql_query( "UPDATE " . $prefix . "_stories SET newsst='$t' WHERE sid='$sid' AND alanguage='$alanguage'" );
		ncatlist();
		Header( "Location: " . $adminfile . ".php?op=newsadminhome" );
		exit();
	}

	/**
	 * fixweightcat()
	 * 
	 * @return
	 */
	function fixweightcat()
	{
		global $prefix, $db;
		$sql = "SELECT catid, parentid FROM " . $prefix . "_stories_cat WHERE parentid='0' ORDER BY weight ASC";
		$result = $db->sql_query( $sql );
		$weight = 0;
		while ( $row = $db->sql_fetchrow($result) )
		{
			$catid = intval( $row['catid'] );
			$parentid = intval( $row['parentid'] );
			$weight++;
			$db->sql_query( "UPDATE " . $prefix . "_stories_cat SET weight='$weight' WHERE catid='$catid'" );
			$sql2 = "SELECT catid FROM " . $prefix . "_stories_cat WHERE parentid='$catid' ORDER BY weight ASC";
			$result2 = $db->sql_query( $sql2 );
			$weight2 = 0;
			while ( $row2 = $db->sql_fetchrow($result2) )
			{
				$catid2 = intval( $row2['catid'] );
				$weight2++;
				$db->sql_query( "UPDATE " . $prefix . "_stories_cat SET weight='$weight2' WHERE catid='$catid2'" );
			}
		}
	}

	/**
	 * newstopbanner()
	 * 
	 * @return
	 */
	function newstopbanner()
	{
		global $adminfile;
		OpenTable();
		echo "<center><b><a href=\"" . $adminfile . ".php?op=newsadminhome\"><b>" . _ARTICLEADMIN . "</b></a></b><br>\n<br>\n" . "<a href=\"" . $adminfile . ".php?op=newsconfig\"><b>" . _CONFIGNEWS . "</b></a> | \n" . "<a href=\"" . $adminfile . ".php?op=ManagerCategory\"><b>" . _CATEGORIESADMIN . "</b></a> | \n" . "<a href=\"" . $adminfile . ".php?op=ManagerTopic\"><b>" . _TOPICSADMIN . "</b></a><br>\n" . "<a href=\"" . $adminfile . ".php?op=Imggallery\"><b>" . _IMGGALLERY . "</b></a> | \n" . "<a href=\"" . $adminfile . ".php?op=adminnews\"><b>" . _NEWSADMIN . "</b></a> | \n" . "<a href=\"" . $adminfile . ".php?op=Comment\"><b>" . _DUYETCOMM . "</b></a> | \n" . "<a href=\"" . $adminfile . ".php?op=Commentok\"><b>" . _CHECKCOMM . "</b></a>\n" . "</center>\n";
		CloseTable();
		echo "<br>\n";
	}

	/**
	 * newsadminhome()
	 * 
	 * @return
	 */
	function newsadminhome()
	{
		global $adminfile, $prefix, $user_prefix, $db, $radminsuper, $anonymous;
		include ( "../header.php" );
		GraphicAdmin();
		newstopbanner();
		$sql = "SELECT sid, title, aid, UNIX_TIMESTAMP(time) as formatted, hometext FROM " . $prefix . "_stories_temp order by time DESC";
		$result = $db->sql_query( $sql );
		if ( $db->sql_numrows($result) != 0 )
		{
			OpenTable();
			echo "<center><font class=\"title\"><b>" . _NEWSUBMISSIONS . "</b></font></center><br>";
			echo "<center><table width=\"100%\" border=\"1\">\n";
			while ( $row = $db->sql_fetchrow($result) )
			{
				$sid = intval( $row['sid'] );
				$subject = $row['title'];
				$timestamp = viewtime( $row['formatted'], 2 );
				$author = $row['aid'];
				$hometext = stripslashes( $row['hometext'] );
				echo "<tr>\n<td width=\"100%\"><font class=\"content\">\n";
				if ( $subject == "" )
				{
					echo "&nbsp;<a href=\"" . $adminfile . ".php?op=DisplayStory&amp;sid=$sid\" title=\"$hometext\">" . _NOSUBJECT . "</a></font>\n";
				}
				else
				{
					echo "&nbsp;<a href=\"" . $adminfile . ".php?op=DisplayStory&amp;sid=$sid\" title=\"$hometext\">$subject</a></font>\n";
				}
				list( $u_id ) = $db->sql_fetchrow( $db->sql_query("select user_id from " . $user_prefix . "_users where username='$author'") );
				if ( $author != $anonymous and $u_id > 1 )
				{
					$author = "<a href=\"" . $adminfile . ".php?op=modifyUser&chng_uid=$u_id\">$author</a>";
				}
				echo "</td><td align=\"center\">$author\n";
				echo "</td align=\"center\"><td><font class=\"content\">&nbsp;$timestamp&nbsp;</font></td>" . "<td  align=\"right\" nowrap><font class=\"content\">&nbsp;(<a href=\"" . $adminfile . ".php?op=DisplayStory&amp;sid=$sid\">" . _EDIT . "</a>-<a href=\"" . $adminfile . ".php?op=DeleteStory&amp;sid=$sid\">" . _DELETE . "</a>)&nbsp;</td></tr>\n";
			}
			echo "</table>\n";
			if ( $radminsuper == 1 )
			{
				echo "<br><center>" . "[ <a href=\"" . $adminfile . ".php?op=subdelete\">" . _DELETEAll . "</a> ]" . "</center>";
			}
			CloseTable();
			echo "<br>";
		}

		$sql2 = "SELECT anid, aid, title, hometext, UNIX_TIMESTAMP(time) as formatted, alanguage FROM " . $prefix . "_stories_auto ORDER BY time ASC";
		$result2 = $db->sql_query( $sql2 );
		if ( $db->sql_numrows($result2) != 0 )
		{
			OpenTable();
			echo "<center><b>" . _AUTOMATEDARTICLES . "</b></center><br>";
			echo "<table border=\"1\" width=\"100%\">";
			while ( $row2 = $db->sql_fetchrow($result2) )
			{
				$anid = $row2['anid'];
				$said = $row2['aid'];
				$title = $row2['title'];
				$hometext = $row2['hometext'];
				$time = viewtime( $row2['formatted'], 2 );
				echo "<tr><td width=\"100%\">&nbsp;<a href=\"" . $adminfile . ".php?op=EditStoryAuto&anid=$anid\" title=\"$hometext\">$title</a>&nbsp;</td>" . "<td>&nbsp;$time&nbsp;</td>" . "<td align=\"right\" nowrap>(<a href=\"" . $adminfile . ".php?op=EditStoryAuto&anid=$anid\">" . _EDIT . "</a>-<a href=\"" . $adminfile . ".php?op=RemoveStoryAuto&anid=$anid\">" . _DELETE . "</a>)</td></tr>";

			}
			echo "</table>";
			CloseTable();
			echo "<br>";
		}
		$numf = $db->sql_fetchrow( $db->sql_query("SELECT COUNT(*) FROM " . $prefix . "_stories") );
		$page = ( isset($_GET['page']) ) ? intval( $_GET['page'] ) : 0;
		$all_page = ( $numf[0] ) ? $numf[0] : 1;
		$per_page = 10;
		$base_url = "" . $adminfile . ".php?op=newsadminhome";
		$sql3 = "SELECT sid, aid, title, UNIX_TIMESTAMP(time) as formatted, alanguage, newsst FROM " . $prefix . "_stories ORDER BY time DESC LIMIT $page,$per_page";
		$result3 = $db->sql_query( $sql3 );
		if ( $db->sql_numrows($result3) != 0 )
		{
			OpenTable();
			echo "<center><b>" . _LASTARTICLES . "</b></center><br>";
			echo "<center><table border=\"1\" width=\"100%\" bgcolor=\"$bgcolor1\">";
			while ( $row3 = $db->sql_fetchrow($result3) )
			{
				$sid = intval( $row3['sid'] );
				$said = $row3['aid'];
				$title = $row3['title'];
				$time = viewtime( $row3['formatted'], 2 );
				$alanguage = $row3['alanguage'];
				$newsst = intval( $row3['newsst'] );
				if ( $newsst == 1 )
				{
					$ttd = "<a title=\"" . _OUTTTD . "\" href=\"" . $adminfile . ".php?op=AddTTD&t=0&sid=$sid\"><img border=\"0\" src=\"../images/red_dot.gif\" width=\"10\" height=\"10\"></a>";
				}
				else
				{
					$ttd = "<a title=\"" . _INTTD . "\" href=\"" . $adminfile . ".php?op=AddTTD&t=1&sid=$sid\"><img border=\"0\" src=\"../images/green_dot.gif\" width=\"10\" height=\"10\"></a>";
				}
				if ( $alanguage == "" )
				{
					$alanguage = "" . _ALL . "";
				}
				$num = $db->sql_numrows( $db->sql_query("select * from " . $prefix . "_stories_images where sid=$sid AND catid='0' AND home='0'") );
				if ( $num )
				{
					$htl = "<a title=\"" . _ADDIMG . "\" href=\"" . $adminfile . ".php?op=Imggallery&in=2&sid=$sid\"><img border=\"0\" src=\"../images/out.gif\" width=\"20\" height=\"20\"></a>";
				}
				else
				{
					$htl = "<a title=\"" . _ADDIMG . "\" href=\"" . $adminfile . ".php?op=Imggallery&in=2&sid=$sid\"><img border=\"0\" src=\"../images/center_l.gif\" width=\"7\" height=\"11\"></a>";
				}
				echo "<tr><td align=\"right\"><b>$sid</b>" . "</td><td align=\"left\" width=\"100%\"><a href=\"../modules.php?name=News&op=viewst&sid=$sid\">$title</a>  ( $time )" . "</td><td align=\"center\">$alanguage" . "</td><td align=\"right\" nowrap>(<a href=\"" . $adminfile . ".php?op=EditStory&sid=$sid\">" . _EDIT . "</a>-<a href=\"" . $adminfile . ".php?op=RemoveStory&sid=$sid\">" . _DELETE . "</a>)" . "</td><td width=\"20\" height=\"20\" align=\"center\">$htl</td><td width=10>$ttd</td></tr>";
			}
			echo "</table>";
			echo @generate_page( $base_url, $all_page, $per_page, $page );
			echo "<br><br><center>" . "<form action=\"" . $adminfile . ".php\" method=\"post\">" . "" . _STORYID . ": <input type=\"text\" NAME=\"sid\" SIZE=\"10\"> " . "<select name=\"op\">" . "<option value=\"EditStory\" SELECTED>" . _EDIT . "</option>" . "<option value=\"RemoveStory\">" . _DELETE . "</option>" . "</select> " . "<input type=\"submit\" value=\"" . _GO . "\">" . "</form></center>";
			CloseTable();
		}
		include ( "../footer.php" );
	}

	/**
	 * newsconfig()
	 * 
	 * @return
	 */
	function newsconfig()
	{
		global $adminfile, $datafold;

		global $articlecomm, $commentcheck, $addnews, $newsprint, $newssave, $newsfriend, $newshome, $news2cot, $catnewshome, $newspagenum, $catnewshomeimg, $sizecatnewshomeimg, $sizeimgskqa, $htatl, $catimgnewshome, $newsarticleimg, $sizenewsarticleimg, $hienthi_tlq, $hienthi_ccd, $comblbarstat, $block_atl, $sizeatl, $block_top10topic, $block_top10articles, $block_top10count, $temp_path, $path;
		include ( "../header.php" );

		newstopbanner();
		echo "<form action=\"" . $adminfile . ".php\" method=\"post\">";
		OpenTable();
		echo "<center><b>" . _FUNCTIONSNEWS . "</b></center><br>" . "<table border=\"0\"><tr><td>" . "" . _COMMENTSARTICLES . ":</td><td><select name=\"xarticlecomm\">";
		$yarticlecomm = array( _NO, _COMMENTSARTICLES1, _COMMENTSARTICLES2 );
		for ( $d = 0; $d <= 2; $d++ )
		{
			$seld = "";
			if ( $d == $articlecomm )
			{
				$seld = " selected";
			}
			echo "<option name=\"xarticlecomm\" value=\"$d\" $seld>$yarticlecomm[$d]</option>\n";
		}

		if ( $commentcheck == 1 )
		{
			$checkcom1 = "checked";
			$checkcom2 = "";
		}
		else
		{
			$checkcom2 = "checked";
			$checkcom1 = "";
		}
		echo "<tr>\n";
		echo "<td>" . _COMMCONFIG . "</td>\n";
		echo "<td><input type='radio' $checkcom1 name='comme' value='1'>" . _YES . "&nbsp;<input type='radio' $checkcom2 name='comme' value='0'>" . _NO . "</td>\n";
		echo "</tr>\n";


		echo "</select></td></tr><tr><td>" . "" . _NEWSADDNEWS . ":</td><td><select name=\"xaddnews\">";
		$yaddnews = array( _NO, _NEWSADDNEWS1, _NEWSADDNEWS2 );
		for ( $d = 0; $d <= 2; $d++ )
		{
			$seld = "";
			if ( $d == $addnews )
			{
				$seld = " selected";
			}
			echo "<option name=\"xaddnews\" value=\"$d\" $seld>$yaddnews[$d]</option>\n";
		}
		echo "</select></td></tr><tr><td>" . "" . _NEWSPRINT . ":</td><td><select name=\"xnewsprint\">";
		$ynewsprint = array( _NO, _NEWSPRINT1, _NEWSPRINT2 );
		for ( $d = 0; $d <= 2; $d++ )
		{
			$seld = "";
			if ( $d == $newsprint )
			{
				$seld = " selected";
			}
			echo "<option name=\"xnewsprint\" value=\"$d\" $seld>$ynewsprint[$d]</option>\n";
		}
		echo "</select></td></tr><tr><td>" . "" . _NEWSSAVE . ":</td><td><select name=\"xnewssave\">";
		$ynewssave = array( _NO, _NEWSSAVE1, _NEWSSAVE2 );
		for ( $d = 0; $d <= 2; $d++ )
		{
			$seld = "";
			if ( $d == $newssave )
			{
				$seld = " selected";
			}
			echo "<option name=\"xnewssave\" value=\"$d\" $seld>$ynewssave[$d]</option>\n";
		}
		echo "</select></td></tr><tr><td>" . "" . _NEWSFRIEND . ":</td><td><select name=\"xnewsfriend\">";
		$ynewsfriend = array( _NO, _NEWSFRIEND1, _NEWSFRIEND2 );
		for ( $d = 0; $d <= 2; $d++ )
		{
			$seld = "";
			if ( $d == $newsfriend )
			{
				$seld = " selected";
			}
			echo "<option name=\"xnewsfriend\" value=\"$d\" $seld>$ynewsfriend[$d]</option>\n";
		}
		echo "</select></td></tr></table>";
		CloseTable();
		echo "<br>";
		OpenTable();
		echo "<center><b>" . _NEWSHOMEPAGE . "</b></center><br>" . "<table border=\"0\"><tr><td>" . "" . _NEWSHOMESX . ":</td><td><select name=\"xnewshome\">";
		$ynewshome = array( _NEWSHOMESX1, _NEWSHOMESX2 );
		for ( $d = 0; $d < 2; $d++ )
		{
			$seld = "";
			if ( $d == $newshome )
			{
				$seld = " selected";
			}
			echo "<option name=\"xnewshome\" value=\"$d\" $seld>$ynewshome[$d]</option>\n";
		}
		echo "</select></td></tr>";
		// Begin News 2 cot
		echo "<tr><td>" . "" . _NEWS2 . ":</td><td><select name=\"xnews2cot\">";
		$ynews2cot = array( _NEWS21, _NEWS22 );
		for ( $d = 0; $d < 2; $d++ )
		{
			$seld = "";
			if ( $d == $news2cot )
			{
				$seld = " selected";
			}
			echo "<option name=\"xnews2cot\" value=\"$d\" $seld>$ynews2cot[$d]</option>\n";
		}
		echo "</select></td></tr>";
		// End News 2 cot
		echo "<tr><td>" . _NEWSHOMESX2A . ":</td><td>&nbsp;</td></tr><tr><td>" . "<li>" . _NEWSHOMESX2A0 . ":</li></td><td><select name=\"xcatnewshome\">";
		$ycatnewshome = array( _NEWSHOMESX2A1, _NEWSHOMESX2A2, _NEWSHOMESX2A3 );
		for ( $d = 0; $d <= 2; $d++ )
		{
			$seld = "";
			if ( $d == $catnewshome )
			{
				$seld = " selected";
			}
			echo "<option name=\"xcatnewshome\" value=\"$d\" $seld>$ycatnewshome[$d]</option>\n";
		}
		echo "</select></td></tr><tr><td>" . "" . _STORIESPAGE . ":</td><td><select name=\"xnewspagenum\">";
		for ( $d = 1; $d <= 20; $d++ )
		{
			$seld = "";
			if ( $d == $newspagenum )
			{
				$seld = " selected";
			}
			echo "<option name=\"xnewspagenum\" value=\"$d\" $seld>$d</option>\n";
		}
		echo "</select></td></tr><tr><td>" . "" . _NEWSHOMEIMG . ":</td><td><select name=\"xcatnewshomeimg\">";
		$zcatnewshomeimg = array( 'left', 'right' );
		$ycatnewshomeimg = array( _NEWSHOMEIMG1, _NEWSHOMEIMG2 );
		for ( $d = 0; $d < 2; $d++ )
		{
			$seld = "";
			if ( $zcatnewshomeimg[$d] == $catnewshomeimg )
			{
				$seld = " selected";
			}
			echo "<option name=\"xcatnewshomeimg\" value=\"$zcatnewshomeimg[$d]\" $seld>$ycatnewshomeimg[$d]</option>\n";
		}
		echo "</select></td></tr><tr><td>" . "" . _SIZENEWSHOMEIMG . ":</td><td><select name=\"xsizecatnewshomeimg\">";
		$ysizecatnewshomeimg = array( '50', '60', '70', '80', '90', '100', '110', '120', '130', '140', '150', '160', '170', '180', '190', '200' );
		for ( $d = 0; $d < 16; $d++ )
		{
			$seld = "";
			if ( $ysizecatnewshomeimg[$d] == $sizecatnewshomeimg )
			{
				$seld = " selected";
			}
			echo "<option name=\"xsizecatnewshomeimg\" value=\"$ysizecatnewshomeimg[$d]\" $seld>$ysizecatnewshomeimg[$d]</option>\n";
		}
		echo "</select></td></tr><tr><td>" . "" . _SIZETTD . ":</td><td><select name=\"xsizeimgskqa\">";
		$ysizeimgskqa = array( '50', '60', '70', '80', '90', '100', '110', '120', '130', '140', '150', '160', '170', '180', '190', '200' );
		for ( $d = 0; $d < 16; $d++ )
		{
			$seld = "";
			if ( $ysizeimgskqa[$d] == $sizeimgskqa )
			{
				$seld = " selected";
			}
			echo "<option name=\"xsizeimgskqa\" value=\"$ysizeimgskqa[$d]\" $seld>$ysizeimgskqa[$d]</option>\n";
		}
		echo "</select></td></tr><tr><td>" . "" . _SIZEHTL . ":</td><td><select name=\"xsizeatl\">";
		for ( $d = 100; $d <= 200; $d++ )
		{
			echo "<option name=\"xsizeatl\" value=\"$d\"";
			if ( $d == $sizeatl ) echo " selected";
			echo ">$d</option>\n";
		}
		echo "</select></td></tr><tr><td>" . "" . _HTHTL . ":</td><td><select name=\"xhtatl\">";
		$htskqa = array( _HTHTL1, _HTHTL2 );
		for ( $d = 0; $d < sizeof($htskqa); $d++ )
		{
			echo "<option name=\"xhtatl\" value=\"$d\"";
			if ( $d == $htatl ) echo " selected";
			echo ">$htskqa[$d]</option>\n";
		}
		echo "</select></td></tr><tr><td>" . "" . _NEWSHOMECATIMG . ":</td><td>";
		if ( $catimgnewshome == 1 )
		{
			echo "<input type=\"radio\" name=\"xcatimgnewshome\" value=\"1\" checked>" . _YES . " &nbsp;" . "<input type=\"radio\" name=\"xcatimgnewshome\" value=\"0\">" . _NO . "";
		}
		else
		{
			echo "<input type=\"radio\" name=\"xcatimgnewshome\" value=\"1\">" . _YES . " &nbsp;" . "<input type=\"radio\" name=\"xcatimgnewshome\" value=\"0\" checked>" . _NO . "";
		}
		echo "</td></tr></table>";
		CloseTable();
		echo "<br>";
		OpenTable();
		echo "<center><b>" . _NEWSARTICLEPAGE . "</b></center><br>" . "<table border=\"0\"><tr><td>" . "" . _NEWSARTICLEIMG . ":</td><td><select name=\"xnewsarticleimg\">";
		$znewsarticleimg = array( 'left', 'right' );
		$ynewsarticleimg = array( _NEWSARTICLEIMG1, _NEWSARTICLEIMG2 );
		for ( $d = 0; $d < 2; $d++ )
		{
			$seld = "";
			if ( $znewsarticleimg[$d] == $newsarticleimg )
			{
				$seld = " selected";
			}
			echo "<option name=\"xnewsarticleimg\" value=\"$znewsarticleimg[$d]\" $seld>$ynewsarticleimg[$d]</option>\n";
		}
		echo "</select></td></tr><tr><td>" . "" . _SIZENEWSARTICLEIMG . ":</td><td><select name=\"xsizenewsarticleimg\">";
		$ysizenewsarticleimg = array( '50', '60', '70', '80', '90', '100', '110', '120', '130', '140', '150', '160', '170', '180', '190', '200' );
		for ( $d = 0; $d < 16; $d++ )
		{
			$seld = "";
			if ( $ysizenewsarticleimg[$d] == $sizenewsarticleimg )
			{
				$seld = " selected";
			}
			echo "<option name=\"xsizenewsarticleimg\" value=\"$ysizenewsarticleimg[$d]\" $seld>$ysizenewsarticleimg[$d]</option>\n";
		}
		echo "</select></td></tr><tr><td>" . "" . _NEWSARTICLEBLOCKNUMTLQ . ":</td><td><select name=\"xhienthi_tlq\">";
		for ( $d = 0; $d < 11; $d++ )
		{
			$seld = "";
			if ( $d == $hienthi_tlq )
			{
				$seld = " selected";
			}
			echo "<option name=\"xhienthi_tlq\" value=\"$d\" $seld>$d</option>\n";
		}
		echo "</select></td></tr><tr><td>" . "" . _NEWSARTICLEBLOCKNUMCCD . ":</td><td><select name=\"xhienthi_ccd\">";
		for ( $d = 0; $d <= 30; $d++ )
		{
			$seld = "";
			if ( $d == $hienthi_ccd )
			{
				$seld = " selected";
			}
			echo "<option name=\"xhienthi_ccd\" value=\"$d\" $seld>$d</option>\n";
		}
		echo "</select></td></tr><tr><td>" . "" . _NEWSARTICLEBLOCKTH . ":</td><td>";
		if ( $comblbarstat == 1 )
		{
			echo "<input type=\"radio\" name=\"xcomblbarstat\" value=\"1\" checked>" . _YES . " &nbsp;" . "<input type=\"radio\" name=\"xcomblbarstat\" value=\"0\">" . _NO . "";
		}
		else
		{
			echo "<input type=\"radio\" name=\"xcomblbarstat\" value=\"1\">" . _YES . " &nbsp;" . "<input type=\"radio\" name=\"xcomblbarstat\" value=\"0\" checked>" . _NO . "";
		}
		echo "</td></tr><tr><td>" . "" . _NEWSARTICLEBLOCKATL . ":</td><td><select name=\"xblock_atl\">";
		for ( $d = 0; $d <= 10; $d++ )
		{
			$seld = "";
			if ( $d == $block_atl )
			{
				$seld = " selected";
			}
			echo "<option name=\"xblock_atl\" value=\"$d\" $seld>$d</option>\n";
		}
		echo "</select></td></tr></table>";
		CloseTable();
		echo "<br>";
		OpenTable();
		echo "<center><b>" . _NEWSFUNCTIONS . "</b></center><br>" . "<table border=\"0\"><tr><td>" . "" . _NEWSIMGTEMPPATH . ":</td><td>" . "<input type=\"text\" name=\"xtemp_path\" value=\"$temp_path\" size=\"40\" maxlength=\"100\">";
		echo "</td></tr><tr><td>" . "" . _NEWSIMGPATH . ":</td><td>" . "<input type=\"text\" name=\"xpath\" value=\"$path\" size=\"40\" maxlength=\"100\">";
		echo "</td></tr></table>";
		CloseTable();
		echo "<br>" . "<input type=\"hidden\" name=\"op\" value=\"newsconfigsave\">" . "<center><input type=\"submit\" value=\"" . _SAVECHANGES . "\"></center>" . "</form>";
		include ( "../footer.php" );
	}

	/**
	 * newsconfigsave()
	 * 
	 * @return
	 */
	function newsconfigsave()
	{
		global $adminfile, $datafold, $checkmodname;
		$xarticlecomm = intval( $_POST['xarticlecomm'] );

		$xcommentcheck = intval( $_POST['comme'] );
		$xaddnews = intval( $_POST['xaddnews'] );
		$xnewsprint = intval( $_POST['xnewsprint'] );
		$xnewssave = intval( $_POST['xnewssave'] );
		$xnewsfriend = intval( $_POST['xnewsfriend'] );
		$xnewshome = intval( $_POST['xnewshome'] );
		$xnews2cot = intval( $_POST['xnews2cot'] );

		$xcatnewshome = intval( $_POST['xcatnewshome'] );
		$xnewspagenum = intval( $_POST['xnewspagenum'] );
		$xcatnewshomeimg = FixQuotes( $_POST['xcatnewshomeimg'] );
		$xsizecatnewshomeimg = intval( $_POST['xsizecatnewshomeimg'] );
		$xsizeimgskqa = intval( $_POST['xsizeimgskqa'] );
		$xcatimgnewshome = intval( $_POST['xcatimgnewshome'] );
		$xnewsarticleimg = FixQuotes( $_POST['xnewsarticleimg'] );
		$xsizenewsarticleimg = intval( $_POST['xsizenewsarticleimg'] );
		$xhienthi_tlq = intval( $_POST['xhienthi_tlq'] );
		$xhienthi_ccd = intval( $_POST['xhienthi_ccd'] );
		$xcomblbarstat = intval( $_POST['xcomblbarstat'] );
		$xblock_atl = intval( $_POST['xblock_atl'] );
		$xsizeatl = intval( $_POST['xsizeatl'] );
		$xhtatl = intval( $_POST['xhtatl'] );
		$xtemp_path = FixQuotes( $_POST['xtemp_path'] );
		$xpath = FixQuotes( $_POST['xpath'] );

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
		$content .= "\$articlecomm = $xarticlecomm;\n";

		$content .= "\$commentcheck = $xcommentcheck;\n";

		$content .= "\$addnews = $xaddnews;\n";
		$content .= "\$newsprint = $xnewsprint;\n";
		$content .= "\$newssave = $xnewssave;\n";
		$content .= "\$newsfriend = $xnewsfriend;\n";
		$content .= "\n";
		$content .= "\$newshome = $xnewshome;\n";
		$content .= "\$news2cot = $xnews2cot;\n";

		$content .= "\$catnewshome = $xcatnewshome;\n";
		$content .= "\$newspagenum = $xnewspagenum;\n";
		$content .= "\$catnewshomeimg = \"$xcatnewshomeimg\";\n";
		$content .= "\$sizecatnewshomeimg = $xsizecatnewshomeimg;\n";
		$content .= "\$sizeimgskqa = $xsizeimgskqa;\n";
		$content .= "\$catimgnewshome = $xcatimgnewshome;\n";
		$content .= "\n";
		$content .= "\$newsarticleimg = \"$xnewsarticleimg\";\n";
		$content .= "\$sizenewsarticleimg = $xsizenewsarticleimg;\n";
		$content .= "\$hienthi_tlq = $xhienthi_tlq;\n";
		$content .= "\$hienthi_ccd = $xhienthi_ccd;\n";
		$content .= "\$comblbarstat = $xcomblbarstat;\n";
		$content .= "\$block_atl = $xblock_atl;\n";
		$content .= "\$sizeatl = $xsizeatl;\n";
		$content .= "\$htatl = $xhtatl;\n";
		$content .= "\n";
		$content .= "\$temp_path = \"$xtemp_path\";\n";
		$content .= "\$path = \"$xpath\";\n";
		$content .= "\n";
		$content .= "?>";

		@fwrite( $file, $content );
		@fclose( $file );
		@chmod( "../$datafold/config_" . $checkmodname . ".php", 0604 );

		Header( "Location: " . $adminfile . ".php?op=newsconfig" );
	}

	/**
	 * puthome()
	 * 
	 * @param mixed $ihome
	 * @param mixed $acomm
	 * @return
	 */
	function puthome( $ihome, $acomm )
	{
		echo "<br><b>" . _PUBLISHINHOME . "</b>&nbsp;&nbsp;";
		if ( $ihome == 1 )
		{
			$sel1 = "checked";
			$sel2 = "";
		}
		else
		{
			$sel1 = "";
			$sel2 = "checked";
		}
		echo "<input type=\"radio\" name=\"ihome\" value=\"1\" $sel1>" . _YES . "&nbsp;" . "<input type=\"radio\" name=\"ihome\" value=\"0\" $sel2>" . _NO . "<br>";
		echo "<br><b>" . _ACTIVATECOMMENTS . "</b>&nbsp;&nbsp;";
		if ( ($acomm == 0) or ($acomm == "") )
		{
			$sel1 = "checked";
			$sel2 = "";
		}
		if ( $acomm == 1 )
		{
			$sel1 = "";
			$sel2 = "checked";
		}
		echo "<input type=\"radio\" name=\"acomm\" value=\"0\" $sel1>" . _YES . "&nbsp;" . "<input type=\"radio\" name=\"acomm\" value=\"1\" $sel2>" . _NO . "</font><br><br>";
	}

	/**
	 * deleteStory()
	 * 
	 * @param mixed $sid
	 * @return
	 */
	function deleteStory( $sid )
	{
		global $adminfile, $prefix, $db, $temp_path;
		$sid = intval( $sid );
		$result = $db->sql_query( "SELECT images FROM " . $prefix . "_stories_temp where sid=$sid" );
		$row = $db->sql_fetchrow( $result );
		$images = $row['images'];
		@unlink( "../" . $temp_path . "/" . $images . "" );
		@unlink( "../" . $temp_path . "/small_" . $images . "" );
		@unlink( "../" . $temp_path . "/nst_" . $images . "" );
		$db->sql_query( "delete from " . $prefix . "_stories_temp where sid=$sid" );
		info_exit( "<center><b>" . _DELEDARTICLE . "</b></center><META HTTP-EQUIV=\"refresh\" content=\"2;URL=" . $adminfile . ".php?op=newsadminhome\">" );
	}

	/**
	 * SelectCategory()
	 * 
	 * @param mixed $cat
	 * @return
	 */
	function SelectCategory( $cat )
	{
		global $adminfile, $prefix, $db;
		$sql = "select catid, parentid, title from " . $prefix . "_stories_cat order by parentid, weight";
		$result = $db->sql_query( $sql );
		echo "<a href=\"" . $adminfile . ".php?op=ManagerCategory\" title=\"" . _CATEGORIESADMIN . "\" target=\"blank\"><b>" . _CATEGORY . "</b></a> ";
		echo "<select name=\"catid\">";
		echo "<option name=\"catid\" value=\"0\">" . _ASELECTCATEGORY . "</option>";
		while ( $row = $db->sql_fetchrow($result) )
		{
			$ctitle = $row['title'];
			if ( $row['parentid'] != 0 )
			{
				list( $ptitle ) = $db->sql_fetchrow( $db->sql_query("select title from " . $prefix . "_stories_cat where catid='" . $row['parentid'] . "'") );
				$ctitle = "$ptitle &raquo; $ctitle";
			}
			echo "<option name=\"catid\" value=\"$row[catid]\"";
			if ( $row['catid'] == $cat )
			{
				echo " selected";
			}
			echo ">$ctitle</option>";
		}
		echo "</select>";
	}

	/**
	 * xaddcat()
	 * 
	 * @return
	 */
	function xaddcat()
	{
		global $adminfile, $db, $prefix;
		OpenTable();
		echo "<center><font class=\"option\"><b>" . _CATEGORYADD . "</b></font><br><br><br>" . "<form action=\"" . $adminfile . ".php\" method=\"post\">" . "<b>" . _CATNAME . ":</b> " . "<input type=\"text\" name=\"title\" size=\"30\"> " . "&nbsp;<b>" . _STPIC . ":</b> " . "<select name=\"catimage\">";
		$path1 = explode( "/", "../images/cat/" );
		$path = "$path1[0]/$path1[1]/$path1[2]";
		$handle = opendir( $path );
		while ( $file = readdir($handle) )
		{
			if ( (ereg("^([_0-9a-zA-Z]+)([.]{1})([_0-9a-zA-Z]{3})$", $file)) and $file != "AllTopics.gif" )
			{
				$tlist .= "$file ";
			}
		}
		closedir( $handle );
		$tlist = explode( " ", $tlist );
		sort( $tlist );
		for ( $i = 0; $i < sizeof($tlist); $i++ )
		{
			if ( $tlist[$i] != "" )
			{
				echo "<option name=\"catimage\" value=\"$tlist[$i]\">$tlist[$i]\n";
			}
		}
		echo "</select><br>" . "<b>" . _PUBLISHINHOME . ":</b> " . "<input type=\"radio\" name=\"xihome\" value=\"1\" checked>" . _YES . " &nbsp;" . "<input type=\"radio\" name=\"xihome\" value=\"0\">" . _NO . " &nbsp;" . "<br>" . _CLINKSHOME1 . ": <select name=\"linkshome\">";
		for ( $a = 0; $a <= 10; $a++ )
		{
			$sel = "";
			if ( $a == 3 )
			{
				$sel = " selected";
			}
			echo "<option name=\"linkshome\"$sel>$a</option>\n";
		}
		echo "</select><br>";
		echo "<b>" . _INCAT . ":</b> " . "<select name=\"parentid\">";
		echo "<option value=\"0\">" . _NOINCAT . "</option>";
		$sql2 = "select catid, title from " . $prefix . "_stories_cat WHERE parentid='0'";
		$result2 = $db->sql_query( $sql2 );
		while ( $row2 = $db->sql_fetchrow($result2) )
		{
			$catid2 = intval( $row2['catid'] );
			$title2 = $row2['title'];
			echo "<option value=\"$catid2\">$title2</option>";
		}
		echo "</select>&nbsp;" . "<input type=\"hidden\" name=\"storieshome\" value=\"0\">" . "<input type=\"hidden\" name=\"op\" value=\"SaveCategory\">" . "<input type=\"submit\" value=\"" . _SAVE . "\">" . "</form></center>";
		CloseTable();
		echo "<br>";
	}

	/**
	 * ManagerCategory()
	 * 
	 * @return
	 */
	function ManagerCategory()
	{
		global $adminfile, $prefix, $db;
		include ( "../header.php" );

		newstopbanner();
		xaddcat();
		$sql = "select * from " . $prefix . "_stories_cat order by parentid, weight";
		$result = $db->sql_query( $sql );
		$num = $db->sql_numrows( $result );
		if ( $num > 0 )
		{
			OpenTable();
			echo "<table border=\"1\" width=\"100%\"><tr>" . "<td align=\"center\"><b>" . _CATEGORIES . "</b></td><td colspan=\"3\" align=\"center\">" . "<b>" . _POSITION . "</b></td><td align=\"center\"><b>" . _FUNCTIONS . "</b></td><td align=\"center\"><b>" . _CSTORIESHOME6 . "</b></td><td align=\"center\"><b>" . _CLINKSHOME . "</b></td><td align=\"center\" width=20>&nbsp;</td></tr>";
			while ( $row = $db->sql_fetchrow($result) )
			{
				$catid = intval( $row['catid'] );
				$parentid = intval( $row['parentid'] );
				$title = "<b>" . $row['title'] . "</b>";
				$storieshome = intval( $row['storieshome'] );
				$linkshome = intval( $row['linkshome'] );
				$ihome = intval( $row['ihome'] );
				if ( $parentid != 0 )
				{
					list( $ptitle ) = $db->sql_fetchrow( $db->sql_query("select title from " . $prefix . "_stories_cat where catid='$parentid'") );
					$title = "$ptitle &raquo; $title";
				}
				if ( $ihome == "1" )
				{
					$title = "" . $title . " (*)";
				}

				$weight = $row['weight'];
				$weight1 = $weight - 1;
				$weight3 = $weight + 1;
				list( $catid1 ) = $db->sql_fetchrow( $db->sql_query("SELECT catid FROM " . $prefix . "_stories_cat WHERE weight='$weight1' AND parentid='$parentid'") );
				list( $catid3 ) = $db->sql_fetchrow( $db->sql_query("SELECT catid FROM " . $prefix . "_stories_cat WHERE weight='$weight3' AND parentid='$parentid'") );
				$con1 = intval( $catid1 );
				$con3 = intval( $catid3 );
				if ( $con1 )
				{
					$up = "<a href=\"" . $adminfile . ".php?op=OrderCategory&amp;weight=$weight&amp;catidori=$catid&amp;weightrep=$weight1&amp;catidrep=$con1\"><img src=\"../images/up.gif\" alt=\"" . _CATUP . "\" title=\"" . _CATUP . "\" border=\"0\" hspace=\"3\"></a>";
				}
				else
				{
					$up = "";
				}
				if ( $con3 )
				{
					$down = "<a href=\"" . $adminfile . ".php?op=OrderCategory&amp;weight=$weight&amp;catidori=$catid&amp;weightrep=$weight3&amp;catidrep=$con3\"><img src=\"../images/down.gif\" alt=\"" . _CATDOWN . "\" title=\"" . _CATDOWN . "\" border=\"0\" hspace=\"3\"></a>";
				}
				else
				{
					$down = "";
				}
				$up_down = "$up $down";
				if ( (! $up) and (! $down) )
				{
					$up_down = "&nbsp;";
				}
				$functions = "[ <a href=\"../modules.php?name=News&op=viewcat&catid=$catid\">" . _SHOW . "</a> | <a href=\"" . $adminfile . ".php?op=EditCategory&catid=$catid\">" . _EDIT . "</a> | <a href=\"" . $adminfile . ".php?op=DelCategory&cat=$catid\">" . _DELETE . "</a> ]";
				$sql4 = "select * from " . $prefix . "_stories where catid='$catid'";
				$result4 = $db->sql_query( $sql4 );
				$row4 = $db->sql_fetchrow( $result4 );
				if ( $row4 )
				{
					if ( $storieshome == 0 )
					{
						$storieshome = " [<a href=\"" . $adminfile . ".php?op=CstorieshomeCategory&catid=$catid\">" . _CSTORIESHOME1 . "</a>]";
					}
					else
					{
						$storieshome = " [<a href=\"" . $adminfile . ".php?op=EditStory&sid=$storieshome\">" . _CSTORIESHOME4 . "</a> | <a href=\"" . $adminfile . ".php?op=CstorieshomeCategory&catid=$catid\">" . _CSTORIESHOME5 . "</a>]";
					}
				}
				else
				{
					$storieshome = " [" . _CSTORIESHOME3 . "]";
				}
				echo "<tr><td>$title</td><td align=\"center\">$weight</td><td align=\"center\">";
				if ( $parentid == 0 )
				{
					echo "$up_down";
				}
				else
				{
					echo "&nbsp;";
				}
				echo "</td><td align=\"center\">";
				if ( $parentid == 0 )
				{
					echo "&nbsp;";
				}
				else
				{
					echo "$up_down";
				}
				if ( $db->sql_numrows($db->sql_query("select * from " . $prefix . "_stories_images where sid='0' AND home='0' AND catid='$catid'")) )
				{
					$htl = "<a title=\"" . _ADDIMG . "\" href=\"" . $adminfile . ".php?op=Imggallery&in=1&catid=$catid\"><img border=\"0\" src=\"../images/out.gif\" width=\"20\" height=\"20\"></a>";
				}
				else
				{
					$htl = "<a title=\"" . _ADDIMG . "\" href=\"" . $adminfile . ".php?op=Imggallery&in=1&catid=$catid\"><img border=\"0\" src=\"../images/center_l.gif\" width=\"7\" height=\"11\"></a>";
				}
				echo "</td><td align=\"center\">$functions</td><td align=\"center\">$storieshome</td><td align=\"center\">$linkshome</td><td align=\"center\" width=20>$htl</td></tr>";
			}
			echo "</table>";
			CloseTable();
		}
		include ( "../footer.php" );
	}

	/**
	 * OrderCategory()
	 * 
	 * @param mixed $weightrep
	 * @param mixed $weight
	 * @param mixed $catidrep
	 * @param mixed $catidori
	 * @return
	 */
	function OrderCategory( $weightrep, $weight, $catidrep, $catidori )
	{
		global $adminfile, $prefix, $db;
		$catidrep = intval( $catidrep );
		$catidori = intval( $catidori );
		$db->sql_query( "update " . $prefix . "_stories_cat set weight='$weight' where catid='$catidrep'" );
		$db->sql_query( "update " . $prefix . "_stories_cat set weight='$weightrep' where catid='$catidori'" );
		fixweightcat();
		ncatlist();
		Header( "Location: " . $adminfile . ".php?op=ManagerCategory" );
	}


	/**
	 * AddCategory()
	 * 
	 * @return
	 */
	function AddCategory()
	{
		include ( "../header.php" );

		newstopbanner();
		xaddcat();
		include ( "../footer.php" );
	}

	/**
	 * EditCategory()
	 * 
	 * @param mixed $catid
	 * @return
	 */
	function EditCategory( $catid )
	{
		global $adminfile, $prefix, $db;
		include ( "../header.php" );

		newstopbanner();
		OpenTable();
		echo "<center><font class=\"option\"><b>" . _EDITCATEGORY . "</b></font><br><br>";
		if ( ! $catid )
		{
			$sql = "select catid title from " . $prefix . "_stories_cat";
			$result = $db->sql_query( $sql );
			echo "<form action=\"" . $adminfile . ".php\" method=\"post\">";
			echo "<b>" . _ASELECTCATEGORY . ": </b>";
			echo "<select name=\"catid\">";
			echo "<option name=\"catid\" value=\"0\">" . _THECATEGORY . "</option>";
			while ( $row = $db->sql_fetchrow($result) )
			{
				$catid = intval( $row['catid'] );
				$title = $row['title'];
				echo "<option name=\"catid\" value=\"$catid\">$title</option>";
			}
			echo "</select>";
			echo "<input type=\"hidden\" name=\"op\" value=\"EditCategory\">";
			echo "<input type=\"submit\" value=\"" . _EDIT . "\"></form><br><br>";
			echo "" . _NOARTCATEDIT . "";
		}
		else
		{
			$sql = "select * from " . $prefix . "_stories_cat where catid='$catid'";
			$result = $db->sql_query( $sql );
			$row = $db->sql_fetchrow( $result );
			$title = $row['title'];
			$parentid = intval( $row['parentid'] );
			$catimage = $row['catimage'];
			$ihome = intval( $row['ihome'] );
			if ( $ihome == 1 )
			{
				$sel1 = "checked";
				$sel2 = "";
			}
			else
			{
				$sel1 = "";
				$sel2 = "checked";
			}
			$linkshome = $row['linkshome'];
			echo "<form action=\"" . $adminfile . ".php\" method=\"post\">";
			echo "<b>" . _CATEGORYNAME . ":</b> ";
			echo "<input type=\"text\" name=\"title\" size=\"30\" value=\"$title\"> ";
			echo "&nbsp;<b>" . _STPIC . ":</b> " . "<select name=\"catimage\">";
			$path1 = explode( "/", "../images/cat/" );
			$path = "$path1[0]/$path1[1]/$path1[2]";
			$handle = opendir( $path );
			while ( $file = readdir($handle) )
			{
				if ( (ereg("^([_0-9a-zA-Z]+)([.]{1})([_0-9a-zA-Z]{3})$", $file)) and $file != "AllTopics.gif" )
				{
					$tlist .= "$file ";
				}
			}
			closedir( $handle );
			$tlist = explode( " ", $tlist );
			sort( $tlist );
			for ( $i = 0; $i < sizeof($tlist); $i++ )
			{
				if ( $tlist[$i] != "" )
				{
					if ( $catimage == $tlist[$i] )
					{
						$sel = "selected";
					}
					else
					{
						$sel = "";
					}
					echo "<option name=\"catimage\" value=\"$tlist[$i]\" $sel>$tlist[$i]\n";
				}
			}
			echo "</select><br>" . "<b>" . _PUBLISHINHOME . ":</b> " . "<input type=\"radio\" name=\"ihome\" value=\"1\" $sel1>" . _YES . "&nbsp;" . "<input type=\"radio\" name=\"ihome\" value=\"0\" $sel2>" . _NO . "<br>";
			echo "<b>" . _CLINKSHOME1 . ":</b> " . "<select name=\"linkshome\">";
			for ( $a = 0; $a <= 10; $a++ )
			{
				$sel = "";
				if ( $a == $linkshome )
				{
					$sel = " selected";
				}
				echo "<option name=\"linkshome\"$sel>$a</option>\n";
			}
			echo "</select>";
			if ( $db->sql_numrows($db->sql_query("select * from " . $prefix . "_stories_cat where parentid='$catid'")) == 0 )
			{
				echo "<br><b>" . _INCAT . ":</b> " . "<select name=\"parentid\">";
				echo "<option value=\"0\">" . _NOINCAT . "</option>";
				$sql2 = "select catid, title from " . $prefix . "_stories_cat WHERE parentid='0' AND catid!='$catid'";
				$result2 = $db->sql_query( $sql2 );
				while ( $row2 = $db->sql_fetchrow($result2) )
				{
					$catid2 = intval( $row2['catid'] );
					$title2 = $row2['title'];
					echo "<option value=\"$catid2\"";
					if ( $catid2 == $parentid ) echo " selected";
					echo ">$title2</option>";
				}
				echo "</select>&nbsp;";
			}
			else
			{
				echo "<br>" . _NOTEINCAT . "<input type=\"hidden\" name=\"parentid\" value=\"$parentid\"><br>";
			}
			echo "<input type=\"hidden\" name=\"catid\" value=\"$catid\">";
			echo "<input type=\"hidden\" name=\"op\" value=\"SaveEditCategory\">";
			echo "<input type=\"submit\" value=\"" . _SAVECHANGES . "\"><br><br>";
			echo "</form>";
		}
		echo "</center>";
		CloseTable();
		include ( "../footer.php" );
	}

	/**
	 * CstorieshomeCategory()
	 * 
	 * @param mixed $catid
	 * @return
	 */
	function CstorieshomeCategory( $catid )
	{
		global $adminfile, $datafold, $prefix, $db, $multilingual, $currentlang;
		$result1 = $db->sql_query( "select title, storieshome from " . $prefix . "_stories_cat where catid='$catid'" );
		$check = $db->sql_numrows( $result1 );
		if ( $check != 1 )
		{
			Header( "Location: " . $adminfile . ".php?op=ManagerCategory" );
			exit;
		}
		include ( "../header.php" );

		newstopbanner();
		OpenTable();
		echo "<center><font class=\"option\"><b>" . _CSTORIESHOME . "</b></font>";
		$row = $db->sql_fetchrow( $result1 );
		$storieshome = intval( $row['storieshome'] );
		$title = $row['title'];
		echo "<font class=\"option\"><b>: $title</b></font><br><br>";
		if ( $multilingual == 1 )
		{
			$querylang = "AND (alanguage='$currentlang' OR alanguage='')";
		}
		else
		{
			$querylang = "";
		}
		$result2 = $db->sql_query( "select sid, title from " . $prefix . "_stories where catid='$catid' AND (ihome='1' AND newsst!='1') $querylang" );
		$check2 = $db->sql_numrows( $result2 );
		if ( $check2 == 0 )
		{
			Header( "Location: " . $adminfile . ".php?op=ManagerCategory" );
			exit;
		}
		echo "<form action=\"" . $adminfile . ".php\" method=\"post\">" . "<select name=\"storieshome\">" . "<option name=\"storieshome\" value=\"0\">" . _CSTORIESHOME2 . "</option>";
		while ( $row2 = $db->sql_fetchrow($result2) )
		{
			$sid2 = intval( $row2['sid'] );
			$title2 = $row2['title'];
			echo "<option name=\"storieshome\" value=\"$sid2\"";
			if ( $sid2 == $storieshome ) echo " selected";
			echo ">$title2</option>\n";
		}
		echo "</select><br>";
		echo "<input type=\"hidden\" name=\"catid\" value=\"$catid\">";
		echo "<input type=\"hidden\" name=\"op\" value=\"SaveCstorieshomeCategory\">";
		echo "<input type=\"submit\" value=\"" . _SAVECHANGES . "\">";
		echo "</form>";
		echo "</center>";
		CloseTable();
		include ( "../footer.php" );
	}

	/**
	 * DelCategory()
	 * 
	 * @param mixed $cat
	 * @return
	 */
	function DelCategory( $cat )
	{
		global $adminfile, $prefix, $db;
		$cat = intval( $cat );
		if ( ! $cat )
		{
			include ( "../header.php" );

			newstopbanner();
			OpenTable();
			echo "<center><font class=\"option\"><b>" . _DELETECATEGORY . "</b></font><br><br>";
			$selcat = $db->sql_query( "select catid, parentid, title from " . $prefix . "_stories_cat ORDER BY parentid, weight" );
			echo "<form action=\"" . $adminfile . ".php\" method=\"post\">" . "<b>" . _SELECTCATDEL . ": </b>" . "<select name=\"cat\">";
			while ( $row = $db->sql_fetchrow($selcat) )
			{
				$catid = intval( $row['catid'] );
				$parentid = intval( $row['parentid'] );
				$title = $row['title'];
				if ( $parentid != 0 )
				{
					list( $ptitle ) = $db->sql_fetchrow( $db->sql_query("select title from " . $prefix . "_stories_cat where catid='$parentid'") );
					$title = "$ptitle &raquo; $title";
				}
				echo "<option name=\"cat\" value=\"$catid\">$title</option>";
			}
			echo "</select>" . "<input type=\"hidden\" name=\"op\" value=\"DelCategory\">" . "<input type=\"submit\" value=\"" . _DELETE . "\">" . "</form>";
			echo "</center>";
			CloseTable();
			include ( "../footer.php" );
		}
		else
		{
			$result1 = $db->sql_query( "select title, parentid from " . $prefix . "_stories_cat where catid='$cat'" );
			$row = $db->sql_fetchrow( $result1 );
			$title = $row['title'];
			$parentid = intval( $row['parentid'] );
			if ( ! $row )
			{
				Header( "Location: " . $adminfile . ".php?op=DelCategory" );
				exit;
			}
			if ( $db->sql_numrows($db->sql_query("select * from " . $prefix . "_stories_cat where parentid='$cat'")) > 0 )
			{
				info_exit( "" . _NOTEINCAT . "<br><br>" . _GOBACK . "</center>" );
			}
			$num = $db->sql_numrows( $db->sql_query("select * from " . $prefix . "_stories where catid='$cat'") );
			$num2 = $db->sql_numrows( $db->sql_query("select * from " . $prefix . "_stories_auto where catid='$cat'") );
			$num3 = $db->sql_numrows( $db->sql_query("select * from " . $prefix . "_stories_images where catid='$cat'") );
			if ( ($num == 0) and ($num2 == 0) and ($num3 == 0) )
			{
				$db->sql_query( "delete from " . $prefix . "_stories_cat where catid='$cat'" );
				fixweightcat();
				ncatlist();
				info_exit( "<br><b>" . _CATDELETED . "</b><META HTTP-EQUIV=\"refresh\" content=\"2;URL=" . $adminfile . ".php?op=ManagerCategory\">" );
			}
			else
			{
				include ( "../header.php" );

				newstopbanner();
				OpenTable();
				echo "<center><font class=\"option\"><b>" . _DELETECATEGORY . "</b></font><br><br>";
				echo "<br><br><b>" . _WARNING . ":</b> " . _THECATEGORY . " <b>$title</b> " . _HAS . " <b>$numrows</b> " . _STORIESINSIDE . "<br>" . "" . _DELCATWARNING1 . "<br>" . "" . _DELCATWARNING2 . "<br><br>" . "" . _DELCATWARNING3 . "<br><br>" . "<b>[ <a href=\"" . $adminfile . ".php?op=YesDelCategory&amp;catid=$cat\">" . _YESDEL . "</a> | " . "<a href=\"" . $adminfile . ".php?op=NoMoveCategory&amp;catid=$cat\">" . _NOMOVE . "</a> ]</b>";
				echo "</center>";
				CloseTable();
				include ( "../footer.php" );
			}
		}
	}

	/**
	 * YesDelCategory()
	 * 
	 * @param mixed $catid
	 * @return
	 */
	function YesDelCategory( $catid )
	{
		global $adminfile, $prefix, $db, $path;
		$catid = intval( $catid );
		$check = $db->sql_numrows( $db->sql_query("select * from " . $prefix . "_stories_cat where catid='$catid'") );
		if ( $check != 1 )
		{
			Header( "Location: " . $adminfile . ".php?op=DelCategory" );
			exit;
		}
		list( $parentid ) = $db->sql_fetchrow( $db->sql_query("select parentid from " . $prefix . "_stories_cat where catid='$catid'") );
		if ( $db->sql_numrows($db->sql_query("select * from " . $prefix . "_stories_cat where parentid='$catid'")) > 0 )
		{
			info_exit( "" . _NOTEINCAT . "<br><br>" . _GOBACK . "</center>" );
		}
		$result2 = $db->sql_query( "select sid, images from " . $prefix . "_stories where catid='$catid'" );
		while ( $row2 = $db->sql_fetchrow($result2) )
		{
			$sid = intval( $row2['sid'] );
			$images = $row2['images'];
			@unlink( "../" . $path . "/" . $images . "" );
			@unlink( "../" . $path . "/small_" . $images . "" );
			@unlink( "../" . $path . "/nst_" . $images . "" );
			$result2b = $db->sql_query( "SELECT imglink FROM " . $prefix . "_stories_images where sid='$sid'" );
			while ( $row2b = $db->sql_fetchrow($result2b) )
			{
				$imglink = $row2b['imglink'];
				$apath = "../" . $path . "";
				$filel = array_reverse( explode("/", $imglink) );
				if ( file_exists("" . $apath . "/" . $filel[0] . "") )
				{
					@unlink( "" . $apath . "/" . $filel[0] . "" );
				}
			}
			$db->sql_query( "delete from " . $prefix . "_stories where sid='$sid'" );
			$db->sql_query( "delete from " . $prefix . "_stories_comments where sid='$sid'" );
			$db->sql_query( "DELETE FROM " . $prefix . "_stories_images where sid=$sid" );
		}

		$result3 = $db->sql_query( "select anid, images from " . $prefix . "_stories_auto where catid='$catid'" );
		while ( $row3 = $db->sql_fetchrow($result3) )
		{
			$anid = intval( $row3['anid'] );
			$images = $row3['images'];
			@unlink( "../" . $path . "/" . $images . "" );
			@unlink( "../" . $path . "/small_" . $images . "" );
			@unlink( "../" . $path . "/nst_" . $images . "" );
			$db->sql_query( "delete from " . $prefix . "_stories_auto where anid='$anid'" );
			$db->sql_query( "OPTIMIZE TABLE " . $prefix . "_stories_auto" );
			liststauto();
		}

		$result4 = $db->sql_query( "select imgid, imglink from " . $prefix . "_stories_images where catid='$catid'" );
		while ( $row4 = $db->sql_fetchrow($result4) )
		{
			$imgid = intval( $row4['imgid'] );
			$filelink = $row4['imglink'];
			$filel = array_reverse( explode("/", $filelink) );
			if ( file_exists("../" . $path . "/" . $filel[0] . "") )
			{
				@unlink( "../" . $path . "/" . $filel[0] . "" );
			}
			$db->sql_query( "delete from " . $prefix . "_stories_images where imgid='$imgid'" );
		}

		$db->sql_query( "delete from " . $prefix . "_stories_cat where catid='$catid'" );
		fixweightcat();
		ncatlist();
		info_exit( "<center><b>" . _CATDELETED . "</b></center><META HTTP-EQUIV=\"refresh\" content=\"2;URL=" . $adminfile . ".php?op=ManagerCategory\">" );
	}

	/**
	 * NoMoveCategory()
	 * 
	 * @param mixed $catid
	 * @param mixed $newcat
	 * @return
	 */
	function NoMoveCategory( $catid, $newcat )
	{
		global $adminfile, $prefix, $db;
		$catid = intval( $catid );
		$newcat = intval( $newcat );
		$check = $db->sql_numrows( $db->sql_query("select * from " . $prefix . "_stories_cat where catid='$catid'") );
		if ( $check != 1 )
		{
			Header( "Location: " . $adminfile . ".php?op=DelCategory" );
			exit;
		}
		list( $parentid ) = $db->sql_fetchrow( $db->sql_query("select parentid from " . $prefix . "_stories_cat where catid='$catid'") );
		if ( $db->sql_numrows($db->sql_query("select * from " . $prefix . "_stories_cat where parentid='$catid'")) > 0 )
		{
			info_exit( "" . _NOTEINCAT . "<br><br>" . _GOBACK . "</center>" );
		}
		list( $title ) = $db->sql_fetchrow( $db->sql_query("select title from " . $prefix . "_stories_cat where catid='$catid'") );
		include ( "../header.php" );

		newstopbanner();
		OpenTable();
		echo "<center><font class=\"option\"><b>" . _MOVESTORIES . "</b></font><br><br>";
		if ( $newcat == 0 )
		{
			echo "" . _ALLSTORIES . " <b>$title</b> " . _WILLBEMOVED . ":<br><br>";
			$selcat = $db->sql_query( "select catid, parentid, title from " . $prefix . "_stories_cat WHERE catid!='$catid'" );
			echo "<form action=\"" . $adminfile . ".php\" method=\"post\">";
			echo "<b>" . _SELECTNEWCAT . ":</b> ";
			echo "<select name=\"newcat\">";
			echo "<option name=\"newcat\" value=\"0\">" . _NOCAT . "</option>";
			while ( list($newcat, $nparentid, $title2) = $db->sql_fetchrow($selcat) )
			{
				$newcat = intval( $newcat );
				if ( $nparentid != 0 )
				{
					list( $title3 ) = $db->sql_fetchrow( $db->sql_query("select title from " . $prefix . "_stories_cat where catid='$nparentid'") );
					$title2 = "$title3 &raquo; $title2";
				}
				echo "<option name=\"newcat\" value=\"$newcat\">$title2</option>";
			}
			echo "</select>";
			echo "<input type=\"hidden\" name=\"catid\" value=\"$catid\">";
			echo "<input type=\"hidden\" name=\"op\" value=\"NoMoveCategory\">";
			echo "<input type=\"submit\" value=\"" . _OK . "\">";
			echo "</form>";
		}
		else
		{
			$resultm = $db->sql_query( "select sid from " . $prefix . "_stories where catid='$catid'" );
			while ( list($sid) = $db->sql_fetchrow($resultm) )
			{
				$db->sql_query( "update " . $prefix . "_stories set catid='$newcat' where sid='" . intval($sid) . "'" );
			}
			$resultn = $db->sql_query( "select anid from " . $prefix . "_stories_auto where catid='$catid'" );
			while ( list($anid) = $db->sql_fetchrow($resultn) )
			{
				$db->sql_query( "update " . $prefix . "_stories_auto set catid='$newcat' where anid='" . intval($anid) . "'" );
			}
			$resulto = $db->sql_query( "select imgid from " . $prefix . "_stories_images where catid='$catid'" );
			while ( list($imgid) = $db->sql_fetchrow($resulto) )
			{
				$db->sql_query( "update " . $prefix . "_stories_images set catid='$newcat' where imgid='" . intval($imgid) . "'" );
			}
			$db->sql_query( "delete from " . $prefix . "_stories_cat where catid='$catid'" );
			fixweightcat();
			ncatlist();
			echo "<center><b>" . _MOVEDONE . "</b></center>";
			echo "<META HTTP-EQUIV=\"refresh\" content=\"2;URL=" . $adminfile . ".php?op=ManagerCategory\">";
		}
		CloseTable();
		include ( "../footer.php" );
	}

	/**
	 * SaveEditCategory()
	 * 
	 * @param mixed $catid
	 * @param mixed $parentid
	 * @param mixed $title
	 * @param mixed $catimage
	 * @param mixed $ihome
	 * @param mixed $linkshome
	 * @return
	 */
	function SaveEditCategory( $catid, $parentid, $title, $catimage, $ihome, $linkshome )
	{
		global $adminfile, $prefix, $db;
		$title = ereg_replace( "\"", "", $title );
		$catimage = ereg_replace( "\"", "", $catimage );
		$catid = intval( $catid );
		$parentid = intval( $parentid );
		if ( ! $catid or $catid == 0 or ! $title or $title == "" )
		{
			Header( "Location: " . $adminfile . ".php?op=EditCategory" );
			exit;
		}
		$check = $db->sql_numrows( $db->sql_query("select catid from " . $prefix . "_stories_cat where catid!='$catid' AND title='$title'") );
		if ( $check )
		{
			info_exit( "<center><font class=\"content\"><b>" . _CATEXISTS . "</b></font><br><br>" . _GOBACK . "</center>" );
		}
		if ( $parentid != 0 )
		{
			if ( $db->sql_numrows($db->sql_query("select * from " . $prefix . "_stories_cat where parentid='$catid'")) != 0 )
			{
				info_exit( "" . _NOTEINCAT . "" );
			}
		}
		$db->sql_query( "update " . $prefix . "_stories_cat set parentid='$parentid', title='$title', catimage='$catimage',  ihome='$ihome', linkshome='$linkshome' where catid='$catid'" );
		fixweightcat();
		ncatlist();
		Header( "Location: " . $adminfile . ".php?op=ManagerCategory" );
	}

	/**
	 * SaveCstorieshomeCategory()
	 * 
	 * @param mixed $catid
	 * @param mixed $storieshome
	 * @return
	 */
	function SaveCstorieshomeCategory( $catid, $storieshome )
	{
		global $adminfile, $prefix, $db;
		$check = $db->sql_numrows( $db->sql_query("select * from " . $prefix . "_stories_cat where catid='" . intval($catid) . "'") );
		if ( $check != 1 )
		{
			Header( "Location: " . $adminfile . ".php?op=ManagerCategory" );
			exit;
		}
		$db->sql_query( "update " . $prefix . "_stories_cat set storieshome='" . intval($storieshome) . "' where catid='" . intval($catid) . "'" );
		ncatlist();
		Header( "Location: " . $adminfile . ".php?op=ManagerCategory" );
	}

	/**
	 * SaveCategory()
	 * 
	 * @param mixed $title
	 * @param mixed $parentid
	 * @param mixed $catimage
	 * @param mixed $xihome
	 * @param mixed $storieshome
	 * @param mixed $linkshome
	 * @return
	 */
	function SaveCategory( $title, $parentid, $catimage, $xihome, $storieshome, $linkshome )
	{
		global $adminfile, $prefix, $db;
		$title = ereg_replace( "\"", "", $title );
		$catimage = ereg_replace( "\"", "", $catimage );
		$parentid = intval( $parentid );
		if ( $title == "" )
		{
			Header( "Location: " . $adminfile . ".php?op=ManagerCategory" );
			exit();
		}
		$row = $db->sql_numrows( $db->sql_query("select title from " . $prefix . "_stories_cat where title='$title'") );
		if ( $row )
		{
			info_exit( "<center><font class=\"content\"><b>" . _CATEXISTS . "</b></font><br><br>" . _GOBACK . "</center>" );
		}

		list( $xweight ) = $db->sql_fetchrow( $db->sql_query("SELECT max(weight) AS xweight FROM " . $prefix . "_stories_cat WHERE parentid='$parentid'") );
		if ( $xweight == "-1" )
		{
			$weight = 1;
		}
		else
		{
			$weight = $xweight + 1;
		}
		$result = $db->sql_query( "insert into " . $prefix . "_stories_cat values (NULL, '$parentid', '$title', '$weight', '$catimage', '$xihome', '$storieshome', '$linkshome')" );
		fixweightcat();
		ncatlist();
		Header( "Location: " . $adminfile . ".php?op=ManagerCategory" );
	}

	/**
	 * SelectTopic()
	 * 
	 * @param mixed $topicid
	 * @return
	 */
	function SelectTopic( $topicid )
	{
		global $adminfile, $prefix, $db;
		$sql = "select topicid, topictitle from " . $prefix . "_stories_topic order by topictitle";
		$result = $db->sql_query( $sql );
		echo "<b><a href=\"" . $adminfile . ".php?op=ManagerTopic\" title=\"" . _TOPICSADMIN . "\" target=\"blank\">" . _TOPICS . "</a></b> ";
		echo "<select name=\"topicid\">";
		echo "<option name=\"topicid\" value=\"0\">" . _ASELECTTOPIC . "</option>";
		while ( $row = $db->sql_fetchrow($result) )
		{
			echo "<option name=\"topicid\" value=\"$row[topicid]\"";
			if ( $row['topicid'] == $topicid )
			{
				echo " selected";
			}
			echo ">$row[topictitle]</option>";
		}
		echo "</select>";
	}

	/**
	 * xaddtopic()
	 * 
	 * @return
	 */
	function xaddtopic()
	{
		global $adminfile;
		OpenTable();
		echo "<center><font class=\"option\"><b>" . _TOPICADD . "</b></font><br><br><br>" . "<form action=\"" . $adminfile . ".php\" method=\"post\">" . "<b>" . _TOPICNAME . ":</b> " . "<input type=\"text\" name=\"topictitle\" size=\"32\"> " . "<input type=\"hidden\" name=\"op\" value=\"SaveTopic\">" . "&nbsp;<input type=\"submit\" value=\"" . _SAVE . "\">" . "</form></center>";
		CloseTable();
		echo "<br>";
	}

	/**
	 * ManagerTopic()
	 * 
	 * @return
	 */
	function ManagerTopic()
	{
		global $adminfile, $prefix, $db;
		include ( "../header.php" );

		newstopbanner();
		OpenTable();
		echo "<center><font class=\"title\"><b>" . _TOPICSADMIN . "</b></font></center>";
		CloseTable();
		echo "<br>";
		xaddtopic();
		$result = $db->sql_query( "select * from " . $prefix . "_stories_topic" );
		if ( $db->sql_numrows($result) > 0 )
		{
			OpenTable();
			echo "<table border=\"1\" width=\"100%\"><tr>" . "<td align=\"center\"><b>" . _TOPICS . "</b></td><td align=\"center\"><b>" . _FUNCTIONS . "</b></td></tr>";
			while ( $row = $db->sql_fetchrow($result) )
			{
				$topicid = intval( $row['topicid'] );
				$topictitle = $row['topictitle'];
				$functions = "[ <a href=\"" . $adminfile . ".php?op=ViewTopic&topicid=$topicid\">" . _SHOW . "</a> | <a href=\"" . $adminfile . ".php?op=EditTopic&topicid=$topicid\">" . _EDIT . "</a> | <a href=\"" . $adminfile . ".php?op=DelTopic&topicid=$topicid\">" . _DELETE . "</a> ]";
				echo "<tr><td align=\"center\">$topictitle</td><td align=\"center\">$functions</td></tr>";
			}
			echo "</table>";
			CloseTable();
		}
		include ( "../footer.php" );
	}

	/**
	 * ViewTopic()
	 * 
	 * @param mixed $topicid
	 * @param mixed $xtitle
	 * @return
	 */
	function ViewTopic( $topicid, $xtitle )
	{
		global $adminfile, $prefix, $db;
		$result = $db->sql_query( "select topictitle from " . $prefix . "_stories_topic where topicid='$topicid'" );
		if ( $db->sql_numrows($result) == 0 )
		{
			Header( "Location: " . $adminfile . ".php?op=ManagerTopic" );
			exit;
		}
		$row = $db->sql_fetchrow( $result );
		$topictitle = $row['topictitle'];
		include ( "../header.php" );

		newstopbanner();
		OpenTable();
		echo "<center><font class=\"title\"><b>" . _TOPICSADMIN . "</b></font></center>";
		CloseTable();
		echo "<br>";
		OpenTable();
		echo "<center><font class=\"option\"><b>" . _VIEWTOPIC . ": $topictitle</b></font><br><br><br></center>";
		$result2 = $db->sql_query( "select sid, title, hometext from " . $prefix . "_stories where topicid='$topicid'" );
		if ( $db->sql_numrows($result2) == 0 )
		{
			echo "<center>" . _NOSTORIESTP . "</center>";
		}
		else
		{
			echo "<table border=\"1\" cellpadding=\"3\" cellspacing=\"3\" width=\"100%\">";
			$a = 1;
			while ( $row2 = $db->sql_fetchrow($result2) )
			{
				$sid = intval( $row2['sid'] );
				$title = $row2['title'];
				$hometext = $row2['hometext'];
				echo "<tr>\n<td>$a</td>\n<td width=\"100%\"><a href=\"../modules.php?name=News&op=viewst&sid=$sid\">$title</a></td>\n<td align=\"right\">\n<a title=\"" . _OUTTOPIC . "\" href=\"" . $adminfile . ".php?op=OutTopic&amp;topicid=$topicid&amp;sid=$sid\">\n<img border=\"0\" src=\"../images/out.gif\" width=\"20\" height=\"20\" alt=\"" . _OUTTOPIC . "\"></a></td></tr>";
				$a++;
			}
			echo "</table>\n";
		}
		CloseTable();
		echo "<br>";
		OpenTable();
		echo "<center><font class=\"option\"><b>" . _INTOPIC . ": $topictitle</b></font><br><br><br>";
		if ( $xtitle != "" )
		{
			$result3 = $db->sql_query( "select sid, title, hometext  from " . $prefix . "_stories where (title like '%$xtitle%' OR hometext like '%$xtitle%' OR bodytext like '%$xtitle%' OR notes  like '%$xtitle%' ) AND topicid!='$topicid'" );
			$nrows3 = $db->sql_numrows( $result3 );
			if ( $nrows3 > 0 )
			{
				echo "<table border=\"1\" cellpadding=\"3\" cellspacing=\"3\" width=\"100%\">";
				$x = 1;
				while ( $row3 = $db->sql_fetchrow($result3) )
				{
					$sid3 = intval( $row3['sid'] );
					$title3 = $row3['title'];
					$hometext3 = $row3['hometext'];
					echo "<tr>\n<td>$x</td>\n<td width=\"100%\"><a href=\"../modules.php?name=News&op=viewst&sid=$sid3\">$title3</a></td>\n<td align=\"right\">\n<a title=\"" . _INTOPIC . "\" href=\"" . $adminfile . ".php?op=InTopic&amp;topicid=$topicid&amp;sid=$sid3\">\n<img border=\"0\" src=\"../images/in.gif\" width=\"20\" height=\"20\" alt=\"" . _INTOPIC . "\"></a></td></tr>";
					$x++;
				}
				echo "</table>\n<br>";
			}
		}
		echo "<form action=\"" . $adminfile . ".php\" method=\"post\">" . "<b>" . _SEARCHARTICLE . ":</b> " . "<input type=\"text\" name=\"xtitle\" size=\"42\"> " . "<input type=\"hidden\" name=\"topicid\" value=\"$topicid\">" . "<input type=\"hidden\" name=\"op\" value=\"ViewTopic\">" . "<input type=\"submit\" value=\"" . _SEARCH . "\">" . "</form>";
		echo "</center>";
		CloseTable();
		include ( "../footer.php" );
	}

	/**
	 * OutTopic()
	 * 
	 * @param mixed $topicid
	 * @param mixed $sid
	 * @return
	 */
	function OutTopic( $topicid, $sid )
	{
		global $adminfile, $prefix, $db;
		$topicid = intval( $topicid );
		$sid = intval( $sid );
		$check = $db->sql_numrows( $db->sql_query("select * from " . $prefix . "_stories_topic where topicid='$topicid'") );
		$check2 = $db->sql_numrows( $db->sql_query("select * from " . $prefix . "_stories where sid='$sid' AND topicid='$topicid'") );
		if ( ($check != 1) or ($check2 != 1) )
		{
			Header( "Location: " . $adminfile . ".php?op=ManagerTopic" );
			exit;
		}
		$db->sql_query( "update " . $prefix . "_stories set topicid='0' where sid='$sid'" );
		Header( "Location: " . $adminfile . ".php?op=ViewTopic&topicid=$topicid" );
	}

	/**
	 * InTopic()
	 * 
	 * @param mixed $topicid
	 * @param mixed $sid
	 * @return
	 */
	function InTopic( $topicid, $sid )
	{
		global $adminfile, $prefix, $db;
		$topicid = intval( $topicid );
		$sid = intval( $sid );
		$check = $db->sql_numrows( $db->sql_query("select * from " . $prefix . "_stories_topic where topicid='$topicid'") );
		$check2 = $db->sql_numrows( $db->sql_query("select * from " . $prefix . "_stories where sid='$sid' AND topicid!='$topicid'") );
		if ( ($check != 1) or ($check2 != 1) )
		{
			Header( "Location: " . $adminfile . ".php?op=ManagerTopic" );
			exit;
		}
		$db->sql_query( "update " . $prefix . "_stories set topicid='$topicid' where sid='$sid'" );
		Header( "Location: " . $adminfile . ".php?op=ViewTopic&topicid=$topicid" );
	}

	/**
	 * AddTopic()
	 * 
	 * @return
	 */
	function AddTopic()
	{
		include ( "../header.php" );

		newstopbanner();
		OpenTable();
		echo "<center><font class=\"title\"><b>" . _TOPICSADMIN . "</b></font></center>";
		CloseTable();
		echo "<br>";
		xaddtopic();
		include ( "../footer.php" );
	}

	/**
	 * EditTopic()
	 * 
	 * @param mixed $topicid
	 * @return
	 */
	function EditTopic( $topicid )
	{
		global $adminfile, $prefix, $db;
		$topicid = intval( $topicid );
		include ( "../header.php" );

		newstopbanner();
		OpenTable();
		echo "<center><font class=\"title\"><b>" . _TOPICSADMIN . "</b></font></center>";
		CloseTable();
		echo "<br>";
		OpenTable();
		echo "<center><font class=\"option\"><b>" . _EDITTOPIC . "</b></font><br>";
		if ( ! $topicid || $topicid == 0 )
		{
			$result = $db->sql_query( "select topicid, topictitle from " . $prefix . "_stories_topic" );
			echo "<form action=\"" . $adminfile . ".php\" method=\"post\">";
			echo "<b>" . _ASELECTTOPIC . "</b>";
			echo "<select name=\"topicid\">";
			echo "<option name=\"topicid\" value=\"0\">" . _THETOPIC . "</option>";
			while ( $row = $db->sql_fetchrow($result) )
			{
				$topicid = intval( $row['topicid'] );
				$topictitle = $row['topictitle'];
				echo "<option name=\"topicid\" value=\"$topicid\">$topictitle</option>";
			}
			echo "</select>";
			echo "<input type=\"hidden\" name=\"op\" value=\"EditTopic\">";
			echo "<input type=\"submit\" value=\"" . _EDIT . "\"></form><br><br>";
			echo "" . _NOARTTOPICEDIT . "";
		}
		else
		{
			$result = $db->sql_query( "select topictitle from " . $prefix . "_stories_topic where topicid='$topicid'" );
			$row = $db->sql_fetchrow( $result );
			$topictitle = $row['topictitle'];
			echo "<form action=\"" . $adminfile . ".php\" method=\"post\">";
			echo "<b>" . _TOPICNAME . ":</b> ";
			echo "<input type=\"text\" name=\"topictitle\" size=\"32\" value=\"$topictitle\"> ";
			echo "<input type=\"hidden\" name=\"topicid\" value=\"$topicid\">";
			echo "<input type=\"hidden\" name=\"op\" value=\"SaveEditTopic\">";
			echo "&nbsp;<input type=\"submit\" value=\"" . _SAVECHANGES . "\"><br><br>";
			echo "" . _NOARTTOPICEDIT2 . "";
			echo "</form>";
		}
		echo "</center>";
		CloseTable();
		include ( "../footer.php" );
	}

	/**
	 * DelTopic()
	 * 
	 * @param mixed $topicid
	 * @return
	 */
	function DelTopic( $topicid )
	{
		global $adminfile, $prefix, $db;
		$topicid = intval( $topicid );
		if ( ! $topicid )
		{
			include ( "../header.php" );

			newstopbanner();
			OpenTable();
			echo "<center><font class=\"title\"><b>" . _TOPICSADMIN . "</b></font></center>";
			CloseTable();
			echo "<br>";
			OpenTable();
			echo "<center><font class=\"option\"><b>" . _DELETETOPIC . "</b></font><br><br><br>";
			$seltopic = $db->sql_query( "select topicid, topictitle from " . $prefix . "_stories_topic" );
			echo "<form action=\"" . $adminfile . ".php\" method=\"post\">" . "<b>" . _SELECTTOPICDEL . ": </b>" . "<select name=\"topicid\">";
			while ( $row = $db->sql_fetchrow($seltopic) )
			{
				$topicid = $row['topicid'];
				$topictitle = $row['topictitle'];
				echo "<option name=\"topicid\" value=\"$topicid\">$topictitle</option>";
			}
			echo "</select>" . "<input type=\"hidden\" name=\"op\" value=\"DelTopic\">" . "<input type=\"submit\" value=\"" . _DELETE . "\">" . "</form><br><br>";
			echo "</center>";
			CloseTable();
			include ( "../footer.php" );
		}
		else
		{
			$result = $db->sql_query( "select topictitle from " . $prefix . "_stories_topic where topicid='$topicid'" );
			$row = $db->sql_fetchrow( $result );
			$topictitle = $row['topictitle'];
			if ( $db->sql_numrows($db->sql_query("select * from " . $prefix . "_stories where topicid='$topicid'")) != 0 )
			{
				$result = $db->sql_query( "select sid from " . $prefix . "_stories where topicid='$topicid'" );
				while ( $row = $db->sql_fetchrow($result) )
				{
					$sid = intval( $row['sid'] );
					$db->sql_query( "update " . $prefix . "_stories set topicid='0' where sid='$sid'" );
				}
			}
			$db->sql_query( "delete from " . $prefix . "_stories_topic where topicid='$topicid'" );
			info_exit( "<center><b>" . _TOPICDELETED . "</b></center><META HTTP-EQUIV=\"refresh\" content=\"2;URL=" . $adminfile . ".php?op=ManagerTopic\">" );
		}
	}

	/**
	 * SaveEditTopic()
	 * 
	 * @param mixed $topicid
	 * @param mixed $topictitle
	 * @return
	 */
	function SaveEditTopic( $topicid, $topictitle )
	{
		global $adminfile, $prefix, $db;
		$topictitle = ereg_replace( "\"", "", $topictitle );
		$topicid = intval( $topicid );
		if ( ! $topicid or $topicid == 0 or ! $topictitle or $topictitle == "" )
		{
			Header( "Location: " . $adminfile . ".php?op=EditTopic" );
			exit;
		}
		if ( $db->sql_numrows($db->sql_query("select catid from " . $prefix . "_stories_topic where topicid!='$topicid' AND topictitle='$topictitle'")) > 0 )
		{
			info_exit( "<center><font class=\"content\"><b>" . _TOPICEXISTS . "</b></font><br><br>" . _GOBACK . "</center>" );
		}
		$result = $db->sql_query( "update " . $prefix . "_stories_topic set topictitle='$topictitle' where topicid='$topicid'" );
		if ( ! $result )
		{
			return;
		}
		Header( "Location: " . $adminfile . ".php?op=ManagerTopic" );
	}

	/**
	 * SaveTopic()
	 * 
	 * @param mixed $topictitle
	 * @return
	 */
	function SaveTopic( $topictitle )
	{
		global $adminfile, $prefix, $db;
		$topictitle = ereg_replace( "\"", "", $topictitle );
		if ( $topictitle == "" )
		{
			Header( "Location: " . $adminfile . ".php?op=ManagerTopic" );
			exit();
		}
		$check = $db->sql_numrows( $db->sql_query("select topictitle from " . $prefix . "_stories_topic where topictitle='$topictitle'") );
		if ( $row )
		{
			info_exit( "<center><font class=\"content\"><b>" . _TOPICEXISTS . "</b></font><br><br>" . _GOBACK . "</center>" );
		}
		$result = $db->sql_query( "insert into " . $prefix . "_stories_topic values (NULL, '$topictitle')" );
		if ( ! $result )
		{
			return;
		}
		info_exit( "<center><font class=\"content\"><b>" . _TOPICADDED . "</b></font></center><META HTTP-EQUIV=\"refresh\" content=\"2;URL=" . $adminfile . ".php?op=ManagerTopic\">" );
	}

	/**
	 * displayStory()
	 * 
	 * @param mixed $sid
	 * @return
	 */
	function displayStory( $sid )
	{
		global $editor, $hourdiff, $adminfile, $newsarticleimg, $temp_path, $bgcolor1, $bgcolor2, $prefix, $user_prefix, $db, $multilingual, $sizenewsarticleimg;
		$sid = intval( $sid );
		$result1 = $db->sql_query( "SELECT * FROM " . $prefix . "_stories_temp where sid='$sid'" );
		$num = $db->sql_numrows( $result1 );
		if ( $num != 1 )
		{
			Header( "Location: " . $adminfile . ".php?op=newsadminhome" );
			exit;
		}
		include ( '../header.php' );

		newstopbanner();
		OpenTable();
		echo "<center><font class=\"title\"><b>" . _SUBMISSIONSADMIN . "</b></font></center>";
		CloseTable();
		echo "<br>";
		$row = $db->sql_fetchrow( $result1 );
		$sid = intval( $row['sid'] );
		$cat = intval( $row['catid'] );
		$topicid = intval( $row['topicid'] );

		$aid = nv_htmlspecialchars( strip_tags(stripslashes(trim($row['aid']))) );
		$images = $row['images'];
		$alanguage = $row['alanguage'];
		$sender_ip = $row['sender_ip'];

		$title = nv_htmlspecialchars( strip_tags(stripslashes(trim($row['title']))) );
		$hometext = cheonguoc( stripslashes($row['hometext']) );
		$bodytext = cheonguoc( stripslashes($row['bodytext']) );

		$imgtext = nv_htmlspecialchars( strip_tags(stripslashes(trim($row['imgtext']))) );
		$notes = cheonguoc( stripslashes($row['notes']) );

		$source = nv_htmlspecialchars( strip_tags(stripslashes(trim($row['source']))) );
		if ( $notes != "" )
		{
			$story_notes = "<hr>$notes";
		}
		else
		{
			$story_notes = "";
		}
		$story_pic = "";
		if ( $images != "" )
		{
			$size2 = @getimagesize( "../$temp_path/$images" );
			$widthimg = $size2[0];
			if ( $size2[0] > $sizenewsarticleimg )
			{
				$widthimg = $sizenewsarticleimg;
			}
			$story_pic = "<table border=\"0\" width=\"$widthimg\" cellpadding=\"0\" cellspacing=\"3\" align=\"$newsarticleimg\">\n<tr>\n<td>\n<img border=\"0\" src=\"../$temp_path/$images\" width=\"$widthimg\"></td>\n</tr>\n<tr>\n<td align=\"center\"><i><b>$imgtext</b></i></td>\n</tr>\n</table>\n";
		}
		OpenTable();
		echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"10\" width=\"100%\">
    <tr><td><font class=storytitle>$title</font></td></tr>
    <tr><td>$story_pic$hometext<br><br>$bodytext</td></tr>
    <tr><td>$story_notes</td></tr>
    </table>";
		CloseTable();
		echo "<br>";
		OpenTable();
		echo "<font class=\"content\">" . "<form enctype=\"multipart/form-data\" action=\"" . $adminfile . ".php\" method=\"post\">" . "<b>" . _SENDERNAME . "</b> ";
		$sql2 = "select * from " . $user_prefix . "_users where username='$aid'";
		$result2 = $db->sql_query( $sql2 );
		$row2 = $db->sql_numrows( $result2 );
		if ( $row2 == 1 )
		{
			$row2a = $db->sql_fetchrow( $result2 );
			echo "<font class=\"content\">[ <a href='" . $adminfile . ".php?op=modifyUser&chng_uid=$row2a[user_id]'>" . _USERPROFILE . "</a> | IP: <a href='" . $adminfile . ".php?op=ConfigureBan&bad_ip=$sender_ip'>$sender_ip</a> ]</font>";
		}
		else
		{
			echo "<font class=\"content\">[ IP: <a href='" . $adminfile . ".php?op=ConfigureBan&bad_ip=$sender_ip'>$sender_ip</a> ]</font>";
		}
		echo "<br><input type=\"text\" NAME=\"author\" size=\"25\" value=\"$aid\">&nbsp; " . "<b>" . _SOURCE . "</b> " . "<input type=\"text\" name=\"source\" size=\"44\" value=\"$source\"><br><br>";
		echo "<b>" . _TITLE . "</b><br>" . "<input type=\"text\" name=\"subject\" size=\"85\" value=\"$title\"><br><br>";
		SelectCategory( $cat );
		echo "<br><br>";
		SelectTopic( $topicid );
		echo "<br>";
		puthome( 1, 0 );
		if ( $multilingual == 1 )
		{
			echo "<br><b>" . _LANGUAGE . ": </b>" . "<select name=\"alanguage\">";
			echo "<option value=\"\" $sellang>" . _ALL . "</option>";
			echo select_language( $alanguage );
			echo "</select>";
		}
		else
		{
			echo "<input type=\"hidden\" name=\"alanguage\" value=\"$alanguage\">";
		}
		$hometext = str_replace( "<br />", "\r\n", $hometext );
		if ( ! $editor )
		{
			$bodytext = str_replace( "<br />", "\r\n", $bodytext );
			$nottext = str_replace( "<br />", "\r\n", $nottext );
		}
		echo "<br><br><b>" . _STORYTEXT . "</b><br>" . "<textarea wrap=\"virtual\" cols=\"85\" rows=\"7\" name=\"hometext\">$hometext</textarea><br><br>" . "<b>" . _EXTENDEDTEXT . "</b><br>";
		if ( $editor == 1 )
		{
			aleditor( "bodytext", $bodytext, 500, 250 );
		}
		else
		{
			echo "<textarea wrap=\"virtual\" cols=\"85\" rows=\"7\" name=\"bodytext\">$bodytext</textarea>";
		}
		echo "<br><br><b>" . _NOTES . "</b><br>";






		echo "<textarea wrap=\"virtual\" cols=\"85\" rows=\"3\" name=\"notes\">$notes</textarea>";

		echo "<br><br>";
		if ( $images != "" )
		{
			echo "<b>" . _DELSTPIC . ":</b> ";
			echo "<input type=\"checkbox\" name=\"delpic\" value=\"yes\"> <a href=\"../$temp_path/$images\" target=\"_blank\">$images</a><br><br>";
		}
		echo "<input type=\"hidden\" name=\"images\" value=\"$images\">";
		echo "<b>" . _STPIC . ":</b> " . "<input name=\"userfile\" type=\"file\"><br><br>";
		echo "" . _IMGTEXT . ": <input type=\"text\" name=\"imgtext\" value=\"$imgtext\" size=\"44\"><br><br>";
		echo "<input type=\"hidden\" NAME=\"sid\" value=\"$sid\">" . "<input type=\"hidden\" NAME=\"sender_ip\" value=\"$sender_ip\">" . "<br><b>" . _PROGRAMSTORY . "</b>&nbsp;&nbsp;" . "<input type=\"radio\" name=\"automated\" value=\"1\">" . _YES . " &nbsp;&nbsp;" . "<input type=\"radio\" name=\"automated\" value=\"0\" checked>" . _NO . "&nbsp;&nbsp;&nbsp; " . "<br><br>" . _YESPROGRAMSTORY . " (" . _NOWIS . ": " . viewtime( time(), 2 ) . "):<br><br>";
		echo "" . _DAY . ": <select name=\"day\">";
		for ( $i = 1; $i <= 31; $i++ )
		{
			echo "<option value=\"" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "\"";
			if ( $i == date("d", time() + $hourdiff * 60) )
			{
				echo " selected";
			}
			echo ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
		}
		echo "</select>";
		echo "" . _UMONTH . ": <select name=\"month\">";
		for ( $i = 1; $i <= 12; $i++ )
		{
			echo "<option value=\"" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "\"";
			if ( $i == date("m", time() + $hourdiff * 60) )
			{
				echo " selected";
			}
			echo ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
		}
		echo "</select>";
		echo "" . _YEAR . ": <select name=\"year\">";
		$z = date( "Y", time() + $hourdiff * 60 );
		for ( $i = $z; $i <= $z + 4; $i++ )
		{
			echo "<option value=\"$i\"";
			if ( $i == $z )
			{
				echo " selected";
			}
			echo ">$i</option>\n";
		}
		echo "</select>";
		echo " " . _HOUR . ": <select name=\"hour\">";
		for ( $i = 0; $i < 24; $i++ )
		{
			echo "<option value=\"" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "\"";
			if ( $i == date("H", time() + $hourdiff * 60) )
			{
				echo " selected";
			}
			echo ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
		}
		echo "</select>";
		echo ": <select name=\"min\">";
		for ( $i = 0; $i < 60; $i++ )
		{
			echo "<option value=\"" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "\"";
			if ( $i == date("i", time() + $hourdiff * 60) )
			{
				echo " selected";
			}
			echo ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
		}
		echo "</select>";
		echo ": 00<br><br>" . "<select name=\"op\">" . "<option value=\"DeleteStory\">" . _DELETESTORY . "</option>" . "<option value=\"PreviewAgain\" selected>" . _PREVIEWSTORY . "</option>" . "<option value=\"PostStory\">" . _POSTSTORY . "</option>" . "</select>" . "<input type=\"submit\" value=\"" . _OK . "\">";
		echo "<br>";
		echo "</form>";
		CloseTable();
		include ( '../footer.php' );
	}

	/**
	 * previewStory()
	 * 
	 * @param mixed $sender_ip
	 * @param mixed $delpic
	 * @param mixed $images
	 * @param mixed $imgtext
	 * @param mixed $automated
	 * @param mixed $year
	 * @param mixed $day
	 * @param mixed $month
	 * @param mixed $hour
	 * @param mixed $min
	 * @param mixed $sid
	 * @param mixed $author
	 * @param mixed $subject
	 * @param mixed $hometext
	 * @param mixed $bodytext
	 * @param mixed $notes
	 * @param mixed $catid
	 * @param mixed $topicid
	 * @param mixed $ihome
	 * @param mixed $alanguage
	 * @param mixed $acomm
	 * @param mixed $source
	 * @return
	 */
	function previewStory( $sender_ip, $delpic, $images, $imgtext, $automated, $year, $day, $month, $hour, $min, $sid, $author, $subject, $hometext, $bodytext, $notes, $catid, $topicid, $ihome, $alanguage, $acomm, $source )
	{
		global $editor, $hourdiff, $adminfile, $max_size, $width, $height, $newsarticleimg, $sizenewsarticleimg, $temp_path, $anonymous, $bgcolor1, $bgcolor2, $prefix, $user_prefix, $db, $multilingual;
		$sid = intval( $sid );
		$num = $db->sql_numrows( $db->sql_query("SELECT * FROM " . $prefix . "_stories_temp where sid='$sid'") );
		if ( $num != 1 )
		{
			Header( "Location: " . $adminfile . ".php?op=newsadminhome" );
			exit;
		}
		$images = @uploadimg( $images, $delpic, 1, $sizenewsarticleimg, $temp_path );
		if ( $images == "" ) $imgtext = "";
		include ( '../header.php' );

		newstopbanner();
		OpenTable();
		echo "<center><font class=\"title\"><b>" . _ARTICLEADMIN . "</b></font></center>";
		CloseTable();
		echo "<br>";
		$subject = htmlspecialchars( check_html($subject) );
		$author = htmlspecialchars( check_html($author) );

		$source = htmlspecialchars( check_html($source) );

		$hometext = cheonguoc( nl2brStrict(stripslashes($hometext)) );
		$bodytext = cheonguoc( stripslashes(FixQuotes($bodytext)) );
		$imgtext = htmlspecialchars( check_html($imgtext) );
		$notes = cheonguoc( stripslashes($notes) );
		if ( ! $editor )
		{
			$bodytext = cheonguoc( nl2brStrict($bodytext) );
			$notes = cheonguoc( nl2brStrict($notes) );
		}
		$story_notes = "";
		if ( $notes != "" )
		{
			$story_notes = "<hr>$notes";
		}
		$story_pic = "";
		if ( $images != "" )
		{
			$size2 = @getimagesize( "../$temp_path/$images" );
			$widthimg = $size2[0];
			if ( $size2[0] > $sizenewsarticleimg )
			{
				$widthimg = $sizenewsarticleimg;
			}
			$story_pic = "<table border=\"0\" width=\"$widthimg\" cellpadding=\"0\" cellspacing=\"3\" align=\"$newsarticleimg\">\n<tr>\n<td>\n<img border=\"0\" src=\"../$temp_path/$images\" width=\"$widthimg\"></td>\n</tr>\n<tr>\n<td align=\"center\"><i><b>$imgtext</b></i></td>\n</tr>\n</table>\n";
		}
		OpenTable();
		echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"10\" width=\"100%\">
    <tr><td><font class=storytitle>$subject</font></td></tr>
    <tr><td>$story_pic$hometext<br><br>$bodytext</td></tr>
    <tr><td>$story_notes</td></tr>
    </table>";
		CloseTable();
		echo "<br>";
		OpenTable();
		echo "<font class=\"content\">" . "<form enctype=\"multipart/form-data\" action=\"" . $adminfile . ".php\" method=\"post\">" . "<b>" . _SENDERNAME . "</b> ";

		$sql2 = "select * from " . $user_prefix . "_users where username='$author'";
		$result2 = $db->sql_query( $sql2 );
		$row2 = $db->sql_numrows( $result2 );
		if ( $row2 == 1 )
		{
			$row2a = $db->sql_fetchrow( $result2 );
			echo "<font class=\"content\">[ <a href='" . $adminfile . ".php?op=modifyUser&chng_uid=$row2a[user_id]'>" . _USERPROFILE . "</a> | IP: <a href='" . $adminfile . ".php?op=ConfigureBan&bad_ip=$sender_ip'>$sender_ip</a> ]</font>";
		}
		else
		{
			echo "<font class=\"content\">[ IP: <a href='" . $adminfile . ".php?op=ConfigureBan&bad_ip=$sender_ip'>$sender_ip</a> ]</font>";
		}
		echo "<br><input type=\"text\" NAME=\"author\" size=\"25\" value=\"$author\">&nbsp; " . "<b>" . _SOURCE . "</b> " . "<input type=\"text\" name=\"source\" size=\"44\" value=\"$source\"><br><br>";
		echo "<b>" . _TITLE . "</b><br>" . "<input type=\"text\" name=\"subject\" size=\"85\" value=\"$subject\"><br><br>";
		$cat = $catid;
		SelectCategory( $cat );
		echo "<br><br>";
		SelectTopic( $topicid );
		echo "<br>";
		puthome( $ihome, $acomm );
		if ( $multilingual == 1 )
		{
			echo "<br><b>" . _LANGUAGE . ": </b>" . "<select name=\"alanguage\">";
			echo "<option value=\"\" $sellang>" . _ALL . "</option>";
			echo select_language( $alanguage );
			echo "</select>";
		}
		else
		{
			echo "<input type=\"hidden\" name=\"alanguage\" value=\"$alanguage\">";
		}
		$hometext = str_replace( "<br />", "\r\n", $hometext );
		if ( ! $editor )
		{
			$bodytext = str_replace( "<br />", "\r\n", $bodytext );
			$nottext = str_replace( "<br />", "\r\n", $nottext );
		}
		echo "<br><br><b>" . _STORYTEXT . "</b><br>" . "<textarea wrap=\"virtual\" cols=\"85\" rows=\"7\" name=\"hometext\">$hometext</textarea><br><br>" . "<b>" . _EXTENDEDTEXT . "</b><br>";
		if ( $editor == 1 )
		{
			aleditor( "bodytext", $bodytext, 500, 250 );
		}
		else
		{
			echo "<textarea wrap=\"virtual\" cols=\"85\" rows=\"7\" name=\"bodytext\">$bodytext</textarea>";
		}
		echo "<br><br><b>" . _NOTES . "</b><br>";
		if ( $editor == 1 )
		{
			aleditor( "notes", $notes, 500, 250 );
		}
		else
		{
			echo "<textarea wrap=\"virtual\" cols=\"85\" rows=\"3\" name=\"notes\">$notes</textarea>";
		}
		echo "<br><br>";
		if ( $images != "" )
		{
			echo "<b>" . _DELSTPIC . ":</b> ";
			echo "<input type=\"checkbox\" name=\"delpic\" value=\"yes\"> <a href=\"../$temp_path/$images\" target=\"_blank\">$images</a><br><br>";
		}
		echo "<input type=\"hidden\" name=\"images\" value=\"$images\">";
		echo "<b>" . _STPIC . ":</b> " . "<input name=\"userfile\" type=\"file\"><br><br>";
		echo "" . _IMGTEXT . ": <input type=\"text\" name=\"imgtext\" value=\"$imgtext\" size=\"44\"><br><br>";
		echo "<input type=\"hidden\" NAME=\"sid\" value=\"$sid\">" . "<input type=\"hidden\" NAME=\"sender_ip\" value=\"$sender_ip\">";
		if ( $automated == 1 )
		{
			$sel1 = "checked";
			$sel2 = "";
		}
		else
		{
			$sel1 = "";
			$sel2 = "checked";
		}
		echo "<br><b>" . _PROGRAMSTORY . "</b>&nbsp;&nbsp;" . "<input type=\"radio\" name=\"automated\" value=\"1\" $sel1>" . _YES . " &nbsp;&nbsp;" . "<input type=\"radio\" name=\"automated\" value=\"0\" $sel2>" . _NO . "&nbsp;&nbsp;&nbsp; " . "<br><br>" . _YESPROGRAMSTORY . " (" . _NOWIS . ": " . viewtime( time(), 2 ) . "):<br><br>";
		echo "" . _DAY . ": <select name=\"day\">";
		for ( $i = 1; $i <= 31; $i++ )
		{
			echo "<option value=\"" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "\"";
			if ( $i == $day )
			{
				echo " selected";
			}
			echo ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
		}
		echo "</select>";
		echo "" . _UMONTH . ": <select name=\"month\">";
		for ( $i = 1; $i <= 12; $i++ )
		{
			echo "<option value=\"" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "\"";
			if ( $i == $month )
			{
				echo " selected";
			}
			echo ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
		}
		echo "</select>";
		echo "" . _YEAR . ": <select name=\"year\">";
		$z = date( "Y", time() + $hourdiff * 60 );
		for ( $i = $z; $i <= $z + 4; $i++ )
		{
			echo "<option value=\"$i\"";
			if ( $i == $year )
			{
				echo " selected";
			}
			echo ">$i</option>\n";
		}
		echo "</select>";
		echo " " . _HOUR . ": <select name=\"hour\">";
		for ( $i = 0; $i < 24; $i++ )
		{
			echo "<option value=\"" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "\"";
			if ( $i == $hour )
			{
				echo " selected";
			}
			echo ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
		}
		echo "</select>";
		echo ": <select name=\"min\">";
		for ( $i = 0; $i < 60; $i++ )
		{
			echo "<option value=\"" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "\"";
			if ( $i == $min )
			{
				echo " selected";
			}
			echo ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
		}
		echo "</select>";
		echo ": 00<br><br>" . "<select name=\"op\">" . "<option value=\"DeleteStory\">" . _DELETESTORY . "</option>" . "<option value=\"PreviewAgain\" selected>" . _PREVIEWSTORY . "</option>" . "<option value=\"PostStory\">" . _POSTSTORY . "</option>" . "</select>" . "<input type=\"submit\" value=\"" . _OK . "\">";
		CloseTable();
		echo "<br>";
		echo "</form>";
		include ( '../footer.php' );
	}

	/**
	 * postStory()
	 * 
	 * @param mixed $sender_ip
	 * @param mixed $delpic
	 * @param mixed $images
	 * @param mixed $imgtext
	 * @param mixed $automated
	 * @param mixed $year
	 * @param mixed $day
	 * @param mixed $month
	 * @param mixed $hour
	 * @param mixed $min
	 * @param mixed $sid
	 * @param mixed $author
	 * @param mixed $subject
	 * @param mixed $hometext
	 * @param mixed $bodytext
	 * @param mixed $notes
	 * @param mixed $catid
	 * @param mixed $topicid
	 * @param mixed $ihome
	 * @param mixed $alanguage
	 * @param mixed $acomm
	 * @param mixed $source
	 * @return
	 */
	function postStory( $sender_ip, $delpic, $images, $imgtext, $automated, $year, $day, $month, $hour, $min, $sid, $author, $subject, $hometext, $bodytext, $notes, $catid, $topicid, $ihome, $alanguage, $acomm, $source )
	{
		global $editor, $hourdiff, $adminfile, $aid, $max_size, $width, $height, $path, $temp_path, $prefix, $db;
		$sid = intval( $sid );
		if ( $db->sql_numrows($db->sql_query("SELECT * FROM " . $prefix . "_stories_temp where sid=$sid")) != 1 )
		{
			Header( "Location: " . $adminfile . ".php" );
			exit;
		}
		$images = @uploadimg( $images, $delpic, 1, $sizenewsarticleimg, $temp_path );

		if ( $images != "" )
		{
			$oldfile = "../$temp_path/$images";
			$newfile = "../$path/$images";
			if ( ! @rename($oldfile, $newfile) )
			{
				info_exit( "<br><br>" . _COPYIMAGESFAILED . "<br>" );
			}
			if ( file_exists("../" . $temp_path . "/small_" . $images) )
			{
				$oldfile2 = "../" . $temp_path . "/small_" . $images;
				$newfile2 = "../" . $path . "/small_" . $images;
				@rename( $oldfile2, $newfile2 );
			}
		}
		if ( ! file_exists("" . INCLUDE_PATH . "" . $path . "/" . $images . "") )
		{
			$images = "";
		}
		if ( $images == "" )
		{
			$imgtext = "";
		}
		$subject = nv_htmlspecialchars( strip_tags(stripslashes(trim($subject))) );
		$hometext = cheonguoc( nl2brStrict(stripslashes(FixQuotes($hometext))) );
		$bodytext = cheonguoc( stripslashes(FixQuotes($bodytext)) );
		$notes = cheonguoc( stripslashes(FixQuotes($notes)) );
		if ( ! $editor )
		{
			$bodytext = cheonguoc( nl2brStrict($bodytext) );
			$notes = cheonguoc( nl2brStrict($notes) );
		}
		$imgtext = nv_htmlspecialchars( strip_tags(stripslashes(trim($imgtext))) );
		$source = nv_htmlspecialchars( strip_tags(stripslashes(trim($source))) );
		if ( $automated == 1 )
		{
			$hour = str_pad( intval($hour), 2, "0", STR_PAD_LEFT );
			$min = str_pad( intval($min), 2, "0", STR_PAD_LEFT );
			$day = str_pad( intval($day), 2, "0", STR_PAD_LEFT );
			$month = str_pad( intval($month), 2, "0", STR_PAD_LEFT );
			$year = intval( $year );
			$acttime = mktime( $hour, $min, 0, $month, $day, $year ) - $hourdiff * 60;
			$date = date( "Y-m-d H:i:s", $acttime );
			$result = $db->sql_query( "INSERT INTO " . $prefix . "_stories_auto VALUES (NULL, '$catid', '$author', '$subject', '$date', '$hometext', '$bodytext', '$images', '$notes', '$ihome', '$alanguage', '$acomm', '$imgtext', '$source', '$topicid')" );
			if ( ! $result )
			{
				return;
			}
			liststauto();
		}
		else
		{
			$result = $db->sql_query( "INSERT INTO " . $prefix . "_stories VALUES (NULL, '$catid', '$author', '$subject', now(), '$hometext', '$bodytext', '$images', '0', '0', '$notes', '$ihome', '$alanguage', '$acomm', '$imgtext', '$source', '$topicid', '0')" );
			if ( ! $result )
			{
				return;
			}
		}
		$db->sql_query( "DELETE FROM " . $prefix . "_stories_temp WHERE sid='$sid'" );
		info_exit( "<br><br><center><b>" . _NEWSSAVED . "</b></center><br><br><META HTTP-EQUIV=\"refresh\" content=\"2;URL=" . $adminfile . ".php?op=adminnews\">" );
	}

	/**
	 * editStory()
	 * 
	 * @param mixed $sid
	 * @return
	 */
	function editStory( $sid )
	{
		global $editor, $hourdiff, $adminfile, $max_size, $width, $height, $newsarticleimg, $sizenewsarticleimg, $path, $bgcolor1, $bgcolor2, $prefix, $user_prefix, $db, $multilingual;
		$sid = intval( $sid );
		$result1 = $db->sql_query( "SELECT * FROM " . $prefix . "_stories where sid='$sid'" );
		if ( $db->sql_numrows($result1) != 1 )
		{
			Header( "Location: " . $adminfile . ".php?op=newsadminhome" );
			exit;
		}
		include ( '../header.php' );

		newstopbanner();
		$row = $db->sql_fetchrow( $result1 );
		$catid = intval( $row['catid'] );

		$author = nv_htmlspecialchars( strip_tags(stripslashes(trim($row['aid']))) );
		$topicid = intval( $row['topicid'] );
		$images = $row['images'];
		$ihome = intval( $row['ihome'] );
		$alanguage = $row['alanguage'];
		$acomm = intval( $row['acomm'] );

		$subject = nv_htmlspecialchars( strip_tags(stripslashes(trim($row['title']))) );
		$hometext = cheonguoc( stripslashes($row['hometext']) );
		$bodytext = cheonguoc( stripslashes($row['bodytext']) );

		$imgtext = nv_htmlspecialchars( strip_tags(stripslashes(trim($row['imgtext']))) );
		$notes = cheonguoc( stripslashes($row['notes']) );

		$source = nv_htmlspecialchars( strip_tags(stripslashes(trim($row['source']))) );
		if ( $notes != "" )
		{
			$story_notes = "<hr>$notes";
		}
		else
		{
			$story_notes = "";
		}
		$story_pic = "";
		if ( $images != "" )
		{
			$size2 = @getimagesize( "../$path/$images" );
			$widthimg = $size2[0];
			if ( $size2[0] > $sizenewsarticleimg )
			{
				$widthimg = $sizenewsarticleimg;
			}
			$story_pic = "<table border=\"0\" width=\"$widthimg\" cellpadding=\"0\" cellspacing=\"3\" align=\"$newsarticleimg\">\n<tr>\n<td>\n<img border=\"0\" src=\"../$path/$images\" width=\"$widthimg\"></td>\n</tr>\n<tr>\n<td align=\"center\"><i><b>$imgtext</b></i></td>\n</tr>\n</table>\n";
		}
		OpenTable();
		echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"10\" width=\"100%\">
		<tr><td><font class=storytitle>$subject</font></td></tr>
		<tr><td>$story_pic$hometext<br><br>$bodytext</td></tr>
		<tr><td>$story_notes</td></tr>
		</table>";
		CloseTable();
		echo "<br>";
		OpenTable();
		echo "<form enctype=\"multipart/form-data\" action=\"" . $adminfile . ".php\" method=\"post\">" . "<b>" . _SENDERNAME . "</b> ";
		list( $u_id ) = $db->sql_fetchrow( $db->sql_query("select user_id from " . $user_prefix . "_users where username='$author'") );
		if ( $u_id > 1 )
		{
			echo "<font class=\"content\">[ <a href='" . $adminfile . ".php?op=modifyUser&chng_uid=2'>" . _USERPROFILE . "</a> ]</font>";
		}
		echo "<br><input type=\"text\" NAME=\"author\" size=\"25\" value=\"$author\">&nbsp; " . "<b>" . _SOURCE . "</b> " . "<input type=\"text\" name=\"source\" size=\"44\" value=\"$source\"><br><br>";
		echo "<b>" . _TITLE . "</b><br>" . "<input type=\"text\" name=\"subject\" size=\"85\" value=\"$subject\"><br><br>";
		$cat = $catid;
		SelectCategory( $cat );
		echo "<br><br>";
		SelectTopic( $topicid );
		echo "<br>";
		puthome( $ihome, $acomm );
		if ( $multilingual == 1 )
		{
			echo "<br><b>" . _LANGUAGE . ":</b>" . "<select name=\"alanguage\">";
			echo "<option value=\"\" $sellang>" . _ALL . "</option>";
			echo select_language( $alanguage );
			echo "</select>";
		}
		else
		{
			echo "<input type=\"hidden\" name=\"alanguage\" value=\"$alanguage\">";
		}
		$hometext = str_replace( "<br />", "\r\n", $hometext );
		if ( ! $editor )
		{
			$bodytext = str_replace( "<br />", "\r\n", $bodytext );
			$notes = str_replace( "<br />", "\r\n", $notes );
		}
		echo "<br><br><b>" . _STORYTEXT . "</b><br>" . "<textarea wrap=\"virtual\" cols=\"85\" rows=\"7\" name=\"hometext\">$hometext</textarea><br><br>" . "<b>" . _EXTENDEDTEXT . "</b><br>";
		if ( $editor == 1 )
		{
			aleditor( "bodytext", $bodytext, 500, 250 );
		}
		else
		{
			echo "<textarea wrap=\"virtual\" cols=\"85\" rows=\"7\" name=\"bodytext\">$bodytext</textarea>";
		}
		echo "<br><br><b>" . _NOTES . "</b><br>";




		echo "<textarea wrap=\"virtual\" cols=\"85\" rows=\"3\" name=\"notes\">$notes</textarea>";


		echo "<br><br>";
		if ( $images != "" )
		{
			echo "<b>" . _DELSTPIC . ":</b> ";
			echo "<input type=\"checkbox\" name=\"delpic\" value=\"yes\"> <a href=\"../$path/$images\" target=\"_blank\">$images</a><br><br>";
		}

		echo "<a target=\"ManagerPicNews2\" href=" . $adminfile . ".php?op=ManagerPicNews2><b>" . _IMGNEWS1 . ":</a></b> <input name=\"images\" value=\"$images\"> (" . _IMGNEWS0 . " " . _IMGNEWS00 . ".)<br>";
		echo "<b>" . _IMGNEWS2 . ":</b> " . "<input name=\"userfile\" type=\"file\"><br><br>";
		echo "" . _IMGTEXT . ": <input type=\"text\" name=\"imgtext\" value=\"$imgtext\" size=\"100\"><br><br>";
		echo "<input type=\"hidden\" NAME=\"sid\" value=\"$sid\">" . "<input type=\"hidden\" name=\"op\" value=\"ChangeStory\">" . "<input type=\"submit\" value=\"" . _SAVECHANGES . "\">" . "</form>";
		CloseTable();
		include ( '../footer.php' );

	}

	/**
	 * removeStory()
	 * 
	 * @param mixed $sid
	 * @param integer $ok
	 * @return
	 */
	function removeStory( $sid, $ok = 0 )
	{
		global $adminfile, $prefix, $db, $path;
		$sid = intval( $sid );
		if ( $db->sql_numrows($db->sql_query("SELECT * FROM " . $prefix . "_stories where sid=$sid")) != 1 )
		{
			Header( "Location: " . $adminfile . ".php" );
			exit;
		}
		if ( $ok )
		{
			list( $images ) = $db->sql_fetchrow( $db->sql_query("SELECT images FROM " . $prefix . "_stories where sid=$sid") );
			$images = @uploadimg( $images, "yes", 0, 0, $path );
			$result2 = $db->sql_query( "SELECT imglink FROM " . $prefix . "_stories_images where sid='$sid'" );
			while ( $row2 = $db->sql_fetchrow($result2) )
			{
				$imglink = $row2['imglink'];
				$apath = "../" . $path . "";
				$filel = array_reverse( explode("/", $imglink) );
				if ( file_exists("" . $apath . "/" . $filel[0] . "") )
				{
					$delf = "" . $apath . "/" . $filel[0] . "";
					@unlink( $delf );
				}
			}
			list( $catid3 ) = $db->sql_fetchrow( $db->sql_query("SELECT catid FROM " . $prefix . "_stories_cat where storieshome='$sid'") );
			if ( $catid3 )
			{
				$db->sql_query( "update " . $prefix . "_stories_cat set storieshome='0' where catid='$catid3'" );
			}
			$db->sql_query( "DELETE FROM " . $prefix . "_stories where sid=$sid" );
			$db->sql_query( "DELETE FROM " . $prefix . "_stories_comments where sid=$sid" );
			$db->sql_query( "DELETE FROM " . $prefix . "_stories_images where sid=$sid" );
			ncatlist();
			info_exit( "<center><b>" . _DELEDARTICLE . "</b></center><META HTTP-EQUIV=\"refresh\" content=\"2;URL=" . $adminfile . ".php?op=newsadminhome\">" );
		}
		else
		{
			info_exit( "<center>" . _REMOVESTORY . ": $sid " . _ANDCOMMENTS . "<br><br>[ <a href=\"" . $adminfile . ".php?op=newsadminhome\">" . _NO . "</a> | <a href=\"" . $adminfile . ".php?op=RemoveStory&amp;sid=$sid&amp;ok=1\">" . _YES . "</a> ]</center><br><br>" );
		}
	}

	/**
	 * changeStory()
	 * 
	 * @param mixed $sid
	 * @param mixed $author
	 * @param mixed $subject
	 * @param mixed $hometext
	 * @param mixed $bodytext
	 * @param mixed $images
	 * @param mixed $imgtext
	 * @param mixed $delpic
	 * @param mixed $notes
	 * @param mixed $catid
	 * @param mixed $topicid
	 * @param mixed $ihome
	 * @param mixed $alanguage
	 * @param mixed $acomm
	 * @param mixed $source
	 * @return
	 */
	function changeStory( $sid, $author, $subject, $hometext, $bodytext, $images, $imgtext, $delpic, $notes, $catid, $topicid, $ihome, $alanguage, $acomm, $source )
	{
		global $editor, $adminfile, $path, $prefix, $db, $sizenewsarticleimg, $sizeimgskqa;
		$sid = intval( $sid );
		if ( $db->sql_numrows($db->sql_query("SELECT * FROM " . $prefix . "_stories where sid='$sid'")) != 1 )
		{
			Header( "Location: " . $adminfile . ".php" );
			exit;
		}
		list( $xcatid, $xnewsst, $ximages ) = $db->sql_fetchrow( $db->sql_query("SELECT catid, newsst, images FROM " . $prefix . "_stories where sid='$sid'") );
		$images = @uploadimg( $images, $delpic, 1, $sizenewsarticleimg, $path );
		if ( $images == "" ) $imgtext = "";
		if ( $xnewsst == '1' and $ximages != $images and $images != "" and file_exists("" . INCLUDE_PATH . "" . $path . "/" . $images . "") )
		{
			@unlink( "" . INCLUDE_PATH . "" . $path . "/nst_" . $ximages . "" );
			$size2 = @getimagesize( "" . INCLUDE_PATH . "" . $path . "/" . $images . "" );
			$widthimg = $size2[0];
			$f_name = explode( ".", $images );
			$f_num = sizeof( $f_name ) - 1;
			$f_extension = strtolower( $f_name[$f_num] );
			if ( ($widthimg > $sizeimgskqa) and ($f_extension == "jpg") and (extension_loaded("gd")) )
			{
				$outpath = "" . INCLUDE_PATH . "" . $path . "";
				$inpath = "" . INCLUDE_PATH . "" . $path . "";
				$outimg = "" . $images . "";
				$inimg = "nst_" . $images . "";
				$insize = "" . $sizeimgskqa . "";
				thumbs( $outpath, $inpath, $outimg, $inimg, $insize );
			}
		}
		list( $storieshome ) = $db->sql_fetchrow( $db->sql_query("SELECT storieshome FROM " . $prefix . "_stories_cat WHERE catid='$xcatid'") );
		if ( $storieshome == $sid )
		{
			$db->sql_query( "update " . $prefix . "_stories_cat set storieshome='0' where catid='$xcatid'" );
		}

		$subject = nv_htmlspecialchars( strip_tags(stripslashes(trim($subject))) );
		$hometext = cheonguoc( nl2brStrict(stripslashes(FixQuotes($hometext))) );
		$bodytext = cheonguoc( stripslashes(FixQuotes($bodytext)) );
		$notes = cheonguoc( stripslashes(FixQuotes($notes)) );
		$imgtext = nv_htmlspecialchars( strip_tags(stripslashes(trim($imgtext))) );
		$source = nv_htmlspecialchars( strip_tags(stripslashes(trim($source))) );




		if ( ! $editor )
		{
			$bodytext = cheonguoc( nl2brStrict($bodytext) );
			$notes = cheonguoc( nl2brStrict($notes) );
		}


		$db->sql_query( "UPDATE " . $prefix . "_stories SET catid='$catid', aid='$author', topicid='$topicid', title='$subject', hometext='$hometext', bodytext='$bodytext', images='$images', notes='$notes', ihome='$ihome', alanguage='$alanguage', acomm='$acomm', imgtext='$imgtext', source='$source' where sid='$sid'" );
		ncatlist();
		info_exit( "<br><br><center><b>" . _NEWSSAVED . "</b></center><br><br><META HTTP-EQUIV=\"refresh\" content=\"2;URL=" . $adminfile . ".php?op=newsadminhome\">" );
	}

	/**
	 * xaddnews()
	 * 
	 * @return
	 */
	function xaddnews()
	{
		global $editor, $adminfile, $language, $multilingual, $nukeurl, $path;
		OpenTable();
		echo "<center><font class=\"option\"><b>" . _ADDARTICLE . "</b></font></center><br><br>" . "<form enctype=\"multipart/form-data\" action=\"" . $adminfile . ".php\" method=\"post\">" . "<b>" . _SENDERNAME . "</b>" . "<br><input type=\"text\" name=\"author\" size=\"25\">&nbsp; " . "<b>" . _SOURCE . "</b> " . "<input type=\"text\" name=\"source\" size=\"44\"><br><br>" . "<b>" . _TITLE . "</b><sup>(*)</sup><br>" . "<input type=\"text\" name=\"subject\" size=\"85\"><br><br>";
		SelectCategory( 0 );
		echo "<br><br>";
		SelectTopic( 0 );
		echo "<br>";
		puthome( 1, 0 );
		if ( $multilingual == 1 )
		{
			echo "<b>" . _LANGUAGE . ": </b>" . "<select name=\"alanguage\">";
			echo "<option value=\"\">" . _ALL . "</option>";
			echo select_language( $language );
			echo "</select>";
		}
		else
		{
			echo "<input type=\"hidden\" name=\"alanguage\" value=\"$language\">";
		}
		echo "<br><br><b>" . _STORYTEXT . "</b><sup>(*)</sup><br>" . "<textarea wrap=\"virtual\" cols=\"85\" rows=\"7\" name=\"hometext\">$hometext</textarea><br><br>" . "<b>" . _EXTENDEDTEXT . "</b><sup>(*)</sup><br>";
		if ( $editor == 1 )
		{
			aleditor( "bodytext", "", 500, 250 );
		}
		else
		{
			echo "<textarea wrap=\"virtual\" cols=\"85\" rows=\"7\" name=\"bodytext\"></textarea>";
		}
		echo "<br><br><b>" . _NOTES . "</b><br>";




		echo "<textarea wrap=\"virtual\" cols=\"85\" rows=\"3\" name=\"notes\"></textarea>";


		echo "<br><br>";

		$images = "";
		echo "<a href=" . $adminfile . ".php?op=ManagerPicNews2 target=\"ManagerPicNews2\"><b>" . _IMGNEWS1 . ":</b></a> <input name=\"images\" value=\"$images\"> (" . _IMGNEWS . ".)<br>";

		echo "<b>" . _IMGNEWS2 . ":</b> " . "<input name=\"userfile\" type=\"file\"><br><br>" . "<b>" . _IMGTEXT . "</b>: <input type=\"text\" name=\"imgtext\" value=\"\" size=\"44\"><br><br>" . "<b>" . _PROGRAMSTORY . "</b>&nbsp;&nbsp;" . "<input type=\"radio\" name=\"automated\" value=\"1\">" . _YES . " &nbsp;&nbsp;" . "<input type=\"radio\" name=\"automated\" value=\"0\" checked>" . _NO . "&nbsp;&nbsp;&nbsp; " . "<br><br>" . _YESPROGRAMSTORY . " (" . _NOWIS . ": " . viewtime( time(), 2 ) . "):<br><br>";
		echo "" . _DAY . ": <select name=\"day\">";
		for ( $i = 1; $i <= 31; $i++ )
		{
			echo "<option value=\"" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "\"";
			if ( $i == date("d", time() + $hourdiff * 60) )
			{
				echo " selected";
			}
			echo ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
		}
		echo "</select>";
		echo "" . _UMONTH . ": <select name=\"month\">";
		for ( $i = 1; $i <= 12; $i++ )
		{
			echo "<option value=\"" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "\"";
			if ( $i == date("m", time() + $hourdiff * 60) )
			{
				echo " selected";
			}
			echo ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
		}
		echo "</select>";
		echo "" . _YEAR . ": <select name=\"year\">";
		$z = date( "Y", time() + $hourdiff * 60 );
		for ( $i = $z; $i <= $z + 4; $i++ )
		{
			echo "<option value=\"$i\"";
			if ( $i == $z )
			{
				echo " selected";
			}
			echo ">$i</option>\n";
		}
		echo "</select>";
		echo " " . _HOUR . ": <select name=\"hour\">";
		for ( $i = 0; $i < 24; $i++ )
		{
			echo "<option value=\"" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "\"";
			if ( $i == date("H", time() + $hourdiff * 60) )
			{
				echo " selected";
			}
			echo ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
		}
		echo "</select>";
		echo ": <select name=\"min\">";
		for ( $i = 0; $i < 60; $i++ )
		{
			echo "<option value=\"" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "\"";
			if ( $i == date("i", time() + $hourdiff * 60) )
			{
				echo " selected";
			}
			echo ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
		}
		echo "</select>";
		echo ": 00<br><br>" . "<select name=\"op\">" . "<option value=\"PreviewAdminStory\" selected>" . _PREVIEWSTORY . "</option>" . "<option value=\"PostAdminStory\">" . _POSTSTORY . "</option>" . "</select>" . "<input type=\"submit\" value=\"" . _OK . "\">" . "</form>";
		echo "<sup>(*)</sup>: " . _YEUCAU . "";
		CloseTable();
		echo "<br>";
	}

	/**
	 * adminnews()
	 * 
	 * @return
	 */
	function adminnews()
	{
		global $prefix, $db;
		include ( "../header.php" );

		newstopbanner();
		xaddnews();
		include ( "../footer.php" );
	}

	/**
	 * adminStory()
	 * 
	 * @return
	 */
	function adminStory()
	{
		include ( '../header.php' );

		newstopbanner();
		xaddnews();
		include ( '../footer.php' );
	}

	/**
	 * previewAdminStory()
	 * 
	 * @param mixed $automated
	 * @param mixed $year
	 * @param mixed $day
	 * @param mixed $month
	 * @param mixed $hour
	 * @param mixed $min
	 * @param mixed $subject
	 * @param mixed $hometext
	 * @param mixed $bodytext
	 * @param mixed $images
	 * @param mixed $imgtext
	 * @param mixed $delpic
	 * @param mixed $catid
	 * @param mixed $topicid
	 * @param mixed $ihome
	 * @param mixed $alanguage
	 * @param mixed $acomm
	 * @param mixed $notes
	 * @param mixed $author
	 * @param mixed $source
	 * @return
	 */
	function previewAdminStory( $automated, $year, $day, $month, $hour, $min, $subject, $hometext, $bodytext, $images, $imgtext, $delpic, $catid, $topicid, $ihome, $alanguage, $acomm, $notes, $author, $source )
	{
		global $editor, $adminfile, $max_size, $width, $height, $newsarticleimg, $sizenewsarticleimg, $path, $bgcolor1, $bgcolor2, $prefix, $db, $alanguage, $multilingual;
		if ( ((! $subject) or ($subject == "")) and ((! $hometext) or ($hometext == "")) )
		{
			Header( "Location: " . $adminfile . ".php?op=adminnews" );
			exit;
		}
		$images = @uploadimg( $images, $delpic, 1, $sizenewsarticleimg, $path );
		if ( $images == "" ) $imgtext = "";
		include ( '../header.php' );

		newstopbanner();

		$subject = nv_htmlspecialchars( strip_tags(stripslashes(trim($subject))) );
		$author = nv_htmlspecialchars( strip_tags(stripslashes(trim($author))) );

		$source = nv_htmlspecialchars( strip_tags(stripslashes(trim($source))) );

		$hometext = cheonguoc( stripslashes(FixQuotes($hometext)) );
		$bodytext = cheonguoc( stripslashes(FixQuotes($bodytext)) );

		$imgtext = nv_htmlspecialchars( strip_tags(stripslashes(trim($imgtext))) );
		$notes = cheonguoc( stripslashes($notes) );
		if ( ! $editor )
		{
			$bodytext = cheonguoc( nl2brStrict($bodytext) );
			$notes = cheonguoc( nl2brStrict($notes) );
		}
		$story_pic = "";
		if ( $images != "" )
		{
			$size2 = @getimagesize( "../$path/$images" );
			$widthimg = $size2[0];
			if ( $size2[0] > $sizenewsarticleimg )
			{
				$widthimg = $sizenewsarticleimg;
			}
			$story_pic = "<table border=\"0\" width=\"$widthimg\" cellpadding=\"0\" cellspacing=\"3\" align=\"$newsarticleimg\">\n<tr>\n<td>\n<img border=\"0\" src=\"../$path/$images\" width=\"$widthimg\"></td>\n</tr>\n<tr>\n<td align=\"center\"><i><b>$imgtext</b></i></td>\n</tr>\n</table>\n";
		}
		$story_notes = "";
		if ( $notes != "" )
		{
			$story_notes = "<hr>$notes";
		}
		OpenTable();
		echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"10\" width=\"100%\">
	<tr><td><font class=storytitle>$subject</font></td></tr>
	<tr><td>$story_pic$hometext<br><br>$bodytext</td></tr>
	<tr><td>$story_notes</td></tr>
	</table>";
		CloseTable();
		echo "<br>";
		OpenTable();
		$hometext = str_replace( "<br />", "\r\n", $hometext );
		if ( ! $editor )
		{
			$bodytext = str_replace( "<br />", "\r\n", $bodytext );
			$nottext = str_replace( "<br />", "\r\n", $nottext );
		}
		echo "<center><font class=\"option\"><b>" . _PREVIEWSTORY . "</b></font></center><br><br>" . "<form enctype=\"multipart/form-data\" action=\"" . $adminfile . ".php\" method=\"post\">" . "<b>" . _SENDERNAME . "</b>" . "<br><input type=\"text\" name=\"author\" size=\"25\" value=\"$author\">&nbsp; " . "<b>" . _SOURCE . "</b> " . "<input type=\"text\" name=\"source\" size=\"44\" value=\"$source\"><br><br>" . "<b>" . _TITLE . "</b><br>" . "<input type=\"text\" name=\"subject\" size=\"85\" value=\"$subject\"><br><br>";
		SelectCategory( intval($catid) );
		echo "<br><br>";
		SelectTopic( intval($topicid) );
		echo "<br>";
		puthome( $ihome, $acomm );
		if ( $multilingual == 1 )
		{
			echo "<br><b>" . _LANGUAGE . ": </b>" . "<select name=\"alanguage\">";
			echo "<option name=\"alanguage\" value=\"\">" . _ALL . "</option>";
			echo select_language( $alanguage );
			echo "</select>";
		}
		else
		{
			echo "<input type=\"hidden\" name=\"alanguage\" value=\"$alanguage\">";
		}
		echo "<br><br><b>" . _STORYTEXT . "</b><br>" . "<textarea wrap=\"virtual\" cols=\"85\" rows=\"7\" name=\"hometext\">$hometext</textarea><br><br>" . "<b>" . _EXTENDEDTEXT . "</b><br>";
		if ( $editor == 1 )
		{
			aleditor( "bodytext", $bodytext, 500, 250 );
		}
		else
		{
			echo "<textarea wrap=\"virtual\" cols=\"85\" rows=\"7\" name=\"bodytext\">$bodytext</textarea>";
		}
		echo "<br><br><b>" . _NOTES . "</b><br>";
		if ( $editor == 1 )
		{
			aleditor( "notes", $notes, 500, 250 );
		}
		else
		{
			echo "<textarea wrap=\"virtual\" cols=\"85\" rows=\"3\" name=\"notes\">$notes</textarea>";
		}
		echo "<br><br>";
		if ( $images != "" )
		{
			echo "<b>" . _DELSTPIC . ":</b> ";
			echo "<input type=\"checkbox\" name=\"delpic\" value=\"yes\"> <a href=\"../$path/$images\" target=\"_blank\">$images</a><br><br>";
		}
		echo "<input type=\"hidden\" name=\"images\" value=\"$images\">";
		echo "<b>" . _STPIC . ":</b> " . "<input name=\"userfile\" type=\"file\"><br><br>";
		echo "" . _IMGTEXT . ": <input type=\"text\" name=\"imgtext\" value=\"$imgtext\" size=\"44\"><br><br>";
		if ( $automated == 1 )
		{
			$sel1 = "checked";
			$sel2 = "";
		}
		else
		{
			$sel1 = "";
			$sel2 = "checked";
		}
		echo "<br><b>" . _PROGRAMSTORY . "</b>&nbsp;&nbsp;" . "<input type=\"radio\" name=\"automated\" value=\"1\" $sel1>" . _YES . " &nbsp;&nbsp;" . "<input type=\"radio\" name=\"automated\" value=\"0\" $sel2>" . _NO . "&nbsp;&nbsp;&nbsp; " . "<br><br>" . _YESPROGRAMSTORY . " (" . _NOWIS . ": " . viewtime( time(), 2 ) . "):<br><br>";
		echo "" . _DAY . ": <select name=\"day\">";
		for ( $i = 1; $i <= 31; $i++ )
		{
			echo "<option value=\"" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "\"";
			if ( $i == $day )
			{
				echo " selected";
			}
			echo ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
		}
		echo "</select>";
		echo "" . _UMONTH . ": <select name=\"month\">";
		for ( $i = 1; $i <= 12; $i++ )
		{
			echo "<option value=\"" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "\"";
			if ( $i == $month )
			{
				echo " selected";
			}
			echo ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
		}
		echo "</select>";
		echo "" . _YEAR . ": <select name=\"year\">";
		$z = date( "Y", time() + $hourdiff * 60 );
		for ( $i = $z; $i <= $z + 4; $i++ )
		{
			echo "<option value=\"$i\"";
			if ( $i == $year )
			{
				echo " selected";
			}
			echo ">$i</option>\n";
		}
		echo "</select>";
		echo " " . _HOUR . ": <select name=\"hour\">";
		for ( $i = 0; $i < 24; $i++ )
		{
			echo "<option value=\"" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "\"";
			if ( $i == $hour )
			{
				echo " selected";
			}
			echo ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
		}
		echo "</select>";
		echo ": <select name=\"min\">";
		for ( $i = 0; $i < 60; $i++ )
		{
			echo "<option value=\"" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "\"";
			if ( $i == $min )
			{
				echo " selected";
			}
			echo ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
		}
		echo "</select>";
		echo ": 00<br><br>" . "<select name=\"op\">" . "<option value=\"PreviewAdminStory\" selected>" . _PREVIEWSTORY . "</option>" . "<option value=\"PostAdminStory\">" . _POSTSTORY . "</option>" . "</select>" . "<input type=\"submit\" value=\"" . _OK . "\"></form>";
		CloseTable();
		echo "<br>";
		include ( '../footer.php' );
	}

	/**
	 * postAdminStory()
	 * 
	 * @param mixed $automated
	 * @param mixed $year
	 * @param mixed $day
	 * @param mixed $month
	 * @param mixed $hour
	 * @param mixed $min
	 * @param mixed $subject
	 * @param mixed $hometext
	 * @param mixed $bodytext
	 * @param mixed $images
	 * @param mixed $imgtext
	 * @param mixed $delpic
	 * @param mixed $catid
	 * @param mixed $topicid
	 * @param mixed $ihome
	 * @param mixed $alanguage
	 * @param mixed $acomm
	 * @param mixed $author
	 * @param mixed $source
	 * @param mixed $notes
	 * @return
	 */
	function postAdminStory( $automated, $year, $day, $month, $hour, $min, $subject, $hometext, $bodytext, $images, $imgtext, $delpic, $catid, $topicid, $ihome, $alanguage, $acomm, $author, $source, $notes )
	{
		global $editor, $hourdiff, $adminfile, $aid, $max_size, $width, $height, $path, $prefix, $db, $sizenewsarticleimg;

		if ( (! $subject) or ($subject == "") or (! $hometext) or ($hometext == "") )
		{
			Header( "Location: " . $adminfile . ".php?op=adminnews" );
			exit;
		}
		$images = @uploadimg( $images, $delpic, 1, $sizenewsarticleimg, $path );
		if ( ! file_exists("" . INCLUDE_PATH . "" . $path . "/" . $images . "") )
		{
			$images = "";
		}
		if ( $images == "" ) $imgtext = "";
		$subject = nv_htmlspecialchars( strip_tags(stripslashes(trim($subject))) );
		$hometext = cheonguoc( nl2brStrict(stripslashes(FixQuotes($hometext))) );
		$bodytext = cheonguoc( stripslashes(FixQuotes($bodytext)) );
		$notes = cheonguoc( stripslashes(FixQuotes($notes)) );
		if ( ! $editor )
		{
			$bodytext = cheonguoc( nl2brStrict($bodytext) );
			$notes = cheonguoc( nl2brStrict($notes) );
		}
		$imgtext = nv_htmlspecialchars( strip_tags(stripslashes(trim($imgtext))) );
		$source = nv_htmlspecialchars( strip_tags(stripslashes(trim($source))) );
		if ( $automated == 1 )
		{
			$hour = str_pad( intval($hour), 2, "0", STR_PAD_LEFT );
			$min = str_pad( intval($min), 2, "0", STR_PAD_LEFT );
			$day = str_pad( intval($day), 2, "0", STR_PAD_LEFT );
			$month = str_pad( intval($month), 2, "0", STR_PAD_LEFT );
			$year = intval( $year );
			$acttime = mktime( $hour, $min, 0, $month, $day, $year ) - $hourdiff * 60;
			$date = date( "Y-m-d H:i:s", $acttime );
			$result = $db->sql_query( "INSERT INTO " . $prefix . "_stories_auto VALUES (NULL, '$catid', '$author', '$subject', '$date', '$hometext', '$bodytext', '$images', '$notes', '$ihome', '$alanguage', '$acomm', '$imgtext', '$source', '$topicid')" );
			if ( ! $result )
			{
				exit();
			}
			liststauto();
			info_exit( "<br><br><center><b>" . _NEWSSAVED . "</b></center><br><br><META HTTP-EQUIV=\"refresh\" content=\"2;URL=" . $adminfile . ".php?op=adminnews\">" );
		}
		else
		{
			$result = $db->sql_query( "INSERT INTO " . $prefix . "_stories VALUES (NULL, '$catid', '$author', '$subject', now(), '$hometext', '$bodytext', '$images', '0', '0', '$notes', '$ihome', '$alanguage', '$acomm', '$imgtext', '$source', '$topicid','0')" );
			if ( ! $result )
			{
				exit();
			}
			info_exit( "<br><br><center><b>" . _NEWSSAVED . "</b></center><br><br><META HTTP-EQUIV=\"refresh\" content=\"2;URL=" . $adminfile . ".php?op=adminnews\">" );
		}
	}

	/**
	 * subdelete()
	 * 
	 * @return
	 */
	function subdelete()
	{
		global $adminfile, $prefix, $db;
		$db->sql_query( "delete from " . $prefix . "_stories_temp" );
		info_exit( "<br><br><center><b>" . _DELEDALLARTICLE . "</b></center><br><br><META HTTP-EQUIV=\"refresh\" content=\"2;URL=" . $adminfile . ".php?op=adminnews\">" );
	}

	/**
	 * RemoveStoriesComment()
	 * 
	 * @param mixed $tid
	 * @param mixed $sid
	 * @param integer $ok
	 * @return
	 */
	function RemoveStoriesComment( $tid, $sid, $ok = 0 )
	{
		global $adminfile, $prefix, $db;
		if ( $ok == 1 )
		{
			$db->sql_query( "DELETE FROM " . $prefix . "_stories_comments WHERE tid='$tid'" );
			$db->sql_query( "UPDATE " . $prefix . "_stories SET comments=comments-1 WHERE sid='$sid'" );
			Header( "Location: ../modules.php?name=News&op=viewst&sid=$sid" );
		}
		else
		{
			info_exit( "<center>" . _SURETODELCOMMENTS . "<br><br>[ <a href=\"javascript:history.go(-1)\">" . _NO . "</a> | <a href=\"" . $adminfile . ".php?op=RemoveStoriesComment&tid=$tid&sid=$sid&ok=1\">" . _YES . "</a> ]</center>" );
		}
	}

	/**
	 * EditStoriesComment()
	 * 
	 * @param mixed $tid
	 * @return
	 */
	function EditStoriesComment( $tid )
	{
		global $adminfile, $prefix, $db;
		$result = $db->sql_query( "SELECT * FROM " . $prefix . "_stories_comments where tid='" . intval($tid) . "'" );
		if ( $db->sql_numrows($result) == 0 )
		{
			Header( "Location: " . $adminfile . ".php?op=newsadminhome" );
			exit;
		}
		$row = $db->sql_fetchrow( $result );
		$sid = intval( $row['sid'] );
		$sender_name = $row['name'];
		$sender_email = $row['email'];
		$sender_url = $row['url'];
		$sender_host = $row['host_name'];


		$com_subject = nv_htmlspecialchars( strip_tags(stripslashes(trim($row['subject']))) );
		$com_text = cheonguoc( stripslashes($row['comment']) );
		include ( '../header.php' );

		newstopbanner();
		OpenTable();
		echo "<center><font class=\"title\"><b>" . _EDITSTORIESCOMMENT . "</b></font></center>";
		CloseTable();
		echo "<br>";
		OpenTable();
		echo "<form enctype=\"multipart/form-data\" action=\"" . $adminfile . ".php\" method=\"post\">\n" . "<b>" . _SUBJECT . "</b><br><input type=\"text\" name=\"com_subject\" size=\"70\" value=\"$com_subject\"><br><br>\n" . "<b>" . _CONTENT . "</b><br><textarea wrap=\"virtual\" cols=\"70\" rows=\"7\" name=\"com_text\">$com_text</textarea><br><br>\n" . "<b>" . _SENDERNAME . "</b><br><input type=\"text\" name=\"sender_name\" size=\"20\" value=\"$sender_name\"><br><br>\n" . "<b>" . _EMAIL . "</b><br><input type=\"text\" name=\"sender_email\" size=\"20\" value=\"$sender_email\"><br><br>\n" . "<b>" . _URL . "</b><br><input type=\"text\" name=\"sender_url\" size=\"20\" value=\"$sender_url\"><br><br>\n" . "<input type=\"hidden\" name=\"sid\" value=\"$sid\">\n" . "<input type=\"hidden\" name=\"tid\" value=\"$tid\">\n" . "<select name=\"op\">\n" . "<option value=\"SaveEditStoriesComment\" selected>" . _SAVE . "</option>\n" . "<option value=\"RemoveStoriesComment\">" . _REMOVECOMMENTS . "</option>\n" .
			"</select>\n" . "<input type=\"submit\" value=\"" . _OK . "\">\n</form><br>\n" . "[ <a href='" . $adminfile . ".php?op=ConfigureBan&bad_ip=$sender_host'>" . _IPBANLIST . ": $sender_host</a> ]\n";
		CloseTable();
		include ( "../footer.php" );
	}

	/**
	 * SaveEditStoriesComment()
	 * 
	 * @param mixed $tid
	 * @param mixed $sid
	 * @param mixed $sender_name
	 * @param mixed $sender_email
	 * @param mixed $sender_url
	 * @param mixed $com_subject
	 * @param mixed $com_text
	 * @return
	 */
	function SaveEditStoriesComment( $tid, $sid, $sender_name, $sender_email, $sender_url, $com_subject, $com_text )
	{
		global $adminfile, $prefix, $db;
		$tid = intval( $tid );
		$sid = intval( $sid );
		$com_subject = nv_htmlspecialchars( strip_tags(stripslashes(trim($com_subject))) );

		$com_text = cheonguoc( nl2brStrict(stripslashes(FixQuotes($com_text, "nohtml"))) );
		if ( $db->sql_numrows($db->sql_query("SELECT * FROM " . $prefix . "_stories_comments where tid=$tid")) == 0 )
		{
			Header( "Location: " . $adminfile . ".php?op=newsadminhome" );
			exit;
		}
		$result2 = $db->sql_query( "UPDATE " . $prefix . "_stories_comments SET name='$sender_name', email='$sender_email', url='$sender_url', subject='$com_subject', comment='$com_text' WHERE tid='$tid'" );
		if ( ! $result2 )
		{
			return;
		}
		info_exit( "<br><br><center><b>" . _NEWSCOMSAVED . "</b></center><br><br><META HTTP-EQUIV=\"refresh\" content=\"2;URL=../modules.php?name=News&op=showcomm&sid=$sid\">" );
	}

	/**
	 * EditStoryAuto()
	 * 
	 * @param mixed $anid
	 * @return
	 */
	function EditStoryAuto( $anid )
	{
		global $editor, $hourdiff, $adminfile, $bgcolor1, $bgcolor2, $prefix, $user_prefix, $db, $multilingual, $path, $sizenewsarticleimg, $newsarticleimg;
		$anid = intval( $anid );
		if ( $db->sql_numrows($db->sql_query("SELECT * FROM " . $prefix . "_stories_auto WHERE anid='$anid'")) != 1 )
		{
			Header( "Location: " . $adminfile . ".php?op=newsadminhome" );
			exit;
		}
		include ( '../header.php' );

		newstopbanner();
		$result1 = $db->sql_query( "SELECT anid, catid, aid, UNIX_TIMESTAMP(time) as formatted, title, hometext, bodytext, images, notes, ihome, alanguage, acomm, imgtext, source, topicid FROM " . $prefix . "_stories_auto WHERE anid='$anid'" );
		$row = $db->sql_fetchrow( $result1 );
		$anid = intval( $row['anid'] );
		$catid = intval( $row['catid'] );

		$aid = nv_htmlspecialchars( strip_tags(stripslashes(trim($row['aid']))) );
		$time = intval( $row['formatted'] );

		$title = nv_htmlspecialchars( strip_tags(stripslashes(trim($row['title']))) );


		$hometext = cheonguoc( stripslashes($row['hometext']) );
		$bodytext = cheonguoc( stripslashes($row['bodytext']) );
		$images = $row['images'];

		$notes = cheonguoc( stripslashes($row['notes']) );
		$ihome = $row['ihome'];
		$alanguage = $row['alanguage'];
		$acomm = $row['acomm'];

		$imgtext = nv_htmlspecialchars( strip_tags(stripslashes(trim($row['imgtext']))) );

		$source = nv_htmlspecialchars( strip_tags(stripslashes(trim($row['source']))) );
		$topicid = intval( $row['topicid'] );
		$story_notes = "";
		if ( $notes != "" )
		{
			$story_notes = "<hr>$notes";
		}
		$story_pic = "";
		if ( $images != "" )
		{
			$size2 = getimagesize( "../$path/$images" );
			$widthimg = $size2[0];
			if ( $size2[0] > $sizenewsarticleimg )
			{
				$widthimg = $sizenewsarticleimg;
			}
			$story_pic = "<table border=\"0\" width=\"$widthimg\" cellpadding=\"0\" cellspacing=\"3\" align=\"$newsarticleimg\">\n<tr>\n<td>\n<img border=\"0\" src=\"../$path/$images\" width=\"$widthimg\"></td>\n</tr>\n<tr>\n<td align=\"center\"><i><b>$imgtext</b></i></td>\n</tr>\n</table>\n";
		}
		OpenTable();
		echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"10\" width=\"100%\">
	<tr><td><font class=storytitle>$title</font></td></tr>
	<tr><td>$story_pic$hometext<br><br>$bodytext</td></tr>
	<tr><td>$story_notes</td></tr>
	</table>";
		CloseTable();
		echo "<br>";
		OpenTable();
		echo "<font class=\"content\">" . "<form enctype=\"multipart/form-data\" action=\"" . $adminfile . ".php\" method=\"post\">" . "<b>" . _SENDERNAME . "</b> ";
		$sql2 = "select * from " . $user_prefix . "_users where username='$aid'";
		$result2 = $db->sql_query( $sql2 );
		$row2 = $db->sql_numrows( $result2 );
		if ( $row2 == 1 )
		{
			$row2a = $db->sql_fetchrow( $result2 );
			echo "<font class=\"content\">[ <a href='" . $adminfile . ".php?op=modifyUser&chng_uid=$row2a[user_id]'>" . _USERPROFILE . "</a> ]</font>";
		}
		echo "<br><input type=\"text\" NAME=\"author\" size=\"25\" value=\"$aid\">&nbsp; " . "<b>" . _SOURCE . "</b> " . "<input type=\"text\" name=\"source\" size=\"44\" value=\"$source\"><br><br>";
		echo "<b>" . _TITLE . "</b><br>" . "<input type=\"text\" name=\"title\" size=\"85\" value=\"$title\"><br><br>";
		SelectCategory( $catid );
		echo "<br><br>";
		SelectTopic( $topicid );
		echo "<br>";
		puthome( $ihome, $acomm );
		if ( $multilingual == 1 )
		{
			echo "<br><b>" . _LANGUAGE . ": </b>" . "<select name=\"alanguage\">";
			echo "<option value=\"\" $sellang>" . _ALL . "</option>";
			echo select_language( $alanguage );
			echo "</select>";
		}
		else
		{
			echo "<input type=\"hidden\" name=\"alanguage\" value=\"$alanguage\">";
		}
		$hometext = str_replace( "<br />", "\r\n", $hometext );
		if ( ! $editor )
		{
			$bodytext = str_replace( "<br />", "\r\n", $bodytext );
			$nottext = str_replace( "<br />", "\r\n", $nottext );
		}
		echo "<br><br><b>" . _STORYTEXT . "</b><br>" . "<textarea wrap=\"virtual\" cols=\"85\" rows=\"7\" name=\"hometext\">$hometext</textarea><br><br>" . "<b>" . _EXTENDEDTEXT . "</b><br>";
		if ( $editor == 1 )
		{
			aleditor( "bodytext", $bodytext, 500, 250 );
		}
		else
		{
			echo "<textarea wrap=\"virtual\" cols=\"85\" rows=\"7\" name=\"bodytext\">$bodytext</textarea>";
		}
		echo "<br><br><b>" . _NOTES . "</b><br>";
		if ( $editor == 1 )
		{
			aleditor( "notes", $notes, 500, 250 );
		}
		else
		{
			echo "<textarea wrap=\"virtual\" cols=\"85\" rows=\"3\" name=\"notes\">$notes</textarea>";
		}
		echo "<br><br>";
		if ( $images != "" )
		{
			echo "<b>" . _DELSTPIC . ":</b> ";
			echo "<input type=\"checkbox\" name=\"delpic\" value=\"yes\"> <a href=\"../$path/$images\" target=\"_blank\">$images</a><br><br>";
		}
		echo "<input type=\"hidden\" name=\"images\" value=\"$images\">";
		echo "<b>" . _STPIC . ":</b> " . "<input name=\"userfile\" type=\"file\"><br><br>";
		echo "" . _IMGTEXT . ": <input type=\"text\" name=\"imgtext\" value=\"$imgtext\" size=\"44\"><br><br>";
		echo "<input type=\"hidden\" NAME=\"anid\" value=\"$anid\">" . "<br>" . _CHNGPROGRAMSTORY . " (" . _NOWIS . ": " . viewtime( time(), 2 ) . "):<br><br>";
		echo "" . _DAY . ": <select name=\"day\">";
		for ( $i = 1; $i <= 31; $i++ )
		{
			echo "<option value=\"" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "\"";
			if ( $i == date("d", $time + $hourdiff * 60) )
			{
				echo " selected";
			}
			echo ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
		}
		echo "</select>";
		echo "" . _UMONTH . ": <select name=\"month\">";
		for ( $i = 1; $i <= 12; $i++ )
		{
			echo "<option value=\"" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "\"";
			if ( $i == date("m", $time + $hourdiff * 60) )
			{
				echo " selected";
			}
			echo ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
		}
		echo "</select>";
		echo "" . _YEAR . ": <select name=\"year\">";
		$z = date( "Y", $time + $hourdiff * 60 );
		for ( $i = $z; $i <= $z + 4; $i++ )
		{
			echo "<option value=\"$i\"";
			if ( $i == $z )
			{
				echo " selected";
			}
			echo ">$i</option>\n";
		}
		echo "</select>";
		echo " " . _HOUR . ": <select name=\"hour\">";
		for ( $i = 0; $i < 24; $i++ )
		{
			echo "<option value=\"" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "\"";
			if ( $i == date("H", $time + $hourdiff * 60) )
			{
				echo " selected";
			}
			echo ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
		}
		echo "</select>";
		echo ": <select name=\"min\">";
		for ( $i = 0; $i < 60; $i++ )
		{
			echo "<option value=\"" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "\"";
			if ( $i == date("i", $time + $hourdiff * 60) )
			{
				echo " selected";
			}
			echo ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
		}
		echo "</select>";
		echo ": 00<br><br>" . "<input type=\"hidden\" name=\"op\" value=\"EditStoryAutoSave\">" . "<input type=\"submit\" value=\"" . _SAVECHANGES . "\">";
		echo "<br>";
		echo "</form>";
		CloseTable();
		include ( "../footer.php" );
	}

	/**
	 * EditStoryAutoSave()
	 * 
	 * @param mixed $anid
	 * @param mixed $catid
	 * @param mixed $author
	 * @param mixed $title
	 * @param mixed $year
	 * @param mixed $day
	 * @param mixed $month
	 * @param mixed $hour
	 * @param mixed $min
	 * @param mixed $hometext
	 * @param mixed $bodytext
	 * @param mixed $images
	 * @param mixed $notes
	 * @param mixed $ihome
	 * @param mixed $alanguage
	 * @param mixed $acomm
	 * @param mixed $imgtext
	 * @param mixed $source
	 * @param mixed $topicid
	 * @param mixed $delpic
	 * @param mixed $userfile
	 * @return
	 */
	function EditStoryAutoSave( $anid, $catid, $author, $title, $year, $day, $month, $hour, $min, $hometext, $bodytext, $images, $notes, $ihome, $alanguage, $acomm, $imgtext, $source, $topicid, $delpic, $userfile )
	{
		global $editor, $hourdiff, $adminfile, $aid, $max_size, $width, $height, $path, $prefix, $db, $sizenewsarticleimg;
		$anid = intval( $anid );
		if ( $db->sql_numrows($db->sql_query("SELECT * FROM " . $prefix . "_stories_auto where anid='$anid'")) != 1 )
		{
			Header( "Location: " . $adminfile . ".php" );
			exit;
		}
		if ( (! $hometext) or ($hometext == "") or (! $title) or ($title == "") )
		{
			Header( "Location: " . $adminfile . ".php?op=adminnews" );
			exit;
		}
		$images = @uploadimg( $images, $delpic, 1, $sizenewsarticleimg, $path );
		if ( ! file_exists("" . INCLUDE_PATH . "" . $path . "/" . $images . "") )
		{
			$images = "";
		}
		if ( $images == "" ) $imgtext = "";
		$title = nv_htmlspecialchars( strip_tags(stripslashes(trim($title))) );
		$hometext = cheonguoc( nl2brStrict(stripslashes(FixQuotes($hometext))) );
		$bodytext = cheonguoc( stripslashes(FixQuotes($bodytext)) );
		$notes = cheonguoc( stripslashes(FixQuotes($notes)) );
		if ( ! $editor )
		{
			$bodytext = cheonguoc( nl2brStrict($bodytext) );
			$notes = cheonguoc( nl2brStrict($notes) );
		}
		$imgtext = nv_htmlspecialchars( strip_tags(stripslashes(trim($imgtext))) );
		$source = nv_htmlspecialchars( strip_tags(stripslashes(trim($source))) );
		$hour = str_pad( intval($hour), 2, "0", STR_PAD_LEFT );
		$min = str_pad( intval($min), 2, "0", STR_PAD_LEFT );
		$day = str_pad( intval($day), 2, "0", STR_PAD_LEFT );
		$month = str_pad( intval($month), 2, "0", STR_PAD_LEFT );
		$year = intval( $year );
		$acttime = mktime( $hour, $min, 0, $month, $day, $year ) - $hourdiff * 60;
		$date = date( "Y-m-d H:i:s", $acttime );
		$db->sql_query( "UPDATE " . $prefix . "_stories_auto SET catid='$catid', aid='$author', topicid='$topicid', title='$title', hometext='$hometext', bodytext='$bodytext', images='$images', notes='$notes', ihome='$ihome', alanguage='$alanguage', acomm='$acomm', imgtext='$imgtext', source='$source', time='$date' WHERE anid='$anid'" );
		liststauto();
		info_exit( "<br><br><center><b>" . _NEWSSAVED . "</b></center><br><br><META HTTP-EQUIV=\"refresh\" content=\"2;URL=" . $adminfile . ".php?op=newsadminhome\">" );
	}

	/**
	 * RemoveStoryAuto()
	 * 
	 * @param mixed $anid
	 * @return
	 */
	function RemoveStoryAuto( $anid )
	{
		global $adminfile, $prefix, $db, $path;
		$anid = intval( $anid );
		$result = $db->sql_query( "SELECT images FROM " . $prefix . "_stories_auto where anid='$anid'" );
		$row = $db->sql_fetchrow( $result );
		$images = $row['images'];
		@unlink( "../" . $path . "/" . $images . "" );
		@unlink( "../" . $path . "/small_" . $images . "" );
		@unlink( "../" . $path . "/nst_" . $images . "" );
		$db->sql_query( "delete from " . $prefix . "_stories_auto where anid=$anid" );
		$db->sql_query( "OPTIMIZE TABLE " . $prefix . "_stories_auto" );
		liststauto();
		info_exit( "<center><b>" . _DELEDARTICLE . "</b></center><META HTTP-EQUIV=\"refresh\" content=\"2;URL=" . $adminfile . ".php?op=newsadminhome\">" );
	}

	/**
	 * Imggallery()
	 * 
	 * @return
	 */
	function Imggallery()
	{
		global $adminfile, $prefix, $db, $bgcolor2, $path, $multilingual, $currentlang;
		$in = ( isset($_GET['in']) ) ? intval( $_GET['in'] ) : intval( $_POST['in'] );
		if ( $in == 1 )
		{
			$catid = ( isset($_GET['catid']) ) ? intval( $_GET['catid'] ) : intval( $_POST['catid'] );
			if ( $catid == 0 )
			{
				Header( "Location: " . $adminfile . ".php?op=newsadminhome" );
				exit;
			}
			$result = $db->sql_query( "select * from " . $prefix . "_stories_images where catid='$catid' AND ihome='0' order by ilanguage, imgid DESC" );
		} elseif ( $in == 2 )
		{
			$sid = ( isset($_GET['sid']) ) ? intval( $_GET['sid'] ) : intval( $_POST['sid'] );
			if ( $sid == 0 )
			{
				Header( "Location: " . $adminfile . ".php?op=newsadminhome" );
				exit;
			}
			$result = $db->sql_query( "select * from " . $prefix . "_stories_images where sid='$sid' AND ihome='0' order by imgid DESC" );

		}
		else
		{
			$result = $db->sql_query( "select * from " . $prefix . "_stories_images where ihome='1' order by ilanguage, imgid DESC" );
		}
		include ( '../header.php' );

		newstopbanner();
		OpenTable();
		if ( $in == 1 )
		{
			list( $cattitle ) = $db->sql_fetchrow( $db->sql_query("select title from " . $prefix . "_stories_cat where catid='" . $catid . "'") );
			echo "<center><font class=\"title\"><b>" . _SKIMAGESGAL . "</b><br>" . _IMGFORCAT . ": <b>$cattitle</b></font></center>";
		} elseif ( $in == 2 )
		{
			list( $stitle, $slanguage ) = $db->sql_fetchrow( $db->sql_query("select title, alanguage from " . $prefix . "_stories where sid='" . $sid . "'") );
			echo "<center><font class=\"title\"><b>" . _SKIMAGESGAL . "</b><br>" . _IMGFORART . ": <b>$stitle</b></font></center>";
		}
		else
		{
			echo "<center><font class=\"title\"><b>" . _IMGGALLERY . "</b></font></center>";
		}
		CloseTable();
		echo "<br>";

		if ( $db->sql_numrows($result) > 0 )
		{
			OpenTable();
			echo "<table border=\"1\" cellpadding=\"3\" cellspacing=\"3\" width=\"100%\">\n" . "<tr>\n<td width=\"10\" align=\"center\" bgcolor=\"$bgcolor2\">TT</td>\n" . "<td bgcolor=\"$bgcolor2\">" . _TITLEIMG . "</td>\n";
			if ( $in != 2 )
			{
				echo "<td bgcolor=\"$bgcolor2\">" . _LANGUAGE . "</td>\n";
			}
			echo "<td bgcolor=\"$bgcolor2\"><center>" . _FUNCTIONS . "</center></td>\n</tr>\n";
			$a = 0;
			while ( $row = $db->sql_fetchrow($result) )
			{
				$imgid = intval( $row['imgid'] );
				$imgtitle = $row['imgtitle'];
				$imglink = $row['imglink'];
				$ilanguage = $row['ilanguage'];
				if ( $imglink != "" and file_exists("" . INCLUDE_PATH . "" . $path . "/" . $imglink . "") )
				{
					$a++;
					$size2 = @getimagesize( "" . INCLUDE_PATH . "" . $path . "/" . $imglink . "" );
					echo "<tr>\n<td width=\"10\">$a</td>\n<td><a href=\"javascript:;\" onClick=\"MM_openBrWindow('" . INCLUDE_PATH . "viewimg.php?imglink=" . $path . "/" . $imglink . "','','scrollbars=no,width=$size2[0],height=$size2[1],resizable=no')\">" . $imgtitle . "</a></td>\n";
					if ( $in != 2 ) echo "<td>" . ucfirst( $ilanguage ) . "</td>\n";
					echo "<td><center><a href=\"" . $adminfile . ".php?op=EditImgStory&imgid=$imgid\">" . _EDIT . "</a> | <a href=\"" . $adminfile . ".php?op=DelImgStory&imgid=$imgid\">" . _DELETE . "</a></center></td>\n</tr>\n";
				}
			}
			echo "</table>\n";
			CloseTable();
			echo "<br>";
		}
		OpenTable();
		echo "<center><font class=\"option\"><b>" . _ADDIMG . "</b></font></center><br><br>\n" . "<center><form enctype=\"multipart/form-data\" action=\"" . $adminfile . ".php\" method=\"post\">\n" . "<b>" . _UPLOADIMG . "</b><br>\n" . "<input name=\"userfile\" type=\"file\" size=\"37\"><br><br>\n" . "<b>" . _TITLE . "</b><br>\n" . "<input name=\"imgtitle\" type=\"text\" size=\"80\" maxlength=\"255\"><br><br>\n" . "<b>" . _TITLEIMG . "</b><br>\n" . "<textarea name=\"imgtext\" cols=\"80\" rows=\"10\"></textarea><br><br>\n";
		if ( $in != 2 )
		{
			if ( $multilingual == 1 )
			{
				echo "<b>" . _LANGUAGE . "</b><br>\n" . "<select name=\"ilanguage\">";
				echo select_language( $currentlang );
				echo "</select><br><br>\n";
			}
			else
			{
				echo "<input type=\"hidden\" name=\"ilanguage\" value=\"$currentlang\">\n";
			}
		}
		else
		{
			echo "<input type=\"hidden\" name=\"ilanguage\" value=\"$slanguage\">\n";
		}
		if ( $in != 1 and $in != 2 )
		{
			echo "<input type=\"hidden\" name=\"ihome\" value=\"1\">\n";
			echo "<input type=\"hidden\" name=\"sid\" value=\"0\">\n";
			echo "<input type=\"hidden\" name=\"catid\" value=\"0\">\n";
		}
		else
		{
			echo "<input type=\"hidden\" name=\"ihome\" value=\"0\">\n";
			if ( $in == 1 )
			{
				echo "<input type=\"hidden\" name=\"sid\" value=\"0\">\n";
				echo "<input type=\"hidden\" name=\"catid\" value=\"$catid\">\n";
			} elseif ( $in == 2 )
			{
				echo "<input type=\"hidden\" name=\"sid\" value=\"$sid\">\n";
				echo "<input type=\"hidden\" name=\"catid\" value=\"0\">\n";
			}
		}
		echo "<input type=\"hidden\" name=\"in\" value=\"$in\">\n";
		echo "<input type=\"hidden\" name=\"op\" value=\"addimg\">\n" . "<input type=\"submit\" value=\"" . _ADD . "\">\n" . "<input type=\"reset\" value=\"" . _RESET . "\">\n</form></center>\n";
		CloseTable();
		include ( "../footer.php" );
	}

	/**
	 * addimg()
	 * 
	 * @return
	 */
	function addimg()
	{
		global $adminfile, $sizeatl, $prefix, $db, $path;
		$imgtitle = stripslashes( FixQuotes($_POST['imgtitle']) );
		$imgtext = nl2brStrict( stripslashes(FixQuotes($_POST['imgtext'])) );
		$ilanguage = stripslashes( FixQuotes($_POST['ilanguage']) );
		$sid = intval( $_POST['sid'] );
		$catid = intval( $_POST['catid'] );
		$ihome = intval( $_POST['ihome'] );
		$in = intval( $_POST['in'] );
		if ( ! is_uploaded_file($_FILES['userfile']['tmp_name']) )
		{
			Header( "Location: " . $adminfile . ".php?op=newsadminhome" );
		}
		$imglink = @uploadimg( "", "", 1, $sizeatl, $path );
		$db->sql_query( "insert into " . $prefix . "_stories_images values (NULL, '$imgtitle', '$imgtext', '$imglink', '$sid', '$catid', '$ihome','$ilanguage')" );
		if ( $catid > 0 )
		{
			Header( "Location: " . $adminfile . ".php?op=Imggallery&in=1&catid=$catid" );
		} elseif ( $sid > 0 )
		{
			Header( "Location: " . $adminfile . ".php?op=Imggallery&in=2&sid=$sid" );
		}
		else
		{
			Header( "Location: " . $adminfile . ".php?op=Imggallery" );
		}
	}

	/**
	 * EditImgStory()
	 * 
	 * @param mixed $imgid
	 * @return
	 */
	function EditImgStory( $imgid )
	{
		global $multilingual, $adminfile, $prefix, $db, $path;
		$imgid = intval( $imgid );
		$result = $db->sql_query( "SELECT * FROM " . $prefix . "_stories_images where imgid=$imgid" );
		$row = $db->sql_fetchrow( $result );
		$imgtitle = stripslashes( FixQuotes($row['imgtitle']) );
		$imgtext = str_replace( "<br />", "\r\n", stripslashes($row['imgtext']) );
		$imglink = stripslashes( $row['imglink'] );
		$sid = intval( $row['sid'] );
		$catid = intval( $row['catid'] );
		$ihome = intval( $row['ihome'] );
		$ilanguage = stripslashes( $row['ilanguage'] );
		if ( $db->sql_numrows($result) != 1 )
		{
			Header( "Location: " . $adminfile . ".php?op=Imggallery" );
			exit;
		}
		include ( '../header.php' );

		newstopbanner();
		OpenTable();
		echo "<center><a href=\"" . $adminfile . ".php?op=Imggallery\"><font class=\"title\"><b>" . _IMGGALLERY . "</b></font></a></center>";
		CloseTable();
		echo "<br>";
		OpenTable();
		echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"3\">\n<tr>\n<td valign=\"top\">";
		echo "<center><form enctype=\"multipart/form-data\" action=\"" . $adminfile . ".php\" method=\"post\">\n" . "<b>" . _IMGSTORY . "</b>: \n" . "<b>$imgtitle</b><br><br>\n" . "<b>" . _UPLOADIMG . "</b><br>\n" . "<input name=\"userfile\" type=\"file\" size=\"37\"><br><br>\n" . "<b>" . _TITLE . "</b><br>\n" . "<input name=\"imgtitle\" type=\"text\" size=\"80\" maxlength=\"255\" value=\"$imgtitle\"><br><br>\n" . "<b>" . _TITLEIMG . "</b><br>\n" . "<textarea name=\"imgtext\" cols=\"80\" rows=\"10\">$imgtext</textarea><br><br>\n";
		if ( $sid == 0 )
		{
			if ( $multilingual == 1 )
			{
				echo "<b>" . _LANGUAGE . "</b><br>\n" . "<select name=\"ilanguage\">";
				echo select_language( $ilanguage );
				echo "</select><br><br>\n";
			}
			else
			{
				echo "<input type=\"hidden\" name=\"ilanguage\" value=\"$ilanguage\">\n";
			}
		}
		else
		{
			echo "<input type=\"hidden\" name=\"ilanguage\" value=\"$ilanguage\">\n";
		}
		echo "<input type=\"hidden\" name=\"ihome\" value=\"$ihome\">\n";
		echo "<input type=\"hidden\" name=\"sid\" value=\"$sid\">\n";
		echo "<input type=\"hidden\" name=\"catid\" value=\"$catid\">\n";
		echo "<input type=\"hidden\" name=\"imglink\" value=\"$imglink\">\n";
		echo "<input type=\"hidden\" name=\"imgid\" value=\"$imgid\">\n";
		echo "<input type=\"hidden\" name=\"op\" value=\"SaveEditImgStory\">\n" . "<input type=\"submit\" value=\"" . _SAVECHANGES . "\">\n" . "<input type=\"reset\" value=\"" . _RESET . "\">\n</form></center>\n";
		if ( file_exists("" . INCLUDE_PATH . "" . $path . "/" . $imglink . "") )
		{
			$imglink1 = $imglink;
			$size1 = @getimagesize( "" . INCLUDE_PATH . "" . $path . "/" . $imglink . "" );
			if ( file_exists("" . INCLUDE_PATH . "" . $path . "/small_" . $imglink . "") )
			{
				$imglink = "small_" . $imglink . "";
			}
			$size2 = @getimagesize( "" . INCLUDE_PATH . "" . $path . "/" . $imglink . "" );
			echo "</td><td valign=\"top\">";
			if ( $size1[0] > $size2[0] )
			{
				echo "<img style=\"cursor: hand\" onclick=\"return MM_openBrWindow('" . INCLUDE_PATH . "viewimg.php?imglink=" . $path . "/" . $imglink1 . "','viewclick','scrollbars=no,width=$size1[0],height=$size1[1],resizable=no');\" border=\"0\" src=\"" . INCLUDE_PATH . "" . $path . "/" . $imglink . "\" width=\"$size2[0]\">";
			}
			else
			{
				echo "<img border=\"0\" src=\"" . INCLUDE_PATH . "" . $path . "/" . $imglink . "\" width=\"$size2[0]\">";
			}
		}
		echo "</td></tr></table></center>\n";
		CloseTable();
		include ( '../footer.php' );
	}

	/**
	 * DelImgStory()
	 * 
	 * @param mixed $imgid
	 * @return
	 */
	function DelImgStory( $imgid )
	{
		global $adminfile, $prefix, $db, $path;
		$imgid = intval( $imgid );
		$resultcheck = $db->sql_query( "SELECT sid, catid, ihome, imglink FROM " . $prefix . "_stories_images where imgid=$imgid" );
		if ( $db->sql_numrows($resultcheck) != 1 )
		{
			Header( "Location: " . $adminfile . ".php?op=Imggallery" );
			exit;
		}
		$row = $db->sql_fetchrow( $resultcheck );
		$sid = intval( $row['sid'] );
		$catid = intval( $row['catid'] );
		$ihome = intval( $row['ihome'] );
		$filelink = $row['imglink'];
		@unlink( "" . INCLUDE_PATH . "" . $path . "/" . $filelink . "" );
		@unlink( "" . INCLUDE_PATH . "" . $path . "/small_" . $filelink . "" );
		$result = $db->sql_query( "DELETE FROM " . $prefix . "_stories_images WHERE imgid=$imgid" );
		if ( ! $result )
		{
			return;
		}
		if ( $ihome == 1 )
		{
			Header( "Location: " . $adminfile . ".php?op=Imggallery" );
		}
		else
		{
			if ( $catid != 0 )
			{
				Header( "Location: " . $adminfile . ".php?op=Imggallery&in=1&catid=$catid" );
			} elseif ( $sid != 0 )
			{
				Header( "Location: " . $adminfile . ".php?op=Imggallery&in=2&sid=$sid" );
			}
		}
	}

	/**
	 * SaveEditImgStory()
	 * 
	 * @return
	 */
	function SaveEditImgStory()
	{
		global $adminfile, $prefix, $db, $sizeatl, $path;
		$imgid = intval( $_POST['imgid'] );
		if ( $db->sql_numrows($db->sql_query("SELECT * FROM " . $prefix . "_stories_images where imgid=$imgid")) != 1 )
		{
			Header( "Location: " . $adminfile . ".php?op=Imggallery" );
			exit;
		}
		$sid = intval( $_POST['sid'] );
		$catid = intval( $_POST['catid'] );
		$ihome = intval( $_POST['ihome'] );
		$imglink = stripslashes( FixQuotes($_POST['imglink']) );
		if ( is_uploaded_file($_FILES['userfile']['tmp_name']) )
		{
			$imglink = @uploadimg( $imglink, "yes", 1, $sizeatl, $path );
		}
		$imgtitle = stripslashes( FixQuotes($_POST['imgtitle']) );
		$imgtext = nl2brStrict( stripslashes(FixQuotes($_POST['imgtext'])) );
		$ilanguage = stripslashes( FixQuotes($_POST['ilanguage']) );
		$db->sql_query( "UPDATE `" . $prefix . "_stories_images` SET `imgtitle` = '$imgtitle', `imgtext` = '$imgtext', `imglink` = '$imglink', `sid` = '$sid', `catid` = '$catid', `ihome` = '$ihome', `ilanguage` = '$ilanguage' WHERE `imgid` =$imgid LIMIT 1" );
		if ( $ihome == 1 )
		{
			Header( "Location: " . $adminfile . ".php?op=Imggallery" );
		}
		else
		{
			if ( $catid != 0 and $sid == 0 )
			{
				Header( "Location: " . $adminfile . ".php?op=Imggallery&in=1&catid=$catid" );
			} elseif ( $sid != 0 and $catid == 0 )
			{
				Header( "Location: " . $adminfile . ".php?op=Imggallery&in=2&sid=$sid" );
			}
		}
	}



	/**
	 * ManagerPicNews()
	 * 
	 * @param mixed $file
	 * @return
	 */
	function ManagerPicNews( $file )
	{
		global $db, $prefix, $adminfold, $adminfile, $module_name, $bgcolor2, $path, $nukeurl;
		include ( "../header.php" );
		newstopbanner();

		$imgdir = "" . INCLUDE_PATH . "$path";
		$pdir = opendir( $imgdir );

		while ( $file = readdir($pdir) )
		{
			$f_name = end( explode(".", $file) );
			$ftypeqk = strtolower( $f_name );
			if ( $file != "" && ($ftypeqk == "jpg" || $ftypeqk == "jpeg" || $ftypeqk == "gif" || $ftypeqk == "png") )
			{
				$tlist[] = "$file";
			}
		}
		closedir( $pdir );
		@sort( $tlist );
		if ( sizeof($tlist) > 0 )
		{

			$page = ( isset($_GET['page']) ) ? intval( $_GET['page'] ) : 0;
			$all_page = sizeof( $tlist );
			$perpage = 100;
			$base_url = "" . $adminfile . ".php?op=ManagerPicNews";

			if ( $page == 0 )
			{
				$pagenext = $perpage;
			}
			else
			{
				$pagenext = $perpage + $page;
			}
			if ( $pagenext > $all_page )
			{
				$pagenext = $all_page;
			}
			;

			Opentable();
			echo "" . _IMGNEWS4 . " $nukeurl/$path/ " . _IMGNEWS5 . " <a href=" . $adminfile . ".php?op=ManagerPicNews2&page=$page><b>" . _HERE . "</b></a>.";
			Closetable();
			echo "<br>";
			Opentable();
			echo "<center>";
			if ( $all_page > $perpage )
			{
				echo generate_page( $base_url, $all_page, $perpage, $page );
				echo "<br><br>";
			}
			for ( $i = $page; $i < $pagenext; $i++ )
			{
				if ( $tlist[$i] != "" )
				{
					echo "<input onclick=\"this.focus();this.select()\" value=\"$tlist[$i]\" size=\"26\"><a target=\"_new\" href=\"$nukeurl/$path/$tlist[$i]\">Xem</a> ";
				}
			}
			echo "<br><br>";
			if ( $all_page > $perpage )
			{
				echo generate_page( $base_url, $all_page, $perpage, $page );
				echo "</center><br>";
			}
			Closetable();

		}
		else
		{
			echo "" . _NOIMGNEWS . " $nukeurl/$path/";
		}

		include ( "../footer.php" );

	}



	/**
	 * ManagerPicNews2()
	 * 
	 * @param mixed $file
	 * @return
	 */
	function ManagerPicNews2( $file )
	{
		global $db, $prefix, $adminfold, $adminfile, $module_name, $bgcolor2, $path, $nukeurl;
		include ( "../header.php" );
		newstopbanner();

		$imgdir = "" . INCLUDE_PATH . "$path";
		$pdir = opendir( $imgdir );

		while ( $file = readdir($pdir) )
		{
			$f_name = end( explode(".", $file) );
			$ftypeqk = strtolower( $f_name );
			if ( $file != "" && ($ftypeqk == "jpg" || $ftypeqk == "jpeg" || $ftypeqk == "gif" || $ftypeqk == "png") )
			{
				$tlist[] = "$file";
			}
		}
		closedir( $pdir );
		@sort( $tlist );
		if ( sizeof($tlist) > 0 )
		{

			$page = ( isset($_GET['page']) ) ? intval( $_GET['page'] ) : 0;
			$all_page = sizeof( $tlist );
			$perpage = 20;
			$base_url = "" . $adminfile . ".php?op=ManagerPicNews2";

			if ( $page == 0 )
			{
				$pagenext = $perpage;
			}
			else
			{
				$pagenext = $perpage + $page;
			}
			if ( $pagenext > $all_page )
			{
				$pagenext = $all_page;
			}
			;

			OpenTable();
			echo "" . _IMGNEWS4 . " $nukeurl/$path/ " . _IMGNEWS51 . " <a href=" . $adminfile . ".php?op=ManagerPicNews&page=$page><b>" . _HERE . "</b></a>.";
			CloseTable();
			echo "<br>";
			OpenTable();
			echo "<center>";
			if ( $all_page > $perpage )
			{
				echo generate_page( $base_url, $all_page, $perpage, $page );
				echo "<br><br>";
			}
			for ( $i = $page; $i < $pagenext; $i++ )
			{
				if ( $tlist[$i] != "" )
				{
					echo "<a target=\"_new\" href=\"$nukeurl/$path/$tlist[$i]\"><img src=\"$imgdir/$tlist[$i]\" width=\"100\" border=\"0\"></a><input onclick=\"this.focus();this.select()\" value=\"$tlist[$i]\" size=\"15\"> ";
				}
			}
			echo "<br><br>";
			if ( $all_page > $perpage )
			{
				echo generate_page( $base_url, $all_page, $perpage, $page );
				echo "</center><br>";
			}

			CloseTable();

		}
		else
		{
			echo "" . _NOIMGNEWS . " $nukeurl/$path/";
		}
		include ( "../footer.php" );

	}


	/**
	 * Commentadmin()
	 * 
	 * @return
	 */
	function Commentadmin()
	{
		global $adminfile, $db, $prefix;
		include ( "../header.php" );

		newstopbanner();
		OpenTable();
		echo "<div align='center'><b>" . _COMMENTADMIN . "</b></div>\n";
		CloseTable();
		echo "<br>";
		OpenTable();
		echo "<br><br><center>" . "<form action=\"" . $adminfile . ".php\" method=\"post\">" . "" . _COMMID . ": <input type=\"text\" NAME=\"tid\" SIZE=\"10\"> " . "<select name=\"op\">" . "<option value=\"EditStoriesComment\" SELECTED>" . _EDIT . "</option>" . "<option value=\"RemoveStoriesComment\">" . _DELETE . "</option>" . "</select> " . "<input type=\"submit\" value=\"" . _GO . "\">" . "</form></center>";
		CloseTable();
		echo "<br>";
		OpenTable();
		$num_comno = $db->sql_fetchrow( $db->sql_query("SELECT COUNT(tid) FROM " . $prefix . "_stories_comments WHERE online='0'") );
		$all_page = $num_comno[0] ? $num_comno[0] : 1;
		$page = isset( $_GET['page'] ) ? intval( $_GET['page'] ) : 0;
		$perpage = 10;
		$base_url = "" . $adminfile . ".php?op=Comment";
		$sql_comno = "SELECT a.tid as tid, a.sid as sid, a.name as name, a.comment as comment, b.title as title FROM " . $prefix . "_stories_comments a, " . $prefix . "_stories b WHERE b.sid=a.sid AND a.online=0 ORDER BY a.date DESC LIMIT $page, $perpage";
		$res_comno = $db->sql_query( $sql_comno );
		echo "<table width='100%' border='0' cellpadding='5' style='border-collapse: collapse'>\n";
		echo "<tr>\n";
		echo "<td align='center'><b>" . _COMMNO . "</b></td>\n";
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
			echo "<td><a href=\"../modules.php?name=News&op=viewst&sid=" . $row_comno['sid'] . "\">" . $row_comno['title'] . "</a><br><i>" . $row_comno['comment'] . "</i></td>\n";
			echo "<td align='center'><a href='" . $adminfile . ".php?op=Commentche&id=" . $row_comno['tid'] . "'>" . _COMMCHE . "</a>&nbsp;|&nbsp;<a href='" . $adminfile . ".php?op=EditStoriesComment&amp;tid=" . $row_comno['tid'] . "'>" . _EDIT . "</a>&nbsp;|&nbsp;<a href='" . $adminfile . ".php?op=RemoveStoriesComment&amp;sid=" . $row_comno['sid'] . "&amp;tid=" . $row_comno['tid'] . "'>" . _DELETE . "</a>&nbsp;|&nbsp;<a href='" . $adminfile . ".php?op=Commentdel&id=" . $row_comno['tid'] . "'>" . _DELETEIT . "</a></td>\n";
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
	 * Commentok()
	 * 
	 * @return
	 */
	function Commentok()
	{
		global $adminfile, $db, $prefix;
		include ( "../header.php" );

		newstopbanner();
		OpenTable();
		echo "<div align='center'><b>" . _COMMENTADMIN . "</b></div>\n";
		CloseTable();
		echo "<br>";
		OpenTable();
		echo "<br><br><center>" . "<form action=\"" . $adminfile . ".php\" method=\"post\">" . "" . _COMMID . ": <input type=\"text\" NAME=\"tid\" SIZE=\"10\"> " . "<select name=\"op\">" . "<option value=\"EditStoriesComment\" SELECTED>" . _EDIT . "</option>" . "<option value=\"RemoveStoriesComment\">" . _DELETE . "</option>" . "</select> " . "<input type=\"submit\" value=\"" . _GO . "\">" . "</form></center>";
		CloseTable();
		echo "<br>";
		OpenTable();
		$num_comok = $db->sql_fetchrow( $db->sql_query("SELECT COUNT(tid) FROM " . $prefix . "_stories_comments WHERE online='1'") );
		$all_page = $num_comok[0] ? $num_comok[0] : 1;
		$page = isset( $_GET['page'] ) ? intval( $_GET['page'] ) : 0;
		$perpage = 10;
		$base_url = "" . $adminfile . ".php?op=Commentok";
		$sql_comok = "SELECT a.tid as tid, a.sid as sid, a.name as name, a.comment as comment, b.title as title FROM " . $prefix . "_stories_comments a, " . $prefix . "_stories b WHERE b.sid=a.sid AND a.online=1 ORDER BY a.date DESC LIMIT $page, $perpage";
		$res_comok = $db->sql_query( $sql_comok );
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
		echo "<td width='140' align='center'><b>" . _FUNCTIONS . "</b></td>\n";
		echo "</tr>\n";
		$i = 1;
		while ( $row_comok = $db->sql_fetchrow($res_comok) )
		{
			$stt = $i + $page;
			echo "<tr>\n";
			echo "<td align='center'>" . $row_comok['tid'] . "</td>\n";
			echo "<td>" . $row_comok['name'] . "</td>\n";
			echo "<td><a href=\"../modules.php?name=News&op=viewst&sid=" . $row_comok['sid'] . "\">" . $row_comok['title'] . "</a><br><i>" . $row_comok['comment'] . "</i></td>\n";
			echo "<td align='center'><a href='" . $adminfile . ".php?op=Commentche&id=" . $row_comok['tid'] . "'>" . _COMMCHE2 . "</a>&nbsp;|&nbsp;<a href='" . $adminfile . ".php?op=EditStoriesComment&amp;tid=" . $row_comok['tid'] . "'>" . _EDIT . "</a>&nbsp;|&nbsp;<a href='" . $adminfile . ".php?op=RemoveStoriesComment&amp;sid=" . $row_comok['sid'] . "&amp;tid=" . $row_comok['tid'] . "'>" . _DELETE . "</a>&nbsp;|&nbsp;<a href='" . $adminfile . ".php?op=Commentdel&id=" . $row_comok['tid'] . "'>" . _DELETEIT . "</a></td>\n";
			echo "</tr>\n";
			$i++;
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
	 * Commentdel()
	 * 
	 * @param mixed $tid
	 * @return
	 */
	function Commentdel( $tid )
	{
		global $db, $prefix, $adminfile;
		$tid = intval( isset($_GET['id']) ? $_GET['id'] : $_POST['id'] );
		$sql_delcom = "SELECT sid,online FROM " . $prefix . "_stories_comments WHERE tid=$tid";
		$res_descom = $db->sql_query( $sql_delcom );
		$che_com = $db->sql_numrows( $res_delcom );
		if ( $che_com == 0 || $tid == "" )
		{
			header( "Location: " . $adminfile . ".php?op=Comment" );
		}
		list( $sid, $comonline ) = $db->sql_fetchrow( $res_checom );

		$res_del = $db->sql_query( "DELETE FROM " . $prefix . "_stories_comments WHERE tid=$tid" );
		if ( $res_del )
		{
			if ( $comonline )
			{

				$db->sql_query( "UPDATE " . $prefix . "_stories SET comments=comments-1 WHERE sid='$sid'" );
			}

			info_exit( "<center><b>" . _COMMERR . "</b></center><META HTTP-EQUIV=\"refresh\" content=\"2;URL=" . $adminfile . ".php?op=Comment\">" );
		}
		else
		{
			info_exit( "<center><b>" . _COMMERR2 . "</b></center><META HTTP-EQUIV=\"refresh\" content=\"2;URL=" . $adminfile . ".php?op=Comment\">" );
		}
	}

	/**
	 * Commentcheck()
	 * 
	 * @param mixed $tid
	 * @return
	 */
	function Commentcheck( $tid )
	{
		global $db, $prefix, $adminfile;
		$tid = intval( $_GET['id'] );
		$sql_checom = "SELECT name, email, comment, online FROM " . $prefix . "_stories_comments WHERE tid='$tid'";
		$res_checom = $db->sql_query( $sql_checom );
		$che_com2 = $db->sql_numrows( $res_checom );
		if ( $che_com2 == 0 || $tid == "" )
		{
			header( "Location: " . $adminfile . ".php?op=Comment" );
		}
		list( $comname, $comemail, $comment, $comonline ) = $db->sql_fetchrow( $res_checom );
		if ( $comonline == 1 )
		{
			$checkcom1 = "checked";
			$checkcom2 = "";
		}
		else
		{
			$checkcom2 = "checked";
			$checkcom1 = "";
		}
		include ( "../header.php" );

		newstopbanner();
		OpenTable();
		echo "<table width='100%' border='0' cellpadding='5' style='border-collapse: collapse'>\n";
		echo "<form action='' method='POST'><tr>\n";
		echo "<td colspan='2' align='center'><b>" . _COMMCHE3 . "</b></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td align='right'><b>" . _COMMNAME . ":</b></td>\n";
		echo "<td>$comname</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td align='right'><b>Email:</b></td>\n";
		echo "<td>$comemail</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td align='right'><b>" . _COMMKIEN . ":</b></td>\n";
		echo "<td>$comment</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td align='right'><b>" . _COMMALLOW . "</b></td>\n";
		echo "<td><input type='radio' name='comme' $checkcom1 value='1'>" . _YES . "&nbsp;&nbsp;<input type='radio' name='comme' $checkcom2 value='0'>" . _NO . "</td>\n";
		echo "</tr>\n";
		echo "<input type='hidden' name='id' value='$tid'>\n";
		echo "<input type='hidden' name='op' value='Commentchetrue'>\n";
		echo "<tr>\n";
		echo "<td colspan='2' align='center'><input type='submit' value='" . _OK . "'>&nbsp;<input type='button' value='" . _DELETE . "' onclick=\"window.location='" . $adminfile . ".php?op=Commentdel&id=$tid'; return false\"></td>\n";
		echo "</form></tr>\n";
		echo "</table>\n";
		CloseTable();
		include ( "../footer.php" );
	}

	/**
	 * Commentchecktrue()
	 * 
	 * @param mixed $tid
	 * @return
	 */
	function Commentchecktrue( $tid )
	{
		global $db, $prefix, $adminfile;
		$tid = intval( $_POST['id'] );
		$comme = intval( $_POST['comme'] );
		$res_updcom = $db->sql_query( "UPDATE " . $prefix . "_stories_comments SET online='$comme' WHERE tid='$tid'" );
		if ( $res_updcom )
		{
			list( $sid ) = $db->sql_fetchrow( $db->sql_query("SELECT sid FROM " . $prefix . "_stories_comments WHERE tid='$tid'") );

			$db->sql_query( "UPDATE " . $prefix . "_stories SET comments=comments+1 WHERE sid='$sid'" );

			info_exit( "<center><b>" . _COMMERR3 . "</b></center><META HTTP-EQUIV=\"refresh\" content=\"2;URL=" . $adminfile . ".php?op=Comment\">" );
		}
		else
		{
			info_exit( "<center><b>" . _COMMERR2 . "</b></center><META HTTP-EQUIV=\"refresh\" content=\"2;URL=" . $adminfile . ".php?op=Comment\">" );
		}
	}



	switch ( $op )
	{

		case "ManagerPicNews":
			ManagerPicNews( $file );
			break;

		case "ManagerPicNews2":
			ManagerPicNews2( $file2 );
			break;

		case "Comment":
			Commentadmin();
			break;

		case "Commentok":
			Commentok();
			break;

		case "Commentche":
			Commentcheck( $tid );
			break;

		case "Commentchetrue":
			Commentchecktrue( $tid );
			break;

		case "Commentdel":
			Commentdel( $tid );
			break;

		case "fixweightcat":
			fixweightcat();
			break;

		case "ManagerCategory":
			ManagerCategory();
			break;

		case "OrderCategory":
			OrderCategory( $weightrep, $weight, $catidrep, $catidori );
			break;

		case "EditCategory":
			EditCategory( $catid );
			break;

		case "CstorieshomeCategory":
			CstorieshomeCategory( $catid );
			break;

		case "subdelete":
			subdelete();
			break;

		case "DelCategory":
			DelCategory( $cat );
			break;

		case "YesDelCategory":
			YesDelCategory( $catid );
			break;

		case "NoMoveCategory":
			NoMoveCategory( $catid, $newcat );
			break;

		case "SaveEditCategory":
			SaveEditCategory( $catid, $parentid, $title, $catimage, $ihome, $linkshome );
			break;

		case "AddCategory":
			AddCategory();
			break;

		case "SaveCategory":
			SaveCategory( $title, $parentid, $catimage, $xihome, $storieshome, $linkshome );
			break;

		case "SaveCstorieshomeCategory":
			SaveCstorieshomeCategory( $catid, $storieshome );
			break;

		case "ManagerTopic":
			ManagerTopic();
			break;

		case "ViewTopic":
			ViewTopic( $topicid, $xtitle );
			break;

		case "OutTopic":
			OutTopic( $topicid, $sid );
			break;

		case "InTopic":
			InTopic( $topicid, $sid );
			break;

		case "AddTopic":
			AddTopic();
			break;

		case "EditTopic":
			EditTopic( $topicid );
			break;

		case "DelTopic":
			DelTopic( $topicid );
			break;

		case "SaveEditTopic":
			SaveEditTopic( $topicid, $topictitle );
			break;

		case "SaveTopic":
			SaveTopic( $topictitle );
			break;

		case "Imggallery":
			Imggallery();
			break;

		case "addimg":
			addimg();
			break;

		case "EditImgStory":
			EditImgStory( $imgid );
			break;

		case "DelImgStory":
			DelImgStory( $imgid );
			break;

		case "SaveEditImgStory":
			SaveEditImgStory();
			break;

		case "adminnews":
			adminnews();
			break;

		case "DisplayStory":
			displayStory( $sid );
			break;

		case "PreviewAgain":
			previewStory( $sender_ip, $delpic, $images, $imgtext, $automated, $year, $day, $month, $hour, $min, $sid, $author, $subject, $hometext, $bodytext, $notes, $catid, $topicid, $ihome, $alanguage, $acomm, $source );
			break;

		case "PostStory":
			postStory( $sender_ip, $delpic, $images, $imgtext, $automated, $year, $day, $month, $hour, $min, $sid, $author, $subject, $hometext, $bodytext, $notes, $catid, $topicid, $ihome, $alanguage, $acomm, $source );
			break;

		case "EditStory":
			editStory( $sid );
			break;

		case "RemoveStory":
			removeStory( $sid, $ok );
			break;

		case "ChangeStory":
			changeStory( $sid, $author, $subject, $hometext, $bodytext, $images, $imgtext, $delpic, $notes, $catid, $topicid, $ihome, $alanguage, $acomm, $source );
			break;

		case "DeleteStory":
			deleteStory( $sid );
			break;

		case "adminStory":
			adminStory( $sid );
			break;

		case "PreviewAdminStory":
			previewAdminStory( $automated, $year, $day, $month, $hour, $min, $subject, $hometext, $bodytext, $images, $imgtext, $delpic, $catid, $topicid, $ihome, $alanguage, $acomm, $notes, $author, $source );
			break;

		case "PostAdminStory":
			postAdminStory( $automated, $year, $day, $month, $hour, $min, $subject, $hometext, $bodytext, $images, $imgtext, $delpic, $catid, $topicid, $ihome, $alanguage, $acomm, $author, $source, $notes );
			break;

		case "RemoveStoriesComment":
			RemoveStoriesComment( $tid, $sid, $ok );
			break;

		case "EditStoriesComment":
			EditStoriesComment( $tid );
			break;

		case "SaveEditStoriesComment":
			SaveEditStoriesComment( $tid, $sid, $sender_name, $sender_email, $sender_url, $com_subject, $com_text );
			break;

		case "EditStoryAuto":
			EditStoryAuto( $anid );
			break;

		case "EditStoryAutoSave":
			EditStoryAutoSave( $anid, $catid, $author, $title, $year, $day, $month, $hour, $min, $hometext, $bodytext, $images, $notes, $ihome, $alanguage, $acomm, $imgtext, $source, $topicid, $delpic, $userfile );
			break;

		case "RemoveStoryAuto":
			RemoveStoryAuto( $anid );
			break;

		case "newsconfig":
			newsconfig();
			break;

		case "newsconfigsave":
			newsconfigsave();
			break;

		case "newsadminhome":
			newsadminhome();
			break;

		case "AddTTD":
			AddTTD();
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