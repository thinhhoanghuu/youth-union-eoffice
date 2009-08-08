<?php

/*
* @Program:		NukeViet CMS v2.0 RC1
* @File name: 	Module AutoTranslate
* @Author: 		Nguyen The Hung (Nukeviet Group)
* @Version: 	3.0
* @Date: 		01.05.2009
* @Website: 	www.nukeviet.vn
* @Contact: 	admin@mangxd.com
* @Copyright: 	(C) 2009
* @License: 	http://opensource.org/licenses/gpl-license.php GNU Public License
*/

if ( ! defined('NV_SYSTEM') )
{
	die( "You can't access this file directly...<br>Rat tiec, ban khong the truy cap truc tiep file nay!<br><hr><center>Copyright 2009 by NukeViet.VN <br><a href='javascript:history.back(1)'><b>[Quay lai]</b></a></center>" );
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

include ( "header.php" );

OpenTable();
echo "<center><div id=\"introNew\"> <strong><font color=\"#ff9900\" face=\"Arial, Helvetica, sans-serif\" size=\"3\">" . _AUTONAME . "</font></strong>";
echo "\n<div style=\"float: center; margin-left: 10px; margin-top: 2px;\" id=\"poweredby\">" . _AUTOIPOW1 . " <img src=\"http://www.google.com/logos/Logo_25wht.gif\" align=\"absmiddle\">" . _AUTOIPOW3 . "</div>";
echo "</center>\n";
CloseTable();
echo "<br>";
OpenTable();
echo "<table cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" id=\"table2\">
<tbody>
<tr>
<td width=\"80%\" valign=\"top\" align=\"left\" style=\"padding-left: 10px; padding-top: 10px;\">";
echo "<font class=\"storytitle\"><b>" . _AUTOSOURCE . "</b></font><br>";
echo "<form name=\"loading\">\n" . "\n  <script src=\"modules/" . $module_name . "/img3/js_api\" type=\"text/javascript\"></script>" . "\n  <script src=\"modules/" . $module_name . "/img3/trans\" type=\"text/javascript\"></script>" . "\n <script language=\"javascript\" type=\"text/javascript\">" . "\n function limitText(limitField, limitCount, limitNum) {" . "\n	if (limitField.value.length > limitNum) {" . "\n		limitField.value = limitField.value.substring(0, limitNum);" . "\n	} else {" . "\n		limitCount.value = limitNum - limitField.value.length;" . "\n	}" . "\n }" . "\n</script>\n" . "\n (" . _AUTOMAX . "" . "\n <input readonly=\"readonly\" name=\"countdownall\" size=\"3\" value=\"500\" style=\"border: 0px none ; font-size: 10px; font-family: Arial,Helvetica,sans-serif; width: 18px;\" type=\"text\">" . "\n " . _AUTOLEFT . ")" . "\n<label>" . "\n<textarea cols=\"20\" rows=\"5\" name=\"originaltextall\" style=\"width: 99%; height: 100px; overflow: auto;\" onkeyup=\"limitText(this.form.originaltextall,this.form.countdownall,500);\" onkeydown=\"limitText(this.form.originaltextall,this.form.countdownall,500);\" id=\"originaltextall\"></textarea> " .
	"\n</label>" . "\n  <strong>" . _AUTOFROM . " " . "\n  <select id=\"sourcelanguageall\" name=\"sourcelanguageall\">" . "\n  <option value=\"\" selected=\"selected\">" . _AUTOIN . "</option>" . "\n	<option value=\"en\">English</option> " . "\n    <option value=\"vi\">Vietnamese</option> " . "\n    <option value=\"ar\">Arabic</option> " . "\n    <option value=\"bg\">Bulgarian</option> " . "\n    <option value=\"ca\">Catalan</option> " . "\n    <option value=\"zh-TW\">Chinese (traditional)</option>" . "\n    <option value=\"zh-CN\">Chinese (simplified)</option>" . "\n    <option value=\"hr\">Croatian</option> " . "\n    <option value=\"cs\">Czech</option>" . "\n    <option value=\"da\">Danish</option> " . "\n    <option value=\"nl\">Dutch</option>" . "\n    <option value=\"tl\">Filipino</option> " . "\n    <option value=\"fi\">Finnish</option>" . "\n    <option value=\"fr\">French</option> " . "\n    <option value=\"hi\">Hindi</option>" . "\n    <option value=\"id\">Indonesian</option> " .
	"\n    <option value=\"it\">Italian</option>" . "\n    <option value=\"ja\">Japanese</option> " . "\n    <option value=\"ko\">Korean</option>" . "\n    <option value=\"lv\">Latvian</option> " . "\n    <option value=\"lt\">Lithuanian</option>" . "\n    <option value=\"no\">Norwegian</option> " . "\n    <option value=\"pl\">Polish</option>" . "\n    <option value=\"pt-PT\">Portuguese</option> " . "\n    <option value=\"ro\">Romanian</option> " . "\n    <option value=\"ru\">Russian</option> " . "\n    <option value=\"sr\">Serbian</option>" . "\n    <option value=\"th\">ThaiLan</option> " . "\n    <option value=\"sk\">Slovak</option> " . "\n    <option value=\"sl\">Slovenian</option> " . "\n    <option value=\"es\">Spanish</option> " . "\n    <option value=\"sv\">Swedish</option> " . "\n    <option value=\"uk\">Ukrainian</option></select> " . _AUTOTO . "" . "\n    <select id=\"targetlanguageall\" name=\"targetlanguageall\">" . "\n    <option value=\"vi\" selected=\"selected\">Vietnamese</option>" .
	"\n    <option value=\"en\">English</option> " . "\n    <option value=\"ar\">Arabic</option> " . "\n    <option value=\"bg\">Bulgarian</option> " . "\n    <option value=\"ca\">Catalan</option> " . "\n    <option value=\"zh-TW\">Chinese (traditional)</option> " . "\n    <option value=\"zh-CN\">Chinese (simplified)</option> " . "\n    <option value=\"hr\">Croatian</option> " . "\n    <option value=\"cs\">Czech</option> " . "\n    <option value=\"th\">ThaiLan</option>" . "\n    <option value=\"da\">Danish</option> " . "\n    <option value=\"nl\">Dutch</option> " . "\n    <option value=\"tl\">Filipino</option> " . "\n    <option value=\"fi\">Finnish</option> " . "\n    <option value=\"fr\">French</option> " . "\n    <option value=\"hi\">Hindi</option> " . "\n    <option value=\"id\">Indonesian</option> " . "\n    <option value=\"it\">Italian</option> " . "\n    <option value=\"ja\">Japanese</option> " . "\n    <option value=\"ko\">Korean</option> " . "\n    <option value=\"lv\">Latvian</option> " .
	"\n    <option value=\"lt\">Lithuanian</option> " . "\n    <option value=\"no\">Norwegian</option> " . "\n    <option value=\"pl\">Polish</option> " . "\n    <option value=\"pt-PT\">Portuguese</option> " . "\n    <option value=\"ro\">Romanian</option> " . "\n    <option value=\"ru\">Russian</option> " . "\n    <option value=\"sr\">Serbian</option> " . "\n    <option value=\"sk\">Slovak</option> " . "\n    <option value=\"sl\">Slovenian</option> " . "\n    <option value=\"es\">Spanish</option> " . "\n    <option value=\"sv\">Swedish</option> " . "\n    <option value=\"uk\">Ukrainian</option> " . "\n    </select> " . "\n</strong>" . "\n  <label><input id=\"translatesubmitall\" onclick=\"doTranslationAll();\" value=\"" . _AUTODO . "\" name=\"translatesubmitall\" type=\"button\"></label>" . "\n</form><br>" . "\n<div id=\"resultDiv\">" . "\n<font class=\"storytitle\">" . _AUTORESULT . "</font><br>" . "\n<textarea cols=\"20\" rows=\"5\" name=\"resultdiv\" readonly=\"readonly\" id=\"result\" style=\"width: 99%; height: 100px;\"></textarea>" .
	"\n</div>";
echo "</td></tr></tbody></table>";

echo "<br>";

echo "<table cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" id=\"table2\">
<tbody>
<tr>
<td width=\"80%\" valign=\"top\" align=\"left\" style=\"padding-left: 10px; padding-top: 10px;\">";
echo "\n      <font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\" color=\"#333333\">" . "\n	  <font class=\"storytitle\">" . _AUTOINST . ":</font>" . "\n      <ul>" . "\n          <li>" . _AUTOINST1 . "</li>" . "\n          <li>" . _AUTOINST2 . "</li>" . "\n      <li>" . _AUTOINST3 . "</li>" . "\n      <li>" . _AUTOINST4 . "</li>" . "\n      </ul></font>";
include ( "modules/" . $module_name . "/img3/firefox" );
echo "\n    </td></tr>" . "\n  </tbody></table>" . "\n</div>";
echo "</td></tr></tbody></table>";

echo "<br>";
OpenTable();
echo "<center>" . _AUTOPOWER . "</center>";
CloseTable();
include ( "footer.php" );

?>