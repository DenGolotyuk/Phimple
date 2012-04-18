<?

class minifier
{
	public static function compile()
	{
		$list = self::gather_files(FROOT);
		$list = array_merge($list, self::gather_files(ROOT));
		
		self::pack($list);
	}
	
	public static function gather_files($path)
	{
		$list = array();
		$files = glob($path . '/*', GLOB_NOSORT);
		
		foreach ( $files as $file )
			if ( in_array(pathinfo($file, PATHINFO_EXTENSION), array('css', 'js')) )
				if ( !strpos($file, 'app/web') )
					$list[] = $file;
		
		foreach ( glob($path . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir )
			$list = array_merge($list, self::gather_files($dir));
		
		return $list;
	}
	
	public static function pack($list)
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
		
		$signs = ROOT . '/app/web/signs.php';
		if ( is_file($signs) ) $sign = include $signs;
		
		foreach ( $group as $name => $content )
		{
			if ( pathinfo($name, PATHINFO_EXTENSION) == 'css' )
				$content = reclient::css($content);
			
			if ( $sign[$name] == md5($content) ) continue;
			
			$sign[$name] = md5($content);
			
			$file = ROOT . '/app/web/' . $name;
			file_put_contents($file, $content);
			exec('java -jar ' . FROOT . '/assets/utils/yui.jar ' . $file . ' -o ' . $file);
		}
		
		foreach ( $sign as $name => $md5 )
			$php[] = "'{$name}' => '{$md5}'";
		
		file_put_contents($signs, '<? return array(' . implode(', ', $php) . ');');
	}
}