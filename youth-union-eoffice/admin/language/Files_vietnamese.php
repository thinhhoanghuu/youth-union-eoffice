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

define("_FILESADMIN","Quản lý mục downloads");
define("_FILESCONFIG","Xác lập chính");
define("_FILESMANAGERCAT","Quản lý chủ đề");
define("_FILESMANAGER","Quản lý file");
define("_FILESETING","Xác lập cấu hình chính cho module Files");
define("_FHOMENOTE","Hiển thị thông báo trên trang chính của module");
define("_SHOWSUBCAT","Hiển thị thư mục con trong mỗi thư mục chính");
define("_TABCOLUMN","Số cột thư mục ");
define("_FILESPERPAGE","Số file trên một trang");
define("_DOWNLOAD","Ai được tải file");
define("_FCHECKNUM","Hiển thị mã kiểm tra khi tải file");
define("_ADDCOMMENTS","Thêm bình luận cho file");
define("_BROKELINK","Thông báo về liên kết hỏng ");
define("_FVOTES","Chức năng bình chọn file");
define("_ADDFILES","Chức năng thêm file vào CSDL");
define("_UPLOADFILES","Chức năng tải file lên server host site bạn");
define("_IFUPLOADFILES","Nếu cho upload file thì");
define("_MAXFILESIZE","Dung lượng tối đa được tải lên(byte)");
define("_FILEADMINDIR","Thư mục chứa các file khi người quản trị thêm file");
define("_FILEUSERDIR","Thư mục tạm thời chứa các file do người dùng gửi tới ");
define("_FHOMEMSG","Nội dung thông báo trên trang chính module");
define("_MEMBER","Chỉ có thành viên");
define("_ORDER","Đặt hàng");
define("_LISTFILEADDED","Các file đã đăng");
define("_MOVEFILESCAT","Di chuyển danh mục file");
define("_ADDNEWFILES","Những file mới gửi tới");
define("_ADDBROCFILES","Thông báo về liên kết hỏng");
define("_SUBTITLE","Miêu tả");
define("_ADDSUBCATEGORY","Thêm tiểu mục");
define("_INCAT","Vào mục");
define("_ADDFILE","Thêm file");
define("_HOMEFILES","Trang chính");
define("_FILEAUTOR","Tên tác giả");
define("_FAUEMAIL","Email tác giả");
define("_FAUURL","Trang cá nhân");
define("_FILE","Tải file lên server");
define("_FILELINK","Đường dẫn trực tiếp đến file");
define("_FILEVERSION","Phiên bản");
define("_SIZENOTE","Chỉ trong trường hợp đưa vào liên kết file.");
define("_EDITFCAT","Sửa tên mục");
define("_DELFCAT","Xóa mục");
define("_DELFCATEGORY","Xóa mục");
define("_DELFCATNOTE","Bạn thực sự muốn xóa mục trên? Nên nhớ rằng tất cả tiểu mục, các file, bài bình luận trong nó đều bị xóa theo.");
define("_FILEEXIST","<b>LỖI!</b><br>File với tên này đã có trên site!");
define("_UPLOADEROR","<b>LỖI!</b><br>Vì một nguyên nhân nào đó mà hệ thống không thể tiếp nhận file này. Hãy kiểm tra xem bạn đã CHMOD đúng cho thư mục chứa file chưa.");
define("_UPLOADEROR2","<b>LỖI!</b><br>Bạn chưa chỉ ra file muốn đưa vào danh mục downloads.");
define("_FILEEDIT","Sửa file");
define("_TESTLINK","Kiểm tra");
define("_SAVEFILEED","Lưu thay đổi");
define("_DELFILES","Xóa file");
define("_DELFILE","File đã được xóa");
define("_DELFILENOTE","Bạn thực sự muốn xóa file này? Cần biết là tất cả bài bình luận của nó cũng bị xóa theo.");
define("_NONEWFILES","Không có file mới gửi đến.");
define("_NOBROCFILES","Không có thông báo liên kết file hỏng.");
define("_IPSENDER","IP người gửi");
define("_BANIPSENDER","Đình chỉ truy cập từ IP này");
define("_DETFILES","Chi tiết");
define("_LINKSSTATUS","Kiểm tra");
define("_EDITFILE","Sửa");
define("_DELITFILE","Xóa");
define("_IGNORE","Bỏ qua");
define("_FADMINST","Lựu chọn");
define("_MOVEFCAT","Chuyển file");
define("_FROMCATEGORY","Từ mục");
define("_INCATEGORY","Đến mục");
define("_MOVEFCATS","Chuyển file");
define("_FDELETE","Xóa file");
define("_DELFILESCOM","Xóa bình luận của file");
define("_DELFILEC","Chú ý!");
define("_DELFILECOMNOTE","Bạn thực sự muốn xóa bài bình luận này?");
define("_FILESMIME","Những loại files thành viên được tải lên<br>(<i>Không nên thêm thành phần</>)");
// Add News on Modules Files - www.mangvn.org - 09-2008
define("_FINEWS","Hiển thị Catalogue News trên trang chính Module Files");
define("_FINEWS1","Số bản tin hiển thị");
define("_FINEWS0","Không hiển thị");
// cat ngan phan mo ta cho file by www.mangvn.org 06-01-2009
define("_CATNGAN","Có thu gọn đoạn mô tả file khi trình bày thư mục không?");
define("_CATNGAN0","Không");
define("_CATNGAN1","Số ký tự bạn muốn thu gọn");
define("_CATNGANYES","Có");
//
define("_TTT","Sử dụng link Download trực tiếp?");
define("_TTTHD","Chọn 'Có' nếu host của bạn gặp vấn đề về file download (ví dụ file download về có dung lượng nhỏ hơn 1kb)");
define("_RENAMEFILE","Tùy chọn đổi tên file<br>(cho trường hợp không download trực tiếp)");
define("_PREFILENAME","Thêm vào phía trước tên file");
define("_AUTOFILENAME","Tự động đổi tên cho file");

?>