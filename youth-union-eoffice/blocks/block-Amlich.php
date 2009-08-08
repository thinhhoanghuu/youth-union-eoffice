<?php
/*
 * @Program:	NukeViet CMS v2.0 RC1
 * @File name: 	Block Vietnam Lunar-Calendar
 * @Author: 	Nguyen The Hung (Nukeviet Group)
 * @Version: 	2.0
 * @Date: 		01.05.2009
 * @Website: 	http://mangvn.org
 * @Copyright: 	(C) 2009
 * @License: 	http://opensource.org/licenses/gpl-license.php GNU Public License
 */
 
if ((!defined('NV_SYSTEM')) AND (!defined('NV_ADMIN'))) {
    Header("Location: ../index.php");
    exit;
}
$content  =  "<!--\n";
$content  .= "Begin: Vietnam Lunar-Calendar, author: Hồ Ngọc Đức | Block âm lịch - http://mangvn.org \n";
$content  .= "//-->\n";
$content  .= "<center><a name=\"amlich\"></a>\n";
$content  .= "<script language=\"JavaScript\" src=\"js/amlich.js\" type=\"text/javascript\"></script>\n";
$content  .= "<script language=\"JavaScript\">showVietCal();</script>\n";
$content  .= "<script language=\"JavaScript\">document.writeln(printSelectedMonth()); </script>\n";
$content  .= "<table border=\"0\" width=\"100%\"  style=\"border-collapse: collapse; font-size: 10 px; color: black; font-family: verdana\">\n";
$content  .= "	<tr>\n";
$content  .= "		<td align=\"center\">\n";
$content  .= "<form name=\"SelectMonth\" action=\"#amlich\"\">\n";
$content  .= ""._UMONTH." <select name=\"month\"><option value=\"1\">1<option value=\"2\">2<option value=\"3\">3<option value=\"4\">4<option value=\"5\">5<option value=\"6\">6<option value=\"7\">7<option value=\"8\">8<option value=\"9\">9<option value=\"10\">10<option value=\"11\">11<option value=\"12\">12</select>\n";
$content  .= "<input type=\"button\" value=\""._VIEW."\" onClick=\"javascript:viewMonth(parseInt(month.value), parseInt(year.value));\"><br>\n";
$content  .= ""._YEAR."&nbsp; <INPUT NAME=\"year\" size=4 value=\"2005\"> \n";
$content  .= "<input type=\"button\" value=\""._VIEW."\" onClick=\"javascript:viewYear(parseInt(year.value));\">\n";
$content  .= "</form>\n";
$content  .= "		</td>\n";
$content  .= "	</tr>\n";
$content  .= "</table>\n";
$content  .= "<script type=\"text/javascript\">\n";
$content  .= "<!--\n";
$content  .= "	getSelectedMonth();\n";
$content  .= "	document.SelectMonth.month.value = currentMonth;\n";
$content  .= "	document.SelectMonth.year.value = currentYear;\n";
$content  .= "	function viewMonth(mm, yy) {\n";
$content  .= "		window.location = window.location.pathname + '?yy='+yy+'&mm='+mm+'#amlich';\n";
$content  .= "	}\n";
$content  .= "	function viewYear(yy) {\n";
$content  .= "		var loc = 'js/amlich-namnay.html?yy='+yy;\n";
$content  .= "		var win2702 = window.open(loc, \"win2702\", \"menubar=yes,scrollbars=yes,resizable=yes\");\n";
$content  .= "	}\n";
$content  .= "//-->\n";
$content  .= "</script><hr></center><b>"._QUOTE.":</b><br><center>\n";
// chèn danh ngôn vào
global $datafold, $themepath, $currentlang;
if (file_exists("".INCLUDE_PATH."".$datafold."/quote-".$currentlang.".txt")) {
	$frases = file("".INCLUDE_PATH."".$datafold."/quote-".$currentlang.".txt");
	$numero_frases = count($frases);
	if ($numero_frases != 0) {$numero_frases--;}
	mt_srand((double)microtime()*1000000);
	$numero_aleatorio = mt_rand(0,$numero_frases);
	$content  .= "$frases[$numero_aleatorio]";
} elseif (file_exists("".INCLUDE_PATH."".$datafold."/quote-english.txt")) {
	$frases = file("".INCLUDE_PATH."".$datafold."/quote-english.txt");
	$numero_frases = count($frases);
	if ($numero_frases != 0) {$numero_frases--;}
	mt_srand((double)microtime()*1000000);
	$numero_aleatorio = mt_rand(0,$numero_frases);
	$content  .= "$frases[$numero_aleatorio]";
} 
// hết đoạn chèn danh ngôn
$content  .= "</center>\n";
$content  .= "<!--\n";
$content  .= "End: Vietnam Lunar-Calendar, author: Hồ Ngọc Đức | Block âm lịch - http://mangvn.org \n";
$content  .= "//-->\n";
?>