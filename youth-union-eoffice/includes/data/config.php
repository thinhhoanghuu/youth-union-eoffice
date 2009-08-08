<?php

// File: config.php.
// Created: 08-08-2009 11:51:29.
// Modified: 08-08-2009 11:51:29.
// Program: NukeViet CMS v2.0 RC2.
// Website: www.nukeviet.vn.
// Do not change anything in this file!

if ((!defined('NV_SYSTEM')) AND (!defined('NV_ADMIN'))) {
die('Stop!!!');
}

define("USER_COOKIE","nvu_TTgra");
define("ADMIN_COOKIE","nva_TTgra");
$sitename = "QLDV";
$nukeurl = "http://localhost";
$site_logo = "logo.gif";
$startdate = "08/08/2009";
$adminmail = "admin@gmail.com";
$anonpost = "1";
$Default_Theme = "nv_silver";
$changtheme = "0";
$actthemes = "nv_green|nv_orange|nv_silver";
$live_cookie_time = "365";
$cookie_path = "/";
$cookie_domain = "";
$language = "vietnamese";
$gfx_chk = "3";
$multilingual = "1";
$notify = "1";
$anonymous = "Guest";
$admingraphic = "1";
$minpass = "5";
$pollcomm = "1";
$articlecomm = "1";
$Home_Module = "News";
$adminfold = "admin";
$adminfile = "admin";
$disable_site = "0";
$disable_message = "";
$gzip_method = "0";
$eror_value = "1";
$counteract = "1";
$timecount = "300";
$hourdiff = "0";
$htg1 = "d.m.Y";
$htg2 = "d.m.Y H:i";
$width = 600;
$max_size = 67108864;
$antidos = 0;
$security_tags = "script|object|iframe|applet|meta|style|form|img|onmouseover|body";
$security_url_get = 0;
$security_url_post = 0;
$security_union_get = 1;
$security_union_post = 1;
$security_cookies = 1;
$security_sessions = 1;
$security_files = "php|php3|php4|pl|phtml|html|htm|asp|cgi";
$editor = "1";

$AllowableHTML = array("b"=>1,"i"=>1,"strike"=>1,"div"=>2,"u"=>1,"a"=>2,"em"=>1,"br"=>1,"strong"=>1,"blockquote"=>1,"tt"=>1,"li"=>1,"ol"=>1,"ul"=>1);
define('NV_INSTALLED', true);

?>