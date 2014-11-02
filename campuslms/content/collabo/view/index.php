<header>
	<h1>Cours #<?php echo $data['cours']['id']; ?></h1>

	<h2><?php echo $data['cours']['nom']; ?></h2>
</header>

<?php
if ($_SESSION['user_level'] == 'collaborateur'){
	echo '<ul id="options">';
		echo '<li>';
//			echo '<a class="btn color_background openInMenuBar" href="'.$_GET['qs'][0].'/'.$_GET['qs'][1].'/ordre"><strong>Ordre des cours</strong>Modifier l\'ordre des cours</a>';

			if (count($data['lecons']) >= 1){
				echo '<a class="btn color_background openInMenuBar" href="'.$_GET['qs'][0].'/'.$_GET['qs'][1].'/ponderation"><strong>Pondération</strong>Modifier la pondération</a>';
			}
		echo '</li>';
	echo '</ul>';
}

foreach($data['messages'] AS $msg){
	echo '<div class="message color_background">';
		echo '<div class="noImg color_text">!</div>';
		echo '<div class="ctn">';
			if ($msg['date_debut']){
				echo "Message reçu le ".$msg['date_debut'];
			}

			echo '<h2>Message au groupe</h2>';
			echo '<h3>de '.$msg['prenom'].' '.$msg['nom'].'</h3>';
			echo '<p>'.nl2br($msg['message']).'</p>';
		echo '</div>';
		echo '<div class="clr"></div>';
	echo '</div>';
}

/*	if ($data['message']){
		echo '<div class="message color_background">';
			echo '<div class="noImg color_text">!</div>';
			echo '<div class="ctn">';
				echo '<h2>Message au groupe</h2>';
				echo '<p>'.nl2br($data['message']).'</p>';

				if ($data['enseignant']){
					echo '<a href="notifications/messages/'.$data['enseignant'].'" class="btn openInRightBar color_text"><strong>Question?</strong>Contacter votre enseignant</a>';
					echo '<div class="clr"></div>';
				}
			echo '</div>';
			echo '<div class="clr"></div>';
		echo '</div>';
	}*/

	$i = 0;

	if (count($data['lecons']) == 0 && $_SESSION['user_level'] != 'collaborateur'){
		echo '<div class="message color_background"><div class="noImg color_text">!</div><h2>Aucun contenu a afficher</h2><p>Aucune leçon n\'est active dans ce cours. Veuillez revenir plus tard ou contacter votre enseignant pour plus d\'information.</p><div class="clr"></div></div>';
//		echo "Une erreur est survenue";
//		return;
	}

	echo '<div id="articleContainer">';

	$nbGroupe = 0;

	foreach ($data['groupes'] AS $groupe){
		echo "<h3 class=\"title\" data-groupe=\"".$groupe['id']."\">".$groupe['nom']."<span>Modifier</span></h3>";
		$nbGroupe++;

		if (count($groupe['cours']) > 0){
			foreach($groupe['cours'] AS $cours){
				$i++;
				showCours($cours, $i);
			}
		}
	}

	if (count($data['sansgroupe']) > 0){
		if ($nbGroupe != 0){
			echo "<h3 data-groupe=\"0\">Non classé</h3>";
		}

		foreach($data['sansgroupe'] AS $cours){
			$nbGroupe++;
			$i++;
			showCours($cours, $i);
		}
	}

	function showCours($id, $i){
		GLOBAL $data;
		$lecon = $data['lecons'][$id];

		?>
		<article class="color_border leconbtn<?php echo (($lecon['disabled'])?' disabled':''); ?>" data-lecon="<?php echo $lecon['id']; ?>">
			<?php
				$imgSrc = "data/cours/".$lecon['id_cours']."/lecons/".$lecon['id']."/main.jpg";
				if (file_exists(dirname(__FILE__)."/../../../../".$imgSrc)){
					echo '<img src="'.$imgSrc.'" alt=""/>';
				}else{
					echo '<div class="noImg rcolor_background"><p>Leçon</p><p class="rcolor_text">'.sprintf('%02d', $i).'</div>';

				}
			?>
			<header>
				<div class="color_background"></div>
				<a href="<?php echo $_GET['qs'][0]."/".$lecon['id_cours']; ?>/lecon/<?php echo $lecon['id']; ?>">
					Leçon #<?php echo $i; ?>
					<strong><?php echo $lecon['nom']; ?></strong>
				</a>
			</header>
			<?php
//				if (rand(0,1)){
				if (0){
					echo '<div class="success color_background"></div>';
				}
			?>
		</article>
		<?php
	}

	echo "</div>";

	if ($allowCreation){
?>
<article class="color_border newLecon leconbtn">
	<header>
		<div class="color_background"></div>
		<a class="openInMenuBar" href="<?php echo $_GET['qs'][0]."/".$_GET['qs'][1]; ?>/lecon/nouveau">
			Nouvelle leçon
			<strong>Création d'une nouvelle leçon</strong>
		</a>
	</header>
</article>

<div class="clr"></div>

<?php
	if (count($data['lecons']) > 1){
		echo '<a class="btn color_background" id="reorderButton"><strong>Réorganiser</strong> les leçons</a>';
	}

	echo '<a class="btn color_background openInMenuBar" href="'.$_GET['qs'][0].'/'.$_GET['qs'][1].'/groupe"><strong>Séquence</strong>Ajouter une séquence</a>';


	}else{
		echo '<div class="clr"></div>';

		echo '<div class="enseignant color_border">';
			echo "Plus sur votre enseignant";
			echo "<h2>".$data['enseignant']['prenom']." ".$data['enseignant']['nom']."</h2>";

			if ($data['enseignant']['profile']){
				echo "<p>".nl2br($data['enseignant']['profile'])."</p><br/>";
			}

			echo "<a href=\"notifications/messages/".$data['enseignant']['id']."\" class=\"openInRightBar btn2x color_background\">";

				$imgUrl = "data/profile/".$data['enseignant']['id'].".jpg";
				if (file_exists($imgUrl)){
					echo "<img class=\"rounded\" src=\"".$imgUrl."\" alt=\"".$data['enseignant']['nom']."\">";
				}else{
					echo '<div class="noImg color_text">'.getFirstLetters($data['enseignant']['prenom']." ".$data['enseignant']['nom']).'</div>';
				}

				echo "<strong>".$data['enseignant']['prenom']." ".$data['enseignant']['nom']."</strong>Contacter par messagerie interne";
			echo "</a>";

			echo "<a target=\"_blank\" href=\"skype:".$data['enseignant']['skype']."?chat\" class=\"color_background btn2x skypeButton skypeButton".$data['enseignant']['id']." onLoad\" data-id=\"".$data['enseignant']['id']."\" data-username=\"".$data['enseignant']['skype']."\" data-onload=\"skypeDaemon_check\">";

				echo "<img src=\"campuslms/template/default/images/skype.png\" alt=\"".$data['enseignant']['nom']." (Skype)\">";

				echo "<strong>Contacter par Skype</strong><span>État inconnu</span>";
			echo "</a>";
			echo '<div class="clr"></div>';
		echo '</div>';
	}
?>

<div class="clr"></div>