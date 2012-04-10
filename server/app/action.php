<?

abstract class action
{
	public $layout = 'layout';
	public $view = null;
	
	public function __construct()
	{
		$this->view = str_replace('_action', '', get_class($this));
	}
	
	public function __toString()
	{
		return str_replace('_action', '', get_class($this));
	}
}