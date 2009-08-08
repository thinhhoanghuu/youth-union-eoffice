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
	$checkmodname = "Data";

	if ( file_exists("language/" . $checkmodname . "_" . $currentlang . ".php") )
	{
		include_once ( "language/" . $checkmodname . "_" . $currentlang . ".php" );
	}

	/**
	 * Datamenu()
	 * 
	 * @return
	 */
	function Datamenu()
	{
		global $adminfile;
		OpenTable();
		echo "<center><b><a href=\"" . $adminfile . ".php?op=DataAdmin\"> " . _DATABASEADMIN . "</b></a><center>";
		echo "<br>";
		echo "<center> [ <a href=\"" . $adminfile . ".php?op=backup\"> " . _NOMALBACKUP . "</a> | <a href=\"" . $adminfile . ".php?op=OptimizeDB\"> " . _OPTIMIZE . "</a> | <a href=\"" . $adminfile . ".php?op=database\"> " . _DATATOOLS . "</a>]</center>";
		CloseTable();
		echo "<br>";
	}

	/**
	 * AfterAction()
	 * 
	 * @return
	 */
	function AfterAction()
	{
		OpenTable();
		echo "<center><b>" . _FINISHDB . "</b></center>";
		CloseTable();
	}

	/**
	 * DataAdmin()
	 * 
	 * @return
	 */
	function DataAdmin()
	{
		include ( "../header.php" );
		GraphicAdmin();
		Datamenu();
		include ( "../footer.php" );
	}

	switch ( $op )
	{

		case "BackupDB":
			@set_time_limit( 600 );
			$crlf = "\n";

			$strNoTablesFound = "No tables found in database.";
			$strDatabase = "Database ";
			$strTableStructure = "Table structure for table";
			$strDumpingData = "Dumping data for table";
			$strError = "Error";
			$strSQLQuery = "SQL-query";
			$strMySQLSaid = "MySQL said: ";
			$strBack = "Back";
			$strDone = "On";
			$strat = "at";
			$strby = "by";
			$date_jour = date( "m-d-Y" );

			$filename = $dbname . "_" . $date_jour . ".sql";
			global $gzip;
			$do_gzip_compress = ( $gzip == 1 );
			if ( $do_gzip_compress )
			{
				@ob_start();
				@ob_implicit_flush( 0 );
				header( "Content-Type: application/x-gzip; name=\"$filename.gz\"" );
				header( "Content-disposition: attachment; filename=$filename.gz" );
			}
			else
			{
				header( "Content-Type: text/x-delimtext; name=\"$filename\"" );
				header( "Content-disposition: attachment; filename=$filename" );
			}

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
				global $drop;
				if ( $drop == 1 )
				{
					$schema_create .= "DROP TABLE IF EXISTS $table;$crlf";
				}
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
					if ( $row['Index_type'] == "FULLTEXT" ) $kname = "FULLTEXT|$kname";
					if ( ! isset($index[$kname]) ) $index[$kname] = array();
					$index[$kname][] = $row['Column_name'];
				}

				while ( list($x, $columns) = @each($index) )
				{
					$schema_create .= ",$crlf";
					if ( $x == "PRIMARY" ) $schema_create .= "   PRIMARY KEY (" . implode( $columns, ", " ) . ")";
					elseif ( substr($x, 0, 6) == "UNIQUE" ) $schema_create .= "   UNIQUE " . substr( $x, 7 ) . " (" . implode( $columns, ", " ) . ")";
					elseif ( substr($x, 0, 8) == "FULLTEXT" ) $schema_create .= "   FULLTEXT " . substr( $x, 9 ) . " (" . implode( $columns, ", " ) . ")";
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

			global $dbhost, $dbuname, $dbpass, $dbname, $tablelist;
			mysql_pconnect( $dbhost, $dbuname, $dbpass );
			@mysql_select_db( "$dbname" ) or die( "Unable to select database" );

			if ( is_array($tablelist) && count($tablelist) > 0 )
			{
				$tables = $tablelist;
				$num_tables = count( $tablelist );
			}
			else
			{
				$tablelist = mysql_list_tables( $dbname );
				$num_tables = @mysql_numrows( $tablelist );
				if ( $num_tables > 0 )
					for ( $i = 0; $i < $num_tables; $i++ )
					{
						$tables[] = mysql_tablename( $tablelist, $i );
					}
			}

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
				print "# $strDatabase : $dbname$crlf";
				print "# $strDone $date_jour $strat $heure_jour $strby $name !$crlf";
				print "#$crlf";
				print "# ========================================================$crlf";
				print "$crlf";

				foreach ( $tables as $table )
				{
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
				}
			}

			exit;
			break;

		case "database":
			include ( "../header.php" );
			GraphicAdmin();
			Datamenu();

			OpenTable();
			echo '<table><tr>';
			echo "<td><form method=\"post\" name=\"backup\" action=\"" . $adminfile . ".php\"><SELECT NAME=\"tablelist[]\" size=\"20\" multiple>";
			$tables = mysql_list_tables( $dbname );
			for ( $i = 0; $i < mysql_num_rows($tables); $i++ )
			{
				$table = mysql_tablename( $tables, $i );
				echo "<OPTION VALUE=\"$table\">$table</OPTION>";
			}
			@mysql_free_result( $result );
			echo "</SELECT><br><br>";
			echo '<b>' . _FUNCTIONS . ':</b><br><SELECT NAME="op"></br>' . '<OPTION VALUE="BackupDB">' . _SAVEDATABASE . '</OPTION>' . '<OPTION VALUE="OptimizeDB">' . _OPTIMIZE . '</OPTION>' . '<OPTION VALUE="CheckDB">' . _CHECK . '</OPTION>' . '<OPTION VALUE="AnalyzeDB">' . _ANLYZE . '</OPTION>' . '<OPTION VALUE="RepairDB">' . _REPAIR . '</OPTION>' . '<OPTION VALUE="StatusDB">' . _STATUS . '</OPTION>' . '</SELECT>';
			echo '<br><br>' . _FORBACKUP . ':<br></br><input type="checkbox" value="1" NAME="drop">&nbsp;Include drop statement<br>' . '<br><input type="checkbox" value="1" NAME="gzip">&nbsp;' . _GZIPUSE . '<br><br>';
			echo '<input type="submit" value="' . _ACTIONDB . '"></form></td><td valign="top" width="100%">';
			OpenTable();
			echo '' . _DBHELP . '';
			CloseTable();
			echo "<br>";
			OpenTable();
			echo "<center><form ENCTYPE=\"multipart/form-data\" method=\"post\" action=\"" . $adminfile . ".php\" name=\"restore\">";
			echo "<b>" . _ADDRESDB . "</b><br></br>" . _ADDORRESTOR . "<br>";
			echo "<input type=\"file\" name=\"sqlfile\" size=70>&nbsp;&nbsp;<input type=\"hidden\" name=\"op\" value=\"RestoreDB\"><input type=\"submit\" value=\"" . _ACTIONDB . "\">";
			echo "</form></center>";
			CloseTable();
			echo '</td></tr>';
			echo '</table>';
			CloseTable();
			include ( "../footer.php" );
			break;

		case "OptimizeDB":
		case "CheckDB":
		case "AnalyzeDB":
		case "RepairDB":
		case "StatusDB":
			$type = strtoupper( substr($op, 0, -2) );
			include ( "../header.php" );
			GraphicAdmin();
			Datamenu();
			OpenTable();
			echo "<center><font class=\"title\"><b>$type " . _DATABASE . "</b></font></center>";
			CloseTable();
			echo "<br>";
			OpenTable();
			global $dbhost, $dbuname, $dbpass, $dbname, $tablelist;
			mysql_pconnect( $dbhost, $dbuname, $dbpass );
			@mysql_select_db( "$dbname" ) or die( "Unable to select database" );

			if ( is_array($tablelist) && count($tablelist) > 0 )
			{
				$tables = $tablelist;
				$num_tables = count( $tablelist );
			}
			else
			{
				$tablelist = mysql_list_tables( $dbname );
				$num_tables = @mysql_numrows( $tablelist );
				if ( $num_tables > 0 )
					for ( $i = 0; $i < $num_tables; $i++ )
					{
						$tables[] = mysql_tablename( $tablelist, $i );
					}
			}

			if ( $num_tables > 0 )
			{
				if ( $type == "STATUS" )
				{
					$query = 'SHOW TABLE STATUS FROM ' . $dbname;
				}
				else
				{
					$query = "$type TABLE ";
					foreach ( $tables as $table )
					{
						if ( $query != "$type TABLE " ) $query .= ", ";
						$query .= $table;
					}
				}

				$result = mysql_query( $query );

				$numfields = mysql_num_fields( $result );
				echo '<table align="center" border="1"><tr>';
				for ( $j = 0; $j < $numfields; $j++ )
				{
					echo '<td align="center"><b>' . mysql_field_name( $result, $j ) . '</b></td>';
				}
				echo '</tr>';
				while ( $row = mysql_fetch_row($result) )
				{
					echo '<tr>';
					for ( $j = 0; $j < $numfields; $j++ )
					{
						echo '<td>' . $row[$j] . '</td>';
					}
					echo '</tr>';
				}
				echo '</table>';
			}
			CloseTable();
			echo "<br>";
			AfterAction();
			include "../footer.php";
			break;

		case "RestoreDB":
			include ( "../includes/sql_parse.php" );
			$sqlfile_tmpname = $HTTP_POST_FILES['sqlfile']['tmp_name'];
			$sqlfile_name = $HTTP_POST_FILES['sqlfile']['name'];
			$sqlfile_type = ( ! empty($HTTP_POST_FILES['sqlfile']['type']) ) ? $HTTP_POST_FILES['sqlfile']['type']:
			"";
			if ( $sqlfile_tmpname == '' || $sqlfile_name == '' )
			{
				info_exit( "ERROR no file specified!" );
			}

			if ( preg_match("/^(text\/[a-zA-Z]+)|(application\/(x\-)?gzip(\-compressed)?)|(application\/octet-stream)$/is", $sqlfile_type) )
			{
				if ( preg_match("/\.gz$/is", $sqlfile_name) )
				{
					$do_gzip_compress = false;
					$phpver = phpversion();
					if ( $phpver >= "4.0" )
					{
						if ( extension_loaded("zlib") )
						{
							$do_gzip_compress = true;
						}
					}

					if ( $do_gzip_compress )
					{
						$gz_ptr = gzopen( $sqlfile_tmpname, 'rb' );
						$sql_query = "";
						while ( ! gzeof($gz_ptr) )
						{
							$sql_query .= gzgets( $gz_ptr, 100000 );
						}
					}
					else
					{
						info_exit( "ERROR Can't decompress file" );
					}
				}
				else
				{
					$sql_query = fread( fopen($sqlfile_tmpname, 'r'), filesize($sqlfile_tmpname) );
				}
			}
			else
			{
				info_exit( "ERROR filename incorrect $sqlfile_type $sqlfile_name" );
			}

			if ( $sql_query != "" )
			{
				$sql_query = remove_remarks( $sql_query );
				$pieces = split_sql_file( $sql_query, ";\n" );
				foreach ( $pieces as $query )
				{
					set_time_limit( 30 );
					$db->sql_query( $query );
				}
			}
			include ( "../header.php" );
			GraphicAdmin();
			Datamenu();
			OpenTable();
			echo "<h2 align=\"center\">Finnished adding $sqlfile_name to the database</h2>$sql_query<br>" . sizeof( $pieces ) . "";
			CloseTable();
			include ( "../footer.php" );
			break;

		case "DataAdmin":
			DataAdmin();
			break;
	}
}
else
{
	echo "Access Denied";
}

?>