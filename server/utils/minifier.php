<?

class minifier
{
	public static function compile()
	{
		self::compile_app();
		
		$apps = glob(ROOT . '/*', GLOB_ONLYDIR);
		foreach ( $apps as $dir )
		{
			if ( basename($dir) == 'app' ) continue;
			
			if ( file_exists($dir . '/web/index.php') )
				self::compile_app(basename($dir));
		}
	}
	
	public static function compile_app($app = 'app')
	{
		log::message('Compiling ' . $app);
		
		$list = self::gather_files(FROOT . '/client');
		$list = array_merge($list, self::gather_files(ROOT . '/' . $app));
		
		self::pack($list, $app);
	}
	
	public static function gather_files($path)
	{
		$list = array();
		$files = glob($path . '/*', GLOB_NOSORT);
		
		foreach ( $files as $file )
			if ( in_array(pathinfo($file, PATHINFO_EXTENSION), array('css', 'js')) )
				if ( !strpos($file, '/web/') )
					$list[] = $file;
		
		foreach ( glob($path . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir )
			$list = array_merge($list, self::gather_files($dir));
		
		return $list;
	}
	
	public static function pack($list, $app)
	{
		foreach ( $list as $file )
		{
			$ext = pathinfo($file, PATHINFO_EXTENSION);
			$name = pathinfo($file, PATHINFO_FILENAME);
			
			$sufix = '';
			if ( ($spos = strpos($name, ':')) !== false )
				$sufix = substr($name, $spos);
			
			$group[$ext . $sufix . '.' . $ext] .= file_get_contents($file);
			if ( $ext == 'js' ) $group[$ext . $sufix . '.' . $ext] .= '; ';
		}
		
		$signs = ROOT . '/' . $app . '/web/signs.php';
		if ( is_file($signs) ) $sign = include $signs;
		
		foreach ( $group as $name => $content )
		{
			if ( pathinfo($name, PATHINFO_EXTENSION) == 'css' )
				$content = reclient::css($content);
			
			if ( $sign[$name] == md5($content) ) continue;
			log::message( $sign[$name] );
			log::message( md5($content) );
			log::message('');
			
			$sign[$name] = md5($content);
			
			$file = ROOT . '/' . $app . '/web/' . $name;
			file_put_contents($file, $content);
			
			if ( !config::get('static-no-compile') )
				exec('java -jar ' . FROOT . '/assets/utils/yui.jar ' . $file . ' -o ' . $file);
		}
		
		foreach ( $sign as $name => $md5 )
			$php[] = "'{$name}' => '{$md5}'";
		
		file_put_contents($signs, '<? return array(' . implode(', ', $php) . ');');
	}
}