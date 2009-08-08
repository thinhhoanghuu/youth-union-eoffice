<?php

/*
* @Program:		NukeViet CMS
* @File name: 	NukeViet System
* @Version: 	2.0 RC2
* @Date: 		28.05.2009
* @Website: 	www.nukeviet.vn
* @Copyright: 	(C) 2009
* @License: 	http://opensource.org/licenses/gpl-license.php GNU Public License
*/

if ( (! defined('NV_SYSTEM') and ! defined('NV_ADMIN')) or ! defined('NV_MAINFILE') )
{
	Header( "Location: index.php" );
	exit;
}

/**
 * set ini
 */
set_magic_quotes_runtime( 0 );
ini_set( 'magic_quotes_gpc', 'Off' );
ini_set( 'magic_quotes_runtime', 'Off' );
ini_set( 'magic_quotes_sybase', 'Off' );
ini_set( 'session.use_trans_sid', 0 );
ini_set( 'session.auto_start', '0' );
ini_set( 'display_errors', 0 );
ini_set( 'display_startup_errors', 0 );
ini_set( 'log_errors', 1 );
ini_set( 'error_reporting', 2039 );
ini_set( 'track_errors', 1 );

/**
 * INCLUDE_PATH
 */
if ( defined('FORUM_ADMIN') )
{
	define( 'INCLUDE_PATH', '../../../' );
} elseif ( defined('INSIDE_MOD') )
{
	define( 'INCLUDE_PATH', '../../' );
} elseif ( defined('NV_ADMIN') || defined('NV_RSS') )
{
	define( 'INCLUDE_PATH', '../' );
}
else
{
	define( 'INCLUDE_PATH', './' );
}

/**
 * Ket noi file config
 */
@require_once ( INCLUDE_PATH . $datafold . "/config.php" );
@include_once ( INCLUDE_PATH . "includes/functions/functions.php" );

if ( $eror_value )
{
	@ini_set( 'display_errors', 1 );
	error_reporting( E_ALL ^ E_NOTICE );
}
else
{
	@ini_set( 'display_errors', 0 );
	error_reporting( 0 );
}

if ( ! ini_get('register_globals') ) @import_request_variables( "GPC", "" );

$mainfile = 1;
$mtime = microtime();
$mtime = explode( " ", $mtime );
$mtime = $mtime[1] + $mtime[0];
$start_time = $mtime;

/**
 * gzip_compress
 */
$do_gzip_compress = false;
if ( $gzip_method )
{
	$PREFER_DEFLATE = false;
	$FORCE_COMPRESSION = false;
	$AE = ( isset($_SERVER['HTTP_ACCEPT_ENCODING']) ) ? $_SERVER['HTTP_ACCEPT_ENCODING'] : $_SERVER['HTTP_TE'];
	$support_gzip = ( strpos($AE, 'gzip') !== false ) || $FORCE_COMPRESSION;
	$support_deflate = ( strpos($AE, 'deflate') !== false ) || $FORCE_COMPRESSION;

	if ( $support_gzip && $support_deflate ) $support_deflate = $PREFER_DEFLATE;
	if ( $support_deflate )
	{
		header( "Content-Encoding: deflate" );
		ob_start( "compress_output_deflate" );
	}
	else
	{
		if ( $support_gzip )
		{
			header( "Content-Encoding: gzip" );
			ob_start( "compress_output_gzip" );
		}
		else
		{
			ob_start();
		}
	}
}
else
{
	ob_start();
}

/**
 * session_start
 */
$rootdir = str_replace( "\\", "/", realpath(dirname(__FILE__) . "/../..") );
if ( ! ereg('/$', $rootdir) ) $rootdir = $rootdir . '/';
if ( is_dir($rootdir . 'tmp') ) ini_set( 'session.save_path', $rootdir . 'tmp' );
session_name( "NVS" );
session_start();


/**
* Tao ma truy cap
*/
if ( isset($_GET['gfx']) and $_GET['gfx'] == "gfx" )
{
   $_SESSION['random_num'] = mt_rand( 100000, 999999 );
   $image = ImageCreateFromJPEG( INCLUDE_PATH . "images/code_bg.jpg" );
   if ( ! $image )
   {
      $image = imagecreate( 73, 15 );
      $bgc = imagecolorallocate( $image, 240, 240, 240 );
      imagefilledrectangle( $image, 0, 0, 73, 15, $bgc );
   }
   $text_color = ImageColorAllocate( $image, 50, 50, 50 );
   Header( "Content-type: image/jpeg" );
   ImageString( $image, 5, 11, 1, $_SESSION['random_num'], $text_color );
   ImageJPEG( $image, '', 90 );
   ImageDestroy( $image );
   die();
   break;
}

