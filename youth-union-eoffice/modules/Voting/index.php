<?php

/*
* @Program:		NukeViet CMS v2.0 RC1
* @File name: 	Module Voting
* @Version: 	2.1
* @Date: 		01.05.2009
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

//Hien thi ket qua tham do` = Cap nhat gia tri binh` chon vao CSDL


/**
 * xemketqua()
 * 
 * @param mixed $pollid
 * @return
 */
function xemketqua( $pollid )
{
	global $db, $prefix, $multilingual, $currentlang, $module_name, $client_ip;
	include_once ( 'header.php' );
	$pollid = intval( $pollid );
	if ( $multilingual == 1 )
	{
		$querylang = "AND planguage='$currentlang'";
	}
	else
	{
		$querylang = "";
	}
	$sql = "SELECT * FROM " . $prefix . "_nvvotings WHERE pollid='$pollid' $querylang";
	$result = $db->sql_query( $sql );
	$row = $db->sql_fetchrow( $result );
	$totalevote = explode( "|", $row['votes'] );
	$option = explode( "|", $row['optiontext'] );
	$total = intval( $row['totalvotes'] );
	$xquestion = explode( '|', $row['question'] );
	$question = $xquestion[0];
	$expire = $xquestion[1];
	$mdate = $row['time'];

	if ( $total > 0 )
	{
		$options = '';
		$showresult = "<p>" . _KETQUATHAMDO . " : <b>" . $question . "</b></p>\n";
		$showresult .= "<table border=\"1\" cellpadding=\"2\" width=\"100%\" cellspacing=\"1\" style=\"border-collapse: collapse\">\n";
		$pollcolor = array( 'RED', 'GREEN', 'YELLOW', 'OGRANGE', 'BLUE', 'OrangeRed', 'Indigo', 'Magenta', 'GreenYellow ', 'GREY', 'BlanchedAlmond ', 'BLACK' );
		for ( $i = 0; $i < intval($row['options']); $i++ )
		{
			$colcolor = $pollcolor[$i];
			if ( ! $colcolor ) $colcolor = $pollcolor[0];
			$totalevote[$i] = intval( $totalevote[$i] );
			$eachopt[$i] = round( ($totalevote[$i] / $total) * 100 );
			$phantram[$i] = "" . round( ($totalevote[$i] / $total) * 100, 2 ) . "%";
			$showresult .= "<tr>\n";
			$showresult .= "<td width=\"40%\" align=\"left\"><b>" . $option[$i] . "</b></td>\n";
			$showresult .= "<td width=\"40%\">";
			if ( $eachopt[$i] != 0 )
			{

				$showresult .= "<table width=\"100%\"><tr><td bgcolor=\"" . $colcolor . "\" width=\"" . $eachopt[$i] . "%\">&nbsp;</td><td>$phantram[$i]</td></tr></table> ";
			}
			else
			{
				$showresult .= "<img border=\"0\" src=\"images/spacer.gif\" width=\"1\" height=\"1\">$phantram[$i]\n";
			}
			$showresult .= "</td>\n";
			$showresult .= "<td align=\"right\" width=\"20%\">";
			$showresult .= $totalevote[$i] . " " . _PHIEU;
			$showresult .= "</td>\n";
			$showresult .= "</tr>\n";
		}

		$showresult .= "<tr>\n";
		$showresult .= "<td width=\"100%\" colspan=\"3\" align=\"right\"><b>" . _TOTALVOTES . "</b>&nbsp;<font color='brown'><b>" . $total . "</b></font></td>\n";
		$showresult .= "</tr>\n";
		$showresult .= "</table>\n";
		$showresult .= "<br>";


	}
	else
	{
		$showresult = "<center><b>" . _WARNING2 . "</b></center>";
	}
	if ( $expire == 0 )
	{
		OpenTable();
		$showresult .= "<center>[ <b>&raquo; <a href=\"modules.php?name=$module_name&op=viewpoll&pollid=$pollid\">" . _VOTING . "</a> &raquo; <a href=\"modules.php?name=$module_name\">" . _POLLS . "</a></b> ]</center>";
		echo $showresult;
		CloseTable();
		if ( $row['acomm'] != 0 )
		{
			echo "<br>";
			OpenTable();
			include ( "modules/$module_name/comments.php" );
			CloseTable();
		}

	}
	else
	{
		$etime = ( ($mdate + $expire) - time() ) / 3600;
		$etime = ( int )$etime;
		if ( $etime < 1 )
		{
			OpenTable();
			$showresult .= "<center>[ <b><a href=\"modules.php?name=$module_name\">" . _POLLS . "</a></b> ]</center>";
			echo "<center><b>" . _WARNING3 . "</b></center><hr>"; //truy cap thang vao poll da qua han
			echo $showresult;
			if ( $row['acomm'] != 0 )
			{
				echo "<br>";
				include ( "modules/$module_name/comments.php" );
			}
			CloseTable();
		}
		else
		{
			OpenTable();
			$showresult .= "<center>[ <b>&raquo; <a href=\"modules.php?name=$module_name&op=viewpoll&pollid=$pollid\">" . _VOTING . "</a> &raquo; <a href=\"modules.php?name=$module_name\">" . _POLLS . "</a></b> ]</center>";
			echo $showresult;
			CloseTable();
			if ( $row['acomm'] != 0 )
			{
				echo "<br>";
				OpenTable();
				include ( "modules/$module_name/comments.php" );
				CloseTable();
			}
		}
	}

	include_once ( 'footer.php' );
	exit();
}

