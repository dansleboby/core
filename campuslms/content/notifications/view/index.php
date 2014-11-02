<nav>
	<h1>Notifications</h1>
	<ul id="notifications">
		<?php
			if ($_SESSION['user_level'] == 'etudiant'){
				echo '<li class="message compose"><a class="openInMenuBar" href="notifications/messages/new"><span>+</span><label>Nouveau message</label></a></li>';
			}
			if (count($data['notifications']) > 0){
				foreach($data['notifications'] AS $res){
					switch($res['type']){
						case 'message':
							$nom = $res['prenom']." ".$res['nom'];
							$text = getFirstLetters($nom);

							echo '<li class="message '.(($res['lu'] == 0)?'new':'').'"><a class="openInRightBar" href="notifications/messages/'.$res['lid'].'"><span>'.$text.'</span><label>'.$res['nom'].', '.$res['prenom'].'</label></a></li>';
						break;
						default:
							$top = 100;
							$bot = 100;
							echo '<li class="note '.(($res['lu'] == 0)?'new':'').'"><a class="openInRightBar" href="notifications/notes"><span>'.$top.'</span><span>'.$bot.'</span><label>Nouvelle note reçue</label></li>';
						break;
					}
/*					print_r($res);
					print_r($res2);*/
				}
			}
		?>
	</ul>
		<?php
/*			if (count($data['notifications']) == 0){
				echo "<a>Aucune notification</a>";
			}*/
		?>
	<a class="hideInSection" href="#">Fermer</a>

</nav>