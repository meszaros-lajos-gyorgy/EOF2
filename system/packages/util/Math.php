<?php

class Math extends Object{
	public function random(){
		return call_user_func_array('mt_rand', func_get_args());
	}
}

?>