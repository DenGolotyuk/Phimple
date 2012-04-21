<?

class render
{
	public static function action(action $action)
	{
		if ( request::get_accept() == 'application/json' )
			self::json($action);
		if ( $action->rss )
			self::rss($action);
		else
			self::html($action);
	}
	
	public static function rss($action)
	{
		$rss = $action->rss;
		
		$xml = '<?xml version="1.0" encoding="UTF-8" ?>
		<rss version="2.0">

		<channel>
		<title>' . htmlspecialchars($rss['title']) . '</title>
		<link>' . htmlspecialchars($rss['link']) . '</link>
		<lastBuildDate>' . date('r') . '</lastBuildDate>
		<pubDate>' . date('r') . '</pubDate>';

		foreach ( $rss['items'] as $item )
		{
			$xml .= '<item>
			<title>' . htmlspecialchars($item['title']) . '</title>
			<description><![CDATA[' . $item['description'] . ']]></description>
			<link>' . htmlspecialchars($item['link']) . '</link>
			<guid isPermaLink="false">' . htmlspecialchars($item['guid']) . '</guid>
			<pubDate>' . date('r', $item['ts']) . '</pubDate>
			</item>';
		}

		$xml .= '</channel></rss>';
		
		response::set_header('Content-type', 'text/xml; charset=utf8');
		response::send($xml);
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
		
		if ( $action->layout && !request::is_ajax() )
			$response = self::html_layout($action->layout, $context);
		else
			$response = self::html_view($action->view, $context);
		
		response::set_header('Content-type', 'text/html; charset=utf8');
		response::send($response);
	}
	
	public static function app()
	{
		return defined('PRE') ? PRE : 'app';
	}
	
	public static function tpl($tpl)
	{
		$tpl = str_replace('_', '/' , $tpl);
		
		if ( defined('PRE') )
			$tpl = str_replace(PRE . '/', '', $tpl);
		
		return $tpl;
	}
	
	public static function html_layout($tpl, $context = array())
	{
		foreach ( context::$action as $var => $val ) $$var = $val;
		foreach ( $context as $var => $val ) $$var = $val;
		
		ob_start();
		$view = ROOT . '/' . self::app() . '/layout/' . $tpl . '.phtml';
		
		include $view;
		return ob_get_clean();
	}
	
	public static function html_view($tpl, $context = array())
	{
		foreach ( context::$action as $var => $val ) $$var = $val;
		foreach ( $context as $var => $val ) $$var = $val;
		
		ob_start();
		$view = ROOT . '/' . self::app() . '/actions/' . self::tpl($tpl) . '.phtml';
		
		include $view;
		return ob_get_clean();
	}
	
	public static function html_partial($partial, $context = array())
	{
		$com = explode('/', $partial);
		$tpl = 'partials/' . array_pop($com);
		$tpl = implode('/', $com) . '/' . $tpl;
		
		foreach ( $context as $var => $val ) $$var = $val;
		
		ob_start();
		$view = ROOT . '/' . self::app() . '/actions/' . self::tpl($tpl) . '.phtml';
		
		include $view;
		return ob_get_clean();
	}
	
	public static function exception($e)
	{
		if ( !$_SERVER['SHLVL'] )
		{
			if ( request::is_ajax() )
			{
				if ( !config::get('production') || ( config::get('production') && ($e instanceof pub_exception) ) )
					$message = $e->getMessage();
				else
					$message = 'Извините, у нас произошла ошибка, попробуйте еще раз чуть позже';
				
				echo json_encode(array('exception' => $message));
			}
			else
			{
				if ( $e instanceof no_action_exception )
				{
					header("Status: 404 Not Found");
					include dirname(__FILE__) . '/tpl/404.php';
				}
				else
				{
					include dirname(__FILE__) . '/tpl/exception.php';
				}
			}
		}
	}
}