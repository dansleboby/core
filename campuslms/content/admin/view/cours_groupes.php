<!--<header>
	<h1>Administration</h1>
	<h2>Groupes de l'utilisateur</h2>
</header>-->

	<h1>Groupes assignés au cours</h1>
	<a class="cancel" href="#">Annuler</a>	
	<div class="clr"></div>

	<ul>
	<?php
		if ($canEdit){
			echo "<li><a class=\"btn openInMenuBar\" href=\"admin/cours/".$_GET['qs'][2]."/groupes/nouveau\">Ajouter un groupe</a></li>";
		}else{
			echo "<li><hr/></li>";
		}
				$i = 0;
		foreach($data AS $k=>$v){
			$i++;
			echo "<li>";
			switch($v['etat']){
				case 'actif':
					echo "<strong>".$v['nom']."</strong>";
				break;
				case 'inactif':
					echo "<em>".$v['nom']."</em>";
				break;
				default:
					echo $v['nom'];
				break;
			}			if ($canEdit){
				echo "<a class=\"deletelink openInRightBar\" href=\"admin/cours/".$_GET['qs'][2]."/groupes/".$v['fid']."/delete\">Supprimer</a>";
				echo "<a class=\"editlink openInMenuBar\" href=\"admin/cours/".$_GET['qs'][2]."/groupes/".$v['fid']."\">Modifier</a>";
			}
			echo "</li>";
		}

		if ($i == 0)
			echo "<li>Aucun résultat.</li>";
	?>
	</ul>