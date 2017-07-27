<?php

namespace App\Controllers;

use FAL\LocalFS;
use App\Models\FS;

class EditorController extends Controller {
	protected $model;
	protected $fs;

	public function __construct() {
		parent::__construct();
		$this->model = new FS();
		$this->fs = new LocalFS($this->model->getPath());
	}

	public function getIndex() {
		template('editor', ['fileTree' => array_merge($this->fs->listDir(null, null, true), $this->fs->listDir('/../default/', null, true)), 'projects' => true]);
	}
}