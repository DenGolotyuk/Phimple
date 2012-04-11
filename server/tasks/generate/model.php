<?

class generate_model_task
{
	private $name = null;
	
	public function execute(array $params = array())
	{
		$this->name = $params[0];
		
		if ( !$this->name )
			return log::error('What\'s the name of the model?');
		
		log::message('Generating model: ' . $this->name);
		
		$this->init_files();
	}

	public function init_files()
	{
		$skeleton = FROOT . '/assets/generate/model';
		
		$this->prepare_folders();
		$this->copy_file($skeleton . '/model.php', ROOT . '/lib/model/' . $this->name . '.php');
		$this->copy_file($skeleton . '/base.php', ROOT . '/lib/model/base/' . $this->name . '.php', true);
	}
	
	protected function prepare_folders()
	{
		$prepare = ROOT . '/lib/model/base';
		
		if ( !is_dir($prepare) )
		{
			log::message('Preparing folders...');
			@mkdir($prepare, 0755, true);
		}
	}
	
	protected function copy_file( $src, $dst, $over = false )
	{
		if ( !$over && file_exists($dst) )
		{
			log::message('Model already created, skipping: ' . $dst);
			return;
		}
		
		$content = file_get_contents($src);
		$content = str_replace(
			array('{$name}'), array($this->name), $content
		);
		
		if ( file_exists($dst) && ($existing = file_get_contents($dst)) )
		{
			preg_match_all('/const (.+?) = \'(.+?)\';/', $existing, $m);
			
			foreach ( $m[1] as $i => $name )
			{
				$val = $m[2][$i];
				$content = preg_replace('/const ' . $name . ' = \'(.+?)\';/', 'const ' . $name . ' = \'' . $val . '\';', $content);
			}
		}
		else if ( $over )
		{
			$migrations = ROOT . '/data/migrations/';
			if ( !is_dir($migrations) )
			{
				mkdir ($migrations, 0755, true);
			}
			else
			{
				$files = glob($migrations . '/*');
				foreach ( $files as $file )
				{
					$fname = basename($file);
					$idx = (int)$fname;
					if ( $max < $idx ) $max = $idx;
				}
			}
			
			$migration = $migrations . '/' . ($max + 1) . '.' . $this->name . '.mgr';
			file_put_contents($migration,
			'create ' . $this->name . "\n" .
			'	id: int +ai +pk' . "\n" .
			'	ts: int'
			);
			
			log::message('Generated new migration');
			system("nano " . $migration . " > `tty`");
		}

		file_put_contents($dst, $content);
		log::message('Created: ' . $dst);
	}
}