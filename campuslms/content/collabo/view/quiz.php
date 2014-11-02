<?php
	if ($data['quiz']['error']){
		echo "<h1>Erreur</h1>";
		echo "<p>Un problème est survenu. Veuillez réessayer.</p>";
		return;
	}

	if ($data['quiz']['done']){
		//Quiz déjà fait ou sauvegardé. Afficher message.
		echo "<form>";
		echo 	"<h1>Quiz</h1><div class=\"clr\"></div>";

		echo '<article>';
		if (!$_POST['ajax'] && 0){
			echo '<header>';
				echo '<h1><a class="openInContentPane" href="'.$_GET['qs'][0].'/'.$_GET['qs'][1].'/lecon/'.$_GET['qs'][3].'">&#9668; Retour à la leçon</a></h1>';
				echo '<h2>'.$data['quiz']['nom'].'</h2>';
			echo '</header>';
		}

//		print_r($_POST);

			if ($data['quiz']['saved']){
				echo "<p>Votre résultat a été sauvegardé.</p>";
			}else{
				echo "<p>Vous avez remplis ce quiz le ".date('Y-m-d à h:i',strtotime($data['quiz']['done']))."</p>";
			}

			echo "<p>Vous avez obtenu une note de ".($data['quiz']['pointage']*1)."/".($data['quiz']['valeur']*1)."</p>";

			if ($data['quiz']['voir']){
				//bouton «Afficher mes réponses»
				echo '<a class="openInMenuBar color_background btn2x" href="'.$_GET['data'].'/review"><strong>Historique</strong>Revoir mes résultats</a>';
			}

			if ($data['quiz']['refaire']){
				//Bouton refaire le quiz (pour étude)
				echo '<a class="openInContentPane color_background btn2x" href="'.$_GET['data'].'/redo"><strong>Refaire le quiz</strong>pour fins d\'études seulement.</a>';
			}
		echo '</article>';

		echo '<a class="cancel" href="#">Annuler</a>';

		echo "</form>";

		return;
	}
?>
<article>
	<h1><a class="openInContentPane" href="<?php echo $_GET['qs'][0]; ?>/<?php echo $_GET['qs'][1]; ?>/lecon/<?php echo $_GET['qs'][3]; ?>">&#9668; Retour à la leçon</a></h1>
<?php
	if ($allowCreation){
		echo '<a class="openInMenuBar btn color_background" style="float:right;" href="'.$_GET['data'].'/edit" style="top:166px;"><strong>Modifier le quiz</strong>Nom et réglages</a>';
	}

?>
	<h2><?php echo $data['quiz']['nom']; ?></h2>

	<?php
		if ($data['quiz']['alert']){
			echo "<p class=\"alert\">".$data['quiz']['alert']."</p><br/>";
		}
	?>

	<div><?php echo $data['quiz']['description']; ?></div>

	<hr class="qtop"/>

	<form id="theform" class="openInMenuBar" method="post" action="<?php echo implode('/',$_GET['qs']); ?>" onsubmit="return doSaveQuiz();">

	<?php

	if ($data['quiz']['randomize']){
		echo "<p><em>Les questions seront ordonnées aléatoirement pour les utilisateurs</em></p>";
	}

	$i = 0;
	foreach($data['questions'] AS $k=>$v){
		$i++;

		echo "<div class=\"qContainer\" id=\"questionId".$v['id']."\">";

			echo "<h3>".$i."</h3>";

			if ($allowCreation){
				if ($v['randomize']){
					echo "<em>Les réponses seront ordonnées aléatoirement pour les utilisateurs</em>";
				}

				echo '<a class="openInMenuBar color_text" href="'.$_GET['qs'][0].'/'.$_GET['qs'][1].'/lecon/'.$_GET['qs'][3].'/quiz/'.$_GET['qs'][5].'/question/'.$v['id'].'/delete">Supprimer</a>';	
			}

			echo "<div>";
				echo "<p>".$v['question']."</p>";
				$imgUrl = "data/cours/".$_GET['qs'][1]."/lecons/".$_GET['qs'][3]."/fichiers/quiz/".$_GET['qs'][5]."/".$v['id'].".jpg";

				if (file_exists($imgUrl))
					echo "<img src=\"".$imgUrl."\" width=\"200\" height=\"120\" style=\"background:#333333\"/>";

				$type = "radio";
				$suffix = "";

				if ($v['multi']){
					$type = "checkbox";
					$suffix = "[]";
				}

				foreach($v['reponses'] AS $k2=>$v2){

					echo '<label for="q'.$k.'r'.$k2.'">';
						echo '<input type="'.$type.'" class="'.$type.'" name="q'.$k.$suffix.'" id="q'.$k.'r'.$k2.'" value="'.$v2['id'].'">';
						echo "<span>";
							if ($v2['valeur'] > 0){
								echo "<strong class=\"color_background\">".$v2['reponse']."</strong>";
							}else{
								echo $v2['reponse'];
							}
							if ($v2['valeur'] != 0){
								echo " (".($v2['valeur']*1)." pts)";
							}
							echo "</span>";
					echo '</label>';
				}
			echo "</div>";

		echo "</div>";

		echo "<div class=\"clr\"></div>";

	echo "<hr id=\"quizQuestionHr".$v['id']."\"/>";
	}

	?>
	<input type="hidden" name="datestart" value="<?php echo $data['datestart']; ?>"/>
	<input type="hidden" name="skey" value="<?php echo $data['skey'][0]; ?>"/>

	</form>

	<?php
		if ($allowCreation){
			echo '<a class="openInMenuBar color_background btn2x" href="'.$_GET['qs'][0].'/'.$_GET['qs'][1].'/lecon/'.$_GET['qs'][3].'/quiz/'.$_GET['qs'][5].'/question/nouvelle"><strong>Ajouter une question</strong>à ce quiz</a>';
//			if ($data['quiz']['randomize']){
//				echo '<a class="openInMenuBar color_background btn2x" href="cours/1/lecon/2/quiz/1/reordonner"><strong>Réordonner les questions</strong>de ce quiz</a>';
//			}
		}else{
			echo '<a class="color_background btn2x" href="javascript:saveQuiz();" onclick="return saveQuiz();"><strong>Soumettre mes réponses</strong>Ce quiz est comptabilisé dans votre note</a>';
		}
	?>
</article>