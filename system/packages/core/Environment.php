<?php

final class Environment{
	const
		IS_IN_CLI_MODE					= 'is-in-cli-mode',
		HAS_MULTITHREAD					= 'has-multithread',
		
		ERROR_PROPERTY_DOES_NOT_EXIST	= 'The given property does not exist'
	;
	
	private static
		$datas	= Object::UNDEFINED
	;
	
	public static function get($what){
		if(self::$datas === Object::UNDEFINED){
			self::init();
		}
		
		if(isset(self::$datas[$what])){
			return self::$datas[$what];
		}else{
			Error::raise(self::ERROR_PROPERTY_DOES_NOT_EXIST);
		}
	}
	
	private static function init(){
		self::$datas = Array(
			self::IS_IN_CLI_MODE	=> (
				defined('STDIN')
				|| (
					empty($_SERVER['REMOTE_ADDR'])
					&& !isset($_SERVER['HTTP_USER_AGENT'])
					&& count($_SERVER['argv']) > 0
				)
			),
			self::HAS_MULTITHREAD	=> function_exists('pcntl_fork')
		);
	}
	
	private function __construct(){}
	private function __clone(){}
}

?>