/**
 * client_ip
 */
$client_ip = nv_getClientIP();

/**
 * banIP
 */
if ( nv_is_ban($client_ip) )
{
	$content = "<html>\n<head>\n<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\">\n";
	$content .= "<title>" . $sitename . "</title>\n</head>\n\n<body>\n";
	$content .= "<table width=\"99%\" height=\"99%\" border=\"2\" cellspacing=\"10\" cellpadding=\"10\" >\n";
	$content .= "<tr valign =\"middle\"><td>\n";
	$content .= "<center><img border=\"0\" src=\"" . INCLUDE_PATH . "images/logo.gif\"><h1><font color=\"#FF0000\">Hi and Good-bye!!!</font></h1></center>";
	$content .= "</td>\n</tr>\n</table>\n</body>\n";
	die( $content );
}

/**
 * Ket noi CSDL
 */
switch ( $dbtype )
{
	case 'MySQL':
		@require_once ( INCLUDE_PATH . 'includes/db/mysql.php' );
		break;
	case 'mysql4':
		@require_once ( INCLUDE_PATH . 'includes/db/mysql4.php' );
		break;
	case 'postgres':
		@require_once ( INCLUDE_PATH . 'includes/db/postgres7.php' );
		break;
	case 'mssql':
		@require_once ( INCLUDE_PATH . 'includes/db/mssql.php' );
		break;
	case 'msaccess':
		@require_once ( INCLUDE_PATH . 'includes/db/msaccess.php' );
		break;
	case 'oracle':
		@require_once ( INCLUDE_PATH . 'includes/db/oracle.php' );
		break;
	case 'mssql-odbc':
		@require_once ( INCLUDE_PATH . 'includes/db/mssql-odbc.php' );
		break;
	case 'db2':
		@require_once ( INCLUDE_PATH . 'includes/db/db2.php' );
		break;
}

$db = new sql_db( $dbhost, $dbuname, $dbpass, $dbname, false );
if ( ! $db->db_connect_id )
{
	$content = "<html>\n<head>\n<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\">\n";
	$content .= "<meta http-equiv=\"refresh\" content=\"30;url=" . INCLUDE_PATH . "\">\n";
	$content .= "<title>" . $sitename . "</title>\n</head>\n\n<body>\n";
	$content .= "<table width=\"99%\" height=\"99%\" border=\"2\" cellspacing=\"10\" cellpadding=\"10\" >\n";
	$content .= "<tr valign =\"middle\"><td>\n";
	$content .= "<center><img border=\"0\" src=\"" . INCLUDE_PATH . "images/logo.gif\"><h1>There currently are problems in connecting to SQL server. Please return to our site after a few minutes!</h1></center>";
	$content .= "</td>\n</tr>\n</table>\n</body>\n";
	die( $content );
}

/**
 * Xac dinh admin
 */
unset( $admin, $adm_name, $adm_super, $admin_ar );
if ( isset($_SESSION[ADMIN_COOKIE]) && ! empty($_SESSION[ADMIN_COOKIE]) )
{
	$admin = base64_encode( addslashes(base64_decode($_SESSION[ADMIN_COOKIE])) );
	if ( is_array($admin) )
	{
		unset( $admin, $_SESSION[ADMIN_COOKIE] );
	}
	else
	{
		if ( ! defined('NV_ADMIN') )
		{
			$admin_ar = explode( "#:#", addslashes(base64_decode($admin)) );
			if ( substr(addslashes($admin_ar[0]), 0, 25) != "" && $admin_ar[1] != "" )
			{
				$admsql = "SELECT `pwd`, `name`, `radminsuper` FROM `" . $prefix . "_authors` WHERE `aid`='" . trim( substr(addslashes($admin_ar[0]), 0, 25) ) . "' AND `checknum` = '" . $admin_ar[3] . "' AND `agent` = '" . $admin_ar[4] . "' AND `last_ip` = '" . $admin_ar[5] . "'";
				$admresult = $db->sql_query( $admsql );
				$pass = $db->sql_fetchrow( $admresult );
				$db->sql_freeresult( $admresult );
				if ( $pass[0] == $admin_ar[1] && ! empty($pass[0]) && ($admin_ar[4] == substr(trim($_SERVER['HTTP_USER_AGENT']), 0, 80)) )
				{
					define( 'IS_ADMIN', true );
					$adm_name = addslashes( $pass[1] );
					$adm_super = intval( $pass[2] );
					if ( $adm_super == 1 )
					{
						define( 'IS_SPADMIN', true );
					}
				}
				else
				{
					unset( $admin, $_SESSION[ADMIN_COOKIE] );
				}
			}
			else
			{
				unset( $admin, $_SESSION[ADMIN_COOKIE] );
			}
		}
	}
}

