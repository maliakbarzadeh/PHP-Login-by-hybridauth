<?php
require 'vendor/autoload.php';
require 'config.php';

use Hybridauth\Hybridauth;

$hybridauth = new Hybridauth($config);
$adapters = $hybridauth->getConnectedAdapters();

if(isset($adapters)){
	if(isset($_SESSION)){
		// destroy the session
		session_destroy();
	}
	$url=$config['callback'] . "?logout={$name}";
	header( "Location: $url" );
}
if(isset($_SESSION)){
	// destroy the session
	session_destroy();
	$url="index.php";
	header( "Location: $url" );
}
?>