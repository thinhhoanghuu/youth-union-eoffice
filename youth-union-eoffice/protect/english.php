<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" lang="en"><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="Link Protect, Protect all link to other sites as anonymously. This script is tool protect all the links your homepage or board. Webmasters can use this tool to prevent their site from appearing in the server logs of referred pages as referrer. The operators of the referred pages cannot see where their visitors come from any more.">
<meta name="keywords" content="Joomla, Drupal, Mambo, PHP-Nuke, NukeViet, CMS, anonymous, link, url, redirect, forum, board, script, domain, external, hompage">
<title>Link Protect + Protect all link to other sites as anonymously © 2009</title>
<script type="text/javascript" src="tool.js"></script>
</head><body>
	<div id="page">
		<div id="header"></div>			
		
		<div id="content">
			<h1 align="center">Link Protect © 2009</h1>
			<div align="center" >Language: <a href="english.php">English
			<img border="0" src="../images/language/flag-english.png" width="16" height="11"></a> | 
				<a href="vietnamese.php">Tiếng Việt
			<img border="0" src="../images/language/flag-vietnamese.png" width="16" height="11"></a></div>
			<h1><font size="5">Protect all link other sites as anonymously</font></h1>
			<font face="Times New Roman" size="4">What is this?</font><p>
			<font face="Times New Roman">This script is tool protect all the links your homepage or board. 
			Webmasters can use this tool to prevent their site from appearing in 
			the server logs of referred pages as referrer. The operators of the 
			referred pages cannot see where their visitors come from any more.<br>
			Using the referrer removal service is quite easy: http://mangvn.org/protect/go.php?link=http://www.hacker.vn 
			produces an anonymous link to hacker.vn which prevents the original 
			site from appearing as a referrer in the logfiles of the referred 
			page.<br></font>Easy to use for<font face="Times New Roman"> Joomla, 
			Drupal, Mambo, PHP-Nuke or NukeViet CMS...</font></p>
			<form name="displayResult" onsubmit="return false;" action="#">
				<fieldset class="embeddingData">
					<legend><font face="Times New Roman" size="4">Copy the code</font></legend>
					To anonymize all the external links on your forum, blog or homepage using
Link Protect tool, just add this script somewhere in your html code (at the end of the body area 
					of your main template.&nbsp; If possible, directly before the 
					&lt;/body&gt;  tag). This script is auto protect your site:<br>
					<textarea class="anonym_textarea" id="embeddingCode" name="embeddingCode" cols="50" rows="6" onclick="this.focus();this.select()" readonly=""></textarea><br>
					<input class="anonym_FormSubmit" name="markAll" id="markAll" value="select all" onclick="document.displayResult.embeddingCode.select();" type="button">
				</fieldset>
			</form>							
			
			<form name="anonymizerForm" onsubmit="generateCode('anonymizerForm', 'embeddingCode'); return false;" action="#">
				<fieldset class="generationData">
					<legend><font face="Times New Roman" size="4">Exclude keywords &amp; domains</font> (optional)</legend>
					Do not anonymize the following domains / keywords (comma separated: <em>yourdomain.com, keyword1, 
					keyword2 
					...)</em><br>
					<input id="keywordsInput" name="keywords" class="anonym_input" type="text" size="66"><br>
					<input id="submitButton" value="generate" class="anonym_FormSubmit" type="submit">

				</fieldset>
			</form>
			
			<script type="text/javascript">
			   generateCode("anonymizerForm", "embeddingCode");
			</script>

			
		</div>
		
		<div id="footer">
			<div id="footer_inside">
				<p>
					
				</p>
				<div class="sp16"><hr>

			<h2 id="singleLink">Anonymize a single link</h2>
			<p>In order to produce a single anonymous link, enter the URL you want to link to and then click on "Generate URL".</p>

			<form name="theform" onsubmit="return go();" action="#">
				<fieldset>
					<font face="Times New Roman" size="4">Your URL:</font><p>
					<input class="anonym_input" maxlength="255" name="nick" value="http://" type="text">
					<input class="anonym_FormSubmit" onclick="javascript:go()" value="Generate URL" type="button"><br>
					</p>
			
					<p><font face="Times New Roman" size="4">This is the anonymous URL:</font></p>
					<textarea class="anonym_textarea" name="thelink1" wrap="soft" cols="66" rows="2" style="overflow: auto; height: 30px;" onclick="this.focus();this.select()" readonly=""></textarea><br>
					<font face="Times New Roman" size="4">
					<br>This is the anonymous URL as an HTML link:</font><p>
					<textarea class="anonym_textarea_big" name="thelink2" wrap="soft" cols="66" rows="2" style="overflow: auto; height: 30px;" onclick="this.focus();this.select()" readonly=""></textarea><br>
					</p>
					<p><font face="Times New Roman" size="4">This is the anonymous URL as a board link (works with any board software):</font></p>
					<textarea class="anonym_textarea_big" name="thelink3" wrap="soft" cols="66" rows="2" style="overflow: auto; height: 30px;" onclick="this.focus();this.select()" readonly=""></textarea><br>
			
				</fieldset>
			</form>
					<p align="center"><FONT face=Arial size=2>Powered by Link Protect © <a href="http://mangvn.org">MangVN.Org</a> 2009</FONT></div>
			</div>
		</div>		
	</div>
	
<p align="center"><font face="Arial" size="2">This page have
<img src="http://hostingtoolbox.com/bin/Count.cgi?df=linkprotect@english@<?php $_SERVER['QUERY_STRING'] ?>&dd=D&ft=0" width="54" height="13">/<img src="http://hostingtoolbox.com/bin/Count.cgi?df=linkprotect@<?php $_SERVER['QUERY_STRING'] ?>&dd=D&ft=0" width="54" height="13"> 
visitor since 21.03.2009.</font></p>
	
</body></html>