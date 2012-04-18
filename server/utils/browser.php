<?

class browser
{
	private $content;
	private $cookie_jar;
	
	public $convert_charset;
	
	public function __construct()
	{
		$this->cookie_jar = tempnam('/tmp', 'curl-cookies');
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
	
	public function matches($reg)
	{
		preg_match_all($reg, $this->content, $m);

		if ( $m[1] )
		{
			foreach ( $m[1] as $i => $match )
			{
				foreach ( $m as $j => $list )
					$matches[$i][$j] = $m[$j][$i];
			}
		}
		
		return $matches;
	}
	
	public function execute($url, $params = array())
	{
		$params[CURLOPT_RETURNTRANSFER] = true;
		$params[CURLOPT_COOKIEFILE] = $this->cookie_jar;
		$params[CURLOPT_COOKIEJAR] = $this->cookie_jar;
		$params[CURLOPT_REFERER] = $url;
		$params[CURLOPT_FOLLOWLOCATION] = true;
		$params[CURLOPT_USERAGENT] = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:11.0) Gecko/20100101 Firefox/11.0';
		
		$c = curl_init($url);
		
		foreach ( $params as $opt => $val )
			curl_setopt ($c, $opt, $val);
		
		$body = curl_exec($c);
		if ( $this->convert_charset )
			$body = iconv ($this->convert_charset, 'utf-8', $body);
		
		
		
		return $body;
	}
}