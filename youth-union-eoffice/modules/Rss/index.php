<?php

/*
* @Program:		NukeViet CMS v2.0 RC1
* @File name: 	Module RSS generation
* @Author: 		laser (Nukeviet Group)
* @Version: 	3.1
* @Date: 		25.06.2009
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
/********************************************/

global $prefix, $db, $sitename, $currentlang, $admin, $multilingual, $module_name, $admin_file, $nukeurl;
include ( "header.php" );
$iconrss = "<img  style=\"border-width: 0px; vertical-align: middle;\" src=\"images/rss.gif\">";
$imgmid = "<img  style=\"border-width: 0px; vertical-align: middle;\" src=\"images/line1.gif\">";
$imgmid2 = "<img  style=\"border-width: 0px; vertical-align: middle;\" src=\"images/line3.gif\">";
$imgbottom = "<img  style=\"border-width: 0px; vertical-align: middle;\" src=\"images/line2.gif\">";
$bookmarkrss = "<img  style=\"border-width: 0px; vertical-align: middle;\" src=\"images/bookmarkrss.gif\">";
$feedurl = "http://www.addthis.com/feed.php?h1=";

Opentable();
echo ( "<center><h2>" . _NJMAP . " $sitename</h2>" . _NJMAP1 . " $sitename " . _NJMAP2 . "</center><br>" );
echo ( "<b><u>" . _Key . "</u></b><br>" );
echo ( "" . $imgmid2 . "" . $iconrss . " " . _Key1 . "<br>" );
echo ( "&nbsp; &nbsp; &nbsp;" . $imgmid2 . "" . $iconrss . " " . _Key2 . "<br>" );
echo ( "&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; " . $imgbottom . "" . $iconrss . " " . _Key3 . "<br>" );
echo ( "&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;" . $imgbottom . "" . $bookmarkrss . " " . _Key5 . "<br>" );
echo ( "<li class=MsoNormal>" . _Key6 . "<br></li>" );

Closetable();
echo "<br>";

OpenTable();
echo "<P><h3>" . _RSSABOUT . "</h3></p>";
echo "<p>" . _RSSABOUT1 . "<br><br>" . _RSSABOUT2 . "<br><br>" . _RSSABOUT3 . "<br><br>" . _RSSHELP7 . "</p>";

CloseTable();
echo "<br>";

OpenTable();
echo "";
echo "<table>";
echo "<tr><td style=\"padding-left: 50px;\" width=\"90%\">";
// begin
echo "<img  style=\"border-width: 0px; vertical-align: middle;\" src=\"images/home.gif\"><b>" . _HOMERSS . "</b><br>\n";
echo "</td></tr>";
echo "<tr><td style=\"padding-left: 65px;\" width=\"90%\">";

