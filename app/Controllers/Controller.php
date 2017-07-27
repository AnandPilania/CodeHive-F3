<?php

namespace App\Controllers;

abstract class Controller {
	protected $app;

	public function __construct() {
		$this->app = f3();
	}
}