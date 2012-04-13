<?

class html_helper
{
	private static $signs = null;
	private static $client_context = array();
	private static $js_callbacks = array();
	
	public static function sign($path)
	{
		if ( !self::$signs ) self::$signs = include ROOT . '/app/web/signs.php';
		
		return substr(base_convert(self::$signs[trim($path, '/')], 16, 36), 0, 4);
	}
	
	public static function js($path)
	{
		return '<script type="text/javascript" src="' . $path . '?' . self::sign($path) . '"></script>';
	}
	
	public static function css($path)
	{
		return '<link rel="stylesheet" href="' . $path . '?' . self::sign($path) . '" type="text/css" media="all" />';
	}
	
	public static function head()
	{
		return
		'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		 <link rel="shortcut icon" href="/favicon.ico" />' .
		 self::css('/css.css') . 
		 '<!--[if lt IE 8]>' . self::css('/css:ie.css') . '<![endif]-->';
	}
	
	public static function foot($context = array())
	{
		$context['action'] = (string)context::$action;
		$context = array_merge($context, self::$client_context);
		
		return
			self::js('/js.js') . 
			'<script type="text/javascript">App.init(' . json_encode($context) . ');</script>' .
			'<script type="text/javascript">' . implode(';', self::$js_callbacks) . '</script>';
	}
	
	public static function set_client_context($name, $value)
	{
		self::$client_context[$name] = $value;
	}
	
	public static function js_callback($cb)
	{
		if ( !strpos($cb, '(') ) $cb .= '()';
		self::$js_callbacks[] = $cb;
	}
}