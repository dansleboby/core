<?php
	if ($data['error']){
		echo "<h1>Erreur</h1>";
		echo "<p>Un problème est survenu. Veuillez réessayer.</p>";
		return;
	}
?>
	<?php
	if ($data['saved']){
		if ($_POST['ajax']){
		//Confirm text
			$data['confirmText'] = "Les modifications ont été appliquées et sont effectives dès maintenant.";

			$data['refreshContent'] = false;
			$link = $_GET['qs'];
			array_pop($link);

			$data['goTo'] = implode("/", $link);

			exit(json_encode($data));
		}
		?>
<!--<header>
	<h1>Administration</h1>
	<h2>Nouveau cours</h2>
</header>-->
			<form method="post" action="<?php echo implode('/',$_GET['qs']); ?>">
			<h1>Ordre</h1>
			<h2>Cours #<?php echo $_GET['qs'][1]; ?></h2>
			<div class="clr"></div>

			<p>Les modifications ont été appliquées et sont effectives immédiatement.</p>
			</form>
		<?php
	}
	?>