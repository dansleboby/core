<article>
	<header>
		<h1><a class="openInContentPane" href="<?php echo $_GET['qs'][0]."/".$_GET['qs'][1]; ?>">&#9668; Retour à <?php echo $data['cours']['nom']; ?></a></h1>
		<?php
			echo '<a class="openInMenuBar btn color_background" style="float:right;" href="'.$_GET['data'].'/edit" style="top:166px;"><strong>Modifier la leçon</strong>Nom et description</a>';
			echo '<a class="openInRightBar btn color_background" style="float:right;" href="'.$_GET['data'].'/prealables" style="top:166px;">Gérer les <strong>préalables</strong></a>';
		?>
		<h2><?php

			echo $data['lecon']['nom']; 
		?></h2>
	</header>

	<?php
		$imgSrc = "data/cours/".$_GET['qs'][1]."/lecons/".$_GET['qs'][3]."/main.jpg";
		$imgE = file_exists(dirname(__FILE__)."/../../../../".$imgSrc);

		echo '<section class="video color_background'.(($data['lecon']['media'] || $imgE)?' withCtn':'').'">';

			if ($imgE){
				echo "<img src=\"".$imgSrc."\" alt=\"\"/>";
			}

			if ($data['lecon']['media']){
				echo "<div class=\"embed\">";

				if (substr($data['lecon']['media'], 0,1) == "<"){
					echo $data['lecon']['media'];
				}else if (strpos($data['lecon']['media'],'youtu') !== false){
				    preg_match('#(?:http://)?(?:www\.)?(?:youtube\.com/(?:v/|watch\?v=)|youtu\.be/)([\w-]+)(?:\S+)?#', $data['lecon']['media'], $match);

				    echo '<iframe title="YouTube video player" width="700" height="402" src="http://www.youtube.com/embed/'.$match[1].'" frameborder="0" allowfullscreen></iframe>';
				}else if (strpos($data['lecon']['media'],'vimeo') !== false){
					$id = substr(parse_url($data['lecon']['media'], PHP_URL_PATH), 1);

				    echo '<iframe title="Vimeo video player" width="700" height="402" src="http://player.vimeo.com/video/'.$id.'" frameborder="0" allowfullscreen></iframe>';
				}

				echo "</div>";
			}

			echo '<a class="openInMenuBar btn" href="'.$_GET['qs'][0].'/'.$_GET['qs'][1].'/lecon/'.$_GET['qs'][3].'/media/video" style="top:166px;">';
				if ($data['lecon']['media']){
					echo '<strong>Modifier la vidéo</strong>';
				}else{
					echo '<strong>Insérer une vidéo</strong>';
				}
				echo 'Vimeo, YouTube';
			echo '</a>';

			echo '<a class="openInMenuBar btn" href="'.$_GET['qs'][0].'/'.$_GET['qs'][1].'/lecon/'.$_GET['qs'][3].'/media/image" style="top:226px;">';
				if ($imgE){
					echo '<strong>Modifier l\'image</strong>';
				}else{
					echo '<strong>Insérer une image</strong>';
				}
				echo 'Téléverser une image de votre ordinateur (700x402px / 1400x804px@2x)';
			echo '</a>';

		echo '</section>';
	?>

	<section>
		<header>
			<h3>Description</h3>
			<div><?php
				echo $data['lecon']['description']; 
			?></div>
		</header>
	</section>

	<?php
		echo '<section>';
			echo '<header>';
				echo '<h3>Références</h3>';
			echo '</header>';

			if (count($data['fichiers']) > 0){
				echo "<p>Cliquez sur un élément pour le modifier.</p>";

				echo '<div id="referencesContainer">';

				foreach($data['fichiers'] AS $fichier){
					echo '<a class="openInMenuBar color_background btn resize" href="'.$_GET['qs'][0].'/'.$_GET['qs'][1].'/lecon/'.$_GET['qs'][3].'/'.$fichier['type'].'s/'.$fichier['id'].'/edit" id="lienId'.$fichier['id'].'">';
						if ($fichier['type'] == 'lien'){
							echo '<strong>'.$fichier['nom'].'</strong>';
							echo "Lien externe";
						}else{
							echo '<strong>'.$fichier['nom'].'</strong>';
							echo "Fichier";
						}
					echo '</a>';
				}

				echo "</div>";
			}

			echo '<a class="openInMenuBar no_background btn resize" href="'.$_GET['qs'][0].'/'.$_GET['qs'][1].'/lecon/'.$_GET['qs'][3].'/fichiers/nouveau">';
				echo '<strong>Nouveau fichier</strong>';
				echo 'Créer un nouveau fichier';
			echo '</a>';

			echo '<a class="openInMenuBar no_background btn resize" href="'.$_GET['qs'][0].'/'.$_GET['qs'][1].'/lecon/'.$_GET['qs'][3].'/liens/nouveau">';
				echo '<strong>Nouveau lien</strong>';
				echo 'Créer un nouveau lien';
			echo '</a>';

			if (count($data['fichiers']) > 1){
				echo '<a class="reorderButton" data-for="references">Réorganiser les références</a>';
			}

		echo '</section>';

		echo '<section>';
			echo '<header>';
				echo '<h3>Quiz</h3>';
			echo '</header>';

			if (count($data['quiz']) > 0){
				echo "<p>Cliquez sur une entrée pour la modifier.</p>";
			}

			echo '<div id="quizContainer">';
				foreach($data['quiz'] AS $quiz){
					echo '<a class="color_background btn resize openInContentPane" href="'.$_GET['qs'][0].'/'.$_GET['qs'][1].'/lecon/'.$_GET['qs'][3].'/quiz/'.$quiz['id'].'" id="quizId'.$quiz['id'].'">';
						echo '<strong>'.$quiz['nom'].'</strong>';
						echo 'Quiz';
					echo '</a>';
				}
				echo "</div>";

			echo '<a class="openInMenuBar no_background btn resize" href="'.$_GET['qs'][0].'/'.$_GET['qs'][1].'/lecon/'.$_GET['qs'][3].'/quiz/nouveau">';
				echo '<strong>Nouveau quiz</strong>';
				echo 'Créer un nouveau quiz';
			echo '</a>';

			if (count($data['quiz']) > 1){
				echo '<a class="reorderButton" data-for="quiz">Réorganiser les quizz</a>';
			}
		echo '</section>';

		echo '<section>';
			echo '<header>';
				echo '<h3>'.((count($data['tp']) > 1)?'Travaux pratiques':'Travail pratique').'</h3>';
			echo '</header>';

			if (count($data['tp']) > 0){
				echo "<p>Cliquez sur une entrée pour la modifier.</p>";
			}

			echo '<div id="tpContainer">';
				foreach($data['tp'] AS $tp){
					echo '<a class="openInMenuBar color_background btn resize" href="'.$_GET['qs'][0].'/'.$_GET['qs'][1].'/lecon/'.$_GET['qs'][3].'/tp/'.$tp['id'].'/edit" id="tpId'.$tp['id'].'">';
						echo '<strong>'.$tp['nom'].'</strong>';
						echo 'TP';
					echo '</a>';
				}
				echo "</div>";

			echo '<a class="openInMenuBar no_background btn resize" href="'.$_GET['qs'][0].'/'.$_GET['qs'][1].'/lecon/'.$_GET['qs'][3].'/tp/nouveau">';
				echo '<strong>Nouveau TP</strong>';
				echo 'Créer un nouveau travail pratique';
			echo '</a>';

			if (count($data['tp']) > 1){
				echo '<a class="reorderButton" data-for="tp">Réorganiser les TP</a>';
			}
		echo '</section>';

		echo '<section>';
			echo '<header>';
				echo '<h3>Devoir'.((count($data['devoirs']) > 1)?'s':'').'</h3>';
			echo '</header>';

			if (count($data['devoirs']) > 0){
				echo "<p>Cliquez sur une entrée pour la modifier.</p>";
			}

			echo '<div id="devoirsContainer">';
				foreach($data['devoirs'] AS $devoirs){
					echo '<a class="openInMenuBar color_background btn resize" href="'.$_GET['qs'][0].'/'.$_GET['qs'][1].'/lecon/'.$_GET['qs'][3].'/devoirs/'.$devoirs['id'].'/edit" id="devoirId'.$devoirs['id'].'">';
						echo '<strong>'.$devoirs['nom'].'</strong>';
						echo 'Devoir';
					echo '</a>';
				}
				echo "</div>";

			echo '<a class="openInMenuBar no_background btn resize" href="'.$_GET['qs'][0].'/'.$_GET['qs'][1].'/lecon/'.$_GET['qs'][3].'/devoirs/nouveau">';
				echo '<strong>Nouveau devoir</strong>';
				echo 'Créer un nouveau devoirs';
			echo '</a>';

			if (count($data['devoirs']) > 1){
				echo '<a class="reorderButton" data-for="devoirs">Réorganiser les devoirs</a>';
			}

		echo '</section>';
	?>
</article>