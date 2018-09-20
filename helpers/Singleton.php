<?php
trait Singleton {
	protected static $instance;
	public static function instance() {
		if(!self::$instance) {
			self::$instance = new static;
		}
		return self::$instance;
	}
}