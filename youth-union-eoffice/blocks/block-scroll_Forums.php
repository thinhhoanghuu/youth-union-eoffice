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

if ( (! defined('NV_SYSTEM')) and (! defined('NV_ADMIN')) )
{
	Header( "Location: ../index.php" );
	exit;
}

global $prefix, $user_prefix, $db, $sitename, $bgcolor1, $bgcolor2;


$direction = "up"; // The direction the ShoutBox scrolls by itself. Options: up down

if ( $direction != "up" )
{
	$reversecode = "&nbsp;&nbsp;&nbsp;<a href=\"#\" onMouseover=\"scrollup()\" onMouseout=\"copyspeed=marqueespeed\"><img src=\"images/qshoutblock/up.gif\" border=\"0\" alt=\"\" width=\"9\" height=\"5\"></a>";
	$reversecode .= "&nbsp;&nbsp;&nbsp;<a href=\"#\" onMouseover=\"scrolldoubledown()\" onMouseout=\"copyspeed=marqueespeed\"><img src=\"images/qshoutblock/down.gif\" border=\"0\" alt=\"\" width=\"9\" height=\"5\"></a>";
}
else
{
	$reversecode = "&nbsp;&nbsp;&nbsp;<a href=\"#\" onMouseover=\"scrolldown()\" onMouseout=\"copyspeed=marqueespeed\"><img src=\"images/qshoutblock/down.gif\" border=\"0\" alt=\"\" width=\"9\" height=\"5\"></a>";
	$reversecode .= "&nbsp;&nbsp;&nbsp;<a href=\"#\" onMouseover=\"scrolldoubleup()\" onMouseout=\"copyspeed=marqueespeed\"><img src=\"images/qshoutblock/up.gif\" border=\"0\" alt=\"\" width=\"9\" height=\"5\"></a>";
}
$reversecode .= "&nbsp;&nbsp;&nbsp;<a href=\"#\" onMouseover=\"copyspeed=pausespeed\" onMouseout=\"copyspeed=marqueespeed\"><img src=\"images/qshoutblock/pause.gif\" border=\"0\" alt=\"\" width=\"9\" height=\"5\"></a>&nbsp;&nbsp;&nbsp;";
$top_content = "<table width=\"96%\" border=\"2\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" bgcolor=\"#444444\"><tr><td>";

/**********************************/
/*                                */
/* Configuration parameters       */
/*                                */
/**********************************/
// When set to 1 then Forums permissions which View and/or Read are NOT set to 'ALL' will NOT be displayed in the center block
$HideViewReadOnly = 1;
// Show only 5 last new topics
$Last_New_Topics = 5;
// Icon that is displayed in Center Block in front of Topic
$IconPath = "modules/Forums/templates/subSilver/images/icon_mini_message.gif";

/**********************************/
/*                                */
/* Don't Edit Below !             */
/*                                */
/**********************************/
$border = 0;
$cellspacing = 0;
$cellstyle = "style=\"border-left-width: 1; border-right-width: 1; border-top-width: 1; border-bottom-style: dotted; border-bottom-width: 1\"";

/* Total Amount of Topics */
$result = $db->sql_query( "SELECT * FROM " . $prefix . "_bbtopics" );
$Amount_Of_Topics = $db->sql_numrows( $result );

/* Total Amount of Posts */
$result = $db->sql_query( "SELECT * FROM " . $prefix . "_bbposts" );
$Amount_Of_Posts = $db->sql_numrows( $result );

/* Total Amount of Topic Views */
$Amount_Of_Topic_Views = 0;
$result = $db->sql_query( "SELECT topic_views FROM " . $prefix . "_bbtopics" );
while ( list($topic_views) = $db->sql_fetchrow($result) )
{
	$Amount_Of_Topic_Views = $Amount_Of_Topic_Views + $topic_views;
}

/* Total Amount of Topic Replies */
$Amount_Of_Topic_Replies = 0;
$result = $db->sql_query( "SELECT topic_replies FROM " . $prefix . "_bbtopics" );
while ( list($topic_replies) = $db->sql_fetchrow($result) )
{
	$Amount_Of_Topic_Replies = $Amount_Of_Topic_Replies + $topic_replies;
}

