<?php

final class System{
	private static
		$instances = Array()
	;
	
	public static function get($className, Array $params = Array()){
		if(!isset(self::$instances[$className])){
			self::$instances[$className] = (new ReflectionClass($className))->newInstanceArgs($params);
		}
		return self::$instances[$className];
	}
	
	private function __construct(){}
	private function __clone(){}
}

?>