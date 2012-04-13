<?

class helper
{
	public static function time($ts)
	{
		$delta = time() - $ts;
		
		if ( $delta <= 60  ) return 'Только что';
		if ( $delta <= 60*5  ) return 'Пару минут назад';
		if ( $delta <= 60*30  ) return 'Полчаса назад';
		if ( $delta <= 60*60  ) return 'Час назад';
		if ( $delta <= 60*60*3  ) return 'Пару часов назад';
		if ( $delta <= 60*60*12  ) return 'Недавно';
		if ( $delta <= 60*60*24  ) return 'Вчера';
		if ( $delta <= 60*60*24*7  ) return 'На этой неделе';
		if ( $delta <= 60*60*24*30  ) return 'В этом месяце';
		if ( $delta <= 60*60*24*30*2  ) return 'Давно';
	}
}