<?

class render
{
	public static function action(action $action)
	{
		if ( request::get_accept() == 'application/json' )
			self::json($action);
		else
			self::html($action);
	}
	
	public static function json($action)
	{
		$response = json_encode($action->json);
		
		response::set_header('Content-type', 'text/json; charset=utf8');
		response::send($response);
	}
	
	public static function html($action)
	{
		if ( !config::get('production') ) minifier::compile();
		
		foreach ( $action as $var => $val ) $context[$var] = $val;
		
		if ( $action->layout )
			$response = self::html_layout($action->layout, $context);
		else
			$response = self::html_view($action->view, $context);
		
		response::set_header('Content-type', 'text/html; charset=utf8');
		response::send($response);
	}
	
	public static function html_layout($tpl, $context = array())
	{
		foreach ( context::$action as $var => $val ) $$var = $val;
		foreach ( $context as $var => $val ) $$var = $val;
		
		ob_start();
		$view = ROOT . '/app/layout/' . $tpl . '.phtml';
		
		include $view;
		return ob_get_clean();
	}
	
	public static function html_view($tpl, $context = array())
	{
		$tpl = str_replace('_', '/', $tpl);
		
		foreach ( context::$action as $var => $val ) $$var = $val;
		foreach ( $context as $var => $val ) $$var = $val;
		
		ob_start();
		$view = ROOT . '/app/actions/' . $tpl . '.phtml';
		
		include $view;
		
		return ob_get_clean();
	}
	
	public static function exception($e)
	{
		if ( !$_SERVER['SHLVL'] )
		{
			if ( $e instanceof no_action_exception )
			{
				#header("Status: 404 Not Found");
				include dirname(__FILE__) . '/tpl/404.php';
			}
			else
			{
				include dirname(__FILE__) . '/tpl/exception.php';
			}
		}
	}
}