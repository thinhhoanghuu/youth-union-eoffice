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

if ( ! defined('NV_MAINFILE') )
{
	die( 'Stop!!!' );
}

if ( ! function_exists("floatval") )
{
	/**
	 * floatval()
	 * 
	 * @param mixed $inputval
	 * @return
	 */
	function floatval( $inputval )
	{
		return ( float )$inputval;
	}
}

if ( ! function_exists('stripos') )
{
	/**
	 * stripos_clone()
	 * 
	 * @param mixed $haystack
	 * @param mixed $needle
	 * @param integer $offset
	 * @return
	 */
	function stripos_clone( $haystack, $needle, $offset = 0 )
	{
		$return = strpos( strtoupper($haystack), strtoupper($needle), $offset );
		if ( $return === false )
		{
			return false;
		}
		else
		{
			return true;
		}
	}
}
else
{
	function stripos_clone( $haystack, $needle, $offset = 0 )
	{
		$return = stripos( $haystack, $needle, $offset = 0 );
		if ( $return === false )
		{
			return false;
		}
		else
		{
			return true;
		}
	}
}

if ( ! function_exists('http_build_query') )
{
	/**
	 * http_build_query()
	 * 
	 * @param mixed $data
	 * @param string $prefix
	 * @param string $sep
	 * @param string $key
	 * @return
	 */
	function http_build_query( $data, $prefix = '', $sep = '', $key = '' )
	{
		$ret = array();
		foreach ( (array )$data as $k => $v )
		{
			if ( is_int($k) && $prefix != null )
			{
				$k = urlencode( $prefix . $k );
			}
			if ( (! empty($key)) || ($key === 0) ) $k = $key . '[' . urlencode( $k ) . ']';
			if ( is_array($v) || is_object($v) )
			{
				array_push( $ret, http_build_query($v, '', $sep, $k) );
			}
			else
			{
				array_push( $ret, $k . '=' . urlencode($v) );
			}
		}
		if ( empty($sep) ) $sep = ini_get( 'arg_separator.output' );
		return implode( $sep, $ret );
	}
}

if ( ! function_exists('str_ireplace') )
{
	/**
	 * str_ireplace()
	 * 
	 * @param mixed $search
	 * @param mixed $replace
	 * @param mixed $subject
	 * @return
	 */
	function str_ireplace( $search, $replace, $subject )
	{
		$token = chr( 1 );
		$haystack = strtolower( $subject );
		$needle = strtolower( $search );
		while ( ($pos = strpos($haystack, $needle)) !== false )
		{
			$subject = substr_replace( $subject, $token, $pos, strlen($search) );
			$haystack = substr_replace( $haystack, $token, $pos, strlen($search) );
		}
		$subject = str_replace( $token, $replace, $subject );
		return $subject;
	}
}

/**
 * compress_output_gzip()
 * 
 * @param mixed $output
 * @return
 */
function compress_output_gzip( $output )
{
	return gzencode( $output );
}

/**
 * compress_output_deflate()
 * 
 * @param mixed $output
 * @return
 */
function compress_output_deflate( $output )
{
	return gzdeflate( $output, 9 );
}

/**
 * nv_getClientIP()
 * 
 * @return
 */
function nv_getClientIP()
{
	$client_ip = $_SERVER['HTTP_CLIENT_IP'];
	if ( ! strstr($client_ip, ".") ) $client_ip = $_SERVER['REMOTE_ADDR'];
	if ( ! strstr($client_ip, ".") ) $client_ip = getenv( "REMOTE_ADDR" );
	return trim( $client_ip );
}

/**
 * nv_is_ban()
 * 
 * @param string $client_ip
 * @return
 */
function nv_is_ban( $client_ip = "" )
{
	global $datafold;
	if ( empty($client_ip) ) $client_ip = nv_getClientIP();
	$return = false;
	$ip_ban = "";
	if ( file_exists(INCLUDE_PATH . $datafold . "/config_Setban.php") and filesize(INCLUDE_PATH . $datafold . "/config_Setban.php") != 0 )
	{
		@include ( INCLUDE_PATH . $datafold . "/config_Setban.php" );

	}
	if ( ! empty($ip_ban) and in_array($client_ip, explode("|", $ip_ban)) )
	{
		$return = true;
	}
	return $return;
}

