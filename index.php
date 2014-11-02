<?php
	//Inclure le core!
	if (@file_exists('campuslms/core/core.php')){
		require('campuslms/core/core.php');
	}else{
		exit('<div style="width:100%;height:100%;margin:0;padding:0;background:url(campuslms/template/default/images/sidelogo.png) no-repeat center 100px rgb(1,145,214);color:#FFFFFF;text-align:center;line-height:500px;font-family:sans-serif;">Une erreur est survenue. Veuillez r&eacute;essayer.</div>');
	}
?>