<?php

/**
 * A wrapper object for strings, which comes with a lot of functionality
 * 
 * @package wrappers
 */
class String extends Wrapper{
	const
		LEFT	= 0,
		RIGHT	= 1,
		BOTH	= 2
	;
	private
		$encoding
	;
	
	public function __construct($value = '', $encoding = null){
		$this->setEncoding($encoding === null ? self::getBaseEncoding() : $encoding);
		parent::__construct($value);
	}
	
	public function length(){
		return mb_strlen($this->get(), $this->getEncoding());
	}
	public function toLower(){
		$this->set(mb_convert_case($this->get(), MB_CASE_LOWER, $this->getEncoding()));
		return $this;
	}
	public function toUpper(){
		$this->set(mb_convert_case($this->get(), MB_CASE_UPPER, $this->getEncoding()));
		return $this;
	}
	public function trim($string = null, $target = self::BOTH){
		$function = ($target === self::LEFT ? 'l' : ($target === self::RIGHT ? 'r' : '')).'trim';
		return $this->set($function($this->get(), String::toObject($string === null ? " \t\n\r\0\x0B" : $string)->get()));
	}
	public function unTrim($string, $target = self::BOTH){
		$this->trim($string, $target);
		switch($target){
			case self::LEFT : {
				$this->prepend($string);
			}
			break;
			case self::RIGHT : {
				$this->add($string);
			}
			break;
			case self::BOTH : {
				$this
					->prepend($string)
					->add($string)
				;
			}
			break;
		}
		return $this;
	}
	public function strpos($needle, $offset = 0){
		return mb_strpos($this->get(), String::toObject($needle)->get(), $offset, $this->getEncoding());
	}
	public function substr($start, $end = null){
		$haystack = $this->copy();
		if($end === null){
			$end = $haystack->length();
		}
		$haystack->set(mb_substr($haystack->get(), $start, $end, $haystack->getEncoding()));
		return $haystack;
	}
	
	public function split($separator){
		$parts = explode($separator, $this->get());
		foreach($parts as $key => $value){
			$parts[$key] = new self($value);
		}
		return new ArrayObject($parts);
	}
	
	public function add($value){
		$this->set($this->get().String::toObject($value)->get());
		return $this;
	}
	public function prepend($value){
		$this->set(String::toObject($value)->get().$this->get());
		return $this;
	}
	
	public function replace(Array $rules = Array()){
		if(count($rules) > 0){
			foreach($rules as $needle => $replacement){
				$needle				= new String($needle);
				$needleLen			= $needle->length();
				$replacement		= new String($replacement);
				$replacementLen		= $replacement->length();
				$pos				= $this->strpos($needle);
				while($pos !== false){
					$this->set(
						$this
							->substr(0, $pos)
							->add($replacement)
							->add($this->substr($pos + $needleLen))
							->get()
					);
					$pos = $this->strpos($needle, $pos + $replacementLen);
				}
			}
		}
		return $this;
	}
	
	public function parse(Array $params = Array()){
		if(count($params) >= 0){
			foreach($params as $key => $value){
				$params[$key] = (string)$value;
			}
			$this->replace($params);
		}
		return $this;
	}
	
	public function explodeToChars(){
		return preg_split('//u', $this->get(), -1, PREG_SPLIT_NO_EMPTY);
	}
	
	public function getEncoding(){
		return $this->encoding;
	}
	protected function setEncoding($encoding){
		$this->encoding = $encoding;
	}
	
	public static function setBaseEncoding($encoding){
		mb_internal_encoding($encoding);
	}
	public static function getBaseEncoding(){
		return mb_internal_encoding();
	}
	
	public static function toObject($a, $primitiveType = 'string', Array $params = Array()){
		return parent::toObject($a, $primitiveType, $params);
	}
}

?>