/**
 * nv_set_currentlang()
 * 
 * @return
 */
function nv_set_currentlang()
{
	global $multilingual, $language, $live_cookie_time, $cookie_path, $cookie_domain;
	$currentlang = "";
	if ( $multilingual )
	{
		if ( isset($_COOKIE['lang']) and ! eregi("[^a-zA-Z0-9_]", $_COOKIE['lang']) and file_exists(INCLUDE_PATH . "language/lang-" . $_COOKIE['lang'] . ".php") )
		{
			$currentlang = $_COOKIE['lang'];
		} elseif ( file_exists(INCLUDE_PATH . "language/lang-" . $language . ".php") )
		{
			setcookie( "lang", $language, time() + intval($live_cookie_time) * 86400, $cookie_path, $cookie_domain );
			$currentlang = $language;
		}
	}
	else
	{
		if ( file_exists(INCLUDE_PATH . "language/lang-" . $language . ".php") ) $currentlang = $language;
	}
	return $currentlang;
}

/**
 * nv_set_ThemeSel()
 * 
 * @return
 */
function nv_set_ThemeSel()
{
	global $changtheme, $Default_Theme, $live_cookie_time, $cookie_path, $cookie_domain;
	$ThemeSel = "";
	if ( $changtheme )
	{
		if ( isset($_COOKIE['clsk']) and ! eregi("[^a-zA-Z0-9_\-]", $_COOKIE['clsk']) and file_exists(INCLUDE_PATH . "themes/" . $_COOKIE['clsk'] . "/theme.php") )
		{
			$ThemeSel = $_COOKIE['clsk'];
		} elseif ( file_exists(INCLUDE_PATH . "themes/" . $Default_Theme . "/theme.php") )
		{
			setcookie( "clsk", $Default_Theme, time() + intval($live_cookie_time) * 86400, $cookie_path, $cookie_domain );
			$ThemeSel = $Default_Theme;
		}
	}
	else
	{
		if ( file_exists(INCLUDE_PATH . "themes/" . $Default_Theme . "/theme.php") ) $ThemeSel = $Default_Theme;
	}
	return $ThemeSel;
}

/**
 * del_online()
 * 
 * @param mixed $del
 * @return
 */
function del_online( $del )
{
	global $db, $prefix;
	list( $online ) = $db->sql_fetchrow( $db->sql_query("SELECT `online` FROM `" . $prefix . "_stats`") );
	$onl1 = explode( "|", $online );
	$onl = "";
	for ( $z = 0; $z < sizeof($onl1); $z++ )
	{
		$onl2 = explode( ":", $onl1[$z] );
		if ( $onl2[0] != $del )
		{
			if ( $onl != "" ) $onl .= "|";
			$onl .= "" . $onl1[$z] . "";
		}
	}
	$db->sql_query( "UPDATE `" . $prefix . "_stats` SET `online`='" . $onl . "'" );
}

/**
 * get_lang()
 * 
 * @param mixed $module
 * @return
 */
function get_lang( $module )
{
	global $currentlang, $language;
	if ( $module == admin )
	{
		if ( file_exists("language/lang-$currentlang.php") ) @include_once ( "language/lang-$currentlang.php" );
		elseif ( file_exists("language/lang-$language.php") ) @include_once ( "language/lang-$language.php" );
	}
	else
	{
		if ( file_exists("modules/$module/language/lang-$currentlang.php") )
		{
			@include_once ( "modules/$module/language/lang-$currentlang.php" );
		}
		else
		{
			if ( file_exists("modules/$module/language/lang-$language.php") )
			{
				@include_once ( "modules/$module/language/lang-$language.php" );
			}
		}
	}
}

/**
 * is_active()
 * 
 * @param mixed $module
 * @return
 */
function is_active( $module )
{
	global $prefix, $db;
	$module = trim( $module );
	$sql = "SELECT active FROM " . $prefix . "_modules WHERE title='$module'";
	$result = $db->sql_query( $sql );
	$row = $db->sql_fetchrow( $result );
	$db->sql_freeresult( $result );
	$act = $row['active'];
	$act = intval( $act );
	if ( ! $result or $act == 0 ) return 0;
	else  return 1;
}

