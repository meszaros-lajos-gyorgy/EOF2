<?php

class Object extends stdClass{
	const
		UNDEFINED			= null,
		
		ERROR_IS_NOT_TYPE	= 'The given parameter is not a %type'
	;
	private
		$uuid		= false
	;
	
	public function __construct(){
		$this->getUUID();
	}
	
	public function getUUID(){
		if($this->uuid === false){
			$this->uuid = uniqid();
		}
		return $this->uuid;
	}
	
	public function copy(){
		return clone $this;
	}
	
	public static function toObject($a, $primitiveType = self::UNDEFINED, Array $params = Array()){
		$thisClassName = get_called_class();
		if(!self::isSameObject($a, true)){
			if($primitiveType !== self::UNDEFINED){
				if(gettype($a) === $primitiveType){
					array_unshift($params, $a);
					$a = (new ReflectionClass($thisClassName))->newInstanceArgs($params);
				}else{
					Error::raise((new String(self::ERROR_IS_NOT_TYPE))->parse(Array(
						'%type'	=> $primitiveType
					))->get());
				}
			}
		}
		
		return $a;
	}
	
	public static function isSameObject($a, $checkSubClass = false){
		$thisClassName = get_called_class();
		return (
			gettype($a) === 'object'
			&& (
				get_class($a) === $thisClassName
				|| (
					$checkSubClass
					&& is_subclass_of($a, $thisClassName)
				)
			)
		);
	}
}

?>