/**
 * pollvote()
 * 
 * @return
 */
function pollvote()
{
	global $db, $prefix, $multilingual, $currentlang, $module_name, $client_ip;
	$pollid = intval( (isset($_POST['pollid'])) ? $_POST['pollid'] : $_GET['pollid'] );
	if ( ! isset($pollid) )
	{
		Header( "Location: index.php" );
		exit();
	}
	if ( $db->sql_numrows($db->sql_query("SELECT * FROM " . $prefix . "_nvvotings WHERE pollid='$pollid'")) == 0 )
	{
		Header( "Location: index.php" );
		exit;
	}
	//	if(isset($_POST['option_id']) AND is_numeric($_POST['option_id'])) {
	if ( isset($_POST['option_id']) )
	{
		$option_id = $_POST['option_id'];
		//$option_id = intval($_POST['option_id']);
		$otionchoice = array();
		$otionchoice = $_POST["option_id"];


		$past = time() - 21600;
		//$past = time();
		$db->sql_query( "DELETE FROM " . $prefix . "_nvvoting_votes WHERE vottime < $past" );

		if ( $db->sql_numrows($db->sql_query("SELECT * FROM " . $prefix . "_nvvoting_votes WHERE pollid='$pollid' AND ip='$client_ip'")) == 0 )
		{
			if ( $multilingual == 1 )
			{
				$querylang = "AND planguage='$currentlang'";
			}
			else
			{
				$querylang = "";
			}
			$sql = "SELECT * FROM " . $prefix . "_nvvotings WHERE pollid='$pollid' $querylang";
			$result = $db->sql_query( $sql );
			$row = $db->sql_fetchrow( $result );
			$totalevote = explode( "|", $row['votes'] );

			$newvotes = array();

			for ( $i = 0; $i < intval($row['options']); $i++ )
			{
				$newvotes[$i] = intval( $totalevote[$i] );
				for ( $j = 0; $j < count($otionchoice); $j++ )
				{
					if ( $i == $otionchoice[$j] )
					{
						$newvotes[$i] = intval( $totalevote[$i] ) + 1;
						echo "update" . $otionchoice[$j] . "<br>";
					}
				}
			}

			$newvotes = implode( "|", $newvotes );

			$db->sql_query( "UPDATE " . $prefix . "_nvvotings SET votes = '$newvotes', totalvotes = totalvotes+1 WHERE pollid = " . $pollid . "" );
			$db->sql_query( "INSERT INTO " . $prefix . "_nvvoting_votes (ip, vottime, pollid) VALUES ('$client_ip', '" . time() . "', '$pollid')" );

			Header( "Location: modules.php?name=$module_name&op=pollvote&pollid=$pollid" );
			exit;
		}
		else
			if ( $db->sql_numrows($db->sql_query("SELECT * FROM " . $prefix . "_nvvoting_votes WHERE pollid='$pollid' AND ip='$client_ip'")) != 0 )
			{
				include_once ( "header.php" );
				//$waittime = viewtime(intval($past),1);
				$sql = "SELECT * FROM " . $prefix . "_nvvoting_votes WHERE pollid='$pollid'";
				$result = $db->sql_query( $sql );
				$row = $db->sql_fetchrow( $result );
				$vottime = $row['vottime'];
				//echo $vottime;
				$waittime = ( (21600 + $vottime) - time() ) / 3600;
				$waittime = ( int )$waittime;
				OpenTable();
				echo "<center><b>" . _ALERTPOLL6 . ": " . $waittime . " " . _ALERTPOLL7 . "</b></center>";
				CloseTable();

				xemketqua( $pollid );
				include_once ( "footer.php" );
			}
			else
			{
				xemketqua( $pollid );
				exit();
			}
	}
	else
	{
		xemketqua( $pollid );
		exit();
	}
}

