<!DOCTYPE html>
<html<?php echo (($_SESSION['user_level'] == "etudiant")?' class="mobilable"':''); ?>>
	<head>
		<meta charset="utf-8"/>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.js"></script>
		<meta http-equiv="X-UA-Compatible" content="chrome=1">
		<base href="<?php echo SITE_URL; ?>"/>
		<meta name="viewport" content="minimal-ui,initial-scale=1.0,maximum-scale=1.0,width=device-width">
		<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,600" rel="stylesheet" type="text/css">
		<link rel="stylesheet" media="screen" href="campuslms/template/default/style/style.css"/>
		<link rel="stylesheet" media="screen" href="campuslms/template/default/style/style.php"/>
		<link rel="stylesheet" media="screen" href="campuslms/lib/jqueryui/jquery-ui.css"/>
		<script type="text/javascript" src="campuslms/lib/upload/plupload/plupload.full.js"></script>
		<link rel="shortcut icon" href="fav.ico">
		<?php
			echo $siteHeader;
		?>
		<script src="campuslms/template/default/script/easing.js"></script>
		<script src="campuslms/template/default/script/script.js"></script>
		<script src="campuslms/core/script/script.js"></script>
		<script src="campuslms/lib/tinymce/jquery.tinymce.min.js"></script>

		<!--[if lt IE 9]>
			<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
	</head>
	<body class="<?php echo implode(' ', $_GET['qs']); ?>">
		<script>
			//Mark the body as «loading»
			$('body').addClass('loading');
			var SITE_URL = "<?php echo SITE_URL; ?>";
		</script>
		<header class="color_background">
			<h1>Campus LMS</h1>
			<div id="UIBackButton">◄ Retour</div>
		</header>
		<aside class="color_background">
			<div id="sidebar">
				<header></header>
				<nav>
					<?php
						echo $siteSidebar;
					?>
				</nav>
			</div>
				<?php
					if ($siteFooter){
						echo "			<footer class=\"color_background color_border\"><div>".$siteFooter."</div></footer>";
					}
				?>
		</aside>
		<section class="color_text">
			<?php
				echo $siteContent;
			?>
		</section>
		<div id="loader"></div>
		<noscript>
			<div id="fatalerror">
				<div>
					<h1>Votre navigateur n'est pas compatible avec la plateforme Campus LMS.</h1>
					<p>Pour accéder à la plateforme, assurez-vous d'utiliser un navigateur compatible avec les technologies suivantes, et que celles-ci sont activées : 
					<ul>
						<li>Javascript</li>
						<li>HTML5</li>
						<li>CSS3</li>
					</ul>
					</p>
				</div>
			</div>
		</noscript>
		<?php 
//			echo "<pre>";
//				print_r($_SESSION); 
//			echo "</pre>";
		?>
		<script src="campuslms/template/default/script/placeholder.js"></script>
	</body>
</html>