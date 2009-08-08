<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" lang="en"><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="Link Protect, Protect all link to other sites as anonymously. This script is tool protect all the links your homepage or board. Webmasters can use this tool to prevent their site from appearing in the server logs of referred pages as referrer. The operators of the referred pages cannot see where their visitors come from any more.">
<meta name="keywords" content="Joomla, Drupal, Mambo, PHP-Nuke, NukeViet, CMS, anonymous, link, url, redirect, forum, board, script, domain, external, hompage">
<title>Link Protect + Bảo mật cho liên kết của bạn © 2009</title>
<script type="text/javascript" src="tool.js"></script>
</head><body>
	<div id="page">
		<div id="header"></div>			
		
		<div id="content">
			<h1 align="center">Link Protect © 2009</h1>
			<div align="center">Language: <a href="english.php">English
			<img border="0" src="../images/language/flag-english.png" width="16" height="11"></a> | 
			<a href="vietnamese.php">Tiếng Việt 
			<img border="0" src="../images/language/flag-vietnamese.png" width="16" height="11"></a></div>
			<h1><font size="5">Link Protect - Bảo vệ cho tất cả các liên kết từ site của bạn</font></h1>
			<font face="Times New Roman" size="4">&#39;Link Protect&#39; là gì?</font><p>
			<font face="Times New Roman">Link Protect là tiện ích trực tuyến 
			giúp bảo vệ trang web của bạn khỏi sự nhòm ngó của người khác. Bằng 
			cách che giấu thông tin referred nhờ liên kết chuyển tiếp ngầm có 
			dạng : http://mangvn.org/protect/go.php?link=http://www.hacker.vn để 
			link tới hacker.vn thay vì các liên kết trực tiếp truyền thống. 
			Website của bạn sẽ được bảo vệ an toàn và hoàn toàn vô hình trước 
			con mắt của chủ trang hacker.vn. Chúng tôi gọi đó là &quot;Liên kết vô 
			danh&quot;.<br>
			Công cụ này d</font>ễ dàng sử dụng cho<font face="Times New Roman"> Joomla, 
			Drupal, Mambo, PHP-Nuke hay NukeViet CMS... chỉ với một lần chèn duy 
			nhất vào trang header hoặc footer của hệ thống, quá trình bảo vệ 
			hoàn toàn tự động. Kể cả sau này bạn có muốn bỏ đi cũng không hề ảnh 
			hưởng gì đến trang Web của bạn cả.</font></p>
			<p>
			<font face="Times New Roman" size="4">Tại sao phải sử dụng &#39;Link Protect&#39;?</font></p>
			<p>
			Nếu bạn click vào một link để đến 1 trang khác, chủ site đó có thể 
			xác định bạn đến site của họ từ đâu một cách dễ dàng bằng cách kiểm 
			tra Referer dễ dàng từ <font face="Times New Roman">server logs</font>. 
			Đôi khi những thông tin này vô tình cung cấp cho hacker những lỗ 
			hổng để họ tấn công vào Web của bạn. Sử dụng dịch vụ che giấu 
			Referer là 1 cách hiệu quả chống lại nguy cơ này.
			<font face="Times New Roman">&nbsp;Quản trị viên từ các trang Web 
			kia sẽ không thể biết bạn đến từ đâu.</font></p>
			<form name="displayResult" onsubmit="return false;" action="#">
				<fieldset class="embeddingData">
					<legend><font face="Times New Roman" size="4">Khởi tạo đoạn 
					mã bảo vệ tự động</font></legend>
					Copi đoạn mã dưới đây và đặt vào trang Web của bạn (trước 
					thẻ 
					&lt;/body&gt;  trong mã HTML). Tất cả các liên kết trên 
					trang của bạn sẽ được bảo vệ tự động:<br>
					<textarea class="anonym_textarea" id="embeddingCode" name="embeddingCode" cols="50" rows="6" onclick="this.focus();this.select()" readonly=""></textarea><br>
					<input class="anonym_FormSubmit" name="markAll" id="markAll" value="Chọn tất cả" onclick="document.displayResult.embeddingCode.select();" type="button">
				</fieldset>
			</form>							
			
			<form name="anonymizerForm" onsubmit="generateCode('anonymizerForm', 'embeddingCode'); return false;" action="#">
				<fieldset class="generationData">
					<legend><font face="Times New Roman" size="4">Loại bỏ các từ 
					khóa &amp; domains</font> (tùy chọn)</legend>
					Nếu liên kết là các tên miền hoặc có các từ khóa sau thì 
					tính năng bảo vệ sẽ được tự động tắt (phân cách bằng dấu 
					phẩy, ví dụ: <em>yourdomain.com, keyword1, 
					keyword2 
					...)</em><br>
					<input id="keywordsInput" name="keywords" class="anonym_input" type="text" size="66"><br>
					<input id="submitButton" value="Khởi tạo" class="anonym_FormSubmit" type="submit">

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

			<h2 id="singleLink">Bảo vệ liên kết đến 1 trang cụ thể</h2>
			<p>Nếu bạn không muốn bảo vệ một các tự động đối với toàn bộ các 
			liên kết như kiểu trên mà chỉ cần bảo vệ 1 link cụ thể, hãy sử dụng 
			công cụ dưới đây. Nhập địa chỉ trang web muốn tạo link bảo vệ và 
			nhấp nút "Tạo URL".</p>

			<form name="theform" onsubmit="return go();" action="#">
				<fieldset>
					<font face="Times New Roman" size="4">Địa chỉ trang web:</font><p>
					<input class="anonym_input" maxlength="255" name="nick" value="http://" type="text">
					<input class="anonym_FormSubmit" onclick="javascript:go()" value="Tạo URL" type="button"><br>
					</p>
			
					<p><font face="Times New Roman" size="4">Liên kết này sẽ 
					giúp bạn trở nên vô hình:</font></p>
					<textarea class="anonym_textarea" name="thelink1" wrap="soft" cols="66" rows="2" style="overflow: auto; height: 30px;" onclick="this.focus();this.select()" readonly=""></textarea><br>
					<font face="Times New Roman" size="4">
					<br>Đoạn mã html trình bày liên kết (đã được bảo vệ):</font><p>
					<textarea class="anonym_textarea_big" name="thelink2" wrap="soft" cols="66" rows="2" style="overflow: auto; height: 30px;" onclick="this.focus();this.select()" readonly=""></textarea><br>
					</p>
					<p><font face="Times New Roman" size="4">Đoạn mã sử dụng cho 
					Forum (tương thích với các dạng forum sử dụng BBcode):</font></p>
					<textarea class="anonym_textarea_big" name="thelink3" wrap="soft" cols="66" rows="2" style="overflow: auto; height: 30px;" onclick="this.focus();this.select()" readonly=""></textarea><br>
			
				</fieldset>
			</form>
					<p align="center"><FONT face=Arial size=2>Powered by Link Protect © <a href="http://mangvn.org">MangVN.Org</a> 2009</FONT></div>
			</div>
		</div>		
	</div>
	
<p align="center"><font face="Arial" size="2">This page have
<img src="http://hostingtoolbox.com/bin/Count.cgi?df=linkprotect@vietnamese@<?php $_SERVER['QUERY_STRING'] ?>&dd=D&ft=0" width="54" height="13">/<img src="http://hostingtoolbox.com/bin/Count.cgi?df=linkprotect@<?php $_SERVER['QUERY_STRING'] ?>&dd=D&ft=0" width="54" height="13"> 
visitor since 21.03.2009.</font></p>
	
</body></html>