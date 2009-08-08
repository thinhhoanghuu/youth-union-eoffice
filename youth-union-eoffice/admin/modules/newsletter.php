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

global $prefix, $db;
$aid = substr( "$aid", 0, 25 );
$row = $db->sql_fetchrow( $db->sql_query("SELECT title, admins FROM " . $prefix . "_modules WHERE title='Newsletter'") );
$row2 = $db->sql_fetchrow( $db->sql_query("SELECT name, email, radminsuper FROM " . $prefix . "_authors WHERE aid='$aid'") );
$admins = explode( ",", $row['admins'] );
$auth_user = 0;
for ( $i = 0; $i < sizeof($admins); $i++ )
{
	if ( $row2['name'] == "$admins[$i]" and $row['admins'] != "" )
	{
		$auth_user = 1;
	}
}

if ( $row2['radminsuper'] == 1 || $auth_user == 1 )
{
	$adminemail = $row2['email'];
	include_once ( "language/newsletter-" . $currentlang . ".php" );


	/**
	 * newslettertitle()
	 * 
	 * @return
	 */
	function newslettertitle()
	{
		global $adminfile;
		OpenTable();
		echo "<center><font class=\"title\"><b>" . _NEWSLETTER . "</b></font><br><br>" . "<a href=\"" . $adminfile . ".php?op=adminnl\">" . _NEW_ADMINTITLE . "</a> | " . "<a href=\"" . $adminfile . ".php?op=viewsubnl\">" . _NEW_VIEWLINK . "</a> | " . "<a href=\"" . $adminfile . ".php?op=viewallnl\">" . _NEW_VIEWOLD . "</a>" . "</center>";
		CloseTable();
		echo "<br>";
	}


	/**
	 * Admin()
	 * 
	 * @return
	 */
	function Admin()
	{
		global $adminfile, $db, $prefix, $adminemail;
		include ( "../header.php" );
		GraphicAdmin();
		newslettertitle();
		OpenTable();
		$maxstories = 50;
		$count = $db->sql_numrows( $db->sql_query("SELECT * FROM " . $prefix . "_newsletter WHERE status='2'") );
		echo "<center><font class=\"title\"><b>" . _NEW_ADMINTITLE . "</b></font></center><br><br>" . "" . _NEW_COUNTABO . ": $count<br><br>" . "<form action=\"" . $adminfile . ".php\" method=\"POST\">" . "<b>" . _NEW_NEWSLETTERSUBJECT . ":</b><br>" . "<input type=\"text\" name=\"sub\" value=\"\" size=\"70\" maxlength=\"100\"><br><br>" . "<b>" . _NEW_NEWTEXT . ":</b><br>" . "<textarea name=\"text\" rows=\"15\" cols=\"70\"></textarea><br><br>" . "<b>" . _NEW_NEWTEXTHTML . ":</b><br>" . "<textarea name=\"htmltext\" rows=\"15\" cols=\"70\"></textarea><br><br>" . "<b>" . _NEW_ALTERNATESENDER . ":</b><br>" . "<input type=\"text\" name=\"send\" value=\"$adminemail\" size=\"50\" maxlength=\"100\"><br><br>" . "<b>" . _NEW_ATTACHSTORY . ":</b><br>" . "<SELECT name =\"sid\">" . "<option name =\"sid\" value=\"0\" selected>&nbsp;</option>";
		$result = $db->sql_query( "SELECT sid, title FROM " . $prefix . "_stories ORDER BY sid DESC LIMIT $maxstories" );
		while ( $row = $db->sql_fetchrow($result) )
		{
			echo "<option name =\"sid\" value=\"$row[sid]\">$row[title]</option>";
		}
		echo "</select><br><br>" . "<b>" . _NEW_ATTACHTYPE . ":</b><br>" . "<SELECT name =\"sidtype\">" . "<option value=\"1\">" . _NEW_LINKONLY . "" . "<option value=\"2\">" . _NEW_HOMEANDLINK . "" . "<option value=\"3\">" . _NEW_FULLSTORY . "" . "</select><br><br>" . "<b>" . _NEW_ADDSEPERATOR . ":</b><br>" . "<input type=\"radio\" name=\"seperator\" value=\"1\">" . _YES . " &nbsp;" . "<input type=\"radio\" name=\"seperator\" value=\"0\"  checked>" . _NO . "" . "<input type=\"hidden\" name=\"op\" value=\"adminaddstorynl\">" . "<br><br><input type=\"submit\" value=\"" . _NEW_SENDIT . "\"><br><br></form>";
		CloseTable();
		include ( "../footer.php" );
	}

	/**
	 * AdminAddStory()
	 * 
	 * @param mixed $sid
	 * @param mixed $sidtype
	 * @param mixed $text
	 * @param mixed $htmltext
	 * @param mixed $sub
	 * @param mixed $send
	 * @param mixed $seperator
	 * @return
	 */
	function AdminAddStory( $sid, $sidtype, $text, $htmltext, $sub, $send, $seperator )
	{
		global $adminfile, $db, $prefix, $adminemail, $nukeurl, $sitename;
		if ( ($sub == "") and ($text == "") and ($htmltext == "") and $sid == 0 )
		{
			Header( "Location: " . $adminfile . ".php?op=adminnl" );
			exit;
		}
		if ( $sub == "" )
		{
			$sub = "" . _NEW_DEFAULTSUBJECT . " $sitename";
		}
		if ( ($sid == 0) or ($db->sql_numrows($db->sql_query("SELECT * FROM " . $prefix . "_stories WHERE sid='$sid'")) == 0) )
		{
			include ( "../header.php" );
			GraphicAdmin();
			newslettertitle();
			OpenTable();
			$xtext = nl2br( $text );
			$xhtmltext = stripslashes( $htmltext );
			$text = htmlspecialchars( $text, ENT_QUOTES );
			$htmltext = htmlspecialchars( $htmltext, ENT_QUOTES );
			if ( $send == "" )
			{
				$send = $adminemail;
			}
			$count0 = $db->sql_numrows( $db->sql_query("SELECT * FROM " . $prefix . "_newsletter WHERE status='2' AND html='0'") );
			$count1 = $db->sql_numrows( $db->sql_query("SELECT * FROM " . $prefix . "_newsletter WHERE status='2' AND html='1'") );
			echo "<center><b>" . _NEW_VIEW . ":</b></center><br><br>" . "<table border=\"0\" cellpadding=\"10\" cellspacing=\"10\" width=\"100%\"><tr><td colspan=\"2\" align=\"center\"><b>$sub</b></td></tr>" . "<tr><td width=\"50%\" style=\"border: 1px solid #000000\">" . _NEW_TEXTVIEW . ": $count0</td><td width=\"50%\" style=\"border: 1px solid #000000\">" . _NEW_HTMLVIEW . ": $count1</td></tr>" . "<tr><td valign=\"top\" style=\"border: 1px solid #000000\">$xtext</td>" . "<td valign=\"top\" style=\"border: 1px solid #000000\">$xhtmltext</td></tr></table><br><br>" . "<center><form action=\"" . $adminfile . ".php\" method=\"POST\">" . "<input type=\"hidden\" name=\"sub\" value=\"$sub\">" . "<input type=\"hidden\" name=\"text\" value=\"$text\">" . "<input type=\"hidden\" name=\"htmltext\" value=\"$htmltext\">" . "<input type=\"hidden\" name=\"send\" value=\"$send\">" . "<input type=\"hidden\" name=\"op\" value=\"adminsendnl\">" . "<input type=\"submit\" value=\"" . _NEW_SENDIT . "\"></form></center>";
			CloseTable();
			include ( "../footer.php" );
			exit;
		}
		else
		{
			$text = stripslashes( $text );
			$htmltext = stripslashes( $htmltext );
			include ( "../header.php" );
			GraphicAdmin();
			newslettertitle();
			OpenTable();
			$row = $db->sql_fetchrow( $db->sql_query("SELECT sid, title, hometext, bodytext FROM " . $prefix . "_stories WHERE sid='$sid'") );
			$sid = intval( $row['sid'] );
			$title = $row['title'];
			$hometext = $row['hometext'];
			$bodytext = $row['bodytext'];
			switch ( $sidtype )
			{
				case '1':

					$text = "$text$title\n" . _NEW_LINKTEXT . ":\n$nukeurl/modules.php?name=News&op=viewst&sid=$sid\n";
					$htmltext = "$htmltext<a href=\"$nukeurl/modules.php?name=News&op=viewst&sid=$sid\">$title</a><br>\n";
					break;

				case '2':

					$text = "$text$title\n$hometext\n\n" . _NEW_LINKTEXT . ":\n$nukeurl/modules.php?name=News&op=viewst&sid=$sid\n";
					$htmltext = "$htmltext<b>$title</b><br><br>$hometext<br><br><a href=\"$nukeurl/modules.php?name=News&op=viewst&sid=$sid\">" . _NEW_LINKTEXT . "</a><br>\n";
					break;

				case '3':

					$text = "$text$title\n$hometext\n$bodytext\n\n" . _NEW_LINKTEXT . ":\n$nukeurl/modules.php?name=News&op=viewst&sid=$sid\n";
					$htmltext = "$htmltext$title<br><br>$hometext<br>$bodytext<br><br><a href=\"$nukeurl/modules.php?name=News&op=viewst&sid=$sid\">" . _NEW_LINKTEXT . "</a><br>\n";
					break;
			}



			if ( $seperator == '1' )
			{
				$text = "$text" . _NEW_TEXTSEPERATOR . "";
				$htmltext = "$htmltext" . _NEW_HTMLSEPERATOR . "";
			}
			$sub = htmlspecialchars( $sub, ENT_QUOTES );
			$text = htmlspecialchars( $text, ENT_QUOTES );
			$htmltext = htmlspecialchars( $htmltext, ENT_QUOTES );
			if ( $send == "" )
			{
				$send = $adminemail;
			}
			$maxstories = 50;
			$count = $db->sql_numrows( $db->sql_query("SELECT * FROM " . $prefix . "_newsletter WHERE status='2'") );
			echo "<center><font class=\"title\"><b>" . _NEW_ADMINTITLE . "</b></font></center><br><br>" . "" . _NEW_COUNTABO . ": $count<br><br>" . "<form action=\"" . $adminfile . ".php\" method=\"POST\">" . "<b>" . _NEW_NEWSLETTERSUBJECT . ":</b><br>" . "<input type=\"text\" name=\"sub\" value=\"$sub\" size=\"70\" maxlength=\"100\"><br><br>" . "<b>" . _NEW_NEWTEXT . ":</b><br>" . "<textarea name=\"text\" rows=\"15\" cols=\"70\">$text</textarea><br><br>" . "<b>" . _NEW_NEWTEXTHTML . ":</b><br>" . "<textarea name=\"htmltext\" rows=\"15\" cols=\"70\">$htmltext</textarea><br><br>" . "<b>" . _NEW_ALTERNATESENDER . ":</b><br>" . "<input type=\"text\" name=\"send\" value=\"$send\" size=\"50\" maxlength=\"100\"><br><br>" . "<b>" . _NEW_ATTACHSTORY . ":</b><br>" . "<SELECT name =\"sid\">" . "<option name =\"sid\" value=\"0\" selected>&nbsp;</option>";
			$result = $db->sql_query( "SELECT sid, title FROM " . $prefix . "_stories ORDER BY sid DESC LIMIT $maxstories" );
			while ( $row = $db->sql_fetchrow($result) )
			{
				echo "<option name =\"sid\" value=\"$row[sid]\">$row[title]</option>";
			}
			echo "</select><br><br>" . "<b>" . _NEW_ATTACHTYPE . ":</b><br>" . "<SELECT name =\"sidtype\">" . "<option value=\"1\">" . _NEW_LINKONLY . "" . "<option value=\"2\">" . _NEW_HOMEANDLINK . "" . "<option value=\"3\">" . _NEW_FULLSTORY . "" . "</select><br><br>" . "<b>" . _NEW_ADDSEPERATOR . ":</b><br>" . "<input type=\"radio\" name=\"seperator\" value=\"1\">" . _YES . " &nbsp;" . "<input type=\"radio\" name=\"seperator\" value=\"0\"  checked>" . _NO . "" . "<input type=\"hidden\" name=\"op\" value=\"adminaddstorynl\">" . "<br><br><input type=\"submit\" value=\"" . _NEW_SENDIT . "\"><br><br></form>";
			CloseTable();
			include ( "../footer.php" );
		}
	}

	/**
	 * Adminsend()
	 * 
	 * @param mixed $sub
	 * @param mixed $text
	 * @param mixed $htmltext
	 * @param mixed $send
	 * @return
	 */
	function Adminsend( $sub, $text, $htmltext, $send )
	{
		global $adminfile, $db, $prefix, $adminemail, $nukeurl, $sitename;
		if ( $text == "" && $htmltext == "" )
		{
			Header( "Location: " . $adminfile . ".php?op=adminnl" );
			exit;
		}
		if ( $sub == "" )
		{
			$sub = "" . _NEW_DEFAULTSUBJECT . " $sitename";
		}
		include ( "../header.php" );
		GraphicAdmin();
		newslettertitle();
		OpenTable();
		echo "<center><font class=\title\"><b>" . _NEW_SENDIT . "</b></font></center>";
		$sub = addslashes( $sub );

		$text = stripslashes( $text );
		$htmltext = stripslashes( $htmltext );
		if ( $send == "" )
		{
			$send = $adminemail;
		}
		list( $newsletterid ) = $db->sql_fetchrow( $db->sql_query("SELECT max(id) AS newsletterid FROM " . $prefix . "_newsletter_send") );
		if ( $newsletterid == "-1" )
		{
			$newsletterid = 1;
		}
		else
		{
			$newsletterid = $newsletterid + 1;
		}
		$query = $db->sql_query( "INSERT INTO " . $prefix . "_newsletter_send (id, subject, text, html, send) VALUES ('$newsletterid', '$sub', '$text', '$htmltext', now())" );
		if ( ! $query )
		{
			return;
		}
		$query2 = "SELECT email, id, html, newsletterid, checkkey FROM " . $prefix . "_newsletter WHERE status='2'";
		$result = $db->sql_query( $query2 );
		while ( $row = $db->sql_fetchrow($result) )
		{
			$sendto = $row['email'];
			$subid = intval( $row['id'] );
			$sendhow = $row['html'];
			$checkkey = $row['checkkey'];

			$mailhead = "From: $sitename <$send>\n";
			$mailhead .= "X-Sender: <$send>\n";
			$mailhead .= "X-Mailer: PHP\n";

			$mailhead .= "X-Priority: 6\n";

			$subject = stripslashes( $sub );
			if ( $sendhow == '0' )
			{
				$message = stripslashes( strip_tags($text) );
				$message .= "\n\n\n\n" . _NEW_UNREG . ":\n$nukeurl/modules.php?name=Newsletter&func=delletter&del_email=$sendto&del_check=$checkkey\n";
				$mailhead .= "Content-Type: text/plain; charset= " . _CHARSET . "\n";
			}
			else
			{
				$message = stripslashes( $htmltext );
				$message .= "\n\n\n\n<a href=$nukeurl/modules.php?name=Newsletter&func=delletter&del_email=$sendto&del_check=$checkkey>" . _NEW_UNREG . "</a>\n";
				$mailhead .= "Content-Type: text/html; charset= " . _CHARSET . "\n";
			}

			if ( (($sendhow == 0) and (strlen($text)) > 1) or (($sendhow == 1) and (strlen($htmltext) > 1)) )
			{
				mail( $sendto, $subject, $message, $mailhead );
				$count++;

				$tc = "$newsletterid$row[newsletterid]";
				$query3 = $db->sql_query( "UPDATE " . $prefix . "_newsletter SET newsletterid=',$tc' WHERE id='$subid'" );
				if ( ! $query3 )
				{
					return;
				}
			}
		}

		echo "<center><br><br>" . _NEW_OK . " $count email</center>";
		CloseTable();
		echo "<META HTTP-EQUIV=\"refresh\" content=\"3;URL=" . $adminfile . ".php?op=adminnl\">";
		include ( "../footer.php" );

	}

	/**
	 * ViewSubscribers()
	 * 
	 * @param mixed $sort
	 * @param mixed $sort2
	 * @param mixed $sort3
	 * @param mixed $booknum
	 * @return
	 */
	function ViewSubscribers( $sort, $sort2, $sort3, $booknum )
	{
		global $adminfile, $db, $prefix;
		include ( "../header.php" );
		GraphicAdmin();
		newslettertitle();
		OpenTable();
		echo "<center><font class=\"title\"><b>" . _NEW_VIEWLINK . "</b></font></center><br><br>";
		if ( ($sort != 'email') and ($sort != 'html') )
		{
			$sort = 'id';
		}
		if ( $sort2 == '0' or $sort2 == '1' )
		{
			$xsort2 = "AND html='$sort2'";
		}
		else
		{
			$xsort2 = "";
		}
		$numht = 20;
		if ( $booknum == "" )
		{
			$booknum = 1;
		}
		$offset = ( $booknum - 1 ) * $numht;
		if ( $sort3 == '1' )
		{
			$query = "SELECT email, id, html, newsletterid FROM " . $prefix . "_newsletter WHERE status='2' $xsort2 ORDER BY $sort limit $offset, $numht";
			$sort3 = '0';
		} elseif ( $sort3 != '1' )
		{
			$query = "SELECT email, id, html, newsletterid FROM " . $prefix . "_newsletter WHERE status='2' $xsort2 ORDER BY $sort DESC limit $offset, $numht";
			$sort3 = '1';
		}

		echo "<table border=\"1\" cellpadding=\"3\" cellspacing=\"3\" width=\"100%\">" . "<tr><td align=\"center\" width=\"30\"><a href=\"" . $adminfile . ".php?op=viewsubnl&sort3=$sort3\"><b>TT</b></a></td><td align=\"center\"><b>" . _NEW_DELEMAIL . "</b></td>" . "<td align=\"center\"><a href=\"" . $adminfile . ".php?op=viewsubnl&sort=email&sort3=$sort3\"><b>Email</b></a></td>" . "<td align=\"center\"><a href=\"" . $adminfile . ".php?op=viewsubnl&sort=html&sort3=$sort3\"><b>" . _NEW_TYPE . "</b></a></td>" . "<td align=\"center\"><b>" . _NEW_SENDTO . "</b></td></tr><form action=\"" . $adminfile . ".php\" method=\"post\">";
		$result = $db->sql_query( $query );
		$a = 1;
		while ( $row = $db->sql_fetchrow($result) )
		{
			$id = $row['id'];
			$email = $row['email'];
			$html = $row['html'];
			$newsletterid = $row['newsletterid'];
			$newsletterids = explode( ",", $newsletterid );
			$numletters = sizeof( $newsletterids ) - 1;
			if ( $row['newsletterid'] != "" )
			{
				$numletters = "$numletters " . _NEW_SENDTO2 . " [ <a href=\"" . $adminfile . ".php?op=viewsendnl&amp;userid=$id\">" . _NEW_SENDTO3 . "</a> ]";
			}
			else
			{
				$numletters = "&nbsp;";
			}
			if ( $html == 0 )
			{
				$type = "<a href=\"" . $adminfile . ".php?op=viewsubnl&sort=$sort&sort2=0&sort3=$sort3\">plaintext</a>";
			} elseif ( $html == 1 )
			{
				$type = "<a href=\"" . $adminfile . ".php?op=viewsubnl&sort=$sort&sort2=1&sort3=$sort3\">html</a>";
			}
			$email = "<a href=\"mailto:$email\">$email</a>";
			echo "<tr><td align=\"center\">$a</td><td align=\"center\"><input type=\"checkbox\" name=\"userid[]\" value=\"$id\"></td><td align=\"center\">$email</td><td align=\"center\">$type</td><td align=\"center\">$numletters</td></tr>";
			$a++;
		}
		echo "<tr><td colspan=\"2\">" . "<input type=\"hidden\" name=\"op\" value=\"delusernl\">" . "<center><input type=\"submit\" value=\"" . _NEW_DELEMAILS . "\"></center></td></form>" . "<form action=\"" . $adminfile . ".php\" method=\"post\"><td colspan=\"3\"><center>" . _NEW_ADDEMAIL . ": " . "<input type=\"text\" name=\"new_email\" value=\"\" size=\"30\" maxlength=\"30\"> " . "" . _NEW_TYPE . ": <select name =\"new_type\">" . "<option name =\"new_type\" value=\"0\">" . _NEW_TYPETEXT . "</option>" . "<option name =\"new_type\" value=\"1\">" . _NEW_TYPEHTML . "</option>" . "</select> <input type=\"hidden\" name=\"op\" value=\"actionusernl\">" . "<input type=\"submit\" value=\"" . _NEW_ADDEMAIL2 . "\"></center></form></td></tr></table>";

		$sql_bn = "select * from " . $prefix . "_newsletter WHERE status='2' $xsort2";
		$result_bn = $db->sql_query( $sql_bn );
		$numbooks = $db->sql_numrows( $result_bn );
		@$numpages = ceil( $numbooks / $numht );
		if ( $sort3 == '1' )
		{
			$sort3 = '0';
		} elseif ( $sort3 != '1' )
		{
			$sort3 = '1';
		}
		if ( $numpages > 1 )
		{
			echo "<br><br><hr><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" align=\"center\">" . "<tr>\n<td width=\"25%\">" . _PAGENUM . ": $booknum</td>\n<td align=\"center\" width=\"50%\">";
			if ( $booknum > 1 )
			{
				$prevpage = $booknum - 1;
				$leftarrow = "../images/left.gif";
				echo "<a href=\"" . $adminfile . ".php?op=viewsubnl&sort=$sort&sort2=$sort2&sort3=$sort3&amp;booknum=$prevpage\">";
				echo "<img src=\"$leftarrow\" align=\"absmiddle\" border=\"0\" hspace=\"10\"></a>";
			}
			for ( $i = 1; $i < $numpages + 1; $i++ )
			{

				if ( $i == $booknum )
				{
					echo "<b>$i</b>";
				}
				else
				{
					$pagelink = 5;
					if ( ($i > $booknum) and ($i < $booknum + $pagelink) or ($i < $booknum) and ($i > $booknum - $pagelink) )
					{
						echo " <a href=\"" . $adminfile . ".php?op=viewsubnl&sort=$sort&sort2=$sort2&sort3=$sort3&amp;booknum=$i\">$i</a> ";
					}
					if ( ($i == $numpages) and ($booknum < $numpages - $pagelink) )
					{
						echo "... <a href=\"" . $adminfile . ".php?op=viewsubnl&sort=$sort&sort2=$sort2&sort3=$sort3&amp;booknum=$i\">$i</a>";
					}
					if ( ($i == 1) and ($booknum > 1 + $pagelink) )
					{
						echo "<a href=\"" . $adminfile . ".php?op=viewsubnl&sort=$sort&sort2=$sort2&sort3=$sort3&amp;booknum=$i\">$i</a> ...";
					}
				}
			}
			if ( $booknum < $numpages )
			{
				$nextpage = $booknum + 1;
				$rightarrow = "../images/right.gif";
				echo "<a href=\"" . $adminfile . ".php?op=viewsubnl&sort=$sort&sort2=$sort2&sort3=$sort3&amp;booknum=$nextpage\">";
				echo "<img src=\"$rightarrow\" align=\"absmiddle\" border=\"0\" hspace=\"10\"></a>";
			}
			echo "</td>\n<td align=\"right\" width=\"25%\">\n";
			echo "<script language=\"JavaScript\">\n" . "<!-- Hide the script from old browsers --\n" . "function JumpTo(form) { \n" . "var myindex=form.booknum.selectedIndex;\n" . "if (form.booknum.options[myindex].value != \"0\") {\n" . "parent.location=form.booknum.options[myindex].value; \n" . "}\n" . "}\n" . "//--> \n" . "</script>\n" . "<form method=\"POST\">\n" . "<select size=\"1\" name=\"booknum\" onChange=\"JumpTo(this.form)\">\n";
			for ( $i = 1; $i < $numpages + 1; $i++ )
			{
				if ( $i == $booknum )
				{
					$sel = "selected";
				}
				else
				{
					$sel = "";
				}
				echo "<option value=\"" . $adminfile . ".php?op=viewsubnl&sort=$sort&sort2=$sort2&sort3=$sort3&amp;booknum=$i\" $sel>$i</option>\n";
			}
			echo "</select></form></td></tr></table>\n";
		}


		CloseTable();
		include ( "../footer.php" );
	}

	/**
	 * ViewSendLetters()
	 * 
	 * @param mixed $userid
	 * @param mixed $booknum
	 * @return
	 */
	function ViewSendLetters( $userid, $booknum )
	{
		global $adminfile, $db, $prefix;
		include ( "../header.php" );
		GraphicAdmin();
		newslettertitle();
		OpenTable();
		$query = "SELECT email, id, html, newsletterid FROM " . $prefix . "_newsletter WHERE id='$userid'";
		$result = $db->sql_query( $query );
		$check = $db->sql_numrows( $result );
		if ( $check != 1 )
		{
			Header( "Location: " . $adminfile . ".php?op=adminnl" );
			exit;
		}
		$row = $db->sql_fetchrow( $result );
		$qid = $row['id'];
		$email = $row['email'];
		$newsletterid = $row['newsletterid'];
		$newsletterids = explode( ",", $newsletterid );
		if ( $row['newsletterid'] == "" )
		{
			Header( "Location: " . $adminfile . ".php?op=viewsubnl" );
			exit;
		}
		echo "<center><font class=\"title\"><b>" . _NEW_SENDLETTERS . " $email</b></font></center><br><br>";
		echo "<table border=\"1\" cellpadding=\"3\" cellspacing=\"3\" width=\"100%\">" . "<tr><td align=\"center\" width=\"30\"><b>TT</b></td><td align=\"center\"><b>" . _NEW_DELLETTER . "</b></td>" . "<td align=\"center\"><b>" . _NEW_NEWSLETTERSUBJECT . "</b></td>" . "<td align=\"center\"><b>" . _NEW_SENDDATA . "</b></td></tr><form action=\"" . $adminfile . ".php\" method=\"post\">";
		$numht = 20;
		if ( $booknum == "" )
		{
			$booknum = 1;
		}
		$offset = ( ($booknum - 1) * $numht ) + 1;
		for ( $i = $offset; $i < (($booknum * $numht) + 1); $i++ )
		{
			$xid = $newsletterids[$i];
			$row2 = $db->sql_fetchrow( $db->sql_query("SELECT id, subject, send FROM " . $prefix . "_newsletter_send WHERE id='$xid'") );
			$id = $row2['id'];
			$subject = $row2['subject'];
			$send = $row2['send'];

			$z = $i;
			if ( $subject != "" )
			{
				echo "<tr><td align=\"center\" width=\"30\">$z</td><td align=\"center\"><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td>" . "<td align=\"center\"><a href=\"" . $adminfile . ".php?op=viewletternl&amp;newsid=$id\">$subject</a></td>" . "<td align=\"center\">$send</td></tr>";
			}
		}
		echo "<tr><td colspan=\"2\">" . "<input type=\"hidden\" name=\"op\" value=\"delletternl\">" . "<center><input type=\"submit\" value=\"" . _NEW_DELLETTERS . "\"></center></td></form>" . "<td colspan=\"2\">&nbsp;</td></tr></table>";

		$numbooks = sizeof( $newsletterids ) - 1;
		@$numpages = ceil( $numbooks / $numht );
		if ( $numpages > 1 )
		{
			echo "<br><br><hr><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" align=\"center\">" . "<tr>\n<td width=\"25%\">" . _PAGENUM . ": $booknum</td>\n<td align=\"center\" width=\"50%\">";
			if ( $booknum > 1 )
			{
				$prevpage = $booknum - 1;
				$leftarrow = "../images/left.gif";
				echo "<a href=\"" . $adminfile . ".php?op=viewsendnl&userid=$userid&amp;booknum=$prevpage\">";
				echo "<img src=\"$leftarrow\" align=\"absmiddle\" border=\"0\" hspace=\"10\"></a>";
			}
			for ( $i = 1; $i < $numpages + 1; $i++ )
			{

				if ( $i == $booknum )
				{
					echo "<b>$i</b>";
				}
				else
				{
					$pagelink = 5;
					if ( ($i > $booknum) and ($i < $booknum + $pagelink) or ($i < $booknum) and ($i > $booknum - $pagelink) )
					{
						echo " <a href=\"" . $adminfile . ".php?op=viewsendnl&userid=$userid&amp;booknum=$i\">$i</a> ";
					}
					if ( ($i == $numpages) and ($booknum < $numpages - $pagelink) )
					{
						echo "... <a href=\"" . $adminfile . ".php?op=viewsendnl&userid=$userid&amp;booknum=$i\">$i</a>";
					}
					if ( ($i == 1) and ($booknum > 1 + $pagelink) )
					{
						echo "<a href=\"" . $adminfile . ".php?op=viewsendnl&userid=$userid&amp;booknum=$i\">$i</a> ...";
					}
				}
			}
			if ( $booknum < $numpages )
			{
				$nextpage = $booknum + 1;
				$rightarrow = "../images/right.gif";
				echo "<a href=\"" . $adminfile . ".php?op=viewsendnl&userid=$userid&amp;booknum=$nextpage\">";
				echo "<img src=\"$rightarrow\" align=\"absmiddle\" border=\"0\" hspace=\"10\"></a>";
			}
			echo "</td>\n<td align=\"right\" width=\"25%\">\n";
			echo "<script language=\"JavaScript\">\n" . "<!-- Hide the script from old browsers --\n" . "function JumpTo(form) { \n" . "var myindex=form.booknum.selectedIndex;\n" . "if (form.booknum.options[myindex].value != \"0\") {\n" . "parent.location=form.booknum.options[myindex].value; \n" . "}\n" . "}\n" . "//--> \n" . "</script>\n" . "<form method=\"POST\">\n" . "<select size=\"1\" name=\"booknum\" onChange=\"JumpTo(this.form)\">\n";
			for ( $i = 1; $i < $numpages + 1; $i++ )
			{
				if ( $i == $booknum )
				{
					$sel = "selected";
				}
				else
				{
					$sel = "";
				}
				echo "<option value=\"" . $adminfile . ".php?op=viewsendnl&userid=$userid&amp;booknum=$i\" $sel>$i</option>\n";
			}
			echo "</select></form></td></tr></table>\n";
		}


		CloseTable();
		include ( "../footer.php" );
	}

	/**
	 * ViewLetter()
	 * 
	 * @param mixed $newsid
	 * @return
	 */
	function ViewLetter( $newsid )
	{
		global $adminfile, $db, $admin, $module_name, $prefix;
		include ( "../header.php" );
		GraphicAdmin();
		newslettertitle();
		OpenTable();
		$query = "SELECT subject, text, html FROM " . $prefix . "_newsletter_send WHERE id='$newsid'";
		$result = $db->sql_query( $query );
		$check = $db->sql_numrows( $result );
		if ( $check != 1 )
		{
			Header( "Location: " . $adminfile . ".php?op=adminnl" );
			exit;
		}
		$row = $db->sql_fetchrow( $result );
		$subject = $row[subject];
		$text = $row[text];
		$html = $row[html];
		$subject = stripslashes( $subject );
		$text = nl2br( $text );
		$html = stripslashes( $html );
		echo "<center><font class=\"title\">" . _NEW_VIEWLETTER . ":<br><b>&quot;$subject&quot;</b></font></center><br><br>";
		if ( $text != "" )
		{
			echo "<b>PlainText:</b><br><table border=\"0\" cellpadding=\"10\" cellspacing=\"10\" width=\"100%\"><tr><td style=\"border: 1px solid #000000\">$text</td></tr></table>";
		}
		if ( $html != "" )
		{
			echo "<b>HTML:</b><br><table border=\"0\" cellpadding=\"10\" cellspacing=\"10\" width=\"100%\"><tr><td style=\"border: 1px solid #000000\">$html</td></tr></table>";
		}
		CloseTable();
		include ( "../footer.php" );
	}

	/**
	 * ViewPastLetters()
	 * 
	 * @param mixed $booknum
	 * @return
	 */
	function ViewPastLetters( $booknum )
	{
		global $adminfile, $db, $prefix;
		include ( "../header.php" );
		GraphicAdmin();
		newslettertitle();
		OpenTable();
		echo "<center><font class=\"title\"><b>" . _NEW_VIEWOLD . "</b></font></center><br><br>";
		echo "<table border=\"1\" cellpadding=\"3\" cellspacing=\"3\" width=\"100%\">" . "<tr><td align=\"center\" width=\"30\"><b>TT</b></td><td align=\"center\"><b>" . _NEW_DELLETTER . "</b></td>" . "<td align=\"center\"><b>" . _NEW_NEWSLETTERSUBJECT . "</b></td>" . "<td align=\"center\"><b>" . _NEW_SENDDATA . "</b></td></tr><form action=\"" . $adminfile . ".php\" method=\"post\">";
		$numht = 2;
		if ( $booknum == "" )
		{
			$booknum = 1;
		}
		$offset = ( $booknum - 1 ) * $numht;
		$query = "SELECT subject, send, id FROM " . $prefix . "_newsletter_send ORDER BY send DESC limit $offset, $numht";
		$result = $db->sql_query( $query );
		$a = $offset + 1;
		while ( list($subject, $date, $id) = $db->sql_fetchrow($result) )
		{
			$date = formatTimestamp( $date );
			echo "<tr><td><center>$a</center></td><td align=\"center\"><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td>" . "<td><center><a href=\"" . $adminfile . ".php?op=viewletternl&amp;newsid=$id\">$subject</a></center></td>" . "<td><center>$date</center></td></tr>";
			$a++;
		}
		echo "<tr><td colspan=\"2\">" . "<input type=\"hidden\" name=\"op\" value=\"delletternl\">" . "<center><input type=\"submit\" value=\"" . _NEW_DELLETTERS . "\"></center></td></form>" . "<td colspan=\"2\">&nbsp;</td></tr></table>";

		$sql_bn = "select * from " . $prefix . "_newsletter_send";
		$result_bn = $db->sql_query( $sql_bn );
		$numbooks = $db->sql_numrows( $result_bn );
		@$numpages = ceil( $numbooks / $numht );
		if ( $numpages > 1 )
		{
			echo "<br><br><hr><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" align=\"center\">" . "<tr>\n<td width=\"25%\">" . _PAGENUM . ": $booknum</td>\n<td align=\"center\" width=\"50%\">";
			if ( $booknum > 1 )
			{
				$prevpage = $booknum - 1;
				$leftarrow = "../images/left.gif";
				echo "<a href=\"" . $adminfile . ".php?op=viewallnl&amp;booknum=$prevpage\">";
				echo "<img src=\"$leftarrow\" align=\"absmiddle\" border=\"0\" hspace=\"10\"></a>";
			}
			for ( $i = 1; $i < $numpages + 1; $i++ )
			{

				if ( $i == $booknum )
				{
					echo "<b>$i</b>";
				}
				else
				{
					$pagelink = 5;
					if ( ($i > $booknum) and ($i < $booknum + $pagelink) or ($i < $booknum) and ($i > $booknum - $pagelink) )
					{
						echo " <a href=\"" . $adminfile . ".php?op=viewallnl&amp;booknum=$i\">$i</a> ";
					}
					if ( ($i == $numpages) and ($booknum < $numpages - $pagelink) )
					{
						echo "... <a href=\"" . $adminfile . ".php?op=viewallnl&amp;booknum=$i\">$i</a>";
					}
					if ( ($i == 1) and ($booknum > 1 + $pagelink) )
					{
						echo "<a href=\"" . $adminfile . ".php?op=viewallnl&amp;booknum=$i\">$i</a> ...";
					}
				}
			}
			if ( $booknum < $numpages )
			{
				$nextpage = $booknum + 1;
				$rightarrow = "../images/right.gif";
				echo "<a href=\"" . $adminfile . ".php?op=viewallnl&amp;booknum=$nextpage\">";
				echo "<img src=\"$rightarrow\" align=\"absmiddle\" border=\"0\" hspace=\"10\"></a>";
			}
			echo "</td>\n<td align=\"right\" width=\"25%\">\n";
			echo "<script language=\"JavaScript\">\n" . "<!-- Hide the script from old browsers --\n" . "function JumpTo(form) { \n" . "var myindex=form.booknum.selectedIndex;\n" . "if (form.booknum.options[myindex].value != \"0\") {\n" . "parent.location=form.booknum.options[myindex].value; \n" . "}\n" . "}\n" . "//--> \n" . "</script>\n" . "<form method=\"POST\">\n" . "<select size=\"1\" name=\"booknum\" onChange=\"JumpTo(this.form)\">\n";
			for ( $i = 1; $i < $numpages + 1; $i++ )
			{
				if ( $i == $booknum )
				{
					$sel = "selected";
				}
				else
				{
					$sel = "";
				}
				echo "<option value=\"" . $adminfile . ".php?op=viewallnl&amp;booknum=$i\" $sel>$i</option>\n";
			}
			echo "</select></form></td></tr></table>\n";
		}


		CloseTable();
		include ( "../footer.php" );
	}

	/**
	 * Delletter()
	 * 
	 * @param mixed $id
	 * @return
	 */
	function Delletter( $id )
	{
		global $db, $prefix, $adminfile;
		for ( $i = 0; $i < sizeof($id); $i++ )
		{
			$query2 = "SELECT id, newsletterid FROM " . $prefix . "_newsletter";
			$result = $db->sql_query( $query2 );
			while ( $row = $db->sql_fetchrow($result) )
			{
				$newsletterid = $row[newsletterid];
				$newsletterids = explode( ",", $newsletterid );
				$xid = "";
				for ( $z = 0; $z < sizeof($newsletterids); $z++ )
				{
					if ( $newsletterids[$z] != $id[$i] and $newsletterids[$z] != "" )
					{
						$xid .= ",$newsletterids[$z]";
					}
				}
				$db->sql_query( "UPDATE " . $prefix . "_newsletter SET newsletterid='$xid' WHERE id='$row[id]'" );
			}
			$query = $db->sql_query( "DELETE FROM " . $prefix . "_newsletter_send WHERE id='$id[$i]'" );
			if ( ! $query )
			{
				return;
			}
		}
		Header( "Location: " . $adminfile . ".php?op=viewallnl" );
	}

	/**
	 * Delusernl()
	 * 
	 * @param mixed $userid
	 * @return
	 */
	function Delusernl( $userid )
	{
		global $adminfile, $db, $prefix;
		for ( $i = 0; $i < sizeof($userid); $i++ )
		{
			$query = $db->sql_query( "DELETE FROM " . $prefix . "_newsletter WHERE id='$userid[$i]'" );
			if ( ! $query )
			{
				return;
			}
		}
		Header( "Location: " . $adminfile . ".php?op=viewsubnl" );
	}

	/**
	 * Actionusernl()
	 * 
	 * @param mixed $new_email
	 * @param mixed $new_type
	 * @return
	 */
	function Actionusernl( $new_email, $new_type )
	{
		global $adminfile, $db, $prefix;
		$new_email = strtolower( $new_email );
		$actionletter = 1;
		if ( (! $new_email) || ($new_email == "") || (! eregi("^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,6}$", $new_email)) || (strrpos($new_email, ' ') > 0) )
		{
			$info = "" . _NEW_NOEMAIL . "";
			$actionletter = 0;
		}
		$numrow = $db->sql_numrows( $db->sql_query("SELECT * FROM " . $prefix . "_newsletter WHERE email='$new_email'") );
		if ( $numrow != 0 )
		{
			$info = "" . _NEW_ALREADY . "";
			$actionletter = 0;
		}
		if ( $actionletter == 0 )
		{
			include ( "../header.php" );
			GraphicAdmin();
			newslettertitle();
			OpenTable();
			echo "<center>$info<br><br>" . "<a href=\"" . $adminfile . ".php?op=viewsubnl\"><b>" . _NEW_CLICKHERE . "</b></a></center>";
			echo "<META HTTP-EQUIV=\"refresh\" content=\"3;URL=" . $adminfile . ".php?op=viewsubnl\">";
			CloseTable();
			include ( "../footer.php" );
			return;
		} elseif ( $actionletter == 1 )
		{
			srand( (double)microtime() * 1000000 );
			$mycode = rand();
			$time = time();
			list( $newest_uid ) = $db->sql_fetchrow( $db->sql_query("SELECT max(id) AS newest_uid FROM " . $prefix . "_newsletter") );
			if ( $newest_uid == "-1" )
			{
				$new_uid = 1;
			}
			else
			{
				$new_uid = $newest_uid + 1;
			}
			$result = $db->sql_query( "INSERT INTO " . $prefix . "_newsletter (id, email, status, html, checkkey, time, newsletterid) VALUES ('$new_uid', '$new_email', '2', '$new_type', '$mycode', '$time', '')" );
			if ( ! $result )
			{
				return;
			}
			include ( "../header.php" );
			GraphicAdmin();
			newslettertitle();
			OpenTable();
			echo "<center><b>" . _NEW_SUBOK . "</b></center>";
			echo "<META HTTP-EQUIV=\"refresh\" content=\"3;URL=" . $adminfile . ".php?op=viewsubnl\">";
			CloseTable();
			include ( "../footer.php" );
		}
	}

	switch ( $op )
	{

		case "adminnl":
			Admin();
			break;

		case "adminaddstorynl":
			AdminAddStory( $sid, $sidtype, $text, $htmltext, $sub, $send, $seperator );
			break;

		case "adminsendnl":
			Adminsend( $sub, $text, $htmltext, $send, $sid, $sidtype );
			break;

		case "viewsubnl":
			ViewSubscribers( $sort, $sort2, $sort3, $booknum );
			break;

		case "viewsendnl":
			ViewSendLetters( $userid, $booknum );
			break;

		case "viewletternl":
			ViewLetter( $newsid );
			break;

		case "viewallnl":
			ViewPastLetters( $booknum );
			break;

		case "delletternl":
			Delletter( $id );
			break;

		case "delusernl":
			Delusernl( $userid );
			break;

		case "actionusernl":
			Actionusernl( $new_email, $new_type );
			break;
	}
}
else
{
	echo "Access Denied";
}

?>
