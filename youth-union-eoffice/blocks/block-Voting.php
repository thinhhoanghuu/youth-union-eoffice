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

global $db, $prefix, $multilingual, $currentlang;
get_lang( Voting );
if ( $multilingual == 1 )
{
	$querylang = "WHERE planguage='$currentlang'";
}
else
{
	$querylang = "";
}
$sql = "SELECT * FROM " . $prefix . "_nvvotings $querylang ORDER BY pollid DESC LIMIT 1";
$result = $db->sql_query( $sql );
$row = $db->sql_fetchrow( $result );
$pollid = intval( $row['pollid'] );
$option = explode( "|", $row['optiontext'] );
$options = $row['options'];
$xquestion = explode( '|', $row['question'] );
$question = $xquestion[0];
$expire = $xquestion[1];
$mdate = $row['time'];
echo "<script language=\"javascript\" src=\"" . INCLUDE_PATH . "js/voting.js\"></script>\n";
if ( $pollid == 0 || $pollid == "" )
{
	$content = "";
}
else
{
	$errsm = "'" . _ALERTPOLL8 . "'";
	$content1 .= "<form id=\"frmnpoll\" name=\"frmpoll\" action=\"" . INCLUDE_PATH . "modules.php?name=Voting\" method=\"post\" onsubmit=\"return chkSelect($errsm)\">";
	$content1 .= "<input type=\"hidden\" name=\"pollid\" value=\"" . $pollid . "\">";
	$content1 .= "<font class=\"content\"><b>" . $question . "</b></font><br><br>\n";
	$content1 .= "<table border=\"0\" width=\"100%\">";
	$ttbc = intval( $row['ttbc'] );
	$msg = "'" . _ALERTPOLL5 . ": " . $ttbc . "'";
	for ( $i = 0; $i <= $options - 1; $i++ )
	{
		if ( isset($options) ) $options = $options . "<br>";
		$content1 .= "<tr><td valign=\"top\"><input type=\"checkbox\" name=\"option_id[]\" value=\"$i\" class=\"content\" onClick=\"return KeepCount(this.form,$i,$ttbc,$msg)\">&nbsp;" . $option[$i] . "</td></tr>\n";
	}
	$content1 .= "<input type=\"hidden\" name=\"op\" value=\"pollvote\">";
	$content1 .= "</table><br><center><input class=\"button\" type=\"submit\" value=\"" . _VOTE . "\"><br>";
	$content1 .= "<br><font class=\"content\"><a href=\"" . INCLUDE_PATH . "modules.php?name=Voting&amp;op=pollvote&amp;pollid=$pollid\"><b>" . _RESULTS . "</b></a><br><a href=\"" . INCLUDE_PATH . "modules.php?name=Voting\"><b>" . _POLLS . "</b></a><br>";

	if ( ($row['totalcomm'] != 0) || ($row['acomm'] != 0) )
	{
		$content1 .= "<br>" . _VOTING . ": <b>" . $row['totalvotes'] . "</b><br> " . _PCOMMENTS . " <b>" . $row['totalcomm'] . "</b>\n\n";
	}
	else
	{
		$content1 .= "<br>" . _VOTING . " <b>" . $row['totalvotes'] . "</b>\n\n";
	}
	$content1 .= "</font></center></form>\n\n";
	if ( $expire == 0 )
	{
		$content = $content1;
	}
	else
	{
		$etime = ( ($mdate + $expire) - time() ) / 3600;
		$etime = ( int )$etime;
		if ( $etime < 1 )
		{
		}
		else
		{
			$content = $content1;
		}
	}
}

?>