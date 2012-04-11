<?

class response
{
	public static function set_header($name, $value)
	{
		header("{$name}: {$value}");
	}
	
	public static function send($body)
	{
		echo $body;
	}
	
	public static function redirect( $url )
	{
		if ( request::is_ajax() )
			echo json_encode(array('redirect' => $url));
		else
			self::set_header('Location', $url);
		
		exit;
	}
}