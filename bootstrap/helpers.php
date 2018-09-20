<?php
function app($key = NULL, $add = NULL) {
    $app = Base::instance();
    return ($key ? ($add ? (NULL !== $app->get($key) ? $app->get($key).$add : $app->get($key)) : $app->get($key)) : $app);
}
function base_path($inner = '') {
    return realpath(__DIR__.'/../').'/'.$inner;
}
function abort() {
    app()->abort();
}
function status($code = 404) {
    app()->error($code);
}
function reroute($where) {
    app()->reroute($where);
}
function is_api($path = NULL) {
    $path = $path ?: Route::instance()->current();
    if (is_string($path)) {
        return explode('/', $path)[1] === 'api';
    }
    return false;
}
function env($pEnv = NULL) {
    global $env;
    return $pEnv ? $pEnv === $env : $env;
}
function dot($items) {
    return new Dot($items);
}
function assets($path,$type,$group='head',$priority=5,$slot=null,$params=null) {
    Assets::instance()->add($path,$type,$group,$priority,$slot,$params);
}
function assetsJS($path,$priority=5,$group='footer',$slot=null) {
    asset($path, 'js', $group, $priority, $slot);
}
function assetsCSS($path,$priority=5,$group='footer',$slot=null) {
    asset($path, 'css', $group, $priority, $slot);
}
function random($length = 16) {
    $string = '';
    while (($len = strlen($string)) < $length) {
        $size = $length - $len;
        $bytes = random_bytes($size);
        $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
    }
    return $string;
}
function generateRandom() {
    return 'base64:'.base64_encode(random_bytes(
            app()->get('CIPHER') == 'AES-128-CBC' ? 16 : 32
        ));
}
function generateKey($chiper = 'AES-256-CBC') {
    return 'base64:'.generateEncKey($chiper);
}
function generateEncKey($chiper = 'AES-256-CBC') {
    return base64_encode(random_bytes($cipher == 'AES-128-CBC' ? 16 : 32));
}
function generateToken($key = NULL, $length = 40, $algo = 'sha256') {
    return hash_hmac('sha256', random($length), ($key?:app('SECRET')));
}
function generateExpiryDate($ttl) {
    $date = new DateTime();
    $date->add(new DateInterval('PT'.$ttl.'H'));
    return $date->format('Y-m-d H:i:s');
}
function back() {
    global $app;
    if($route = $app->get('ref')) {
        reroute($route);
    }
}
function backWith(array $data) {
    global $app;
    if($route = $app->get('ref')) {
        $app->set('data', $data);
        reroute($route);
    }
}
function view($template, array $params = []) {
    global $app;
    if (!empty($params)) {
        $app->mset($params);
    }
    $app->set('content', extension($template, 'htm'));
    echo Template::instance()->render('layouts/app.htm');
    exit();
}
function json($data, $status = 'success', $code = 200) {
    global $ver;
    header('Content Type: application/json; charset='.app('ECODING')?:'utf-8');
    echo json_encode(array('ver' => $ver, 'code' => $code, 'status' => $status, 'response' => (is_array($data) ?: array($data))));
    exit();
}
function extension($file, $default = 'json') {
    return $file . '.' . (getExtension($file, $default)?:$default);
}
function getExtension($file, $default) {
    return pathinfo($file, PATHINFO_EXTENSION)?:$default;
}
function flash($message, $type = 'success') {
    Flash::instance()->addMessage($message, $type);
}
function trans($key, $params = NULL) {
    global $app;
    return $app->format($app->get($key), ($params ?: ''));
}
function error($error) {
    if (NULL === $error) {
        return;
    }
    if (is_array($error)) {
        foreach ($error as $err) {
            if (is_array($err)) {
                foreach ($err as $e) {
                    flash($e, 'danger');
                }
            } else {
                flash($err, 'danger');
            }
        }
    } else {
        flash($error, 'danger');
    }
}
function dd($params) {
    die(var_dump($params));
}
function _getDBType($type){
    if ($type == 'jig' || $type == 'mongo') {
        return ucfirst($type);
    } elseif (in_array($type, array('sql', 'mysql', 'sqlite', 'pgsql', 'sqlsrv'))) {
        return 'SQL';
    }
    return NULL;
}
function _load($path, $set = true) {
    global $app;
    if(!is_array($path)) {
        $path = array($path);
    }
    foreach($path as $dir) {
        $path = base_path($dir);
        if(is_dir($path)) {
            foreach(glob($path . '/*') as $file) {
                _loader($app, $file, $set);
            }
        }else if(is_file($path)) {
			_loader($app, $path, $set);
		}
    }
}
function _loader($app, $file, $set) {
    if(!Str::contains($file, 'core') && filesize($file) > 0) {
		$ext = getExtension($file, 'ini');
		if($ext === 'ini') {
			$app->config($file);
		}else if($ext === 'php') {
			if($set) {
				$app->mset(include($file));
			}else{
				include($file);
			}
		}
	}
}
function authenticate($app) {
    $token = getBearerToken($app);
    // check if there's a token present
    if ($token) {
        // Get the JSON data that has been encoded (can contain whatever you like)
        // and add that data to the request object that gets passed to the controllers
        if($tokenProperties = (object) json_decode(JWT::decode($token, $app->JWT_SECRET))) {
            // Load into the App class
            (new App()) -> user($tokenProperties);
        } else {
            // Send response when the integrity-check of the Token failed.
            // This will happen if the passed encrypted data is not a valid JSON-string
            // due to a wrong encryption or bad encoding.
            throw new Exception\NotAuthorizedException('Token integrity-check failed!');
        }
    } else {
        // The response when there is no Token present
        throw new Exception\NotAuthorizedException('No Token present!');
    }
}
/**
 * Get the Authorization header from the request Headers
 * @return string $header
 */
function getAuthorizationHeader() {
    $headers = NULL;
    if (isset($_SERVER['Authorization'])) {
        $headers = trim($_SERVER["Authorization"]);
    } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { // Nginx or fast CGI
        $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
    } elseif (function_exists('apache_request_headers')) {
        $requestHeaders = apache_request_headers();
        // Server-side fix for bug in old Android versions (a nice side-effect of
        // this fix means we don't care about capitalization for Authorization)
        $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
        if (isset($requestHeaders['Authorization'])) {
            $headers = trim($requestHeaders['Authorization']);
        }
    }
    return $headers;
}
/**
 * Check and get the Bearer token from the header
 * @return string token
 */
function getBearerToken($app) {
    // Get the headers first.
    $headers = getAuthorizationHeader();
    if (!empty($headers) && preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
        return $matches[1];
    } else {
        // Allow for the usage of the token as a GET parameter
        return ($app->ALLOW_URL_GET_TOKEN && isset($_GET[GET_TOKEN_PARAM])) ? $_GET['token'] : NULL;
    }
}
function user() {
    return app('SESSION.user');
}