/**
 * message_box()
 * 
 * @return
 */
function message_box()
{
	global $adminfold, $adminfile, $bgcolor1, $bgcolor2, $textcolor2, $prefix, $multilingual, $currentlang, $db;
	if ( $multilingual == 1 )
	{
		$querylang = "AND (mlanguage='$currentlang' OR mlanguage='')";
	}
	else
	{
		$querylang = "";
	}
	$sql = "SELECT mid, title, content, date, expire, view FROM " . $prefix . "_message WHERE active='1' $querylang";
	$result = $db->sql_query( $sql );
	if ( $numrows = $db->sql_numrows($result) == 0 )
	{
		return;
	}
	else
	{
		while ( $row = $db->sql_fetchrow($result) )
		{
			$mid = $row['mid'];
			$mid = intval( $mid );
			$title = stripslashes( check_html($row['title'], "nohtml") );
			$content = stripslashes( $row['content'] );
			$mdate = $row['date'];
			$expire = $row['expire'];
			$expire = intval( $expire );
			$view = $row['view'];
			$view = intval( $view );
			if ( $title != "" && $content != "" )
			{
				if ( $expire == 0 )
				{
					$remain = _UNLIMITED;
				}
				else
				{
					$etime = ( ($mdate + $expire) - time() ) / 3600;
					$etime = ( int )$etime;
					if ( $etime < 1 )
					{
						$remain = _EXPIRELESSHOUR;
					}
					else
					{
						$remain = "" . _EXPIREIN . " $etime " . _HOURS . "";
					}
				}
				if ( $view == 4 and defined('IS_ADMIN') )
				{
					OpenTable();
					echo "<center><font class=\"option\" color=\"$textcolor2\"><b>$title</b></font></center><br>\n" . "<font class=\"content\">$content</font>";
					if ( defined('IS_SPADMIN') )
					{
						echo "<br><br><center><font class=\"content\">[ " . _MVIEWADMIN . " - $remain - <a href=\"" . $adminfold . "/" . $adminfile . ".php?op=editmsg&mid=$mid\">" . _EDIT . "</a> ]</font></center>";
					}
					CloseTable();
					echo "<br>";
				} elseif ( $view == 3 and defined('IS_USER') || defined('IS_ADMIN') )
				{
					OpenTable();
					echo "<center><font class=\"option\" color=\"$textcolor2\"><b>$title</b></font></center><br>\n" . "<font class=\"content\">$content</font>";
					if ( defined('IS_SPADMIN') )
					{
						echo "<br><br><center><font class=\"content\">[ " . _MVIEWUSERS . " - $remain - <a href=\"" . $adminfold . "/" . $adminfile . ".php?op=editmsg&mid=$mid\">" . _EDIT . "</a> ]</font></center>";
					}
					CloseTable();
					echo "<br>";
				} elseif ( $view == 2 and ! defined('IS_USER') || defined('IS_ADMIN') )
				{
					OpenTable();
					echo "<center><font class=\"option\" color=\"$textcolor2\"><b>$title</b></font></center><br>\n" . "<font class=\"content\">$content</font>";
					if ( defined('IS_SPADMIN') )
					{
						echo "<br><br><center><font class=\"content\">[ " . _MVIEWANON . " - $remain - <a href=\"" . $adminfold . "/" . $adminfile . ".php?op=editmsg&mid=$mid\">" . _EDIT . "</a> ]</font></center>";
					}
					CloseTable();
					echo "<br>";
				} elseif ( $view == 1 )
				{
					OpenTable();
					echo "<center><font class=\"option\" color=\"$textcolor2\"><b>$title</b></font></center><br>\n" . "<font class=\"content\">$content</font>";
					if ( defined('IS_SPADMIN') )
					{
						echo "<br><br><center><font class=\"content\">[ " . _MVIEWALL . " - $remain - <a href=\"" . $adminfold . "/" . $adminfile . ".php?op=editmsg&mid=$mid\">" . _EDIT . "</a> ]</font></center>";
					}
					CloseTable();
					echo "<br>";
				}
				if ( $expire != 0 )
				{
					$past = time() - $expire;
					if ( $mdate < $past )
					{
						$db->sql_query( "UPDATE " . $prefix . "_message SET active='0' WHERE mid='$mid'" );
					}
				}
			}
		}
	}
}

