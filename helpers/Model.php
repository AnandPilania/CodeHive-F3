<?php

use DB\Cortex;
use DB\SQL\Schema;

class Model extends Cortex {
    protected $app,
        $db = 'DB',
        $defFields = array(
            'created_at' => array(
                'type' => Schema::DT_TIMESTAMP,
                'default' => Schema::DF_CURRENT_TIMESTAMP
            ),
            'updated_at' => array(
                'type' => Schema::DT_TIMESTAMP,
                'default' => '0-0-0 0:0:0'
            )
        ),
        $guard = true;

    public function __construct(Base $app = null) {
        $this->app = $app ?: Base::instance();
        $this->table = $this->app->get('DB_TABLE_PREFIX', '_') . $this->table;
        $this->fieldConf = (property_exists($this, 'fields') && is_array($this->fields)
            ? array_merge($this->defFields, $this->fields) : $this->defFields);

        parent::__construct();

        $this->onload(function() {
            if($this->guard && property_exists($this, 'guarded') && is_array($this->guarded) && count($this->guarded) > 0) {
                foreach($this->guarded as $guard) {
                    unset($this->{$guard});
                }
            }
        });
    }

    public function guard() {
        $this->guard = true;
        return $this;
    }
    public function unguard() {
        $this->guard = false;
        return $this;
    }

    public function set_token() {
        return generateToken();
    }
}