/* Total Amount of Members */
$result = $db->sql_query( "SELECT * FROM " . $user_prefix . "_users" );
$Amount_Of_Members = $db->sql_numrows( $result ) - 1;

/* Last X New Topics */
$Count_Topics = 0;
$Topic_Buffer = "";
$result1 = $db->sql_query( "SELECT topic_id, forum_id, topic_last_post_id, topic_title, topic_poster, topic_views, topic_replies, topic_moved_id, topic_first_post_id FROM " . $prefix . "_bbtopics ORDER BY topic_last_post_id DESC" );
while ( list($topic_id, $forum_id, $topic_last_post_id, $topic_title, $topic_poster, $topic_views, $topic_replies, $topic_moved_id, $topic_first_post_id) = $db->sql_fetchrow($result1) )
{
	$skip_display = 0;
	if ( $HideViewReadOnly == 1 )
	{
		$result5 = $db->sql_query( "SELECT auth_view, auth_read FROM " . $prefix . "_bbforums where forum_id = '$forum_id'" );
		list( $auth_view, $auth_read ) = $db->sql_fetchrow( $result5 );
		if ( ($auth_view != 0) or ($auth_read != 0) )
		{
			$skip_display = 1;
		}
	}

	if ( $topic_moved_id != 0 )
	{
		// Shadow Topic !!
		$skip_display = 1;
	}

	if ( $skip_display == 0 )
	{
		$Count_Topics += 1;
		$result2 = $db->sql_query( "SELECT topic_id, poster_id, FROM_UNIXTIME( post_time,'%d.%m.%Y - %T') as post_time FROM " . $prefix . "_bbposts where post_id = '$topic_last_post_id'" );
		list( $topic_id, $poster_id, $post_time ) = $db->sql_fetchrow( $result2 );

		$result3 = $db->sql_query( "SELECT username, user_id FROM " . $user_prefix . "_users where user_id='$poster_id'" );
		list( $uname, $uid ) = $db->sql_fetchrow( $result3 );
		$LastPoster = "<A HREF=\"modules.php?name=Forums&file=profile&mode=viewprofile&u=$uid\"STYLE=\"text-decoration: none\"> $uname </a>";

		if ( $uname == "Anonymous" )
		{
			$result8 = $db->sql_query( "SELECT post_username FROM " . $user_prefix . "_bbposts where post_id='$topic_last_post_id'" );
			list( $uname ) = $db->sql_fetchrow( $result8 );
			if ( ! $uname == "" )
			{
				$LastPoster = $uname;
			}
			else
			{
				$LastPoster = "Khach";
			}
		}

		$result4 = $db->sql_query( "SELECT username, user_id FROM " . $user_prefix . "_users where user_id='$topic_poster'" );
		list( $uname, $uid ) = $db->sql_fetchrow( $result4 );
		$OrigPoster = "<A HREF=\"modules.php?name=Forums&file=profile&mode=viewprofile&u=$uid\"STYLE=\"text-decoration: none\"> $uname </a>";

		if ( $uname == "Anonymous" )
		{
			$result7 = $db->sql_query( "SELECT post_username FROM " . $user_prefix . "_bbposts where post_id='$topic_first_post_id'" );
			list( $uname ) = $db->sql_fetchrow( $result7 );
			if ( ! $uname == "" )
			{
				$OrigPoster = $uname;
			}
			else
			{
				$OrigPoster = "Khach";
			}
		}

		$result6 = $db->sql_query( "SELECT forum_name FROM " . $prefix . "_bbforums where forum_id = '$forum_id'" );
		list( $forum_name ) = $db->sql_fetchrow( $result6 );
		$TopicForum = "<a href=\"modules.php?name=Forums&amp;file=viewforum&amp;f=$forum_id\"STYLE=\"text-decoration: none\">$forum_name</a>";

		$TopicImage = "<img src=\"$IconPath\" border=\"0\" alt=\"\">";
		$TopicTitleShow = "<a href=\"modules.php?name=Forums&amp;file=viewtopic&amp;p=$topic_last_post_id#$topic_last_post_id\"STYLE=\"text-decoration: none\">$topic_title</a>";

		$Topic_Buffer .= "<tr><td $cellstyle align=\"left\">$TopicImage&nbsp;&nbsp;$TopicTitleShow<br>Chu de: $TopicForum<br>Da xem: $topic_views<br>Tra loi: $topic_replies<br>Tac gia: $OrigPoster<br>Viet: $LastPoster<br><font size=\"-2\"><i>$post_time</i></font></td></tr>";

	}

	if ( $Last_New_Topics == $Count_Topics )
	{
		break 1;
	}

}
$mid_content = "<table width=\"100%\" border=\"$border\"  cellspacing=\"$cellspacing\" bordercolor=\"$bgcolor2\" bgcolor=\"$bgcolor1\">";
$mid_content .= "$Topic_Buffer";
$mid_content .= "</table>";

