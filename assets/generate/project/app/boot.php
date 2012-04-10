<?

define('ROOT', realpath(dirname( __FILE__ ) . '/..'));
define('FROOT', realpath(dirname( __FILE__ ) . '/../../phimple'));

$env_path = ROOT . '/config/env';
if ( is_file($env_path) ) define('ENV', include $env_path);

require_once FROOT . '/server/app/application.php';
application::init();