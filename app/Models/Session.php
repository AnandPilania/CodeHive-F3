<?php

namespace Models;

use Model;
use DB\SQL\Schema;

class Session extends \DB\Cortex {
	protected $db = 'DB', $table = 'sessions';
	protected $fieldConf = array(
		'session_id' => array(
			'type' => Schema::DT_VARCHAR512
			),
		'data' => array(
			'type' => Schema::DT_TEXT
			),
		'ip' => array(
			'type' => Schema::DT_VARCHAR128
			),
		'agent' => array(
			'type' => Schema::DT_TEXT
			),
		'stamp' => array(
			'type' => Schema::DT_VARCHAR128
			),
		'desktop' => array(
			'has-one' => array('Models\Desktop', 'session_id')
			)
		);
}