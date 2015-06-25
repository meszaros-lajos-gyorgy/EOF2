<?php

class Socket extends Object{
	const
		ADDRESS	= "address",
		PORT	= "port"
	;
	private
		$address,
		$port
	;
	
	public function __construct(){
		$config = Config::get(Config::SOCKET);
	}
	public function run(){
		// Environment::get(ENVIRONMENT::HAS_MULTITHREAD)
		// http://www.electrictoolbox.com/article/php/process-forking/
		
		
	}
	
	
}

?>