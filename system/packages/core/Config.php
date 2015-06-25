<?php

final class Config{
	const
		CLASS_PATH			= 'classpath',
		ENCODING			= 'encoding',
		DB					= 'db',
		SOCKET				= 'socket',
		
		FLAG_WRAP_IN_ARRAY	= 0x01
	;
	
	private static
		$datas		= null
	;
	
	public static function get(){
		$params			= func_get_args();
		$key			= (func_num_args() >= 1 ? array_shift($params) : null);
		list($flags)	= (count($params) ? $params : Array(0));
		
		$data			= (
			$key !== null
			? (isset(self::$datas[$key]) ? self::$datas[$key] : null)
			: self::$datas
		);
		
		if(($flags & self::FLAG_WRAP_IN_ARRAY) !== 0 && !is_array($data)){
			$data = ($data !== null ? Array($data) : Array());
		}
		
		return $data;
	}
	
	public static function set(){
		if(self::$datas === null){
			self::init();
		}
		$params = func_get_args();
		switch(func_num_args()){
			case 0 : {
				Error::raise((new String(Error::MORE_PARAMS_NEEDED))->parse(Array(
					'%function'	=> 'Config::set',
					'%min'		=> 1,
					'%given'	=> 0
				))->get());
			}
			break;
			case 1 : {
				list($data) = $params;
			}
			break;
			default : {
				$key	= null;
				$data	= Array();
				$isKey	= true;
				foreach($params as $value){
					if($isKey === true){
						$key = $value;
					}else{
						$data[$key] = $value;
					}
					$isKey = !$isKey;
				}
			}
		}
		self::$datas = array_merge(self::$datas, $data);
	}
	
	private static function init(){
		self::$datas = Array(
			self::CLASS_PATH	=> '.',
			self::ENCODING		=> 'utf-8'
		);
	}
	
	final private function __construct(){}
	final private function __clone(){}
}

?>