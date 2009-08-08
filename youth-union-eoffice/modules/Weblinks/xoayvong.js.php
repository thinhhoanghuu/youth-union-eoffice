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

if ( ! defined('NV_SYSTEM') )
{
	die( "You can't access this file directly..." );
}
// www.mangvn.org - 2009
require_once ( "mainfile.php" );
$module_name = basename( dirname(__file__) );
get_lang( $module_name );
if ( file_exists("" . $datafold . "/config_" . $module_name . ".php") )
{
	@require_once ( "" . $datafold . "/config_" . $module_name . ".php" );
}
if ( defined('_MODTITLE') ) $module_title = _MODTITLE;
##########################################

// Khai báo các thông số trình bày hình cho block Weblinks.
// Block sẽ trình bày dưới dạng 1 đa giác xoay với các mặt đa giác là các logo site.
$tocdo = 4; // Tốc độ xoay, tính bằng giây
$hinh = 8; // số mặt phẳng cạnh đa giác xoay: là số chẵn
$rong = 120; // chiều rộng, tính bằng px
$cao = 60; // chiều cao, tính bằng px
//$kieu = 1; // Kiểu trình bày. 1: dàn logo dọc; 2: dàn logo ngang
$tongso = 50; // Số banner tối đa gọi ra trong 1 lần
$source = 0; // Lọc banner từ chủ đề nào? 0: tất cả chủ đề; -1: chủ đề gốc; >0: chủ đề lựa chọn

// Hết khai báo

