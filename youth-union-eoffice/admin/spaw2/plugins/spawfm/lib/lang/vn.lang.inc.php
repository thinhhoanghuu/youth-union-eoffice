<?php 
// ================================================
// SPAW File Manager plugin
// ================================================
// English language file
// ================================================
// Developed: Saulius Okunevicius, saulius@solmetra.com
// Copyright: Solmetra (c)2006 All rights reserved.
// ------------------------------------------------
//                                www.solmetra.com
// ================================================
// v.1.0, 2006-11-20
// ================================================

// charset to be used in dialogs
$spaw_lang_charset = 'utf-8';

// language text data array
// first dimension - block, second - exact phrase
// alternative text for toolbar buttons and title for dropdowns - 'title'

$spaw_lang_data = array(
  'spawfm' => array(
    'title' => 'SPAW Quản lý File',
    'error_reading_dir' => 'Lỗi: không thể đọc nội dung thư mục.',
    'error_upload_forbidden' => 'Lỗi: không được tải file này vào thư mục hiện tại.',
    'error_upload_file_too_big' => 'Quá trình tải lên thất bại: dung lượng file quá lớn.',
    'error_upload_failed' => 'Quá trình tải lên thất bại.',
    'error_upload_file_incomplete' => 'Quá trình tải lên chưa hoàn thành, vui lòng thử lại.',
    'error_bad_filetype' => 'Lỗi: thể loại file không được cho phép.',
    'error_max_filesize' => 'Dung lượng file tối đa cho phép:',
    'error_delete_forbidden' => 'Lỗi: không được phép xoá file trong thư mục này.',
    'confirm_delete' => 'Bạn chắc chắn muốn xoá file "[*file*]"?',
    'error_delete_failed' => 'Lỗi: không thể xoá file, có thể bạn không đủ quyền.',
    'error_no_directory_available' => 'Không cho phép duyệt thư mục.',
    'download_file' => '[tải về]',
    'error_chmod_uploaded_file' => 'Việc tải file lên đã thành công, nhưng không thể thực hiện CHMOD.',
    'error_img_width_max' => 'Chiều rộng tối đa cho phép của hình: [*MAXWIDTH*]px',
    'error_img_height_max' => 'Chiều cao tối đa cho phép của hình: [*MAXHEIGHT*]px',
    'rename_text' => 'Nhập tên mới cho file "[*FILE*]":',
    'error_rename_file_missing' => 'Đổi tên thất bại - không tìm thấy file.',
    'error_rename_directories_forbidden' => 'Lỗi: không thể đổi tên cho thư mục này.',
    'error_rename_forbidden' => 'Lỗi: không thể đổi tên các files trong thư mục này.',
    'error_rename_file_exists' => 'Lỗi: đã tồn tại file "[*FILE*]".',
    'error_rename_failed' => 'Lỗi: đổi tên thất bại. Có thể bạn không có đủ quyền.',
    'error_rename_extension_changed' => 'Lỗi: phần mở rộng của file không được phép thay đổi!',
    'newdirectory_text' => 'Nhập tên cho thư mục:',
    'error_create_directories_forbidden' => 'Lỗi: bị cấm tạo thư mục mới.',
    'error_create_directories_name_used' => 'Tên này đã được sử dụng, vui lòng chọn tên khác.',
    'error_create_directories_failed' => 'Lỗi: không thể tạo thư mục. Có thể bạn không có đủ quyền.',
    'error_create_directories_name_invalid' => 'Những ký tự sau không được sử dụng trong tên thư mục: / \\ : * ? " < > |',
    'confirmdeletedir_text' => 'Bạn chắc chắn muốn xoá thư mục "[*DIR*]"?',
    'error_delete_subdirectories_forbidden' => 'Việc xoá thư mục đã bị cấm.',
    'error_delete_subdirectories_failed' => 'Không thể xoá thư mục. Có thể bạn không có đủ quyền.',
    'error_delete_subdirectories_not_empty' => 'Thư mục có chứa dữ liệu.',
    'createthumb_text' => 'Nhập chiều rộng mới cho hình:',
  ),
  'buttons' => array(
    'ok'        => '  OK  ',
    'cancel'    => 'Huỷ bỏ',
    'view_list' => 'Chế độ: danh sách',
    'view_details' => 'Chế độ: thông số',
    'view_thumbs' => 'Chế độ: hình mẫu',
    'rename'    => 'Đổi tên...',
    'thumbnail' => 'Tạo hình mẫu',
    'delete'    => 'Xoá',
    'go_up'     => 'Lên trên',
    'upload'    =>  'Tải lên',
    'create_directory'  =>  'Thư mục mới...',
  ),
  'file_details' => array(
    'name'  =>  'Tên',
    'type'  =>  'Loại',
    'size'  => 'Dung lượng',
    'date'  =>  'Cập nhật',
    'filetype_suffix'  =>  'file',
    'img_dimensions'  =>  'Thông số',
    'file_folder'  =>  'Thư mục',
  ),
  'filetypes' => array(
    'any'       => 'Tất cả',
    'images'    => 'Hình ảnh',
    'flash'     => 'Phim Flash',
    'documents' => 'Tài liệu',
    'audio'     => 'Âm thanh',
    'video'     => 'Phim ảnh',
    'archives'  => 'Tệp nén',
    '.jpg'  =>  'Hình JPG',
    '.jpeg'  =>  'Hình JPEG',
    '.gif'  =>  'Hình GIF',
    '.png'  =>  'Hình PNG',
    '.swf'  =>  'Phim Flash',
    '.doc'  =>  'Tài liệu Word',
    '.xls'  =>  'Tài liệu Excel',
    '.pdf'  =>  'Tài liệu PDF',
    '.rtf'  =>  'Tài liệu RTF',
    '.odt'  =>  'OpenDocument Text',
    '.ods'  =>  'OpenDocument Spreadsheet',
    '.sxw'  =>  'OpenOffice.org 1.0 Text Document',
    '.sxc'  =>  'OpenOffice.org 1.0 Spreadsheet',
    '.wav'  =>  'WAV audio file',
    '.mp3'  =>  'MP3 audio file',
    '.ogg'  =>  'Ogg Vorbis audio file',
    '.wma'  =>  'Windows audio file',
    '.avi'  =>  'AVI video file',
    '.mpg'  =>  'MPEG video file',
    '.mpeg'  =>  'MPEG video file',
    '.mov'  =>  'QuickTime video file',
    '.wmv'  =>  'Windows video file',
    '.zip'  =>  'ZIP archive',
    '.rar'  =>  'RAR archive',
    '.gz'  =>  'gzip archive',
    '.txt'  =>  'Text Document',
    ''  =>  '',
  ),
);
?>
