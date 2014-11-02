<header>
	<h1>Modifier les paramètres</h1>
	<h2>Réglages</h2>
</header>

<form method="post" action="">
	<div>
		<h3>Général</h3>
			<label for="s_SITE_NAME">Nom</label>
				<input class="text" type="text" name="s_SITE_NAME" id="s_SITE_NAME" value="<?php echo SITE_NAME; ?>"/>
			<label for="s_SITE_TITLE">Titre par défaut</label>
				<input class="text" type="text" name="s_SITE_TITLE" id="s_SITE_TITLE" value="<?php echo SITE_TITLE; ?>"/>
			<label for="s_SITE_URL">URL du site</label>
				<input class="text" type="text" name="s_SITE_URL" id="s_SITE_URL" value="<?php echo SITE_URL; ?>"/>
			<label for="s_SITE_DESCRIPTION">Description</label>
				<input class="text" type="text" name="s_SITE_DESCRIPTION" id="s_SITE_DESCRIPTION" value="<?php echo SITE_DESCRIPTION; ?>"/>
	<!--		<label for="">Logo <span class="small">(PNG blanc)</span></label>
				<input class="text" type="file" name="" id="" value=""/>!-->
			<label for="s_SITEMODE">Maintenance</label>
				<select name="s_SITEMODE" id="s_SITEMODE">
					<option value="maintenance"<?php echo ((SITEMODE == 'live')?' selected="selected"':''); ?>>En maintenance. Le site ne sera accessible que par les administrateurs</option>
					<option value="live"<?php echo ((SITEMODE == 'live')?' selected="selected"':''); ?>>Non. Le site est accessible par tous les utilisateurs</option>
				</select>
			<label for="s_USE_USERCODE">Utiliser les code internes</label>
				<select name="s_USE_USERCODE" id="s_USE_USERCODE">
					<option value="true"<?php echo ((USE_USERCODE == "true")?' selected="selected"':''); ?>>Oui. Associer des codes et permettre la connexion avec ceux-ci.</option>
					<option value="false"<?php echo ((USE_USERCODE != "true")?' selected="selected"':''); ?>>Non. Ne pas utiliser de code interne.</option>
				</select>
			<label for="s_CRONMODE">Tâche automatiques (CRON)</label>
				<select name="s_CRONMODE" id="s_CRONMODE">
					<option value="true"<?php echo ((CRONMODE == "true")?' selected="selected"':''); ?>>Réelles (requiert réglage serveur) (?)</option>
					<option value="fake"<?php echo ((CRONMODE != "fake")?' selected="selected"':''); ?>>Simulée (appelé automatiquement par les visiteurs)</option>
					<option value="false"<?php echo ((CRONMODE != "false")?' selected="selected"':''); ?>>Désactivée</option>
				</select>
			<label for="s_EXPIRATION_ALERT">Avertissement d'échéance</label>
				<select name="s_EXPIRATION_ALERT" id="s_EXPIRATION_ALERT">
					<option value="0"<?php echo ((EXPIRATION_ALERT == 0)?' selected="selected"':''); ?>>Désactivée</option>
					<option value="1"<?php echo ((EXPIRATION_ALERT == 1)?' selected="selected"':''); ?>>1 jour</option>
					<option value="2"<?php echo ((EXPIRATION_ALERT == 2)?' selected="selected"':''); ?>>2 jours</option>
					<option value="3"<?php echo ((EXPIRATION_ALERT == 3)?' selected="selected"':''); ?>>3 jours</option>
					<option value="4"<?php echo ((EXPIRATION_ALERT == 4)?' selected="selected"':''); ?>>4 jours</option>
					<option value="5"<?php echo ((EXPIRATION_ALERT == 5)?' selected="selected"':''); ?>>5 jours</option>
					<option value="6"<?php echo ((EXPIRATION_ALERT == 6)?' selected="selected"':''); ?>>6 jours</option>
					<option value="7"<?php echo ((EXPIRATION_ALERT == 7)?' selected="selected"':''); ?>>7 jours</option>
					<option value="14"<?php echo ((EXPIRATION_ALERT == 14)?' selected="selected"':''); ?>>14 jours</option>
					<option value="21"<?php echo ((EXPIRATION_ALERT == 21)?' selected="selected"':''); ?>>21 jours</option>
					<option value="30"<?php echo ((EXPIRATION_ALERT == 30)?' selected="selected"':''); ?>>30 jours</option>
					<option value="60"<?php echo ((EXPIRATION_ALERT == 60)?' selected="selected"':''); ?>>60 jours</option>
					<option value="90"<?php echo ((EXPIRATION_ALERT == 90)?' selected="selected"':''); ?>>90 jours</option>
				</select>
	</div>

	<div>
	<h3>Base de donnée</h3>
		<label for="s_DB_TYPE">Type</label>
			<select name="s_DB_TYPE" id="s_DB_TYPE">
				<?php
					$options = array("MySQL","MySQLi","SQLite","MSSQL","PDO-MySQL","PDO-SQLite","PDO-PGSQL");
					foreach ($options AS $i){
						echo '<option value="'.$i.'"'.(($i == DB_TYPE)?' selected="selected"':'').'>'.$i.'</option>';
					}
				?>
			</select>
		<label for="s_DB_HOST">Hostname</label>
			<input class="text" type="text" name="s_DB_HOST" id="s_DB_HOST" value="<?php echo DB_HOST; ?>"/>
		<label for="s_DB_PORT">Port</label>
			<input class="text" type="text" name="s_DB_PORT" id="s_DB_PORT" value="<?php echo DB_PORT; ?>"/>
		<label for="s_DB_USERNAME">Username</label>
			<input class="text" type="text" name="s_DB_USERNAME" id="s_DB_USERNAME" value="<?php echo DB_USERNAME; ?>"/>
		<label for="s_DB_PASSWORD">Password</label>
			<input class="text" type="text" name="s_DB_PASSWORD" id="s_DB_PASSWORD" value="" placeholder="Le mot de passe n'est pas affiché pour des raisons de sécurité."/>
		<label for="s_DB_NAME">Nom</label>
			<input class="text" type="text" name="s_DB_NAME" id="s_DB_NAME" value="<?php echo DB_NAME; ?>"/>
	</div>

