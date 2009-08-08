<?php

// File: config_Your_Account.php.
// Created: 08-08-2009 11:51:29.
// Modified: 08-08-2009 11:51:29.
// Program: NukeViet CMS v2.0 RC2.
// Website: www.nukeviet.vn.
// Do not change anything in this file!

if ((!defined('NV_SYSTEM')) AND (!defined('NV_ADMIN'))) {
die('Stop!!!');
}

$allowuserreg = 0;
$allowuserlogin = 0;
$useactivate = 0;
$nick_max = 30;
$nick_min = 5;
$pass_max = 15;
$pass_min = 4;
$expiring = 86400;
$userredirect = "home";
$sendmailuser = 1;
$send2mailuser = 0;
$allowmailchange = 1;
$bad_mail = "yoursite.com|mysite.com|localhost|xxx";
$bad_nick = "anonimo|anonymous|god|linux|nobody|operator|root";
$suspend_nick = "";

?>