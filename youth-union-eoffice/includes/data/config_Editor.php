<?php

// File: config_Editor.php.
// Created: 06-07-2009 17:12:20.
// Modified: 06-07-2009 17:18:04.
// Do not change anything in this file!

if ((!defined('NV_SYSTEM')) AND (!defined('NV_ADMIN'))) {
	die('Stop!!!');
}

$editorconfig = array(
	'default_theme' => 'spaw2',
	'default_toolbarset' => 'standard',
	'allow_upload' => '1',
	'allow_modify' => '1',
	'max_upload_filesize' => '0',
	'max_img_width' => '0',
	'max_img_height' => '0',
	'allowed_filetypes' => array("images","flash","documents","archives"),
	'img_dir' => 'uploads/spaw2/images/',
	'flash_dir' => 'uploads/spaw2/flash/',
	'files_dir' => 'uploads/',
	'doc_dir' => 'uploads/spaw2/doc/',
	'arch_dir' => 'uploads/spaw2/compressed/',
	'editor_pass' => '3z-5e93#e^^0b.-k!ie9d#i25,'
);

?>