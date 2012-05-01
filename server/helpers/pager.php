<?

class pager_helper
{
	public static function page()
	{
		return max(request::get('p'), 1);
	}
	
	public static function prev()
	{
		if ( self::page() > 1 ) return self::page() - 1;
		return 1;
	}
	
	public static function next()
	{
		return self::page() + 1;
	}
	
	public static function offset($limit = 20)
	{
		return (self::page() - 1)*$limit;
	}
	
	public static function pages($total, $per_page = 20, $show = 10)
	{
		$pages = ceil($total/$per_page);
		
		$delta = $show/2;
		$start = self::page() - $delta;
		if ( $start < 1 ) $start = 1;
		
		$end = $start + $show - 1;
		if ( $end > $pages ) $end = $pages;
		
		return range($start, $end);
	}
}