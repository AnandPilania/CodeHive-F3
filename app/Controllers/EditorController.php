<?php
namespace Controllers;

use Controller;
use FAL\LocalFS;
use Models\FS;

class EditorController extends Controller {
	protected $model;
	protected $fs;

	public function __construct() {
		parent::__construct();
		$this->model = new FS();
		$this->fs = new LocalFS($this->model->getPath());
	}

	public function getIndex() {
		view('editor', ['fileTree' => array_merge($this->fs->listDir(null, null, true), $this->fs->listDir('/../default/', null, true)), 'projects' => true]);
	}
}