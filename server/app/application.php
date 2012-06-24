<?

require_once 'config.php';
require_once 'loader.php';

class application
{
	public static function init()
	{
		error_handler::init();
		config::init();
	}
	
	public static function run_action( $name = null )
	{
		if ( !$name ) $name = request::get_action_name();
		if ( defined('PRE') ) $name = PRE . '_' . $name;
		
		if ( !class_exists($name . '_action') )
			$name = $name .= '_root';
		
		$name .= '_action';
		if ( !class_exists($name) ) throw new no_action_exception();
		
		context::$action = new $name;
		
		if ( method_exists(context::$action, 'init') ) context::$action->init();
		
		if ( method_exists(context::$action, 'execute') ) context::$action->execute();
		
		if ( (request::get_method() == 'POST') && method_exists(context::$action, 'execute_post') )
			context::$action->execute_post();
		
		if ( (request::get_method() == 'GET') && method_exists(context::$action, 'execute_get') )
			context::$action->execute_get();
			
		render::action(context::$action);
	}
	
	public static function run_task( $name = null, $args = array() )
	{
		log::message('Executing ' . $name . ' task...');
		$ts = microtime(true);
		
		$name .= '_task';
		
		$action = new $name;
		$action->execute($args);
		
		log::message('Done in ' . number_format(microtime(true) - $ts, 3) . 's');
	}
}