<?php

/*
* @Program:		NukeViet CMS
* @File name: 	NukeViet System
* @Version: 	2.0 RC2
* @Date: 		31.05.2009
* @Website: 	www.nukeviet.vn
* @Copyright: 	(C) 2009
* @License: 	http://opensource.org/licenses/gpl-license.php GNU Public License
*/

if ( eregi("header.php", $_SERVER['SCRIPT_NAME']) )
{
	Header( "Location: index.php" );
	die();
}

@require_once ( "mainfile.php" );

/**
 * head()
 * 
 * @return
 */
function head()
{
	global $key_words, $header_page_keyword, $home, $ThemeSel, $page_title, $page_title2, $module_title, $sitename, $banners, $nukeurl, $user, $cookie, $bgcolor1, $bgcolor2, $bgcolor3, $bgcolor4, $textcolor1, $textcolor2, $adminpage, $pagetitle, $month;
	if ( $home ) $module_title = _HOMEPAGE;
	include ( INCLUDE_PATH . "themes/" . $ThemeSel . "/theme.php" );

	$keys = array();
	if ( isset($key_words) and ! empty($key_words) )
	{
		$keys = strip_tags( $key_words );
		$keys = explode( ",", $keys );
		$keys = array_map( "trim", $keys );
	}

	if ( isset($header_page_keyword) and ! empty($header_page_keyword) )
	{
		$keywords = strip_tags( $header_page_keyword );
		$keywords = trim( ereg_replace('("|\?|!|:|\.|\(|\)|;|\\\\)+', ' ', $keywords) );
		$keywords = ereg_replace( '( |' . CHR(10) . '|' . CHR(13) . ')+', ',', $keywords );
		$keywords = substr( $keywords, 0, 1600 );
		$keywords = explode( ",", $keywords );
		$keywords = array_unique( $keywords );
		if ( ! empty($keywords) )
		{
			foreach ( $keywords as $keyword )
			{
				if ( strlen($keyword) > 3 ) $keys[] = $keyword;
			}
		}
	}
	$keys = ( ! empty($keys) ) ? implode( ", ", $keys ) : "";
	$keys .= "nukeviet, phpnuke, vietnam, cms, php, MySQL, portal, vietnamese";
	$description = strip_tags( (! empty($page_title)) ? $page_title : $sitename );
	$headtitle = $sitename;
	if ( ! empty($module_title) ) $headtitle .= " | " . $module_title;
	if ( ! empty($page_title) ) $headtitle .= " | " . $page_title;

	@Header( "Last-Modified: " . gmdate("D, d M Y H:i:s", strtotime("-1 day")) . " GMT" );
	@Header( "Content-Type: text/html; charset=" . _CHARSET );
	if ( ! empty($_SERVER['SERVER_SOFTWARE']) && strstr($_SERVER['SERVER_SOFTWARE'], 'Apache/2') )
	{
		@Header( "Cache-Control: no-cache, pre-check=0, post-check=0" );
	}
	else
	{
		@Header( "Cache-Control: private, pre-check=0, post-check=0, max-age=0" );
	}
	@Header( "Expires: 0" );
	@Header( "Pragma: no-cache" );
	$head = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
	$head .= "<html>\n";
	$head .= "<head>\n";
	$head .= "<meta http-equiv=\"content-type\" content=\"text/html; charset=" . _CHARSET . "\">\n";
	$head .= "<meta http-equiv=\"expires\" content=\"0\">\n";
	$head .= "<meta name=\"resource-type\" content=\"document\">\n";
	$head .= "<meta name=\"distribution\" content=\"global\">\n";
	$head .= "<meta name=\"author\" content=\"" . $sitename . "\">\n";
	$head .= "<meta name=\"copyright\" content=\"Copyright (c) " . gmdate( "Y" ) . " by " . $sitename . "\">\n";
	$head .= "<meta name=\"keywords\" content=\"" . $keys . "\">\n";
	$head .= "<meta name=\"description\" content=\"" . $description . "\">\n";
	$head .= "<meta name=\"robots\" content=\"index, follow\">\n";
	$head .= "<meta name=\"revisit-after\" content=\"1 days\">\n";
	$head .= "<meta name=\"rating\" content=\"general\">\n";
	$head .= "<title>" . $headtitle . "</title>\n";
	$head .= "<script type=\"text/javascript\">\n";
	$head .= "function MM_openBrWindow(theURL,winName,features) {\n";
	$head .= "window.open(theURL,winName,features);\n";
	$head .= "}\n";
	$head .= "</script>\n";
	$head .= "<link rel=\"StyleSheet\" href=\"" . INCLUDE_PATH . "themes/" . $ThemeSel . "/style/style.css\" type=\"text/css\">\n";
	$head .= "<link rel=\"alternate\" type=\"application/rss+xml\" title=\"News - " . strip_tags( $sitename ) . "\" href=\"" . INCLUDE_PATH . "modules.php?name=News&amp;file=rss\">\n";
	$head .= "<link rel=\"alternate\" type=\"application/rss+xml\" title=\"Files - " . strip_tags( $sitename ) . "\" href=\"" . INCLUDE_PATH . "modules.php?name=Files&amp;file=rss\">\n";
	$head .= "  <link rel=\"icon\" href=\"" . INCLUDE_PATH . "favicon.ico\" type=\"image/vnd.microsoft.icon\">\n";
	$head .= "  <link rel=\"shortcut icon\" href=\"" . INCLUDE_PATH . "favicon.ico\" type=\"image/vnd.microsoft.icon\">\n";
	$head .= "</head>\n\n";
	echo $head;
	themeheader();
}

head();

if ( $home )
{
	newsstart();
	message_box();
	blocks( 'Center', $name );
}

?>