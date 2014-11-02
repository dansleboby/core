<!--<header>
	<h1>Administration</h1>
	<h2>Groupes de l'utilisateur</h2>
</header>-->

	<h1>Groupes de l'utilisateur</h1>
	<a class="cancel" href="#">Annuler</a>	
	<div class="clr"></div>


	<ul>
	<?php
		if ($canEdit){
			echo "<li><a class=\"btn openInMenuBar\" href=\"admin/account/".$_GET['qs'][2]."/groupes/nouveau\">Nouveau groupe</a></li>";
		}else{
			echo "<li><hr/></li>";
		}

		$i = 0;
		foreach($data AS $k=>$v){
			$i++;
			echo "<li><strong>".$v['nom']."</strong>";
			if ($canEdit){
				echo "<a class=\"deletelink openInRightBar\" href=\"admin/account/".$_GET['qs'][2]."/groupes/".$v['gid']."/delete\">Supprimer</a>";
				echo "<a class=\"editlink openInMenuBar\" href=\"admin/account/".$_GET['qs'][2]."/groupes/".$v['gid']."\">Modifier</a>";
			}
			echo "</li>";
		}

		if ($i == 0)
			echo "<li>Aucun résultat.</li>";
	?>
	</ul>