<?php
/*
	<div>
	<h3>Fonctionnalités</h3>
	<!--	<label for="">Création des cours</label>
			<select name="" id="">
				<option value="">Administrateur uniquement</option>
				<option value="">Administrateur et collaborateurs</option>
			</select>!-->

		<label for="">Messagerie/Étudiants</label>
			<select name="" id="">
				<option value="">Les étudiants peuvent contacter leurs enseignants</option>
			</select>

		<label for="">Messagerie/Enseignants</label>
			<select name="" id="">
				<option value="">Les enseignants peuvent contacter leurs étudiants</option>
			</select>

	<!--	<label for="">Courriel</label>
			<select name="" id="">
				<option value="">Permettre aux utilisateurs de se contacter par Courriel</option>
			</select>!-->

		<label for="">Skype</label>
			<select name="" id="">
				<option value="">Permettre aux utilisateurs de se contacter via Skype</option>
			</select>
	</div>
*/
?>
	<div>

	<h3>Performances</h3>
	<!--	<label for="">Mise en cache des informations</label>
			<select name="" id="">
				<option value="">Simple</option>
				<option value="">Aggressif</option>
				<option value="">Désactiver</option>
			</select>!-->

		<label for="s_DELAY_TIMEOUT">Délai maximal des requêtes</label>
			<select name="s_DELAY_TIMEOUT" id="s_DELAY_TIMEOUT">
				<?php
					$options = array(5,10,15,20,25,30,40,50,60,90,120);
					foreach ($options AS $i){
						echo '<option value="'.$i.'"'.(($i == DELAY_TIMEOUT)?' selected="selected"':'').'>Annuler après '.$i.' secondes</option>';
					}
					echo '<option value="0"'.((DELAY_TIMEOUT == 0)?' selected="selected"':'').'Aucune limite</option>';
				?>
			</select>

		<label for="s_DELAY_NOTIFICATION">Rafraichissement des notification</label>
			<select name="s_DELAY_NOTIFICATION" id="s_DELAY_NOTIFICATION">
				<?php
					$options = array(15,30,45,60,90,120);
					foreach ($options AS $i){
						echo '<option value="'.$i.'"'.(($i == DELAY_NOTIFICATION)?' selected="selected"':'').'>Toutes les '.$i.' secondes</option>';
					}
					echo '<option value="0"'.((DELAY_NOTIFICATION == 0)?' selected="selected"':'').'Au changement de page</option>';
				?>
			</select>

		<label for="s_DELAY_CHAT">Rafraichissement des conversations</label>
			<select name="s_DELAY_CHAT" id="s_DELAY_CHAT">
				<?php
	//				echo '<option value="1"'.((DELAY_CHAT == 1)?' selected="selected"':'').'Selon activité (automatique)</option>';
					$options = array(2,4,6,8,15,30);
					foreach ($options AS $i){
						echo '<option value="'.$i.'"'.(($i == DELAY_CHAT)?' selected="selected"':'').'>Toutes les '.$i.' secondes</option>';
					}
					echo '<option value="0"'.((DELAY_CHAT == 0)?' selected="selected"':'').'Au changement de page</option>';
				?>
			</select>
	</div>
	<!--
	<div>
	<h3>CRON job</h3>
		<p>Instructions pour activer les CRON job</p>

		<label for="">Type de CRON job</label>
			<select name="" id="">
				<option value="">Vrai CRON job</option>
				<option value="">Faux CRON job</option>
				<option value="">Désactiver</option>
			</select>


		<label for="">Fichiers temporaires</label>
			<select name="" id="">
				<option value="">Supprimer après 12 heures</option>
				<option value="">Supprimer après 24 heures</option>
				<option value="">Supprimer après 48 heures</option>
				<option value="">Ne pas supprimer</option>
			</select>

		<label for="">Notification/Étudiant</label>
			<select name="" id="">
				<option value="">Envoyer les notifications non lues par courriel immédiatement</option>
				<option value="">Envoyer les notifications non lues par courriel après 1h</option>
				<option value="">Envoyer les notifications non lues par courriel après 2h</option>
				<option value="">Ne pas envoyer les notifications non lues par courriel</option>
			</select>

		<label for="">Notification/Enseignants</label>
			<select name="" id="">
				<option value="">Envoyer les notifications non lues par courriel immédiatement</option>
				<option value="">Envoyer les notifications non lues par courriel après 1h</option>
				<option value="">Envoyer les notifications non lues par courriel après 2h</option>
				<option value="">Ne pas envoyer les notifications non lues par courriel</option>
			</select>
	</div>

	<div>
	<h3>Sauvegardes</h3>
		<p>Pour fonctionner, les CRON job doivent être activées</p>
		<label for="">Backup des données</label>
			<select name="" id="">
				<option value="">Tous les jours</option>
				<option value="">Tous les deux jours</option>
				<option value="">Toutes les semaines</option>
				<option value="">Tous les mois</option>
				<option value="">Désactiver</option>
			</select>

		<label for="">Sauvegarde sur le serveur</label>
			<select name="" id="">
				<option value="">Garder tous les backups</option>
				<option value="">Garder 6 mois de backups</option>
				<option value="">Garder 1 mois de backups</option>
				<option value="">Garder 1 semaine de backups</option>
				<option value="">Ne pas sauvegarder sur le serveur</option>
			</select>

		<label for="">Envoyer par courriel</label>
			<input class="text" type="text" name="" id="" value="" placeholder="Laissez vide pour désactiver la fonction"/>
	</div>

	<div>
	<h3>Mise à jour</h3>
		<p>Pour mettre à jour automatiquement la plarteforme, activez les CRON jobs</p>

		<label for="">Mise à jour automatique</label>
			<select name="" id="">
				<option value="">Seulement les mise à jour sûr</option>
				<option value="">Toutes les mise à jour</option>
				<option value="">Désactiver</option>
			</select>

		<label for="">Serveur de mise à jour</label>
			<input class="text" type="text" name="" id="" value="" placeholder="Laissez vide pour désactiver la fonction"/>

		<label for="">Vérification des mises à jour</label>
			<select name="" id="">
				<option value="">Vérifier les mises à jour avec le serveur CampusLMS</option>
				<option value="">Vérifier les mises à jour avec le serveur de mise à jour</option>
				<option value="">Ne pas vérifier les mises à jour</option>
			</select>
	</div>
	!-->
	<div>
	<h3>Thème</h3>
		<label for="s_DEFAULT_TEMPLATE">Choix du thème</label>
			<select name="s_DEFAULT_TEMPLATE" id="s_DEFAULT_TEMPLATE">
				<option value="default">Thème par défaut</option>
			</select>

	<?php
		foreach($theme['settings'] AS $k=>$v){
			echo '<label for="themeSetting_'.$k.'">'.$v['label'].'</label>';
			switch($v['type']){
				case 'color':
					echo '<input class="text" type="color" name="themeSetting_'.$k.'" id="themeSetting_'.$k.'" value="'.$v['value'].'" placeholder="Par défaut : '.$v['default'].'"/>';
				break;
				case 'select':
					echo '<select name="themeSetting_'.$k.'" id="themeSetting_'.$k.'">';
					foreach($v['values'] AS $k2=>$v2){
						echo '<option value="'.$k2.'"'.(($k2 == $v['value'])?' selected="selected"':'').'>'.$v2.'</option>';
					}
					echo '</select>';
				break;
				default:
					echo '<input class="text" type="text" name="themeSetting_'.$k.'" id="themeSetting_'.$k.'" value="'.$v['value'].'" placeholder="Par défaut : '.$v['default'].'"/>';
				break;
			}

		}

		echo '<input type="hidden" name="skey" value="'.$data['skey'].'"/>';
	?>
	</div>
	<div class="clr"></div>
	<input type="submit" value="Sauvegarder" class="btn2x color_background"/>
</form>