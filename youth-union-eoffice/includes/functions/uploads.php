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

if ( ! defined('NV_MAINFILE') )
{
	die( 'Stop!!!' );
}

function thumbs( $outpath, $inpath, $outimg, $inimg, $insize )
{
	if ( @copy($outpath . "/" . $outimg, $inpath . "/" . $inimg) )
	{
		$f_name = end( explode(".", $inimg) );
		$f_extension = strtolower( $f_name );
		if ( $f_extension == "jpg" and extension_loaded("gd") )
		{
			$src_img = ImageCreateFromJpeg( $inpath . "/" . $inimg );
			$src_width = ImagesX( $src_img );
			$src_height = ImagesY( $src_img );
			$dest_width = $insize;
			$dest_height = $src_height / ( $src_width / $dest_width );
			$dest_img = ImageCreateTrueColor( $dest_width, $dest_height );
			ImageCopyResampled( $dest_img, $src_img, 0, 0, 0, 0, $dest_width, $dest_height, $src_width, $src_height );
			ImageJpeg( $dest_img, $inpath . "/" . $inimg, 90 );
			ImageDestroy( $dest_img );
		}
	}
}

function uploadimg( $images, $delpic, $thumb, $thumb_width, $upath )
{
	global $max_size, $width;
	if ( $delpic == "yes" )
	{
		@unlink( INCLUDE_PATH . $upath . "/" . $images );
		@unlink( INCLUDE_PATH . $upath . "/small_" . $images );
		$images = "";
	}
	if ( is_uploaded_file($_FILES['userfile']['tmp_name']) )
	{
		@unlink( INCLUDE_PATH . $upath . "/" . $images );
		@unlink( INCLUDE_PATH . $upath . "/small_" . $images );
		$images = "";
		$realname = $_FILES['userfile']['name'];
		$file_size = $_FILES['userfile']['size'];
		$file_type = $_FILES['userfile']['type'];
		$f_name = end( explode(".", $realname) );
		$f_extension = strtolower( $f_name );
		$loaiimg_ext = array( "gif", "jpg", "jpeg", "pjpeg", "png", "bmp" );
		if ( $file_size > $max_size )
		{
			info_exit( "<br><br><center>" . _EROR1 . " " . $file_size . " " . _EROR2 . " " . $max_size . " byte.<br><br>" . _GOBACK . "</center><br><br>" );
		}
		if ( ! preg_match("#image\/[x\-]*(jpg|jpeg|pjpeg|gif|png)#is", $file_type) || ! in_array($f_extension, $loaiimg_ext) )
		{
			info_exit( "<br><br><center>" . _EROR6 . "<br><br>" . _GOBACK . "</center><br><br>" );
		}
		$datakod = date( U );
		$picname = $datakod . ".nv." . $f_extension;
		if ( ! @copy($_FILES['userfile']['tmp_name'], INCLUDE_PATH . $upath . "/" . $picname) )
		{
			if ( ! move_uploaded_file($_FILES['userfile']['tmp_name'], INCLUDE_PATH . $upath . "/" . $picname) )
			{
				info_exit( "<br><br>" . _UPLOADFAILED . "<br>" );
			}
		}
		if ( file_exists(INCLUDE_PATH . $upath . "/" . $picname) )
		{
			$images = $picname;
			if ( $f_extension == "jpg" and extension_loaded("gd") )
			{
				$size = @getimagesize( INCLUDE_PATH . $upath . "/" . $images );
				$thc = 0;
				if ( $size[0] > $width )
				{
					$thc = 1;
					$sizemoi = $width;
				} elseif ( $size[0] > $thumb_width and $size[0] < ($thumb_width + 20) )
				{
					$thc = 1;
					$sizemoi = $thumb_width;
				}
				if ( $thc == 1 )
				{
					$src_img = ImageCreateFromJpeg( INCLUDE_PATH . $upath . "/" . $images );
					$src_width = ImagesX( $src_img );
					$src_height = ImagesY( $src_img );
					$dest_width = $sizemoi;
					$dest_height = $src_height / ( $src_width / $dest_width );
					$dest_img = ImageCreateTrueColor( $dest_width, $dest_height );
					ImageCopyResampled( $dest_img, $src_img, 0, 0, 0, 0, $dest_width, $dest_height, $src_width, $src_height );
					ImageJpeg( $dest_img, INCLUDE_PATH . $upath . "/" . $images, 90 );
					ImageDestroy( $dest_img );
				}
				$size = @getimagesize( INCLUDE_PATH . $upath . "/" . $images );
				if ( $thumb == 1 and $size[0] > $thumb_width )
				{
					$picname_thumb = "small_" . $picname;
					if ( ! @copy($_FILES['userfile']['tmp_name'], INCLUDE_PATH . $upath . "/" . $picname_thumb) )
					{
						@move_uploaded_file( $_FILES['userfile']['tmp_name'], INCLUDE_PATH . $upath . "/" . $picname_thumb );
					}
					if ( file_exists(INCLUDE_PATH . $upath . "/" . $picname_thumb) )
					{
						$src_img = ImageCreateFromJpeg( INCLUDE_PATH . $upath . "/" . $picname_thumb );
						$src_width = ImagesX( $src_img );
						$src_height = ImagesY( $src_img );
						$dest_width = $thumb_width;
						$dest_height = $src_height / ( $src_width / $dest_width );
						$dest_img = ImageCreateTrueColor( $dest_width, $dest_height );
						ImageCopyResampled( $dest_img, $src_img, 0, 0, 0, 0, $dest_width, $dest_height, $src_width, $src_height );
						ImageJpeg( $dest_img, INCLUDE_PATH . $upath . "/" . $picname_thumb, 90 );
						ImageDestroy( $dest_img );
					}
				}
			}
		}
	}
	return ( $images );
}

?>