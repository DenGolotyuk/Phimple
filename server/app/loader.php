<?

class loader
{
	public static $map = array();
	
	public static function init()
	{
		if ( defined('ROOT') )
		{
			$file = ROOT . '/data/cache/loader';
			if ( is_file($file) ) self::$map = include $file;
		}
	}
	
	public static function compile()
	{
		self::$map = array();
		
		if ( defined('FROOT') ) self::gather_classes(FROOT);
		if ( defined('ROOT') )
		{
			self::gather_classes(ROOT);
			
			file_put_contents(ROOT . '/data/cache/loader', self::generate_map_cache());
			@chmod(ROOT . '/data/cache/loader', 0777);
		}
	}

	public static function gather_classes( $dir )
	{
		$i = new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator($dir),
				RecursiveIteratorIterator::SELF_FIRST
			);

		foreach ( $i as $file )
		{
			if ( strpos($file, '/oi/') ) continue;
			if ( strpos($file, '/swift/') ) continue;
			
			if ( pathinfo($file, PATHINFO_EXTENSION) == 'php' )
			{
				error_log($file);
				
				$data = file_get_contents( $file );

				if ( preg_match_all('/(class|interface) ([a-z_0-9]+)/i', $data, $m) )
				{
					foreach ( $m[2] as $class_name ) self::$map[$class_name] = (string)$file;
				}
			}
		}
	}
	
	public static function generate_map_cache()
	{
		$src = '<? return array(';
		
		foreach ( self::$map as $class => $path )
			$src .= "'{$class}' => '{$path}',\n";

		return $src . ');';
	}
	
	public static function path($name)
	{
		if ( !self::$map ) self::init();
		$path = self::$map[$name];
		
		if ( !$path && !config::get('production') )
		{
			self::compile();
			$path = self::$map[$name];
		}
		
		return $path;
	}
}

function __autoload($name)
{
	if ( $path = loader::path($name) ) require_once $path;
}