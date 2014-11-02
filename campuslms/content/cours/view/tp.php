<?php
	if ($data['error']){
		echo "<h1>Erreur</h1>";
		echo "<p>Un problème est survenu. Veuillez réessayer.</p>";
		return;
	}
?>
<!--<header>
	<h1>Administration</h1>
	<h2>Nouveau cours</h2>
</header>-->
<form method="post" action="<?php echo implode('/',$_GET['qs']); ?>">
	<?php
	if ($_GET['qs'][6] == 'upload'){
//		if ($data['remise'][0]['date']){
			if ($data['remise'][0]['note'] != ''){
				exit('Erreur');
			}
//		}

		if ($data['saved']){
			?>
				<h1>Remise</h1>
				<h2><?php echo $data['fichier']['nom']; ?></h2>
				<div class="clr"></div>

				<p>Merci. Votre fichier est en cours de téléversement</p>
				<p>Assurez-vous de garder la fenêtre ouverte jusqu'à ce que celui-ci ait été reçu. Vous pouvez suivre la progression du téléversement dans le coin supérieur droit de votre écran.</p>

			<?php
		}else{
			?>
				<h1>Remise</h1>
				<h2><?php echo $data['fichier']['nom']; ?></h2>
				<div class="clr"></div>

				<textarea placeholder="Commentaires (optionnels)" name="description" id="description"><?php echo $data['remise'][0]['description']; ?></textarea>

				<input type="hidden" name="skey" id="skey" value="<?php echo $data['skey'][0]; ?>"/>
				<label for="fichier" style="background:#FFFFFF;color:#555555;width:700px;height:24px;display:block;">
					<input type="file" class="text" name="fichier" id="fichier"/>
				</label>

				<input type="submit" class="submit" value="Sauvegarder"/>
			<?php
		}
	}else{
		?>
			<h1>TP</h1>
			<h2><?php echo $data['fichier']['nom']; ?></h2>
			<div class="clr"></div>

			<p><?php echo nl2br4txt($data['fichier']['description']); ?></p>

			<?php
/*				echo "--".$data['fichier']['location']."--";
				print_r($data['fichier']);*/

				if (file_exists($data['fichier']['location']) && $data['fichier']['etat'] == 'recu') {
			?>
			<a class="color_background btn2x" target="_blank" href="<?php echo implode('/',$_GET['qs']); ?>/download">
				<strong>Télécharger</strong>
				<?php echo $data['fichier']['name']." (".humanFilesize($data['fichier']['location']).")"; ?>
			</a>
			<?php
				}
			?>

			<hr/>

			<?php

				if ($data['remise'][0]['date']){
					if ($data['remise'][0]['description']){
						echo "Commentaires : <div class=\"textarea\">".nl2br($data['remise'][0]['description'])."</div>";
						echo '<div class="clr"></div>';
					}

					if (count($data['remise']) == 1){
						echo "<span style=\"position:absolute;top:2px;right:35px;\">Remis le ".$data['remise'][0]['date']."</span>";
					}else if (count($data['remise']) > 1){
						echo "<span style=\"position:absolute;top:2px;right:35px;\">Modifié le ".$data['remise'][count($data['remise'])-1]['date']."</span>";
					}

					if ($data['remise'][0]['note'] != ''){
						//Indiquer la note - Impossible de modifier le fichier
						echo "<br/>Votre TP a été corrigé le ".$data['remise'][0]['datenote'].". Vous avez obtenu la note de <strong>".($data['remise'][0]['note']*1)."%</strong>";
					}else{
						echo '<a class="color_background btn openInMenuBar" href="'.implode('/',$_GET['qs']).'/upload"><strong>Mettre à jour</strong>Remplacer votre TP</a>';
					}
				}else if ($data['fichier']['valeur'] > 0) {
					//Indiquer date limite de remise, si dispo
					echo "Vous n'avez pas encore remis ce TP.";
					echo '<div class="clr"></div>';

					echo '<a class="color_background btn openInMenuBar" href="'.implode('/',$_GET['qs']).'/upload"><strong>Téléverser</strong>Remettre votre TP</a>';
				}else{
					//Rien à remettre
				}
			?>
			<div class="clr"></div>
		<?php
	}
	?>
	<a class="cancel" href="#">Annuler</a>
</form>