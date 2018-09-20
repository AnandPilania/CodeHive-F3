<?php
namespace Controllers;

use Controller;

class IndexController extends Controller {
	public function getIndex() {
		view('index');
	}

	public function getApiIndex() {
		json(['success' => true]);
	}
}