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

if ( ! defined('NV_MAINFILE') )
{
	die( 'Stop!!!' );
}

if ( $counteract and ! defined('NV_ADMIN') )
{
	list( $online, $statclients, $stathits ) = $db->sql_fetchrow( $db->sql_query("SELECT * FROM `" . $prefix . "_stats`") );
	$past = time() - 60;
	$onls_g = "";
	$onls_m = "";
	$uname = $client_ip;
	if ( defined('IS_USER') ) $uname = intval( $user_ar[0] );
	if ( ! empty($online) )
	{
		$online1 = explode( "|", $online );
		$g = $m = 0;
		$g_online[0] = $m_online[0] = "";
		for ( $l = 0; $l < sizeof($online1); $l++ )
		{
			$online2 = explode( ":", $online1[$l] );
			if ( intval($online2[2]) > $past )
			{
				if ( $online2[1] == 1 )
				{
					if ( $onls_g != "" ) $onls_g .= "|";
					$onls_g .= ( $online2[0] != $uname ) ? $online1[$l] : $online2[0] . ":1:" . time();
					$g_online[$g] = $online2[0];
					$g++;
				}
				else
				{
					if ( $onls_m != "" ) $onls_m .= "|";
					$onls_m .= ( $online2[0] != $uname ) ? $online1[$l] : $online2[0] . ":0:" . time();
					$m_online[$m] = $online2[0];
					$m++;
				}
			}
		}
		if ( ! in_array($uname, $g_online) and ! in_array($uname, $m_online) )
		{
			if ( defined('IS_USER') )
			{
				if ( $onls_m != "" ) $onls_m .= "|";
				$onls_m .= $uname . ":0:" . time();
			}
			else
			{
				if ( $onls_g != "" ) $onls_g .= "|";
				$onls_g .= $uname . ":1:" . time();
			}
		}
	}
	else
	{
		if ( defined('IS_USER') )
		{
			$onls_m = $uname . ":0:" . time();
		}
		else
		{
			$onls_g = $uname . ":1:" . time();
		}
	}
	if ( empty($onls_g) ) $onls_t = $onls_m;
	elseif ( empty($onls_m) ) $onls_t = $onls_g;
	elseif ( ! empty($onls_g) and ! empty($onls_m) ) $onls_t = $onls_g . "|" . $onls_m;

	$stats_time = time() - intval( $timecount );
	$statcls = "";
	$stathits1 = intval( $stathits );
	if ( ! empty($statclients) )
	{
		$statclients_ar = explode( "|", $statclients );
		$m = 0;
		$statip[0] = "";
		for ( $l = 0; $l < sizeof($statclients_ar); $l++ )
		{
			$statclients_ar2 = explode( ":", $statclients_ar[$l] );
			if ( intval($statclients_ar2[1]) > $stats_time )
			{
				if ( $statcls != "" ) $statcls .= "|";
				$statcls .= $statclients_ar[$l];
				$statip[$m] = $statclients_ar2[0];
				$m++;
			}
		}
		if ( ! in_array($client_ip, $statip) )
		{
			if ( $statcls != "" ) $statcls .= "|";
			$statcls .= $client_ip . ":" . time();
			$stathits1++;
		}
	}
	else
	{
		$statcls = $client_ip . ":" . time();
		$stathits1++;
	}
	if ( $onls_t != $online || $statcls != $statclients || $stathits1 != $stathits )
	{
		$db->sql_query( "UPDATE `" . $prefix . "_stats` SET `online`='" . $onls_t . "', `clients`='" . $statcls . "', `hits`='" . $stathits1 . "'" );
	}
}

?>