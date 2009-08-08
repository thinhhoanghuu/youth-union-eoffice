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

$maxnumchat = 50; //so luong toi da dong chat
$maxch = 100; //so luong toi da ky tu o 1 dong chat
$thoig = 0; //hien thi hay khong thoi gian
$mausac1 = "#ce6700"; //mau cua chu the
$mausac2 = "#4f4f00"; //mau sac cua khach the
$heightchat = 200; //chieu cao cua khung chat

/*************Het phan khai bao****************/

global $datafold, $module_name, $user_ar;

if ( defined('IS_SPADMIN') and isset($_GET['delchat']) and $_GET['delchat'] == '1' )
{
	@unlink( "" . INCLUDE_PATH . "" . $datafold . "/chat.php" );
	header( "Location: index.php" );
	exit();
}

if ( defined('IS_USER') and isset($_POST['chat']) and $_POST['chat'] == "send" and isset($_POST['send']) and $_POST['send'] != "" and isset($_POST['chater']) and $_POST['chater'] != "" )
{
	$chatrow = array();
	if ( file_exists("" . INCLUDE_PATH . "" . $datafold . "/chat.php") )
	{
		@include_once ( "" . $datafold . "/chat.php" );
	}
	@chmod( "" . INCLUDE_PATH . "" . $datafold . "/chat.php", 0777 );
	$filechat = @fopen( "" . INCLUDE_PATH . "" . $datafold . "/chat.php", "w" );
	$contentc = "<?php\n\n";
	$fctime = date( "d-m-Y H:i:s", filectime("" . INCLUDE_PATH . "" . $datafold . "/chat.php") );
	$fmtime = date( "d-m-Y H:i:s" );
	$contentc .= "// File: chat.php.\n// Created: $fctime.\n// Modified: $fmtime.\n// Do not change anything in this file!\n\n";
	$contentc .= "if ((!defined('NV_SYSTEM')) AND (!defined('NV_ADMIN'))) {\n";
	$contentc .= "die('Stop!!!');\n";
	$contentc .= "}\n";
	$contentc .= "\n";
	$contentc .= "\$chatrow[] = \"" . htmlspecialchars( stripslashes(FixQuotes(filter_text($_POST['chater'], "nohtml"))) ) . "|" . htmlspecialchars( stripslashes(FixQuotes(filter_text($_POST['send'], "nohtml"))) ) . "|" . time() . "\";\n";
	for ( $x = 0; $x < sizeof($chatrow); $x++ )
	{
		$chatrow2 = explode( "|", $chatrow[$x] );
		if ( $chatrow2[0] != "" && $chatrow2[1] != "" )
		{
			$contentc .= "\$chatrow[] = \"$chatrow[$x]\";\n";
		}
	}
	$contentc .= "\n";
	$contentc .= "?>";
	@fwrite( $filechat, $contentc );
	@fclose( $filechat );
	@chmod( "" . INCLUDE_PATH . "" . $datafold . "/chat.php", 0666 );
	unset( $chatrow );
	unset( $chatrow2 );
	header( "Location: " . $_SERVER['HTTP_REFERER'] . "" );
	exit();
}

