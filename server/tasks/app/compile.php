<?

class compile_task
{
	public function execute(array $params = array())
	{
		log::message('Compiling loader...');
		loader::compile();
		
		log::message('Compiling JS/CSS...');
		minifier::compile();
	}
}