//Giao dien xem va binh chon 1 tham do
/**
 * viewpoll()
 * 
 * @return
 */
function viewpoll()
{
	global $db, $prefix, $multilingual, $currentlang, $module_name;
	$pollid = intval( (isset($_POST['pollid'])) ? $_POST['pollid'] : $_GET['pollid'] );
	if ( ! isset($pollid) )
	{
		Header( "Location: index.php" );
		exit();
	}
	if ( $db->sql_numrows($db->sql_query("SELECT * FROM " . $prefix . "_nvvotings WHERE pollid='$pollid'")) != 1 )
	{
		Header( "Location: index.php" );
		exit;
	}
	include_once ( 'header.php' );

	if ( $multilingual == 1 )
	{
		$querylang = "AND planguage='$currentlang'";
	}
	else
	{
		$querylang = "";
	}
	$sql = "SELECT * FROM " . $prefix . "_nvvotings WHERE pollid='$pollid' $querylang";
	$result = $db->sql_query( $sql );
	$row = $db->sql_fetchrow( $result );
	$pollid = intval( $row['pollid'] );
	$xquestion = explode( '|', $row['question'] );
	$question = $xquestion[0];
	$option = explode( "|", $row['optiontext'] );
	$options = intval( $row['options'] );
	$acomm = intval( $row['acomm'] );
	$totalvotes = intval( $row['totalvotes'] );
	$totalcomm = intval( $row['totalcomm'] );
	$ttbc = intval( $row['ttbc'] );
	$expire = $xquestion[1];
	$mdate = $row['time'];
	$option_id = array();
	if ( $row )
	{
		echo "<script language=\"javascript\" src=\"" . INCLUDE_PATH . "js/voting.js\"></script>\n";
		$errsm = "'" . _ALERTPOLL8 . "'";
		$formpoll = "<form name=\"frmpoll\" action=\"modules.php?name=" . $module_name . "\" method=\"post\" onsubmit=\"return chkSelect($errsm)\">\n";
		$formpoll .= "<input type=\"hidden\" name=\"pollid\" value=\"" . $pollid . "\">\n";
		$formpoll .= "<font class=\"content\"><b>" . $question . "</b></font><br>(" . _ALERTPOLL5 . ": " . $ttbc . " " . _LVOTES . ")<br><br>\n";
		$formpoll .= "<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\" width=\"100%\">\n";
		$msg = "'" . _ALERTPOLL5 . ": " . $ttbc . " " . _LVOTES . "'";

		///
		for ( $i = 0; $i <= ($options - 1); $i++ )
		{
			if ( isset($options) ) $options = $options . "<br>";
			$formpoll .= "<tr><td valign=\"top\"><input type=\"checkbox\" name=\"option_id[]\" value=\"$i\" class=\"content\" onClick=\"return KeepCount(this.form,$i,$ttbc,$msg)\"> $option[$i]</td></tr>\n";
			//	$formpoll .= "<tr><td valign=\"top\"><input type=\"checkbox\" name=\"option_id[]\" value=\"$i\" class=\"content\"> $option[$i]</td></tr>\n";
		}
		$formpoll .= "<input type=\"hidden\" name=\"op\" value=\"pollvote\">\n";
		$formpoll .= "</table><br><center><font class=\"content\"><input type=\"submit\" value=\"" . _VOTE . "\"></font><br>\n";
		$formpoll .= "<br><font class=\"content\"><a href=\"modules.php?name=" . $module_name . "&amp;op=pollvote&amp;pollid=$pollid\"><b>" . _RESULTS . "</b></a><br><a href=\"modules.php?name=" . $module_name . "\"><b>" . _POLLS . "</b></a><br>\n";
		if ( ($totalcomm != 0) || ($acomm != 0) )
		{
			$formpoll .= "<br>" . _VOTES . ": <b>" . $totalvotes . "</b> <br> " . _PCOMMENTS . " <b>" . $totalcomm . "</b>\n\n";
		}
		else
		{
			$formpoll .= "<br>" . _VOTES . " <b>" . $totalvotes . "</b>\n\n";
		}
		$formpoll .= "</font></center></form>\n\n";
	}
	else
	{
		$formpoll = "<center><b>" . _WARNING2 . "</b></center>";
	}
	if ( $expire == 0 )
	{
		OpenTable();
		echo $formpoll;
		CloseTable();
	}
	else
	{
		$etime = ( ($mdate + $expire) - time() ) / 3600;
		$etime = ( int )$etime;
		if ( $etime < 1 )
		{
			//echo "<center><b>"._WARNING1."</b></center>"; //truy cap thang vao poll da qua han
			xemketqua( $pollid );
		}
		else
		{
			OpenTable();
			echo $formpoll;
			CloseTable();
		}
	}


	include_once ( 'footer.php' );

}

