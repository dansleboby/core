<!--<header>
	<h1>Administration</h1>
	<h2>Nouveau cours</h2>
</header>-->

<form method="post" action="<?php echo implode('/', $_GET['qs']); ?>">
	<h1><?php echo (($_GET['qs'][2] > 0)?'Modifier':'Nouveau'); ?></h1>
	<h2>Groupe</h2>
	<div class="clr"></div>
<!--	<label for="nom">Nom</label>!-->
	<input type="text" class="text" placeholder="nom" name="nom" id="nom" value="<?php echo escape($data['nom']); ?>"/>

<!--	<select name="etat" id="etat" class="">
		<optgroup label="Choisissez un niveau">
			<?php
				if (!$data['actif'])
					$data['actif'] = 'actif';

				$niveaux = array('inactif'=>'Fermé',
				                 'actif'=>'Actif');

				foreach ($niveaux as $k => $v) {
					echo '<option value="'.$k.'"'.(($k == $data['etat'])?' selected':'').'>'.$v.'</option>';
				}
			?>
		</optgroup>
	</selet>-->

	<input type="date" class="text specialField" placeholder="Date de début (YYYY-MM-DD) - laissez vide si immédiat" name="datedebut" id="datedebut" value="<?php echo $data['datedebut']; ?>" data-specialType="dateHelper">

	<input type="date" class="text specialField" placeholder="Date de fin (YYYY-MM-DD) - laissez vide si sans fin" name="datefin" id="datefin" value="<?php echo $data['datefin']; ?>" data-specialType="dateHelper">


	<input type="submit" class="submit" value="Sauvegarder"/>
	<a class="cancel" href="#">Annuler</a>
	<?php
		if ($_GET['qs'][2] > 0){
			echo '<a class="delete openInMenuBar" href="admin/groupes/'.$_GET['qs'][2].'/delete">Supprimer le groupe</a>';
		}
	?>
</form>