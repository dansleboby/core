<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
		<meta http-equiv="X-UA-Compatible" content="chrome=1">
		<base href="<?php echo SITE_URL; ?>"/>
		<meta name="viewport" content="minimal-ui,initial-scale=1.0,maximum-scale=1.0,width=device-width">
		<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,600" rel="stylesheet" type="text/css">
		<link rel="stylesheet" media="screen" href="campuslms/template/default/style/login.css"/>
		<link rel="stylesheet" media="screen" href="campuslms/template/default/style/style.php"/>
		<link rel="shortcut icon" href="fav.ico">
		<title><?php echo SITE_NAME." - ".SITE_TITLE; ?></title>
		<?php
//			echo $siteHeader;
		?>
		<script src="campuslms/template/default/script/login.js"></script>
		<!--[if lt IE 9]>
			<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
	</head>
	<body class="login color_background <?php echo implode(' ', $_GET['qs']); ?>">
		<script>
			//Mark the body as «loading»
			$('body').addClass('loading');
		</script>
		<header><h1>CampusLMS</h1></header>
		<section>
			<header>
				<div id="loginPersonnel" class="logintab active color_text" data-type="perso">Personnel</div>
				<div id="loginEntreprise" class="logintab inactive" data-type="cie">Entreprise</div>
			</header>
			<nav>
				<?php
					echo $siteSidebar;
				?>
			</nav>
		</section>
		<div id="fx"></div>
		<footer>
			<?php
				$file = "campuslms/version";

				$v = file_get_contents($file);
				echo "Bêta ".$v." - ".date('Y-m-d H:i',filemtime($file));
			?>
		</footer>
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
		<script src="campuslms/template/default/script/placeholder.js"></script>
	</body>
</html>