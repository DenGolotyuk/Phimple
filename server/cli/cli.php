<?

class cli
{
	public static function init($args)
	{
		log::set_logger('terminal');
		array_shift($args);
		
		if ( $args )
		{
			$task = array_shift($args);
			application::run_task( $task, $args );
		}
		else
		{
			self::show_help();
		}
	}
	
	public static function show_help()
	{
		log::message('List of available tasks');
		
		$list = self::find_tasks( FROOT . '/server/tasks' );
		
		if ( defined('ROOT') )
			$list = array_merge($list, self::find_tasks( ROOT . '/lib/tasks' ));
		
		sort($list);
		
		foreach ( $list as $task_file )
		{
			$php = file_get_contents($task_file);
			preg_match('/class (.+)/', $php, $m);
			
			$task = str_replace('_task', '', $m[1]);
			log::message(str_repeat(' ', 3) . $task);
		}
		
		log::message('');
	}
	
	public static function find_tasks($path)
	{
		$list = glob($path . '/*.php', GLOB_NOSORT);
		
		foreach ( glob($path . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $file )
			$list = array_merge ($list, self::find_tasks ($file));
		
		return $list;
	}
}