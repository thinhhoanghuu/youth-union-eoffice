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

if ( ! defined('NV_ADMIN') ) die( "Access Denied" );

switch ( $op )
{

	case "ManagerCategory":
	case "OrderCategory":
	case "YesDelCategory":
	case "subdelete":
	case "DelCategory":
	case "NoMoveCategory":
	case "EditCategory":
	case "SaveEditCategory":
	case "CstorieshomeCategory":
	case "SaveCstorieshomeCategory":
	case "AddCategory":
	case "SaveCategory":
	case "ManagerTopic":

	case "ManagerPicNews":
	case "ManagerPicNews2":

	case "ViewTopic":
	case "OutTopic":
	case "InTopic":
	case "AddTopic":
	case "EditTopic":
	case "DelTopic":
	case "SaveEditTopic":
	case "SaveTopic":
	case "Imggallery":
	case "addimg":
	case "EditImgStory":
	case "DelImgStory":
	case "SaveEditImgStory":
	case "DisplayStory":
	case "Imggallery":
	case "adminnews":
	case "PreviewAgain":
	case "PostStory":
	case "EditStory":
	case "RemoveStory":
	case "ChangeStory":
	case "DeleteStory":
	case "adminStory":
	case "PreviewAdminStory":
	case "PostAdminStory":
	case "RemoveStoryAuto":
	case "EditStoryAuto":
	case "EditStoryAutoSave":
	case "RemoveStoriesComment":
	case "EditStoriesComment":
	case "SaveEditStoriesComment":
	case "newsconfig":
	case "newsconfigsave":
	case "newsadminhome":
	case "AddTTD":
	case "Comment":
	case "Commentok":
	case "Commentche":
	case "Commentchetrue":
	case "Commentdel":

		include ( "modules/stories.php" );
		break;

}

?>
