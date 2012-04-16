<?

class mail
{
	public static $unsub_provider = 'user_unsubs';
	
	public static function send($to, $subject, $view, $context = array())
	{
		if ( !$to ) return;
		if (is_numeric($to) )
		{
			if ( !$user = users::get($to) ) return;
			$to = $user['email'];
			
			if ( !$context['important'] && config::get('production') && ($user['last_mail'] > time() - 60*60*12) ) return;
			
		}
		
		if ( !filter_var($to, FILTER_VALIDATE_EMAIL) ) return;
		
		if ( self::$unsub_provider && class_exists(self::$unsub_provider) )
		{
			if ( call_user_func(self::$unsub_provider . '::get', $to) ) return;
		}
		
		if ( $user ) users::save($user['id'], array('last_mail' => time()));
		
		$headers = "From: " . config::get('mail-from') . "\r\n" .
					"Reply-To: ". config::get('mail-from') . "\r\n" .
					"MIME-Version: 1.0\r\n" .
					"Content-Type: text/html; charset=utf-8\r\n";
		
		foreach ( $context as $var => $value ) $$var = $value;
		
		ob_start();
		include ROOT . '/app/mails/layout.phtml';
		$body = ob_get_clean();
		
		if ( !config::get('production') )
			file_put_contents ('/var/www/mail.html', $body);
		else
			mail($to, $subject, $body, $headers);
	}
}