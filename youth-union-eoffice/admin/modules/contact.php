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
$checkmodname = "Contact";
$adm_access = checkmodac( "" . $checkmodname . "" );
if ( $adm_access == 1 )
{
	if ( file_exists("language/" . $checkmodname . "_" . $currentlang . ".php") )
	{
		include_once ( "language/" . $checkmodname . "_" . $currentlang . ".php" );
	}

	/**
	 * contacthomemenu()
	 * 
	 * @return
	 */
	function contacthomemenu()
	{
		global $adminfile;
		echo "<center><font class=\"title\"><b>" . _CONTACT . "</b></font><br><br>";
		echo "<b><a href=\"" . $adminfile . ".php?op=contacthome\">" . _BAILIENHE . "</a>&nbsp;|&nbsp;";
		echo "<a href=\"" . $adminfile . ".php?op=contactemail\">" . _MAILUSER . "</a>|&nbsp;";
		echo "<a href=\"" . $adminfile . ".php?op=deptdefault\">" . _CONTACTSET . "</a></b></center>";
	}

	/**
	 * phonedefault()
	 * 
	 * @return
	 */
	function phonedefault()
	{
		global $adminfile, $prefix, $db;
		echo "<font class=\"title\"><b>" . _NSCURRENTPHONE . "</b></font><br><br><center>";
		echo "<table width=\"90%\" cellspacing=\"1\" cellpadding=\"6\" border=\"0\" align=\"center\">";
		echo "<tr bgcolor=\"#cccccc\">";
		echo "<td align=\"center\">&nbsp;</td>";
		echo "<td align=\"left\"><b>" . _NSPHONENAME . "</b></td>";
		echo "<td align=\"left\"><b>" . _NSPHONENUM . "</b></td>";
		echo "<td align=\"left\"><b>" . _NSDEPTEMAIL . "</b></td>";
		echo "<td align=\"center\"><b>" . _NSCONTACTFUNC . "</b></td></tr>";
		$phone = 1;
		$result1 = $db->sql_query( "select * from " . $prefix . "_contact order by pid" );
		while ( $row1 = $db->sql_fetchrow($result1) )
		{
			$pid = intval( $row1['pid'] );
			$phone_name = stripslashes( $row1['phone_name'] );
			$phone_num = stripslashes( $row1['phone_num'] );
			$email_name = stripslashes( $row1['email_name'] );
			echo "<tr bgcolor=\"#E9E9E9\"><td align=\"center\"><b>$phone.</b></td>";
			echo "<td align=\"left\">$phone_name</td>";
			echo "<td align=\"left\">$phone_num</td>";
			echo "<td align=\"left\">$email_name</td>";
			echo "<td align=\"center\" valign=\"middle\">";
			echo "<input type=\"button\" value=\"" . _NSFEDIT . "\" title=\"" . _NSFEDIT . "\" onClick=\"window.location='" . $adminfile . ".php?op=phoneedit&amp;pid=$pid#Edit'\">&nbsp;&nbsp;";
			echo "<input type=\"button\" value=\"" . _NSFDELETE . "\" title=\"" . _NSFDELETE . "\" onClick=\"window.location='" . $adminfile . ".php?op=phonedelete&amp;pid=$pid#Delete'\">";
			echo "</td></tr>";
			$phone++;
		}
		echo "</table></center><br>";
	}

	/**
	 * readcontacthome()
	 * 
	 * @return
	 */
	function readcontacthome()
	{
		global $adminfile, $hourdiff, $prefix, $db, $bgcolor2;
		$result11 = $db->sql_query( "select pid, name, email_name, dip_name, time  from " . $prefix . "_contact_contact where reply='' order by pid" );
		if ( $db->sql_numrows($result11) != 0 )
		{
			OpenTable();
			echo "<center><font class=\"option\"><b><u>" . _EADCONTACHOME . "</u></b></font><br>";
			echo "<table border=\"1\" cellpadding=\"2\" cellspacing=\"0\" width=\"95%\">";
			echo "<tr><td align=\"center\" bgcolor=\"$bgcolor2\"><b>" . _CONTIME . "</b></td><td align=\"center\" bgcolor=\"$bgcolor2\"><b>" . _CONTACHOME1 . "</b></td><td align=\"center\" bgcolor=\"$bgcolor2\"><b>" . _CONTACHOME4 . "</b></td><td align=\"center\" bgcolor=\"$bgcolor2\"><b>" . _NSPHONENAME . "</b></td><td align=\"center\" bgcolor=\"$bgcolor2\"><b>" . _CONFUNC . "</b></td></tr>";
			while ( $row11 = $db->sql_fetchrow($result11) )
			{
				$pid = intval( $row11['pid'] );
				$name = stripslashes( $row11['name'] );
				$email_name = stripslashes( $row11['email_name'] );
				$dip_name = intval( $row11['dip_name'] );
				$time = $row11['time'];
				ereg( "([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $time, $datetime );
				$datetime = mktime( $datetime[4], $datetime[5] + $hourdiff, $datetime[6], $datetime[2], $datetime[3], $datetime[1] );
				$datetime = date( "d-m-Y", $datetime );
				$result13 = $db->sql_query( "SELECT dept_name FROM " . $prefix . "_contact_dept where did='$dip_name'" );
				$row13 = $db->sql_fetchrow( $result13 );
				$dept_name = stripslashes( $row13['dept_name'] );
				$chucnang_xem = "<a href=\"" . $adminfile . ".php?op=readcontact&pid=$pid\">" . _CONVIEW . "</a>";
				$chucnang_xoa = "<a href=\"" . $adminfile . ".php?op=contactdel&pid=$pid\">" . _NSFDELETE . "</a>";
				echo "<tr><td align=\"left\">$datetime</td><td align=\"left\"><a href=\"" . $adminfile . ".php?op=readcontact&pid=$pid\">$name</a></td><td align=\"left\"><a href=\"" . $adminfile . ".php?op=re_contactemail&pid=$pid\">$email_name</a></td><td align=\"left\"><a href=\"" . $adminfile . ".php?op=deptedit&did=$dip_name\">$dept_name</a></td><td align=\"center\">$chucnang_xem - $chucnang_xoa</td></tr>";
			}
			echo "</table>";
			CloseTable();
		}
		else
		{
			OpenTable();
			echo "<center>" . _CONBLANK . "</center>";
			CloseTable();
		}
		echo "</center><br>";
	}

	/**
	 * readcontact()
	 * 
	 * @param mixed $pid
	 * @return
	 */
	function readcontact( $pid )
	{
		global $adminfile, $prefix, $db;
		$pid = intval( $pid );
		$result = $db->sql_query( "select pid FROM " . $prefix . "_contact_contact where pid='$pid'" );
		if ( $numrows = $db->sql_numrows($result) != 1 )
		{
			Header( "Location: " . $adminfile . ".php?op=contacthome" );
			die();
		}
		include ( "../header.php" );
		GraphicAdmin();
		OpenTable();
		contacthomemenu();
		CloseTable();
		echo "<br>";
		OpenTable();
		$result11 = $db->sql_query( "select * FROM " . $prefix . "_contact_contact where pid='$pid'" );
		$row11 = $db->sql_fetchrow( $result11 );
		$name = stripslashes( $row11['name'] );
		$add_name = stripslashes( $row11['add_name'] );
		$phone_num = stripslashes( $row11['phone_num'] );
		$email_name = stripslashes( $row11['email_name'] );
		$dip_name = intval( $row11['dip_name'] );
		$messenger = stripslashes( $row11['messenger'] );
		$time = $row11['time'];
		ereg( "([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $time, $datetime );
		$datetime = mktime( $datetime[4], $datetime[5], $datetime[6], $datetime[2], $datetime[3], $datetime[1] );
		$datetime = date( "H:i - d/m/Y", $datetime );
		$result13 = $db->sql_query( "SELECT dept_name FROM " . $prefix . "_contact_dept where did='$dip_name'" );
		$row13 = $db->sql_fetchrow( $result13 );
		$dept_name = stripslashes( $row13['dept_name'] );
		echo "<b>" . _CONTO . ":</b> $dept_name<br>";
		echo "<b>" . _CONTIME . ":</b> $datetime<br>";
		echo "<b>" . _CONTACHOME1 . ":</b> $name<br>";
		if ( $add_name != "" )
		{
			echo "<b>" . _CONTACHOME2 . ":</b> $add_name<br>";
		}
		if ( $phone_num != "" )
		{
			echo "<b>" . _CONTACHOME3 . ":</b> $phone_num<br>";
		}
		echo "<b>" . _CONTACHOME4 . ":</b> $email_name<br>";
		echo "<br><b>" . _CONTACHOME5 . ":</b><br> $messenger<br>";
		echo "<p align=\"right\"><b><a href=\"" . $adminfile . ".php?op=re_contactemail&pid=$pid\">" . _CONANS . "</b></a> | <a href=\"" . $adminfile . ".php?op=contactdel&pid=$pid\">" . _NSFDELETE . "</a></p>";

		CloseTable();
		include ( "../footer.php" );
	}

	/**
	 * re_contactemail()
	 * 
	 * @param mixed $pid
	 * @return
	 */
	function re_contactemail( $pid )
	{
		global $adminfile, $prefix, $db, $adminemail;
		$pid = intval( $pid );
		$result = $db->sql_query( "select pid FROM " . $prefix . "_contact_contact where pid='$pid'" );
		if ( $numrows = $db->sql_numrows($result) != 1 )
		{
			Header( "Location: " . $adminfile . ".php?op=contacthome" );
			die();
		}

		include ( "../header.php" );
		GraphicAdmin();
		echo "<a name=\"Edit\">";
		OpenTable();
		contacthomemenu();
		CloseTable();
		OpenTable();
		$result11 = $db->sql_query( "select * FROM " . $prefix . "_contact_contact where pid='$pid'" );
		$row11 = $db->sql_fetchrow( $result11 );
		$name = stripslashes( $row11['name'] );
		$add_name = stripslashes( $row11['add_name'] );
		$phone_num = stripslashes( $row11['phone_num'] );
		$email_name = stripslashes( $row11['email_name'] );
		$dip_name = intval( $row11['dip_name'] );
		$messenger = stripslashes( $row11['messenger'] );
		$time = $row11['time'];
		ereg( "([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $time, $datetime );
		$datetime = mktime( $datetime[4], $datetime[5], $datetime[6], $datetime[2], $datetime[3], $datetime[1] );
		$datetime = date( "H:i - d/m/Y", $datetime );
		$result13 = $db->sql_query( "SELECT dept_name FROM " . $prefix . "_contact_dept where did='$dip_name'" );
		$row13 = $db->sql_fetchrow( $result13 );
		$dept_name = stripslashes( $row13['dept_name'] );
		echo "<b>" . _CONTO . ":</b> $dept_name<br>";
		echo "<b>" . _CONTIME . ":</b> $datetime<br>";
		echo "<b>" . _CONTACHOME1 . ":</b> $name<br>";
		if ( $add_name != "" )
		{
			echo "<b>" . _CONTACHOME2 . ":</i> $add_name<br>";
		}
		if ( $phone_num != "" )
		{
			echo "<b>" . _CONTACHOME3 . ":</i> $phone_num<br>";
		}
		echo "<b>" . _CONTACHOME4 . ":</b> $email_name<br>";
		echo "<br><b>" . _CONTACHOME5 . ":</b></br> $messenger<br>";

		echo "<br><br><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">";
		echo "<tr><td valign=\"top\">";
		echo "<center><font class=\"option\"><b>" . _CONSEND . "</b></font><br>";
		echo "<form action=\"" . $adminfile . ".php\" method=\"post\">";
		echo "<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\" width=\"100%\">";
		echo "<tr><td align=\"right\">" . _TIEUDE . " <font color=\"#FF0000\">*</font>:</td><td align=\"left\">&nbsp;<input type=\"text\" name=\"contactsubject\" size=\"50\" maxlength=\"50\"></td></tr>";
		echo "<tr><td align=\"right\" valign=\"top\"><font class=\"content\">" . _EMAILMESSAGE . " <font color=\"#FF0000\">*</font>:</font></td>";
		echo "<td align=\"left\">&nbsp;<textarea cols=\"50\" name=\"contactemail_noidung\" rows=\"10\" ></textarea></td></tr>";
		echo "</table>";
		echo "<input type=\"hidden\" name=\"contactemail_nhan\" value=\"$email_name\">";
		echo "<input type=\"hidden\" name=\"contactemailtype\" value=\"1\">";
		echo "<input type=\"hidden\" name=\"op\" value=\"contactemailsend\">";
		echo "<input type=\"submit\" value=\"" . _SENDEMAIL . "\">";
		echo "</form></center>";
		echo "</td><td width=\"160\" valign=\"top\">";
		echo "<i>Ghi chú</i><br>" . _GHICHU . "";
		echo "</td></tr></table>";
		CloseTable();
		include ( "../footer.php" );
	}

	/**
	 * contactdel()
	 * 
	 * @param mixed $pid
	 * @param integer $ok
	 * @return
	 */
	function contactdel( $pid, $ok = 0 )
	{
		global $adminfile, $prefix, $db;
		$pid = intval( $pid );
		$result = $db->sql_query( "SELECT pid FROM " . $prefix . "_contact_contact where pid=$pid" );
		$num = $db->sql_numrows( $result );
		if ( $num != 1 )
		{
			Header( "Location: " . $adminfile . ".php?op=contacthome" );
			exit;
		}
		if ( $ok )
		{
			$datanex = $db->sql_query( "DELETE FROM " . $prefix . "_contact_contact WHERE pid=$pid" );
			include ( "../header.php" );
			GraphicAdmin();
			OpenTable();
			contacthomemenu();
			CloseTable();
			echo "<br>";
			OpenTable();
			if ( $datanex )
			{
				echo "<center>" . _CONDEL . "</center>";
			}
			else
			{
				echo "<br><center>" . _CONERR . "</center>";
			}
			echo " <meta http-equiv=\"refresh\" content=\"2;url=admin.php?op=contacthome\"><br><br>";
			CloseTable();
			include ( "../footer.php" );
		}
		else
		{
			include ( "../header.php" );
			GraphicAdmin();
			OpenTable();
			contacthomemenu();
			CloseTable();
			echo "<br>";
			OpenTable();
			echo "<center>" . _CONDELID . " $pid ";
			echo "<br><br>[ <a href=\"" . $adminfile . ".php?op=contacthome\">" . _NO . "</a> | <a href=\"" . $adminfile . ".php?op=contactdel&amp;pid=$pid&amp;ok=1\">" . _YES . "</a> ]</center><br><br>";
			CloseTable();
			include ( "../footer.php" );
		}
	}


	/**
	 * contacthome()
	 * 
	 * @return
	 */
	function contacthome()
	{
		global $adminfile, $prefix, $db;
		include ( "../header.php" );
		GraphicAdmin();
		OpenTable();
		contacthomemenu();
		CloseTable();
		echo "<br>";
		readcontacthome();
		include ( "../footer.php" );
	}

	/**
	 * contactemail()
	 * 
	 * @return
	 */
	function contactemail()
	{
		global $adminfile, $prefix, $db, $adminemail;
		include ( "../header.php" );
		GraphicAdmin();
		echo "<a name=\"Edit\">";
		OpenTable();
		contacthomemenu();
		CloseTable();
		OpenTable();
		echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">";
		echo "<tr><td valign=\"top\">";
		echo "<center><font class=\"option\"><b>" . _MAILUSER . "</b></font><br>";
		echo "<form action=\"" . $adminfile . ".php\" method=\"post\">";
		echo "<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\" width=\"100%\">";
		echo "<tr><td align=\"right\">" . _TIEUDE . " <font color=\"#FF0000\">*</font>:</td><td align=\"left\">&nbsp;<input type=\"text\" name=\"contactsubject\" size=\"50\" maxlength=\"50\"></td></tr>";
		echo "<tr><td align=\"right\">" . _EMAIL_NHAN . " <font color=\"#FF0000\">*</font>:</td><td align=\"left\">&nbsp;<input type=\"text\" name=\"contactemail_nhan\" size=\"50\" maxlength=\"50\"></td></tr>";
		echo "<tr><td align=\"right\">" . _CACHGUI . " <font color=\"#FF0000\">*</font>:</td><td align=\"left\">&nbsp;<SELECT type=\"number\" name =\"contactemailtype\">" . "<option value=\"1\">" . _CONTACT_ONLY . "" . "<option value=\"2\">" . _CONTACT_EMAIL . "" . "<option value=\"3\">" . _CONTACT_USER . "" . "<option value=\"4\">" . _CONTACT_ALL . "" . "</select></td></tr>";
		echo "<tr><td align=\"right\" valign=\"top\"><font class=\"content\">" . _EMAILMESSAGE . " <font color=\"#FF0000\">*</font>:</font></td>";
		echo "<td align=\"left\">&nbsp;<textarea cols=\"50\" name=\"contactemail_noidung\" rows=\"10\" ></textarea></td></tr>";
		echo "</table><input type=\"hidden\" name=\"op\" value=\"contactemailsend\">";
		echo "<input type=\"submit\" value=\"" . _SENDEMAIL . "\">";
		echo "</form></center>";
		echo "</td><td width=\"160\" valign=\"top\">";
		echo "<i>" . _CONNOTE . "</i><br>" . _GHICHU . "";
		echo "</td></tr></table>";
		CloseTable();
		include ( "../footer.php" );
	}

	/**
	 * contactemailsend()
	 * 
	 * @param mixed $contactsubject
	 * @param mixed $contactemail_nhan
	 * @param mixed $contactemailtype
	 * @param mixed $contactemail_noidung
	 * @return
	 */
	function contactemailsend( $contactsubject, $contactemail_nhan, $contactemailtype, $contactemail_noidung )
	{
		global $adminfile, $prefix, $user_prefix, $db, $adminemail, $sitename;
		include ( "../header.php" );
		GraphicAdmin();
		OpenTable();
		contacthomemenu();
		CloseTable();
		if ( ($contactsubject == "") or (strlen($contactemail_noidung) < 20) )
		{
			OpenTable();
			echo "<br><center><font class=\"title\"><b>" . _NOFULL . "</b></font>";
			echo "<br><br><br><font class=\"title\">" . _PLEASEFULL . "</font><br><br>";
			echo " <meta http-equiv=\"refresh\" content=\"3;url=javascript:history.go(-1)\">";
			echo "[ <a href=\"javascript:history.go(-1)\">" . _BACK . "</a> ]</center><br>";
			CloseTable();
			include ( "../footer.php" );
			exit;
		}

		if ( ($contactemailtype == 1) and ($contactemail_nhan == "") )
		{
			OpenTable();
			echo "<br><center><font class=\"title\"><b>" . _NOFULL . "</b></font>";
			echo "<br><br><br><font class=\"title\">" . _PLEASEFULL . "</font><br><br>";
			echo " <meta http-equiv=\"refresh\" content=\"3;url=javascript:history.go(-1)\">";
			echo "[ <a href=\"javascript:history.go(-1)\">" . _BACK . "</a> ]</center><br>";
			CloseTable();
			include ( "../footer.php" );
			exit;
		}

		$subject = "[$sitename]: " . stripslashes( $contactsubject ) . "";
		$content = stripslashes( $contactemail_noidung );
		$content = "\n $content\n";
		$xheaders = "From: " . $sitename . " <" . $adminemail . ">\n";
		$xheaders .= "X-Sender: <" . $adminemail . ">\n";
		$xheaders .= "X-Mailer: PHP\n";

		$xheaders .= "X-Priority: 6\n";

		$xheaders .= "Content-Type: text/html; charset=utf-8\n";


		$count = 0;
		if ( ($contactemailtype == 1) or ($contactemailtype == 4) )
		{
			$user_email = stripslashes( $contactemail_nhan );
			mail( "$user_email", "$subject", "$content", $xheaders );
			$count = $count + 1;
		}

		if ( ($contactemailtype == 2) or ($contactemailtype == 4) )
		{
			$resultnewsletter = $db->sql_query( "SELECT email FROM " . $prefix . "_newsletter " );
			while ( $rownewsletter = $db->sql_fetchrow($resultnewsletter) )
			{
				$user_email = stripslashes( $rownewsletter['email'] );
				mail( "$user_email", "$subject", "$content", $xheaders );
				$count = $count + 1;
			}
		}

		if ( ($contactemailtype == 3) or ($contactemailtype == 4) )
		{
			$result_users = $db->sql_query( "SELECT user_email FROM " . $user_prefix . "_users " );
			while ( $row_users = $db->sql_fetchrow($result_users) )
			{
				$user_email = stripslashes( $row_users['user_email'] );
				mail( "$user_email", "$subject", "$content", $xheaders );
				$count = $count + 1;
			}
		}

		Header( "Location: " . $adminfile . ".php?op=contactemailxong&pid=$count" );
	}

	/**
	 * contactemailxong()
	 * 
	 * @param mixed $pid
	 * @return
	 */
	function contactemailxong( $pid )
	{
		include ( "../header.php" );
		GraphicAdmin();
		OpenTable();
		contacthomemenu();
		CloseTable();
		echo "<p align=\"center\"><b>" . _MASSEMAILSENT . ": $pid</b></p>";
		include ( "../footer.php" );
	}


	/**
	 * deptdefault()
	 * 
	 * @return
	 */
	function deptdefault()
	{
		global $adminfile, $prefix, $db;
		include ( "../header.php" );
		GraphicAdmin();
		echo "<a name=\"Default\">";
		OpenTable();
		contacthomemenu();
		CloseTable();
		echo "<br>";

		OpenTable();
		echo "<font class=\"title\"><b>" . _EMAILDEPT . "</b></font><br><br>";
		echo "<table width=\"95%\" cellspacing=\"1\" cellpadding=\"3\" border=\"0\" align=\"center\">";
		echo "<tr bgcolor=\"#cccccc\">";
		echo "<td align=\"center\">&nbsp;</td>";
		echo "<td align=\"left\"><b>" . _NSDEPTNAME . "</b></td>";
		echo "<td align=\"left\"><b>" . _NSDEPTEMAIL . "</b></td>";
		echo "<td align=\"left\"><b>" . _NSDEPTCONTACT . "</b></td>";
		echo "<td align=\"center\"><b>" . _NSCONTACTFUNC . "</b></td></tr>";
		$dept = 1;
		$result2 = $db->sql_query( "select * from " . $prefix . "_contact_dept order by dept_name" );
		while ( $row2 = $db->sql_fetchrow($result2) )
		{
			$did = intval( $row2['did'] );
			$dept_name = stripslashes( $row2['dept_name'] );
			$dept_email = stripslashes( $row2['dept_email'] );
			$dept_contact = intval( $row2['dept_contact'] );
			echo "<tr bgcolor=\"#E9E9E9\"><td align=\"center\"><b>$dept.</b></td>";
			echo "<td align=\"left\">$dept_name</td>";
			echo "<td align=\"left\">$dept_email</td>";
			if ( $dept_contact == 1 )
			{
				echo "<td align=\"left\">" . _NSDEPTCONTACT1 . "</td>";
			}
			else
				if ( $dept_contact == 2 )
				{
					echo "<td align=\"left\">" . _NSDEPTCONTACT2 . "</td>";
				}
				else
				{
					echo "<td align=\"left\">" . _NSDEPTCONTACT3 . "</td>";
				}
				echo "<td align=\"center\" valign=\"middle\">";
			echo "<input type=\"button\" value=\"" . _NSFEDIT . "\" title=\"" . _NSFEDIT . "\" onClick=\"window.location='" . $adminfile . ".php?op=deptedit&amp;did=$did#Edit'\">&nbsp;&nbsp;";
			echo "<input type=\"button\" value=\"" . _NSFDELETE . "\" title=\"" . _NSFDELETE . "\" onClick=\"window.location='" . $adminfile . ".php?op=deptdelete&amp;did=$did#Delete'\">";
			echo "</td></tr>";
			$dept++;
		}
		echo "</table><br>";
		echo "<a name=\"Add\">";

		OpenTable();
		echo "<center><font class=\"option\"><b>" . _NSADDDEPT . "</b></font><br>";
		echo "<form action=\"" . $adminfile . ".php\" method=\"post\">";
		echo "<center><table border=\"0\" cellpadding=\"2\" cellspacing=\"2\" width=\"100%\">";
		echo "<tr><td align=\"right\">" . _NSDEPTNAME . ":</td><td align=\"left\">&nbsp;<input type=\"text\" name=\"dept_name\" size=\"50\" maxlength=\"50\" ></td></tr>";
		echo "<tr><td align=\"right\">" . _NSDEPTEMAIL . ":</td><td align=\"left\">&nbsp;<input type=\"text\" name=\"dept_email\" size=\"50\" maxlength=\"50\" ></td></tr>";
		echo "<tr><td align=\"right\">" . _NSDEPTCONTACT . ":</td><td align=\"left\">&nbsp;<SELECT type=\"number\" name =\"dept_contact\">" . "<option value=\"1\">" . _NSDEPTCONTACT1 . "" . "<option value=\"2\">" . _NSDEPTCONTACT2 . "" . "<option value=\"3\">" . _NSDEPTCONTACT3 . "" . "</select></td></tr></table></center>";
		echo "<input type=\"hidden\" name=\"op\" value=\"deptadd\">";
		echo "<input type=\"submit\" value=\"" . _NSSAVE . "\">";
		echo "</form></center>";

		CloseTable();
		CloseTable();

		echo "<br>";
		OpenTable();
		phonedefault();
		echo "<a name=\"PhoneAdd\">";
		OpenTable();
		echo "<center><font class=\"option\"><b>" . _NSADDPHONE . "</b></font><br>";
		echo "<form action=\"" . $adminfile . ".php\" method=\"post\">";
		echo "<table border=\"0\" cellpadding=\"2\" cellspacing=\"2\" width=\"100%\">";
		echo "<tr><td align=\"right\">" . _NSPHONENAME . ":</td><td align=\"left\"><input type=\"text\" name=\"phone_name\" size=\"50\" maxlength=\"50\"></td></tr>";
		echo "<tr><td align=\"right\">" . _NSADD . ":</td><td align=\"left\"><input type=\"text\" name=\"add_name\" size=\"50\" maxlength=\"50\"></td></tr>";
		echo "<tr><td align=\"right\">" . _NSPHONENUM . ":</td><td align=\"left\"><input type=\"text\" name=\"phone_num\" size=\"50\" maxlength=\"50\"></td></tr>";
		echo "<tr><td align=\"right\">" . _NSFAXNUM . ":</td><td align=\"left\"><input type=\"text\" name=\"fax_num\" size=\"50\" maxlength=\"50\"></td></tr>";
		echo "<tr><td align=\"right\">" . _NSDEPTEMAIL . ":</td><td align=\"left\"><input type=\"text\" name=\"email_name\" size=\"50\" maxlength=\"50\"></td></tr>";
		echo "<tr><td align=\"right\">" . _CONWEB . ":</td><td align=\"left\"><input type=\"text\" name=\"web_name\" size=\"50\" maxlength=\"50\"></td></tr>";
		echo "<tr><td align=\"right\">" . _CONNOTE . ":</td><td align=\"left\"><input type=\"text\" name=\"note_name\" size=\"50\" maxlength=\"50\"></td></tr>";
		echo "</table><input type=\"hidden\" name=\"op\" value=\"phoneadd\">";
		echo "<input type=\"submit\" value=\"" . _NSSAVE . "\">";
		echo "</form></center>";
		CloseTable();
		CloseTable();
		include ( "../footer.php" );
	}

	/**
	 * phoneedit()
	 * 
	 * @param mixed $pid
	 * @return
	 */
	function phoneedit( $pid )
	{
		global $adminfile, $prefix, $db;
		include ( "../header.php" );
		GraphicAdmin();
		OpenTable();
		contacthomemenu();
		CloseTable();
		OpenTable();
		$result3 = $db->sql_query( "SELECT * FROM " . $prefix . "_contact where pid='$pid'" );
		$row3 = $db->sql_fetchrow( $result3 );
		$pid = intval( $row3['pid'] );
		$phone_name = stripslashes( $row3['phone_name'] );
		$add_name = stripslashes( $row3['add_name'] );
		$phone_num = stripslashes( $row3['phone_num'] );
		$fax_num = stripslashes( $row3['fax_num'] );
		$email_name = stripslashes( $row3['email_name'] );
		$web_name = stripslashes( $row3['web_name'] );
		$note_name = stripslashes( $row3['note_name'] );
		echo "<center><font class=\"option\"><b>" . _NSEDITPHONE . "</b></font><br>";
		echo "<form action=\"" . $adminfile . ".php\" method=\"post\">";
		echo "<table border=\"0\" cellpadding=\"2\" cellspacing=\"2\" width=\"100%\">";
		echo "<tr><td align=\"right\">" . _NSPHONENAME . ":</td><td align=\"left\"><input type=\"text\" name=\"phone_name\" size=\"50\" maxlength=\"50\" value=\"$phone_name\"></td></tr>";
		echo "<tr><td align=\"right\">" . _NSADD . ":</td><td align=\"left\"><input type=\"text\" name=\"add_name\" size=\"50\" maxlength=\"50\" value=\"$add_name\"></td></tr>";
		echo "<tr><td align=\"right\">" . _NSPHONENUM . ":</td><td align=\"left\"><input type=\"text\" name=\"phone_num\" size=\"50\" maxlength=\"50\" value=\"$phone_num\"></td></tr>";
		echo "<tr><td align=\"right\">" . _NSFAXNUM . ":</td><td align=\"left\"><input type=\"text\" name=\"fax_num\" size=\"50\" maxlength=\"50\" value=\"$fax_num\"></td></tr>";
		echo "<tr><td align=\"right\">" . _NSDEPTEMAIL . ":</td><td align=\"left\"><input type=\"text\" name=\"email_name\" size=\"50\" maxlength=\"50\" value=\"$email_name\"></td></tr>";
		echo "<tr><td align=\"right\">" . _CONWEB . ":</td><td align=\"left\"><input type=\"text\" name=\"web_name\" size=\"50\" maxlength=\"50\" value=\"$web_name\"></td></tr>";
		echo "<tr><td align=\"right\">" . _CONNOTE . ":</td><td align=\"left\"><input type=\"text\" name=\"note_name\" size=\"50\" maxlength=\"50\" value=\"$note_name\"></td></tr>";
		echo "</table><input type=\"hidden\" name=\"pid\" value=\"$pid\">";
		echo "<input type=\"hidden\" name=\"op\" value=\"phonemodify\">";
		echo "<input type=\"submit\" value=\"" . _NSSAVE . "\">";
		echo "</form></center>";
		CloseTable();
		include ( "../footer.php" );
	}


	/**
	 * deptedit()
	 * 
	 * @param mixed $did
	 * @return
	 */
	function deptedit( $did )
	{
		global $adminfile, $prefix, $db;
		include ( "../header.php" );
		GraphicAdmin();
		echo "<a name=\"Edit\">";
		OpenTable();
		contacthomemenu();
		CloseTable();

		OpenTable();
		$result4 = $db->sql_query( "select * from " . $prefix . "_contact_dept where did='$did'" );
		$row4 = $db->sql_fetchrow( $result4 );
		$did = intval( $row4['did'] );
		$dept_name = stripslashes( $row4['dept_name'] );
		$dept_email = stripslashes( $row4['dept_email'] );
		$dept_contact = intval( $row4['dept_contact'] );
		echo "<center><font class=\"option\"><b>" . _EDITEMAILDEPT . "</b></font><br>";
		echo "<form action=\"" . $adminfile . ".php\" method=\"post\">";
		echo "<table border=\"0\" cellpadding=\"2\" cellspacing=\"2\" width=\"100%\">";
		echo "<tr><td align=\"right\">" . _NSDEPTNAME . ":</td><td align=\"left\">&nbsp;<input type=\"text\" name=\"dept_name\" size=\"50\" maxlength=\"50\" value=\"$dept_name\"></td></tr>";
		echo "<tr><td align=\"right\">" . _NSDEPTEMAIL . ":</td><td align=\"left\">&nbsp;<input type=\"text\" name=\"dept_email\" size=\"50\" maxlength=\"50\" value=\"$dept_email\"></td></tr>";
		echo "<tr><td align=\"right\">" . _NSDEPTCONTACT . ":</td><td align=\"left\">&nbsp;<SELECT type=\"number\" name =\"dept_contact\">";
		if ( $dept_contact == 1 )
		{
			echo "<option value=\"1\">" . _NSDEPTCONTACT1 . "" . "<option value=\"2\">" . _NSDEPTCONTACT2 . "" . "<option value=\"3\">" . _NSDEPTCONTACT3 . "";
		}
		else
			if ( $dept_contact == 2 )
			{
				echo "<option value=\"2\">" . _NSDEPTCONTACT2 . "" . "<option value=\"1\">" . _NSDEPTCONTACT1 . "" . "<option value=\"3\">" . _NSDEPTCONTACT3 . "";
			}
			else
			{
				echo "<option value=\"3\">" . _NSDEPTCONTACT3 . "" . "<option value=\"1\">" . _NSDEPTCONTACT1 . "" . "<option value=\"2\">" . _NSDEPTCONTACT2 . "";
			}
			echo "</select></td></tr></table>";
		echo "<input type=\"hidden\" name=\"did\" value=\"$did\">";
		echo "<input type=\"hidden\" name=\"op\" value=\"deptmodify\">";
		echo "<input type=\"submit\" value=\"" . _NSSAVE . "\">";
		echo "</form></center>";
		CloseTable();
		include ( "../footer.php" );
	}


	/**
	 * deptmodify()
	 * 
	 * @param mixed $did
	 * @param mixed $dept_name
	 * @param mixed $dept_email
	 * @param mixed $dept_contact
	 * @return
	 */
	function deptmodify( $did, $dept_name, $dept_email, $dept_contact )
	{
		global $adminfile, $prefix, $db;
		$dept_name = stripslashes( FixQuotes($dept_name) );
		$dept_email = stripslashes( FixQuotes($dept_email) );
		$dept_contact = intval( $dept_contact );
		$db->sql_query( "update " . $prefix . "_contact_dept set dept_name='$dept_name', dept_email='$dept_email', dept_contact='$dept_contact' where did='$did'" );
		Header( "Location: " . $adminfile . ".php?op=deptdefault" );
	}


	/**
	 * phonemodify()
	 * 
	 * @param mixed $pid
	 * @param mixed $phone_name
	 * @param mixed $add_name
	 * @param mixed $phone_num
	 * @param mixed $fax_num
	 * @param mixed $email_name
	 * @param mixed $web_name
	 * @param mixed $note_name
	 * @return
	 */
	function phonemodify( $pid, $phone_name, $add_name, $phone_num, $fax_num, $email_name, $web_name, $note_name )
	{
		global $adminfile, $prefix, $db;
		$phone_name = stripslashes( FixQuotes($phone_name) );
		$add_name = stripslashes( FixQuotes($add_name) );
		$phone_num = stripslashes( FixQuotes($phone_num) );
		$fax_num = stripslashes( FixQuotes($fax_num) );
		$email_name = stripslashes( FixQuotes($email_name) );
		$web_name = stripslashes( FixQuotes($web_name) );
		$note_name = stripslashes( FixQuotes($note_name) );

		$db->sql_query( "update " . $prefix . "_contact set phone_name='$phone_name', add_name='$add_name', phone_num='$phone_num', fax_num='$fax_num', email_name='$email_name', web_name='$web_name', note_name='$note_name'  where pid='$pid'" );
		Header( "Location: " . $adminfile . ".php?op=deptdefault" );
	}


	/**
	 * deptadd()
	 * 
	 * @param mixed $dept_name
	 * @param mixed $dept_email
	 * @param mixed $dept_contact
	 * @return
	 */
	function deptadd( $dept_name, $dept_email, $dept_contact )
	{
		global $adminfile, $prefix, $db;
		$dept_name = stripslashes( FixQuotes($dept_name) );
		$dept_email = stripslashes( FixQuotes($dept_email) );
		$dept_contact = intval( $dept_contact );

		$db->sql_query( "insert into " . $prefix . "_contact_dept values (NULL,'$dept_name','$dept_email','$dept_contact')" );
		Header( "Location: " . $adminfile . ".php?op=deptdefault" );
	}


	/**
	 * phoneadd()
	 * 
	 * @param mixed $phone_name
	 * @param mixed $add_name
	 * @param mixed $phone_num
	 * @param mixed $fax_num
	 * @param mixed $email_name
	 * @param mixed $web_name
	 * @param mixed $note_name
	 * @return
	 */
	function phoneadd( $phone_name, $add_name, $phone_num, $fax_num, $email_name, $web_name, $note_name )
	{
		global $adminfile, $prefix, $db;
		$phone_name = stripslashes( FixQuotes($phone_name) );
		$add_name = stripslashes( FixQuotes($add_name) );
		$phone_num = stripslashes( FixQuotes($phone_num) );
		$fax_num = stripslashes( FixQuotes($fax_num) );
		$email_name = stripslashes( FixQuotes($email_name) );
		$web_name = stripslashes( FixQuotes($web_name) );
		$note_name = stripslashes( FixQuotes($note_name) );

		$db->sql_query( "insert into " . $prefix . "_contact values (NULL,'$phone_name', '$add_name','$phone_num', '$fax_num', '$email_name', '$web_name', '$note_name')" );
		Header( "Location: " . $adminfile . ".php?op=deptdefault" );
	}


	/**
	 * phonedelete()
	 * 
	 * @param mixed $pid
	 * @param integer $confirm
	 * @return
	 */
	function phonedelete( $pid, $confirm = 0 )
	{
		global $adminfile, $prefix, $db;
		if ( $confirm == 1 )
		{
			$db->sql_query( "delete from " . $prefix . "_contact where pid='$pid'" );
			Header( "Location: " . $adminfile . ".php?op=deptdefault" );
		}
		else
		{
			include ( "../header.php" );
			GraphicAdmin();
			echo "<a name=\"Delete\">";
			OpenTable();
			contacthomemenu();
			CloseTable();
			OpenTable();
			$result = $db->sql_query( "select * from " . $prefix . "_contact where pid='$pid'" );
			$pid = intval( $pid );
			$phone_name = stripslashes( $phone_name );
			list( $pid, $phone_name ) = $db->sql_fetchrow( $result );
			echo "<center><br><br>";
			echo "<b>" . _NSDELETEPHONE . "</b><br><br>";
			echo "" . _NSPHONEDELSURE . " <b>$phone_name</b><br>";
			echo "<br><br>";
			echo "<input type=\"button\" value=\"" . _NSYES . "\" title=\"" . _NSYES . "\" onClick=\"window.location='" . $adminfile . ".php?op=phonedelete&amp;pid=$pid&amp;confirm=1'\">&nbsp;&nbsp;";
			echo "<input type=\"button\" value=\"" . _NSNO . "\" title=\"" . _NSNO . "\" onClick=\"window.location='" . $adminfile . ".php?op=deptdefault'\">";
			echo "</center><br><br>";
			CloseTable();
			include ( "../footer.php" );
		}
	}


	/**
	 * deptdelete()
	 * 
	 * @param mixed $did
	 * @param integer $confirm
	 * @return
	 */
	function deptdelete( $did, $confirm = 0 )
	{
		global $adminfile, $prefix, $db;
		if ( $confirm == 1 )
		{
			$db->sql_query( "delete from " . $prefix . "_contact_dept where did='$did'" );
			Header( "Location: " . $adminfile . ".php?op=deptdefault" );
		}
		else
		{
			include ( "../header.php" );
			GraphicAdmin();
			echo "<a name=\"Delete\">";
			OpenTable();
			contacthomemenu();
			CloseTable();
			OpenTable();
			$result = $db->sql_query( "select did, dept_name from " . $prefix . "_contact_dept where did='$did'" );
			$did = intval( $did );
			$dept_name = stripslashes( $dept_name );
			list( $did, $dept_name ) = $db->sql_fetchrow( $result );
			echo "<center><br><br>";
			echo "<b>" . _NSDELETEDEPT . "</b><br><br>";
			echo "" . _NSDEPTDELSURE . " <b>$dept_name</b><br>";
			echo "<br><br>";
			echo "<input type=\"button\" value=\"" . _NSYES . "\" title=\"" . _NSYES . "\" onClick=\"window.location='" . $adminfile . ".php?op=deptdelete&amp;did=$did&amp;confirm=1'\">&nbsp;&nbsp;";
			echo "<input type=\"button\" value=\"" . _NSNO . "\" title=\"" . _NSNO . "\" onClick=\"window.location='" . $adminfile . ".php?op=deptdefault'\">";
			echo "</center><br><br>";
			CloseTable();
			include ( "../footer.php" );
		}
	}

	switch ( $op )
	{

		case "contacthome":
			contacthome();
			break;

		case "readcontacthome":
			readcontacthome();
			break;

		case "readcontact":
			readcontact( $pid );
			break;

		case "contactdel":
			contactdel( $pid, $ok );
			break;

		case "deptdefault":
			deptdefault();
			break;

		case "phoneedit":
			phoneedit( $pid );
			break;

		case "deptedit":
			deptedit( $did );
			break;

		case "phonemodify":
			phonemodify( $pid, $phone_name, $add_name, $phone_num, $fax_num, $email_name, $web_name, $note_name );
			break;

		case "deptmodify":
			deptmodify( $did, $dept_name, $dept_email, $dept_contact );
			break;

		case "phoneadd":
			phoneadd( $phone_name, $add_name, $phone_num, $fax_num, $email_name, $web_name, $note_name );
			break;

		case "deptadd":
			deptadd( $dept_name, $dept_email, $dept_contact );
			break;

		case "phonedelete":
			phonedelete( $pid, $confirm );
			break;

		case "deptdelete":
			deptdelete( $did, $confirm );
			break;

		case "contactemail":
			contactemail();
			break;

		case "contactemailxong":
			contactemailxong( $pid );
			break;

		case "re_contactemail":
			re_contactemail( $pid );
			break;

		case "contactemailsend":
			contactemailsend( $contactsubject, $contactemail_nhan, $contactemailtype, $contactemail_noidung );
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