/**
 * cookiedecode()
 * 
 * @param mixed $user
 * @return
 */
function cookiedecode( $user )
{
	global $cookie, $prefix, $db, $user_prefix;
	$user = base64_decode( $user );
	$user = addslashes( $user );
	$user = htmlentities( $user, ENT_QUOTES );
	$cookie = explode( ":", $user );
	$sql = "SELECT user_password FROM " . $user_prefix . "_users WHERE username='$cookie[1]'";
	$result = $db->sql_query( $sql );
	$row = $db->sql_fetchrow( $result );
	$pass = $row['user_password'];
	if ( $cookie[2] == $pass && $pass != "" )
	{
		return $cookie;
	}
	else
	{
		unset( $user );
		unset( $cookie );
	}
}

/**
 * getusrinfo()
 * 
 * @param mixed $user
 * @return
 */
function getusrinfo( $user )
{
	global $userinfo, $user_prefix, $db;
	$user2 = base64_decode( $user );
	$user2 = addslashes( $user2 );
	$user3 = explode( ":", $user2 );
	$sql = "SELECT * FROM " . $user_prefix . "_users WHERE username='$user3[1]' AND user_password='$user3[2]'";
	$result = $db->sql_query( $sql );
	if ( $db->sql_numrows($result) == 1 )
	{
		$userinfo = $db->sql_fetchrow( $result );
	}
	return $userinfo;
}

/**
 * FixQuotes()
 * 
 * @param string $what
 * @return
 */
function FixQuotes( $what = "" )
{

	$what = ereg_replace( "'", "''", $what );
	while ( eregi("\\\\'", $what) )
	{
		$what = ereg_replace( "\\\\'", "'", $what );
	}
	return $what;
}


/**
 * check_words()
 * 
 * @param mixed $Message
 * @return
 */
function check_words( $Message )
{
	global $CensorMode, $EditedMessage, $datafold;
	include ( "" . INCLUDE_PATH . "$datafold/config.php" );
	$EditedMessage = $Message;
	if ( $CensorMode != 0 )
	{
		if ( is_array($CensorList) )
		{
			$Replace = $CensorReplace;
			if ( $CensorMode == 1 )
			{
				for ( $i = 0; $i < count($CensorList); $i++ )
				{
					$EditedMessage = eregi_replace( "$CensorList[$i]([^a-zA-Z0-9])", "$Replace\\1", $EditedMessage );
				}
			} elseif ( $CensorMode == 2 )
			{
				for ( $i = 0; $i < count($CensorList); $i++ )
				{
					$EditedMessage = eregi_replace( "(^|[^[:alnum:]])$CensorList[$i]", "\\1$Replace", $EditedMessage );
				}
			} elseif ( $CensorMode == 3 )
			{
				for ( $i = 0; $i < count($CensorList); $i++ )
				{
					$EditedMessage = eregi_replace( "$CensorList[$i]", "$Replace", $EditedMessage );
				}
			}
		}
	}
	return ( $EditedMessage );
}

/**
 * delQuotes()
 * 
 * @param mixed $string
 * @return
 */
function delQuotes( $string )
{


	$tmp = "";

	$result = "";

	$i = 0;
	$attrib = -1;

	$quote = 0;

	$len = strlen( $string );
	while ( $i < $len )
	{
		switch ( $string[$i] )
		{

			case "\"":

				if ( $quote == 0 )
				{
					$quote = 1;
				}
				else
				{
					$quote = 0;
					if ( ($attrib > 0) && ($tmp != "") )
					{
						$result .= "=\"$tmp\"";
					}
					$tmp = "";
					$attrib = -1;
				}
				break;
			case "=":

				if ( $quote == 0 )
				{

					$attrib = 1;
					if ( $tmp != "" ) $result .= " $tmp";
					$tmp = "";
				}
				else  $tmp .= '=';
				break;
			case " ":

				if ( $attrib > 0 )
				{

					$tmp .= $string[$i];
				}
				break;
			default:

				if ( $attrib < 0 ) $attrib = 0;
				$tmp .= $string[$i];
				break;
		}
		$i++;
	}
	if ( ($quote != 0) && ($tmp != "") )
	{
		if ( $attrib == 1 ) $result .= "=";

		$result .= "\"$tmp\"";

	}
	return $result;
}

