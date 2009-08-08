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

if ( ! defined('NV_ADMIN') )
{
	die( "Access Denied" );
}

if ( defined('IS_SPADMIN') )
{


	/**
	 * Configure()
	 * 
	 * @return
	 */
	function Configure()
	{
		global $adminfile, $datafold;
		include ( "../$datafold/config.php" );
		include ( "../header.php" );
		GraphicAdmin();
		OpenTable();
		echo "<center><font class=\"title\"><b><a href=\"" . $adminfile . ".php?op=Configure\">" . _SITECONFIG . "</a></b></font></center>";
		CloseTable();
		echo "<br>";
		OpenTable();
		echo "<center><font class=\"option\"><b>" . _GENSITEINFO . "</b></font></center>" . "<form action=\"" . $adminfile . ".php\" method=\"post\">" . "<table border=\"1\" cellpadding=\"3\" cellspacing=\"3\" align=\"center\"><tr><td>" . "" . _SITENAME . ":</td><td><input type=\"text\" name=\"xsitename\" value=\"$sitename\" size=\"40\" maxlength=\"100\">" . "</td></tr><tr><td>" . "" . _SITEURL . ":</td><td><input type=\"text\" name=\"xnukeurl\" value=\"$nukeurl\" size=\"40\" maxlength=\"200\">" . "</td></tr><tr><td>" . "" . _SITELOGO . ":</td><td><input type=\"text\" name=\"xsite_logo\" value=\"$site_logo\" size=\"20\" maxlength=\"25\">" . "</td></tr><tr><td>" . "" . _STARTDATE . ":</td><td><input type=\"text\" name=\"xstartdate\" value=\"$startdate\" size=\"20\" maxlength=\"30\">" . "</td></tr><tr><td>" . "" . _ADMINEMAIL . ":</td><td><input type=\"text\" name=\"xadminmail\" value=\"$adminmail\" size=30 maxlength=100>" . "</td></tr><tr><td>" . "" . _ALLOWANONPOST . " </td><td>";
		if ( $anonpost == 1 )
		{
			echo "<input type=\"radio\" name=\"xanonpost\" value=\"1\" checked>" . _YES . " &nbsp;
        <input type=\"radio\" name=\"xanonpost\" value=\"0\">" . _NO . "";
		}
		else
		{
			echo "<input type=\"radio\" name=\"xanonpost\" value=\"1\">" . _YES . " &nbsp;
        <input type=\"radio\" name=\"xanonpost\" value=\"0\" checked>" . _NO . "";
		}
		echo "</td></tr><tr><td>" . "" . _DEFAULTTHEME . ":</td><td><select name=\"xDefault_Theme\">";
		$handle = opendir( '../themes' );
		while ( $file = readdir($handle) )
		{
			if ( (! ereg("[.]", $file)) )
			{
				$themelist .= "$file ";
			}
		}
		closedir( $handle );
		$themelist = explode( " ", $themelist );
		sort( $themelist );
		for ( $i = 0; $i < sizeof($themelist); $i++ )
		{
			if ( $themelist[$i] != "" )
			{
				echo "<option name=\"xDefault_Theme\" value=\"$themelist[$i]\" ";
				if ( $themelist[$i] == $Default_Theme ) echo "selected";
				echo ">$themelist[$i]\n";
			}
		}
		echo "</select>" . "</td></tr><tr><td>" . "" . _ALLCHANGTHEME . " </td><td>";
		if ( $changtheme == 1 )
		{
			echo "<input type=\"radio\" name=\"xchangtheme\" value=\"1\" checked>" . _YES . " &nbsp;
        <input type=\"radio\" name=\"xchangtheme\" value=\"0\">" . _NO . "";
		}
		else
		{
			echo "<input type=\"radio\" name=\"xchangtheme\" value=\"1\">" . _YES . " &nbsp;
        <input type=\"radio\" name=\"xchangtheme\" value=\"0\" checked>" . _NO . "";
		}
		echo "</td></tr><tr><td>" . "" . _ACTTHEMES . ":</td><td>";
		$x = 0;
		$actthemes2 = explode( "|", $actthemes );
		for ( $i = 0; $i < sizeof($themelist); $i++ )
		{
			if ( $themelist[$i] != "" )
			{
				if ( $x % 5 == 0 ) echo "<br>";
				echo "<input type=\"checkbox\" name=\"xactthemes[]\" value=\"$themelist[$i]\"";
				if ( in_array($themelist[$i], $actthemes2) ) echo " checked";
				echo "> $themelist[$i] ";
				$x++;
			}
		}
		echo "</td></tr><tr><td>" . "" . _LIVE_COOKIE_TIME . ":</td><td><select name=\"xlive_cookie_time\">" . "<option name=\"xlive_cookie_time\">$live_cookie_time</option>" . "<option name=\"xlive_cookie_time\">1</option>" . "<option name=\"xlive_cookie_time\">3</option>" . "<option name=\"xlive_cookie_time\">5</option>" . "<option name=\"xlive_cookie_time\">10</option>" . "<option name=\"xlive_cookie_time\">15</option>" . "<option name=\"xlive_cookie_time\">20</option>" . "<option name=\"xlive_cookie_time\">30</option>" . "<option name=\"xlive_cookie_time\">50</option>" . "</select>" . "</td></tr><tr><td>" . "" . _COOKIE_PATH . ":</td><td><input type=\"text\" name=\"xcookie_path\" value=\"$cookie_path\" size=40 maxlength=120>" . "</td></tr><tr><td>" . "" . _COOKIE_DOMAIN . ":</td><td><input type=\"text\" name=\"xcookie_domain\" value=\"$cookie_domain\" size=40 maxlength=120>" . "</td></tr><tr><td>" . "" . _USER_COOKIE . ":</td><td><input type=\"text\" name=\"xuser_cookie\" value=\"" .
			USER_COOKIE . "\" size=40 maxlength=120>" . "</td></tr><tr><td>" . "" . _ADMIN_COOKIE . ":</td><td><input type=\"text\" name=\"xadmin_cookie\" value=\"" . ADMIN_COOKIE . "\" size=40 maxlength=120>" . "</td></tr><tr><td>" . "" . _SELLANGUAGE . ":</td><td>" . "<select name=\"xlanguage\">";
		$handle = opendir( '../language' );
		echo select_language( $language );
		echo "</select>" . "</td></tr><tr><td>" . "" . _SECRETCODE . "</td><td>" . "<select name=\"xgfx_chk\">";
		$gfx_chk_array = array( _GFX00, _GFX01, _GFX02, _GFX03, _GFX04, _GFX05, _GFX06, _GFX07 );
		for ( $d = 0; $d <= 7; $d++ )
		{
			echo "<option name=\"xgfx_chk\" value=\"$d\"";
			if ( $d == $gfx_chk ) echo " selected";
			echo ">$gfx_chk_array[$d]</option>\n";
		}
		echo "</select></td></tr></table>";
		CloseTable();
		echo "<br>";
		OpenTable();
		echo "<center><font class=\"option\"><b>" . _MULTILINGUALOPT . "</b></font></center>" . "<table border=\"1\" cellpadding=\"3\" cellspacing=\"3\" align=\"center\"><tr><td>" . "" . _ACTMULTILINGUAL . "</td><td>";
		if ( $multilingual == 1 )
		{
			echo "<input type=\"radio\" name=\"xmultilingual\" value=\"1\" checked>" . _YES . " &nbsp;" . "<input type=\"radio\" name=\"xmultilingual\" value=\"0\">" . _NO . "";
		}
		else
		{
			echo "<input type=\"radio\" name=\"xmultilingual\" value=\"1\">" . _YES . " &nbsp;" . "<input type=\"radio\" name=\"xmultilingual\" value=\"0\" checked>" . _NO . "";
		}
		echo "</td></tr></table>";
		echo "<br>";
		CloseTable();
		echo "<br>";
		OpenTable();
		echo "<center><font class=\"option\"><b>" . _MISCOPT . "</b></font></center>" . "<table border=\"1\" cellpadding=\"3\" cellspacing=\"3\" align=\"center\"><tr><td>";
		echo "" . _MODULEINHOME . "</td><td>" . "<select name=\"xHome_Module\">";
		$handle = opendir( '../modules' );
		while ( $file = readdir($handle) )
		{
			if ( ! ereg("[.]", $file) )
			{
				$ModulFound = $file;
				$moduleslist .= "$ModulFound ";
			}
		}
		closedir( $handle );
		$moduleslist = explode( " ", $moduleslist );
		sort( $moduleslist );
		for ( $i = 0; $i < sizeof($moduleslist); $i++ )
		{
			if ( $moduleslist[$i] != "" )
			{
				echo "<option name=\"xHome_Module\" value=\"$moduleslist[$i]\" ";
				if ( $moduleslist[$i] == $Home_Module ) echo "selected";
				echo ">" . ucfirst( $moduleslist[$i] ) . "\n";
			}
		}
		echo "</select>";

		echo "</td></tr><tr><td>" . "" . _NOTIFYSUBMISSION . "</td><td>";
		if ( $notify == 1 )
		{
			echo "<input type=\"radio\" name=\"xnotify\" value=\"1\" checked>" . _YES . " &nbsp;
        <input type=\"radio\" name=\"xnotify\" value=\"0\">" . _NO . "";
		}
		else
		{
			echo "<input type=\"radio\" name=\"xnotify\" value=\"1\">" . _YES . " &nbsp;
        <input type=\"radio\" name=\"xnotify\" value=\"0\" checked>" . _NO . "";
		}
		echo "</td></tr><tr><td>" . "" . _ANONYMOUSNAME . ":</td><td><input type=\"text\" name=\"xanonymous\" value=\"$anonymous\">" . "</td></tr><tr><td>" . "" . _ADMINGRAPHIC . "</td><td>";
		if ( $admingraphic == 1 )
		{
			echo "<input type=\"radio\" name=\"xadmingraphic\" value=\"1\" checked>" . _YES . " &nbsp;
        <input type=\"radio\" name=\"xadmingraphic\" value=\"0\">" . _NO . "";
		}
		else
		{
			echo "<input type=\"radio\" name=\"xadmingraphic\" value=\"1\">" . _YES . " &nbsp;
        <input type=\"radio\" name=\"xadmingraphic\" value=\"0\" checked>" . _NO . "";
		}
		echo "</td></tr><tr><td>" . "" . _PASSWDLEN . ":</td><td>" . "<select name=\"xminpass\">" . "<option name=\"xminpass\" value=\"$minpass\">$minpass</option>" . "<option name=\"xminpass\" value=\"3\">3</option>" . "<option name=\"xminpass\" value=\"5\">5</option>" . "<option name=\"xminpass\" value=\"8\">8</option>" . "<option name=\"xminpass\" value=\"10\">10</option>" . "</select>" . "</td></tr><tr><td>" . "" . _COMMENTSPOLLS . "</td><td>";
		if ( $pollcomm == 1 )
		{
			echo "<input type=\"radio\" name=\"xpollcomm\" value=\"1\" checked>" . _YES . " &nbsp;
        <input type=\"radio\" name=\"xpollcomm\" value=\"0\">" . _NO . "";
		}
		else
		{
			echo "<input type=\"radio\" name=\"xpollcomm\" value=\"1\">" . _YES . " &nbsp;
        <input type=\"radio\" name=\"xpollcomm\" value=\"0\" checked>" . _NO . "";
		}
		echo "</td></tr><tr><td>" . "" . _COMMENTSARTICLES . "</td><td>";
		if ( $articlecomm == 1 )
		{
			echo "<input type=\"radio\" name=\"xarticlecomm\" value=\"1\" checked>" . _YES . " &nbsp;
        <input type=\"radio\" name=\"xarticlecomm\" value=\"0\">" . _NO . "";
		}
		else
		{
			echo "<input type=\"radio\" name=\"xarticlecomm\" value=\"1\">" . _YES . " &nbsp;
        <input type=\"radio\" name=\"xarticlecomm\" value=\"0\" checked>" . _NO . "";
		}

		echo "</td></tr><tr><td>" . "" . _GZIP_METHOD . "</td><td>";
		if ( $gzip_method == 1 )
		{
			echo "<input type=\"radio\" name=\"xgzip_method\" value=\"1\" checked>" . _YES . " &nbsp;
        <input type=\"radio\" name=\"xgzip_method\" value=\"0\">" . _NO . "";
		}
		else
		{
			echo "<input type=\"radio\" name=\"xgzip_method\" value=\"1\">" . _YES . " &nbsp;
        <input type=\"radio\" name=\"xgzip_method\" value=\"0\" checked>" . _NO . "";
		}


		echo "</td></tr><tr><td>" . "" . _EROR_VALUE . "</td><td>";
		if ( $eror_value == 1 )
		{
			echo "<input type=\"radio\" name=\"xeror_value\" value=\"1\" checked>" . _YES . " &nbsp;
        <input type=\"radio\" name=\"xeror_value\" value=\"0\">" . _NO . "";
		}
		else
		{
			echo "<input type=\"radio\" name=\"xeror_value\" value=\"1\">" . _YES . " &nbsp;
        <input type=\"radio\" name=\"xeror_value\" value=\"0\" checked>" . _NO . "";
		}

		echo "</td></tr><tr><td>" . "" . _COUNTERACT . "</td><td>";
		if ( $counteract == 1 )
		{
			echo "<input type=\"radio\" name=\"xcounteract\" value=\"1\" checked>" . _YES . " &nbsp;
        <input type=\"radio\" name=\"xcounteract\" value=\"0\">" . _NO . "";
		}
		else
		{
			echo "<input type=\"radio\" name=\"xcounteract\" value=\"1\">" . _YES . " &nbsp;
        <input type=\"radio\" name=\"xcounteract\" value=\"0\" checked>" . _NO . "";
		}

		echo "</td></tr><tr><td>" . "" . _NEWSEDITOR . "</td><td>";


		echo "<select name=\"xeditor\">";
		$gtd = array( _NO, Spaw1 );
		for ( $i = 0; $i < sizeof($gtd); $i++ )
		{
			echo "<option value=\"$i\"";
			if ( $i == $editor ) echo " selected";
			echo ">$gtd[$i]</option>";
		}
		echo "</select>";


		echo "</td></tr><tr><td>" . "" . _ANTIDOS . "</td><td><select name=\"xantidos\">";
		$gtd = array( _NO, _BINHTHUONG, _TOIDA );
		for ( $i = 0; $i < sizeof($gtd); $i++ )
		{
			echo "<option value=\"$i\"";
			if ( $i == $antidos ) echo " selected";
			echo ">$gtd[$i]</option>";
		}
		echo "</select>";

		echo "</td></tr><tr><td>" . "" . _LOCHTMLTAGS . ":</td><td><textarea wrap=\"virtual\" cols=\"70\" rows=\"2\" name=\"xsecurity_tags\">$security_tags</textarea>";

		echo "</td></tr><tr><td>" . "" . _LOCGETURL . "</td><td>";
		if ( $security_url_get == 1 )
		{
			echo "<input type=\"radio\" name=\"xsecurity_url_get\" value=\"1\" checked>" . _YES . " &nbsp;
        <input type=\"radio\" name=\"xsecurity_url_get\" value=\"0\">" . _NO . "";
		}
		else
		{
			echo "<input type=\"radio\" name=\"xsecurity_url_get\" value=\"1\">" . _YES . " &nbsp;
        <input type=\"radio\" name=\"xsecurity_url_get\" value=\"0\" checked>" . _NO . "";
		}

		echo "</td></tr><tr><td>" . "" . _LOCPOSTURL . "</td><td>";
		if ( $security_url_post == 1 )
		{
			echo "<input type=\"radio\" name=\"xsecurity_url_post\" value=\"1\" checked>" . _YES . " &nbsp;
        <input type=\"radio\" name=\"xsecurity_url_post\" value=\"0\">" . _NO . "";
		}
		else
		{
			echo "<input type=\"radio\" name=\"xsecurity_url_post\" value=\"1\">" . _YES . " &nbsp;
        <input type=\"radio\" name=\"xsecurity_url_post\" value=\"0\" checked>" . _NO . "";
		}

		echo "</td></tr><tr><td>" . "" . _LOCGETTNH . "</td><td>";
		if ( $security_union_get == 1 )
		{
			echo "<input type=\"radio\" name=\"xsecurity_union_get\" value=\"1\" checked>" . _YES . " &nbsp;
        <input type=\"radio\" name=\"xsecurity_union_get\" value=\"0\">" . _NO . "";
		}
		else
		{
			echo "<input type=\"radio\" name=\"xsecurity_union_get\" value=\"1\">" . _YES . " &nbsp;
        <input type=\"radio\" name=\"xsecurity_union_get\" value=\"0\" checked>" . _NO . "";
		}

		echo "</td></tr><tr><td>" . "" . _LOCPOSTTNH . "</td><td>";
		if ( $security_union_post == 1 )
		{
			echo "<input type=\"radio\" name=\"xsecurity_union_post\" value=\"1\" checked>" . _YES . " &nbsp;
        <input type=\"radio\" name=\"xsecurity_union_post\" value=\"0\">" . _NO . "";
		}
		else
		{
			echo "<input type=\"radio\" name=\"xsecurity_union_post\" value=\"1\">" . _YES . " &nbsp;
        <input type=\"radio\" name=\"xsecurity_union_post\" value=\"0\" checked>" . _NO . "";
		}

		echo "</td></tr><tr><td>" . "" . _LOCCOOKIES . "</td><td>";
		if ( $security_cookies == 1 )
		{
			echo "<input type=\"radio\" name=\"xsecurity_cookies\" value=\"1\" checked>" . _YES . " &nbsp;
        <input type=\"radio\" name=\"xsecurity_cookies\" value=\"0\">" . _NO . "";
		}
		else
		{
			echo "<input type=\"radio\" name=\"xsecurity_cookies\" value=\"1\">" . _YES . " &nbsp;
        <input type=\"radio\" name=\"xsecurity_cookies\" value=\"0\" checked>" . _NO . "";
		}

		echo "</td></tr><tr><td>" . "" . _LOCSESSIONS . "</td><td>";
		if ( $security_sessions == 1 )
		{
			echo "<input type=\"radio\" name=\"xsecurity_sessions\" value=\"1\" checked>" . _YES . " &nbsp;
        <input type=\"radio\" name=\"xsecurity_sessions\" value=\"0\">" . _NO . "";
		}
		else
		{
			echo "<input type=\"radio\" name=\"xsecurity_sessions\" value=\"1\">" . _YES . " &nbsp;
        <input type=\"radio\" name=\"xsecurity_sessions\" value=\"0\" checked>" . _NO . "";
		}

		echo "</td></tr><tr><td>" . "" . _LOCFILES . ":</td><td><textarea wrap=\"virtual\" cols=\"70\" rows=\"2\" name=\"xsecurity_files\">$security_files</textarea>";

		echo "</td></tr><tr><td>" . "" . _LINKPROTECTON . "</td><td>";
		if ( $protect == 1 )
		{
			echo "<input type=\"radio\" name=\"xprotect\" value=\"1\" checked>" . _YES . " &nbsp;
        <input type=\"radio\" name=\"xprotect\" value=\"0\">" . _NO . "";
		}
		else
		{
			echo "<input type=\"radio\" name=\"xprotect\" value=\"1\">" . _YES . " &nbsp;
        <input type=\"radio\" name=\"xprotect\" value=\"0\" checked>" . _NO . "";
		}

		echo "</td></tr><tr><td>" . "" . _LINKPROTECTED . ":</td><td><textarea wrap=\"virtual\" cols=\"70\" rows=\"2\" name=\"xprotected_links\">$protected_links</textarea>";

		echo "</td></tr><tr><td>" . "" . _TIMECOUNT . ":</td><td><input type=\"text\" name=\"xtimecount\" value=\"$timecount\">" . "</td></tr><tr><td>" . "" . _HOURDIFF . ":</td><td><input type=\"text\" name=\"xhourdiff\" value=\"$hourdiff\">" . "</td></tr><tr><td>" . "" . _HTG1 . ":</td><td><input type=\"text\" name=\"xhtg1\" value=\"$htg1\">" . "</td></tr><tr><td>" . "" . _HTG2 . ":</td><td><input type=\"text\" name=\"xhtg2\" value=\"$htg2\">" . "</td></tr><tr><td>" . "" . _NEWSIMGMAXWIDTHSIZE . ":</td><td><input type=\"text\" name=\"xwidth\" value=\"$width\">" . "</td></tr><tr><td>";
		$maxupload = str_replace( array('M', 'm'), '', @ini_get('upload_max_filesize') );
		echo "" . _NEWSIMGMAXSIZE . " " . $maxupload . "Mb  =  " . ( $maxupload * 1024 * 1024 ) . " byte:</td><td><input type=\"text\" name=\"xmax_size\" value=\"$max_size\">";
		echo "</td></tr><tr><td colspan=\"2\">" . "<table border=\"0\" cellpadding=\"5\" cellspacing=\"5\" width=\"100%\"><tr><td valign=\"top\">" . "" . _CLOSESITE . ":<br><br>";
		if ( $disable_site == 1 )
		{
			echo "<input type=\"radio\" name=\"xdisable_site\" value=\"1\" checked>" . _YES . " &nbsp;
        <input type=\"radio\" name=\"xdisable_site\" value=\"0\">" . _NO . "";
		}
		else
		{
			echo "<input type=\"radio\" name=\"xdisable_site\" value=\"1\">" . _YES . " &nbsp;
        <input type=\"radio\" name=\"xdisable_site\" value=\"0\" checked>" . _NO . "";
		}
		echo "<br><br>" . _CLOSESITE2 . "</td><td valign=\"top\">";
		$disable_message = str_replace( "<br />", "\r\n", html_entity_decode($disable_message) );
		echo "" . _EXPLMESSAGE . ":<br><br><textarea wrap=\"virtual\" cols=\"50\" rows=\"6\" name=\"xdisable_message\">$disable_message</textarea>" . "</td></tr></table>" . "</td></tr></table><br><br>" . "<input type=\"hidden\" name=\"op\" value=\"ConfigSave\">" . "<center><input type=\"submit\" value=\"" . _SAVECHANGES . "\"></center>" . "</form>";
		CloseTable();
		include ( "../footer.php" );

	}

	/**
	 * ConfigSave()
	 * 
	 * @return
	 */
	function ConfigSave()
	{
		global $adminfile, $datafold;
		if ( isset($_GET['op']) )
		{
			Header( "Location: ../index.php" );
			exit;
		}
		include ( "../$datafold/config.php" );
		$xsitename = FixQuotes( $_POST['xsitename'] );
		$xnukeurl = FixQuotes( $_POST['xnukeurl'] );
		$xsite_logo = FixQuotes( $_POST['xsite_logo'] );
		$xstartdate = FixQuotes( $_POST['xstartdate'] );
		$xadminmail = FixQuotes( $_POST['xadminmail'] );
		$xanonpost = intval( $_POST['xanonpost'] );
		$xDefault_Theme = FixQuotes( $_POST['xDefault_Theme'] );
		$xchangtheme = intval( $_POST['xchangtheme'] );
		$xactthemes = $_POST['xactthemes'];
		$xactthemes = implode( "|", $xactthemes );
		$xlive_cookie_time = intval( $_POST['xlive_cookie_time'] );
		$xcookie_path = FixQuotes( $_POST['xcookie_path'] );
		$xcookie_domain = FixQuotes( $_POST['xcookie_domain'] );
		$xuser_cookie = FixQuotes( $_POST['xuser_cookie'] );
		$xadmin_cookie = FixQuotes( $_POST['xadmin_cookie'] );
		$xlanguage = FixQuotes( $_POST['xlanguage'] );
		$xgfx_chk = intval( $_POST['xgfx_chk'] );
		$xmultilingual = intval( $_POST['xmultilingual'] );
		$xnotify = intval( $_POST['xnotify'] );
		$xanonymous = FixQuotes( $_POST['xanonymous'] );
		$xadmingraphic = intval( $_POST['xadmingraphic'] );
		$xminpass = intval( $_POST['xminpass'] );
		$xpollcomm = intval( $_POST['xpollcomm'] );
		$xarticlecomm = intval( $_POST['xarticlecomm'] );
		$xHome_Module = FixQuotes( $_POST['xHome_Module'] );
		$xgzip_method = intval( $_POST['xgzip_method'] );
		$xeror_value = intval( $_POST['xeror_value'] );
		$xcounteract = intval( $_POST['xcounteract'] );

		$xeditor = intval( $_POST['xeditor'] );

		$xtimecount = intval( $_POST['xtimecount'] );
		$xhourdiff = intval( $_POST['xhourdiff'] );
		$xhtg1 = FixQuotes( $_POST['xhtg1'] );
		$xhtg2 = FixQuotes( $_POST['xhtg2'] );
		$xantidos = intval( $_POST['xantidos'] );

		$xsecurity_tags = FixQuotes( $_POST['xsecurity_tags'] );
		$xsecurity_url_get = intval( $_POST['xsecurity_url_get'] );
		$xsecurity_url_post = intval( $_POST['xsecurity_url_post'] );
		$xsecurity_union_get = intval( $_POST['xsecurity_union_get'] );
		$xsecurity_union_post = intval( $_POST['xsecurity_union_post'] );
		$xsecurity_cookies = intval( $_POST['xsecurity_cookies'] );
		$xsecurity_sessions = intval( $_POST['xsecurity_sessions'] );
		$xprotect = intval( $_POST['xprotect'] );
		$xsecurity_files = FixQuotes( $_POST['xsecurity_files'] );
		$xprotected_links = FixQuotes( $_POST['xprotected_links'] );

		$xmax_size = intval( $_POST['xmax_size'] );
		$xdisable_site = intval( $_POST['xdisable_site'] );
		$xdisable_message = htmlspecialchars( nl2brStrict(stripslashes($_POST['xdisable_message'])) );
		if ( $xuser_cookie == $xadmin_cookie )
		{
			$xuser_cookie = "user_" . $xuser_cookie . "";
			$xadmin_cookie = "admin_" . $xadmin_cookie . "";
		}
		$xwidth = intval( $_POST['xwidth'] );

		@chmod( "../$datafold/config.php", 0777 );
		@$file = fopen( "../$datafold/config.php", "w" );

		$content = "<?php\n\n";
		$fctime = date( "d-m-Y H:i:s", filectime("../$datafold/config.php") );
		$fmtime = date( "d-m-Y H:i:s" );
		$content .= "// File: config.php.\n// Created: $fctime.\n// Modified: $fmtime.\n// Do not change anything in this file!\n\n";
		$content .= "if ((!defined('NV_SYSTEM')) AND (!defined('NV_ADMIN'))) {\n";
		$content .= "die('Stop!!!');\n";
		$content .= "}\n";
		$content .= "\n";
		$content .= "define(\"USER_COOKIE\",\"$xuser_cookie\");\n";
		$content .= "define(\"ADMIN_COOKIE\",\"$xadmin_cookie\");\n";
		$content .= "\$sitename = \"$xsitename\";\n";
		$content .= "\$nukeurl = \"$xnukeurl\";\n";
		$content .= "\$site_logo = \"$xsite_logo\";\n";
		$content .= "\$startdate = \"$xstartdate\";\n";
		$content .= "\$adminmail = \"$xadminmail\";\n";
		$content .= "\$anonpost = $xanonpost;\n";
		$content .= "\$Default_Theme = \"$xDefault_Theme\";\n";
		$content .= "\$changtheme = $xchangtheme;\n";
		$content .= "\$actthemes = \"$xactthemes\";\n";
		$content .= "\$live_cookie_time = $xlive_cookie_time;\n";
		$content .= "\$cookie_path = \"$xcookie_path\";\n";
		$content .= "\$cookie_domain = \"$xcookie_domain\";\n";
		$content .= "\$language = \"$xlanguage\";\n";
		$content .= "\$gfx_chk = $xgfx_chk;\n";
		$content .= "\$multilingual = $xmultilingual;\n";
		$content .= "\$notify = $xnotify;\n";
		$content .= "\$anonymous = \"$xanonymous\";\n";
		$content .= "\$admingraphic = $xadmingraphic;\n";
		$content .= "\$minpass = $xminpass;\n";
		$content .= "\$pollcomm = $xpollcomm;\n";
		$content .= "\$articlecomm = $xarticlecomm;\n";
		$content .= "\$Home_Module = \"$xHome_Module\";\n";
		$content .= "\$adminfold = \"$adminfold\";\n";
		$content .= "\$adminfile = \"$adminfile\";\n";
		$content .= "\$gzip_method = $xgzip_method;\n";
		$content .= "\$eror_value = $xeror_value;\n";
		$content .= "\$counteract = $xcounteract;\n";

		$content .= "\$editor = $xeditor;\n";

		$content .= "\$timecount = $xtimecount;\n";
		$content .= "\$hourdiff = $xhourdiff;\n";
		$content .= "\$htg1 = \"$xhtg1\";\n";
		$content .= "\$htg2 = \"$xhtg2\";\n";
		$content .= "\$antidos = $xantidos;\n";

		$content .= "\$security_tags = \"$xsecurity_tags\";\n";
		$content .= "\$security_url_get = $xsecurity_url_get;\n";
		$content .= "\$security_url_post = $xsecurity_url_post;\n";
		$content .= "\$security_union_get = $xsecurity_union_get;\n";
		$content .= "\$security_union_post = $xsecurity_union_post;\n";
		$content .= "\$security_cookies = $xsecurity_cookies;\n";
		$content .= "\$security_sessions = $xsecurity_sessions;\n";
		$content .= "\$protect = $xprotect;\n";
		$content .= "\$security_files = \"$xsecurity_files\";\n";
		$content .= "\$protected_links = \"$xprotected_links\";\n";

		$content .= "\$max_size = $xmax_size;\n";
		$content .= "\$width = $xwidth;\n";
		$content .= "\$disable_site = $xdisable_site;\n";
		$content .= "\$disable_message = \"$xdisable_message\";\n";
		$content .= "\n";
		$content .= "\$AllowableHTML = array(\"b\"=>1,
	    \"i\"=>1,
	    \"a\"=>2,
	    \"em\"=>1,
	    \"br\"=>1,
	    \"strong\"=>1,
	    \"blockquote\"=>1,
	    \"tt\"=>1,
	    \"li\"=>1,
	    \"ol\"=>1,
	    \"ul\"=>1);\n";
		$content .= "\n";
		$content .= "?>";

		@fwrite( $file, $content );
		@fclose( $file );
		@chmod( "../$datafold/config.php", 0604 );

		$yoursite = str_replace( "http://", "", $xnukeurl );
		$yoursite = str_replace( "www", "", $yoursite );
		@chmod( "../$datafold/antidos.php", 0777 );
		@$file = fopen( "../$datafold/antidos.php", "w" );
		$content = "<?php\n\n";

		$fctime = date( "d-m-Y H:i:s", filectime("../$datafold/antidos.php") );
		$fmtime = date( "d-m-Y H:i:s" );
		$content .= "// File: antidos.php.\n// Created: $fctime.\n// Modified: $fmtime.\n// Do not change anything in this file!\n\n";
		$content .= "if (!defined('NV_ANTIDOS')) {\n";
		$content .= "die('Stop!!!');\n";
		$content .= "}\n";
		$content .= "\n";
		$content .= "\$antidos = $xantidos;\n";
		$content .= "\$yoursite = \"$yoursite\";\n";
		$content .= "if (file_exists(\"images/load_bar.gif\")) {\n";
		$content .= "	\$image = \"images/load_bar.gif\";\n";
		$content .= "} elseif(file_exists(\"../images/load_bar.gif\")) {\n";
		$content .= "	\$image = \"../images/load_bar.gif\";\n";
		$content .= "}\n";
		$content .= "\$redirect_ddos = \"<html>\";\n";
		$content .= "\$redirect_ddos .= \"<head>\";\n";
		$content .= "\$redirect_ddos .= \"<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>\";\n";
		$content .= "\$redirect_ddos .= \"<title>$xsitename</title>\";\n";
		$content .= "\$redirect_ddos .= \"</head>\";\n";
		$content .= "\$redirect_ddos .= \"<body>\";\n";
		$content .= "\$redirect_ddos .= \"<br><br><center><img src=\$image width=97 height=19><br><br><b>\";\n";
		$content .= "\$redirect_ddos .= \"" . _ANTIDOS1 . " $xsitename</b><br><br>\";\n";
		$content .= "\$redirect_ddos .= \"[ <a href='\".\$_SERVER['REQUEST_URI'].\"'><b>" . _ANTIDOS2 . "</b></a> ]</center>\";\n";
		$content .= "\$redirect_ddos .= \"</body>\";\n";
		$content .= "\$redirect_ddos .= \"</html>\";\n";
		$content .= "\n";
		$content .= "function binhthuong () {\n";
		$content .= "	global \$redirect_ddos;\n";
		$content .= "	if(!\$_SERVER['HTTP_REFERER']) {\n";
		$content .= "		die (\$redirect_ddos);\n";
		$content .= "	}\n";
		$content .= "}\n";
		$content .= "\n";
		$content .= "function toida () {\n";
		$content .= "	global \$antidos, \$redirect_ddos, \$yoursite;\n";
		$content .= "	if(strpos(\$_SERVER['HTTP_REFERER'], 'http://www.'.\$yoursite) !== 0) {\n";
		$content .= "		if(strpos(\$_SERVER['HTTP_REFERER'], 'http://'.\$yoursite) !== 0) {\n";
		$content .= "			die (\$redirect_ddos);\n";
		$content .= "		}\n";
		$content .= "	}\n";
		$content .= "}\n";
		$content .= "\n";
		$content .= "if(\$antidos == 1) binhthuong ();\n";
		$content .= "elseif(\$antidos == 2) toida ();\n";
		$content .= "\n";
		$content .= "?>";

		@fwrite( $file, $content );
		@fclose( $file );
		@chmod( "../$datafold/antidos.php", 0604 );
		Header( "Location: " . $adminfile . ".php?op=Configure" );
	}


	switch ( $op )
	{

		case "Configure":
			Configure();
			break;

		case "ConfigSave":
			ConfigSave();
			break;

	}

}
else
{
	echo "Access Denied";
}

?>