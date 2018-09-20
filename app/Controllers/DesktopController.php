<?php
namespace Controllers;

use Controller;
use FAL\LocalFS;
use Models\FS;

class DesktopController extends Controller {
	protected $model;
	protected $fs;

	public function __construct() {
		parent::__construct();
		$this->model = new FS();
		$this->fs = new LocalFS($this->model->getPath());
	}

	public function getIndex() {
		view('desktop', ['desktopItems' => array_merge($this->fs->listDir(), $this->fs->listDir('/../default/'))]);
	}
}