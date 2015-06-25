<?php

$reqs = Array(
	'core/Object',
	'wrappers/Wrapper',
	'wrappers/String',
	'core/Error',
	'core/Config',
	'core/Autoload'
);

foreach($reqs as $req){
	require('packages/'.$req.'.php');
}

$config		= Array();
$content	= file_get_contents('config.json');
if($content !== false){
	$config = json_decode($content, true);
	if($config === null) $config = Array();
}

Config::set($config);
Autoload::init();

if(version_compare(phpversion(), '5.4', '<')){
	Error::raise('PHP 5.4 is required to run EOF2');
}

?>