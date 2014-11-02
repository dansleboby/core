<form>
	<h1>Statistiques de l'utilisateur</h1>
		<div class="clr"></div>
		<a class="cancel" href="#">Annuler</a>

		<table style="width:100%;">
			<tr>
				<th>Date</th>
				<th>Action</th>
			</tr>
			<?php
				foreach($data['logs'] AS $log){
					echo "<tr>";
						echo "<td>".$log['date']."</td>";
						echo "<td>".$log['texte']."</td>";
					echo "<tr>";
				}
			?>
		</table>

</form>