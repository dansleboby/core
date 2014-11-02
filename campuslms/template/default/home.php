<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
		<meta http-equiv="X-UA-Compatible" content="chrome=1">
		<base href="<?php echo SITE_URL; ?>"/>
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

		<!--[if lt IE 9]>
			<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
	</head>
	<body class="<?php echo implode(' ', $_GET['qs']); ?>">
		<script>
			//Mark the body as «loading»
			$('body').addClass('loading');
		</script>
		<header>
			<div id="logo" class="color_background">
				<h1>Campus LMS</h1>
			</div>
		</header>
		<aside class="color_background">
			<header></header>
			<nav>
				<?php
					echo $siteSidebar;
				?>
			</nav>
				<?php
					if ($siteFooter){
						echo2 "			<footer>".$siteFooter."</footer>";
					}
				?>
		</aside>
		<section class="color_text">
			<?php
				echo $siteContent;
			?>
		</section>
		<div id="loader"></div>
	</body>
</html>