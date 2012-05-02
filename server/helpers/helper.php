<?

class helper
{
	public static function time($ts)
	{
		$delta = time() - $ts;
		
		if ( $delta <= 60  ) return 'только что';
		if ( $delta <= 60*5  ) return 'пару минут назад';
		if ( $delta <= 60*30  ) return 'полчаса назад';
		if ( $delta <= 60*60  ) return 'час назад';
		if ( $delta <= 60*60*3  ) return 'пару часов назад';
		if ( $delta <= 60*60*12  ) return 'недавно';
		if ( $delta <= 60*60*24  ) return 'вчера';
		if ( $delta <= 60*60*24*7  ) return 'на этой неделе';
		if ( $delta <= 60*60*24*30  ) return 'в этом месяце';
		if ( $delta <= 60*60*24*30*2  ) return 'давно';
	}
	
	public static function date_time($ts)
	{
		return date('H:i d.m', $ts);
	}
	
	public static function url_params($url, $params = array())
	{
		$url = 'http://' . config::get('host') . '/' . $url;
		
		if ( $params )
		{
			foreach ( $params as $k => $v )
			{
				$q[] = "{$k}={$v}";
			}
			
			$url .= '?' . implode('&', $q);
		}
		
		return $url;
	}
	
	public static function transliterate($string)
	{
        $rus = array("ё","й","ю","ь","ч","щ","ц","у","к","е","н","г","ш","з","х","ъ","ф","ы","в","а","п","р","о","л","д","ж","э","я","с","м","и","т","б","Ё","Й","Ю","Ч","Ь","Щ","Ц","У","К","Е","Н","Г","Ш","З","Х","Ъ","Ф","Ы","В","А","П","Р","О","Л","Д","Ж","Э","Я","С","М","И","Т","Б");
        $eng = array("yo","iy","yu","'","ch","sh","c","u","k","e","n","g","sh","z","h","'","f","y","v","a","p","r","o","l","d","j","е","ya","s","m","i","t","b","Yo","Iy","Yu","CH","'","SH","C","U","K","E","N","G","SH","Z","H","'","F","Y","V","A","P","R","O","L","D","J","E","YA","S","M","I","T","B");
        
		return str_replace($rus, $eng,  $string);
    }
}