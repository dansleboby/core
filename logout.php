<?php
	require('campuslms/core/init.php');
	require('campuslms/core/functions.php');

	saveLog('logout');

	$_SESSION = array();
	session_destroy();
	header("location: index.php" ) ; // On renvoie ensuite sur la page d'accueil
?>