?>

<script type="text/javascript">
<!--
/*
Original Javascript code by dynamic drive.
Modified javascript code by SuperCat http://www.ourscripts.net
Special thanks to Dilandou for helping me with the PHP and JS mixing.
Cross browser Marquee II- © Dynamic Drive (www.dynamicdrive.com)
For full source code, 100's more DHTML scripts, and TOS, visit http://www.dynamicdrive.com
Credit MUST stay intact
*/

// Start edit speed and size settings

var marqueewidth="100%" //Specify the marquee's width (in pixels) (keep in mind any cell padding and images you may have in your themes).
var marqueeheight="150px" //Specify the marquee's height
var scrollinterval=50 // Specify the refresh rate. This affects speed too. Larger is slower.
var pauseit=1 //Pause marquee onMousever of text area (0=no. 1=yes)?

// End edit speed and size settings

////NO NEED TO EDIT BELOW THIS LINE////////////

var marqueecontent='<p><?php

echo "$mid_content";

?></p>';
var direction='<?php

echo "$direction";

?>';

if (direction=='up') {
// Scroll upwards start
var marqueespeed=1 // Specify speed (larger is faster 1-10) This is the amount of pixel movement per refresh. 1 is best for smoothness.
marqueespeed=(document.all)? marqueespeed : Math.max(1, marqueespeed-1) //slow speed down by 1 for NS
var copyspeed=marqueespeed
var pausespeed=(pauseit==0)? copyspeed: 0
var iedom=document.all||document.getElementById
var actualheight=''
var cross_marquee, ns_marquee

function populate(){
	if (iedom){
		cross_marquee=document.getElementById? document.getElementById("iemarquee") : document.all.iemarquee
		cross_marquee.style.top=parseInt(marqueeheight)+8+"px"
		cross_marquee.innerHTML=marqueecontent
		actualheight=cross_marquee.offsetHeight
	}
	else if (document.layers){
		ns_marquee=document.ns_marquee.document.ns_marquee2
		ns_marquee.top=parseInt(marqueeheight)+8
		ns_marquee.document.write(marqueecontent)
		ns_marquee.document.close()
		actualheight=ns_marquee.document.height
	}
	lefttime=setInterval("scrollmarquee()",scrollinterval)
}
window.onload=populate

function scrollmarquee(){
	if (iedom){
		if (parseInt(cross_marquee.style.top)>(actualheight*(-1)+8)) {
			cross_marquee.style.top=parseInt(cross_marquee.style.top)-copyspeed+"px"
		}
		else {
			cross_marquee.style.top=parseInt(marqueeheight)+8+"px"
		}
	}
	else if (document.layers){
		if (ns_marquee.top>(actualheight*(-1)+8)) {
			ns_marquee.top-=copyspeed
		}
		else {
			ns_marquee.top=parseInt(marqueeheight)+8
		}
	}
}
function scrolldown(){
copyspeed=marqueespeed-3;

}
function scrolldoubleup(){
copyspeed=marqueespeed+3;

}
var txt='';
if (iedom||document.layers){
	with (document){
		if (iedom){
			txt+='<div style="position:relative;width:'+marqueewidth+';height:'+marqueeheight+';overflow:hidden" onMouseover="copyspeed=pausespeed" onMouseout="copyspeed=marqueespeed">'
			txt+='<div id="iemarquee" style="position:absolute;left:0px;top:0px;width:100%;">'
			txt+='</div></div>'
		}
		else if (document.layers){
			txt+='<ilayer width='+marqueewidth+' height='+marqueeheight+' name="ns_marquee">'
			txt+='<layer name="ns_marquee2" width='+marqueewidth+' height='+marqueeheight+' left=0 top=0 onMouseover="copyspeed=pausespeed" onMouseout="copyspeed=marqueespeed"></layer>'
			txt+='</ilayer>'
		}
	}
}
}
// Scroll upwards end


