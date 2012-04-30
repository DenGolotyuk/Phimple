<?

class mail
{
	public static $unsub_provider = 'user_unsubs';
	
	public static function send($to, $subject, $view, $context = array())
	{
		if ( !$to ) return;
		if ( is_numeric($to) )
		{
			if ( !$user = users::get($to) ) return;
			if ( !users::allow_mail($to, $context) && config::get('production') ) return;
			
			$email = $user['email'];
		}
		else
		{
			$email = $to;
		}
		
		if ( !filter_var($email, FILTER_VALIDATE_EMAIL) ) return;
		
		if ( self::$unsub_provider && class_exists(self::$unsub_provider) )
		{
			if ( call_user_func(self::$unsub_provider . '::get', $email) ) return;
		}
		
		if ( class_exists('mail_helper') )
			mail_helper::before_sent($to, $subject, $view, $context);
		
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
			mail($email, $subject, $body, $headers);
		
		if ( class_exists('mail_helper') )
			mail_helper::on_sent($to, $subject, $view, $context);
		
		return true;
	}
}