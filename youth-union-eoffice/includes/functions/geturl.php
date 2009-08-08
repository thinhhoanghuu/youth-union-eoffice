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
	die( 'Stop!!!' );
}
if ( ! defined('NV_MAINFILE') )
{
	die( 'Stop!!!' );
}

/**
 * detect_openfile_methods()
 * 
 * @return
 */
function detect_openfile_methods()
{
	$disable_functions = @ini_get( "disable_functions" );
	if ( empty($sdisable_functions) )
	{
		$disable_functions = array();
	}
	else
	{
		$disable_functions = split( ',\s*', $disable_functions );
		$disable_functions = array_map( 'trim', $disable_functions );
	}
	$allow_methods = array();
	if ( extension_loaded('curl') and function_exists("curl_init") and ! in_array('curl_init', $disable_functions) )
	{
		$allow_methods[] = 'curl';
	}
	if ( function_exists("fsockopen") and ! in_array('fsockopen', $disable_functions) )
	{
		$allow_methods[] = 'fsockopen';
	}
	if ( function_exists("fopen") and ! in_array('fopen', $disable_functions) )
	{
		$allow_methods[] = 'fopen';
	}
	if ( function_exists("file_get_contents") and ! in_array('file_get_contents', $disable_functions) )
	{
		$allow_methods[] = 'file_get_contents';
	}
	return $allow_methods;
}

/**
 * urlinfo()
 * 
 * @param mixed $url
 * @return
 */
function urlinfo( $url )
{
	//URL: http://username:password@www.example.com:80/dir/page.php?foo=bar&foo2=bar2#bookmark
	$url_info = @parse_url( $url );
	//[host] => www.example.com
	if ( ! isset($url_info['host']) ) return false;
	//[port] => :80
	$url_info['port'] = isset( $url_info['port'] ) ? $url_info['port'] : 80;
	//[login] => username:password@
	$url_info['login'] = isset( $url_info['user'] ) ? $url_info['user'] . ( isset($url_info['pass']) ? ':' . $url_info['pass'] : '' ) . '@' : '';
	//[path] => /dir/page.php
	$url_info['path'] = isset( $url_info['path'] ) ? ( (substr($url_info['path'], 0, 1) == '/') ? $url_info['path'] : ('/' . $url_info['path']) ) : '/';
	//[query] => ?foo=bar&foo2=bar2
	$url_info['query'] = isset( $url_info['query'] ) ? '?' . $url_info['query'] : '';
	//[fragment] => bookmark
	$url_info['fragment'] = isset( $url_info['fragment'] ) ? $url_info['fragment'] : '';
	//[file] => page.php
	$url_info['file'] = array_pop( explode('/', $url_info['path']) );
	//[dir] => /dir
	$url_info['dir'] = substr( $url_info['path'], 0, strrpos($url_info['path'], '/') );
	//[base] => http://www.example.com/dir
	$url_info['base'] = $url_info['scheme'] . '://' . $url_info['host'] . $url_info['dir'];
	//[uri] => http://username:password@www.example.com:80/dir/page.php?#bookmark
	$url_info['uri'] = $url_info['scheme'] . '://' . $url_info['login'] . $url_info['host'] . ( ($url_info['port'] != 80) ? $url_info['port'] : '' ) . $url_info['path'] . '?' . $url_info['query_plus'] . ( ($url_info['fragment'] != '') ? '#' . $url_info['fragment'] : '' );
	ksort( $url_info );
	return $url_info;
}

/**
 * get_contents()
 * 
 * @param mixed $url
 * @param mixed $method
 * @param string $login
 * @param string $password
 * @param string $ref
 * @return
 */
