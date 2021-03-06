<?

if ( $_REQUEST['bhp'] && ($data = json_decode(base64_decode($_REQUEST['bhp'], true))) )
	foreach ( $data as $k => $v ) $_REQUEST[$k] = $_REQUEST[$k] ? $_REQUEST[$k] : $v;

class request
{
	public static function get($name, $default = null)
	{
		return array_key_exists($name, $_REQUEST) ? $_REQUEST[$name] : $default;
	}
	
	public static function get_action_name()
	{
		if ( !request::get('action') ) return 'root';
		return str_replace('/', '_', request::get('action'));
	}
	
	public static function get_method()
	{
		return $_SERVER['REQUEST_METHOD'];
	}

	public static function get_accept()
	{
		$accept = trim($_SERVER['HTTP_ACCEPT'], ';');
		return array_shift(explode(', ', $accept));
	}
	
	public static function is_ajax()
	{
		return (bool)$_SERVER['HTTP_X_REQUESTED_WITH'];
	}
	
	public static function get_ip()
	{
		return sprintf("%u", ip2long($_SERVER['REMOTE_ADDR']));
	}
	
	public static function referrer()
	{
		return $_SERVER['HTTP_REFERER'];
	}
	
	public static function uri()
	{
		return $_SERVER['REQUEST_URI'];
	}
}