<?

class mysql
{
	private static $default_connection = 'master';
	private static $connections = array();
	
	private $connection = 'master';
	private $table = null;
	
	public function __construct( $connection = null, $table = null )
	{
		if ( $connection ) $this->connection = $connection;
		if ( $table ) $this->table = $table;
	}
	
	/**
	 *
	 * @return PDO
	 */
	public function con($connection = null)
	{
		if ( !$connection )
		{
			if ( get_class($this) == 'mysql' )
				$connection = $this->connection;
			else
				$connection = self::$default_connection;
		}
		
		if ( !self::$connections[$connection] )
		{
			$params = config::get('mysql', $connection);
			self::$connections[$connection] = new PDO( 'mysql:host=' . $params['host'] . ';dbname=' . $params['db'], $params['user'], $params['pwd'] );
			self::$connections[$connection]->query('SET NAMES utf8');
		}
		
		return self::$connections[$connection];
	}
	
	public function query($sql, $bind = array(), $connection = null)
	{
		$st = self::con($connection)->prepare($sql);
		if ( $bind )
		{
			foreach ( $bind as $k => $v )
			{
				$st->bindValue($k, $v);
			}
		}
		
		$st->execute();
		$error = $st->errorInfo();
		
		if ( $error[1] )
			throw new sys_exception($error[2], $error[1]);
		
		return $st;
	}
	
	public static function last_id($connection)
	{
		return self::con($connection)->lastInsertId();
	}
	
	private function get_sql($sql_or_filter, $bind_or_table = null, $column = '*')
	{
		if ( is_string($sql_or_filter) )
		{
			$sql = $sql_or_filter;
			$bind = $bind_or_table ? $bind_or_table : array();
		}
		else
		{
			foreach ( $sql_or_filter as $k => $v )
			{
				$where[] = "`{$k}` = :{$k}";
				$bind[$k] = $v;
			}
			
			$table = $bind_or_table;
			
			if ( !$table && (get_class($this) == 'mysql') )
			{
				$table = $this->table;
			}
			
			$sql = 'SELECT ' . $column . ' FROM ' . $table .
					($where ? (' WHERE ' . implode(' AND ', $where)) : '' );
		}
		
		return array( 'statement' => $sql, 'bind' => $bind );
	}
	
	public function col( $sql_or_filter, $bind_or_table = null, $column = '*', $connection = null )
	{
		$sql = self::get_sql($sql_or_filter, $bind_or_table, $column);
		
		$st = self::query($sql['statement'], $sql['bind'], $connection);
		$data = $st->fetch(PDO::FETCH_ASSOC);
		
		return array_shift($data);
	}
	
	public function cols( $sql_or_filter, $bind_or_table = null, $column = '*', $connection = null )
	{
		$sql = self::get_sql($sql_or_filter, $bind_or_table, $column);
		
		$st = self::query($sql['statement'], $sql['bind'], $connection);
		while ( $row = $st->fetch(PDO::FETCH_ASSOC) )
		{
			$list[] = array_shift($row);
		}
		
		return $list;
	}
	
	public function row( $sql_or_filter, $bind_or_table = null, $connection = null )
	{
		$sql = self::get_sql($sql_or_filter, $bind_or_table);
		
		$st = self::query($sql['statement'], $sql['bind'], $connection);
		$data = $st->fetch(PDO::FETCH_ASSOC);
		
		return $data;
	}
	
	public function rows( $sql_or_filter, $bind_or_table = null, $connection = null )
	{
		$sql = self::get_sql($sql_or_filter, $bind_or_table);
		
		$st = self::query($sql['statement'], $sql['bind'], $connection);
		while ( $row = $st->fetch(PDO::FETCH_ASSOC) )
		{
			$list[] = $row;
		}
		
		return $list;
	}
	
	public function insert($data, $table = null, $connection = null, $params = array())
	{
		foreach ( $data as $k => $v )
		{
			$set[] = "`{$k}` = :{$k}";
			$bind[$k] = $v;
		}
		
		if ( !$table && (get_class($this) == 'mysql') )
			$table = $this->table;
		
		$sql = 'INSERT ' .
				( $params['ignore'] ? ' IGNORE ' : '' ) .
				( $params['low'] ? ' LOW_PRIORITY ' : '' ) .
				' INTO ' . $table . ' SET ' . implode(', ', $set);
		
		self::query($sql, $bind, $connection);
		return self::last_id($connection);
	}
	
	public function insert_update($insert, $update, $table = null, $connection = null)
	{
		foreach ( $insert as $k => $v )
		{
			$new[] = "`{$k}` = :{$k}";
			$bind[$k] = $v;
		}
		
		foreach ( $update as $k => $v )
		{
			$up[] = "`{$k}` = :{$k}";
			$bind[$k] = $v;
		}
		
		if ( !$table && (get_class($this) == 'mysql') )
			$table = $this->table;
		
		$sql = 'INSERT INTO ' . $table . ' SET ' . implode(', ', $new) . ' ON DUPLICATE KEY UPDATE ' . implode(',', $up);
		
		self::query($sql, $bind, $connection);
		return self::last_id($connection);
	}
	
	public function update($data, $filter, $table = null, $connection = null)
	{
		foreach ( $data as $k => $v )
		{
			$set[] = "`{$k}` = :{$k}";
			$bind[$k] = $v;
		}
		
		foreach ( $filter as $k => $v )
		{
			$where[] = "`{$k}` = :{$k}";
			$bind[$k] = $v;
		}
		
		if ( !$table && (get_class($this) == 'mysql') )
			$table = $this->table;
		
		$sql = 'UPDATE ' . $table . ' SET ' . implode(', ', $set) . ' WHERE ' . implode(',', $where);
		
		return self::query($sql, $bind, $connection);
	}
	
	public function delete($filter, $table = null, $connection = null)
	{
		foreach ( $filter as $k => $v )
		{
			$where[] = "`{$k}` = :{$k}";
			$bind[$k] = $v;
		}
		
		if ( !$table && (get_class($this) == 'mysql') )
			$table = $this->table;
		
		$sql = 'DELETE FROM ' . $table . ' WHERE ' . implode(',', $where);
		
		return self::query($sql, $bind, $connection);
	}
}