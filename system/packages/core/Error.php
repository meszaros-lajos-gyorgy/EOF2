<?php

final class Error{
	const
		MORE_PARAMS_NEEDED = '%function expects at least %min parameters, %given given.'
	;
	
	public static function raise($exception){
		throw new Exception($exception);
	}
	
	private function __construct(){}
	private function __clone(){}
}

?>