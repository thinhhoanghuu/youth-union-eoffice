<?php

/*
* @Program:		NukeViet CMS v2.0 RC1
* @File name: 	Module Contact
* @Version: 	2.1
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
if ( defined('_MODTITLE') ) $module_title = _MODTITLE;


$index = ( defined('MOD_BLTYPE') ) ? MOD_BLTYPE : 1;
/********************************************/
$pagetitle = $module_name;

/**
 * contac_dep()
 * 
 * @return
 */
function contac_dep()
{
	global $db, $module_name, $sitename, $prefix;
	echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">";
	$result = $db->sql_query( "select * FROM " . $prefix . "_contact order by pid" );
	while ( $row = $db->sql_fetchrow($result) )
	{
		$pid = intval( $row['pid'] );
		$phone_name = stripslashes( $row['phone_name'] );
		$add_name = stripslashes( $row['add_name'] );
		$phone_num = stripslashes( $row['phone_num'] );
		$fax_num = stripslashes( $row['fax_num'] );
		$email_name = stripslashes( $row['email_name'] );
		$web_name = stripslashes( $row['web_name'] );
		$note_name = stripslashes( $row['note_name'] );
		echo "<tr><td width=\"100%\" colspan=\"2\" align=\"left\"><br><b>&nbsp;&nbsp;&nbsp;<font class=\"content\">$phone_name</font></b></td></tr>";
		echo "<tr><td width=\"30\">&nbsp;</td><td align=\"left\"><table border=\"0\" cellpadding=\"2\" cellspacing=\"2\">";
		if ( $add_name != "" )
		{
			echo "<tr><td align=\"left\">" . _ADDRESSINFO . "&nbsp;<font class=\"content\">$add_name </font></td></tr>";
		}
		if ( $phone_num != "" )
		{
			echo "<tr><td align=\"left\">" . _NSPHONENUM . ":&nbsp;<font class=\"content\">$phone_num </font></td></tr>";
		}
		if ( $fax_num != "" )
		{
			echo "<tr><td align=\"left\">" . _NSFAXNUM . ":&nbsp;<font class=\"content\">$fax_num </font></td></tr>";
		}
		if ( $email_name != "" )
		{
			echo "<tr><td align=\"left\">Email:&nbsp;<font class=\"content\"><a href=\"mailto:$email_name\">$email_name</a></font></td></tr>";
		}
		if ( $web_name != "" )
		{
			echo "<tr><td align=\"left\">Website:&nbsp;<font class=\"content\"> <a href=\"$web_name\" target=\"blank\">$web_name</a></font></td></tr>";
		}
		if ( $note_name != "" )
		{
			echo "<tr><td align=\"left\">" . _Note . ":&nbsp;<font class=\"content\">$note_name </font></td></tr>";
		}
		echo "</table></td></tr>";
	}
	echo "</table>";

}

/**
 * contact_form()
 * 
 * @return
 */
