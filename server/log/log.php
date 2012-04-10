<?

class log
{
	public static $instance;
	
	public static function message($message)
	{
		self::instance()->log($message);
	}
	
	public static function instance()
	{
		if ( !self::$instance )
		{
			self::set_logger('error');
		}
		
		return self::$instance;
	}
	
	public static function set_logger($name)
	{
		$name .= '_logger';
		
		self::$instance = new $name;
	}
}