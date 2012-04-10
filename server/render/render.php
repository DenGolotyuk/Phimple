<?

class render
{
	public static function action(action $action)
	{
		self::html($action);
	}
	
	public static function html($action)
	{
		if ( !config::get('production') ) minifier::compile();
		
		foreach ( $action as $var => $val ) $context[$var] = $val;
		
		ob_start();
		
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
		foreach ( context::$action as $var => $val ) $$var = $val;
		foreach ( $context as $var => $val ) $$var = $val;
		
		ob_start();
		$view = ROOT . '/app/actions/' . $tpl . '.phtml';
		
		include $view;
		return ob_get_clean();
	}
}