$chatrow = array();
if ( file_exists("" . INCLUDE_PATH . "" . $datafold . "/chat.php") )
{
	@include_once ( "" . $datafold . "/chat.php" );
}
$chnum = 0;
$sendchat = "";
for ( $x = 0; $x < sizeof($chatrow); $x++ )
{
	$chatrow2 = explode( "|", $chatrow[$x] );
	if ( $chatrow2[0] != "" && $chatrow2[1] != "" )
	{
		$chnum++;
		if ( $chnum == $maxnumchat )
		{
			$maxnumchat2 = $maxnumchat - $x - 1;
			@chmod( "" . INCLUDE_PATH . "" . $datafold . "/chat.php", 0777 );
			$filechat = @fopen( "" . INCLUDE_PATH . "" . $datafold . "/chat.php", "w" );
			$contentc = "<?php\n\n";
			$fctime = date( "d-m-Y H:i:s", filectime("" . INCLUDE_PATH . "" . $datafold . "/chat.php") );
			$fmtime = date( "d-m-Y H:i:s" );
			$contentc .= "// File: chat.php.\n// Created: $fctime.\n// Modified: $fmtime.\n// Do not change anything in this file!\n\n";
			$contentc .= "if ((!defined('NV_SYSTEM')) AND (!defined('NV_ADMIN'))) {\n";
			$contentc .= "die('Stop!!!');\n";
			$contentc .= "}\n";
			$contentc .= "\n";
			$contentc .= "\$chatrow[] = \"" . $chatrow[$maxnumchat2] . "\";\n";
			$contentc .= "\n";
			$contentc .= "?>";
			@fwrite( $filechat, $contentc );
			@fclose( $filechat );
			@chmod( "" . INCLUDE_PATH . "" . $datafold . "/chat.php", 0666 );
			header( "Location: " . $_SERVER['HTTP_REFERER'] . "" );
			exit();
		}
		$mausac = $mausac2;
		if ( defined('IS_USER') and $chatrow2[0] == "" . $user_ar[1] . "" )
		{
			$mausac = $mausac1;
		}
		if ( $thoig == 1 )
		{
			$sendchat .= "<font color=\"$mausac\"><b>" . $chatrow2[0] . "</b></font>: " . $chatrow2[1] . "<div align=\"right\"><font class=tsmall style=\"margin-top: 2px; margin-bottom: 10px\">[ " . viewtime( intval($chatrow2[2]), 2 ) . " ]</font></div>";
		}
		else
		{
			$sendchat .= "<font color=\"$mausac\"><b>" . $chatrow2[0] . "</b></font>: " . $chatrow2[1] . "<br>";
		}
	}
}


$content = "<a name=\"chat\"></a>";
$content .= "<form action=\"modules.php?name=" . $module_name . "#chat\" method=\"post\">";
$content .= "<table border=\"0\" cellpadding=\"0\" style=\"border-collapse: collapse\" width=\"100%\" cellspacing=\"0\">";
if ( $sendchat != "" )
{
	$sendchat = "" . $sendchat . "<br>";
	$content .= "<tr><td bgcolor=\"#FFFFFF\"><img border=\"0\" src=\"" . INCLUDE_PATH . "images/spacer.gif\" width=\"1\" height=\"5\"></td></tr>\n";
	$content .= "<tr><td bgcolor=\"#FFFFFF\"><table border=\"0\" cellpadding=\"2\" style=\"border-collapse: collapse\" width=\"100%\" cellspacing=\"2\">";
	$content .= "<tr><td style=\"text-align: justify\"><div style=\"OVERFLOW: auto; WIDTH: 100%; HEIGHT: " . $heightchat . "px\">" . $sendchat . "</div></td></tr>\n";
	$content .= "</table><br></td></tr>";
}
if ( defined('IS_USER') )
{
	$content .= "<tr><td align=\"center\"><input type=\"hidden\" name=\"chat\" value=\"send\"><input type=\"hidden\" value=\"" . $user_ar[1] . "\" name=\"chater\"><input type=\"text\" name=\"send\" maxlength=\"$maxch\"><input type=\"submit\" value=\"Go\"></td></tr>";
}
else
{
	$content .= "<tr><td><img border=\"0\" src=\"" . INCLUDE_PATH . "images/spacer.gif\" width=\"1\" height=\"5\"></td></tr><tr><td align=\"center\"><a href=\"modules.php?name=Your_Account\"><b>" . _LOGIN . "</b></a></td></tr>";
}
$content .= "</table></form>";
if ( defined('IS_SPADMIN') and $chnum != 0 )
{
	$content .= "<center>Admin: <a href=\"index.php?delchat=1\"><b>" . _DELETE . "</b></a></center>";
}

?>