<?php

/*
$curl	= new cURL();
$result = $curl
	->getDatas()
		->attr(CURLOPT_URL,				$url)
		->attr(CURLOPT_RETURNTRANSFER,	1)
		->attr(CURLOPT_CONNECTTIMEOUT,	5)
		
		->attr(CURLOPT_HTTPAUTH,		CURLAUTH_BASIC)
		->attr(CURLOPT_USERPWD,			$user.':'.$passwd)
		->attr(CURLOPT_SSL_VERIFYPEER,	0)
		->attr(CURLOPT_HEADER,			0)
		
		->attr(CURLOPT_POST,			1)
		->attr(CURLOPT_POSTFIELDS,		'xml='.$xml)
		
		->getParent()
	->open()					// curl_open
	->execute()					// curl_setopt & curl_exec
	->close()					// curl_close
	->getResult()
;
*/

// Az összes adat mehet privátba, a cURL::attr ne legyen elérhetõ kintrõl
// Ezt a cuccot megcsináltam már? vagy ezt mire értettem?

class cURL extends Object{
	const
		ERROR_CURL_NOT_ENABLED	= 'cURL module is not enabled',
		KEY_IS_OPENED			= 'opened',
		KEY_CONNECTION			= 'connection',
		KEY_RESULT				= 'result',
		KEY_DATAS				= 'datas',
		KEY_ERROR				= 'error'
	;
	
	private
		$datas
	;
	
	public function __construct(){
		if(!function_exists('curl_version')){
			Error::raise(self::ERROR_CURL_NOT_ENABLED);
		}
		$this->datas = new Attributes();
		$this->datas
			->attr(self::KEY_DATAS,		new Attributes($this))
			->attr(self::KEY_RESULT,	null)
			->attr(self::KEY_IS_OPENED,	false)
		;
	}
	
	public function open(){
		$datas = $this->datas;
		if($datas->attr(self::KEY_IS_OPENED) === false){
			$datas
				->attr(self::KEY_CONNECTION,	curl_init())
				->attr(self::KEY_IS_OPENED,		true)
			;
		}
		
		return $this;
	}
	
	public function close(){
		$datas = $this->datas;
		if($datas->attr(self::KEY_IS_OPENED) === true){
			curl_close($datas->attr(self::KEY_CONNECTION));
			$datas->attr(self::KEY_IS_OPENED, false);
		}
		return $this;
	}
	
	public function execute(){
		$datas = $this->datas;
		if($datas->attr(self::KEY_IS_OPENED) === true){
			$connection = $datas->attr(self::KEY_CONNECTION);
			
			foreach($datas->attr(self::KEY_DATAS)->toArray() as $key => $value){
				curl_setopt($connection, $key, $value);
			}
			$datas
				->attr(self::KEY_RESULT,	curl_exec($connection))
				->attr(self::KEY_ERROR,		curl_error($connection))
			;
		}
		return $this;
	}
	
	public function getResult(){
		return String::toObject($this->datas->attr(self::KEY_RESULT));
	}
	
	public function getError(){
		return $this->datas->attr(self::KEY_ERROR);
	}
	
	public function getDatas(){
		return $this->datas->attr(self::KEY_DATAS);
	}
	
	public function reset(){
		$this->datas
			->attr(self::KEY_RESULT, null)
			->attr(self::KEY_DATAS)
				->flush()
		;
		return $this;
	}
}

?>