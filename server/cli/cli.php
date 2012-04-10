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
	}
}