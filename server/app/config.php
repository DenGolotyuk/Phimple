<?

class config
{
	public static $data = array(
		'production' => false
	);
	
	public static function init()
	{
		if ( defined('ROOT') )
			self::$data = require ROOT . '/config/app/prod.php';
		
		if ( defined('ENV') )
		{
			$data = require ROOT . '/config/app/' . ENV . '.php';;
			self::$data = array_merge(self::$data, $data);
		}
	}
	
	public static function get()
	{
		$data = self::$data;
		
		foreach ( func_get_args() as $name )
			$data = $data[$name];
		
		return $data;
	}
}