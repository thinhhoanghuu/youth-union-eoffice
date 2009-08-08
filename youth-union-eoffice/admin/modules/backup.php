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

if ( ! defined('NV_ADMIN') )
{
	die( "Access Denied" );
}

if ( defined('IS_SPADMIN') )
{

	switch ( $op )
	{

		case "backup":
			@set_time_limit( 600 );
			$crlf = "\n";

			switch ( $lang )
			{
				case english:

					$strNoTablesFound = "No tables found in database.";
					$strHost = "Host";
					$strDatabase = "Database ";
					$strTableStructure = "Table structure for table";
					$strDumpingData = "Dumping data for table";
					$strError = "Error";
					$strSQLQuery = "SQL-query";
					$strMySQLSaid = "MySQL said: ";
					$strBack = "Back";
					$strFileName = "Save Database";
					$strName = "Database saved";
					$strDone = "On";
					$strat = "at";
					$strby = "by";
					$date_jour = date( "m-d-Y" );
					break;

				default:

					$strNoTablesFound = "Khong tim thay bang trong CSDL.";
					$strHost = "Host";
					$strDatabase = "CSDL ";
					$strTableStructure = "Cau truc Bang";
					$strDumpingData = "Thong so cua Bang";
					$strError = "Loi";
					$strSQLQuery = "SQL-doi hoi";
					$strMySQLSaid = "SQL-tra loi: ";
					$strBack = "Quay lai";
					$strFileName = "Sao luu CSDL";
					$strName = "Sao luu CSDL";
					$strDone = "Ngay thang:";
					$strat = "Thoi gian:";
					$strby = "Duoc thuc hien thanh cong";
					$date_jour = date( "d-m-Y" );
					break;
			}


			$filedbname = "" . $dbname . "_" . $date_jour . "";
			$filedbname = str_replace( "-", "_", $filedbname );
			$filedbname = str_replace( " ", "_", $filedbname );
			header( "Content-disposition: attachment; filename=" . $filedbname . ".sql" );
			header( "Content-type: application/octetstream" );
			header( "Pragma: no-cache" );
			header( "Expires: 0" );

			$client = $_SERVER["HTTP_USER_AGENT"];
			if ( ereg('[^(]*\((.*)\)[^)]*', $client, $regs) )
			{
				$os = $regs[1];

				if ( eregi("Win", $os) ) $crlf = "\r\n";
			}


			/**
			 * my_handler()
			 * 
			 * @param mixed $sql_insert
			 * @return
			 */
			function my_handler( $sql_insert ) {
				global $crlf;
				echo "$sql_insert;$crlf";
			}



			/**
			 * get_table_content()
			 * 
			 * @param mixed $db
			 * @param mixed $table
			 * @param mixed $handler
			 * @return
			 */
			function get_table_content( $db, $table, $handler ) {
				$result = mysql_db_query( $db, "SELECT * FROM $table" ) or mysql_die();
				$i = 0;
				while ( $row = mysql_fetch_row($result) )
				{

					$table_list = "(";

					for ( $j = 0; $j < mysql_num_fields($result); $j++ ) $table_list .= mysql_field_name( $result, $j ) . ", ";

					$table_list = substr( $table_list, 0, -2 );
					$table_list .= ")";

					if ( isset($GLOBALS["showcolumns"]) ) $schema_insert = "INSERT INTO $table $table_list VALUES (";
					else  $schema_insert = "INSERT INTO $table VALUES (";

					for ( $j = 0; $j < mysql_num_fields($result); $j++ )
					{
						if ( ! isset($row[$j]) ) $schema_insert .= " NULL,";
						elseif ( $row[$j] != "" ) $schema_insert .= " '" . addslashes( $row[$j] ) . "',";
						else  $schema_insert .= " '',";
					}
					$schema_insert = ereg_replace( ",$", "", $schema_insert );
					$schema_insert .= ")";
					$handler( trim($schema_insert) );
					$i++;
				}
				return ( true );
			}


			/**
			 * get_table_def()
			 * 
			 * @param mixed $db
			 * @param mixed $table
			 * @param mixed $crlf
			 * @return
			 */
			function get_table_def( $db, $table, $crlf ) {
				$schema_create = "";

				$schema_create .= "CREATE TABLE $table ($crlf";

				$result = mysql_db_query( $db, "SHOW FIELDS FROM $table" ) or mysql_die();
				while ( $row = mysql_fetch_array($result) )
				{
					$schema_create .= "   $row[Field] $row[Type]";

					if ( isset($row["Default"]) && (! empty($row["Default"]) || $row["Default"] == "0") ) $schema_create .= " DEFAULT '$row[Default]'";
					if ( $row["Null"] != "YES" ) $schema_create .= " NOT NULL";
					if ( $row["Extra"] != "" ) $schema_create .= " $row[Extra]";
					$schema_create .= ",$crlf";
				}
				$schema_create = ereg_replace( "," . $crlf . "$", "", $schema_create );
				$result = mysql_db_query( $db, "SHOW KEYS FROM $table" ) or mysql_die();
				while ( $row = mysql_fetch_array($result) )
				{
					$kname = $row['Key_name'];
					if ( ($kname != "PRIMARY") && ($row['Non_unique'] == 0) ) $kname = "UNIQUE|$kname";
					if ( ! isset($index[$kname]) ) $index[$kname] = array();
					$index[$kname][] = $row['Column_name'];
				}

				while ( list($x, $columns) = @each($index) )
				{
					$schema_create .= ",$crlf";
					if ( $x == "PRIMARY" ) $schema_create .= "   PRIMARY KEY (" . implode( $columns, ", " ) . ")";
					elseif ( substr($x, 0, 6) == "UNIQUE" ) $schema_create .= "   UNIQUE " . substr( $x, 7 ) . " (" . implode( $columns, ", " ) . ")";
					else  $schema_create .= "   KEY $x (" . implode( $columns, ", " ) . ")";
				}

				$schema_create .= "$crlf)";
				return ( stripslashes($schema_create) );
			}

			/**
			 * mysql_die()
			 * 
			 * @param string $error
			 * @return
			 */
			function mysql_die( $error = "" ) {
				echo "<b> $strError </b><p>";
				if ( isset($sql_query) && ! empty($sql_query) )
				{
					echo "$strSQLQuery: <pre>$sql_query</pre><p>";
				}
				if ( empty($error) ) echo $strMySQLSaid . mysql_error();
				else  echo $strMySQLSaid . $error;
				echo "<br><a href=\"javascript:history.go(-1)\">$strBack</a>";
				exit;
			}

			global $dbhost, $dbuname, $dbpass, $dbname;
			mysql_pconnect( $dbhost, $dbuname, $dbpass );
			@mysql_select_db( "$dbname" ) or die( "Unable to select database" );

			$tables = mysql_list_tables( $dbname );

			$num_tables = @mysql_numrows( $tables );
			if ( $num_tables == 0 )
			{
				echo $strNoTablesFound;
			}
			else
			{
				$i = 0;
				$heure_jour = date( "H:i" );
				print "# ========================================================$crlf";
				print "#$crlf";
				print "# $strName : $dbname$crlf";
				print "# $strDone $date_jour $strat $heure_jour $strby $name !$crlf";
				print "#$crlf";
				print "# ========================================================$crlf";
				print "$crlf";

				while ( $i < $num_tables )
				{
					$table = mysql_tablename( $tables, $i );

					print $crlf;
					print "# --------------------------------------------------------$crlf";
					print "#$crlf";
					print "# $strTableStructure '$table'$crlf";
					print "#$crlf";
					print $crlf;

					echo get_table_def( $dbname, $table, $crlf ) . ";$crlf$crlf";

					print "#$crlf";
					print "# $strDumpingData '$table'$crlf";
					print "#$crlf";
					print $crlf;

					get_table_content( $dbname, $table, "my_handler" );

					$i++;
				}
			}
			break;
	}

}
else
{
	echo "Access Denied";
}

?>