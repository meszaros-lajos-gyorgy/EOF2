<?php

final class Autoload{
	const
		ERROR_CLASS_NOT_FOUND	= 'Class %class not found in folder/subfolder of %path'
	;
	
	public static function init(){
		spl_autoload_register(function($class){
			self::load($class);
		});
	}
	
	public static function load($class){
		$class				= String::toObject($class);
		$classPath			= Config::get(Config::CLASS_PATH, Config::FLAG_WRAP_IN_ARRAY);
		$fileNameToCompare	= $class->copy();
		$fileNameToCompare->toLower()->add('.php');
		
		foreach($classPath as $path){
			$iterator = new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator($path), 
				RecursiveIteratorIterator::SELF_FIRST
			);
			foreach($iterator as $file){
				if(strtolower($file->getFilename()) == $fileNameToCompare){
					include((string)$file);
				}
			}
		}
		
		if(!class_exists($class, false) && !interface_exists($class, false)){
			Error::raise((new String(self::ERROR_CLASS_NOT_FOUND))->parse(Array(
				'%class'	=> $class,
				'%path'		=> implode(', ', $classPath)
			))->get());
		}
	}
	
	private function __construct(){}
	private function __clone(){}
}

?>