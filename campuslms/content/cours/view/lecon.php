<article>
	<header>
		<h1><a class="openInContentPane" id="inPageBackBtn" href="cours/<?php echo $_GET['qs'][1]; ?>">&#9668; Retour à <?php echo $data['cours']['nom']; ?></a></h1>
		<?php
//			echo "<address>par <strong>".$data['cours']['collaborateur']['nom']."</strong></address>"; 
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

//				    echo '<iframe id="videoFrame" title="YouTube video player" src="http://www.youtube.com/embed/'.$match[1].'" frameborder="0" allowfullscreen></iframe>';
				    echo '<iframe id="videoFrame" title="YouTube video player" src="http://www.youtube.com/embed/'.$match[1].'" frameborder="0" allowfullscreen></iframe>';
				}else if (strpos($data['lecon']['media'],'vimeo') !== false){
					$id = substr(parse_url($data['lecon']['media'], PHP_URL_PATH), 1);

//				    echo '<iframe title="Vimeo video player" width="700" height="402" src="http://player.vimeo.com/video/'.$id.'" frameborder="0" allowfullscreen></iframe>';
				    echo '<iframe title="Vimeo video player" src="http://player.vimeo.com/video/'.$id.'" frameborder="0" allowfullscreen></iframe>';
				}

				echo "</div>";
			}
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
		if (count($data['fichiers']) > 0){
			echo '<section>';
				echo '<header>';
					echo '<h3>Références</h3>';
				echo '</header>';

				echo "<p>Voici des références qui vous seront nécessaires ou utiles.</p>";

				foreach($data['fichiers'] AS $fichier){
					echo '<a class="openInMenuBar color_background btn resize" href="cours/'.$_GET['qs'][1].'/lecon/'.$_GET['qs'][3].'/'.$fichier['type'].'s/'.$fichier['id'].'">';
							if ($fichier['type'] == 'lien'){
								echo '<strong>'.$fichier['nom'].'</strong>';
								echo "Lien externe";
							}else{
								echo '<strong>'.$fichier['nom'].'</strong>';
								echo "Fichier";
							}
					echo '</a>';
				}
			echo '</section>';
		}
	?>

	<?php
		if (count($data['devoirs'])+count($data['quiz'])+count($data['tp']) > 0 && $formationetat == 'actif') {

				$i = 0;

				foreach($data['quiz'] AS $quiz){
					if ($i == 0){
						echo '<section>';
							echo '<header>';
								echo '<h3>Quiz</h3>';
							echo '</header>';

							echo "<p>Pratiquez-vous pour vous assurez de bien maîtriser!</p>";
					}

					$i++;

					if ($quiz['done']){
						echo '<a class="color_background btn resize openInMenuBar" href="cours/'.$_GET['qs'][1].'/lecon/'.$_GET['qs'][3].'/quiz/'.$quiz['id'].'">';

							echo '<div class="done"></div>';

							echo '<strong>'.$quiz['nom'].'</strong>';
							echo 'Quiz';
						echo '</a>';
					}else{
						echo '<a class="color_background btn resize openInContentPane" href="cours/'.$_GET['qs'][1].'/lecon/'.$_GET['qs'][3].'/quiz/'.$quiz['id'].'">';
							echo '<strong>'.$quiz['nom'].'</strong>';
							echo 'Quiz';
						echo '</a>';
					}
				}

			if ($i > 0){
				echo '</section>';
			}

				$i = 0;
				foreach($data['tp'] AS $tp){
					if ($i == 0){
						echo '<section>';
							echo '<header>';
								echo '<h3>TP'.((count($data['tp']) > 1)?'s':'').'</h3>';
							echo '</header>';

							echo "<p>Pratiquez-vous pour vous assurez de bien maîtriser!</p>";
					}
					$i++;
					echo '<a class="openInMenuBar color_background btn resize" href="cours/'.$_GET['qs'][1].'/lecon/'.$_GET['qs'][3].'/tp/'.$tp['id'].'">';
						//Différence entre remis et corrigé ?
						if ($tp['corrige']){
							echo '<div class="done"></div>';
						}else if ($tp['remis']){
							echo '<div class="done" style="opacity:0.5;"></div>';
						}
						echo '<strong>'.$tp['nom'].'</strong>';
						echo 'TP';
					echo '</a>';
				}

			if ($i > 0){
				echo '</section>';
			}

				$i = 0;
				foreach($data['devoirs'] AS $devoirs){
					if ($i == 0){
						echo '<section>';
							echo '<header>';
								echo '<h3>Devoir'.((count($data['devoirs']) > 1)?'s':'').'</h3>';
							echo '</header>';

							echo "<p>Pratiquez-vous pour vous assurez de bien maîtriser!</p>";
					}
					$i++;
					echo '<a class="openInMenuBar color_background btn resize" href="cours/'.$_GET['qs'][1].'/lecon/'.$_GET['qs'][3].'/devoirs/'.$devoirs['id'].'">';
						//Différence entre remis et corrigé ?
						if ($devoirs['corrige']){
							echo '<div class="done"></div>';
						}else if ($devoirs['remis']){
							echo '<div class="done" style="opacity:0.5;"></div>';
						}
						echo '<strong>'.$devoirs['nom'].'</strong>';
						echo 'Devoir';
					echo '</a>';
				}

			if ($i > 0){
				echo '</section>';
			}
		}
	?>

