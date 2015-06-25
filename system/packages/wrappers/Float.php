<?php

final class Float extends Number{
	public function __construct($value){
		if($value !== null && (string)$value === (string)(float)$value){
			parent::__construct((float)$value);
		}else{
			Error::raise((new String(self::ERROR_NOT_VALID))->parse(Array(
				'%type' => 'float'
			))->get());
		}
	}
}

?>