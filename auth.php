<?php

// Bootup the Composer autoloader
include __DIR__ . '/vendor/autoload.php';  

use Mautic\Auth\ApiAuth;

session_start();

$publicKey = '1_41mpiis6e4w0w8c8coo04w44wkk0o8o08ockwoc8ogg04okko4'; 
$secretKey = '1jzl0yhttnms4wo8co8ckcow88wsw4k0gogws44gwkckwgwwko'; 
$callback  = 'http://local.integration.com.br/api.php'; 

// ApiAuth::initiate will accept an array of OAuth settings
$settings = array(
    'baseUrl'          => 'http://local.mautic.com.br',       // Base URL of the Mautic instance
    'version'          => 'OAuth2', // Version of the OAuth can be OAuth2 or OAuth1a. OAuth2 is the default value.
    'clientKey'        => $publicKey,       // Client/Consumer key from Mautic
    'clientSecret'     => $secretKey,       // Client/Consumer secret key from Mautic
    'callback'         => $callback        // Redirect URI/Callback URI for this script
);

/*
// If you already have the access token, et al, pass them in as well to prevent the need for reauthorization

$settings['accessTokenSecret']  = $accessTokenSecret; //for OAuth1.0a
$settings['accessTokenExpires'] = $accessTokenExpires; //UNIX timestamp
$settings['refreshToken']       = $refreshToken;
*/

$initAuth = new ApiAuth();

	// Initiate the auth object
	
	$auth = $initAuth->newAuth($settings);
	
	// Initiate process for obtaining an access token; this will redirect the user to the $authorizationUrl and/or
	// set the access_tokens when the user is redirected back after granting authorization
	
	// If the access token is expired, and a refresh token is set above, then a new access token will be requested
	
	try {
	    if ($auth->validateAccessToken()) {
	
	        // Obtain the access token returned; call accessTokenUpdated() to catch if the token was updated via a
	        // refresh token
	
	        // $accessTokenData will have the following keys:
	        // For OAuth1.0a: access_token, access_token_secret, expires
	        // For OAuth2: access_token, expires, token_type, refresh_token
	
	        if ($auth->accessTokenUpdated()) {
	            $accessTokenData = $auth->getAccessTokenData();
	            $_SESSION['oauth']['accessToken'] = $accessTokenData['access_token'];
	            $_SESSION['oauth']['refreshToken'] = $accessTokenData['refresh_token'];
	        }
	    }
	} catch (Exception $e) {
	    // Do Error handling
	}
