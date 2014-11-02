<!--<header>
	<h1>Administration</h1>
	<h2>Nouveau cours</h2>
</header>-->

<form method="post" action="<?php echo implode('/', $_GET['qs']); ?>">
	<h1><?php echo (($_GET['qs'][2] > 0)?'Modifier':'Nouveau'); ?></h1>
	<h2>Cours</h2>

<?php
	$salt = uniqid();
	echo '<input type="hidden" name="salt" id="salt" value="'.$salt.'"/>';
?>

	<div class="clr"></div>
<!--	<label for="titre">Nom</label>!-->
	<input type="text" class="text" placeholder="nom" name="titre" id="titre" value="<?php echo escape($data['nom']); ?>"/>

<!--	<label for="description">description</label>!-->
	<textarea placeholder="Description" name="description" id="description"><?php echo $data['description']; ?></textarea>

	<select name="user" id="user" class="specialField" data-specialType="selectHelper" data-placeholder="Choisissez un collaborateur">
<?php
	$mysqli = dbconnect();
	$req = "SELECT * FROM users WHERE niveau='collaborateur' ORDER BY nom ASC";
	$query = $mysqli->query($req);
	while($res = $query->fetch_array(MYSQLI_ASSOC)){
		echo '<option value="'.createValidateKey($res['id'], $salt).'"'.(($res['id'] == $data['id_user'])?' selected':'').'>'.$res['nom'].', '.$res['prenom'].' ('.$res['email'].')</option>';
	}
	$mysqli->close();
?>
	</select>

	<select name="type" id="type" data-placeholder='Choisissez un type'>
		<?php
			$types = array('standard'=>"Cours standard (avec leçons)",'group'=>"Cours groupés (sans leçons)");

			foreach($types AS $k=>$v){
				echo '<option value="'.$k.'"'.(($data['type'] == $k)?' selected':'').'>'.$v.'</option>';
			}
		?>
	</select>

	<input type="submit" class="submit" value="Sauvegarder"/>
	<a class="cancel" href="#">Annuler</a>
	<?php
	if ($_GET['qs'][2] > 0){
		echo '<a class="delete openInMenuBar" href="admin/cours/'.$_GET['qs'][2].'/delete">Supprimer le cours</a>';
	}
	?>
</form>