<?php

class DB{
	const
		KEY_HOST					= 'host',
		KEY_DB						= 'db',
		KEY_USER					= 'user',
		KEY_PASSWORD				= 'password',
		KEY_DRIVER					= 'driver',
		
		CONNECTION_STR				= '%driver:host=%host;dbname=%db',
		
		MSG_PDO_ERROR				= 'PDO error: %error',
		
		ERROR_INSTANCE_NOT_EXISTS	= 'The specified PDO instance does not exist ($key = %key)'
	;
	
	private
		$instances,
		$current
	;
	
	public function __call($method, $params){
		return call_user_func_array(Array($this->instances[$this->current], $method), $params);
	}
	
	public function __construct(){
		$this->current = 0;
		$this->instances = new ArrayObject();
		foreach(Config::get(Config::DB, Config::FLAG_WRAP_IN_ARRAY) as $config){
			$this->instances->append($this->connect($config));
		}
	}
	
	public function setActiveInstance($key){
		if(isset($this->instances[$key])){
			$this->current = $key;
		}else{
			Error::raise((new String(self::ERROR_INSTANCE_NOT_EXISTS))->parse(Array(
				'%key'	=> $key
			)));
		}
		return $this;
	}
	
	public function countInstances(){
		return $this->instances->count();
	}
	
	public function getActiveInstanceIndex(){
		return $this->current;
	}
	
	protected function connect($config){
		try{
			$pdo = new PDO(
				(new String(self::CONNECTION_STR))->parse(Array(
					'%driver'	=> $config[self::KEY_DRIVER],
					'%host'		=> $config[self::KEY_HOST],
					'%db'		=> $config[self::KEY_DB]
				))->get(),
				$config[self::KEY_USER],
				$config[self::KEY_PASSWORD]
			);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch(PDOException $e){
			Error::raise((new String(self::MSG_PDO_ERROR))->parse(Array(
				'%error' => $e->getMessage()
			))->get());
		}
		
		return $pdo;
	}
	
	public static function getPGbytea($value){
		return hex2bin(ltrim(stream_get_contents($value), 'x'));	// Ha nincs 5.4-es PHP, akkor pack('H*', $data)
	}
}

?>