<!--<header>
	<h1>Administration</h1>
	<h2>Nouveau cours</h2>
</header>-->

<form method="post" action="<?php echo $_GET['data']; ?>">
	<h1><?php echo (($_GET['qs'][5] > 0)?'Modifier':'Nouveau'); ?> préalable</h1>
	<div class="clr"></div>

	<select name="leconid" id="leconid">
		<?php
			$i = 0;
			foreach($data['lecons'] AS $res){
				$i++;
				if ($data['id_prealable'] == $res['id']){
					echo '<option value="'.$res['id'].'" selected="selected">Leçon #'.$res['nb']." - ".$res['nom'].'</option>';					
				}else{
					echo '<option value="'.$res['id'].'">Leçon #'.$res['nb']." - ".$res['nom'].'</option>';					
				}
			}
//			if ($i == 0){
//				echo '<option value="'.$res['id'].'">'.$res['nom'].'</option>';
//			}
		?>
	</select>

	<select name="cond" id="cond">
		<?php
			$conditions = array('read'=>"Leçon consultée", 
								'10'=>"½ étoile (10%)", 
								'20'=>"1 étoile (20%)", 
								'30'=>"1½ étoiles (30%)", 
								'40'=>"2 étoiles (40%)", 
								'50'=>"2½ étoiles (50%)", 
								'60'=>"3 étoiles (60%)", 
								'70'=>"3½ étoiles (70%)", 
								'80'=>"4 étoiles (80%)", 
								'90'=>"4½ étoiles (90%)", 
								'100'=>"5 étoiles (100%)");

			foreach($conditions AS $k=>$v){
				if ($data['cond'] == $k){
					echo '<option value="'.$k.'" selected="selected">'.$v.'</option>';
				}else{
					echo '<option value="'.$k.'">'.$v.'</option>';
				}
			}
		?>
	</select>

	<input id="prealableid" name="prealableid" type="hidden" value="<?php echo $_GET['qs'][5]; ?>"/>

	<input type="submit" class="submit" value="Sauvegarder"/>
	<a class="cancel" href="#">Annuler</a>
	<?php
		if ($_GET['qs'][5] > 0){
			$link = $_GET['qs'];
			$link[6] = 'delete';
			$link = implode('/', $link);

			echo '<a class="delete openInMenuBar" href="'.$link.'">Supprimer le lien</a>';
		}
	?>
</form>