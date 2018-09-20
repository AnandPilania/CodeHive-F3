<?php
class Repository extends Dot {
    public function __construct(array $items = array()) {
        parent::__construct($items);
    }
    public function keys() {
        return array_keys($this->items);
    }
    public function replace(array $items = array()) {
        $this->set($items);
    }
    public function remove($key) {
        $this->delete($key);
    }
    public function getAlpha($key, $default = '') {
        return preg_replace('/[^[:alpha:]]/', '', $this->get($key, $default));
    }
    public function getAlnum($key, $default = '') {
        return preg_replace('/[^[:alnum:]]/', '', $this->get($key, $default));
    }
    public function getDigits($key, $default = '') {
        return str_replace(array('-', '+'), '', $this->filter($key, $default, FILTER_SANITIZE_NUMBER_INT));
    }
    public function getInt($key, $default = 0) {
        return (int) $this->get($key, $default);
    }
    public function getBoolean($key, $default = false) {
        return $this->filter($key, $default, FILTER_VALIDATE_BOOLEAN);
    }
    public function filter($key, $default = null, $filter = FILTER_DEFAULT, $options = array()) {
        $value = $this->get($key, $default);
        if (!is_array($options) && $options) {
            $options = array('flags' => $options);
        }
        if (is_array($value) && !isset($options['flags'])) {
            $options['flags'] = FILTER_REQUIRE_ARRAY;
        }
        return filter_var($value, $filter, $options);
    }
    public function getIterator() {
        return new ArrayIterator($this->items);
    }
    public function count() {
        return count($this->items);
    }
}