function get_contents( $url, $method, $login = '', $password = '', $ref = '' )
{
	if ( function_exists("set_time_limit") && @ini_get('safe_mode') == 'Off' )
	{
		@set_time_limit( 0 );
	}
	@ini_set( 'allow_url_fopen', 1 );
	@ini_set( 'default_socket_timeout', 120 );
	@ini_set( 'memory_limit', '40M' );
	$url_info = urlinfo( $url );
	if ( ! $url_info ) return false;
	if ( $method == "curl" )
	{
		$curlHandle = curl_init();
		curl_setopt( $curlHandle, CURLOPT_ENCODING, '' );
		curl_setopt( $curlHandle, CURLOPT_URL, $url );
		curl_setopt( $curlHandle, CURLOPT_HEADER, 0 );
		curl_setopt( $curlHandle, CURLOPT_RETURNTRANSFER, 1 );
		if ( ! empty($login) )
		{
			curl_setopt( $curlHandle, CURLOPT_HTTPAUTH, CURLAUTH_BASIC );
			curl_setopt( CURLOPT_USERPWD, '[$login]:[$password]' );
		}
		curl_setopt( $curlHandle, CURLOPT_USERAGENT, ini_get("user_agent") );
		if ( ! empty($ref) )
		{
			curl_setopt( $curlHandle, CURLOPT_REFERER, urlencode($ref) );
		}
		else
		{
			curl_setopt( $curlHandle, CURLOPT_REFERER, $url );
		}
		if ( ! (ini_get("safe_mode") || ini_get("open_basedir")) )
		{
			curl_setopt( $curlHandle, CURLOPT_FOLLOWLOCATION, 1 );
			curl_setopt( $curlHandle, CURLOPT_MAXREDIRS, 10 );
		}
		curl_setopt( $curlHandle, CURLOPT_TIMEOUT, 30 );
		$result = curl_exec( $curlHandle );
		if ( curl_errno($curlHandle) == 23 || curl_errno($curlHandle) == 61 )
		{
			curl_setopt( $curlHandle, CURLOPT_ENCODING, 'none' );
			$result = curl_exec( $curlHandle );
		}
		if ( curl_errno($curlHandle) )
		{
			curl_close( $curlHandle );
			return false;
		}
		$response = curl_getinfo( $curlHandle );
		if ( ($response['http_code'] < 200) || (300 <= $response['http_code']) )
		{
			curl_close( $curlHandle );
			return false;
		}
		curl_close( $curlHandle );
	} elseif ( $method == "fsockopen" )
	{
		if ( isset($url_info['scheme']) and strtolower($url_info['scheme']) == 'https' )
		{
			$url_info['host'] = "ssl://$url_info[host]";
			$url_info['port'] = 443;
		}
		$port = isset( $url_info['port'] ) ? $url_info['port'] : 80;
		$fp = @fsockopen( $url_info['host'], $port, $errno, $errstr, 10 );
		if ( ! $fp )
		{
			return false;
		}
		$url_info['path'] = ( empty($url_info['path']) ) ? '/' : $url_info['path'];
		$query = ( isset($url_info["query"]) ) ? '?' . $url_info["query"] : '';
		$request = "GET " . $url_info['path'] . "" . $query . " HTTP/1.0\r\n";
		if ( ! empty($url_info['port']) )
		{
			$request .= "Host: " . $url_info['host'] . ":" . $url_info['port'] . "\r\n";
		}
		else
		{
			$request .= "Host: " . $url_info['host'] . "\r\n";
		}
		$request .= "Connection: Close\r\n";
		$request .= "User-Agent: " . ini_get( "user_agent" ) . "\r\n\r\n";
		if ( function_exists('gzinflate') )
		{
			$request .= "Accept-Encoding: gzip,deflate\r\n";
		}
		$request .= "Accept: */*\r\n";
		if ( ! empty($ref) )
		{
			$request .= "Referer: " . urlencode( $ref ) . "\r\n";
		}
		else
		{
			$request .= "Referer: " . $url . "\r\n";
		}
		if ( ! empty($login) )
		{
			$request .= "Authorization: Basic " . base64_encode( $login . ':' . $password ) . "\r\n";
		}
		$request .= "\r\n";
		if ( @fwrite($fp, $request) === false )
		{
			@fclose( $fp );
			return false;
		}
		@stream_set_blocking( $fp, true );
		@stream_set_timeout( $fp, 10 );
		$info = @stream_get_meta_data( $fp );
		$response = "";
		while ( (! @feof($fp)) && (! $info['timed_out']) )
		{
			$response .= @fgets( $fp, 4096 );
			$info = @stream_get_meta_data( $fp );
			if ( $info['timed_out'] )
			{
				@fclose( $fp );
				return false;
			}
			@ob_flush;
			@flush();
		}
		if ( function_exists('gzinflate') and substr($response, 0, 8) == "\x1f\x8b\x08\x00\x00\x00\x00\x00" )
		{
			$response = substr( $response, 10 );
			$response = gzinflate( $response );
		}
		@fclose( $fp );
		list( $header, $result ) = preg_split( "/\r?\n\r?\n/", $response, 2 );
		unset( $matches );
		preg_match( "/^HTTP\/[0-9\.]+\s+(\d+)\s+/", $header, $matches );
		if ( $matches == array() || $matches[1] != 200 )
		{
			return false;
		}
	} elseif ( $method == "fopen" )
	{
		if ( ($fd = @fopen($url, "rb")) === false )
		{
			return false;
		}
		$result = '';
		while ( ($data = fread($fd, 4096)) != "" )
		{
			$result .= $data;
		}
		fclose( $fd );
	} elseif ( $method == "file_get_contents" )
	{
		$result = @file_get_contents( $url );
	}
	else
	{
		return false;
	}
	return $result;
}

?>