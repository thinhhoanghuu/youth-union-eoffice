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

global $hourdiff, $month, $adminfile, $db, $prefix, $home, $module_name, $multilingual, $currentlang;
$timeadjust = ( $hourdiff * 60 );

$ac_font_size = "10";

$ac_font_color = "black";

$ac_main_color = "white";

$ac_second_color = "#EAEAEA";

$ac_navigator = true;


$mon_name = array( _JANUARY, _FEBRUARY, _MARCH, _APRIL, _MAY, _JUNE, _JULY, _AUGUST, _SEPTEMBER, _OCTOBER, _NOVEMBER, _DECEMBER );
$nod = array( 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31 );
if ( ! isset($month) )
{
	$ac_month = date( "n", time() + $timeadjust );
	$ac_year = date( "Y", time() + $timeadjust );
	$ac_j_dom = date( "j", time() + $timeadjust );
	$ac_j_dow = date( "w", time() + $timeadjust );
}
else
{
	list( $ac_month, $ac_year ) = explode( "-", $month );
	if ( ! $ac_year || $ac_year == "" ) $ac_year = date( "Y" );
	if ( $ac_year < 1980 ) $ac_year = 1980;
	if ( $ac_year > 2030 ) $ac_year = 2030;
	if ( $ac_month != date("n", time() + $timeadjust) or $ac_year != date("Y", time() + $timeadjust) )
	{
		$ac_j_dom = 1;
		$ac_j_dow = date( "w", mktime(0, 0, 0, $ac_month, 1, $ac_year) );
	}
	else
	{
		$ac_j_dom = date( "j", time() + $timeadjust );
		$ac_j_dow = date( "w", time() + $timeadjust );
	}
}
if ( $ac_year % 4 == 0 )
{
	$nod[1] = 29;
}
$temp_month = $ac_month + 1;
if ( $temp_month != 13 )
{
	$ac_month_next = "$temp_month-$ac_year";
}
else
{
	$temp_year = $ac_year + 1;
	$ac_month_next = "1-$temp_year";
}
$temp_month = $ac_month - 1;
if ( $temp_month != 0 )
{
	$ac_month_prev = "$temp_month-$ac_year";
}
else
{
	$temp_year = $ac_year - 1;
	$ac_month_prev = "12-$temp_year";
}
$temp_year = $ac_year + 1;
$ac_year_next = "$ac_month-$temp_year";
$temp_year = $ac_year - 1;
$ac_year_prev = "$ac_month-$temp_year";
$ac_mon = $mon_name[$ac_month - 1];
if ( $ac_j_dow == 0 ) $ac_j_dow = 7;
$ac_1_dow = $ac_j_dow - ( $ac_j_dom % 7 - 1 );
if ( $ac_1_dow < 1 ) $ac_1_dow += 7;
if ( $ac_1_dow > 7 ) $ac_1_dow -= 7;
$ac_nod = $nod[$ac_month - 1];
$ac_now = 5;
if ( $ac_1_dow - 1 + $ac_nod < 29 )
{
	$ac_now = 4;
}
else
	if ( $ac_1_dow - 1 + $ac_nod > 35 )
	{
		$ac_now = 6;
	}

if ( $ac_month != date("n") or $ac_year != date("Y") ) $ac_j_dom = -10;

$content = "<a name=\"cal\"></a><table width=\"100%\" align=center border=0 cellspacing=1 cellpadding=1 bgcolor=\"#C0C0C0\" style=\"border-collapse: collapse; font-size: $ac_font_size px; color: $ac_font_color; font-family: verdana\">";
$content .= "<tr bgcolor=$ac_second_color><td colspan=7 align=center>$ac_mon $ac_year</td></tr>";
$content .= "<tr bgcolor=$ac_second_color><td align=center>" . _MON . "</td><td align=center>" . _TUE . "</td><td align=center>" . _WED . "</td><td align=center>" . _THU . "</td><td align=center>" . _FRI . "</td><td align=center><font color=red>" . _SAT . "</font></td><td align=center><font color=red>" . _SUN . "</font></td>";

