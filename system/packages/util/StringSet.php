<?php

/**
 * Another level of wrapping for Strings, which allows all type of set operations to be made with other Strings.
 * 
 * @package util
 */
class StringSet extends String{
	const
		NONE                  = 0, // ( ( ) )
		RELATIVE_COMPLEMENT_A = 1, // ( ( )x)
		INTERSECT             = 2, // ( (x) )
		B                     = 3, // ( (x)x)
		RELATIVE_COMPLEMENT_B = 4, // (x( ) )
		SYMMETRIC_DIFFERENCE  = 5, // (x( )x)
		A                     = 6, // (x(x) )
		UNION                 = 7  // (x(x)x)
	;
	
	private
		$cache                = Array()
	;
	
	public function __construct($a){
		if(String::isSameObject($a, true)){
			$value    = $a->get();
			$encoding = $a->getEncoding();
		}else{
			$value    = $a;
			$encoding = String::getBaseEncoding();
		}
		parent::__construct($value, $encoding);
	}
	
	public function overlap($b){
		$b = String::toObject($b);
		if($this->length() > 0 && $b->length() > 0){
			if(!$this->equals($b)){
				if(!($this->length() < $b->length() && (($pos = $b->strpos($this)) !== false) && ($pos === 0))){
					if($this->length() > $b->length() && (($pos = $this->strpos($b)) !== false) && ($pos === $this->length() - $b->length())){
						$this->set($b->get());
					}else{
						$got = false;
						for($i = min($this->length(), $b->length()); $i >= 1; $i--){
							$tmp = $this->substr(-$i);
							if($tmp->equals($b->substr(0, $i))){
								$this->set($tmp->get());
								$got = true;
								break;
							}
						}
						if($got === false){
							$this->set('');
						}
					}
				}
			}
		}else{
			$this->set('');
		}
		return $this;
	}
	
	public function groupWith($b, $operation, $store = false){
		if(!isset($this->cache[$operation])){
			$b = String::toObject($b);
			
			switch((int)$operation){
				case self::RELATIVE_COMPLEMENT_A : {
					$result = $b->substr($this->groupWith($b, self::INTERSECT, true)->length());
				}
				break;
				case self::INTERSECT : {
					$result = $this->overlap($b);
				}
				break;
				case self::B : {
					$result = $b;
				}
				break;
				case self::RELATIVE_COMPLEMENT_B : {
					$result = $this->substr(0, -$this->groupWith($b, self::INTERSECT, true)->length());
				}
				break;
				case self::SYMMETRIC_DIFFERENCE : {
					$result = $this->groupWith($b, self::RELATIVE_COMPLEMENT_B, true)->add($this->groupWith($b, self::RELATIVE_COMPLEMENT_A, true));
				}
				break;
				case self::A : {
					$result = $this;
				}
				break;
				case self::UNION : {
					$result = $this->add($this->groupWith($b, self::RELATIVE_COMPLEMENT_A, true));
				}
				break;
				default : {
					$result = new String('');
				}
			}
			
			$this->cache[$operation] = $result;
		}else{
			$result = $this->cache[$operation];
		}
		
		if(!$store){
			$this->set($result->get());
			$this->cache = Array();
			$result = $this;
		}
		
		return $result;
	}
}

?>