function contact_form()
{
	global $db, $module_name, $sitename, $user, $prefix, $cookie, $user_prefix, $sitekey;
	include ( "header.php" );
	$guitoi = intval( $_GET['to'] );
	OpenTable();
	echo "<center><b><font class=\"title\">" . _FORMHEADER . " $sitename</font></b>";
	echo "</center>";
	Closetable();
	echo "<br>";

	OpenTable();
	if ( defined('IS_USER') )
	{
		global $mbrow;
		$yn = $mbrow[viewuname];
		$yun = $mbrow[username];
		$ye = $mbrow[user_email];
	}
	else
	{
		$yn = "";
		$yun = "";
		$ye = "";
	}
	if ( $yn != "" )
	{
		$ns_un = $yn;
	}
	else
	{
		$ns_un = $yun;
	}
	echo "\n\n<script>\n" . " function check_data3(Forma) {\n" . "if (Forma.cname.value == \"\") {\n" . "	alert(\"" . _CONT1 . "\");\n" . "	Forma.cname.focus();\n" . "	return false;\n" . "}\n" . "if(Forma.cname.value.length < 5){\n" . "	alert(\"" . _CONT2 . "\");\n" . "	Forma.cname.focus();\n" . "	return false;\n" . "}\n" . "if(Forma.cname.value.length > 50){\n" . "alert(\"" . _CONT3 . "\");\n" . "Forma.cname.focus();\n" . "return false;\n" . "}\n" . "if (Forma.from.value == \"\") {\n" . "	alert(\"" . _CONT4 . "\");\n" . "	Forma.from.focus();\n" . "	return false;\n" . "}\n" . "if(Forma.from.value.search(\" \") > 1){\n" . "alert(\"" . _CONT5 . "\");\n" . "Forma.from.focus();\n" . "return false;\n" . "}\n" . "if(Forma.from.value.search(\"@\") < 1){\n" . "alert(\"" . _CONT5 . "\");\n" . "Forma.from.focus();\n" . "return false;\n" . "}\n" . "if (Forma.message.value == \"\") {\n" . "alert(\"" . _CONT6 . "\");\n" . "Forma.message.focus();\n" . "return false;\n" . "}\n" .
		"if(Forma.message.value.length < 25){\n" . "alert(\"" . _CONT7 . "\");\n" . "Forma.message.focus();\n" . "return false;\n" . "}\n" . "if(Forma.message.value.length > 1550){\n" . "alert(\"" . _CONT8 . "\");\n" . "Forma.message.focus();\n" . "return false;\n" . "}\n" . "if(Forma.gfx_check.value.length == \"\"){\n" . "alert(\"" . _CONT9 . "\");\n" . "Forma.message.focus();\n" . "return false;\n" . "}\n" . "if(Forma.gfx_check.value.length < 6){\n" . "alert(\"" . _CONT9 . "\");\n" . "Forma.message.focus();\n" . "return false;\n" . "}\n" . "return true; \n" . "}\n" . "</script>\n";

	echo "<form onsubmit=\"return check_data3(this)\" action=\"modules.php?name=$module_name\" method=\"post\">";
	echo "<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\" align=\"center\">\n<TBODY>\n";
	echo "<tr><td align=\"right\" valign=\"top\"><font class=\"content\">" . _YOURNAME . " <font color=\"#FF0000\">*</font>:</font></td>";
	echo "<td align=\"left\">&nbsp;<input name=\"cname\"  size=\"45\" maxlength=\"125\" value=\"$ns_un\"></td></tr>";
	echo "<tr><td align=\"right\" valign=\"top\"><font class=\"content\">" . _YOURADD . ":</font></td>";
	echo "<td align=\"left\">&nbsp;<input name=\"youradd\"  size=\"45\" maxlength=\"125\"></td></tr>";
	echo "<tr><td align=\"right\" valign=\"top\"><font class=\"content\">" . _NSPHONENUM . ":</font></td>";
	echo "<td align=\"left\">&nbsp;<input name=\"yourphone\" size=\"45\" maxlength=\"125\"></td></tr>";
	echo "<tr><td align=\"right\" valign=\"top\"><font class=\"content\">" . _YOUREMAIL . " <font color=\"#FF0000\">*</font>:</font></td>";
	echo "<td align=\"left\">&nbsp;<input name=\"from\" size=\"45\" maxlength=\"125\" value=\"$ye\"></td></tr>";
	echo "<tr><td align=\"right\" valign=\"top\"><font class=\"content\">" . _PLEASESELECT . ":</font></td>";
	echo "<td align=\"left\">&nbsp;<select name=\"dpid\">";
	$result = $db->sql_query( "select did, dept_name from " . $prefix . "_contact_dept order by did" );
	while ( list($did, $dept_name) = $db->sql_fetchrow($result) )
	{
		$did = intval( $did );
		$dept_name = stripslashes( $dept_name );
		echo "<option value=\"$did\"";
		if ( $guitoi == $did )
		{
			echo "selected";
		}
		echo ">$dept_name";
	}
	echo "</select></td></tr>";
	echo "<tr><td align=\"right\" valign=\"top\"><font class=\"content\">" . _YOURMESSAGE . " <font color=\"#FF0000\">*</font>:</font></td>";
	echo "<td align=\"left\">&nbsp;<textarea cols=\"45\" name=\"message\" rows=\"10\" ></textarea></td></tr>";

	// add Scode
	if ( extension_loaded("gd") and (! defined('IS_USER')) )
	{
		echo "<tr><td align=\"right\">" . _SECURITYCODE . ":</td><td align=\"left\">&nbsp;<img width=\"73\" height=\"17\" src='?gfx=gfx' border='1' alt='" . _SECURITYCODE . "' title='" . _SECURITYCODE . "'></td>\n";
		echo "</tr><tr>";
		echo "<td align=\"right\">" . _TYPESECCODE . " <font color=\"#FF0000\">*</font>:</td><td align=\"left\">&nbsp;<input type=\"text\" NAME=\"gfx_check\" SIZE=\"9\" MAXLENGTH=\"6\"></td>\n";
		echo "</tr><tr>";
		echo "</tr>";
	}
	// end
	echo "<tr><td colspan=\"2\" align=\"center\" valign=\"middle\">" . "<input type=\"hidden\" name=\"op\" value=\"submit\">\n" . "<input type=\"submit\" value=\"" . _SEND . "\">";
	echo "&nbsp;&nbsp;<input type=\"reset\" value=\"" . _CLEAR . "\"></td></tr>\n";
	echo "</TBODY>\n</table></form>";
	Closetable();

	$result_p = $db->sql_query( "SELECT * FROM " . $prefix . "_contact" );
	$num = $db->sql_numrows( $result_p );
	$num = intval( $num );
	if ( $num > 0 )
	{
		echo "<br>";
		OpenTable();
		echo "<font class=\"title\">" . _Addinfo . "</font>";
		contac_dep();
		Closetable();
	}

	include ( "footer.php" );
}

