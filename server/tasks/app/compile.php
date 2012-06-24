<?

class compile_task
{
	public function execute(array $params = array())
	{
		log::message('Compiling loader...');
		loader::compile();
		if ( $params && ($params[0] == 'loader') ) return;
		
		log::message('Compiling JS/CSS...');
		minifier::compile();
	}
}