<?php
class Dot implements ArrayAccess, Countable, IteratorAggregate, JsonSerializable {
    protected $items = [];
    public function __construct($items = []) {
        $this->items = $this->getArrayItems($items);
    }
    public function add($keys, $value = null) {
        if (is_array($keys)) {
            foreach ($keys as $key => $value) {
                $this->add($key, $value);
            }
        } elseif (is_null($this->get($keys))) {
            $this->set($keys, $value);
        }
    }
    public function all() {
        return $this->items;
    }
    public function clear($keys = null) {
        if (is_null($keys)) {
            $this->items = [];
            return;
        }
        $keys = (array) $keys;
        foreach ($keys as $key) {
            $this->set($key, []);
        }
    }
    public function delete($keys) {
        $keys = (array) $keys;
        foreach ($keys as $key) {
            if ($this->exists($this->items, $key)) {
                unset($this->items[$key]);
                continue;
            }
            $items = &$this->items;
            $segments = explode('.', $key);
            $lastSegment = array_pop($segments);
            foreach ($segments as $segment) {
                if (!isset($items[$segment]) || !is_array($items[$segment])) {
                    continue 2;
                }
                $items = &$items[$segment];
            }
            unset($items[$lastSegment]);
        }
    }
    protected function exists($array, $key) {
        return array_key_exists($key, $array);
    }
    public function flatten($delimiter = '.', $items = null, $prepend = '') {
        $flatten = [];
        if (is_null($items)) {
            $items = $this->items;
        }
        foreach ($items as $key => $value) {
            if (is_array($value) && !empty($value)) {
                $flatten = array_merge(
                    $flatten,
                    $this->flatten($delimiter, $value, $prepend.$key.$delimiter)
                );
            } else {
                $flatten[$prepend.$key] = $value;
            }
        }
        return $flatten;
    }
    public function get($key = null, $default = null) {
        if (is_null($key)) {
            return $this->items;
        }
        if ($this->exists($this->items, $key)) {
            return $this->items[$key];
        }
        if (strpos($key, '.') === false) {
            return $default;
        }
        $items = $this->items;
        foreach (explode('.', $key) as $segment) {
            if (!is_array($items) || !$this->exists($items, $segment)) {
                return $default;
            }
            $items = &$items[$segment];
        }
        return $items;
    }
    protected function getArrayItems($items) {
        if (is_array($items)) {
            return $items;
        } elseif ($items instanceof self) {
            return $items->all();
        }
        return (array) $items;
    }
    public function has($keys) {
        $keys = (array) $keys;
        if (!$this->items || $keys === []) {
            return false;
        }
        foreach ($keys as $key) {
            $items = $this->items;
            if ($this->exists($items, $key)) {
                continue;
            }
            foreach (explode('.', $key) as $segment) {
                if (!is_array($items) || !$this->exists($items, $segment)) {
                    return false;
                }
                $items = $items[$segment];
            }
        }
        return true;
    }
    public function isEmpty($keys = null) {
        if (is_null($keys)) {
            return empty($this->items);
        }
        $keys = (array) $keys;
        foreach ($keys as $key) {
            if (!empty($this->get($key))) {
                return false;
            }
        }
        return true;
    }
    public function merge($key, $value = null) {
        if (is_array($key)) {
            $this->items = array_merge($this->items, $key);
        } elseif (is_string($key)) {
            $items = (array) $this->get($key);
            $value = array_merge($items, $this->getArrayItems($value));
            $this->set($key, $value);
        } elseif ($key instanceof self) {
            $this->items = array_merge($this->items, $key->all());
        }
    }
    public function pull($key = null, $default = null) {
        if (is_null($key)) {
            $value = $this->all();
            $this->clear();
            return $value;
        }
        $value = $this->get($key, $default);
        $this->delete($key);
        return $value;
    }
    public function push($key, $value = null) {
        if (is_null($value)) {
            $this->items[] = $key;
            return;
        }
        $items = $this->get($key);
        if (is_array($items) || is_null($items)) {
            $items[] = $value;
            $this->set($key, $items);
        }
    }
    public function set($keys, $value = null) {
        if (is_array($keys)) {
            foreach ($keys as $key => $value) {
                $this->set($key, $value);
            }
            return;
        }
        $items = &$this->items;
        foreach (explode('.', $keys) as $key) {
            if (!isset($items[$key]) || !is_array($items[$key])) {
                $items[$key] = [];
            }
            $items = &$items[$key];
        }
        $items = $value;
    }
    public function setArray($items) {
        $this->items = $this->getArrayItems($items);
    }
    public function setReference(array &$items) {
        $this->items = &$items;
    }
    public function toJson($key = null, $options = 0) {
        if (is_string($key)) {
            return json_encode($this->get($key), $options);
        }
        $options = $key === null ? 0 : $key;
        return json_encode($this->items, $options);
    }
    public function offsetExists($key) {
        return $this->has($key);
    }
    public function offsetGet($key) {
        return $this->get($key);
    }
    public function offsetSet($key, $value) {
        if (is_null($key)) {
            $this->items[] = $value;
            return;
        }
        $this->set($key, $value);
    }
    public function offsetUnset($key) {
        $this->delete($key);
    }
    public function count($key = null) {
        return count($this->get($key));
    }
    public function getIterator() {
        return new ArrayIterator($this->items);
    }
    public function jsonSerialize() {
        return $this->items;
    }
}