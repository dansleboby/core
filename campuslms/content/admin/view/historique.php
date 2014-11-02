<header>
	<h1>Administration</h1>
	<h2>Historique</h2>
</header>
<p>Voici les <?php echo count($data); ?> derniers logs enregistrés sur le système.</p>

<div id="filterContainer" class="color_background">
	<input id="filter" data-conteneur="#radioControlled" data-quoi="tr" data-ou="span.keywords" type="text" placeholder="filtrer...">
</div>
<div class="radioContainer">
	<div class="radioButtonLabel">Affichage : </div>
	<div class="color_background radioButton active" data-control="radioControlledAll" data-show="radioControlledAll">Tous</div>
	<div class="color_background radioButton" data-control="radioControlledAll" data-show="radioControlledLogin">Connexion</div>
	<div class="color_background radioButton" data-control="radioControlledAll" data-show="radioControlledOther">Autre</div>
</div>
<table class="fullWidth color_background color_border">
	<thead>
		<tr>
			<th>Date</th>
			<th>Type</th>
			<th>Utilisateur</th>
			<th>Ref1</th>
			<th>Ref2</th>
			<th>Details</th>
			<th>IP</th>
		</tr>
	</thead>
	<tbody id="radioControlled">
<?php
	foreach($data AS $dat){
		$class = "radioControlledOther";

		switch($dat['texte']){
			case 'login':
			case 'logout':
				$class = "radioControlledLogin";
			break;
		}

		$keywords = array();
		$keywords[] = "date".$dat['date'];
		$keywords[] = "uid".$dat['uid'];
		$keywords[] = "user".$dat['uid_text'];
		$keywords[] = "etat".$dat['texte'];
		$keywords[] = "ip".$dat['ip'];

		echo "<tr class=\"radioControlledAll ".$class."\">";
			echo "<td>".$dat['date']."<span class=\"keywords hidden\">_".implode("_",$keywords)."_</span></td>";
			echo "<td>".$dat['texte']."</td>";
			echo "<td>".writeLine($dat,'uid')."</td>";
			echo "<td>".writeLine($dat,'ref')."</td>";
			echo "<td>".writeLine($dat,'ref2')."</td>";
			echo "<td>".$dat['details']."</td>";
			echo "<td>".$dat['ip']."</td>";
		echo "</tr>";
	}
?>
	</tbody>
</table>