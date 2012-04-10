<? return array(
	'box-shadow' =>
			'-moz-box-shadow: 0 0 $1 $2;
			-webkit-box-shadow: 0 0 $1 $2;
			box-shadow: 0 0 $1 $2;',
	
	'border-radius' =>
			'border-radius: $1;
			-webkit-border-radius: $1;
			-moz-border-radius: $1;',
	
	'background-gradient' =>
			'background-image: -webkit-gradient( linear, left bottom, left top, color-stop(0.06, $1), color-stop(0.53, $2) );
			background-image: -moz-linear-gradient( center bottom, $1 6%, $2 53% );
			background-image: -o-linear-gradient( bottom, $1 6%, $2 53% );',
	
	'transition' =>
			'-webkit-transition: *;
			-moz-transition: *;
			transition: *;'
);