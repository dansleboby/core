<?php
	//Inclure les paramètres du site
		require(dirname(__FILE__).'/../../data/settings/required.php');

	$_ORI = array();
	$_ORI['POST'] = array();
	$_ORI['GET'] = array();

	//Activer les erreurs si on est en mode test
	if (SITEMODE == 'test'){
		error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT); 
		ini_set("display_errors", 1); 
	}else{
		error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT); 
		ini_set("display_errors", 0); 
	}

	//Désactiver Magic Quotes
		if (get_magic_quotes_gpc()) {
		    function stripslashes_gpc(&$value){
		        $value = stripslashes($value);
		    }
		    array_walk_recursive($_GET, 'stripslashes_gpc');
		    array_walk_recursive($_POST, 'stripslashes_gpc');
		    array_walk_recursive($_COOKIE, 'stripslashes_gpc');
		    array_walk_recursive($_REQUEST, 'stripslashes_gpc');
		}

	//Configuration des options
		SESSION_NAME("campuslms");
		SESSION_START();

	//Initialiser variable _req
		$_req = Array(
			'module'=>array(),
			'class'=>array(),
			'control'=>array(),
			'js'=>array(),
			'css'=>array()
		);

/*
	Hardcoded for now. It's temporary.
*/

define("NOTE_5STAR", 90);
define("NOTE_4STAR", 80);
define("NOTE_3STAR", 60);
define("NOTE_2STAR", 40);
define("NOTE_1STAR", 0);

?>