$result2 = $db->sql_query( "SELECT title, custom_title, view FROM " . $prefix . "_modules WHERE active=1 AND view<2 ORDER BY custom_title" );
while ( $row2 = $db->sql_fetchrow($result2) )
{
	$titolomodulo = stripslashes( $row2['custom_title'] );
	$link = $row2['title'];
	$permesso = $row2['view'];
	if ( file_exists("" . INCLUDE_PATH . "modules/" . $link . "/rss.php") )
	{
		echo "" . $imgmid2 . "<a href=\"modules.php?name=$link&amp;file=rss\">" . $iconrss . "</a>";
		echo " <a target=\"_blank\" href=\"" . $feedurl . "" . $nukeurl . "/modules.php%3Fname%3D" . $link . "%26file%3Drss\">" . $bookmarkrss . "</a>";
		echo " <a href=\"modules.php?name=$link\">$titolomodulo</a><br>";

		switch ( $link )
		{
			case 'Files':
				$result3 = $db->sql_query( "SELECT cid, title FROM " . $prefix . "_files_categories where parentid=0 order by title, parentid" );
				while ( $row3 = $db->sql_fetchrow($result3) )
				{
					$titolodown = $row3['title'];
					$cid1 = $row3['cid'];
					echo "" . $imgmid . "" . $imgmid2 . "<a href=\"modules.php?name=Files&amp;file=rss&amp;cid=$cid1\">" . $iconrss . "</a>";
					echo " <a target=\"_blank\" href=\"" . $feedurl . "" . $nukeurl . "/modules.php%3Fname%3D" . $link . "%26file%3Drss%26cid%3D$cid1\">" . $bookmarkrss . "</a>";
					echo " <a href=\"modules.php?name=Files&amp;go=cat&amp;cid=$cid1\">$titolodown</a><br>";
					$result4 = $db->sql_query( "SELECT cid, title FROM " . $prefix . "_files_categories WHERE parentid=$cid1 ORDER BY title" );
					while ( $row4 = $db->sql_fetchrow($result4) )
					{
						$titolodown2 = $row4['title'];
						$cid2 = $row4['cid'];
						echo "" . $imgmid . "" . $imgmid . "" . $imgmid2 . "<a href=\"modules.php?name=Files&amp;file=rss&amp;cid=$cid2\">" . $iconrss . "</a>";
						echo " <a target=\"_blank\" href=\"" . $feedurl . "" . $nukeurl . "/modules.php%3Fname%3D" . $link . "%26file%3Drss%26cid%3D$cid2\">" . $bookmarkrss . "</a>";
						echo " <a href=\"modules.php?name=Files&amp;go=cat&amp;cid=$cid2\">" . $titolodown2 . "</a><br>";
					}
				}
				break;

			case 'Forums':
				$result5 = $db->sql_query( "SELECT cat_id, cat_title FROM " . $prefix . "_bbcategories ORDER BY cat_order" );
				while ( $row5 = $db->sql_fetchrow($result5) )
				{
					$titolocatf = $row5['cat_title'];
					$cat_id = $row5['cat_id'];
					echo "" . $imgmid . "" . $imgmid2 . "<a href=\"modules.php?name=Forums&amp;file=rss&amp;c=$cat_id\">" . $iconrss . "</a>";
					echo " <a target=\"_blank\" href=\"" . $feedurl . "" . $nukeurl . "/modules.php%3Fname%3DForums%26file%3Drss%26c=$cat_id\">" . $bookmarkrss . "</a>";
					echo " <a href=\"modules.php?name=Forums&amp;file=index&amp;c=$cat_id\">$titolocatf</a><br>";
					$result6 = $db->sql_query( "SELECT forum_name, forum_id, auth_view, auth_read FROM " . $prefix . "_bbforums WHERE cat_id=$cat_id AND auth_view<2 AND auth_read<2 ORDER BY forum_order" );
					while ( $row6 = $db->sql_fetchrow($result6) )
					{
						$titoloforum = $row6['forum_name'];
						$fid = $row6['forum_id'];
						$auth_view = $row6['auth_view'];
						$auth_read = $row6['auth_read'];
						echo "" . $imgmid . "" . $imgmid . "" . $imgmid2 . "<a href=\"modules.php?name=Forums&amp;file=rss&amp;f=$fid\">" . $iconrss . "</a>";
						echo " <a target=\"_blank\" href=\"" . $feedurl . "" . $nukeurl . "/modules.php%3Fname%3DForums%26file%3Drss%26f=$fid\">" . $bookmarkrss . "</a>";
						echo " <a href=\"modules.php?name=Forums&amp;file=viewforum&amp;f=$fid\">$titoloforum</a><br>";
					}
				}
				break;

			case 'Nvmusic':
				$result7 = $db->sql_query( "select id,ten from " . $prefix . "_nvmusic_cat order by ten" );
				while ( $row7 = $db->sql_fetchrow($result7) )
				{
					$musicid = $row7['id'];
					$musicten = $row7['ten'];
					echo "" . $imgmid . "" . $imgmid2 . "<a href=\"modules.php?name=Nvmusic&amp;file=rss&amp;id=$musicid\">" . $iconrss . "</a>";
					echo " <a target=\"_blank\" href=\"" . $feedurl . "" . $nukeurl . "/modules.php%3Fname%3DNvmusic%26file%3Drss%26id%3D$musicid\">" . $bookmarkrss . "</a>";
					echo " <a href=\"modules.php?name=Nvmusic&amp;op=viewcat&amp;id=$musicid\"> $musicten</a><br>";

				}
				break;

			case 'Web_Links':
				$result8 = $db->sql_query( "SELECT cid, title from " . $prefix . "_links_categories where parentid=0 order by title" );
				while ( $row8 = $db->sql_fetchrow($result8) )
				{
					$titololink = $row8['title'];
					$cid1 = $row8['cid'];
					echo " " . $imgmid . "" . $imgmid2 . "<a href=\"modules.php?name=Web_Links&amp;file=rss&amp;cid=$cid1\">" . $iconrss . "</a>";
					echo " <a target=\"_blank\" href=\"" . $feedurl . "" . $nukeurl . "/modules.php%3Fname%3DWeb_Links%26file%3Drss%26cid%3D$cid1\">" . $bookmarkrss . "</a>";
					echo " <a href=\"modules.php?name=Web_Links&amp;l_op=viewlink&amp;cid=$cid1\"> $titololink</a><br>";

				}
				break;

			case 'Photos':
				$result9 = $db->sql_query( "SELECT id,name FROM " . $prefix . "_gallery_categories where pid=0 ORDER BY name,id" );
				while ( $row9 = $db->sql_fetchrow($result9) )
				{
					$topiclink = $row9['name'];
					$cidtopic = $row9['id'];
					echo "" . $imgmid . "" . $imgmid2 . "<a href=\"modules.php?name=Photos&amp;file=rss&amp;cid=$cidtopic\">" . $iconrss . "</a>";
					echo " <a target=\"_blank\" href=\"" . $feedurl . "" . $nukeurl . "/modules.php%3Fname%3DPhotos%26file%3Drss%26cid%3D$cidtopic\">" . $bookmarkrss . "</a>";
					echo " <a href=\"modules.php?name=Photos&amp;cid=$cidtopic\">$topiclink</a><br>";
					$result9a = $db->sql_query( "SELECT id,name FROM " . $prefix . "_gallery_categories where pid=$cidtopic ORDER BY name,id" );
					while ( $row9a = $db->sql_fetchrow($result9a) )
					{
						$topiclink2 = $row9a['name'];
						$cidtopic2 = $row9a['id'];
						echo "" . $imgmid . "" . $imgmid . "" . $imgmid2 . "<a href=\"modules.php?name=Photos&amp;file=rss&amp;cid=$cidtopic2\">" . $iconrss . "</a>";
						echo "<a target=\"_blank\" href=\"" . $feedurl . "" . $nukeurl . "/modules.php%3Fname%3DPhotos%26file%3Drss%26cid%3D$cidtopic2\">" . $bookmarkrss . "</a>";
						echo "<a href=\"modules.php?name=Photos&amp;cid=$cidtopic2\"> $topiclink2</a><br>";

					}
				}
				break;

			case 'News':
				$result10 = $db->sql_query( "SELECT title,catid FROM " . $prefix . "_stories_cat where parentid=0 order by title,parentid" );
				while ( $row10 = $db->sql_fetchrow($result10) )
				{
					$newslink = $row10['title'];
					$cidnews = $row10['catid'];
					echo "" . $imgmid . "" . $imgmid2 . "<a href=\"modules.php?name=News&amp;file=rss&amp;catid=" . $cidnews . "&amp;lang=" . $currentlang . "\">" . $iconrss . "</a>";
					echo " <a target=\"_blank\" href=\"" . $feedurl . "" . $nukeurl . "/modules.php%3Fname%3DNews%26file%3Drss%26catid%3D" . $cidnews . "%26lang%3D" . $currentlang . "\">" . $bookmarkrss . "</a>";
					echo " <a href=\"modules.php?name=News&amp;op=viewcat&amp;catid=$cidnews\">$newslink</a><br>";
					$result10a = $db->sql_query( "SELECT title,catid FROM " . $prefix . "_stories_cat where parentid=$cidnews order by title,parentid" );
					while ( $row10a = $db->sql_fetchrow($result10a) )
					{
						$newslink2 = $row10a['title'];
						$cidnews2 = $row10a['catid'];
						echo "" . $imgmid . "" . $imgmid . "" . $imgmid2 . "<a href=\"modules.php?name=News&amp;file=rss&amp;catid=" . $cidnews2 . "&amp;lang=" . $currentlang . "\">" . $iconrss . "</a>";
						echo " <a target=\"_blank\" href=\"" . $feedurl . "" . $nukeurl . "/modules.php%3Fname%3DNews%26file%3Drss%26catid%3D" . $cidnews2 . "%26lang%3D" . $currentlang . "\">" . $bookmarkrss . "</a>";
						echo " <a href=\"modules.php?name=News&amp;op=viewcat&amp;catid=$cidnews2\">$newslink2</a><br>";
					}
				}
				break;

			case 'Weblinks':
				$result11 = $db->sql_query( "SELECT id,title from " . $prefix . "_weblinks_cats order by title,id" );
				while ( $row11 = $db->sql_fetchrow($result11) )
				{
					$titlink = $row11['title'];
					$id1 = $row11['id'];
					echo " " . $imgmid . "" . $imgmid2 . "<a href=\"modules.php?name=Weblinks&amp;file=rss&amp;viewcat=$id1\">" . $iconrss . "</a>";
					echo " <a target=\"_blank\" href=\"" . $feedurl . "" . $nukeurl . "/modules.php%3Fname%3DWeblinks%26file%3Drss%26viewcat%3D$id1\">" . $bookmarkrss . "</a>";
					echo " <a href=\"modules.php?name=Weblinks&amp;viewcat=$id1\">$titlink</a><br>";

				}
				break;
				// 08-02-2009
			case 'Jokes':
				$result11 = $db->sql_query( "SELECT id,title from " . $prefix . "_jokes_cat order by title,id" );
				while ( $row11 = $db->sql_fetchrow($result11) )
				{
					$titlink = $row11['title'];
					$id1 = $row11['id'];
					echo "" . $imgmid . "" . $imgmid2 . "<a href=\"modules.php?name=Jokes&amp;file=rss&amp;catid=$id1\">" . $iconrss . "</a>";
					echo " <a target=\"_blank\" href=\"" . $feedurl . "" . $nukeurl . "/modules.php%3Fname%3DWeblinks%26file%3Drss%26viewcat%3D$id1\">" . $bookmarkrss . "</a>";
					echo " <a href=\"modules.php?name=Jokes&amp;op=ViewCat&amp;catid=$id1\">$titlink</a><br>";

				}
				break;

			case 'Laws':
				$result11 = $db->sql_query( "SELECT id,title from " . $prefix . "_nvalaws where type=0 order by title,id" );
				while ( $row11 = $db->sql_fetchrow($result11) )
				{
					$titlink = $row11['title'];
					$id1 = $row11['id'];
					echo "" . $imgmid . "" . $imgmid . "" . $imgmid2 . "<a href=\"modules.php?name=Laws&amp;file=rss&amp;catid=$id1\">" . $iconrss . "</a>";
					echo " <a target=\"_blank\" href=\"" . $feedurl . "" . $nukeurl . "/modules.php%3Fname%3DLaws%26file%3Drss%26catid%3D$id1\">" . $bookmarkrss . "</a>";
					echo " <a href=\"modules.php?name=Laws&amp;op=alist&amp;catid=$id1\">$titlink</a><br>";
				}
				$result11 = $db->sql_query( "SELECT id,title from " . $prefix . "_nvalaws where type=1 order by title,id" );
				while ( $row11 = $db->sql_fetchrow($result11) )
				{
					$titlink = $row11['title'];
					$id1 = $row11['id'];
					echo "" . $imgmid . "" . $imgmid . "" . $imgmid2 . "<a href=\"modules.php?name=Laws&amp;file=rss&amp;catid=$id1\">" . $iconrss . "</a>";
					echo " <a target=\"_blank\" href=\"" . $feedurl . "" . $nukeurl . "/modules.php%3Fname%3DLaws%26file%3Drss%26catid%3D$id1\">" . $bookmarkrss . "</a>";
					echo " <a href=\"modules.php?name=Laws&amp;op=alist&amp;catid=$id1\">$titlink</a><br>";
				}
				$result11 = $db->sql_query( "SELECT id,title from " . $prefix . "_nvalaws where type=2 order by title,id" );
				while ( $row11 = $db->sql_fetchrow($result11) )
				{
					$titlink = $row11['title'];
					$id1 = $row11['id'];
					echo "" . $imgmid . "" . $imgmid . "" . $imgmid2 . "<a href=\"modules.php?name=Laws&amp;file=rss&amp;catid=$id1\">" . $iconrss . "</a>";
					echo " <a target=\"_blank\" href=\"" . $feedurl . "" . $nukeurl . "/modules.php%3Fname%3DLaws%26file%3Drss%26catid%3D$id1\">" . $bookmarkrss . "</a>";
					echo " <a href=\"modules.php?name=Laws&amp;op=alist&amp;catid=$id1\">$titlink</a><br>";
				}
				break;
			case 'Albums':
				$result11 = $db->sql_query( "SELECT catid,title from " . $prefix . "_album_cat order by title,catid" );
				while ( $row11 = $db->sql_fetchrow($result11) )
				{
					$titlink = $row11['title'];
					$id1 = $row11['catid'];
					echo "" . $imgmid . "" . $imgmid2 . "<a href=\"modules.php?name=Albums&amp;file=rss&amp;catid=$id1\">" . $iconrss . "</a>";
					echo " <a target=\"_blank\" href=\"" . $feedurl . "" . $nukeurl . "/modules.php%3Fname%3DAlbums%26file%3Drss%26catid%3D$id1\">" . $bookmarkrss . "</a>";
					echo " <a href=\"modules.php?name=Albums&amp;op=viewcat&amp;catid=$id1\">$titlink</a><br>";

				}
				break;

			case 'Support':
				$result12 = $db->sql_query( "SELECT title, id FROM " . $prefix . "_nvsupport_cat where (subid='0' AND active='1') order by title, id" );
				while ( $row12 = $db->sql_fetchrow($result12) )
				{
					$newslink = $row12['title'];
					$cidnews = $row12['id'];
					echo "" . $imgmid . "" . $imgmid2 . "<a href=\"modules.php?name=Support&amp;file=rss&amp;cat=" . $cidnews . "&amp;lang=" . $currentlang . "\">" . $iconrss . "</a>";
					echo " <a target=\"_blank\" href=\"" . $feedurl . "" . $nukeurl . "/modules.php%3Fname%3DSupport%26file%3Drss%26cat%3D" . $cidnews . "%26lang%3D" . $currentlang . "\">" . $bookmarkrss . "</a>";
					echo " <a href=\"modules.php?name=Support&amp;op=viewcat&amp;cat=$cidnews\">$newslink</a><br>";
					$result12a = $db->sql_query( "SELECT title, id FROM " . $prefix . "_stories_cat where (subid='" . $cidnews . "' AND active='1') order by title, id" );
					while ( $row10a = $db->sql_fetchrow($result12a) )
					{
						$newslink2 = $row12a['title'];
						$cidnews2 = $row12a['id'];
						echo "" . $imgmid . "" . $imgmid . "" . $imgmid2 . "<a href=\"modules.php?name=Support&amp;file=rss&amp;cat=" . $cidnews2 . "&amp;lang=" . $currentlang . "\">" . $iconrss . "</a>";
						echo " <a target=\"_blank\" href=\"" . $feedurl . "" . $nukeurl . "/modules.php%3Fname%3DSupport%26file%3Drss%26cat%3D" . $cidnews2 . "%26lang%3D" . $currentlang . "\">" . $bookmarkrss . "</a>";
						echo " <a href=\"modules.php?name=Support&amp;op=viewcat&amp;cat=$cidnews2\">$newslink2</a><br>";
					}
				}
				break;
		}
	}
}
echo "</td></tr></table>";
CloseTable();
echo "<br>";

OpenTable();
echo "<p><h2>" . _RSSHELP . "</h2>" . _RSSHELP1 . "<br><br>" . _RSSHELP2 . "<br><br>" . _RSSHELP3 . "" . _RSSHELP4 . "<br><br>" . _RSSHELP5 . "<br><br>" . _RSSHELP6 . "</p>";
CloseTable();
echo "<br>";

OpenTable();
// chen noi dung file co ten note.htm
if ( file_exists("modules/" . $module_name . "/note.htm") )
{
	title( "" . $module_name . "<hr width=300>" );
	include ( "modules/" . $module_name . "/note.htm" );
}
else
{
	echo "<center>[RSS generation 3.0 for NukeViet 2.0]</center>";
}
CloseTable();

include ( "footer.php" );

?>