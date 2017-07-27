<?php

function f3() {
	return Base::instance();
}

function db() {
	return f3()->get('DB');
}

function user() {
	return f3()->get('SESSION.UID');
}

function root_path($inner_path = '') {
	return realpath(__DIR__.'/../').$inner_path;
}

function loadConfig($f3, $file = null) {
	if($file) {
		$f3->config(root_path('/config/'.$file));
	}else{
		foreach(glob(root_path('/config/*')) as $file) {
			$f3->config($file);
		}
	}
}

function loadRoutes($f3, $file = null) {
	if($file) {
		$f3->config(root_path('/routes/'.$file));
	}else{
		foreach(glob(root_path('/routes/*')) as $file) {
			$f3->config($file);
		}
	}
}

function view($template, array $params = []) {
	if(!empty($params)) {
		f3()->mset($params);
	}
	f3()->set('content', extension($template, 'htm'));
	echo View::instance()->render('layout/main.htm');
}

function template($template, array $params = []) {
	$f3 = f3();
	$templateWithExtension = extension($template, 'htm');

	if(!empty($params)) {
		$f3->mset($params);
	}
	$f3->set('content', $templateWithExtension);
	echo Template::instance()->render('layout/main.htm');
}

function flash($message, $type = 'success') {
	Flash::instance()->addMessage($message, $type);
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

function trans($key, $params = null) {
	return f3()->format(f3()->get($key), ($params?:''));
}

function error($error) {
	if(null === $error) {return;}
	if(is_array($error)) {
		foreach($error as $err) {
			if(is_array($err)) {
				foreach($err as $e) {
					flash($e, 'danger');
				}
			}else{
				flash($err, 'danger');
			}
		}
	}else{
		flash($error, 'danger');
	}
}

function validates($params, $rules = null) {
	if(is_array($params)) {
		foreach($params as $key => $value) {
			$response[] = validate($key, $value, true);
		}
	}else{
		$response[] = validate($params, $rules, true);
	}

	foreach($response as $key => $value) {
		if(is_null($value)){
			unset($response[$key]);
		}
	}

	if(!empty($response)) {
		error($response);
		return false;
	}

	return true;
}

function validate($param, $rules, $via = false) {
	$response = null;

	if(!str_contains($param, ':')){
		$response[] = $param.'- param:value';
	}

	$explodeRules = explode('|', $rules);
	$explodeParam = explode(':', $param);

	foreach($explodeRules as $rule) {
		$name = $explodeParam[0];
		$value = $explodeParam[1];

		if(empty($explodeParam[1])){
			$response[] = trans('core.validations.empty', $explodeParam[0]);
		}else{
			if(str_contains($rule, ':')) {
				$reExplode = explode(':', $rule);
				if($reExplode[0] === 'min' && mb_strlen($value) < $reExplode[1]) {
					$response[] = $name." must not be less than ".$reExplode[1];
				}

				if($reExplode[0] === 'max' && mb_strlen($value) > $reExplode[1]) {
					$response[] = $name." must not be greater than ".$reExplode[1];
				}

				if('between' === $reExplode[0] && ($value <= $reExplode[1] || $value >= $reExplode[2])) {
					$response[] = $name." must be between ".$reExplode[1]." to ".$reExplode[2];
				}
			}
			if('confirm' === $rule) {
				$post = f3()->get('POST');
				$original = $name;
				$confirm = $name.'_confirmation';

				if(empty($post[$confirm])){
					$response[] = trans('core.validations.empty', $name.'_confirmation');
				}else if($post[$original] !== $post[$confirm]){
					$response[] = $name.' confirmation must be same as '.$name;
				}
			}

			if('email' === $rule && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
				$response[] = trans('core.validations.email', $name);
			}
			if('integer' === $rule && !filter_var($value, FILTER_VALIDATE_INT)) {
				$response[] = trans('core.validations.integer', $name);
			}
			if('boolean' === $rule) {
				$acceptable = [true, false, 0, 1, '0', '1'];
				if(!in_array($value, $acceptable, true)) {
					$response[] = trans('core.validations.required', $name);
				}
			}
			if('digit' === $rule && !preg_match('/[^0-9]/', $value)) {
				$response[] = trans('core.validations.digit', $name);
			}
			if('numeric' === $rule && !is_numeric($value)) {
				$response[] = trans('core.validations.numeric', $name);
			}
			if('string' === $rule && !is_string($value)) {
				$response[] = trans('core.validations.string', $name);
			}
		}
	}

	if($via) {
		return $response;
	}

	if(!empty($response)) {
		error($response);
		return false;
	}

	return true;
}

function str_contains($haystack, $needles) {
    foreach ((array) $needles as $needle) {
        if ($needle != '' && mb_strpos($haystack, $needle) !== false) {
            return true;
        }
    }

    return false;
}

function extension($file, $default = 'json') {
	return $file.'.'.(pathinfo($file, PATHINFO_EXTENSION)?:$default);
}

function generateRandom() {
	return 'base64:'.base64_encode(random_bytes(
            f3()->get('CIPHER') == 'AES-128-CBC' ? 16 : 32
        ));
}