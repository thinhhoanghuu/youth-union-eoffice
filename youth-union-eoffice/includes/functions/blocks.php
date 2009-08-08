<?php

/*
* @Program:		NukeViet CMS
* @File name: 	NukeViet System
* @Version: 	2.0 RC2
* @Date: 		06.07.2009
* @Website: 	www.nukeviet.vn
* @Copyright: 	(C) 2009
* @License: 	http://opensource.org/licenses/gpl-license.php GNU Public License
*/

if ( (! defined('NV_SYSTEM')) and (! defined('NV_ADMIN')) ) die( 'Stop!!!' );

if ( ! defined('NV_MAINFILE') ) die( 'Stop!!!' );

/**
 * fixweight()
 * 
 * @return
 */
/**
 * fixweight()
 * 
 * @return
 */
function fixweight()
{
	global $prefix, $db;
	$sql = "SELECT * FROM " . $prefix . "_blocks ORDER BY blanguage, bposition, weight";
	$result = $db->sql_query( $sql );
	$xweight = 1;
	while ( $row = $db->sql_fetchrow($result) )
	{
		$posit = $row['bposition'];
		if ( $posit != $old_posit ) $xweight = 1;
		$old_posit = $posit;
		$db->sql_query( "UPDATE " . $prefix . "_blocks SET weight = '$xweight'  WHERE bid = '$row[bid]'" );
		$xweight++;
	}
}

/**
 * blist()
 * 
 * @return
 */
/**
 * blist()
 * 
 * @return
 */
function blist()
{
	global $prefix, $db, $datafold, $currentlang;
	$sql = "SELECT * FROM `" . $prefix . "_blocks` WHERE `blanguage`='" . $currentlang . "' AND active=1 ORDER BY `weight`";
	$result = $db->sql_query( $sql );
	$blocks = array();
	while ( $row = $db->sql_fetchrow($result) )
	{
		$modules = ( $row['module'] != "" ) ? explode( "|", $row['module'] ) : array( "all" );
		$blocks[$row['bposition']][$row['bid']] = array( 'bkey' => $row['bkey'], 'title' => stripslashes($row['title']), 'url' => $row['url'], 'active' => $row['active'], 'refresh' => $row['refresh'], 'time' => $row['time'], 'blockfile' => $row['blockfile'], 'view' => $row['view'], 'expire' => $row['expire'], 'action' => $row['action'], 'link' => $row['link'], 'module' => $modules );
	}

	$blocks = serialize( $blocks );
	$f = INCLUDE_PATH . $datafold . "/blist_" . $currentlang . ".txt";
	@$file = fopen( $f, "w" );
	@$writefile = fwrite( $file, $blocks );
	@fclose( $file );
	@chmod( $f, 0604 );
}


/**
 * blocks()
 * 
 * @param mixed $side
 * @param mixed $name
 * @return
 */
/**
 * blocks()
 * 
 * @param mixed $side
 * @param mixed $name
 * @return
 */