//Giao dien mac dinh -List all polls
/**
 * Pollmain()
 * 
 * @return
 */
function Pollmain()
{
	global $db, $prefix, $adminfile, $adminfold, $multilingual, $currentlang, $module_name;
	include_once ( 'header.php' );
	if ( $multilingual == 1 )
	{
		$querylang = "WHERE planguage='$currentlang'";
	}
	else
	{
		$querylang = "";
	}
	$list_polls = $db->sql_query( "SELECT pollid, question, totalvotes, totalcomm, time FROM " . $prefix . "_nvvotings $querylang ORDER BY time DESC" );
	if ( $db->sql_numrows($list_polls) > 0 )
	{
		OpenTable();
		echo "<center><font class=\"title\"><b>" . _PASTSURVEYS . "</b></font></center>\n";
		CloseTable();
		echo "<br>";
		OpenTable();
		echo "<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\" width=\"100%\">\n";
		while ( $row = $db->sql_fetchrow($list_polls) )
		{
			$pollid = intval( $row['pollid'] );
			//	$question = stripslashes(FixQuotes($row['question']));
			$totalvotes = intval( $row['totalvotes'] );
			$totalcomm = intval( $row['totalcomm'] );
			$xquestion = explode( '|', $row['question'] );
			$question = stripslashes( FixQuotes($xquestion[0]) );
			$expire = $xquestion[1];
			$mdate = $row['time'];
			$time = viewtime( intval($row['time']), 1 );

			if ( $expire == 0 )
			{
				$remain = _UNLIMITED;
				//echo $txtpoll;
				showVoting( $pollid, $question, $time, $remain, $totalvotes, $totalcomm );
			}
			else
			{

				$etime = ( ($mdate + $expire) - time() ) / 3600;
				$etime = ( int )$etime;
				//echo $etime;
				if ( $etime < 1 )
				{
					$remain = "" . _EXPIRED . "";
					//echo $txtpoll;
					showVoting( $pollid, $question, $time, $remain, $totalvotes, $totalcomm );
				}
				else
				{
					$remain = "" . _EXPIREIN . " $etime " . _HOURS . "";
					//echo $txtpoll;
					showVoting( $pollid, $question, $time, $remain, $totalvotes, $totalcomm );
				}
			}

			echo "</tr>";


		} //end while loop
		echo "</table>\n";
		CloseTable();

	}
	else
	{
		title( _CHUACOTHAMDO );
	}
	include ( 'footer.php' );
}

//trinh bay giao dien cho module
/**
 * showVoting()
 * 
 * @param mixed $b1
 * @param mixed $b2
 * @param mixed $b3
 * @param mixed $b4
 * @param mixed $b5
 * @param mixed $b6
 * @return
 */
function showVoting( $b1, $b2, $b3, $b4, $b5, $b6 )
{
	global $adminfold, $adminfile, $module_name;
	$editlink = "<a href=\"" . $adminfold . "/" . $adminfile . ".php?op=poll_edit&pollid=$b1\">" . _EDIT . "</a>";
	$dellink = "<a href=\"" . $adminfold . "/" . $adminfile . ".php?op=poll_del&pollid=$b1\">" . _DELETE . "</a>";
	echo "<tr valign=\"top\" width=\"100%\">\n" . "<td><img border=\"0\" src=\"images/modules/$module_name/icon_poll.gif\" width=\"12\" height=\"10\" title=\"$b2\"></td>\n" . "<td width=\"50%\"><a href=\"modules.php?name=$module_name&op=viewpoll&pollid=$b1\"><b>" . $b2 . "</b></a>\n" . "<br>" . $b3 . " | " . $b4 . "</td>\n" . "<td width=\"15%\"><b>" . $b5 . "</b> " . _VOTES . "</td>\n" . "<td width=\"15%\"><b>" . $b6 . "</b> " . _COMMENTS . "</td>\n" . "<td width=\"10%\"><a href=\"modules.php?name=$module_name&op=pollvote&pollid=$b1\" title=\"" . _MORE . "\">" . _MORE . "</a></td>\n";
	if ( defined('IS_ADMMOD') )
	{
		echo "<td>[" . $editlink . "-" . $dellink . "]</td>\n";
	}
}
switch ( $op )
{

	case "pollvote":
		pollvote();
		break;

	case "viewpoll":
		viewpoll();
		break;

	default:
		Pollmain();
		break;

}

?>
