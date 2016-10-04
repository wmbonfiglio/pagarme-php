<?php namespace Pagarme\Core;

class PagarmeObject implements \ArrayAccess, \Iterator {

	protected $_attributes;
	protected $_unsavedAttributes;
	private $_position;

	public function __construct($response = array()) {
		$this->_attributes = Array();
		$this->_unsavedAttributes = new PagarmeSet();
		$this->_position = 0;

		$this->refresh($response);
	}

	public function __set($key, $value) {
		if($key == "") {
			throw new PagarmeException('Cannot store invalid key');
		}

		$this->_attributes[$key] = $value;
		$this->_unsavedAttributes->add($key);
	}

	public function __isset($key) {
		return isset($this->_attributes[$key]);
	}

	public function __unset($key) {
		unset($this->_attributes[$key]);
		$this->_unsavedAttributes->remove($key);
	}

	public function __get($key) {
		if(array_key_exists($key, $this->_attributes))  {
			return $this->_attributes[$key];
		} else {
			return null;
		}
	}

	public function __call($name, $arguments) {
		$var = PagarmeUtil::fromCamelCase(substr($name,3));
		if(!strncasecmp($name, 'get', 3)) {
			return $this->$var;
		}	else if(!strncasecmp($name, 'set',3)) {
			$this->$var = $arguments[0];
		} else {
			throw new PagarmeException('Metodo inexistente '.$name);
		}
	}

	public function rewind() {
		$this->_position = 0;
	}

	public function current() {
		return $this->_attributes[$this->key()];
	}

	public function key() {
		$keys = $this->keys();
		if(isset($keys[$this->_position])) {
			return $keys[$this->_position];
		}
	}

	public function next() {
		++$this->_position;
	}

	public function valid() {
		$keys = $this->keys();
		return isset($keys[$this->_position]);
	}

	public function offsetSet($key, $value) {
		$this->$key = $value;
	}

	public function offsetGet($key) {
		return $this->$key;
	}

	public function offsetExists($key) {
		return array_key_exists($key, $this->_attributes);
	}

	public function offsetUnset($key) {
		unset($this->$key);
	}

	public function keys() {
		return array_keys($this->_attributes);
	}

	public function unsavedArray() {
		$arr = array();


		foreach($this->_unsavedAttributes->toArray() as $a) {
			if($this->_attributes[$a] instanceof Object) {
				$arr[$a] = $this->_attributes[$a]->unsavedArray();
			} else {
				$arr[$a] = $this->_attributes[$a];
			}
		}

		return $arr;
	}

	public static function build($response, $class = null) {
		if(!$class) {
			$class = get_class();
		}
		$class = explode('PagarMe_', $class);
		if ( class_exists('\\Pagarme\\Models\\'.end($class)) ) {
			$class = '\\Pagarme\\Models\\'.end($class);
		} elseif ( class_exists('\\Pagarme\\Transaction\\'.end($class)) ) {
			$class = '\\Pagarme\\Transaction\\'.end($class);
		} elseif ( class_exists('\\Pagarme\\Core\\Pagarme'.end($class)) ) {
			$class = '\\Pagarme\\Core\\Pagarme'.end($class);
		}
		$obj = new $class($response);
		return $obj;
	}

	public function refresh($response) {
		$removed = array_diff(array_keys($this->_attributes), array_keys($response));

		foreach($removed as $k) {
			unset($this->$k);
		}

		foreach($response as $key => $value) {
			$this->_attributes[$key] = PagarmeUtil::convertToPagarMeObject($value);
			$this->_unsavedAttributes->remove($key);
		}

		return $this->_attributes;
	}

	public function serializeParameters() {
		$params = array();
		if ($this->_unsavedAttributes) {
			foreach ($this->_unsavedAttributes as $k) {
				$v = $this->$k;
				if ($v === NULL) {
					$v = '';
				}
				$params[$k] = $v;
			}
		}

		return $params;
	}

	protected function _lsb($method)
	{
		$class = get_class($this);
		$args = array_slice(func_get_args(), 1);
		return call_user_func_array(array($class, $method), $args);
	}
	protected static function _scopedLsb($class, $method)
	{
		$args = array_slice(func_get_args(), 2);
		return call_user_func_array(array($class, $method), $args);
	}

	public function __toJSON()
	{
		if (defined('JSON_PRETTY_PRINT'))
			return json_encode($this->__toArray(true), JSON_PRETTY_PRINT);
		else
			return json_encode($this->__toArray(true));
	}

	public function __toString()
	{
		return $this->__toJSON();
	}

	public function __toArray($recursive=false)
	{
		if ($recursive)
			return PagarmeUtil::convertPagarMeObjectToArray($this->_attributes);
		else
			return $this->_attributes;
	}

}