if ( $multilingual == 1 )
{
	$querylang = "AND (alanguage='$currentlang' OR alanguage='')";
}
else
{
	$querylang = "";
}
$ac2_month = $ac_month;
if ( $ac2_month < 10 )
{
	$ac2_month = "0$ac2_month";
}
$time1_bl = "" . $ac_year . "-" . $ac2_month . "-01 00:00:00";
$time2_bl = "" . $ac_year . "-" . $ac2_month . "-" . $ac_nod . " 23:59:59";
$timeinterval_bl = "time >='$time1_bl' AND time<='$time2_bl'";
$intervaltime = array();
$result_bl = $db->sql_query( "SELECT time FROM " . $prefix . "_stories WHERE $timeinterval_bl $querylang" );
while ( $row_bl = $db->sql_fetchrow($result_bl) )
{
	$time3_bl = explode( " ", $row_bl['time'] );
	if ( ! in_array($time3_bl[0], $intervaltime) )
	{
		$intervaltime[] = "$time3_bl[0]";
	}
}
for ( $i = 0; $i < $ac_now * 7; $i++ )
{
	if ( $i % 7 == 0 )
	{
		$content .= "</tr>\n<tr align=center bgcolor=$ac_main_color>\n\t";
	}
	if ( $i - $ac_1_dow + 2 != $ac_j_dom )
	{
		$content .= "<td>";
	}
	else
	{
		$content .= "<td align=center bgcolor=$ac_second_color>";
	}
	if ( ($i < $ac_1_dow - 1) || ($i > $ac_nod + $ac_1_dow - 2) )
	{
		$content .= "&nbsp;";
	}
	else
	{
		$xday = $i - $ac_1_dow + 2;
		$yday = "$xday";
		if ( ($i % 7 == 6) || ($i % 7 == 5) )
		{
			$yday = "<font color=red>$yday</font>";
		}
		if ( $xday < 10 )
		{
			$xday = "0$xday";
		}
		$xmonth = $ac_month;
		if ( $xmonth < 10 )
		{
			$xmonth = "0$xmonth";
		}

		$xdate = date( "Y-m-d", mktime(0, 0, 0, $xmonth, $xday, $ac_year) );
		if ( in_array($xdate, $intervaltime) )
		{
			$content .= "<a href=\"" . INCLUDE_PATH . "modules.php?name=News&op=archive&day=$xday&month=$xmonth&year=$ac_year\"><b>$yday</b></a>";
		}
		else
		{
			$content .= "$yday";
		}
	}
	$content .= "</td>\n\t";
}
if ( $ac_navigator )
{
	if ( defined('NV_ADMIN') )
	{
		$xxx = "" . $adminfile . ".php?";
		$yyy = "" . $adminfile . ".php";
	} elseif ( $home == 1 )
	{
		$xxx = "" . INCLUDE_PATH . "index.php?";
		$yyy = "" . INCLUDE_PATH . "index.php";
	}
	else
	{
		$xxx = "" . INCLUDE_PATH . "modules.php?name=$module_name&";
		$yyy = "" . INCLUDE_PATH . "modules.php?name=$module_name";
	}
	$content .= "</tr><tr bgcolor=$ac_second_color><td colspan=7 align=center style=\"font-size: 8pt;\"><b>";
	$content .= "<a href=\"" . $xxx . "month=$ac_year_prev#cal\" title=\"" . _LASTYEAR . "\" style=\"color:black;text-decoration: none;\">&le;</a>&nbsp;";
	$content .= "<a href=\"" . $xxx . "month=$ac_month_prev#cal\" title=\"" . _LASTMONTH . "\" style=\"color:black;text-decoration: none;\">&lt;</a>&nbsp;";
	$content .= "<a href=\"" . $yyy . "#cal\" title=\"" . _CURMONTH . "\" style=\"color:black;text-decoration: none;\">&equiv;</a>&nbsp;";
	$content .= "<a href=\"" . $xxx . "month=$ac_month_next#cal\" title=\"" . _NEXTMONTH . "\" style=\"color:black;text-decoration: none;\">&gt;</a>&nbsp;";
	$content .= "<a href=\"" . $xxx . "month=$ac_year_next#cal\" title=\"" . _NEXTYEAR . "\" style=\"color:black;text-decoration: none;\">&ge;</a>";
	$content .= "</b></td>";
}
$content .= "</tr></table>";

?>