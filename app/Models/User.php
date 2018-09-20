<?php

namespace Models;

use Model;
use Bcrypt;
use DB\SQL\Schema;

class User extends Model {
	protected $table = 'users';
	protected $fields = array(
		'fName' => array(
			'type' => Schema::DT_VARCHAR128,
			'required' => true,
			'nullable' => false
			),
		'lName' => array(
			'type' => Schema::DT_VARCHAR128,
			'required' => true,
			'nullable' => false
			),
		'email' => array(
			'type' => Schema::DT_VARCHAR128,
			'nullable' => false,
			'required' => true,
			'unique' => true
			),
		'password' => array(
			'type' => Schema::DT_VARCHAR256,
			'nullable' => false,
			'required' => true
			),
		'isActive' => array(
			'type' => Schema::DT_BOOLEAN,
			'default' => false
			),
		'desktop' => array(
			'has-one' => array('Models\Desktop', 'session_id')
			)
		);

	public function set_fName($fName) {
		return ucfirst($fName);
	}
	public function set_lName($lName) {
		return ucfirst($lName);
	}
	public function set_password($pswd) {
		return Bcrypt::instance()->hash($pswd);
	}

	public function create(array $data) {
		$this->reset();
		$this->copyfrom($data, array_keys($this->fieldConf));
		$this->save();
	}

	public function login($id, $password, $remember = true) {
		$user = $this->load(array('_id = :id OR email = :id', array(':id' => $id)));

		if($user->dry()) {
			error(trans('error.auth.not_registered', $id));
			return false;
		}

		if(!Bcrypt::instance()->verify()) {
			error(trans('error.auth.password', $id));
			return false;
		}

		if($remember) {
			$this->app->set('SESSION.UID', $user->_id);
		}

		return true;
	}
}