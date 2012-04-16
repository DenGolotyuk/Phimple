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
}