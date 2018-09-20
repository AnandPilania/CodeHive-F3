<?php
$dir = __DIR__;
require $dir.'/../vendor/autoload.php';
require $dir.'/helpers.php';

$app = Base::instance();
$app->config($dir.'/config.ini;');
_load(array('config', 'routes'));
$ver = $app->get('APP.VER');
$env = strtolower($app->ENV);
$isApi = is_api();

$app->mset(array(
    'AUTOLOAD' => '../app/',
    'DEBUG' => $env === strtolower($app->DEBUG_ENV) ? 3 : 0
));

$db_type = strtoupper($app->DB_TYPE);
if(isset($db_type)) {
    switch($db_type) {
        case 'JIG':
            $app->set('DB', new DB\Jig($app->DB_PATH, DB\Jig::FORMAT_JSON));
            break;
        case 'SQL':
        case 'MYSQL':
            $app->set('DB', new DB\SQL('mysql:host='.$app->DB_HOST.';port='.$app->DB_PORT.';dbname='.$app->DB_PREFIX.$app->DB_NAME, $app->DB_USER, $app->DB_PSWD));
            break;
        case 'PGSQL':
            $app->set('DB', new DB\SQL('pgsql:host='.$app->DB_HOST.';dbname='.$app->DB_PREFIX.$app->DB_NAME, $app->DB_USER, $app->DB_PSWD));
            break;
        case 'SQLSRV':
            $app->set('DB', new DB\SQL('sqlsrv:SERVER='.$app->DB_HOST.';Database='.$app->DB_PREFIX.$app->DB_NAME, $app->DB_USER, $app->DB_PSWD));
            break;
        case 'SQLITE':
            $app->set('DB', new DB\SQL('sqlite:'.$app->DB_PATH));
            break;
        case 'MONGO':
            $app->set('DB', new DB\Mongo('mongodb://'.$app->DB_HOST.':'.$app->DB_PORT, $app->DB_PREFIX.$app->DB_NAME));
            break;
    }
}

$type = _getDBType($db_type);
if ($app->CSRF && $app->DB && ('Jig' == $type || 'SQL' == $type || 'Mongo' == $type)) {
    $session = 'DB\\'.$type.'\\Session';
    $nsession = new $session($app->DB, 'SESSIONS', null, 'CSRF');
} elseif ($app->CSRF) {
    $nsession = new Session(null, 'CSRF');
} else {
    if ($app->DB && ($type == 'Jig' || $type == 'Mongo' || $type == 'SQL')) {
        $session = str_ireplace('/', '', 'DB\/'.$type.'\Session');
        $nsession = new $session($app->DB);
    } else {
        $nsession = new Session();
    }
}
$app->set('SESSION', $nsession);

if(!$isApi) {
    if('dev' === $env) {
        Falsum\Run::handler($app->DEBUG != 3);
        $app->route('GET @reloadr: /reloadr', 'Controller->reloadr');
    }else{
        $app->set('ONERROR', 'Controllers\Controller->error');
        Assets::instance();
        $app->set('ASSETS.onFileNotFound', function ($file) {
            echo 'file not found: '.$file;
        });
    }
}

if(!file_exists(base_path('storage/app/installed'))) {
    if($isApi) {
        json('App is not installed yet!', 'error');
    } else{
        $app->route('GET @install: /install', 'Controllers\IntallController->install');
    }
}

return $app;
