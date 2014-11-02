<?php
	if ($_GET['qs'][4] == 'save'){
		echo "<h2>Vos modifications sont sauvegardées.</h2>";
//		echo "<p>Pour en modifier le contenue, cliquez sur la leçon dans la fenêtre du cours.</p>";
	}else{
		echo "<h2>Votre leçon est sauvegardée.</h2>";
		echo "<p>Pour en modifier le contenue, cliquez sur la leçon dans la fenêtre du cours.</p>";
	}
?>