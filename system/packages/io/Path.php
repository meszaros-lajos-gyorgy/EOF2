<?php

class Path{
	const
		URL			= 'url',		// http://www.alma.hu/rackserver/sajt/dinnye/?mennyire=nagyon | $_SERVER
		SERVER		= 'server',		// C:\wamp\www\rackserver\valami\index.php | realpath('.')
		
		PROTOCOL	= 'protocol',	// http
		HOST		= 'host',		// www.alma.hu
		ADDRESS		= 'address',	// 127.0.0.1
		PORT		= 'port',		// 80
		BASE		= 'base',		// /rackserver/		| rackserver\
		PATH		= 'path',		// sajt/dinnye/
		GET			= 'get',		// ?mennyire=nagyon
		
		ROOT		= 'root',		//					| C:\wamp\www\
		
		ELEMENTS	= 'elements'	// PATH szétszedve tömbbe
	;
	
	private
		$elements,
		$datas,
		$DS
	;
	
	public function __construct(){
		$this->DS = new String('/');
	}
	
	public function get($type, $part = Object::UNDEFINED){
		if($this->datas === Object::UNDEFINED){
			$this->setup();
		}
		
		$return = $this->datas->attr($type);
		if($part !== Object::UNDEFINED){
			$return = $return->attr($part);
		}
		return clone $return;
	}
	
	private function setup(){
		$reqURI		= new StringSet($_SERVER['REQUEST_URI']);
		$thisDir	= $this->realpath('.');
		$docRoot	= new StringSet($_SERVER['DOCUMENT_ROOT']);
		$base		= $thisDir->copy()->groupWith($reqURI, StringSet::INTERSECT);
		$req		= $reqURI->split('?');
		$path		= $base->copy()->groupWith($req->offsetGet(0), StringSet::RELATIVE_COMPLEMENT_A);
		
		$elements	= $path->copy()->trim($this->DS, String::RIGHT)->split($this->DS);
		if($elements->offsetGet(0)->equals('')){
			$elements->offsetUnset(0);
		}
		
		$this->datas = new Attributes($this);
		$this->datas
			->attr(self::URL, (new Attributes($this->datas))
				->attr(self::PROTOCOL,		new StringSet('http'.(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 's' : '')))
				->attr(self::HOST,			new StringSet($_SERVER['HTTP_HOST']))
				->attr(self::ADDRESS,		new StringSet($_SERVER['SERVER_ADDR']))
				->attr(self::PORT,			new Integer($_SERVER['SERVER_PORT']))
				->attr(self::BASE,			$base)
				->attr(self::PATH,			$path)
				->attr(self::GET,			$reqURI->strpos('?') !== false ? $req->offsetGet(1) : new StringSet(''))
			)
			->attr(self::SERVER, (new Attributes($this->datas))
				->attr(self::ROOT,			$docRoot)
				->attr(self::BASE,			$docRoot->copy()->groupWith($thisDir, StringSet::RELATIVE_COMPLEMENT_A))
			)
			->attr(self::ELEMENTS,		$elements)
		;
	}
	
	public function realpath($path){
		return (new StringSet(realpath(String::isSameObject($path) ? $path->get() : $path)))
			->parse(Array('\\' => $this->DS->get()))
			->unTrim($this->DS, String::RIGHT)
		;
	}
	
	public function dirname($file){
		return (new StringSet(dirname(String::isSameObject($file) ? $file->get() : $file)))
			->parse(Array('\\' => $this->DS->get()))
			->unTrim($this->DS, String::RIGHT)
		;
	}
}

?>