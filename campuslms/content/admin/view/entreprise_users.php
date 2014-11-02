<!--<header>
	<h1>Administration</h1>
	<h2>Groupes de l'utilisateur</h2>
</header>-->

	<h1>Utilisateurs de l'entreprise</h1>
	<a class="cancel" href="#">Annuler</a>	
	<div class="clr"></div>

	<ul>
	<?php
		if ($canEdit){
			echo "<li><a class=\"btn openInMenuBar\" href=\"admin/entreprise/".$_GET['qs'][2]."/users/nouveau\">Ajouter un utilisateur</a></li>";
		}

		$i = 0;
		$prevNiveau = "";
		foreach($data AS $k=>$v){
			$i++;

			if ($prevNiveau != $v['niveau']){
				echo "</ul><h2>".strtoupper($v['niveau'])."</h2><ul>";
				$prevNiveau = $v['niveau'];
			}

			echo "<li><strong>".$v['nom'].", ".$v['prenom']."</strong>";
			if ($canEdit){
				echo "<a class=\"deletelink openInRightBar\" href=\"admin/entreprise/".$_GET['qs'][2]."/users/".$v['id']."/delete\">Supprimer</a>";
				echo "<a class=\"editlink openInMenuBar\" href=\"admin/entreprise/".$_GET['qs'][2]."/users/".$v['id']."\">Modifier</a>";
			}
			echo "</li>";
		}

		if ($i == 0)
			echo "<li>Aucun résultat.</li>";
	?>
	</ul>