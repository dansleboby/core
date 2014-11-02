<form>
	<h1>Réponses au Quiz</h1>
	<div class="clr"></div>
	<?php
		$i = 0;

		foreach($data['review']  AS $review){
			if ($i > 0){
				echo "<br/><hr/><br/>";
			}
			$i++;
			echo "<h2><strong>Session du ".$review['datefin']."</strong> (".(1*$review['pointage'])."/".(1*$review['valeur']).")</h2>";

			$j = 1;
			foreach($review['questions'] AS $question){
				echo "<strong>".$j.". ".$question['question']."</strong><br/>";
				$j++;

				echo "<ul style=\"margin-left:2em;margin-bottom:1em;\">";
				foreach($question['reponses'] AS $reponse){
					echo "<li>";
						echo '<input type="checkbox" disabled="disabled"'.($reponse['checked']?' checked="checked"':'').'/>';
						echo $reponse['reponse']." (".($reponse['valeur']*1)." pts)";
					echo "</li>";
				}
				echo "</ul>";
			}
		}
	?>
</form>