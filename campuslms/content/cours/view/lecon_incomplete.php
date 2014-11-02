<form>
	<h1>Leçon vérouillée</h1>
	<h2><?php echo $data['nom']; ?></h2>
	<div class="clr"></div>
	<a class="cancel" href="#">Annuler</a>
	<?php

		if ($locked){
			echo "<p>Cette formation est vérouillée. Vous pouvez vous inscrire à celle-ci pour vous la dévérouiller.</p>";
		}else{
			if (count($data['todo']) > 1){
				echo "<p>Cette leçon est vérouillée tant que les prérequis suivants ne sont pas atteints : </p>";
			}else{
				echo "<p>Cette leçon est vérouillée tant que le prérequis suivant n'est pas atteint : </p>";		
			}

			echo "<ul>";
				foreach($data['todo'] AS $res){
					echo "<li>".$res['action']."</li>";
				}
			echo "</ul>";
		}

//		echo "<pre>".print_r($res, true)."</pre><br/>---<br/>";
//		echo "<pre>".print_r($lecon, true)."</pre>";
	?>
</form>
