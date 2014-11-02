<!--<header>
	<h1>Administration</h1>
	<h2>Nouveau cours</h2>
</header>-->

<form method="post" action="<?php echo implode('/', $_GET['qs']); ?>">
	<h1><?php echo (($_GET['qs'][1] == 'account')?(($_GET['qs'][2] > 0)?'Modifier':'Nouvel'):(($_GET['qs'][4] > 0)?'Modifier':'Nouveau')); ?></h1>
	<h2>cours</h2>
	<div class="clr"></div>

<?php
	$salt = uniqid();
	echo '<input type="hidden" name="salt" id="salt" value="'.$salt.'"/>';
?>

	<select name="idCours" id="idCours" class="">
		<optgroup label="Choisissez une formation">
			<?php
				foreach ($data['cours'] as $k => $v) {
					echo '<option value="'.createValidateKey($k,$salt).'"'.(($k == $data['niveau'])?' selected':'').'>'.$v.'</option>';
				}
			?>
		</optgroup>
	</selet>

	<input type="number" class="text" placeholder="Nombre de licenses" name="nblicenses" id="nblicenses" value="<?php echo $data['nblicenses']; ?>">

	<input type="date" class="text specialField" placeholder="Date de début (YYYY-MM-DD) - laissez vide si immédiat" name="datedebut" id="datedebut" value="<?php echo $data['datedebut']; ?>" data-specialType="dateHelper">

	<input type="date" class="text specialField" placeholder="Date de fin (YYYY-MM-DD) - laissez vide si sans fin" name="datefin" id="datefin" value="<?php echo $data['datefin']; ?>" data-specialType="dateHelper">

	<input type="submit" class="submit" value="Sauvegarder"/>
	<a class="cancel" href="#">Annuler</a>
	<?php
	if ($_GET['qs'][4] > 0) {
		echo '<a class="delete openInMenuBar" href="'.implode('/', $_GET['qs']).'/delete">Supprimer le cours</a>';
	}
	?>
</form>