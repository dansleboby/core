<nav>
	<h1>Nouveau message</h1>
<?php
	echo '<a class="openInRightBar" href="notifications/messages/0">Direction</a>';
	foreach($data['user'] AS $res){
		echo '<a class="openInRightBar" href="notifications/messages/'.$res['id'].'">'.$res['nom'].', '.$res['prenom'].'</a>';
	}
?>
	<a class="hideInSection" href="#">Fermer</a>
</nav>