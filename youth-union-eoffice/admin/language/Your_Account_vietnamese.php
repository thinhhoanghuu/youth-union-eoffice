<?php
/*
 * @Program:	NukeViet CMS
 * @File name: 	NukeViet System
 * @Version: 	2.0 RC1
 * @Date: 		01.05.2009
 * @Website: 	www.nukeviet.vn
 * @Copyright: 	(C) 2009
 * @License: 	http://opensource.org/licenses/gpl-license.php GNU Public License
 */

define("_NV_USERADMIN","Quản lý thành viên");
define("_NV_USERCONFIG","Xác lập chính");
define("_NV_USERINFORMATION","Sửa thông tin thành viên");
define("_NV_USERDELETE","Xóa thành viên");
define("_NV_USERACTIVE","Thành viên đợi kích hoạt");
define("_NV_USERFIND","Danh sách cấm");
define("_NV_TOTALUSERS","Tổng số thành viên");
define("_NV_INFOUSERS","Thông tin chung");
define("_NV_TD","Đăng ký hôm nay"); 
define("_NV_YD","Đăng ký hôm qua");
define("_NV_TOTALUSERSTEMP","Thành viên đang chờ kích hoạt"); 
define("_NV_ONLINE","Đang có mặt trên site");
define("_NV_UNAME","Tên");
define("_NV_VIEWOL","Đang xem");
define("_NV_TIMEOL","Thời gian");
define("_NV_ALLOWUSERREG","Đình chỉ việc đăng ký thành viên");
define("_NV_ALLOWUSERLOGIN","Đình chỉ việc đăng nhập của thành viên");
define("_NV_NICKMAX","Độ dài tối đa của tên đăng ký");
define("_NV_NICKMIN","Độ dài tối thiểu của tên đăng ký");
define("_NV_PASSMAX","Độ dài tối đa của mật khẩu");
define("_NV_PASSMIN","Độ dài tối thiểu của mật khẩu");
define("_NV_USEACTIVATE","Hình thức đăng ký thành viên");
define("_NV_USEACTIVATE0","Tự động kích hoạt");
define("_NV_USEACTIVATE1","Qua email");
define("_NV_USEACTIVATE2","Admin kích hoạt");
define("_NV_EXPIRING","Thời hạn kích hoạt account qua email (giờ)");
define("_NV_USERDEFAULTTHEME","Giao diện mặc định cho thành viên mới đăng ký");
define("_NV_DEFAULTTHEME","Giao diện mặc định của site");
define("_NV_USERREDIRECT","Trang chuyển tiếp khi thành viên đăng nhập");
define("_NV_USERREDIRECT0","Trang thông tin cá nhân");
define("_NV_USERREDIRECT1","Trang chủ");
define("_NV_ADMINEMAILUSER","Gửi thông báo cho thành viên khi admin tạo / kích hoạt account");
define("_NV_ADMINEMAILUSER2","Gửi thông báo cho thành viên khi admin xóa account");
define("_NV_USERREGTP","Thành phần hiển thị khi thành viên đăng ký mới");
define("_KB_UREALNAME","Hiển thị mục khai báo tên người đăng ký");
define("_KB_UREALLASTNAME","Hiển thị mục khai báo họ của người đăng ký");
define("_KB_YLOCATION","Hiển thị mục khai báo nơi ở của người đăng ký");
define("_KB_YTELEPHONE","Hiển thị mục khai báo số điện thoại của người đăng ký");
define("_KB_YICQ","Hiển thị mục khai báo số ICQ của người đăng ký");
define("_KB_YINTERESTS","Hiển thị mục khai báo sở thích của người đăng ký");
define("_KB_YSIGNATURE","Hiển thị mục khai báo thông tin ngắn gọn của người đăng ký");
define("_KB_YOURHOMEPAGE","Hiển thị mục khai báo trang web cá nhân của người đăng ký");
define("_KB_PASSWORD","Cho phép người đăng ký tự xác định mật khẩu");
define("_ASREGS","Hiển thị hướng dẫn trong bảng đăng ký");
define("_NV_USERTDINFO","Những xác lập chính khi thành viên thay đổi thông tin cá nhân");
define("_ALLOWMAILCHANGE","Cho phép thành viên thay đổi email");
define("_USERTHEME","Cho phép thành viên thay đổi giao diện");
define("_USERTHEME2","Giao diện");
define("_ERRORINVNICK","Lỗi: Tên truy cập không đúng"); 
define("_NICK2LONG","Tên truy cập quá dài");
define("_NICKADJECTIVE","Tên truy cập quá ngắn"); 
define("_NAMERESTRICTED","Lỗi: tên mà bạn chọn không được chấp nhận.");
define("_NICKNOSPACES","Lỗi: Không được có khoảng trắng trong tên truy cập"); 
define("_NICKTAKEN","Lỗi: Tên này đã có");
define("_ERRORINVEMAIL","Lỗi: Địa chỉ Email sai");
define("_ERROREMAILSPACES","Lỗi: Địa chỉ Email không được có khoảng trắng");
define("_MAILBLOCKED","Lỗi: email sau không được chấp nhận");
define("_EMAILREGISTERED","Lỗi: Địa chỉ email này đã có");
define("_EMAILNOTUSABLE","Lỗi: địa chỉ email này không được phép xử dụng");
define("_PASSLENGTH","Mật khẩu quá dài");
define("_PASSLENGTH1","Mật khẩu quá ngắn");
define("_PASSWDNOMATCH","Hai mật khẩu mà bạn khai báo khác nhau");
define("_ERRORSQL","Lỗi: Đã có lỗi khi tạo CSDL.");
define("_WELCOMETOS","Thân ái đón chào bạn trên site ");
define("_YOUUSEDEMAIL","Bạn hoặc một người nào đó đã sử dụng email");
define("_TOREGISTER","để đăng ký thành viên tại");
define("_FOLLOWINGMEM","Sau đây là thông tin về thành viên:");
define("_UNICKNAME","-Bí danh:"); 
define("_UPASSWORD","-Mật khẩu:");
define("_ACCOUNTCREATED","Account danh cho ban");
define("_RETYPEPASSWORD","Lặp lại mật khẩu");
define("_ACCOUNTCREATED2","Account mới đã được tạo");
define("_SEARCHUNAME","theo bí danh");
define("_SEARCHEMAIL","theo Email");
define("_SEARCHID","theo ID");
define("_USERSEARCH","Tìm kiếm thành viên");
define("_USERSEARCHRESULT","Kết quả tìm kiếm");
define("_NOUSERSEARCHRESULT","Không tìm thấy thành viên nào theo yêu cầu của bạn. Hãy tìm lại");
define("_USERSEARCHRESULT2","(Bạn hãy chọn một trong những thành viên dưới đây)");
define("_NOYESDELUSER","Bạn thực sự muốn xóa thành viên");
define("_NOYESDELUSER2","Bạn thực sự muốn xóa thành viên đang đợi kích hoạt account");
define("_DELUSERMAIL1","xin thông báo");
define("_DELUSERMAIL2","Account của bạn");
define("_DELUSERMAIL3","đã bị xóa khỏi site của chúng tôi. Mọi thắc mắc xin liên hệ theo email");
define("_DELUSERMAIL4","Account cua ban da bi xoa");
define("_DELUSERMAIL5","Việc đăng ký của bạn với bí danh");
define("_DELUSERMAIL6","đã không được chấp nhận. Mọi thắc mắc xin liên hệ theo email");
define("_DELUSERMAIL7","Viec dang ky khong duoc chap nhan");
define("_NV_SAVECONFIG","Cấu hình mới đã được lưu");
define("_USERTEMPUPDATE","Thành viên đang đợi kích hoạt có số ID");
define("_ACTIVUSERTEMP","Chấp nhận kích hoạt");
define("_DELUSERTEMP","Xóa");
define("_BADMAILCONFIG","Email cấm sử dụng để đăng ký thành viên");
define("_BADMAILLIST","Cấm sử dụng những Email có chứa một trong những thành phần sau đây<br><i>(Phân cách bằng dấu phẩy)</i>");
define("_BADMAIL_ADD","Thêm thành phần vào danh sách");
define("_BADMAIL_DEL","Loại thành phần khỏi danh sách");
define("_BADMAILSAVED","Lưu thay đổi");
define("_BADNICKCONFIG","Bí danh cấm sử dụng để đăng ký thành viên");
define("_BADNICKLIST","Cấm sử dụng những bí danh có chứa một trong những thành phần sau đây<br><i>(Phân cách bằng dấu phẩy)</i>");
define("_BADNICK_ADD","Thêm thành phần vào danh sách");
define("_BADNICK_DEL","Loại thành phần khỏi danh sách");
define("_BADNICKSAVED","Lưu thay đổi");
define("_SUSPENDNICKCONFIG","Thành viên bị đình chỉ truy cập");
define("_SUSPENDNICKLIST","Những thành viên sau đây đang bị đình chỉ truy cập<br><i>(Phân cách bởi dấu phẩy)</i>");
define("_SUSPENDNICK_ADD","Thêm vào danh sách (bí danh thành viên)");
define("_SUSPENDNICK_DEL","Loại khỏi danh sách");
define("_SUSPENDNICKSAVED","Lưu thay đổi");
define("_VIEWNAME","Tên hiển thị");

?>