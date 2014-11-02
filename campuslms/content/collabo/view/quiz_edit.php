<!--<header>
	<h1>Administration</h1>
	<h2>Nouveau cours</h2>
</header>-->

<?php

	if (!isset($data['quiz']['randomize'])){
		$data['quiz']['randomize'] = 1;
	}
	if (!isset($data['quiz']['voir'])){
		$data['quiz']['voir'] = 1;
	}

?>

<form method="post" action="<?php echo $_GET['data']; ?>">
	<h1><?php echo (($_GET['4'] > 0)?'Modifier':'Nouveau'); ?> quiz</h1>
	<div class="clr"></div>

	<?php
		switch($data['error']){
			case 'notEnoughQuestion':
				echo "<p>Assurez-vous d'inclure 2 réponses ou plus.</p>";
			break;
		}
	?>

<!--	<label for="titre">Nom</label>!-->
	<input type="text" class="text" placeholder="nom" name="titre" id="titre" value="<?php echo escape($data['quiz']['nom']); ?>"/>

	<textarea placeholder="Mise en situation" name="description" id="description"><?php echo $data['quiz']['description']; ?></textarea>

	<input type="text" class="text" placeholder="Pondération (valeur au bulletin)" name="valeur" id="valeur" value="<?php echo $data['quiz']['valeur']; ?>"/>

	<label for="melangerquestion" style="background:#FFFFFF;color:#555555;width:700px;height:24px;display:block;margin:5px 0;">
		<input type="checkbox" name="melangerquestion" id="melangerquestion"<?php echo (($data['quiz']['randomize'] == 1)?' checked="checked"':''); ?>/>
		Mélanger les questions
	</label>

	<label for="voirreponses" style="background:#FFFFFF;color:#555555;width:700px;height:24px;display:block;margin:5px 0;">
		<input type="checkbox" name="voirreponses" id="voirreponses"<?php echo (($data['quiz']['voir'] == 1)?' checked="checked"':''); ?>/>
		L'étudiant peut-il revoir ses réponses ?
	</label>

	<label for="refaire" style="background:#FFFFFF;color:#555555;width:700px;height:24px;display:block;">
		<input type="checkbox" name="refaire" id="refaire"<?php echo (($data['quiz']['refaire'] == 1)?' checked="checked"':''); ?>/>
		L'étudiant peut-il refaire le quiz (le meilleur résultat est conservé)
	</label>

	<input type="submit" class="submit" value="Sauvegarder"/>
	<a class="cancel" href="#">Annuler</a>
	<?php
		if ($_GET['qs'][5] > 0){
			$link = $_GET['qs'];
			$link[6] = 'delete';
			$link = implode('/', $link);

			echo '<a class="delete openInMenuBar" href="'.$link.'">Supprimer le quiz</a>';
		}
	?>
</form>