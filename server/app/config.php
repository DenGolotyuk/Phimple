<?

class config
{
	public static $data = array(
		'production' => false
	);
	
	public static function init()
	{
		if ( defined('ROOT') ) self::load('prod');
		if ( defined('ENV') ) self::load(ENV);
	}
	
	public static function load($file)
	{
		$data = require ROOT . '/config/app/' . $file . '.php';;
		self::$data = array_merge(self::$data, $data);
	}
	
	public static function get()
	{
		$data = self::$data;
		
		foreach ( func_get_args() as $name )
			$data = $data[$name];
		
		return $data;
	}
}