<?php

// todo: needs refactoring
class File extends Object{
	public static function getMIME($file){
		if(function_exists('finfo_file')){
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$mime = finfo_file($finfo, $file);
			finfo_close($finfo);
			return $mime;
		
		// mimetype extension?
		
		}elseif(function_exists('mime_content_type')){
			return mime_content_type($file);
		}elseif(!stristr(ini_get('disable_functions'), 'shell_exec')){
			$file = escapeshellarg($file);
			$mime = shell_exec('file -bi '.$file);
			return $mime;
		}else{
			return false;
		}
	}
}

?>