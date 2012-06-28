<?

class error_handler
{
	public static function init()
	{
		set_exception_handler('error_handler::exception');
		set_error_handler("error_handler::error", E_ALL & ~E_NOTICE);
	}
	
	public static function exception($e)
	{
		render::exception($e);
		log::error(
			$e . "\nurl: " . $_SERVER['REQUEST_URI'] . "\nref: " . $_SERVER['HTTP_REFERER'] .
			( class_exists('user') ? ("\n" . 'user: ' . user::id()) : '' )
		);
	}
	
	public static function error($errno, $errstr, $errfile, $errline )
	{
		#throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
	}
}