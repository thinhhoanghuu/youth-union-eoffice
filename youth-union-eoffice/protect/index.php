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

$go = $_SERVER['QUERY_STRING'];
if ( $go != "" )
{
	$go = $go;
}
else
{
	$go = "english.php";
}

echo "<html>

<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">
<title>Powered by Web Protect © MangVN.Org 2009 - Redirecting to $link</title>
<meta name=\"description\" content=\"Redirecting: Chuyển trang tới $link\">
<meta http-equiv='refresh' content='0; url=" . $go . "'>
</head>

<body>
<center>
<font face=\"Times New Roman\" size=\"5\">Đang chuyển trang!</font><br>
Redirecting<hr>
	
<p><font face=\"Tahoma\" size=\"2\">

<a href=\"$link\">" . $go . "</a>

</font></p>

</center><hr>
<p align=\"center\"><FONT face=Arial size=2>Powered by 
<a href=\"http://mangvn.org/protect\">Web Protect</a> © MangVN.Org 2009</FONT></p>


</body>

</html>";

?>