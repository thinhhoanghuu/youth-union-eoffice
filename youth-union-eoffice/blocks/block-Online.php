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

global $onls_m, $onls_g, $statcls, $stathits1, $datafold;

if ( $onls_m != "" )
{
	$onls_m1 = explode( "|", $onls_m );
	$num_h1 = sizeof( $onls_m1 );
}
else
{
	$num_h1 = 0;
}
if ( $onls_g != "" )
{
	$onls_g1 = explode( "|", $onls_g );
	$num_h2 = sizeof( $onls_g1 );
}
else
{
	$num_h2 = 0;
}

$num_h1 = str_pad( $num_h1, 3, "0", STR_PAD_LEFT );
$num_h2 = str_pad( $num_h2, 3, "0", STR_PAD_LEFT );
$num_h3 = str_pad( $num_h1 + $num_h2, 3, "0", STR_PAD_LEFT );
$num_h4 = str_pad( $stathits1, 9, "0", STR_PAD_LEFT );

$content = "<table border=\"0\" cellspacing=\"1\" style=\"border-collapse: collapse\" width=\"100%\">\n";
$content .= "<tr>\n";
$content .= "<td width=\"17\"><img src=\"" . INCLUDE_PATH . "images/blocks/ur-anony.gif\" height=\"14\" width=\"17\"></td>\n";
$content .= "<td>&nbsp;" . _OLGUESTS . ":</td>\n";
$content .= "<td align=\"right\">" . $num_h2 . "</td>\n";
$content .= "</tr>\n";
$content .= "<tr>\n";
$content .= "<td width=\"17\"><img src=\"" . INCLUDE_PATH . "images/blocks/ur-member.gif\" height=\"14\" width=\"17\"></td>\n";
$content .= "<td>&nbsp;" . _REGISTERED . ":</td>\n";
$content .= "<td align=\"right\">" . $num_h1 . "</td>\n";
$content .= "</tr>\n";
$content .= "<tr>\n";
$content .= "<td width=\"17\"><img src=\"" . INCLUDE_PATH . "images/blocks/ur-registered.gif\" height=\"14\" width=\"17\"></td>\n";
$content .= "<td>&nbsp;" . _OLTOTAL . "</td>\n";
$content .= "<td align=\"right\">" . $num_h3 . "</td>\n";
$content .= "</tr>\n";
$content .= "<tr>\n";
$content .= "<td width=\"17\"><img src=\"" . INCLUDE_PATH . "images/blocks/group-4.gif\" height=\"14\" width=\"17\"></td>\n";
$content .= "<td>&nbsp;" . _HITSOL . "</td>\n";
$content .= "<td align=\"right\">" . $num_h4 . "</td>\n";
$content .= "</tr>\n";
$content .= "</table>\n";
if ( file_exists("" . INCLUDE_PATH . "" . $datafold . "/ulist.php") )
{
	include_once ( "" . INCLUDE_PATH . "" . $datafold . "/ulist.php" );
	if ( $onls_m != "" )
	{
		$content .= "<hr>\n";
		$content .= "<table border=\"0\" cellspacing=\"1\" style=\"border-collapse: collapse\" width=\"100%\">\n";
		for ( $l = 0; $l < sizeof($onls_m1); $l++ )
		{
			$onls_m2 = explode( ":", $onls_m1[$l] );
			if ( isset($udt[intval($onls_m2[0])]) )
			{
				$onl_m_name = explode( "|", $udt[intval($onls_m2[0])] );
				$onl_m_name = $onl_m_name[1];
				$ln = $l + 1;
				$ln = str_pad( $ln, 2, "0", STR_PAD_LEFT );
				$content .= "<tr>\n";
				$content .= "<td width=\"10\">" . $ln . "</td>\n";
				$content .= "<td align=\"right\"><a href=\"" . INCLUDE_PATH . "modules.php?name=Your_Account&op=userinfo&user_id=" . intval( $onls_m2[0] ) . "\">" . $onl_m_name . "</td>\n";
				$content .= "</tr>\n";
			}
		}
		$content .= "</table>\n";
	}
}
$content .= "\n<center>" . _YOURIP . ": " . $_SERVER["REMOTE_ADDR"] . "</center>";

?>