/**
 * check_html()
 * 
 * @param mixed $str
 * @param string $strip
 * @return
 */
function check_html( $str, $strip = "" )
{
	global $datafold;


	include ( "" . INCLUDE_PATH . "$datafold/config.php" );
	if ( $strip == "nohtml" ) $AllowableHTML = array( '' );
	$str = stripslashes( $str );
	$str = eregi_replace( "<[[:space:]]*([^>]*)[[:space:]]*>", '<\\1>', $str );

	$str = eregi_replace( "<a[^>]*href[[:space:]]*=[[:space:]]*\"?[[:space:]]*([^\" >]*)[[:space:]]*\"?[^>]*>", '<a href="\\1">', $str );

	$str = eregi_replace( "<[[:space:]]* img[[:space:]]*([^>]*)[[:space:]]*>", '', $str );

	$str = eregi_replace( "<a[^>]*href[[:space:]]*=[[:space:]]*\"?javascript[[:punct:]]*\"?[^>]*>", '', $str );

	$tmp = "";
	while ( ereg("<(/?[[:alpha:]]*)[[:space:]]*([^>]*)>", $str, $reg) )
	{
		$i = strpos( $str, $reg[0] );
		$l = strlen( $reg[0] );
		if ( $reg[1][0] == "/" ) $tag = strtolower( substr($reg[1], 1) );
		else  $tag = strtolower( $reg[1] );
		if ( $a = $AllowableHTML[$tag] )
			if ( $reg[1][0] == "/" ) $tag = "</$tag>";
			elseif ( ($a == 1) || ($reg[2] == "") ) $tag = "<$tag>";
			else
			{

				$attrb_list = delQuotes( $reg[2] );

				$attrb_list = ereg_replace( "&", "&amp;", $attrb_list );
				$tag = "<$tag" . $attrb_list . ">";
			}

		else  $tag = "";
		$tmp .= substr( $str, 0, $i ) . $tag;
		$str = substr( $str, $i + $l );
	}
	$str = $tmp . $str;
	return $str;
	exit;

	$str = ereg_replace( "<\?", "", $str );
	return $str;
}

/**
 * filter_text()
 * 
 * @param mixed $Message
 * @param string $strip
 * @return
 */
function filter_text( $Message, $strip = "" )
{
	global $EditedMessage;
	check_words( $Message );
	$EditedMessage = check_html( $EditedMessage, $strip );
	return ( $EditedMessage );
}

/**
 * nl2brStrict()
 * 
 * @param mixed $text
 * @return
 */
function nl2brStrict( $text )
{
	$text = trim( $text );
	$text = ereg_replace( " \r\n", "\r\n", $text );
	$text = str_replace( "\r", '', $text );
	$text = preg_replace( '/(?<!>)\n/', "<br />", $text );
	return $text;
}

/**
 * cheonguoc()
 * 
 * @param mixed $text
 * @return
 */
function cheonguoc( $text )
{
	$text = str_replace( "\\", '&#92;', $text );
	return $text;
}


/**
 * formatTimestamp()
 * 
 * @param mixed $time
 * @param integer $ht
 * @return
 */
