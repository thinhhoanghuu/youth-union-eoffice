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

global $datafold, $sitekey, $gfx_chk;
if ( file_exists("" . INCLUDE_PATH . "" . $datafold . "/config_Your_Account.php") )
{
	include ( "" . INCLUDE_PATH . "" . $datafold . "/config_Your_Account.php" );
}

$re = $_SERVER['QUERY_STRING'];
if ( $re == "" )
{
	$re2 = "index.php";
}
else
{
	$re2 = "modules.php?" . $re . "";
}
if ( ! defined('IS_USER') )
{
	$content = "";
	$content .= "\n<script>\n";
	$content .= "function Ulog(Forma) {\n";
	$content .= "if (Forma.username.value == \"\") {\n";
	$content .= "alert(\"" . _NICKNAME . " ?\");\n";
	$content .= "Forma.username.focus();\n";
	$content .= "return false;\n";
	$content .= "}\n";
	$content .= "dc = Forma.username.value.length;\n";
	$content .= "if(dc < " . $nick_min . "){\n";
	$content .= "alert(\"" . _NICKADJECTIVE . "1\");\n";
	$content .= "Forma.username.focus();\n";
	$content .= "return false;\n";
	$content .= "}\n";
	$content .= "if(dc > " . $nick_max . "){\n";
	$content .= "alert(\"" . _NICK2LONG . "2\");\n";
	$content .= "Forma.username.focus();\n";
	$content .= "return false;\n";
	$content .= "}\n";
	if ( extension_loaded("gd") and ($gfx_chk == 2 or $gfx_chk == 4 or $gfx_chk == 5 or $gfx_chk == 7) )
	{
		$content .= "if (Forma.gfx_check.value == \"\") {\n";
		$content .= "alert(\"" . _SECURITYCODE . " ?\");\n";
		$content .= "Forma.gfx_check.focus();\n";
		$content .= "return false;\n";
		$content .= "}\n";
	}
	$content .= "if (Forma.user_password.value == \"\") {\n";
	$content .= "alert(\"" . _PASSWORD . " ?\");\n";
	$content .= "Forma.user_password.focus();\n";
	$content .= "return false;\n";
	$content .= "}\n";
	$content .= "if (Forma.user_password.value == \"\") {\n";
	$content .= "alert(\"" . _PASSWORD . " ?\");\n";
	$content .= "Forma.user_password.focus();\n";
	$content .= "return false;\n";
	$content .= "}\n";
	$content .= "if (Forma.user_password.value != \"\") {\n";
	$content .= "dp = Forma.user_password.value.length;\n";
	$content .= "if(dp < " . $pass_min . "){\n";
	$content .= "alert(\"" . _PASSLENGTH1 . ". " . _YOUPASSMUSTBE . " " . $pass_min . " " . _YOUPASSMUSTBE2 . " " . $pass_max . " " . _YOUPASSMUSTBE3 . "\");\n";
	$content .= "Forma.user_password.focus();\n";
	$content .= "return false;\n";
	$content .= "}\n";
	$content .= "if(dc > " . $pass_max . "){\n";
	$content .= "alert(\"" . _PASSLENGTH . ". " . _YOUPASSMUSTBE . " " . $pass_min . " " . _YOUPASSMUSTBE2 . " " . $pass_max . " " . _YOUPASSMUSTBE3 . "\");\n";
	$content .= "Forma.user_password.focus();\n";
	$content .= "return false;\n";
	$content .= "}\n";
	$content .= "}\n";
	$content .= "return true; \n";
	$content .= "}\n";
	$content .= "</script>\n";
	$content .= "<form onsubmit=\"return Ulog(this)\" action=\"" . INCLUDE_PATH . "modules.php?name=Your_Account\" method=\"post\">";
	$content .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"1\">";
	$content .= "<tr><td><font class=\"content\">" . _NICKNAME . "</td><td>";
	$content .= "<input type=\"text\" name=\"username\" size=\"11\" maxlength=\"$nick_max\"></td></tr>";
	$content .= "<tr><td>" . _PASSWORD . "</td><td>";
	$content .= "<input type=\"password\" name=\"user_password\" size=\"11\" maxlength=\"$pass_max\"></td></tr>";
	if ( extension_loaded("gd") and ($gfx_chk == 2 or $gfx_chk == 4 or $gfx_chk == 5 or $gfx_chk == 7) )
	{
		$content .= "<tr><td>" . _SECURITYCODE . "</td><td><img width=\"73\" height=\"17\" src='?gfx=gfx' border='1' alt='" . _SECURITYCODE . "' title='" . _SECURITYCODE . "'></td></tr>\n";
		$content .= "<tr><td>" . _TYPESECCODE . "</td><td><input type=\"text\" NAME=\"gfx_check\" SIZE=\"11\" MAXLENGTH=\"6\"></td></tr>\n";
	}
	$content .= "<tr><td>" . _REMEMBER . "</td><td><input type=\"checkbox\" name=\"remember\" value=\"1\"></td></tr><tr><td colspan=2><input type=\"submit\" value=\"" . _LOGIN . "\"><input type=\"button\" onclick=\"window.location.href='modules.php?name=Your_Account&op=new_user';\"  value=\"" . _NEWREG . "\"></td></tr>";
	$content .= "<input type=\"hidden\" name=\"nvforw\" value=\"$re2\">";
	$content .= "<input type=\"hidden\" name=\"op\" value=\"login\">";
	$content .= "</td></tr></table></font><center></form>";
}
else
{
	$content = "<center><form action=\"" . INCLUDE_PATH . "modules.php?name=Your_Account\" method=\"post\">";
	$content .= "<input type=\"hidden\" name=\"nvforw\" value=\"$re2\">";
	$content .= "<input type=\"hidden\" name=\"op\" value=\"logout\">";
	$content .= "<input type=\"submit\" value=\"" . _LOGOUT . "\">";
	$content .= "</form></center>";
}

?>