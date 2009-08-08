<?php

/*
* @Program:		NukeViet CMS
* @File name: 	NukeViet System
* @Version: 	2.0 RC2
* @Date: 		07.07.2009
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

	if ( file_exists("language/Editor_" . $currentlang . ".php") )
	{
		include_once ( "language/Editor_" . $currentlang . ".php" );
	}
	if ( file_exists("../$datafold/config_Editor.php") )
	{
		include_once ( "../$datafold/config_Editor.php" );
	}

	/**
	 * EditorConfig()
	 * 
	 * @return
	 */
	function EditorConfig()
	{
		global $editorconfig, $adminfile;
		include ( '../header.php' );
		GraphicAdmin();
		OpenTable();
		$ech = "<form method=\"post\" action=\"" . $adminfile . ".php\">\n";
		$ech .= "<div align=\"center\">\n";
		$ech .= "<table border=\"0\" cellpadding=\"2\" style=\"border-collapse: collapse\" cellspacing=\"1\">\n";
		$ech .= "<tr>\n";
		$ech .= "<td colspan=\"2\">\n";
		$ech .= "<p align=\"center\"><strong>" . _EDITOR1 . "</strong></p>\n";
		$ech .= "</td>\n";
		$ech .= "</tr>\n";
		$ech .= "<tr>\n";
		$ech .= "<td colspan=\"2\">&nbsp;</td>\n";
		$ech .= "</tr>\n";
		$ech .= "<tr>\n";
		$ech .= "<td>" . _EDITOR2 . ":</td>\n";
		$ech .= "<td><select name=\"editor_default_theme\">\n";
		$editortheme = array( "spaw1", "spaw2" );
		for ( $i = 0; $i < sizeof($editortheme); $i++ )
		{
			$ech .= "<option value=\"" . $editortheme[$i] . "\"";
			if ( $editorconfig['default_theme'] == $editortheme[$i] ) $ech .= " selected";
			$ech .= ">" . $editortheme[$i] . "</option>\n";
		}
		$ech .= "</select></td>\n";
		$ech .= "</tr>\n";
		$ech .= "<tr>\n";
		$ech .= "<td>" . _EDITOR3 . ":</td>\n";
		$ech .= "<td><select name=\"editor_default_toolbarset\">\n";
		$editortoolbar = array( "standard", "all", "mini", "nv" );
		for ( $i = 0; $i < sizeof($editortoolbar); $i++ )
		{
			$ech .= "<option value=\"" . $editortoolbar[$i] . "\"";
			if ( $editorconfig['default_toolbarset'] == $editortoolbar[$i] ) $ech .= " selected";
			$ech .= ">" . $editortoolbar[$i] . "</option>\n";
		}
		$ech .= "</select></td>\n";
		$ech .= "</tr>\n";
		$ech .= "<tr>\n";
		$ech .= "<td>" . _EDITOR4 . ":</td>\n";
		$ech .= "<td>";
		if ( $editorconfig['allow_upload'] )
		{
			$ech .= "<input type=\"checkbox\" name=\"editor_allow_upload\" value=\"1\" checked>";
		}
		else
		{
			$ech .= "<input type=\"checkbox\" name=\"editor_allow_upload\" value=\"1\">";
		}
		$ech .= "</td>\n";
		$ech .= "</tr>\n";
		$ech .= "<tr>\n";
		$ech .= "<td>" . _EDITOR5 . ":</td>\n";
		$ech .= "<td>";
		if ( $editorconfig['allow_modify'] )
		{
			$ech .= "<input type=\"checkbox\" name=\"editor_allow_modify\" value=\"1\" checked>";
		}
		else
		{
			$ech .= "<input type=\"checkbox\" name=\"editor_allow_modify\" value=\"1\">";
		}
		$ech .= "</td>\n";
		$ech .= "</tr>\n";
		$ech .= "<tr>\n";
		$ech .= "<td>" . _EDITOR6 . ":</td>\n";
		$maxupload = str_replace( array('M', 'm'), '', @ini_get('upload_max_filesize') );
		$editormax_upload_filesize = ( isset($editorconfig['max_upload_filesize']) ) ? intval( $editorconfig['max_upload_filesize'] ) : ( $maxupload * 1024 * 1024 );
		$ech .= "<td><input type=\"text\" name=\"editor_max_upload_filesize\" size=\"40\" value=\"" . $editormax_upload_filesize . "\"> byte</td>\n";
		$ech .= "</tr>\n";
		$ech .= "<tr>\n";
		$ech .= "<td>" . _EDITOR7 . ":</td>\n";
		$ech .= "<td><input type=\"text\" name=\"editor_max_img_width\" size=\"40\" value=\"" . intval( $editorconfig['max_img_width'] ) . "\"> px</td>\n";
		$ech .= "</tr>\n";
		$ech .= "<tr>\n";
		$ech .= "<td>" . _EDITOR8 . ":</td>\n";
		$ech .= "<td><input type=\"text\" name=\"editor_max_img_height\" size=\"40\" value=\"" . intval( $editorconfig['max_img_height'] ) . "\"> px</td>\n";
		$ech .= "</tr>\n";
		$ech .= "<tr>\n";
		$ech .= "<td>" . _EDITOR9 . ":</td>\n";
		$ech .= "<td>\n";
		$ech .= "<table border=\"0\" cellpadding=\"2\" style=\"border-collapse: collapse\" cellspacing=\"2\">\n";
		$ech .= "<tr>\n";
		$ech .= "<td>";
		if ( $editorconfig['allowed_filetypes'] != array() && in_array("images", $editorconfig['allowed_filetypes']) )
		{
			$ech .= "<input type=\"checkbox\" name=\"editor_filetype_images\" value=\"1\" checked>";
		}
		else
		{
			$ech .= "<input type=\"checkbox\" name=\"editor_filetype_images\" value=\"1\">";
		}
		$ech .= "</td>\n";
		$ech .= "<td>images (jpg, gif, png)</td>\n";
		$ech .= "</tr>\n";
		$ech .= "<tr>\n";
		$ech .= "<td>";
		if ( $editorconfig['allowed_filetypes'] != array() && in_array("flash", $editorconfig['allowed_filetypes']) )
		{
			$ech .= "<input type=\"checkbox\" name=\"editor_filetype_flash\" value=\"1\" checked>";
		}
		else
		{
			$ech .= "<input type=\"checkbox\" name=\"editor_filetype_flash\" value=\"1\">";
		}
		$ech .= "</td>\n";
		$ech .= "<td>flash (swf)</td>\n";
		$ech .= "</tr>\n";
		$ech .= "<tr>\n";
		$ech .= "<td>";
		if ( $editorconfig['allowed_filetypes'] != array() && in_array("documents", $editorconfig['allowed_filetypes']) )
		{
			$ech .= "<input type=\"checkbox\" name=\"editor_filetype_documents\" value=\"1\" checked>";
		}
		else
		{
			$ech .= "<input type=\"checkbox\" name=\"editor_filetype_documents\" value=\"1\">";
		}
		$ech .= "</td>\n";
		$ech .= "<td>documents (doc, pdf)</td>\n";
		$ech .= "</tr>\n";
		$ech .= "<tr>\n";
		$ech .= "<td>";
		if ( $editorconfig['allowed_filetypes'] != array() && in_array("archives", $editorconfig['allowed_filetypes']) )
		{
			$ech .= "<input type=\"checkbox\" name=\"editor_filetype_archives\" value=\"1\" checked>";
		}
		else
		{
			$ech .= "<input type=\"checkbox\" name=\"editor_filetype_archives\" value=\"1\">";
		}
		$ech .= "</td>\n";
		$ech .= "<td>archives (zip, gz)</td>\n";
		$ech .= "</tr>\n";
		$ech .= "</table>\n";
		$ech .= "</td>\n";
		$ech .= "</tr>\n";
		$ech .= "<tr>\n";
		$ech .= "<td>" . _EDITOR10 . ":</td>\n";
		$ech .= "<td><input type=\"text\" name=\"editor_img_dir\" size=\"40\" value=\"" . $editorconfig['img_dir'] . "\"></td>\n";
		$ech .= "</tr>\n";
		$ech .= "<tr>\n";
		$ech .= "<td>" . _EDITOR11 . ":</td>\n";
		$ech .= "<td><input type=\"text\" name=\"editor_flash_dir\" size=\"40\" value=\"" . $editorconfig['flash_dir'] . "\"></td>\n";
		$ech .= "</tr>\n";
		$ech .= "<tr>\n";
		$ech .= "<td>" . _EDITOR111 . ":</td>\n";
		$ech .= "<td><input type=\"text\" name=\"editor_files_dir\" size=\"40\" value=\"" . $editorconfig['files_dir'] . "\"></td>\n";
		$ech .= "</tr>\n";
		$ech .= "<tr>\n";
		$ech .= "<td>" . _EDITOR12 . ":</td>\n";
		$ech .= "<td><input type=\"text\" name=\"editor_doc_dir\" size=\"40\" value=\"" . $editorconfig['doc_dir'] . "\"></td>\n";
		$ech .= "</tr>\n";
		$ech .= "<tr>\n";
		$ech .= "<td>" . _EDITOR13 . ":</td>\n";
		$ech .= "<td><input type=\"text\" name=\"editor_arch_dir\" size=\"40\" value=\"" . $editorconfig['arch_dir'] . "\"></td>\n";
		$ech .= "</tr>\n";
		$ech .= "<tr>\n";
		$ech .= "<td>" . _EDITOR14 . ":</td>\n";
		$cons = "bcdfghjklmnpqrstvwxyz";
		$vocs = "aeiou";
		$nums = "0123456789";
		$znaks = "!@#$%^&*_+-.,;:";
		for ( $x = 0; $x < 8; $x++ )
		{
			$con[$x] = substr( $cons, mt_rand(0, strlen($cons) - 1), 1 );
			$voc[$x] = substr( $vocs, mt_rand(0, strlen($vocs) - 1), 1 );
			$num[$x] = substr( $nums, mt_rand(0, strlen($nums) - 1), 1 );
			$znak[$x] = substr( $znaks, mt_rand(0, strlen($znaks) - 1), 1 );
		}
		$editor_pass = $num[4] . $con[2] . $voc[0] . $num[0] . $num[4] . $znak[1] . $voc[3] . $znak[2] . $znak[3] . $num[2] . $con[5] . $con[0] . $znak[4] . $voc[4] . $voc[3] . $num[0] . $con[6] . $znak[0] . $voc[2] . $num[3] . $num[1] . $znak[5];
		$editor_pass2 = ( $editorconfig['editor_pass'] && $editorconfig['editor_pass'] != "" ) ? $editorconfig['editor_pass'] : $editor_pass;
		$ech .= "<td><input type=\"text\" name=\"editor_pass\" size=\"40\" value=\"" . $editor_pass2 . "\"></td>\n";
		$ech .= "</tr>\n";
		$ech .= "<tr>\n";
		$ech .= "<td>&nbsp;</td>\n";
		$ech .= "<td>&nbsp;</td>\n";
		$ech .= "</tr>\n";
		$ech .= "<tr>\n";
		$ech .= "<td>&nbsp;</td>\n";
		$ech .= "<td><input type=\"hidden\" value=\"EditorConfigSave\" name=\"op\"><input type=\"submit\" value=\"" . _SAVECHANGES . "\" name=\"Submit\"></td>\n";
		$ech .= "</tr>\n";
		$ech .= "</table>\n";
		$ech .= "</div>\n";
		$ech .= "</form>\n";

		echo $ech;
		CloseTable();
		include ( "../footer.php" );
	}

	/**
	 * EditorConfigSave()
	 * 
	 * @return
	 */
	function EditorConfigSave()
	{
		global $adminfile, $datafold;
		$cons = "bcdfghjklmnpqrstvwxyz";
		$vocs = "aeiou";
		$nums = "0123456789";
		$znaks = "!@#$%^&*_+-.,;:";
		for ( $x = 0; $x < 8; $x++ )
		{
			$con[$x] = substr( $cons, mt_rand(0, strlen($cons) - 1), 1 );
			$voc[$x] = substr( $vocs, mt_rand(0, strlen($vocs) - 1), 1 );
			$num[$x] = substr( $nums, mt_rand(0, strlen($nums) - 1), 1 );
			$znak[$x] = substr( $znaks, mt_rand(0, strlen($znaks) - 1), 1 );
		}
		$editor_pass2 = $num[4] . $con[2] . $voc[0] . $num[0] . $num[4] . $znak[1] . $voc[3] . $znak[2] . $znak[3] . $num[2] . $con[5] . $con[0] . $znak[4] . $voc[4] . $voc[3] . $num[0] . $con[6] . $znak[0] . $voc[2] . $num[3] . $num[1] . $znak[5];

		$editor_default_theme = ( $_POST['editor_default_theme'] ) ? stripslashes( trim($_POST['editor_default_theme']) ) : "spaw2";
		$editor_default_toolbarset = ( $_POST['editor_default_toolbarset'] ) ? stripslashes( trim($_POST['editor_default_toolbarset']) ) : "nv";
		$editor_allow_upload = ( $_POST['editor_allow_upload'] ) ? intval( $_POST['editor_allow_upload'] ) : 0;
		$editor_allow_modify = ( $_POST['editor_allow_modify'] ) ? intval( $_POST['editor_allow_modify'] ) : 0;
		$editor_max_upload_filesize = ( $_POST['editor_max_upload_filesize'] ) ? intval( $_POST['editor_max_upload_filesize'] ) : 0;
		$editor_max_img_width = ( $_POST['editor_max_img_width'] ) ? intval( $_POST['editor_max_img_width'] ) : 0;
		$editor_max_img_height = ( $_POST['editor_max_img_height'] ) ? intval( $_POST['editor_max_img_height'] ) : 0;
		$editor_filetype_images = ( $_POST['editor_filetype_images'] ) ? intval( $_POST['editor_filetype_images'] ) : 0;
		$editor_filetype_flash = ( $_POST['editor_filetype_flash'] ) ? intval( $_POST['editor_filetype_flash'] ) : 0;
		$editor_filetype_documents = ( $_POST['editor_filetype_documents'] ) ? intval( $_POST['editor_filetype_documents'] ) : 0;
		$editor_filetype_archives = ( $_POST['editor_filetype_archives'] ) ? intval( $_POST['editor_filetype_archives'] ) : 0;
		$editor_img_dir = ( $_POST['editor_img_dir'] ) ? stripslashes( trim($_POST['editor_img_dir']) ) : "uploads/";
		$editor_flash_dir = ( $_POST['editor_flash_dir'] ) ? stripslashes( trim($_POST['editor_flash_dir']) ) : "uploads/";
		$editor_files_dir = ( $_POST['editor_files_dir'] ) ? stripslashes( trim($_POST['editor_files_dir']) ) : "uploads/";
		$editor_doc_dir = ( $_POST['editor_doc_dir'] ) ? stripslashes( trim($_POST['editor_doc_dir']) ) : "uploads/";
		$editor_arch_dir = ( $_POST['editor_arch_dir'] ) ? stripslashes( trim($_POST['editor_arch_dir']) ) : "uploads/";
		$editor_pass = ( $_POST['editor_pass'] ) ? stripslashes( trim($_POST['editor_pass']) ) : $editor_pass2;

		$arr = array( $editor_filetype_images, $editor_filetype_flash, $editor_filetype_documents, $editor_filetype_archives );
		$arr2 = array( "images", "flash", "documents", "archives" );
		$allowed_filetypes = "array(";
		for ( $i = 0; $i < 4; $i++ )
		{
			if ( $arr[$i] == 1 )
			{
				$allowed_filetypes .= ( $allowed_filetypes != "array(" ) ? ",\"" . $arr2[$i] . "\"" : "\"" . $arr2[$i] . "\"";
			}
		}
		$allowed_filetypes .= ")";
		if ( $allowed_filetypes == "array()" )
		{
			$editor_allow_upload = $editor_allow_modify = 0;
		}
		@chmod( "../$datafold/config_Editor.php", 0777 );
		@$file = fopen( "../$datafold/config_Editor.php", "w" );
		$content = "<?php\n\n";
		$fctime = date( "d-m-Y H:i:s", filectime("../$datafold/config_Editor.php") );
		$fmtime = date( "d-m-Y H:i:s" );
		$content .= "// File: config_Editor.php.\n// Created: $fctime.\n// Modified: $fmtime.\n// Do not change anything in this file!\n\n";
		$content .= "if ((!defined('NV_SYSTEM')) AND (!defined('NV_ADMIN'))) {\n";
		$content .= "\tdie('Stop!!!');\n";
		$content .= "}\n";
		$content .= "\n";
		$content .= "\$editorconfig = array(\n";
		$content .= "\t'default_theme' => '$editor_default_theme',\n";
		$content .= "\t'default_toolbarset' => '$editor_default_toolbarset',\n";
		$content .= "\t'allow_upload' => '$editor_allow_upload',\n";
		$content .= "\t'allow_modify' => '$editor_allow_modify',\n";
		$content .= "\t'max_upload_filesize' => '$editor_max_upload_filesize',\n";
		$content .= "\t'max_img_width' => '$editor_max_img_width',\n";
		$content .= "\t'max_img_height' => '$editor_max_img_height',\n";
		$content .= "\t'allowed_filetypes' => $allowed_filetypes,\n";
		$content .= "\t'img_dir' => '$editor_img_dir',\n";
		$content .= "\t'flash_dir' => '$editor_flash_dir',\n";
		$content .= "\t'files_dir' => '$editor_files_dir',\n";
		$content .= "\t'doc_dir' => '$editor_doc_dir',\n";
		$content .= "\t'arch_dir' => '$editor_arch_dir',\n";
		$content .= "\t'editor_pass' => '$editor_pass'\n";
		$content .= ");\n";
		$content .= "\n";
		$content .= "?>";

		@fwrite( $file, $content );
		@fclose( $file );
		@chmod( "../$datafold/config_Editor.php", 0604 );
		Header( "Location: " . $adminfile . ".php?op=EditorConfig" );
	}


	switch ( $op )
	{

		case "EditorConfig":
			EditorConfig();
			break;

		case "EditorConfigSave":
			EditorConfigSave();
			break;

	}

}
else
{
	echo "Access Denied";
}

?>