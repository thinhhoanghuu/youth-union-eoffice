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

if ( defined('IS_SPADMIN') )
{
	$checkmodname = "Setban";
	if ( file_exists("language/" . $checkmodname . "_" . $currentlang . ".php") )
	{
		include_once ( "language/" . $checkmodname . "_" . $currentlang . ".php" );
	}
	if ( file_exists("../$datafold/config_" . $checkmodname . ".php") )
	{
		include_once ( "../$datafold/config_" . $checkmodname . ".php" );
	}
	define( "IMG_PATH", "../images/modules/" . $checkmodname . "/" );


	/**
	 * ConfigureBan()
	 * 
	 * @param mixed $bad_ip
	 * @return
	 */
	function ConfigureBan( $bad_ip )
	{
		global $adminfile, $datafold, $ip_ban;
		include ( "../header.php" );
		GraphicAdmin();
		OpenTable();
		echo "<center><font class=\"title\"><b>" . _BANADMIN . "</b></font></center>";
		CloseTable();
		echo "<br>";
		OpenTable();
		$ip_ban_list = ereg_replace( "\|", ", ", $ip_ban );
		echo "<form action=\"" . $adminfile . ".php\" method=\"post\">\n" . "<table border=\"0\"><tr><td valign=top>\n" . "<font class=\"option\"><b>" . _BANBADADRES . " </b></font></td>" . "<td>$ip_ban_list</td></tr>\n" . "<tr><td>" . _BANADDADRES . "</td>" . "<td><input type=\"text\" size=\"30\" name=\"ip_ban_add\" value=\"$bad_ip\"></td></tr>" . "<tr><td>" . _BANDELADRES . "</td>" . "<td><select name=\"ip_ban_del\"><option value=\"\" selected></option>\n";

		$ip_ban_array = explode( "|", $ip_ban );
		$ip_num = count( $ip_ban_array ) - 1;
		$i = 0;
		while ( $i <= $ip_num )
		{
			echo "<option value=\"$ip_ban_array[$i]\">$ip_ban_array[$i]</option>\n";
			$i++;
		}

		echo "</select>" . "<input type=\"hidden\" name=\"op\" value=\"SaveSetBan\">\n";
		echo "</td></tr><tr><td></td><td>" . "<input type=\"submit\" value=\"" . _BANSAVED . "\"></center>\n" . "</form>" . "</td></tr>\n" . "</table>\n";
		CloseTable();
		echo "<br>";
		include ( "../footer.php" );
	}

	/**
	 * SaveSetBan()
	 * 
	 * @param mixed $ip_ban_add
	 * @param mixed $uname_ban_add
	 * @param mixed $ip_ban_del
	 * @param mixed $uname_ban_del
	 * @return
	 */
	function SaveSetBan( $ip_ban_add, $uname_ban_add, $ip_ban_del, $uname_ban_del )
	{
		global $checkmodname, $adminfile, $datafold, $ip_ban;
		$ip_ban_add = trim( $ip_ban_add );
		$ip_ban_del = trim( $ip_ban_del );
		$ip_ban = trim( $ip_ban );
		if ( $ip_ban_del != "" )
		{
			$ip_ban = ereg_replace( "\|$ip_ban_del", "", $ip_ban );
			$ip_ban = ereg_replace( "" . $ip_ban_del . "\|", "", $ip_ban );
			$ip_ban = ereg_replace( "$ip_ban_del", "", $ip_ban );
		}


		if ( $ip_ban_add != "" )
		{
			if ( $ip_ban != "" )
			{
				$ip_ban_add = "" . $ip_ban . "|$ip_ban_add";
			}
		}
		else
		{
			$ip_ban_add = $ip_ban;
		}

		@chmod( "../$datafold/config_" . $checkmodname . ".php", 0777 );
		@$file = fopen( "../$datafold/config_" . $checkmodname . ".php", "w" );
		$content = "<?php\n\n";
		$fctime = date( "d-m-Y H:i:s", filectime("../$datafold/config_" . $checkmodname . ".php") );
		$fmtime = date( "d-m-Y H:i:s" );
		$content .= "// File: config_" . $checkmodname . ".php.\n// Created: $fctime.\n// Modified: $fmtime.\n// Do not change anything in this file!\n\n";
		$content .= "if ((!defined('NV_SYSTEM')) AND (!defined('NV_ADMIN'))) {\n";
		$content .= "die();\n";
		$content .= "}\n";
		$content .= "\$ip_ban = \"$ip_ban_add\";\n";
		$content .= "\n";
		$content .= "?>";
		@$writefile = fwrite( $file, $content );
		fclose( $file );
		@chmod( "../$datafold/config_" . $checkmodname . ".php", 0604 );
		header( "Location: " . $adminfile . ".php?op=ConfigureBan" );

	}


	switch ( $op )
	{

		case "ConfigureBan":
			ConfigureBan( $bad_ip );
			break;

		case "SaveSetBan":
			SaveSetBan( $ip_ban_add, $uname_ban_add, $ip_ban_del, $uname_ban_del );
			break;

	}

}
else
{
	echo "Access Denied";
}

?>