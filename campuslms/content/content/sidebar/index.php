<?php
GLOBAL $logerreur;

	if ($_SESSION['user_id']){
		if (count($data['sidebar']) > 0){
			echo "<ul>";
			foreach($data['sidebar'] AS $res){
				echo "<li".(($res[4])?' id="'.$res[4].'"':'').(($res[0] == substr($_GET['data'],0,strlen($res[0])))?' class="active"':'').">";

					echo '<a href="'.$res[0].'"'.(($res[0] == substr($_GET['data'],0,strlen($res[0])))?' class="color_text"':'').'>';

//						echo "_".$_GET['data']." VS ".substr($_GET['data'],0,strlen($res[0]))."_";

					if ($res[3])
						echo "<span>".$res[2].'</span><strong>'.$res[1].'</strong>';
					else
						echo '<strong>'.$res[1].'</strong><span>'.$res[2]."</span>";

					echo '</a>';


				echo "</li>";
			}
			echo "</ul>";
		}else{
			echo "<strong>Aucun contenu</strong>";
			echo "Aucun contenu n'est lié à votre compte. Contactez votre administrateur pour résoudre cette situation.";
		}
	}else{
		?>
			<form method="post" action="">
			<?php
				switch($logerreur){
					case '':

					break;
					case 1:
						echo "<div id=\"err\">Aucun compte ne correspond à ces informations.</div>";
					break;
					case 2:
						echo "<div id=\"err\">Votre compte est désactivé.</div>";
					break;
					default:

					break;
				}
			?>
				<input name="login_username" id="login_username" placeholder="Courriel<?php echo ((USE_USERCODE == "true")?' ou Code d\'identification':''); ?>" type="text" class="text color_text" value="<?php echo $_POST['login_username']; ?>">
				<input name="login_password" id="login_password" placeholder="Mot de passe" type="password" class="password color_text">
				<input type="hidden" name="loginmode" id="loginmode" value="<?php echo (($_POST['loginmode'] == 'cie')?'cie':'perso'); ?>"/>
				<span class="inputcontainer">
					<input type="submit" class="submit color_background" value="Connexion"/>
				</span>
			</form>
		<?php
	}
?>