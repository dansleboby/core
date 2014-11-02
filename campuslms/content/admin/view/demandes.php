<header>
	<h1>Administration</h1>
	<h2>Demandes</h2>
</header>
<p>Voici les <?php echo count($data); ?> demandes en cours.</p>

<div class="radioContainer">
	<div class="radioButtonLabel">Affichage : </div>
	<div class="color_background radioButton active" data-control="radioControlledAll" data-show="radioControlledNew">Nouveau</div>
	<div class="color_background radioButton" data-control="radioControlledAll" data-show="radioControlledInProgress">En cours</div>
	<div class="color_background radioButton" data-control="radioControlledAll" data-show="radioControlledDone">Terminé</div>
	<div class="color_background radioButton" data-control="radioControlledAll" data-show="radioControlledAll">Tous</div>
</div>
<table class="fullWidth color_background color_border">
	<thead>
		<tr>
			<th>Date</th>
			<th>Utilisateur</th>
			<th>Cours</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody id="radioControlled">
<?php
	foreach($data AS $dat){
		switch($dat['etat']){
			case 0:
				$class = " radioControlledNew";
			break;
			case 1:
				$class = " radioControlledInProgress";
			break;
			case 2:
				$class = " radioControlledDone";
			break;
		}

		echo "<tr class=\"radioControlledAll".$class."\">";
			echo "<td>".$dat['date']."</td>";

			$userID = $dat['userPrenom']." ".$dat['userNom']."<br/>".$dat['email']." / ".$dat['usercode'];

			echo "<td><a href=\"admin/account#filtrer=".urlencode($dat['userPrenom'])."%20".urlencode($dat['userNom'])."\">".$userID."</a></td>";
			echo "<td><a href=\"admin/cours#filtrer=".urlencode($dat['nomCours'])."\">".$dat['nomCours']."</a></td>";
			echo "<td>";
				switch($dat['etat']){
					case 0:
						echo "<a href=\"admin/demandes/".$dat['id']."/1\">Marquer comme « en cours »</a>";
						echo "<br/><a href=\"admin/demandes/".$dat['id']."/2\">Marquer comme « terminé »</a>";
					break;
					case 1:
						echo "<a href=\"admin/demandes/".$dat['id']."/0\">Marquer comme « nouveau »</a>";
						echo "<br/><a href=\"admin/demandes/".$dat['id']."/2\">Marquer comme « terminé »</a>";
					break;
					case 2:
						echo "<a href=\"admin/demandes/".$dat['id']."/0\">Marquer comme « nouveau »</a>";
						echo "<br/><a href=\"admin/demandes/".$dat['id']."/1\">Marquer comme « en cours »</a>";
					break;
				}
			echo "</td>";
		echo "</tr>";
	}
?>
	</tbody>
</table>