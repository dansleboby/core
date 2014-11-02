<div class="fullPane onLoad onUnload" data-onload="msgDaemon_start" data-onunload="msgDaemon_stop">
	<header>
		<?php echo $data['user']['nom'].(($data['user']['prenom'])?', '.$data['user']['prenom']:''); ?>
	</header>
	<form method="post">
		<textarea id="theMessage" name="theMessage" class="bindReturn" data-action="sendMessage" data-to="<?php echo $_POST['id_user']; ?>" placeholder="Entrez votre message"></textarea>
	</form>
	<div class="moreInfo">Appuyez sur Entrer pour envoyer</div>

	<div id="msgContainer" class="content autoBottom">
<?php
	foreach($data['messages'] AS $res){
		echo '<p data-id="'.$res['id'].'" class="'.(($res['id_from'] == $_SESSION['user_id'])?'right color_background':'left rcolor_background').'">';

		echo '<time datetime="'.$res['time'].'">'.$res['timed'].'</time>';

		echo $res['text'];
		echo '</p>';
	}
?>
	</div>

	<a class="cancel" href="#">Annuler</a>
</div>