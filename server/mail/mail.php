<?

class mail
{
	public static function send($to, $subject, $view, $context = array())
	{
		$headers = "From: " . strip_tags($_POST['req-email']) . "\r\n" .
					"Reply-To: ". strip_tags($_POST['req-email']) . "\r\n" .
					"MIME-Version: 1.0\r\n" .
					"Content-Type: text/html; charset=utf-8\r\n";
		
		foreach ( $context as $var => $value ) $$var = $value;
		
		ob_start();
		include ROOT . '/app/mails/layout.phtml';
		$body = ob_get_clean();
		
		mail($to, $subject, $body, $headers);
	}
}