<?php

/**
 * Abstract ancient of all type wrapper objects. A wrapper object wraps a single variable into an object
 * 
 * @package wrappers
 */
abstract class Wrapper extends Object{
	private $value;
	
	/**
	 * @constructor
	 * 
	 */
	public function __construct($value){
		parent::__construct();
		$this->set($value);
	}
	public function __toString(){
		return (string)$this->get();
	}
	
	protected function set($value){
		$this->value = $value;
		return $this;
	}
	public function get(){
		return $this->value;
	}
	
	public function equals($other){
		return $this->get() === (Wrapper::isSameObject($other, true) ? $other->get() : $other);
	}
}

?>