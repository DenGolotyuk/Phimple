<?

class db_migrate_task
{

	public function execute(array $params = array())
	{
		$migrations = glob(ROOT . '/data/migrations/*');
		
		$cache = ROOT . '/data/cache/db.migrations';
		$migrated = file_exists($cache) ? explode("\n", file_get_contents($cache)) : array();
		
		foreach ( $migrations as $migration )
		{
			$name = basename($migration);
			if ( in_array($name, $migrated) )
				continue;
			
			log::message( str_repeat(' ', 5) . 'migrating ' . $name . '...');
			
			$this->migrate($migration);
			$migrated[] = $name;
			$total ++;
		}
		
		if ( $total ) file_put_contents($cache, implode("\n", $migrated));
		
		log::message( $total ? $total . ' migrations processed' : 'No new migrations found' );
	}

	public function migrate($file)
	{
		$content = file_get_contents($file);
		
		if ( (strpos($content, 'create') === 0) || (strpos($content, 'add to') === 0) )
		{
			preg_match('/(create|add to) ([a-z_]+)/', $content, $m);
			$operation = $m[1];
			$table = $m[2];
			
			preg_match_all('/([a-z]+): (.+)/', $content, $m);
			
			foreach ( $m[1] as $i => $column )
			{
				$params = $m[2][$i];
				$additional = '';
				
				if ( $adp = strpos($params, '+') )
				{
					$type = trim(substr($params, 0, $adp));
					$parts = explode('+', substr($params, $adp));
					
					foreach ( $parts as $part )
					{
						if ( !$part = trim($part) ) continue;
						
						switch ( $part )
						{
							case 'pk':
								$pk[] = $column;
								break;
							
							case 'ai':
								$additional .= ' AUTO_INCREMENT ';
								break;
							
							case 'unique':
								$unq = $column;
								break;
							
							default:
								log::error('Unknow property ' . $part . ' for column ' . $column);
						}
					}
				}
				else
				{
					$type = trim($params);
				}
				
				if ( !strpos($type, 'NULL') )
					$additional .= ' NOT NULL ';
				
				if ( in_array($type, array('int', 'smallint', 'bigint', 'tinyint')) )
					$type = $type . ' UNSIGNED ';
				
				$instruction = "`{$column}` {$type} {$additional}";
				
				$fields[] = $instruction;
			}
			
			if ( $pk )
				$fields[] = 'PRIMARY KEY (`' . implode('`,`', $pk) . '`)';
			
			if ( $unq )
				$fields[] = 'UNIQUE KEY `' . $unq . '` (`' . $unq . '`)';
			
			if ( $operation == 'create' )
				$sql = 'CREATE TABLE `' . $table . '` (' . implode(', ', $fields) . ') ENGINE=innoDB DEFAULT CHARSET=utf8';
			else
				$sql = 'ALTER TABLE `' . $table . '` ADD ' . implode(', ADD ', $fields) . '';
		}
		else if ( strpos($content, 'delete') === 0 )
		{
			preg_match('/delete ([a-z_]+)/', $content, $m);
			$table = $m[1];
			
			$sql = 'DROP TABLE `' . $table . '`';
		}
		else if ( strpos($content, 'remove') === 0 )
		{
			preg_match('/remove (.+) from ([a-z_]+)/', $content, $m);
			$columns = explode(',', $m[1]);
			foreach ( $columns as $name ) $drop[] = "DROP COLUMN `" . trim($name) . "`";
			
			$table = $m[2];
			
			$sql = 'ALTER TABLE `' . $table . '` ' . implode(', ', $drop);
		}
		else
		{
			$config = config::get('mysql', mysql::$default_connection);
			exec('mysql -u ' . $config['user'] . ' -p' . $config['pwd'] . ' -h ' . $config['host'] . ' ' . $config['db'] . ' < ' . $file . ' 2>&1', $o, $r);
			
			if ( $r ) throw new sys_exception(implode ("\n", $o));
		}
		
		if ( $sql ) mysql::query($sql);
	}
}