/**
 * submit()
 * 
 * @param mixed $dpid
 * @param mixed $cname
 * @param mixed $youradd
 * @param mixed $yourphone
 * @param mixed $from
 * @param mixed $email
 * @param mixed $message
 * @return
 */
function submit( $dpid, $cname, $youradd, $yourphone, $from, $email, $message )
{
	global $db, $module_name, $sitename, $user, $prefix, $hourdiff, $cookie, $user_prefix, $sitekey;
	include ( "header.php" );
	//them phan xu ly scode
	$gfx_check = intval( $_POST['gfx_check'] );
	if ( extension_loaded("gd") and (! defined('IS_USER')) and (! nv_capcha_txt($gfx_check)) )
	{
		$index = 1;
		OpenTable();
		echo "<br><br><p align=\"center\"><b>" . _CHECKCODE . "</b><br><br></p>";
		CloseTable();
		echo "<br>";
		// Hien lai Form
		OpenTable();
		echo "\n\n<script>\n" . " function check_data3(Forma) {\n" . "if (Forma.cname.value == \"\") {\n" . "	alert(\"" . _CONT1 . "\");\n" . "	Forma.cname.focus();\n" . "	return false;\n" . "}\n" . "if(Forma.cname.value.length < 5){\n" . "	alert(\"" . _CONT2 . "\");\n" . "	Forma.cname.focus();\n" . "	return false;\n" . "}\n" . "if(Forma.cname.value.length > 50){\n" . "alert(\"" . _CONT3 . "\");\n" . "Forma.cname.focus();\n" . "return false;\n" . "}\n" . "if (Forma.from.value == \"\") {\n" . "	alert(\"" . _CONT4 . "\");\n" . "	Forma.from.focus();\n" . "	return false;\n" . "}\n" . "if(Forma.from.value.search(\" \") > 1){\n" . "alert(\"" . _CONT5 . "\");\n" . "Forma.from.focus();\n" . "return false;\n" . "}\n" . "if(Forma.from.value.search(\"@\") < 1){\n" . "alert(\"" . _CONT5 . "\");\n" . "Forma.from.focus();\n" . "return false;\n" . "}\n" . "if (Forma.message.value == \"\") {\n" . "alert(\"" . _CONT6 . "\");\n" . "Forma.message.focus();\n" . "return false;\n" . "}\n" .
			"if(Forma.message.value.length < 25){\n" . "alert(\"" . _CONT7 . "\");\n" . "Forma.message.focus();\n" . "return false;\n" . "}\n" . "if(Forma.message.value.length > 1550){\n" . "alert(\"" . _CONT8 . "\");\n" . "Forma.message.focus();\n" . "return false;\n" . "}\n" . "if(Forma.gfx_check.value.length == \"\"){\n" . "alert(\"" . _CONT9 . "\");\n" . "Forma.message.focus();\n" . "return false;\n" . "}\n" . "if(Forma.gfx_check.value.length < 6){\n" . "alert(\"" . _CONT9 . "\");\n" . "Forma.message.focus();\n" . "return false;\n" . "}\n" . "return true; \n" . "}\n" . "</script>\n";

		echo "<form onsubmit=\"return check_data3(this)\" action=\"modules.php?name=$module_name\" method=\"post\">";
		echo "<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\" align=\"center\">\n<TBODY>\n";
		echo "<tr><td align=\"right\" valign=\"top\"><font class=\"content\">" . _YOURNAME . " <font color=\"#FF0000\">*</font>:</font></td>";
		echo "<td align=\"left\">&nbsp;<input name=\"cname\"  size=\"45\" maxlength=\"125\" value=\"$cname\"></td></tr>";
		echo "<tr><td align=\"right\" valign=\"top\"><font class=\"content\">" . _YOURADD . ":</font></td>";
		echo "<td align=\"left\">&nbsp;<input name=\"youradd\"  size=\"45\" maxlength=\"125\" value=\"$youradd\"></td></tr>";
		echo "<tr><td align=\"right\" valign=\"top\"><font class=\"content\">" . _NSPHONENUM . ":</font></td>";
		echo "<td align=\"left\">&nbsp;<input name=\"yourphone\" size=\"45\" maxlength=\"30\" value=\"$yourphone\"></td></tr>";
		echo "<tr><td align=\"right\" valign=\"top\"><font class=\"content\">" . _YOUREMAIL . " <font color=\"#FF0000\">*</font>:</font></td>";
		echo "<td align=\"left\">&nbsp;<input name=\"from\" size=\"45\" maxlength=\"125\" value=\"$from\"></td></tr>";
		echo "<tr><td align=\"right\" valign=\"top\"><font class=\"content\">" . _PLEASESELECT . ":</font></td>";
		echo "<td align=\"left\">&nbsp;<select name=\"dpid\">";
		$result = $db->sql_query( "select did, dept_name from " . $prefix . "_contact_dept order by did" );
		while ( list($did, $dept_name) = $db->sql_fetchrow($result) )
		{
			$did = intval( $did );
			$dept_name = stripslashes( $dept_name );
			echo "<option value=\"$did\"";
			if ( $dpid == $did )
			{
				echo "selected";
			}
			echo ">$dept_name";
		}
		echo "</select></td></tr>";
		echo "<tr><td align=\"right\" valign=\"top\"><font class=\"content\">" . _YOURMESSAGE . " <font color=\"#FF0000\">*</font>:</font></td>";
		echo "<td align=\"left\">&nbsp;<textarea cols=\"45\" name=\"message\" rows=\"10\" >$message</textarea></td></tr>";

		// add Scode
		if ( extension_loaded("gd") and (! defined('IS_USER')) )
		{
			echo "<tr><td align=\"right\">" . _SECURITYCODE . ":</td><td align=\"left\">&nbsp;<img width=\"73\" height=\"17\" src='?gfx=gfx' border='1' alt='" . _SECURITYCODE . "' title='" . _SECURITYCODE . "'></td>\n";
			echo "</tr><tr>";
			echo "<td align=\"right\">" . _TYPESECCODE . " <font color=\"#FF0000\">*</font>:</td><td align=\"left\">&nbsp;<input type=\"text\" NAME=\"gfx_check\" SIZE=\"9\" MAXLENGTH=\"6\"></td>\n";
			echo "</tr><tr>";
			echo "</tr>";
		}
		// end
		echo "<tr><td colspan=\"2\" align=\"center\" valign=\"middle\">" . "<input type=\"hidden\" name=\"op\" value=\"submit\">\n" . "<input type=\"submit\" value=\"" . _SEND . "\">";
		echo "&nbsp;&nbsp;<input type=\"reset\" value=\"" . _CLEAR . "\"></td></tr>\n";
		echo "</TBODY>\n</table></form>";
		Closetable();
		// End - hien lai Form
		include ( "footer.php" );
		exit;
	}
	//

	if ( ($cname == "") or ($from == "") or (strlen($message) < 20) )
	{
		OpenTable();
		echo "<br><center><font class=\"title\"><b>" . _NOFULL . "</b></font>";
		echo "<br><br><br><font class=\"title\">" . _PLEASEFULL . "</font><br><br>";
		echo " <meta http-equiv=\"refresh\" content=\"4;url=javascript:history.go(-1)\">";
		echo "[ <a href=\"javascript:history.go(-1)\">" . _BACK . "</a> ]</center><br>";
		CloseTable();
		include ( "footer.php" );
		exit;
	}

	if ( ereg("^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,6}$", $from) )
	{
	}
	else
	{
		OpenTable();
		echo "<br><center><font class=\"title\"><b>" . _INVALIDEMAIL . "</b></font>";
		echo "<br><br><br><font class=\"title\">" . _PLEASEGO . "</font><br><br>";
		echo " <meta http-equiv=\"refresh\" content=\"4;url=javascript:history.go(-1)\">";
		echo "[ <a href=\"javascript:history.go(-1)\">" . _BACK . "</a> ]</center><br>";
		CloseTable();
		include ( "footer.php" );
		exit;
	}

	if ( $dpid == "" )
	{
		OpenTable();
		echo "<br><center><font class=\"title\"><b>" . _SELECTDEPARTMENT . "</b></font>";
		echo "<br><br><br><font class=\"title\">" . _PLEASEGO3 . "</font><br><br>";
		echo " <meta http-equiv=\"refresh\" content=\"4;url=javascript:history.go(-1)\">";
		echo "[ <a href=\"javascript:history.go(-1)\">" . _BACK . "</a> ]</center><br>";
		CloseTable();
		include ( "footer.php" );
		exit;
	}

	$result3 = $db->sql_query( "SELECT * FROM " . $prefix . "_contact_dept where did='$dpid'" );
	$row3 = $db->sql_fetchrow( $result3 );
	$dept_contact = intval( $row3['dept_contact'] );
	$dept_name = stripslashes( $row3['dept_name'] );
	$dept_email = stripslashes( $row3['dept_email'] );

	if ( strlen($message) > 1500 )
	{
		OpenTable();
		echo "<br><center><font class=\"title\"><b>" . _NOMESSAGE2 . "</b></font>";
		echo "<br><br><br><font class=\"title\">" . _PLEASEGO4 . " $dept_email</font><br><br>";
		echo " <meta http-equiv=\"refresh\" content=\"4;url=javascript:history.go(-1)\">";
		echo "[ <a href=\"javascript:history.go(-1)\">" . _BACK . "</a> ]</center><br>";
		CloseTable();
		include ( "footer.php" );
		exit;
	}

	if ( ($dept_contact == 2) or ($dept_contact == 3) )
	{
		$name1 = stripslashes( FixQuotes($cname) );
		$add_name1 = stripslashes( FixQuotes($youradd) );
		$phone_num1 = stripslashes( FixQuotes($yourphone) );
		$email_name1 = stripslashes( FixQuotes($from) );
		$dip_name1 = stripslashes( FixQuotes($dpid) );
		$messenger1 = stripslashes( FixQuotes($message) );
		$ctime1 = date( "Y-m-d H:i:s" );
		ereg( "([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $ctime1, $datetime );
		$datetime = mktime( $datetime[4], $datetime[5] + $hourdiff, $datetime[6], $datetime[2], $datetime[3], $datetime[1] );
		$datetime = date( "Y-m-d H:i:s", $datetime );
		$db->sql_query( "insert into " . $prefix . "_contact_contact values (NULL,'$name1', '$add_name1','$phone_num1', '$email_name1', '$dip_name1', '$messenger1', '', '$datetime')" );
		if ( $dept_contact == 2 )
		{
			OpenTable();
			echo "<br><br><center><font class=\"title\"><b>" . _THANKYOUFOR . " </b>";
			echo "<br><br>" . _EMAILSENT . "<br>" . _GETBACK . "</center><br><br>";
			echo " <meta http-equiv=\"refresh\" content=\"4;url=modules.php?name=Contact\"><br><br>";
			CloseTable();
			include ( "footer.php" );
		}

	}

	if ( ($dept_contact == 1) or ($dept_contact == 3) )
	{
		$department = stripslashes( trim($dept_name) );
		$youradd = stripslashes( trim($youradd) );
		$yourphone = stripslashes( trim($yourphone) );
		$subject = "Phản hồi từ website $sitename";
		$from = strip_tags( trim($from) );
		$message = stripslashes( trim($message) );
		$header = "" . _FROM . ": $cname <$from>\r\n\n";
		$header .= "" . _VISITOR . ": $cname <$from>\r\n\n";
		$header .= "" . _YOURADD . ": $youradd\r\n\n";
		$header .= "" . _NSPHONENUM . ": $yourphone\r\n\n";
		$header .= "" . _TODEPARTMENT . ": $dept_name\r\n\n";
		$header .= "" . _MESSAGE . ":";
		@$send = mail( $dept_email, $subject, $message, $header );
		if ( $send == 1 )
		{
			OpenTable();
			echo "<br><br><center><font class=\"title\"><b>" . _THANKYOUFOR . " </b>";
			echo "<br><br>" . _EMAILSENT . "<br>" . _GETBACK . "</center><br><br>";
			echo " <meta http-equiv=\"refresh\" content=\"4;url=modules.php?name=Contact\"><br><br>";
			CloseTable();
			include ( "footer.php" );
		}
		else
		{
			OpenTable();
			echo "<br><br><center><font class=\"title\">" . _ERROR2 . "</font>";
			echo "<br>" . _TRYAGAIN . "<br>";
			echo "[ <a href=\"modules.php?name=Contact\">" . _BACK . "</a> ]</center>";
			CloseTable();
			include ( "footer.php" );
		}
	}
	exit();
	include ( "footer.php" );
}

if ( ! isset($op) )
{
	$op = "";
}
switch ( $op )
{

	case "submit":
		submit( $dpid, $cname, $youradd, $yourphone, $from, $email, $message );
		break;

	default:
		contact_form();
		break;

}

?>