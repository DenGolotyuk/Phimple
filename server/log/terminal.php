<?

class terminal_logger
{

	public static $colors = array(
		'LIGHT_RED' => "[1;31m",
		'LIGHT_GREEN' => "[1;32m",
		'YELLOW' => "[1;33m",
		'LIGHT_BLUE' => "[1;34m",
		'MAGENTA' => "[1;35m",
		'LIGHT_CYAN' => "[1;36m",
		'WHITE' => "[1;37m",
		'NORMAL' => "[0m",
		'BLACK' => "[0;30m",
		'RED' => "[0;31m",
		'GREEN' => "[0;32m",
		'BROWN' => "[0;33m",
		'BLUE' => "[0;34m",
		'CYAN' => "[0;36m",
		'BOLD' => "[1m",
		'UNDERSCORE' => "[4m",
		'REVERSE' => "[7m",
	);

	public function log($message, $level = null)
	{
		echo self::colored($message, $level == 'error' ? 'RED' : 'GREEN');
		echo "\n";
	}

	public static function colored($text, $color = "NORMAL", $back = true)
	{
		$out = self::$colors["$color"];
		
		if ($out == "")
		{
			$out = "[0m";
		}
		
		if ($back)
		{
			return chr(27) . "$out$text" . chr(27) . "[0m";
		}
		else
		{
			echo chr(27) . "$out$text" . chr(27) . chr(27) . "[0m";
		}
	}

}