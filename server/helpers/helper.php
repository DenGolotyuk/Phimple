<?

class helper
{
	public static function time($ts)
	{
		$delta = time() - $ts;
		
		if ( $delta <= 60  ) return 'только что';
		if ( $delta <= 60*5  ) return 'пару минут назад';
		if ( $delta <= 60*30  ) return 'полчаса назад';
		if ( $delta <= 60*60  ) return 'час назад';
		if ( $delta <= 60*60*3  ) return 'пару часов назад';
		if ( $delta <= 60*60*12  ) return 'недавно';
		if ( $delta <= 60*60*24  ) return 'вчера';
		if ( $delta <= 60*60*24*7  ) return 'на этой неделе';
		if ( $delta <= 60*60*24*30  ) return 'в этом месяце';
		if ( $delta <= 60*60*24*30*2  ) return 'давно';
	}
	
	public static function url_params($url, $params = array())
	{
		$url = 'http://' . config::get('host') . '/' . $url;
		
		if ( $params )
		{
			foreach ( $params as $k => $v )
			{
				$q[] = "{$k}={$v}";
			}
			
			$url .= '?' . implode('&', $q);
		}
		
		return $url;
	}
	
	public static function transliterate($text, $type = 'en')
	{
		$data=explode(" ",$text); 
		if(count($data)==''){ 
		return ''; 
		} 
		$alphas=array( 
		'yii'=>'ы', 
		'ji'=>'й', 
		'yo'=>'ё', 
		'ya'=>'я', 
		'shc'=>'щ', 
		'sh'=>'ш', 
		'ea'=>'я', 
		'ii'=>'й', 
		'zh'=>'ж', 
		'ch'=>'ч', 
		'iy'=>'ю', 
		'ts'=>'ц', 
		'u'=>'у', 
		'w'=>'в', 
		'v'=>'в', 
		'i'=>'и', 
		'y'=>'у', 
		'd'=>'д', 
		't'=>'т', 
		'b'=>'б', 
		'p'=>'п', 
		'n'=>'н', 
		'f'=>'ф', 
		'\''=>'ь', 
		'\''=>'ъ', 
		'z'=>'з', 
		'l'=>'л', 
		'k'=>'к', 
		's'=>'с', 
		'm'=>'м', 
		'r'=>'р', 
		's'=>'с', 
		'h'=>'х', 
		'j'=>'ж', 
		'g'=>'г', 
		'_'=>'', 
		'a'=>'а',
		'o' => 'о'
		); 
		$total=''; 
		foreach($data as $k=>$v){ 
		if(preg_match("/^[a-zA-Z]*/",$v)){ 
		foreach($alphas as $id=>$value){ 
			if($type=='de'){ 
				if(strcasecmp($v,$id) AND !preg_match("/->/",$v)){ 
					$v=str_replace($id,$value,$v); 
				}elseif(preg_match("/->/",$v)){ 
					$v=str_replace("->","",$v); 
				} 
			}elseif($type='translit'){ 
				if(strcasecmp($v,$value) AND !preg_match("/->/",$v)){ 
					$v=str_replace($value,$id,$v); 
				}elseif(preg_match("/->/",$v)){ 
					$v=str_replace("->","",$v); 
				} 
			} 
		} 
		} 
		$total.=$v." "; 
		} 
		return $total;
	} 
}