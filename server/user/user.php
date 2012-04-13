<?

class user
{
	public static $session_started;
	
	public static function get($name)
	{
		if ( !self::$session_started ) self::$session_started = session_start();
		return $_SESSION[$name];
	}
	
	public static function set($name, $val)
	{
		if ( !self::$session_started ) self::$session_started = session_start();
		return $_SESSION[$name] = $val;
	}
	
	public static function id()
	{
		return self::get('id');
	}
	
	public static function data($key = null)
	{
		$data = users::get(self::id());
		return $key ? $data[$key] : $data;
	}
	
	public static function login($id)
	{
		self::set('id', $id);
	}
	
	public static function logout()
	{
		self::set('id', null);
	}
}