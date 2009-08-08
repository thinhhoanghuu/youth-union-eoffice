<?php

/*
* @Program:		NukeViet CMS
* @File name: 	NukeViet System Security
* @Version: 	2.0 RC2
* @Date: 		28.05.2009
* @Website: 	www.nukeviet.vn
* @Copyright: 	(C) 2009
* @License: 	http://opensource.org/licenses/gpl-license.php GNU Public License
*/


if ( ! defined('NV_MAINFILE') ) die( 'Stop!!!' );

$phpver = phpversion();
if ( phpversion() < "4.1.0" )
{
	$_GET = $HTTP_GET_VARS;
	$_POST = $HTTP_POST_VARS;
	$_SERVER = $HTTP_SERVER_VARS;
	$_FILES = $HTTP_POST_FILES;
	$_ENV = $HTTP_ENV_VARS;
//	unset( $_REQUEST, $_COOKIE, $_SESSION );
	if ( $_SERVER['REQUEST_METHOD'] == "POST" )
	{
		$_REQUEST = $_POST;
	} elseif ( $_SERVER['REQUEST_METHOD'] == "GET" )
	{
		$_REQUEST = $_GET;
	}
	if ( isset($HTTP_COOKIE_VARS) )
	{
		$_COOKIE = $HTTP_COOKIE_VARS;
	}
	if ( isset($HTTP_SESSION_VARS) )
	{
		$_SESSION = $HTTP_SESSION_VARS;
	}
}

if ( $phpver >= '4.1.0' )
{
	$HTTP_GET_VARS = $_GET;
	$HTTP_POST_VARS = $_POST;
	$HTTP_SERVER_VARS = $_SERVER;
	$HTTP_POST_FILES = $_FILES;
	$HTTP_ENV_VARS = $_ENV;
	$PHP_SELF = $_SERVER['PHP_SELF'];
//	unset( $HTTP_SESSION_VARS, $HTTP_COOKIE_VARS );
	if ( isset($_SESSION) )
	{
		$HTTP_SESSION_VARS = $_SESSION;
	}
	if ( isset($_COOKIE) )
	{
		$HTTP_COOKIE_VARS = $_COOKIE;
	}
}

if ( stristr($_SERVER['SCRIPT_NAME'], "mainfile.php") || stristr(htmlentities($_SERVER['PHP_SELF']), "mainfile.php") ) die();
if ( $_SERVER['HTTP_USER_AGENT'] == "" || $_SERVER['HTTP_USER_AGENT'] == "-" ) die();

if ( isset($_SERVER['QUERY_STRING']) )
{
	unset( $matches, $loc );
	if ( preg_match("/([OdWo5NIbpuU4V2iJT0n]{5}) /", rawurldecode($loc = $_SERVER['QUERY_STRING']), $matches) ) die( "Illegal Operation" );
}

if ( isset($_SERVER['QUERY_STRING']) && (! stripos_clone($_SERVER['QUERY_STRING'], "ad_click") || ! stripos_clone($_SERVER['QUERY_STRING'], "url")) )
{
	$queryString = $_SERVER['QUERY_STRING'];
	if ( stripos_clone($queryString, '%20union%20') or stripos_clone($queryString, '/*') or stripos_clone($queryString, '*/union/*') or stripos_clone($queryString, 'c2nyaxb0') or stripos_clone($queryString, '+union+') or stripos_clone($queryString, 'http://') or (stripos_clone($queryString, 'cmd=') and ! stripos_clone($queryString, '&cmd')) or (stripos_clone($queryString, 'exec') and ! stripos_clone($queryString, 'execu')) or stripos_clone($queryString, 'concat') ) die( 'Illegal Operation' );
}

if ( $_SERVER['REQUEST_METHOD'] == "POST" )
{
	$postString = http_build_query( $_POST );
	$postString = urldecode( $postString );
	if ( ! empty($postString) )
	{
		$postString = str_replace( "%09", "%20", $postString );
		$postString_64 = base64_decode( $postString );
		if ( stristr($postString, '%20union%20') or stristr($postString, '*/union/*') or stristr($postString, ' union ') or stristr($postString_64, '%20union%20') or stristr($postString_64, '*/union/*') or stristr($postString_64, ' union ') ) die( 'Illegal Operation' );

		if ( preg_match("/mod_authors/", $postString || preg_match("/displayadmins/", $postString) || preg_match("/updateadmin/", $postString) || preg_match("/modifyadmin/", $postString) || preg_match("/deladmin/", $postString) || preg_match("/deladmin2/", $postString)) ) die( 'Illegal Operation' );
	}

	if ( ! isset($_SERVER['HTTP_REFERER']) or empty($_SERVER['HTTP_REFERER']) ) die( "<b>Warning:</b> your browser doesn't send the HTTP_REFERER header to the website.<br>This can be caused due to your browser, using a proxy server or your firewall.<br>Please change browser or turn off the use of a proxy<br>or turn off the 'Deny servers to trace web browsing' in your firewall and you shouldn't have problems when sending a POST on this website." );
	if ( ! stripos_clone($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) ) die( "Posting from another server not allowed!" );
}

