<?

class browser
{
	private $content;
	private $cookie_jar = null;
	
	public function __construct()
	{
		$this->cookie_jar = tempnam('/tmp', 'curl-cooks');
	}
	
	public function __destruct()
	{
		unlink($this->cookie_jar);
	}
	
	public function get($url)
	{
		return $this->content = $this->execute($url);
	}
	
	public function post($url, $fields = array())
	{
		return $this->content = $this->execute($url, array(
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $fields
		));
	}
	
	public function match($reg)
	{
		preg_match($reg, $this->content, $m);
		if ( $m[1] ) return $m[1];
	}
	
	public function execute($url, $params = array())
	{
		$params[CURLOPT_RETURNTRANSFER] = true;
		$params[CURLOPT_COOKIEFILE] = $this->cookie_jar;
		$params[CURLOPT_COOKIEJAR] = $this->cookie_jar;
		
		$c = curl_init($url);
		
		foreach ( $params as $opt => $val )
			curl_setopt ($c, $opt, $val);
		
		return curl_exec($c);
	}
}