<?php
			//Afficher le bloc d'aide
			if ($data['enseignant']['id'] > 0){
				echo "<section>";
					echo "<header>";
						echo "<h3>Aide</h3>";
						echo "<p>De la difficulté? Une question? Contacter l’enseignant.</p>";
					echo "</header>";
					echo "<a href=\"notifications/messages/".$data['enseignant']['id']."\" class=\"openInRightBar color_background btn2x\">";

					$imgUrl = "data/profile/".$data['enseignant']['id'].".jpg";
					if (file_exists($imgUrl)){
						echo "<img class=\"rounded\" src=\"".$imgUrl."\" alt=\"".$data['enseignant']['nom']."\">";
					}else{
						echo '<div class="noImg color_text">'.getFirstLetters($data['enseignant']['prenom']." ".$data['enseignant']['nom']).'</div>';
					}

					echo "<strong>".$data['enseignant']['prenom']." ".$data['enseignant']['nom']."</strong>Contacter par messagerie interne</a>";

					echo "<a target=\"_blank\" href=\"skype:".$data['enseignant']['skype']."?chat\" class=\"color_background btn2x skypeButton skypeButton".$data['enseignant']['id']." onLoad\" data-id=\"".$data['enseignant']['id']."\" data-username=\"".$data['enseignant']['skype']."\" data-onload=\"skypeDaemon_check\">";

					echo "<img src=\"campuslms/template/default/images/skype.png\" alt=\"".$data['enseignant']['nom']." (Skype)\">";

					echo "<strong>Contacter par Skype</strong><span>État inconnu</span></a>";


				echo "</section>";
			}

		if ($formationetat == 'actif'){
			//Vérifier le status de l'utilisateur et offrir une option en conséquence
				//(est-ce que quiz terminé et travaux remis?)
			if ((count($data['quiz']) > 0 || count($data['devoirs']) > 0)){
				echo '<section>';
					echo '<header>';
						echo '<h3>Compléter</h3>';
						echo '<p>Pour finaliser cette leçon, asurez-vous de completer tous les quiz et de remettre tous les devoirs.</p>';
					echo '</header>';
				echo '</section>';
			}else{
/*				echo '<section>';
					echo '<header>';
						echo '<h3>Compléter</h3>';
						echo '<p>Marquer cette leçon comme completé.</p>';
					echo '</header>';

					echo '<a href="cours/'.$_GET['qs'][1].'/lecon/'.$_GET['qs'][3].'/terminer" class="color_background btn4x">Compléter</a>';
				echo '</section>';*/
			}

			if (count($data['todo']) >= 1){
				echo "<section>";
					echo "<header>";
						echo "<h3>Prérequis</h3>";
						if (count($data['todo']) > 1){
							echo "<p>Pour accéder à cette leçon, vous avez dû atteindre les prérequis suivants :</p>";
						}else{
							echo "<p>Pour accéder à cette leçon, vous avez dû atteindre le prérequis suivant :</p>";
						}
					echo "</header>";

					echo "<ul>";
						foreach($data['todo'] AS $res){
							echo "<li>".$res['action']."</li>";
						}
					echo "</ul>";
				echo "</section>";
			}
		}
?>

</article>