<?php

class Attributes{
	private
		$parent,
		$props
	;
	
	public function __construct($parent = Object::UNDEFINED){
		$this->props = new ArrayObject();
		if($parent !== Object::UNDEFINED){
			$this->parent = $parent;
		}
	}
	
	public function attr(){
		$argNum = func_num_args();
		if($argNum < 1){
			Error::raise((new String(Error::MORE_PARAMS_NEEDED))->parse(Array(
				'%function'	=> get_called_class().'::'.__FUNCTION__.'()',
				'%min'		=> 1,
				'%given'	=> $argNum,
			))->get());
		}else{
			$args = func_get_args();
			if($argNum === 1){
				if($this->attrExists($args[0])){
					return $this->props->offsetGet($args[0]);
				}else{
					return Object::UNDEFINED;
				}
			}else{
				$this->props->offsetSet($args[0], $args[1]);
				return $this;
			}
		}
	}
	
	public function removeAttr($key){
		$this->props->offsetUnset($key);
		return $this;
	}
	
	public function attrExists($key){
		return $this->props->offsetExists($key);
	}
	
	public function toArray(){
		return $this->props->getArrayCopy();
	}
	
	public function getParent(){
		return $this->parent;
	}
	
	public function length(){
		return $this->props->count();
	}
}

?>