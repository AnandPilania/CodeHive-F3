<?php

namespace App\Controllers;
class IndexController extends Controller {
	public function getIndex() {
		template('index');
	}

	public function getApiIndex() {
		echo json_encode(['success' => true]);
	}
}