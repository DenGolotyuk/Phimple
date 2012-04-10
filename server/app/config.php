<?

class config
{
	public static $data = array();
	
	public static function init()
	{
		self::$data = require ROOT . '/config/app/prod.php';
		if (defined('ENV') )
		{
			$data = require ROOT . '/config/app/' . ENV . '.php';;
			self::$data = array_merge(self::$data, $data);
		}
	}
	
	public static function get($name)
	{
		return self::$data[$name];
	}
}