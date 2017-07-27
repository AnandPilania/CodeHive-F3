<?php

namespace App\Models;

use DB\SQL\Schema;

class Profile extends Model {
	protected $table = 'profile',
	$fields = array(
		'uid' => array(
			'belongs-to-one' => 'App\Models\User'
			),
		'username' => array(
			'type' => Schema::DT_VARCHAR128,
			'default' => null
			),
		'privacy' => array(
			'type' => Schema::DT_INT4,
			'default' => 0
			)
		);
}