echo "var chieu_ngang=$rong;
var chieu_doc=$cao;
var vien_ngoai=true;
var mau_vien=\"#0000FF\";
var toc_do=$tocdo;
var gk_dieu_khien=true;
var so_mat_phang=$hinh;
hinh_anh=new Array(\n";

if ( $source == -1 )
{

	$linkcats = array();
	$homecat = 0;
	$result = $db->sql_query( "SELECT * FROM " . $prefix . "_weblinks_cats WHERE language='" . $currentlang . "' ORDER BY id" );
	while ( $row = $db->sql_fetchrow($result) )
	{
		$linkcats[intval( $row['id'] )] = array( 'id' => intval($row['id']), 'title' => stripslashes($row['title']) );
		if ( $row['ihome'] == '1' )
		{
			$homecat = intval( $row['id'] );
		}
	}
	$viewcat = intval( $_REQUEST['viewcat'] );
	if ( ! $viewcat )
	{
		$viewcat = $homecat;
	}
	if ( ! isset($linkcats[$viewcat]) )
	{
		foreach ( $linkcats as $key => $value )
		{
			$viewcat = $key;
			break;
		}
	}

	if ( $linkcats != array() )
	{
		$cid = $viewcat;
		//	$cname = $linkcats[$viewcat]['title'];

		$result2 = $db->sql_query( "SELECT * FROM " . $prefix . "_weblinks_links WHERE cid='" . $cid . "' AND active='1' AND urlimg!='' ORDER BY RAND() LIMIT " . $tongso . "" );
		$numrows2 = $db->sql_numrows( $result2 );
		if ( $numrows2 )
		{

			$i = 0;
			while ( $row2 = $db->sql_fetchrow($result2) )
			{
				$id = intval( $row2['id'] );
				$urlimg = ( $row2['urlimg'] );
				echo "\"" . $urlimg . "\",\"$nukeurl/modules.php?name=" . $module_name . "&lid=" . $id . "\",\n";
				//
				$i = $i + 1;
			}
			echo "\"$nukeurl/images/logo.gif\",\"$nukeurl\"\n";
		}
	}

} elseif ( $source == 0 )
{
	$result2 = $db->sql_query( "SELECT * FROM " . $prefix . "_weblinks_links WHERE active='1' AND urlimg!='' ORDER BY RAND() LIMIT " . $tongso . "" );
	$numrows2 = $db->sql_numrows( $result2 );
	if ( $numrows2 )
	{

		$i = 0;
		while ( $row2 = $db->sql_fetchrow($result2) )
		{
			$id = intval( $row2['id'] );
			$urlimg = ( $row2['urlimg'] );
			echo "\"" . $urlimg . "\",\"$nukeurl/modules.php?name=" . $module_name . "&lid=" . $id . "\",\n";
			//
			$i = $i + 1;
		}
		echo "\"$nukeurl/images/logo.gif\",\"$nukeurl\"\n";
	}
}
else
{

	$result2 = $db->sql_query( "SELECT * FROM " . $prefix . "_weblinks_links WHERE cid='" . $source . "' AND active='1' AND urlimg!='' ORDER BY RAND() LIMIT " . $tongso . "" );
	$numrows2 = $db->sql_numrows( $result2 );
	if ( $numrows2 )
	{

		$i = 0;
		while ( $row2 = $db->sql_fetchrow($result2) )
		{
			$id = intval( $row2['id'] );
			$urlimg = ( $row2['urlimg'] );
			echo "\"" . $urlimg . "\",\"$nukeurl/modules.php?name=" . $module_name . "&lid=" . $id . "\",\n";
			//
			$i = $i + 1;
		}
		echo "\"$nukeurl/images/logo.gif\",\"$nukeurl\"\n";
	}

}


// khai bao cac tham so trinh bay.
echo ");
hell=new Array(so_mat_phang/2+1);
cat=new Array(so_mat_phang/2);
gk=new Array(3*Math.PI/2,0,3*Math.PI/2,11*Math.PI/6,Math.PI/6,3*Math.PI/2,7*Math.PI/4,0,Math.PI/4,3*Math.PI/2,5*Math.PI/3,11*Math.PI/6,0,Math.PI/6,Math.PI/3);
var gkOf=so_mat_phang==4?0:so_mat_phang==6?2:so_mat_phang==8?5:9;
phuoc_hau=new Array(hinh_anh.length);
var goc=gk_dieu_khien?Math.PI/(so_mat_phang/2):0,gks=so_mat_phang,ngang_max,tong_so,dung=false,i,bu_trai,nua=so_mat_phang/2;
function chay_vong(){
	if(document.getElementById){
		for(i=0;i<hinh_anh.length;i+=2){
			phuoc_hau[i]=new Image();phuoc_hau[i].src=hinh_anh[i]}
		ngang_max=chieu_ngang/Math.sin(Math.PI/so_mat_phang)+nua+1;
		Car_Div=document.getElementById(\"chay_vong\");
		for(i=0;i<nua;i++){
			hell[i]=document.createElement(\"img\");Car_Div.appendChild(hell[i]);	
			hell[i].style.position=\"absolute\";
			hell[i].style.top=0+\"px\";
			hell[i].style.height=chieu_doc+\"px\";
			if(vien_ngoai){
				hell[i].style.borderStyle=\"solid\";
				hell[i].style.borderWidth=1+\"px\";
				hell[i].style.borderColor=mau_vien}
			hell[i].src=hinh_anh[2*i];
			hell[i].lnk=hinh_anh[2*i+1];
			hell[i].onclick=duong_dan;
			hell[i].onmouseover=dung_hinh;
			hell[i].onmouseout=chay_lai}
		nhung_hinh_anh()}}

function nhung_hinh_anh(){
	if(!dung){
		tong_so=0;
		for(i=0;i<nua;i++){
			cat[i]=Math.round(Math.cos(Math.abs(gk[gkOf+i]+goc))*chieu_ngang);
			tong_so+=cat[i]}
		bu_trai=(ngang_max-tong_so)/2;
		for(i=0;i<nua;i++){
			hell[i].style.left=bu_trai+\"px\";
			hell[i].style.width=cat[i]+\"px\";
			bu_trai+=cat[i]}
		goc+=toc_do/720*Math.PI*(gk_dieu_khien?-1:1);
		if((gk_dieu_khien&&goc<=0)||(!gk_dieu_khien&&goc>=Math.PI/nua)){
			if(gks==hinh_anh.length)gks=0;
			if(gk_dieu_khien){
				hell[nua]=hell[0];
				for(i=0;i<nua;i++)hell[i]=hell[i+1];
				hell[nua-1].src=hinh_anh[gks];
				hell[nua-1].lnk=hinh_anh[gks+1]}
			else{for(i=nua;i>0;i--)hell[i]=hell[i-1];
				hell[0]=hell[nua];
				hell[0].src=hinh_anh[gks];
				hell[0].lnk=hinh_anh[gks+1]}
			goc=gk_dieu_khien?Math.PI/nua:0;gks+=2}}
	setTimeout(\"nhung_hinh_anh()\",50)}
function duong_dan(){if(this.lnk)window.location.href=this.lnk}
function dung_hinh(){this.style.cursor=this.lnk?\"pointer\":\"default\";dung=true;}
function chay_lai(){dung=false}\n";

?>