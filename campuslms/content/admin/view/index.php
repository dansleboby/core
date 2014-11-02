<header>
	<h1>Administration</h1>
	<h2>Aperçu des activités</h2>
</header>

<div class="fixedwidth">
	<div class="half first">
		<h3>Utilisateurs actifs</h3>
		<div class="">
			<table class="white color_border">
				<thead>
					<tr>
						<th><div class="large">Durée</div></th>
						<th>Quantité</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th><span class="small">Utilisateurs actifs</span>Dans les dernières 24 heures</th>
						<td><?php echo $data['stats']['activity'][1]; ?></td>
					</tr>
					<tr>
						<th><span class="small">Utilisateurs actifs</span>Dans les dernières 48 heures</th>
						<td><?php echo $data['stats']['activity'][2]; ?></td>
					</tr>
					<tr>
						<th><span class="small">Utilisateurs actifs</span>Dans les dernières 72 heures</th>
						<td><?php echo $data['stats']['activity'][3]; ?></td>
					</tr>
					<tr>
						<th><span class="small">Utilisateurs actifs</span>Dans la dernière semaine</th>
						<td><?php echo $data['stats']['activity'][7]; ?></td>
					</tr>
					<tr>
						<th><span class="small">Utilisateurs actifs</span>Dans les deux dernières semaines</th>
						<td><?php echo $data['stats']['activity'][14]; ?></td>
					</tr>
					<tr>
						<th><span class="small">Utilisateurs actifs</span>Dans le dernier mois</th>
						<td><?php echo $data['stats']['activity'][30]; ?></td>
					</tr>
					<tr>
						<th><span class="small">Utilisateurs actifs</span>Dans les 2 derniers mois</th>
						<td><?php echo $data['stats']['activity'][60]; ?></td>
					</tr>
					<tr>
						<th><span class="small">Utilisateurs actifs</span>Dans la dernière année</th>
						<td><?php echo $data['stats']['activity'][365]; ?></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
<?php
	if ($_SESSION['accountType'] == 'campus'){
?>
	<div class="half">
		<h3>Statistiques globales</h3>
		<div class="">
			<table class="white color_border">
				<thead>
					<tr>
						<th><div class="large">Nom</div></th>
						<th>Quantité</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th>Nombre de cours<span class="small">disponible sur la plateforme</span></th>
						<td><?php echo $data['stats']['cours']; ?></td>
					</tr>
					<tr>
						<th>Nombre de lecons<span class="small">disponible sur la plateforme</span></th>
						<td><?php echo $data['stats']['lecons']; ?></td>
					</tr>
					<tr>
						<th>Nombre de quiz<span class="small">disponible sur la plateforme</span></th>
						<td><?php echo $data['stats']['quiz']; ?></td>
					</tr>
					<tr>
						<th>Nombre de devoirs<span class="small">disponible sur la plateforme</span></th>
						<td><?php echo $data['stats']['devoir']; ?></td>
					</tr>
					<tr>
						<th><span class="small">Nombre d'utilisateurs de niveau</span>Étudiant</th>
						<td><?php echo $data['stats']['users']['etudiant']; ?></td>
					</tr>
					<tr>
						<th><span class="small">Nombre d'utilisateurs de niveau</span>Enseignant</th>
						<td><?php echo $data['stats']['users']['enseignant']; ?></td>
					</tr>
					<tr>
						<th><span class="small">Nombre d'utilisateurs de niveau</span>Collaborateur</th>
						<td><?php echo $data['stats']['users']['collaborateur']; ?></td>
					</tr>
					<tr>
						<th><span class="small">Nombre d'utilisateurs de niveau</span>Administrateur</th>
						<td><?php echo ($data['stats']['users']['admin']+$data['stats']['users']['sadmin']); ?></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
<?php
	}
?>
</div>