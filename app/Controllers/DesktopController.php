<?php

namespace App\Controllers;

use FAL\LocalFS;
use App\Models\Desktop;

class DesktopController extends Controller {
	protected $model;
	protected $fs;

	public function __construct() {
		parent::__construct();
		$this->model = new Desktop();
		$this->fs = new LocalFS($this->model->getPath());
	}

	public function getIndex() {
		template('desktop', ['desktopItems' => array_merge($this->fs->listDir(), $this->fs->listDir('/../default/'))]);
	}
}