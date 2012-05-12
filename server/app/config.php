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
		$path = ROOT . '/config/app/' . $file . '.php';
		if ( !is_file($path) ) return;
		
		$data = require $path;
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