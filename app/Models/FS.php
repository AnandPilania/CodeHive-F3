<?php

namespace App\Models;

use DB\SQL\Schema;

class FS extends Model {
	protected $id = null, $path = null, $user = null, $table = 'desktop', $fields = array();

	public function __construct() {
		$this->user = user();
		$this->fields = array_merge(array('session_id' => array('belongs-to-one' => ($user?'App\Models\User':'App\Models\Session'))), $this->fields);
		parent::__construct();

		$this->id = $this->user?:(new Session())->load(array('ip = ? AND agent = ?', $this->app->IP, $this->app->AGENT))->last()->_id;
		$this->path = root_path('/storage/root/'.$this->id);
		if($this->load(array('session_id = ?', $this->id))->dry()) {
			$this->newSession($this->id);
		}else{
			flash('Session restored!');
		}
	}

	public function getID() {
		return $this->id;
	}

	public function getPath() {
		return $this->path;
	}

	private function newSession($id) {
		if(!is_dir($this->path)){mkdir($this->path);}
		$this->reset();
		$this->copyfrom(array('session_id' => $id), array_keys($this->fieldConf));
		$this->save();
	}
}