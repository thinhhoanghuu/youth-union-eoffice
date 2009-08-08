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
 
define("_DATABASEADMIN","Database manager");
define("_NOMALBACKUP","Nomal backup");
define("_DATATOOLS","Advanced tools");
define("_DATABASE","Database");
define("_CHECK","Check");
define("_REPAIR","Repair");
define("_ANLYZE","Anlyze");
define("_FORBACKUP","Only backup");
define("_ACTIONDB","Agree");
define("_GZIPUSE","(Gzip) Format");
define("_ADDORRESTOR","Add/Restore database . Select SQL or GZIP file to process");
define("_DBHELP","<b>OPTIMIZE</b><br>should be used if you have deleted a large part of a table or if you have made many changes to a table with variable-length rows (tables that have VARCHAR, BLOB, or TEXT columns). Deleted records are maintained in a linked list and subsequent INSERT operations reuse old record positions. You can use OPTIMIZE to reclaim the unused space and to defragment the datafile.<br>
In most setups you don\'t have to run OPTIMIZE at all. Even if you do a lot of updates to variable length rows it\'s not likely that you need to do this more than once a month/week and only on certain tables.<br>
OPTIMIZE works the following way:<ul>
<li>If the table has deleted or split rows, repair the table.
<li>If the index pages are not sorted, sort them.
<li>If the statistics are not up to date (and the repair couldn\'t be done by sorting the index), update them.
</ul>Note that the table is locked during the time OPTIMIZE is running!</p>");
define("_ADDRESDB","Add/restore database");
define("_FINISHDB","Compeleted");
define("_OPTIMIZE","OPtimize");

?>