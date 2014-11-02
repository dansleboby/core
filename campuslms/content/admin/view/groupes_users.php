<!--<header>
	<h1>Administration</h1>
	<h2>Groupes de l'utilisateur</h2>
</header>-->

	<h1>Utilisateurs du groupe</h1>
	<a class="cancel" href="#">Annuler</a>	
	<div class="clr"></div>

	<ul>
	<?php
		if ($canEdit){
			echo "<li><a class=\"btn openInMenuBar\" href=\"admin/groupes/".$_GET['qs'][2]."/users/nouveau\">Ajouter un utilisateur</a></li>";
		}

		$i = 0;
		foreach($data AS $k=>$v){
			$i++;
			echo "<li><strong>".$v['nom'].", ".$v['prenom']." (".$v['email'].")</strong>";
			if ($canEdit && $_SESSION['accountType'] == 'campus'){
				echo "<a class=\"deletelink openInRightBar\" href=\"admin/groupes/".$_GET['qs'][2]."/users/".$v['gid']."/delete\">Supprimer</a>";
				echo "<a class=\"editlink openInMenuBar\" href=\"admin/groupes/".$_GET['qs'][2]."/users/".$v['gid']."\">Modifier</a>";
			}
			echo "</li>";
		}

		if ($i == 0)
			echo "<li>Aucun résultat.</li>";
	?>
	</ul>