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
 
define("_DATABASEADMIN","Quản lý dữ liệu");
define("_NOMALBACKUP","Lưu bình thường");
define("_DATATOOLS","Tùy chỉnh nâng cao");
define("_DATABASE","Tùy chỉnh dữ liệu");
define("_CHECK","Kiểm tra");
define("_REPAIR","Sửa chữa");
define("_ANLYZE","Cập nhật");
define("_FORBACKUP","Chỉ dành cho việc dự phòng dữ liệu");
define("_ACTIONDB","Thực hiện");
define("_GZIPUSE","Nén dữ liệu lại (Gzip)");
define("_ADDORRESTOR","Thêm/Phục hồi dữ liệu lên CSDL. Hãy lựa chọn tệp GZIP hoặc SQL để thực hiện");
define("_DBHELP","<p align=center><b>Những lưu ý khi sử dụng</b></p><p align=justify>&bull;&nbsp;Đây là công cụ cho phép bạn tùy chỉnh dữ liệu ngay trên hệ thống với các thao tác như: tối ưu, sửa chữa, cập nhật, dự phòng, phục hồi, kiểm tra trạng thái...<br>&bull;&nbsp;Bạn có thể tối ưu, phục hồi, sao lưu, kiểm tra hay sửa chữa 1 hoặc nhiều table (Giữ phím Ctrl và nháy chuột vào các bảng cần chọn). Nếu không chọn bảng nào thì các chức năng sẽ áp dụng cho toàn bộ dữ liệu tức tất cả các bảng có trên cơ sở dữ liệu.<br>&bull;&nbsp;Chức năng nén và xóa những dữ liệu thừa chỉ áp dụng cho việc lưu dự phòng dữ liệu.</p><p align=center><b>Một số lưu ý khác</b></p><p align=justify><b>Tối ưu</b>: Khi CSDL của bạn hoạt động được một thời gian thì sẽ xuất hiện những khối dữ liệu thừa do việc nhập xuất không thành công của người dùng, làm dung lượng lớn hơn. Tối ưu dữ liệu sẽ giúp bạn xóa bỏ khối dữ liệu thừa, tối ưu lại hoạt động của dữ liệu.<br>&bull;&nbsp;Sửa chữa kiểm tra xem trạng thái và cập nhật là các chức năng theo đúng nghĩa của nó. Bạn có thể sửa chữa cập nhật và xem trạng thái hoạt động của các bảng dữ liệu một cách nhanh chóng. <br>&bull;&nbsp;Việc bạn thêm bảng mới vào CSDL hết sức đơn giản và nên đối chiếu lại các prefix trước khi thêm . Chọn tệp sql hoặc tệp nén gzip sau đó chọn thực hiện. <br><br>&bull;&nbsp; <font color=#FF0000><b>Cảnh báo</b></font> : Bạn phải cẩn thận khi phục hồi dữ liệu. Tất cả thông tin về dữ liệu phải chính xác nếu không site của bạn sẽ không kết nối được dữ liệu ngay lập tức.</p>");
define("_ADDRESDB","Thêm hoặc phục hồi dữ liệu");
define("_FINISHDB","Xử lý thành công");
define("_OPTIMIZE","Tối ưu cơ sở dữ liệu");

?>