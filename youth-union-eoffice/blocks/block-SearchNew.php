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

$content = "" . _SITESEARCH . "<br><table align=\"center\"><tr><form action=\"modules.php?name=Search\" method=\"post\">\n";
$content .= "<td align=\"right\">\n";
$content .= "<input type=\"text\" name=\"query\" size=\"12\" maxLength=\"57\" onblur=\"if (this.value==''){this.value='" . _SEARCH . "';}\" onfocus=\"if (this.value=='" . _SEARCH . "') {this.value = '';}\" value=\"" . _SEARCH . "\" style=\"text-align: center\">\n";
$content .= "</td>\n";
$content .= "<td>\n";
$content .= "<input type=\"image\" src=\"images/go.gif\" alt=\"" . _SEARCH . "\" value=\"" . _SEARCH . "\" style=\"border: 0\">\n";
$content .= "</td></form></tr></table>\n";

$content .= "" . _GSEARCH . "<br><!-- Google CSE code begins -->\n";
$content .= "<center><form action=\"\" id=\"searchbox_001283472661151608246:uy1j4refgey\" onsubmit=\"return false;\">\n";
$content .= "  <div>\n";
$content .= "    <input type=\"text\" name=\"q\" size=\"16\"/>\n";
$content .= "    <input type=\"submit\" value=\"" . _SEARCH . "\"/>\n";
$content .= "  </div>\n";
$content .= "</form>\n";
$content .= "<script type=\"text/javascript\" src=\"http://www.google.com/coop/cse/brand?form=searchbox_001283472661151608246%3Auy1j4refgey&lang=en\"></script>\n";
$content .= "<div id=\"results_001283472661151608246:uy1j4refgey\" style=\"display:none\">\n";
$content .= "  <div class=\"cse-closeResults\">\n";
$content .= "    <a>&times; " . _CLOSE . "</a>\n";
$content .= "  </div>\n";
$content .= "  <div class=\"cse-resultsContainer\"></div>\n";
$content .= "</div>\n";
$content .= "<style type=\"text/css\">\n";
$content .= "@import url(http://www.google.com/cse/api/overlay.css);\n";
$content .= "</style>\n";
$content .= "<script src=\"http://www.google.com/uds/api?file=uds.js&v=1.0&key=ABQIAAAABoOd2tLZui9yn9TeD6tfRhRB8dIwecbIc7JxUZFle3kttWFkzhRGeIQ2_W4sO86AR8TRCmfbomG2CQ&hl=en\" type=\"text/javascript\"></script>\n";
$content .= "<script src=\"http://www.google.com/cse/api/overlay.js\" type=\"text/javascript\"></script>\n";
$content .= "<script type=\"text/javascript\">\n";
$content .= "function OnLoad() {\n";
$content .= "  new CSEOverlay(\"001283472661151608246:uy1j4refgey\",\n";
$content .= "                 document.getElementById(\"searchbox_001283472661151608246:uy1j4refgey\"),\n";
$content .= "                 document.getElementById(\"results_001283472661151608246:uy1j4refgey\"));\n";
$content .= "}\n";
$content .= "GSearch.setOnLoadCallback(OnLoad);\n";
$content .= "</script>\n";
$content .= "<!-- Google CSE Code ends -->\n";
$content .= "</center>\n";

?>