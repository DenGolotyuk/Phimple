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
		log::error($e);
	}
	
	public static function error($errno, $errstr, $errfile, $errline )
	{
		throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
	}
}