<?php
	switch($_SESSION['user_level']){
		case 'sadmin':
		case 'admin':
		case 'collaborateur':
		case 'etudiant':
		case 'enseignant':
			?>
				<a id="lienLogout" href="logout.php"></a>
				<a id="lienReglages" class="openInMenuBar" href="reglages"></a>
			<?php
				$accept = array('enseignant','etudiant');
				if (in_array($_SESSION['user_level'],$accept)) {
			?>
				<a id="lienNotifications" class="openInMenuBar" data-nb="<?php echo $siteData['footer']['notifications']; ?>" class="<?php echo (($siteData['footer']['notifications'] > 0)?'unread':''); ?>" href="notifications"></a>
			<?php
				}
			?>
				Bienvenue
				<strong><?php echo $_SESSION['user_nom']; ?></strong>
			<?php
		break;
		default:
			//Default - Nothing
		break;
	}
?>