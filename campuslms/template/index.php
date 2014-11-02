<?php
	$template = DEFAULT_TEMPLATE;

	//Si nécessaire, gérer les templates & les préférences des utilisateurs ici.

	if ($_SESSION['user_id']){
		require(dirname(__FILE__).'/'.$template."/index.php");
	}else{
		require(dirname(__FILE__).'/'.$template."/login.php");
	}
?>