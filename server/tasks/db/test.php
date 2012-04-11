<?

class db_test_task
{

	public function execute(array $params = array())
	{
		log::message('Testing time on DB');
		$time = mysql::col('SELECT NOW()');
		log::message('Time is: ' . $time);
		log::message('DB seems to work fine');
	}

}