function blocks( $side, $name )
{
	global $prefix, $multilingual, $currentlang, $db, $block_side, $home, $datafold;
	$blocks = unserialize( file_get_contents(INCLUDE_PATH . $datafold . "/blist_" . $currentlang . ".txt") );
	$now = time();
	$block_side = strtolower( $side[0] );
	$bls = array();
	if ( $block_side == "l" ) $bls = $blocks['l'];
	elseif ( $block_side == "r" ) $bls = $blocks['r'];
	elseif ( $block_side == "c" ) $bls = $blocks['c'];
	elseif ( $block_side == "d" ) $bls = $blocks['d'];

	if ( ! empty($bls) )
	{
		foreach ( $bls as $bid => $bl )
		{
			if ( defined('NV_ADMIN') ) $name = "acp";
			if ( $home ) $name = "home";
			if ( in_array("all", $bl['module']) or ($name != "" and in_array($name, $bl['module'])) )
			{
				if ( $bl['expire'] and $bl['expire'] <= $now )
				{
					if ( $bl['action'] == "d" )
					{
						$db->sql_query( "UPDATE `" . $prefix . "_blocks`` SET `active`='0', `expire`='0' WHERE `bid`='" . $bid . "'" );
						blist();
					} elseif ( $bl['action'] == "r" )
					{
						$db->sql_query( "DELETE FROM `" . $prefix . "_blocks` WHERE `bid`='" . $bid . "'" );
						$db->sql_query( "OPTIMIZE TABLE `" . $prefix . "_blocks`" );
						@unlink( "" . INCLUDE_PATH . "" . $datafold . "/" . $bl['blockfile'] . "" );
						fixweight();
						blist();
					}
					continue;
				}

				$bl_acc = false;
				if ( $bl['view'] == 0 ) $bl_acc = true;
				elseif ( $bl['view'] == 1 and defined('IS_USER') || defined('IS_ADMIN') ) $bl_acc = true;
				elseif ( $bl['view'] == 2 and defined('IS_ADMIN') ) $bl_acc = true;
				elseif ( $bl['view'] == 3 and ! defined('IS_USER') || defined('IS_ADMIN') ) $bl_acc = true;
				if ( $bl_acc )
				{
					$block_path = ( $bl['bkey'] == 0 ) ? INCLUDE_PATH . "blocks/" : INCLUDE_PATH . $datafold . "/";
					if ( ! file_exists($block_path . $bl['blockfile']) ) $content = _BLOCKPROBLEM;
					else  include ( $block_path . $bl['blockfile'] );

					if ( $bl['bkey'] == 2 )
					{
						$siteurl = ereg_replace( "http://", "", $bl['url'] );
						$siteurl = explode( "/", $siteurl );
						$bl['link'] = "http://" . $siteurl[0];
						if ( $bl['time'] < $now - $bl['refresh'] )
						{
							$rdf = parse_url( $bl['url'] );
							$fp = fsockopen( $rdf['host'], 80, $errno, $errstr, 15 );
							if ( $fp )
							{
								if ( $rdf['query'] != '' )
								{
									$rdf['query'] = "?" . $rdf['query'];
								}
								fputs( $fp, "GET " . $rdf['path'] . $rdf['query'] . " HTTP/1.0\r\n" );
								fputs( $fp, "HOST: " . $rdf['host'] . "\r\n\r\n" );
								$string = "";
								while ( ! feof($fp) )
								{
									$pagetext = fgets( $fp, 300 );
									$string .= chop( $pagetext );
								}
								fputs( $fp, "Connection: close\r\n\r\n" );
								fclose( $fp );
								$items = explode( "</item>", $string );
								$content = "<font class=\"content\">";
								for ( $i = 0; $i < 10; $i++ )
								{
									$link = ereg_replace( ".*<link>", "", $items[$i] );
									$link = ereg_replace( "</link>.*", "", $link );
									$title2 = ereg_replace( ".*<title>", "", $items[$i] );
									$title2 = ereg_replace( "</title>.*", "", $title2 );
									$title2 = stripslashes( $title2 );
									if ( $items[$i] == "" and $cont != 1 )
									{
									}
									else
									{
										if ( strcmp($link, $title2) and $items[$i] != "" )
										{
											$cont = 1;
											$content .= "<img border=\"0\" src=\"images/arrow2.gif\" width=\"10\" height=\"5\">&nbsp;<a href=\"$link\" target=\"new\">$title2</a><br>\n";
										}
									}
								}
								$sql = "UPDATE `" . $prefix . "_blocks` SET time='" . $now . "' WHERE `bid`='" . $bid . "'";
								$db->sql_query( $sql );
								@chmod( $block_path . $bl['blockfile'], 0777 );
								@$file = fopen( $block_path . $bl['blockfile'], "w" );
								$content2 = "<?php\n\n";
								$fctime = date( "d-m-Y H:i:s", filectime($block_path . $bl['blockfile']) );
								$fmtime = date( "d-m-Y H:i:s" );
								$content2 .= "// File: " . $bl['blockfile'] . ".\n// Created: $fctime.\n// Modified: $fmtime.\n// Do not change anything in this file!\n\n";
								$content2 .= "if ((!defined('NV_SYSTEM')) AND (!defined('NV_ADMIN'))) {\n";
								$content2 .= "die('Stop!!!');\n";
								$content2 .= "}\n";
								$content2 .= "\n";
								$content2 .= "\$content = \"" . htmlspecialchars( stripslashes($content) ) . "\";\n";
								$content2 .= "\n";
								$content2 .= "?>";
								@$writefile = fwrite( $file, $content2 );
								@fclose( $file );
								@chmod( $block_path . $bl['blockfile'], 0604 );
								blist();
							}
						}
						if ( ($cont == 1) or ($content != "") )
						{
							$content .= "<br><a href=\"http://" . $siteurl[0] . "\" target=\"blank\"><b>" . _HREADMORE . "</b></a></font>";
						}
					}

					if ( $bl['bkey'] != 0 ) $content = html_entity_decode( $content );
					if ( $content != "" )
					{
						if ( $block_side == "c" ) themecenterbox( $bl['title'], $content, $bl['link'] );
						elseif ( $block_side == "d" ) themecenterbox( $bl['title'], $content, $bl['link'] );
						else  themesidebox( $bl['title'], $content, $bl['link'] );
					}
					unset( $content );
				}
			}
		}
	}
}

if ( ! file_exists(INCLUDE_PATH . $datafold . "/blist_" . $currentlang . ".txt") )
{
	blist();
	header( "Location: " . INCLUDE_PATH . "index.php" );
	exit();
}

?>