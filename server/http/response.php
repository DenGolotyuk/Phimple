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
}