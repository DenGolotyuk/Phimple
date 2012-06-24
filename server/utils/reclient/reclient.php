<?

class reclient
{
	public static function css($data)
	{	
		$instructions = require dirname(__FILE__) . '/css.php';
		
		foreach ( $instructions as $command => $representation )
		{
			preg_match_all("/{$command}:([^;}]+)[;}]/", $data, $m);
			$list = array_unique($m[1]);
			
			foreach ( $list as $match )
			{
				$to_replace = $command . ':' . $match;
				$args = explode(' ', trim($match));
				
				$replacement = $representation;
				$replacement = str_replace('*', $match, $replacement);
				
				foreach ( $args as $num => $val )
				{
					$replacement = str_replace('$' . ($num+1), $val, $replacement);
				}
				
				$data = str_replace($to_replace, $replacement, $data);
			}
		}
		
		return $data;
	}
}