<?

class {$name}_base
{
	const TABLE = '{$name}';
	const PK = 'id';
	const CONNECTION = 'master';
	
	private static $db = null;
	
	/**
	 *
	 * @return mysql
	 */
	public static function db()
	{
		if ( !self::$db ) self::$db = new mysql(self::CONNECTION, self::TABLE);
		return self::$db;
	}
	
	public static function get( $pk )
	{
		return self::db()->row(array(self::PK => $pk));
	}
	
	public static function get_by( $key, $value )
	{
		return self::db()->row(array($key => $value));
	}
	
	public static function insert( $data )
	{
		return self::db()->insert($data);
	}
	
	public static function insert_update( $insert, $update )
	{
		return self::db()->insert_update($insert, $update);
	}
	
	public static function save( $pk, $data )
	{
		return self::db()->update($data, array(self::PK => $pk));
	}
	
	public static function delete( $pk )
	{
		return self::db()->delete(array(self::PK => $pk));
	}
}