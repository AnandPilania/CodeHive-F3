<?php

namespace App\Models;

use DB\SQL\Schema;
use DB\Cortex;

abstract class Model extends Cortex {
	protected $app;
	protected $db = 'DB';
	protected $fieldConf = array(
		'created_at' => array(
			'type' => Schema::DT_TIMESTAMP,
			'default' => Schema::DF_CURRENT_TIMESTAMP
			),
		'updated_at' => array(
			'type' => Schema::DT_TIMESTAMP,
			'default' => '0-0-0 0:0:0'
			),
		'deleted_at' => array(
			'type' => Schema::DT_TIMESTAMP,
			'default' => '0-0-0 0:0:0'
			)
		);

	public function __construct() {
		if(property_exists($this, 'fields')) {
			$this->fieldConf = array_merge($this->fields, $this->fieldConf);
		}

		parent::__construct();
		$this->app = f3();
	}
}