function formatTimestamp( $time, $ht = 1 )
{
	global $datetime, $hourdiff, $htg1, $htg2;
	ereg( "([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $time, $datetime );
	$datetime = mktime( $datetime[4], $datetime[5], $datetime[6], $datetime[2], $datetime[3], $datetime[1] );
	$timeadjust = ( $hourdiff * 60 );
	$datetime = $datetime + $timeadjust;
	if ( $ht == 2 )
	{
		$xht = $htg2;
	}
	else
	{
		$xht = $htg1;
	}
	$datetime = date( "$xht", $datetime );
	return ( $datetime );
}

/**
 * viewtime()
 * 
 * @param mixed $vtime
 * @param mixed $ht
 * @return
 */
function viewtime( $vtime, $ht )
{
	global $hourdiff, $htg1, $htg2;
	if ( $ht == 2 )
	{
		$xht = $htg2;
	}
	else
	{
		$xht = $htg1;
	}
	$timeadjust = ( $hourdiff * 60 );
	$viewtime = date( "$xht", $vtime + $timeadjust );
	return ( $viewtime );
}

/**
 * generate_page()
 * 
 * @param mixed $base_url
 * @param mixed $num_items
 * @param mixed $per_page
 * @param mixed $start_item
 * @param bool $add_prevnext_text
 * @return
 */
function generate_page( $base_url, $num_items, $per_page, $start_item, $add_prevnext_text = true )
{
	$total_pages = ceil( $num_items / $per_page );
	if ( $total_pages == 1 )
	{
		return '';
	}

	@$on_page = floor( $start_item / $per_page ) + 1;
	$page_string = '';
	if ( $total_pages > 10 )
	{
		$init_page_max = ( $total_pages > 3 ) ? 3 : $total_pages;
		for ( $i = 1; $i < $init_page_max + 1; $i++ )
		{
			$page_string .= ( $i == $on_page ) ? '<b>' . $i . '</b>' : '<a href="' . $base_url . "&amp;page=" . ( ($i - 1) * $per_page ) . '">' . $i . '</a>';
			if ( $i < $init_page_max )
			{
				$page_string .= ", ";
			}
		}
		if ( $total_pages > 3 )
		{
			if ( $on_page > 1 && $on_page < $total_pages )
			{
				$page_string .= ( $on_page > 5 ) ? ' ... ' : ', ';
				$init_page_min = ( $on_page > 4 ) ? $on_page : 5;
				$init_page_max = ( $on_page < $total_pages - 4 ) ? $on_page : $total_pages - 4;
				for ( $i = $init_page_min - 1; $i < $init_page_max + 2; $i++ )
				{
					$page_string .= ( $i == $on_page ) ? '<b>' . $i . '</b>' : '<a href="' . $base_url . "&amp;page=" . ( ($i - 1) * $per_page ) . '">' . $i . '</a>';
					if ( $i < $init_page_max + 1 )
					{
						$page_string .= ', ';
					}
				}
				$page_string .= ( $on_page < $total_pages - 4 ) ? ' ... ' : ', ';
			}
			else
			{
				$page_string .= ' ... ';
			}

			for ( $i = $total_pages - 2; $i < $total_pages + 1; $i++ )
			{
				$page_string .= ( $i == $on_page ) ? '<b>' . $i . '</b>' : '<a href="' . $base_url . "&amp;page=" . ( ($i - 1) * $per_page ) . '">' . $i . '</a>';
				if ( $i < $total_pages )
				{
					$page_string .= ", ";
				}
			}
		}
	}
	else
	{
		for ( $i = 1; $i < $total_pages + 1; $i++ )
		{
			$page_string .= ( $i == $on_page ) ? '<b>' . $i . '</b>' : '<a href="' . $base_url . "&amp;page=" . ( ($i - 1) * $per_page ) . '">' . $i . '</a>';
			if ( $i < $total_pages )
			{
				$page_string .= ', ';
			}
		}
	}
	if ( $add_prevnext_text )
	{
		if ( $on_page > 1 )
		{
			$page_string = ' <a href="' . $base_url . "&amp;page=" . ( ($on_page - 2) * $per_page ) . '">' . _PAGEPREV . '</a>&nbsp;&nbsp;' . $page_string;
		}
		if ( $on_page < $total_pages )
		{
			$page_string .= '&nbsp;&nbsp;<a href="' . $base_url . "&amp;page=" . ( $on_page * $per_page ) . '">' . _PAGENEXT . '</a>';
		}
	}
	$page_string = _PAGESNUM . ' ' . $page_string;
	return $page_string;
}

/**
 * removecrlf()
 * 
 * @param mixed $str
 * @return
 */
function removecrlf( $str )
{
	return strtr( $str, "\015\012", ' ' );
}

/**
 * info_exit()
 * 
 * @param mixed $info
 * @return
 */
function info_exit( $info )
{
	$info = stripslashes( FixQuotes($info) );
	include ( INCLUDE_PATH . "header.php" );
	OpenTable();
	echo "<br><center><b>" . $info . "</b></center><br>";
	CloseTable();
	include ( INCLUDE_PATH . "footer.php" );
	exit();
}

/**
 * select_language()
 * 
 * @param mixed $sellang
 * @return
 */
function select_language( $sellang )
{
	$handle = opendir( "" . INCLUDE_PATH . "language" );
	while ( $file = readdir($handle) )
	{
		if ( preg_match("/^lang\-(.+)\.php/", $file, $matches) )
		{
			$langFound = $matches[1];
			$languageslist .= "$langFound ";
		}
	}
	closedir( $handle );
	$languageslist = explode( " ", $languageslist );
	sort( $languageslist );
	for ( $i = 0; $i < sizeof($languageslist); $i++ )
	{
		if ( $languageslist[$i] != "" )
		{
			$select_lang .= "<option value='" . $languageslist[$i] . "' ";
			if ( $languageslist[$i] == $sellang ) $select_lang .= "selected";
			$select_lang .= ">" . ucfirst( $languageslist[$i] ) . "</option>";
		}
	}
	return $select_lang;
}

/**
 * aleditor()
 * 
 * @param mixed $content
 * @param mixed $val
 * @param mixed $ewidth
 * @param mixed $eheight
 * @return
 */
function aleditor( $content, $val, $ewidth, $eheight )
{
	global $currentlang;
	if ( $currentlang == "vietnamese" )
	{
		$lag = "vn";
	}
	else
	{
		$lag = "en";
	}
	$sw = new SpawEditor( "$content", $val );
	SpawConfig::setStaticConfigValue( 'default_height', $eheight );
	SpawConfig::setStaticConfigValue( 'default_lang', $lag );
	$sw->show();
}

/**
 * nvheader()
 * 
 * @param mixed $filename
 * @return
 */
function nvheader( $filename )
{
	echo '<script type="text/javascript">';
	echo 'window.location.href="' . $filename . '";';
	echo '</script>';
	echo '<noscript>';
	echo '<meta http-equiv="refresh" content="0;url=' . $filename . '" />';
	echo '</noscript>';
}

/**
 * at_unhtmlspecialchars()
 * 
 * @param mixed $string
 * @return
 */
function at_unhtmlspecialchars( $string )
{
	$array = array( '&' => '&amp;', '\'' => '&#039;', '"' => '&quot;', '<' => '&#x003C;', '>' => '&#x003E;', '\\' => '&#x005C;', '/' => '&#x002F;' );
	foreach ( $array as $key => $value )
	{
		$string = str_replace( $value, $key, $string );
	}
	return $string;
}

/**
 * at_htmlspecialchars()
 * 
 * @param mixed $string
 * @return
 */
function at_htmlspecialchars( $string )
{
	$string = at_unhtmlspecialchars( $string );
	$array = array( '&' => '&amp;', '\'' => '&#039;', '"' => '&quot;', '<' => '&#x003C;', '>' => '&#x003E;', '\\' => '&#x005C;', '/' => '&#x002F;' );
	foreach ( $array as $key => $value )
	{
		$string = str_replace( $key, $value, $string );
	}
	return $string;
}

/**
 * at_getextension()
 * 
 * @param mixed $filename
 * @return
 */
function at_getextension( $filename )
{
	if ( strpos($filename, '.') === false )
	{
		return '';
	}
	$filename = basename( strtolower($filename) );
	$filename = explode( '.', $filename );
	return array_pop( $filename );
}


/**
 * nv_htmlspecialchars()
 * 
 * @param mixed $string
 * @return
 */
function nv_htmlspecialchars( $string )
{
	$search = array( '&', '\'', '"', '<', '>', '\\', '/' );
	$replace = array( '&amp;', '&#039;', '&quot;', '&#x003C;', '&#x003E;', '&#x005C;', '&#x002F;' );
	$string = str_replace( $replace, $search, $string );
	$string = str_replace( $search, $replace, $string );
	return $string;
}

/**
* nv_capcha_txt()
*
* @param mixed $seccode
* @return
*/
function nv_capcha_txt( $seccode )
{
   $return = false;
   if ($seccode ==$_SESSION['random_num'] ) $return = true;
   //echo $seccode . "<br>".$_SESSION['random_num'];
   $_SESSION['random_num'] = mt_rand( 100000, 999999 );
   return $return;
}

?>