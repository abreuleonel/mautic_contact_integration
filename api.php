<?php
session_start();
// Bootup the Composer autoloader
include __DIR__ . '/vendor/autoload.php';  

use Mautic\Auth\ApiAuth;
use Mautic\MauticApi;

$publicKey = '1_41mpiis6e4w0w8c8coo04w44wkk0o8o08ockwoc8ogg04okko4';
$secretKey = '1jzl0yhttnms4wo8co8ckcow88wsw4k0gogws44gwkckwgwwko';
$callback  = 'http://local.integration.com.br/api.php';
$apiUrl = 'http://local.mautic.com.br';

// ApiAuth::initiate will accept an array of OAuth settings
$settings = array(
		'baseUrl'       => $apiUrl,       // Base URL of the Mautic instance
		'version'       => 'OAuth2', // Version of the OAuth can be OAuth2 or OAuth1a. OAuth2 is the default value.
		'clientKey'     => $publicKey,       // Client/Consumer key from Mautic
		'clientSecret'  => $secretKey,       // Client/Consumer secret key from Mautic
		'callback'      => $callback,        // Redirect URI/Callback URI for this script
		'accessToken'	=> $_SESSION['oauth']['accessToken'],
		'refreshToken'	=> $_SESSION['oauth']['refreshToken']
		
);



if(isset($_GET['state'])) {
	$_SESSION['oauth']['state'] = $_GET['state'];	
	
	$initAuth = new ApiAuth();
	$auth = $initAuth->newAuth($settings);
	
	$api = new MauticApi();
	$contactApi = $api->newApi('contacts', $auth, $apiUrl);
	
	echo '<pre>';
	print_r($contactApi->getList()); exit;
}