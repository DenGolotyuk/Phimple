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
}