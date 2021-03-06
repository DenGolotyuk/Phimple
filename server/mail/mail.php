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
			
			if ( method_exists('mail_helper', 'allow') )
				if ( !mail_helper::allow($email, $context) ) return;
		}
		
		if ( !filter_var($email, FILTER_VALIDATE_EMAIL) ) return;
		
		if ( self::$unsub_provider && class_exists(self::$unsub_provider) )
		{
			if ( call_user_func(self::$unsub_provider . '::get', $email) ) return;
		}
		
		if ( method_exists('mail_helper', 'before_sent') )
			mail_helper::before_sent($to, $subject, $view, $context);
		
		if ( $user ) users::save($user['id'], array('last_mail' => time()));
		
		$headers = "From: " . config::get('mail-from') . "\r\n" .
					"Reply-To: ". config::get('mail-from') . "\r\n" .
					"MIME-Version: 1.0\r\n" .
					"X-Mailru-Msgtype: {$view}\r\n" .
					"Content-Type: text/html; charset=utf-8\r\n";
		
		foreach ( $context as $var => $value ) $$var = $value;
		
		ob_start();
		include ROOT . '/app/mails/layout.phtml';
		$body = ob_get_clean();
		
		if ( !config::get('production') )
			file_put_contents ('/var/www/mail.html', $body);
		else if ( config::get('mail_transport') == 'swift' )
			self::send_swift($email, $subject, $body);
		else
			mail($email, $subject, $body, $headers);
			
		if ( $debug_file ) file_put_contents($debug_file, $body);

		if ( method_exists('mail_helper', 'on_sent') )
			mail_helper::on_sent($to, $subject, $view, $context);
		
		return true;
	}
	
	private static function send_swift($to, $subject, $body)
	{
		try
		{
			require_once FROOT . '/server/mail/swift/swift_required.php';
			
			$mailer = Swift_Mailer::newInstance(Swift_SmtpTransport::newInstance());

			$message = Swift_Message::newInstance()
			->setFrom(array(config::get('mail-from') => config::get('site_title')))
			->setTo($to)
			->setSubject($subject)
			->setBody($body, 'text/html', 'utf-8')
			->setReturnPath(config::get('mail-from'));

			$headers = $message->getHeaders();
			$headers->addTextHeader('X-Report-Abuse-To', config::get('mail-from'));

			$mailer->send($message);
			$mailer->getTransport()->stop();
			
			unset($mailer);
		}
		catch (Exception $e)
		{
			error_log($e);
			return false;
		}
	}
}