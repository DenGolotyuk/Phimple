<?

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
}