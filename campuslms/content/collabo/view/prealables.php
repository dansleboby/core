<!--<header>
	<h1>Administration</h1>
	<h2>Préalables de la leçon</h2>
</header>-->

	<h1>Préalables de la leçon</h1>
	<a class="cancel" href="#">Annuler</a>	
	<div class="clr"></div>

	<ul>
	<?php
		echo "<li><a class=\"btn openInMenuBar\" href=\"".$_GET['data']."/nouveau\">Ajouter un préalable</a></li>";

		$i = 0;
		foreach($data AS $k=>$v){
			$i++;

			$minnote = null;

			echo "<li>";
				switch($v['cond']){
					case 'read':
						$action = "Leçon consultée";
					break;
					case '1star':
						$minnote = NOTE_1STAR;
						$action = "1 étoile";
					break;
					case '2star':
						$minnote = NOTE_2STAR;
						$action = "2 étoiles";
					break;
					case '3star':
						$minnote = NOTE_3STAR;
						$action = "3 étoiles";
					break;
					case '4star':
						$minnote = NOTE_4STAR;
						$action = "4 étoiles";
					break;
					case '5star':
						$minnote = NOTE_5STAR;
						$action = "5 étoiles";
					break;
					case '10':
						$minnote = 10;
						$action = "½ étoile";
					break;
					case '20':
						$minnote = 20;
						$action = "1 étoile";
					break;
					case '30':
						$minnote = 30;
						$action = "1½ étoiles";
					break;
					case '40':
						$minnote = 40;
						$action = "2 étoiles";
					break;
					case '50':
						$minnote = 50;
						$action = "2½ étoiles";
					break;
					case '60':
						$minnote = 60;
						$action = "3 étoiles";
					break;
					case '70':
						$minnote = 70;
						$action = "3½ étoiles";
					break;
					case '80':
						$minnote = 80;
						$action = "4 étoiles";
					break;
					case '90':
						$minnote = 90;
						$action = "4½ étoiles";
					break;
					case '100':
						$minnote = 100;
						$action = "5 étoiles";
					break;
				}

				if ($minnote){
					$action .= " (".$minnote."%)";
				}

				$action .= " : ".$v['nom'];


			echo $action;

			echo "<a class=\"deletelink openInRightBar\" href=\"".$_GET['data']."/delete/".$v['id']."\">Supprimer</a>";
			echo "<a class=\"editlink openInMenuBar\" href=\"".$_GET['data']."/".$v['id']."\">Modifier</a>";

			echo "</li>";
		}

		if ($i == 0)
			echo "<li>Aucun résultat.</li>";
	?>
	</ul>