// Scroll downwards start
else {
var marqueespeed=-1 // Specify speed (larger is faster 1-10) This is the amount of pixel movement per refresh. 1 is best for smoothness.
marqueespeed=(document.all)? marqueespeed : -1
var copyspeed=marqueespeed
var pausespeed=(pauseit==0)? copyspeed: 0
var iedom=document.all||document.getElementById
var actualheight=''
var cross_marquee, ns_marquee

function populate(){
	if (iedom){
		cross_marquee=document.getElementById? document.getElementById("iemarquee") : document.all.iemarquee
		cross_marquee.style.top=parseInt(marqueeheight)+8+"px"
		cross_marquee.innerHTML=marqueecontent
		actualheight=cross_marquee.offsetHeight
	}
	else if (document.layers){
		ns_marquee=document.ns_marquee.document.ns_marquee2
		ns_marquee.top=parseInt(marqueeheight)+8
		ns_marquee.document.write(marqueecontent)
		ns_marquee.document.close()
		actualheight=ns_marquee.document.height
	}
	lefttime=setInterval("scrollmarquee()",scrollinterval)
}
window.onload=populate

function scrollmarquee(){
	if (iedom){
		if (parseInt(cross_marquee.style.top)>(actualheight*(-1)+8)) {
			cross_marquee.style.top=parseInt(cross_marquee.style.top)-copyspeed+"px"
		}
		else {
			cross_marquee.style.top=parseInt(marqueeheight)+8+"px"
		}
	}
	else if (document.layers){
		if (ns_marquee.top>(actualheight*(-1)+8)) {
			ns_marquee.top-=copyspeed
		}
		else {
			ns_marquee.top=parseInt(marqueeheight)+8
		}
	}
}
function scrollup(){
copyspeed=marqueespeed+3;

}
function scrolldoubledown(){
copyspeed=marqueespeed-3;

}
var txt='';
if (iedom||document.layers){
	with (document){
		if (iedom){
			txt+='<div style="position:relative;width:'+marqueewidth+';height:'+marqueeheight+';overflow:hidden" onMouseover="copyspeed=pausespeed" onMouseout="copyspeed=marqueespeed">'
			txt+='<div id="iemarquee" style="position:absolute;left:0px;top:0px;width:100%;">'
			txt+='</div></div>'
		}
		else if (document.layers){
			txt+='<ilayer width='+marqueewidth+' height='+marqueeheight+' name="ns_marquee">'
			txt+='<layer name="ns_marquee2" width='+marqueewidth+' height='+marqueeheight+' left=0 top=0 onMouseover="copyspeed=pausespeed" onMouseout="copyspeed=marqueespeed"></layer>'
			txt+='</ilayer>'
		}
	}
}
}
// Scroll downwards end


//-->
</script>

<?

/* Write Table to Screen */

$content = "<script type=\"text/javascript\">document.write(txt);</script>";
$content .= "<tr><td align=\"center\" colspan=\"6\"bgcolor=\"$bgcolor2\">[ <a href=\"modules.php?name=Forums\">" . _BBFORUM_FORUM . "</a> ]&nbsp;&nbsp;&nbsp;[ <a href=\"modules.php?op=modload&name=Forums&file=search\">" . _SEARCH . "</a> ]</center></td></tr>";

?>
