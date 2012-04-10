<?

class error_logger
{
	public function log($message, $level = null)
	{
		error_log($message);
	}
}