/**
 * Xac dinh user
 */
unset( $user, $user_ar, $mbrow );
if ( isset($_COOKIE[USER_COOKIE]) && ! empty($_COOKIE[USER_COOKIE]) )
{
	$user = base64_encode( addslashes(base64_decode($_COOKIE[USER_COOKIE])) );
	if ( is_array($user) )
	{
		unset( $user );
		setcookie( USER_COOKIE, false );
		header( "Location: index.php" );
		exit;
	}
	else
	{
		if ( ! defined('NV_ADMIN') )
		{
			$user_ar = explode( ":", addslashes(base64_decode($user)) );
			if ( intval($user_ar[0]) != 0 and $user_ar[2] != "" )
			{
				$mbsql = "SELECT * FROM `" . $user_prefix . "_users` WHERE `user_id`=" . intval( $user_ar[0] );
				$mbresult = $db->sql_query( $mbsql );
				$mbrow = $db->sql_fetchrow( $mbresult );
				$db->sql_freeresult( $mbresult );
				if ( ! empty($mbrow['user_password']) and $mbrow['user_password'] == $user_ar[2] )
				{
					define( 'IS_USER', true );
				}
				else
				{
					unset( $user );
					setcookie( USER_COOKIE, false );
					header( "Location: index.php" );
					exit;
				}
			}
			else
			{
				unset( $user );
				setcookie( USER_COOKIE, false );
				header( "Location: index.php" );
				exit;
			}
		}
	}
}

/**
 * Ket noi file bao mat
 */
@include_once ( INCLUDE_PATH . "includes/functions/security.php" );

/**
 * Xac dinh ngon ngu
 */
if ( $multilingual and (isset($_GET['newlang']) || isset($_POST['newlang'])) )
{
	$newlang = trim( (isset($_POST['newlang'])) ? $_POST['newlang'] : $_GET['newlang'] );
	if ( ! eregi("[^a-zA-Z0-9_]", $newlang) and file_exists(INCLUDE_PATH . "language/lang-" . $newlang . ".php") )
	{
		setcookie( "lang", $newlang, time() + intval($live_cookie_time) * 86400, $cookie_path, $cookie_domain );
		if ( defined('IS_USER') and (file_exists(INCLUDE_PATH . "modules/Forums/language/lang_" . $newlang . "/lang_main.php")) )
		{
			$db->sql_query( "UPDATE " . $user_prefix . "_users SET user_lang='" . $newlang . "' WHERE user_id='" . intval($user_ar[0]) . "'" );
		}
		header( "Location: index.php" );
		exit;
	}
}
$currentlang = nv_set_currentlang();
if ( empty($currentlang) ) die( "Error! Lang file is absent!" );
include ( INCLUDE_PATH . "language/lang-" . $currentlang . ".php" );

/**
 * Xac dinh Theme
 */
if ( $changtheme and (isset($_GET['newsk']) || isset($_POST['newsk'])) )
{
	$newsk = trim( (isset($_POST['newsk'])) ? $_POST['newsk'] : $_GET['newsk'] );
	if ( ! eregi("[^a-zA-Z0-9\_\-]", $newsk) and file_exists(INCLUDE_PATH . "themes/" . $newsk . "/theme.php") )
	{
		setcookie( "clsk", $newsk, time() + intval($live_cookie_time) * 86400, $cookie_path, $cookie_domain );
		header( "Location: index.php" );
		exit;
	}
}
$ThemeSel = nv_set_ThemeSel();
if ( empty($ThemeSel) ) die( "Error! Theme file is absent!" );

/**
 * TInh so truy cap
 */
@include_once ( INCLUDE_PATH . "includes/functions/counter.php" );

/**
 * Ket noi voi cac file phu tro cho cac module
 */
@include_once ( INCLUDE_PATH . "includes/functions/blocks.php" );
@include_once ( INCLUDE_PATH . "includes/functions/geturl.php" );
@include_once ( INCLUDE_PATH . "includes/functions/news.php" );
@include_once ( INCLUDE_PATH . "includes/functions/uploads.php" );
@include_once ( INCLUDE_PATH . "includes/functions/users.php" );

/**
 * Chan truy cap
 */
if ( $disable_site )
{
	if ( ! eregi($adminfile . ".php", $_SERVER['SCRIPT_NAME']) && ! defined('IS_ADMIN') && $name != "Your_Account" )
	{
		include ( "header.php" );
		OpenTable();
		echo "<center><h1>" . _CLOSESITE . "</h1></center>\n" . stripslashes( html_entity_decode($disable_message) ) . "\n";
		CloseTable();
		include ( "footer.php" );
		die();
	}
}


?>