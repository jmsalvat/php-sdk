<?php
session_start('teste');

require '../Meli/meli.php';

$meli = new Meli('APP_ID', 'SECRET_KEY');

if($_GET['code']) {
	
	// If the code was in get parameter we authorize
	$user = $meli->authorize($_GET['code'], 'http://localhost/PHPSDK/examples/example_login.php');
	
	// Now we create the sessions with the authenticated user
	$_SESSION['access_token'] = $user['body']->access_token;
	$_SESSION['expires_in'] = $user['body']->expires_in;
	$_SESSION['refrsh_token'] = $user['body']->refresh_token;

	// We can check if the access token in invalid checking the time
	if($_SESSION['expires_in'] + time() + 1 < time()) {
		try {
		    print_r($meli->refreshAccessToken());
		} catch (Exception $e) {
		  	echo "Exception: ",  $e->getMessage(), "\n";
		}
	}
	
	$params = array('access_token' => $_SESSION['access_token']);

	$body = array('text' => 'Adding new description <strong>html</strong>');

	$response = $meli->put('/items/MLB12343412/description', $body, $params);
	
} else {
	echo '<a href="' . $meli->getAuthUrl('http://localhost/PHPSDK/examples/example_login.php', Meli::$AUTH_URL['MLB']) . '">Login using MercadoLibre oAuth 2.0</a>';
}
