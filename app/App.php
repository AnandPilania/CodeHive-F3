<?php

namespace App;

use Prefab;

class App extends Prefab {
	public $app;
	public $authenticatedUser = false;

	public function __construct() {
		$this->app = f3();
		$this->authenticatedUser = $this->authenticated();
	}

	public function authenticated() {
		return $this->authenticatedUser = $this->app->get('SESSION.USER')?:false;
	}
}