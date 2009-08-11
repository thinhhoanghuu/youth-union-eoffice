<?php

// File: mainfile.php.
// Created: 08-08-2009 11:51:29.
// Modified: 08-08-2009 11:51:29.
// Program: NukeViet CMS v2.0 RC2.
// Website: www.nukeviet.vn.
// Do not change anything in this file!

define('NV_MAINFILE', true);
define('NV_ANTIDOS', true);

$datafold = "includes/data";

if (file_exists("$datafold/antidos.php")) {
	include("$datafold/antidos.php");
} elseif(file_exists("../$datafold/antidos.php")) {
	include("../$datafold/antidos.php");
}

$dbhost = "localhost";
$dbname = "yueoffice";

$db_tmp = date("G") % 3;
switch ($db_tmp) {
	case 0:
		$dbuname="root"; 
		$dbpass = "123456"; 
		break;
	case 1:
		$dbuname="root"; 
		$dbpass = "123456"; 
		break;
	case 2:
		$dbuname="root"; 
		$dbpass = "123456"; 
		break;
}

$prefix = "nukeviet";
$user_prefix = "nukeviet";
$yu_prefix="sc";
$dbtype = "MySQL";
$sitekey = "6mu76$u^@4sm&iu7z#o51*";

@require_once("includes/functions/mainfile.php");

?>