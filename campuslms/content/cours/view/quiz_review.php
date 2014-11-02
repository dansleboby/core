<!--<header>
	<h1>Administration</h1>
	<h2>Nouveau cours</h2>
</header>-->
<form method="post" action="<?php echo implode('/',$_GET['qs']); ?>">
	<h1>Quiz</h1>
	<h2>Historique des résultats</h2>
	<div class="clr"></div>

	<hr/>

		<?php
			$j = 0;
			foreach($data['review'] AS $review){
				if ($j > 0){
					echo "<hr/>";
				}
				if (count($review['details']) > 0){
					echo "<div style=\"margin:10px 0;\">";
						echo "<span style=\"float:right;text-align:right;\">Le ".$review['datefin']."</span>";
						echo "<span style=\"font-size:1.5em;\">".($review['pointage']*1)."</span>/".($review['valeur']*1);

						$lastq = 0;
						$i = 0;
						foreach($review['details'] AS $res){
							if ($res['id_question'] != $lastq) {
								$i++;
								$lastq = $res['id_question'];
								echo "<h3 style=\"font-size:1.3em;\">".$i.") ".$res['question']."</h3>";
							}
							echo "<div style=\"margin-left:2em;\">".$res['reponse']." (".$res['valeur']." points)</div>";
						}
					echo "</div>";
					$j++;
				}
			}
		?>

	<div class="clr"></div>
	<a class="cancel" href="#">Annuler</a>
</form>