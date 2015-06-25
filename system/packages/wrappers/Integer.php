<?php

final class Integer extends Number{
	public function __construct($value){
		if($value !== null && (string)$value === (string)(int)$value){
			parent::__construct((int)$value);
		}else{
			Error::raise((new String(self::ERROR_NOT_VALID))->parse(Array(
				'%type' => 'integer'
			))->get());
		}
	}
}

?>