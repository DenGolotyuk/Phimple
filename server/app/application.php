<?

require_once 'config.php';
require_once 'loader.php';

class application
{
	public static function init()
	{
		config::init();
		loader::init();
	}
	
	public static function run_action( $name = null )
	{
		if ( !$name ) $name = request::get_action_name();
		$name .= '_action';
		
		context::$action = new $name;
		context::$action->execute();
		
		render::action(context::$action);
	}
	
	public static function run_task( $name = null, $args = array() )
	{
		log::message('Executing ' . $name . ' task...');
		$ts = microtime(true);
		
		$name .= '_task';
		
		$action = new $name;
		$action->execute();
		
		log::message('Done in ' . number_format(microtime(true) - $ts, 3) . 's');
	}
}