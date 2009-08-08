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

$checkmodname = "Voting";
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
	define( "IMG_PATH", "../images/modules/" . $checkmodname . "/" );


	/**
	 * formatInput()
	 * 
	 * @param mixed $input
	 * @return
	 */
	function formatInput( $input )
	{
		return addslashes( htmlspecialchars(trim($input)) );
	}

	/**
	 * acomm()
	 * 
	 * @param mixed $acomm
	 * @return
	 */
	function acomm( $acomm )
	{
		echo "<tr><td><b>" . _ACTIVATECOMMENTS . "</b></td><td>";
		echo "<select name=\"xacomm\">";
		$yacomm = array( _NO, _ALL, _MEMBER );
		for ( $a = 0; $a <= 2; $a++ )
		{
			$seld = "";
			if ( $a == $acomm )
			{
				$seld = " selected";
			}
			echo "<option name=\"xacomm\" value=\"$a\" $seld>$yacomm[$a]</option>\n";
		}
		echo "</select></td></tr>";

	}

	/**
	 * aExpirate()
	 * 
	 * @param mixed $aExpirate
	 * @return
	 */
	function aExpirate( $aExpirate )
	{
		echo "<tr><td><b>" . _EXPIRATION . ":</b></td><td>";
		echo "<select name=\"add_expire\">";
		$yacomm = array( 86400, 172800, 432000, 1296000, 2592000, 0 );
		for ( $a = 0; $a <= 5; $a++ )
		{
			$seld = "";
			if ( $yacomm[$a] == $aExpirate )
			{
				$seld = " selected";
			}
			$expvalue = $yacomm[$a] / 86400;
			if ( $expvalue > 1 )
			{
				$expvalue = $expvalue . " " . _DAYS;
			} elseif ( $expvalue == 1 )
			{
				$expvalue = $expvalue . " " . _DAY;
			} elseif ( $expvalue == 0 )
			{
				$expvalue = _UNLIMITED;
			}
			echo "<option value=\"$yacomm[$a]\" $seld>$expvalue</option>\n";
		}
		echo "</select></td></tr>";
	}
	/**
	 * vshow()
	 * 
	 * @param mixed $b1
	 * @param mixed $b2
	 * @param mixed $b3
	 * @param mixed $b4
	 * @param mixed $b5
	 * @param mixed $b6
	 * @param mixed $b7
	 * @param mixed $b8
	 * @param mixed $b9
	 * @param mixed $colourtxt
	 * @return
	 */
	function vshow( $b1, $b2, $b3, $b4, $b5, $b6, $b7, $b8, $b9, $colourtxt )
	{
		global $checkmodname, $adminfile;
		echo "<tr valign=\"top\">" . "<td><a href=\"../modules.php?name=$checkmodname&op=viewpoll&pollid=$b9\"><b><font color=$colourtxt>" . $b1 . "</font></b></a>" . "<br><font color=$colourtxt>" . $b2 . " | " . $b3 . "</font>" . "</td>" . "<td><b><font color=$colourtxt>" . $b4 . "</b> " . _VOTES . "</font></td>" . "<td><b><font color=$colourtxt>" . $b5 . "</b> " . _COMMENTS . "</font>";
		if ( $b5 != 0 )
		{
			echo "<br>[<a href=\"" . $adminfile . ".php?op=showlistcomm&pollid=$b9\">" . _MANAGERCOMM . "</a>]";
		}
		echo "</td>" . "<td><a href=\"../modules.php?name=$checkmodname&op=pollvote&pollid=$b9\" title=\"" . _MORE . "\">" . "<font color=$colourtxt>" . _MORE . "</font></a></td>" . "<td width=\"20\"></td>" . "<td>[<b>" . $b6 . " - " . $b7 . " - " . $b8 . "</b>]</td>" . "</tr>" . "<tr><td colspan=6><hr></td></tr>";

	}
	/**
	 * polls()
	 * 
	 * @return
	 */
	function polls()
	{
		global $adminfile, $language, $multilingual, $prefix, $db, $checkmodname, $currentlang;
		include ( '../header.php' );
		GraphicAdmin();
		title( "<a href=\"" . $adminfile . ".php?op=polls\">" . _VOTINGADMIN . "</a>" );
		$list_polls = $db->sql_query( "SELECT * FROM " . $prefix . "_nvvotings ORDER BY time,planguage DESC" );
		if ( $db->sql_numrows($list_polls) > 0 )
		{
			OpenTable();
			echo "<center><font class=\"option\"><b>" . _OLDPOLLS . "</b></font><br>";
			echo "<table border=\"0\" cellpadding=\"2\">";
			while ( $row = $db->sql_fetchrow($list_polls) )
			{
				$pollid = intval( $row['pollid'] );
				$xquestion = explode( '|', $row['question'] );
				$question = $xquestion[0];
				$pexpire = $xquestion[1];
				$totalvotes = $row['totalvotes'];
				$totalcomm = $row['totalcomm'];
				$time = viewtime( $row['time'], 1 );
				$planguage = $row['planguage'];
				$mdate = $row['time'];
				$ttbc = $row['ttbc'];
				$colortxt = "000000";
				$actvlink = "<a href=\"" . $adminfile . ".php?op=poll_status&pollid=$pollid\"><font color=$colortxt>" . _DEACTIVATE . "</font></a>";
				$editlink = "<a href=\"" . $adminfile . ".php?op=poll_edit&pollid=$pollid\"><font color=$colortxt>" . _EDIT . "</font></a>";
				$dellink = "<a href=\"" . $adminfile . ".php?op=poll_del&pollid=$pollid\"><font color=$colortxt>" . _DELETE . "</font></a>";
				if ( $pexpire == 0 )
				{
					$pexpiretxt = _UNLIMITED;
					$pexpiretxt = _EXPIRATION . ": " . $pexpiretxt;
					vshow( $question, $time, $pexpiretxt, $totalvotes, $totalcomm, $actvlink, $editlink, $dellink, $pollid, $colortxt );
				}
				else
				{

					$etime = ( ($mdate + $pexpire) - time() ) / 3600;
					$etime = ( int )$etime;
					if ( $etime < 1 )
					{
						$pexpiretxt = _EXPIRED;
						$colortxt = "BDBDBD";
						$actvlink = "<a href=\"" . $adminfile . ".php?op=poll_status&pollid=$pollid\"><font color=$colortxt>" . _ACTIVATE . "</font></a>";
						$editlink = "<a href=\"" . $adminfile . ".php?op=poll_edit&pollid=$pollid\"><font color=$colortxt>" . _EDIT . "</font></a>";
						$dellink = "<a href=\"" . $adminfile . ".php?op=poll_del&pollid=$pollid\"><font color=$colortxt>" . _DELETE . "</font></a>";
						vshow( $question, $time, $pexpiretxt, $totalvotes, $totalcomm, $actvlink, $editlink, $dellink, $pollid, $colortxt );
					}
					else
					{
						$colortxt = "000000";
						$pexpiretxt = "" . _EXPIREIN . " $etime " . _HOURS . "";
						vshow( $question, $time, $pexpiretxt, $totalvotes, $totalcomm, $actvlink, $editlink, $dellink, $pollid, $colortxt );
					}
				}


			}

			echo "</table></center>\n";

			CloseTable();
		}
		else
		{
			title( _CHUACOTHAMDO );

		}

		echo "<br>";

		OpenTable();
		echo "<center><font class=\"option\"><b>" . _CREATEPOLL . "</b></font></center><br>" . "<form action=\"" . $adminfile . ".php\" method=\"post\">";
		echo "<table border=\"0\" cellpadding=\"2\" width=\"60%\" cellspacing=\"1\" align=\"center\">";
		acomm( 2 );
		if ( $multilingual == 1 )
		{
			echo "<TR><td><b>" . _LANGUAGE . ":</b></td>" . "<td><select name=\"planguage\">";
			echo select_language( $currentlang );
			echo "</select></td></tr>";
		}
		else
		{
			echo "<input type=\"hidden\" name=\"planguage\" value=\"$currentlang\">";
		}
		aExpirate( 432000 );
		echo "<tr><td>" . _NUMPOLLS . "</td><td><select name=\"numopt\">";

		for ( $i = 1; $i < 21; $i++ )
		{

			echo "<option value=\"$i\"";
			if ( $i == 5 ) echo " selected";
			echo ">$i</option>\n";
		}

		echo "</select></td></tr>";

		echo "<tr><td>" . _NUMPOLLSSELECT . "</td><td><select name=\"ttbc\">";


		for ( $i = 1; $i < 21; $i++ )
		{

			echo "<option value=\"$i\"";
			if ( $i == 1 ) echo " selected";
			echo ">$i</option>\n";
		}

		echo "</select></td></tr>";

		echo "<tr>" . "  <td  align=\"center\"><input type=\"hidden\"  name=\"op\" value=\"poll_creat_step2\">" . "  <input type=\"submit\" value=\"" . _CONTINUE . "\">" . "  </td></tr>" . "  </table>" . "</form><br>";


		CloseTable();
		include ( '../footer.php' );
	}

	/**
	 * poll_creat_step2()
	 * 
	 * @param mixed $xacomm
	 * @param mixed $planguage
	 * @param mixed $numopt
	 * @param mixed $add_expire
	 * @param mixed $ttbc
	 * @return
	 */
	function poll_creat_step2( $xacomm, $planguage, $numopt, $add_expire, $ttbc )
	{

		global $adminfile, $language, $admin, $multilingual, $prefix, $db;
		include_once ( '../header.php' );
		GraphicAdmin();
		title( "<a href=\"" . $adminfile . ".php?op=polls\">" . _VOTINGADMIN . "</a>" );
		OpenTable();
		echo "<center>" . _CREATNOTE . "</center>";
		echo "<center><form action=\"" . $adminfile . ".php\" method=\"post\">";
		echo "<input type=\"hidden\" name=\"planguage\" value=\"$planguage\"><br>";
		echo "<input type=\"hidden\" name=\"xacomm\" value=\"$xacomm\"><br>";
		echo "<input type=\"hidden\" name=\"xadd_expire\" value=\"$add_expire\"><br>";
		echo "<input type=\"hidden\" name=\"ttbc\" value=\"$ttbc\"><br>";
		echo "<table border=\"0\" cellpadding=\"2\" cellspacing=\"1\" align=\"center\">" . "  <TR>" . "	<TD>" . _QUESTION . "</TD>" . "	<TD><input type=\"text\"  name=\"npollquestion\" size=\"50\"></TD>" . "  </TR>";
		$newpollopt = "";
		for ( $i = 0; $i < $numopt; $i++ )
		{
			$j = $i + 1;
			$newpollopt = "<tr><td>" . _OPTION . " $j:</td><td><input type=\"text\" class=\"qust\" name=\"noptiontext[]\" size=\"50\" maxlength=\"50\"></td></tr>";
			echo $newpollopt;
		}
		echo "<tr>" . "  <td width=\"100%\" colspan=\"2\" align=\"center\"><input type=\"hidden\"  name=\"op\" value=\"poll_creat_step3\">" . "  <input type=\"submit\" value=\"" . _ADD . "\">" . "  <input type=\"reset\" value=\"" . _RESET . "\"></td>" . "  </tr>" . "  </table>" . "</form></center><br>";
		CloseTable();
		include_once ( '../footer.php' );
	}

	/**
	 * poll_creat_step3()
	 * 
	 * @param mixed $xacomm
	 * @param mixed $planguage
	 * @param mixed $ttbc
	 * @param mixed $npollquestion
	 * @param mixed $noptiontext
	 * @param mixed $xadd_expire
	 * @return
	 */
	function poll_creat_step3( $xacomm, $planguage, $ttbc, $npollquestion, $noptiontext, $xadd_expire )
	{

		global $adminfile, $language, $admin, $multilingual, $prefix, $db;
		include_once ( '../header.php' );
		GraphicAdmin();
		title( "<a href=\"" . $adminfile . ".php?op=polls\">" . _VOTINGADMIN . "</a>" );
		$stop = 0;
		if ( $npollquestion == "" ) $stop = 1;
		else
		{
			$reg_ex = "|";
			$replace_word = "";
			$npollquestion = str_replace( $reg_ex, $replace_word, $npollquestion );
		}
		$x = 0;
		$noptiontext2 = array();
		$votestring = array();
		for ( $i = 0; $i < sizeof($noptiontext); $i++ )
		{
			if ( $noptiontext[$i] == "" )
			{
				$x++;
			}
			else
			{
				$noptiontext2[] = $noptiontext[$i];
				$votestring[] = '0';
			}
		}
		$options = sizeof( $noptiontext ) - $x;
		if ( $options < 2 ) $stop = 1;

		if ( $stop == 1 )
		{
			title( _CANNOTADD );
			echo "<center><a href=\"javascript:history.go(-1)\">" . _BACK . "</a></center>";
			exit;
		}
		else
		{
			$votestring = implode( "|", $votestring );
			$optionstring = implode( "|", $noptiontext2 );
			$time = time();
			$time = $time;
			$xacomm = intval( $xacomm );
			$npollquestion = stripslashes( FixQuotes($npollquestion) ) . '|' . $xadd_expire;

			$db->sql_query( "INSERT INTO " . $prefix . "_nvvotings VALUES (NULL, '$npollquestion', '$votestring', '$optionstring', '$options', '$xacomm', '0', '0', '$time', '$planguage', '$ttbc')" );
			Header( "Location: " . $adminfile . ".php?op=polls" );
			exit;
		}
		include_once ( '../footer.php' );
	}

	/**
	 * poll_edit()
	 * 
	 * @param mixed $pollid
	 * @return
	 */
	function poll_edit( $pollid )
	{
		global $adminfile, $language, $admin, $multilingual, $prefix, $db;
		include_once ( '../header.php' );
		GraphicAdmin();
		title( "<a href=\"" . $adminfile . ".php?op=polls\">" . _VOTINGADMIN . "</a>" );
		$pollid = intval( $pollid );
		$sql = "SELECT * FROM " . $prefix . "_nvvotings WHERE pollid='$pollid'";
		$result = $db->sql_query( $sql );
		$row = $db->sql_fetchrow( $result );
		if ( ! $row )
		{
			Header( "Location: " . $adminfile . ".php?op=polls" );
			exit;
		}


		$option = explode( "|", $row['optiontext'] );
		$votes = explode( "|", $row['votes'] );
		$acomm = intval( $row['acomm'] );
		$planguage = $row['planguage'];
		$ttbc = $row['ttbc'];
		$totalvotes = intval( $row['totalvotes'] );
		$xquestion = explode( '|', $row['question'] );
		$question = $xquestion[0];
		$pexpire = $xquestion[1];
		$pexpire = intval( $pexpire );
		$reactvpoll = "";
		$mdate = $row['time'];
		OpenTable();
		echo "<form name=\"editpoll\" action=\"" . $adminfile . ".php\" method=\"post\">";
		echo "<input type=\"hidden\" name=\"pollid\" value=\"$pollid\"><input type=\"hidden\" name=\"totalvotes\" value=\"$totalvotes\"><br>";
		echo "<table border=\"0\" cellpadding=\"2\" cellspacing=\"1\" align=\"center\">" . "<TR>" . "<TD><b>" . _QUESTION . "</b></TD>" . "<TD><input type=\"text\"  name=\"npollquestion\" size=\"50\" value=\"" . $question . "\">" . "</TD></TR>";
		if ( $pexpire != 0 )
		{

			$etime = ( ($mdate + $pexpire) - time() ) / 3600;
			$etime = ( int )$etime;
			if ( $etime < 1 )
			{
				echo "<tr><td>";
				echo _REACTPOLL . ": </td><td><input type=\"checkbox\" name=\"reactvpoll\" value=\"yes\" onclick=\"enableField()\"> [" . _READCTPOLL1 . "]";
				echo "<input type=\"hidden\" name=\"expire\" value=\"$pexpire\">";
				echo "</td></tr>";
			}
		}
		aExpirate( $pexpire );
		if ( $multilingual == 1 )
		{
			echo "<tr><td><b>" . _LANGUAGE . ":</b></td><td>" . "<select name=\"planguage\">";
			echo select_language( $planguage );
			echo "</select>";
			echo "</td></tr>";
		}
		else
		{
			echo "<input type=\"hidden\" name=\"planguage\" value=\"$planguage\">";
		}

		acomm( $acomm );
		echo "<input type=\"hidden\" name=\"xtime\" value=\"$mdate\">";

		echo "<tr><td>" . _NUMPOLLSSELECT . "</td><td><select name=\"ttbc\">";


		for ( $i = 1; $i < 21; $i++ )
		{

			echo "<option value=\"$i\"";
			if ( $i == $ttbc ) echo " selected";
			echo ">$i</option>\n";
		}

		echo "</select></td></tr>";


		for ( $i = 0; $i < intval($row['options']); $i++ )
		{
			$j = $i + 1;
			echo "<tr><td>" . _OPTION . " $j:</td><td><input type=\"hidden\"  name=\"votes[]\" value=\"" . $votes[$i] . "\"><input type=\"text\" name=\"noptiontext[]\" size=\"50\" maxlength=\"50\" value=\"" . $option[$i] . "\"> <input type=\"checkbox\" name=\"del[]\" value=\"1\"> " . _DELETE . "</td></tr>";

		}
		for ( $i = 0; $i < 5; $i++ )
		{
			$j = $i + intval( $row['options'] ) + 1;
			echo "<tr><td>" . _OPTION . " $j:</td><td><input type=\"text\" name=\"noptiontext2[]\" size=\"50\" maxlength=\"50\" value=\"\"></tr>";
		}
		echo "<tr>" . "<td width=\"100%\" colspan=\"2\" align=\"center\"><input type=\"hidden\"  name=\"op\" value=\"poll_edit_save\">" . "<input type=\"submit\" value=\"" . _SAVE . "\"></td>" . "</tr>" . "</table>" . "</form><br>";
		if ( $pexpire != 0 )
		{

			$etime = ( ($mdate + $pexpire) - time() ) / 3600;
			$etime = ( int )$etime;
			if ( $etime < 1 )
			{
				echo "<script language=\"javascript\">";
				echo "document.editpoll.add_expire.disabled=true;";
				echo "document.editpoll.expire.disabled=false;";
				echo "</script>";

			}
		}
		echo "<script language=\"javascript\">";
		echo "function enableField()";
		echo "{";
		echo "if(document.editpoll.reactvpoll.checked==true) {";
		echo "document.editpoll.add_expire.disabled=false;";
		echo "document.editpoll.expire.disabled=true;";
		echo "} else { ";
		echo "document.editpoll.add_expire.disabled=true;";
		echo "document.editpoll.expire.disabled=false;";
		echo "}";
		echo "}";

		echo "</script>";
		CloseTable();
		include_once ( '../footer.php' );
	}
	/**
	 * poll_edit_save()
	 * 
	 * @param mixed $pollid
	 * @param mixed $npollquestion
	 * @param mixed $planguage
	 * @param mixed $ttbc
	 * @param mixed $xacomm
	 * @param mixed $votes
	 * @param mixed $noptiontext
	 * @param mixed $noptiontext2
	 * @param mixed $del
	 * @param mixed $totalvotes
	 * @param mixed $add_expire
	 * @param mixed $expire
	 * @param mixed $reactvpoll
	 * @param mixed $xtime
	 * @return
	 */
	function poll_edit_save( $pollid, $npollquestion, $planguage, $ttbc, $xacomm, $votes, $noptiontext, $noptiontext2, $del, $totalvotes, $add_expire, $expire, $reactvpoll, $xtime )
	{
		global $adminfile, $db, $prefix;
		$pollid = intval( $pollid );
		if ( $db->sql_numrows($db->sql_query("SELECT * FROM " . $prefix . "_nvvotings WHERE pollid='$pollid'")) == 1 )
		{
			$xacomm = intval( $xacomm );
			$optionstring = array();
			$optionvotes = array();
			$atime = time();
			$atime = intval( $atime );
			$reg_ex = "|";
			$replace_word = "";
			$npollquestion = str_replace( $reg_ex, $replace_word, $npollquestion );



			$tru = 0;
			for ( $i = 0; $i < sizeof($noptiontext); $i++ )
			{
				if ( $del[$i] != 1 )
				{
					$optionstring[] = $noptiontext[$i];
					$optionvotes[] = intval( $votes[$i] );
				}
				else
				{
					$tru = $tru + intval( $votes[$i] );
				}
			}
			for ( $i = 0; $i < sizeof($noptiontext2); $i++ )
			{
				if ( $noptiontext2[$i] != "" )
				{
					$optionstring[] = $noptiontext2[$i];
					$optionvotes[] = '0';
				}
			}

			$options = count( $optionstring );
			$optionstring = implode( "|", $optionstring );
			$optionvotes = implode( "|", $optionvotes );
			$totalvotes = intval( $totalvotes ) - $tru;

			if ( $reactvpoll != "yes" )
			{
				$atime = $xtime;
				$add_expire = $expire;
			}
			$npollquestion = stripslashes( $npollquestion ) . '|' . $add_expire;


			$db->sql_query( "UPDATE  " . $prefix . "_nvvotings SET question='$npollquestion', votes='$optionvotes', optiontext='$optionstring', options='$options', acomm='$xacomm', totalvotes= '$totalvotes', time='$atime', planguage='$planguage', ttbc='$ttbc' WHERE pollid='$pollid'" );
			Header( "Location: " . $adminfile . ".php?op=polls" );
			exit;
		}
	}

	/**
	 * poll_status()
	 * 
	 * @param mixed $pollid
	 * @return
	 */
	function poll_status( $pollid )
	{
		global $adminfile, $db, $prefix;
		$pollid = intval( $pollid );
		$sql = "SELECT * FROM " . $prefix . "_nvvotings WHERE pollid='$pollid'";
		$results = $db->sql_query( $sql );
		$rows = $db->sql_fetchrow( $results );
		if ( $rows )
		{
			$xquestion = explode( '|', $rows['question'] );
			$question = $xquestion[0];
			$time = $rows['time'];
			$pexpire = $xquestion[1];
			$etime = ( ($time + $pexpire) - time() ) / 3600;
			$etime = ( int )$etime;
			if ( $etime >= 1 )
			{

				$pexpire = -1;
				$question = $question . "|" . $pexpire;

				$db->sql_query( "UPDATE  " . $prefix . "_nvvotings SET question=\"$question\" WHERE pollid='$pollid'" );
				Header( "Location: " . $adminfile . ".php?op=polls" );
				exit;
			}
			else
			{
				$actvcmm = $_POST['xacomm'];
				$actvpoll = $_POST['add_expire'];
				if ( (! $actvcmm) and (! $actvpoll) )
				{
					include_once ( '../header.php' );
					GraphicAdmin();
					title( _VOTINGADMIN );
					echo "<form action=\"" . $adminfile . ".php\" method=\"post\">";
					echo "<table border=\"0\" cellpadding=\"2\" cellspacing=\"1\" align=\"center\">";
					echo "<tr><td><b>" . _ACTV4POLL . ":</b></td><td>" . $question . "</b></td></tr>";
					aExpirate( 432000 );
					acomm( 2 );
					echo "<td width=\"100%\" colspan=\"2\" align=\"center\">" . "<input type=\"hidden\"  name=\"op\" value=\"poll_status\">" . "<input type=\"hidden\" name=\"pollid\" value=\"$pollid\">" . "<input type=\"submit\" value=\"" . _SAVE . "\"></td>" . "</tr>" . "</table>" . "</form><br>";
					include_once ( '../footer.php' );
				}
				else
				{
					$actvcmm = intval( $actvcmm );
					$npollquestion = $question . '|' . $actvpoll;
					$atime = time();
					$db->sql_query( "UPDATE  " . $prefix . "_nvvotings SET question=\"$npollquestion\",acomm=\"$actvcmm\",time=\"$atime\" WHERE pollid='$pollid'" );

					$actvcmm = "";
					$actvpoll = "";
					Header( "Location: " . $adminfile . ".php?op=polls" );
					exit;
				}
			}
		}

	}
	/**
	 * poll_del()
	 * 
	 * @param mixed $pollid
	 * @param integer $ok
	 * @return
	 */
	function poll_del( $pollid, $ok = 0 )
	{
		global $pollid, $prefix, $db, $adminfold, $adminfile;
		$pollid = intval( $pollid );
		if ( $ok == 1 )
		{

			$db->sql_query( "DELETE FROM " . $prefix . "_nvvotings WHERE pollid=$pollid" );
			$db->sql_query( "DELETE FROM " . $prefix . "_nvvoting_votes WHERE pollid=$pollid" );
			$db->sql_query( "DELETE FROM " . $prefix . "_nvvoting_comments WHERE pollid=$pollid" );
			Header( "Location: " . $adminfile . ".php?op=polls" );
		}
		else
		{

			$sql = "SELECT question FROM " . $prefix . "_nvvotings WHERE pollid='$pollid'";
			$result = $db->sql_query( $sql );
			$row = $db->sql_fetchrow( $result );
			$xquestion = explode( '|', $row['question'] );
			$question = $xquestion[0];
			include ( "../header.php" );
			GraphicAdmin();
			OpenTable();
			echo "<center><font class=\"title\"><b>" . _REMOVEPOLLS . "</b></font></center>";
			CloseTable();
			echo "<br>";
			OpenTable();
			echo "<center>" . "" . _DELETE . "  : <b>" . $question . "</b><br>" . "" . _SURETODELPOLLS . "";
			echo "<br><br>[ <a href=\"javascript:history.go(-1)\">" . _NO . "</a> | <a href=\"" . $adminfile . ".php?op=poll_del&pollid=$pollid&ok=1\">" . _YES . "</a> ]</center>";
			CloseTable();
			include ( "../footer.php" );
		}
	}

	/**
	 * poll_del_comm()
	 * 
	 * @param mixed $tid
	 * @param mixed $pollid
	 * @param integer $ok
	 * @return
	 */
	function poll_del_comm( $tid, $pollid, $ok = 0 )
	{
		global $adminfile, $prefix, $db;
		if ( $ok == 1 )
		{
			$db->sql_query( "DELETE FROM " . $prefix . "_nvvoting_comments WHERE tid='$tid'" );
			$db->sql_query( "UPDATE " . $prefix . "_nvvotings SET totalcomm=totalcomm-1 WHERE pollid='$pollid'" );

			Header( "Location: " . $adminfile . ".php?op=showlistcomm&pollid=$pollid" );
		}
		else
		{
			include ( "../header.php" );
			GraphicAdmin();
			OpenTable();
			echo "<center><font class=\"title\"><b>" . _REMOVECOMMENTS . "</b></font></center>";
			CloseTable();
			echo "<br>";
			OpenTable();
			echo "<center>" . _SURETODELCOMMENTS . "";
			echo "<br><br>[ <a href=\"javascript:history.go(-1)\">" . _NO . "</a> | <a href=\"" . $adminfile . ".php?op=poll_del_comm&tid=$tid&pollid=$pollid&ok=1\">" . _YES . "</a> ]</center>";
			CloseTable();
			include ( "../footer.php" );
		}
	}

	/**
	 * poll_delallcomm()
	 * 
	 * @param mixed $pollid
	 * @param integer $ok
	 * @return
	 */
	function poll_delallcomm( $pollid, $ok = 0 )
	{
		global $adminfile, $prefix, $db;
		if ( $ok == 1 )
		{
			$db->sql_query( "DELETE FROM " . $prefix . "_nvvoting_comments WHERE pollid='$pollid'" );
			$db->sql_query( "UPDATE " . $prefix . "_nvvotings SET totalcomm=0 WHERE pollid='$pollid'" );
			Header( "Location: " . $adminfile . ".php?op=polls" );
		}
		else
		{
			include ( "../header.php" );
			GraphicAdmin();
			OpenTable();
			echo "<center><font class=\"title\"><b>" . _REMOVECOMMENTS . "</b></font></center>";
			CloseTable();
			echo "<br>";
			OpenTable();
			echo "<center>" . _SUREDELALLCOMM . "";
			echo "<br><br>[ <a href=\"javascript:history.go(-1)\">" . _NO . "</a> | <a href=\"" . $adminfile . ".php?op=poll_delallcomm&pollid=$pollid&ok=1\">" . _YES . "</a> ]</center>";
			CloseTable();
			include ( "../footer.php" );
		}
	}

	/**
	 * showlistcomm()
	 * 
	 * @param mixed $pollid
	 * @return
	 */
	function showlistcomm( $pollid )
	{
		global $prefix, $user_prefix, $db, $user, $cookie, $module_name, $bgcolor1, $bgcolor2, $pagenum, $adminfold, $adminfile, $nukeurl;
		$pollid = intval( $pollid );
		if ( ! isset($pollid) || $pollid == 0 )
		{
			Header( "Location: index.php" );
			exit();
		}
		$sql = "SELECT question, acomm FROM " . $prefix . "_nvvotings WHERE pollid='$pollid'";
		$result = $db->sql_query( $sql );
		if ( $numrows = $db->sql_numrows($result) != 1 )
		{
			Header( "Location: index.php" );
			exit;
		}
		$row = $db->sql_fetchrow( $result );

		$xquestion = explode( '|', $row['question'] );
		$title = stripslashes( FixQuotes($xquestion[0]) );
		$acomm = $row['acomm'];
		$numf = $db->sql_fetchrow( $db->sql_query("SELECT COUNT(*) FROM " . $prefix . "_nvvoting_comments WHERE pollid='$pollid'") );
		$page = ( isset($_GET['page']) ) ? intval( $_GET['page'] ) : 0;
		$all_page = ( $numf[0] ) ? $numf[0] : 1;

		$per_page = 20;

		$base_url = $adminfile . ".php?op=showlistcomm&pollid=$pollid";
		if ( $numf[0] == 0 )
		{
			include ( "../header.php" );
			GraphicAdmin();
			title( "<a href=\"" . $adminfile . ".php?op=polls\">" . _VOTINGADMIN . "</a>" );
			OpenTable();
			echo "<center><font class=\"title\"><b>" . _NOCOMMENTS . "</b></font></center>";
			CloseTable();
			echo "<br>";
			include ( "../footer.php" );
			exit();
		}
		include ( "../header.php" );
		GraphicAdmin();
		title( "<a href=\"" . $adminfile . ".php?op=polls\">" . _VOTINGADMIN . "</a>" );
		OpenTable();
		echo "<center><font class=\"title\"><b>" . _MANAGERCOMM . "</b></font></center>";
		CloseTable();
		echo "<br>";
		OpenTable();
		echo "<br><table width=\"100%\"><tr><td><b>" . _COMMENTSARTICLES . ":</b> <a href=\"../modules.php?name=Voting&op=pollvote&pollid=$pollid\"><b>$title</b></a></td>" . "<td align=\"right\">[ <a href=\"" . $adminfile . ".php?op=poll_delallcomm&pollid=$pollid\">" . _DELALLCOMM . "</a> ]</td></tr></table>" . "<hr>";

		if ( $numf[0] > 0 )
		{
			$sql = "SELECT tid, date, name, email, url, host_name, subject, comment FROM " . $prefix . "_nvvoting_comments WHERE pollid='$pollid' ORDER BY date desc LIMIT $page,$per_page";
			$result = $db->sql_query( $sql );
			echo "<form action=\"" . $adminfile . ".php\" method=\"post\">";
			while ( $row = $db->sql_fetchrow($result) )
			{
				$tid = $row['tid'];
				$send_date = $row['date'];
				$sender_name = $row['name'];
				$sql2 = "SELECT user_id, username FROM " . $user_prefix . "_users WHERE username='$sender_name'";
				$result2 = $db->sql_query( $sql2 );
				if ( $db->sql_numrows($result2) == 1 )
				{
					$row2 = $db->sql_fetchrow( $result2 );
					$user_id = $row2[user_id];
					$sender_name = "<a href=\"../modules.php?name=Your_Account&op=userinfo&user_id=$user_id\">$sender_name</a>";

				}
				$com_text = $row['comment'];
				echo "<table border=\"0\" cellpadding=\"2\" cellspacing=\"1\" width=\"100%\" bgcolor=\"$bgcolor2\">\n" . "<tr>\n" . "<td width=\"85%\" bgcolor=\"$bgcolor1\"><b>" . _SENDER . ": $sender_name </b>[ $send_date ]</td>\n" . "<td width=\"15%\" bgcolor=\"$bgcolor1\"><p align=\"right\">";
				if ( $sender_host )
				{
					$blink = "<a href=\"" . $adminfile . ".php?op==ConfigureBan&bad_ip=$sender_host\">$sender_host</a> | ";
				}
				else  $blink = "";
				echo "[ $blink<a href=\"" . $adminfile . ".php?op=poll_del_comm&tid=$tid&pollid=$pollid\">" . _DELETE . "</a> ]";

				echo "</td>\n" . "</tr><tr><td width=\"100%\" bgcolor=\"$bgcolor1\" colspan=\"2\">&nbsp;$com_text</td></tr>\n";
				echo "</table><br><br>";
				$a++;
			}
			echo "</form>";
			echo "<br>";
			echo @generate_page( $base_url, $all_page, $per_page, $page );
		}
		CloseTable();
		include ( "../footer.php" );

	}


	switch ( $op )
	{
		case "showlistcomm":
			showlistcomm( $pollid );
			break;

		case "poll_status":
			poll_status( $pollid );
			break;

		case "polls":
			polls();
			break;

		case "poll_creat_step2":
			poll_creat_step2( $xacomm, $planguage, $numopt, $add_expire, $ttbc );
			break;

		case "poll_creat_step3":
			poll_creat_step3( $xacomm, $planguage, $ttbc, $npollquestion, $noptiontext, $xadd_expire );
			break;

		case "poll_del":
			poll_del( $pollid, $ok );
			break;

		case "poll_edit":
			poll_edit( $pollid );
			break;

		case "poll_edit_save":
			poll_edit_save( $pollid, $npollquestion, $planguage, $ttbc, $xacomm, $votes, $noptiontext, $noptiontext2, $del, $totalvotes, $add_expire, $expire, $reactvpoll, $xtime );
			break;

		case "poll_delallcomm":
			poll_delallcomm( $pollid, $ok );
			break;

		case "poll_del_comm":
			poll_del_comm( $tid, $pollid, $ok );
			break;
	}

}
else
{
	echo "Access Denied";
}

?>
