<article>
	<header>
		<h1><a class="openInContentPane" href="<?php echo $_GET['qs'][0].'/'.$_GET['qs'][1]; ?>">&#9668; Retour à <?php echo $data['cours']['nom']; ?></a></h1>
		<?php
			if ($allowCreation){
				echo '<a class="openInMenuBar btn color_background" style="float:right;" href="'.$_GET['data'].'/edit" style="top:166px;"><strong>Modifier la leçon</strong>Nom et description</a>';
			}else if ($data['cours']['collaborateur']['nom']){
				echo "<address>par <strong>".$data['cours']['collaborateur']['nom']."</strong></address>"; 
			}
		?>
		<h2><?php

			echo $data['lecon']['nom']; 
		?></h2>
	</header>

<p>Veuillez compléter la leçon précédente</p>

</article>