$security_string = "/UNION|OUTFILE|SELECT|ALTER|INSERT|DROP|FROM|WHERE|UPDATE|" . $prefix . "_authors|" . $prefix . "_users|UpdateAuthor|AddAuthor|mod_authors|modifyadmin|deladmin|deladmin2/i";
if ( ! defined('NV_ADMIN') and ! defined('IS_ADMIN') )
{
	foreach ( $_GET as $var_name => $var_value )
	{
		if ( ! empty($security_tags) and preg_match("/<.*?(" . $security_tags . ").*?>/", urldecode($var_value)) || preg_match("/\([^>]*\"?[^)]*\)/", $var_value) || preg_match("/\"/", $var_value) ) die( 'Illegal Operation' );
		if ( $security_url_get and preg_match("/^(http\:\/\/|ftp\:\/\/|\/\/|https:\/\/|php:\/\/|\/\/)/i", $var_value) ) die( 'Illegal Operation' );
		if ( $security_union_get )
		{
			$security_decode = base64_decode( $var_value );
			$security_slash = preg_replace( "/\/\*.*?\*\//", "", $var_value );
			if ( preg_match($security_string, $var_value) or preg_match($security_string, $security_decode) or preg_match($security_string, $security_slash) ) die( 'Illegal Operation' );
		}
	}
	foreach ( $_POST as $var_name => $var_value )
	{
		if ( ! empty($security_tags) and preg_match("/<.*?(" . $security_tags . ").*?>/", urldecode($var_value)) ) die( 'Illegal Operation' );
		if ( $security_url_post and preg_match("/^(http\:\/\/|ftp\:\/\/|\/\/|https:\/\/|php:\/\/|\/\/)/i", $var_value) ) die( 'Illegal Operation' );

		if ( $security_union_post )
		{
			$security_decode = base64_decode( $var_value );
			$security_slash = preg_replace( "/\/\*.*?\*\//", "", $var_value );
			if ( preg_match($security_string, $var_value) or preg_match($security_string, $security_decode) or preg_match($security_string, $security_slash) ) die( 'Illegal Operation' );
		}
	}
}

if ( $security_cookies )
{
	foreach ( $_COOKIE as $var_name => $var_value )
	{
		if ( preg_match("/<.*?(script|object|iframe|applet|meta|style|form|img|onmouseover|body).*?>/", $var_value) or preg_match("/^(http\:\/\/|ftp\:\/\/|\/\/|https:\/\/|php:\/\/|\/\/)/i", $var_value) )
		{
			setcookie( $var_name, false );
			die( 'Illegal Operation' );
		}

		$security_decode = base64_decode( $var_value );
		$security_slash = preg_replace( "/\/\*.*?\*\//", "", $var_value );
		if ( preg_match($security_string, $var_value) or preg_match($security_string, $security_decode) or preg_match($security_string, $security_slash) )
		{
			setcookie( $var_name, false );
			die( 'Illegal Operation' );
		}
	}
}

if ( $security_sessions )
{
	foreach ( $_SESSION as $var_name => $var_value )
	{
		if ( preg_match("/<.*?(script|object|iframe|applet|meta|style|form|img|onmouseover|body).*?>/", $var_value) or preg_match("/^(http\:\/\/|ftp\:\/\/|\/\/|https:\/\/|php:\/\/|\/\/)/i", $var_value) )
		{
			unset( $_SESSION[$var_name] );
			die( 'Illegal Operation' );
		}

		$security_decode = base64_decode( $var_value );
		$security_slash = preg_replace( "/\/\*.*?\*\//", "", $var_value );
		if ( preg_match($security_string, $var_value) or preg_match($security_string, $security_decode) or preg_match($security_string, $security_slash) )
		{
			unset( $_SESSION[$var_name] );
			die( 'Illegal Operation' );
		}
	}
}

if ( ! empty($security_files) )
{
	if ( isset($_FILES) and $_FILES != array() )
	{
		foreach ( $_FILES as $var_name => $var_value )
		{
			if ( ! empty($var_value['name']) and preg_match("/" . $security_files . "$/", $var_value['name']) )
			{
				@unlink( $var_value['tmp_name'] );
				die( 'Illegal Operation' );
			}
		}
	}
}

if ( isset($_GET) and $_GET != array() ) reset( &$_GET );
if ( isset($_POST) and $_POST != array() ) reset( &$_POST );
if ( isset($_COOKIE) and $_COOKIE != array() ) reset( &$_COOKIE );
if ( isset($_SESSION) and $_SESSION != array() ) reset( &$_SESSION );
if ( isset($_FILES) and $_FILES != array() ) reset( &$_FILES );

?>