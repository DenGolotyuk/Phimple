<?

class generate_project_task
{
	private $name = null;
	private $path = null;
	
	public function execute(array $params = array())
	{
		if ( defined('ROOT') )
			return log::error('Cannot generate new project inside existing one');
		
		$this->path = realpath(getcwd());
		$this->name = basename($this->path);
		
		log::message('Generating project: ' . $this->name);
		
		$this->init_files();
		$this->init_dev();
		$this->init_vcs();
	}

	public function init_files()
	{
		$skeleton = FROOT . '/assets/generate/project';
		$this->copy_dir($skeleton, getcwd());
	}
	
	public function init_dev()
	{
		log::message('Adding to hosts');
		exec('sudo -s -- \'echo "127.0.0.1	' . $this->name . '.dev" >> /etc/hosts\'');
		
		log::message('Setting up nginx vhost');
		exec('sudo ln -s ' . $this->path . '/config/nginx/app.conf /etc/nginx/vhosts/' . $this->name . '.dev.conf');
		
		log::message('Restarting nginx');
		exec('sudo nginx -s reload');
	}
	
	public function init_vcs()
	{
		log::message('Checking for GIT');
		$check = $this->path . '/.git';
		if ( is_dir($check) )
		{
			log::message('Found GIT, committing');
			
			exec('echo "" >> .gitignore');
			exec('echo "/data/cache/*" >> .gitignore');
			exec('echo "/config/env" >> .gitignore');
			
			exec('git add -A');
			exec('git commit --message "Initial structure"');
			exec('git push -u origin master');
		}
		else
		{
			log::message('GIT not found');
		}
	}
	
	protected function copy_dir( $src, $dst )
	{
		$d = opendir($src);
		while ( $f = readdir($d) )
		{
			if ( in_array($f, array('.', '..', '.svn')) ) continue;

			$path = $src . '/' . $f;
			log::message(str_repeat(' ', 3) . $f);

			if ( is_dir($path) )
			{
				mkdir($dst . '/' . $f);
				$this->copy_dir($path, $dst . '/' . $f);
			}
			else
			{
				$content = file_get_contents($path);
				$content = str_replace(
					array('{$name}', '{$path}'),
					array($this->name, $this->path),
					$content
				);

				file_put_contents($dst . '/' . $f, $content);
			}
		}
	}
}