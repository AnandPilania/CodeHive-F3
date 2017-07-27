<?php

require(__DIR__.'/../vendor/autoload.php');

$f3 = Base::instance();

loadConfig($f3);
loadRoutes($f3);

$db = $f3->set('DB', new DB\Jig($f3->get('DB_PATH'), DB\Jig::FORMAT_JSON));
$session = new DB\Jig\Session($db, 'sessions', null, 'csrf');

$assets = Assets::instance();
$f3->set('ASSETS.onFileNotFound',function($file) use ($f3){
	echo 'file not found: '.$file;
});

return $f3;