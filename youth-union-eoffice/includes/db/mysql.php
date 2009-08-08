<?php

if ( (! defined('NV_SYSTEM')) and (! defined('NV_ADMIN')) )
{
	die();
}

if ( ! defined("SQL_LAYER") )
{

	/**
	 * get_microtime()
	 * 
	 * @return
	 */
	function get_microtime()
	{
		list( $usec, $sec ) = explode( " ", microtime() );
		return ( $usec + $sec );
	}

	/**
	 * sql_db
	 * 
	 * @package   
	 * @author VBA
	 * @copyright Nguyen Anh Tu
	 * @version 2009
	 * @access public
	 */
	class sql_db
	{

		var $db_connect_id;
		var $query_result;
		var $row = array();
		var $rowset = array();
		var $num_queries = 0;
		var $time = 0;
		var $query_ids = array();


		/**
		 * sql_db::sql_db()
		 * 
		 * @param mixed $sqlserver
		 * @param mixed $sqluser
		 * @param mixed $sqlpassword
		 * @param mixed $database
		 * @param bool $persistency
		 * @return
		 */
		function sql_db( $sqlserver, $sqluser, $sqlpassword, $database, $persistency = true )
		{
			$stime = get_microtime();
			$this->persistency = $persistency;
			$this->user = $sqluser;
			$this->password = $sqlpassword;
			$this->server = $sqlserver;
			$this->dbname = $database;

			if ( $this->persistency )
			{
				$this->db_connect_id = @mysql_pconnect( $this->server, $this->user, $this->password );
			}
			else
			{
				$this->db_connect_id = @mysql_connect( $this->server, $this->user, $this->password );
			}
			if ( $this->db_connect_id )
			{
				if ( $database != "" )
				{
					$this->dbname = $database;
					$dbselect = @mysql_select_db( $this->dbname );
					if ( ! $dbselect )
					{
						@mysql_close( $this->db_connect_id );
						$this->db_connect_id = $dbselect;
					}
				}
				$this->time += ( get_microtime() - $stime );
				return $this->db_connect_id;
			}
			else
			{
				return false;
			}
		}


		/**
		 * sql_db::sql_close()
		 * 
		 * @return
		 */
		function sql_close()
		{
			if ( $this->db_connect_id )
			{
				$numid = count( $this->query_ids );
				for ( $i = 0; $i < $numid; $i++ )
				{
					if ( isset($this->query_ids[$i]) )
					{
						@mysql_free_result( $this->query_ids[$i] );
					}
				}
				if ( ! $this->persistency )
				{
					$result = @mysql_close( $this->db_connect_id );
					$this->db_connect_id = null;
					return $result;
				}
				return false;
			}
			else
			{
				return false;
			}
		}


		/**
		 * sql_db::sql_query()
		 * 
		 * @param string $query
		 * @param bool $transaction
		 * @return
		 */
		function sql_query( $query = "", $transaction = false )
		{
			$stime = get_microtime();

			unset( $this->query_result );
			if ( $query != "" )
			{
				$query = eregi_replace( 'union', 'UNI0N', $query );

				$this->query_result = @mysql_query( $query, $this->db_connect_id );
				$this->num_queries++;
			}
			if ( $this->query_result )
			{
				unset( $this->row[$this->query_result] );
				unset( $this->rowset[$this->query_result] );
				$this->time += ( get_microtime() - $stime );
				$this->query_ids[] = $this->query_result;
				return $this->query_result;
			}
		}


		/**
		 * sql_db::sql_numrows()
		 * 
		 * @param integer $query_id
		 * @return
		 */
		function sql_numrows( $query_id = 0 )
		{
			$stime = get_microtime();
			if ( ! $query_id )
			{
				$query_id = $this->query_result;
			}
			if ( $query_id )
			{
				$result = @mysql_num_rows( $query_id );
				$this->time += ( get_microtime() - $stime );
				return $result;
			}
			else
			{
				$this->time += ( get_microtime() - $stime );
				return false;
			}
		}

		/**
		 * sql_db::sql_affectedrows()
		 * 
		 * @return
		 */
		function sql_affectedrows()
		{
			$stime = get_microtime();
			if ( $this->db_connect_id )
			{
				$result = @mysql_affected_rows( $this->db_connect_id );
				$this->time += ( get_microtime() - $stime );
				return $result;
			}
			else
			{
				return false;
			}
		}

		/**
		 * sql_db::sql_numfields()
		 * 
		 * @param integer $query_id
		 * @return
		 */
		function sql_numfields( $query_id = 0 )
		{
			$stime = get_microtime();
			if ( ! $query_id )
			{
				$query_id = $this->query_result;
			}
			if ( $query_id )
			{
				$result = @mysql_num_fields( $query_id );
				$this->time += ( get_microtime() - $stime );
				return $result;
			}
			else
			{
				$this->time += ( get_microtime() - $stime );
				return false;
			}
		}

		/**
		 * sql_db::sql_fieldname()
		 * 
		 * @param mixed $offset
		 * @param integer $query_id
		 * @return
		 */
		function sql_fieldname( $offset, $query_id = 0 )
		{
			$stime = get_microtime();
			if ( ! $query_id )
			{
				$query_id = $this->query_result;
			}
			if ( $query_id )
			{
				$result = @mysql_field_name( $query_id, $offset );
				$this->time += ( get_microtime() - $stime );
				return $result;
			}
			else
			{
				$this->time += ( get_microtime() - $stime );
				return false;
			}
		}

		/**
		 * sql_db::sql_fieldtype()
		 * 
		 * @param mixed $offset
		 * @param integer $query_id
		 * @return
		 */
		function sql_fieldtype( $offset, $query_id = 0 )
		{
			$stime = get_microtime();
			if ( ! $query_id )
			{
				$query_id = $this->query_result;
			}
			if ( $query_id )
			{
				$result = @mysql_field_type( $query_id, $offset );
				$this->time += ( get_microtime() - $stime );
				return $result;
			}
			else
			{
				return false;
			}
		}

		/**
		 * sql_db::sql_fetchrow()
		 * 
		 * @param integer $query_id
		 * @return
		 */
		function sql_fetchrow( $query_id = 0 )
		{
			$stime = get_microtime();
			if ( ! $query_id )
			{
				$query_id = $this->query_result;
			}
			if ( $query_id )
			{
				$this->row[$query_id] = @mysql_fetch_array( $query_id );
				$this->time += ( get_microtime() - $stime );
				return $this->row[$query_id];
			}
			else
			{
				return false;
			}
		}

		/**
		 * sql_db::sql_fetchrowset()
		 * 
		 * @param integer $query_id
		 * @return
		 */
		function sql_fetchrowset( $query_id = 0 )
		{
			if ( ! $query_id )
			{
				$query_id = $this->query_result;
			}
			if ( $query_id )
			{
				$stime = get_microtime();
				unset( $this->rowset[$query_id] );
				unset( $this->row[$query_id] );
				while ( $this->rowset[$query_id] = @mysql_fetch_array($query_id) )
				{
					$result[] = $this->rowset[$query_id];
				}
				$this->time += ( get_microtime() - $stime );
				return $result;
			}
			else
			{
				return false;
			}
		}

		/**
		 * sql_db::sql_fetchfield()
		 * 
		 * @param mixed $field
		 * @param integer $rownum
		 * @param integer $query_id
		 * @return
		 */
		function sql_fetchfield( $field, $rownum = -1, $query_id = 0 )
		{
			if ( ! $query_id )
			{
				$query_id = $this->query_result;
			}
			if ( $query_id )
			{
				if ( $rownum > -1 )
				{
					$result = @mysql_result( $query_id, $rownum, $field );
				}
				else
				{
					if ( empty($this->row[$query_id]) && empty($this->rowset[$query_id]) )
					{
						if ( $this->sql_fetchrow() )
						{
							$result = $this->row[$query_id][$field];
						}
					}
					else
					{
						if ( $this->rowset[$query_id] )
						{
							$result = $this->rowset[$query_id][$field];
						}
						else
							if ( $this->row[$query_id] )
							{
								$result = $this->row[$query_id][$field];
							}
					}
				}
				return $result;
			}
			else
			{
				return false;
			}
		}

		/**
		 * sql_db::sql_rowseek()
		 * 
		 * @param mixed $rownum
		 * @param integer $query_id
		 * @return
		 */
		function sql_rowseek( $rownum, $query_id = 0 )
		{
			if ( ! $query_id )
			{
				$query_id = $this->query_result;
			}
			if ( $query_id )
			{
				$result = @mysql_data_seek( $query_id, $rownum );
				return $result;
			}
			else
			{
				return false;
			}
		}

		/**
		 * sql_db::sql_nextid()
		 * 
		 * @return
		 */
		function sql_nextid()
		{
			if ( $this->db_connect_id )
			{
				$result = @mysql_insert_id( $this->db_connect_id );
				return $result;
			}
			else
			{
				return false;
			}
		}

		/**
		 * sql_db::sql_freeresult()
		 * 
		 * @param integer $query_id
		 * @return
		 */
		function sql_freeresult( $query_id = 0 )
		{
			if ( ! $query_id )
			{
				$query_id = $this->query_result;
			}
			if ( $query_id )
			{
				unset( $this->row[$query_id] );
				unset( $this->rowset[$query_id] );
				@mysql_free_result( $query_id );
				$numid = count( $this->query_ids );
				for ( $i = 0; $i < $numid; $i++ )
				{
					if ( $this->query_ids[$i] == $query_id )
					{
						unset( $this->query_ids[$i] );
						return true;
					}
				}
				return true;
			}
			else
			{
				return false;
			}
		}

		/**
		 * sql_db::sql_error()
		 * 
		 * @param integer $query_id
		 * @return
		 */
		function sql_error( $query_id = 0 )
		{
			$result["message"] = @mysql_error( $this->db_connect_id );
			$result["code"] = @mysql_errno( $this->db_connect_id );
			return $result;
		}

	}


	define( "SQL_LAYER", "mysql" );

}

?>
