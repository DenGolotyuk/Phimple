<?

class user
{
	public static $session_started;
	public static $first = true;
	
	public static function restrict($state = null)
	{
		if ( !self::id() ) response::redirect ('/login');
		
		if ( $state )
		{
			if ( self::data('state') < $state )
				return true;
		}
	}
	
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
		$id = self::get('id');
		
		if ( !$id ) $id = self::try_login();
		
		if ( self::$first )
		{
			self::$first = false;
		}
			
		return $id;
	}
	
	public static function data($key = null)
	{
		$data = users::get(self::id());
		return $key ? $data[$key] : $data;
	}
	
	public static function auth_param($id)
	{
		$user = users::get($id);
		return base64_encode($user['id'] . ':' . $user['pwd']) . ':' . md5($user['id'] . 'persistance');
	}
	
	public static function persist($id)
	{
		$user = users::get($id);
		
		$cookie = base64_encode($user['id'] . ':' . $user['pwd']) . ':' . md5($user['id'] . 'persistance');
		setcookie('p', $cookie, time() + 60*60*24*30, '/');
	}
	
	public static function clear_persist()
	{
		setcookie('p', null, null, '/');
	}
	
	public static function try_login()
	{
		if ( $_REQUEST['p'] || $_COOKIE['p'] )
		{
			$com = explode(':', $_REQUEST['p'] ? $_REQUEST['p'] : $_COOKIE['p']);
			$data = explode(':', base64_decode($com[0]));
			
			$user = users::get($data[0]);
			
			if ( md5($user['id'] . 'persistance') == $com[1] )
			{
				if ( $data[1] == $user['pwd'] )
					$id = $user['id'];
			}
		}
		
		if ( $id ) self::login($id);
		
		return $id;
	}
	
	public static function login($id)
	{
		self::set('id', $id);
		self::persist($id);
		
		users::save($id, array('last_ts' => time()));
		
		if ( class_exists('user_helper') ) user_helper::on_login($id);
	}
	
	public static function logout()
	{
		self::set('id', null);
		self::clear_persist();
	}
}