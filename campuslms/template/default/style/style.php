<?php
	header('Content-Type: text/css');

	require("../../../core/init.php");

	function hex2rgb($hex) {
	   $hex = str_replace("#", "", $hex);

	   if(strlen($hex) == 3) {
	      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
	      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
	      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
	   } else {
	      $r = hexdec(substr($hex,0,2));
	      $g = hexdec(substr($hex,2,2));
	      $b = hexdec(substr($hex,4,2));
	   }
	   $rgb = array($r, $g, $b);
	   //return implode(",", $rgb); // returns the rgb values separated by commas
	   return $rgb; // returns an array with the rgb values
	}

	$theme = json_decode(file_get_contents("../../../../data/settings/template_default.json"),true);

	if ($_SESSION['id_cie'] != 0){
		$color = $theme['settings']['couleur_entreprise_'.$_SESSION['user_level']]['value'];
	}else{
		$color = $theme['settings']['couleur_'.$_SESSION['user_level']]['value'];
	}

	if (!$color){
		$color = $theme['settings']['couleur_default']['value'];
	}
	if (!$color){
		$color = $theme['settings']['couleur_default']['default'];
	}


	$color = hex2rgb($color);

/*
	$color = array(85,85,85);

	switch($_SESSION['user_level']){
		case 'sadmin':
			$color = array(45,12,12);
		break;
		case 'admin':
			$color = array(17,17,25);
		break;
		case 'collaborateur':
			$color = array(255,62,0);
		break;
		case 'enseignant':
			$color = array(0,63,135);
		break;
		default:
			$color = array(1,145,214);
		break;
	}*/

	if ($theme['settings']['couleur_complementaire']['value'] == "1"){
		$rColor = array(255-$color[0],255-$color[1],255-$color[2]);
	}else{
		$rColor = array(128,128,128);
	}
?>

/*color*/
.color_background, hr{
	background-color:rgb(<?php echo implode(',',$color); ?>) !important;
	color:#FFFFFF;
}
.color_border, hr{
	border-color:rgb(<?php echo implode(',',$color); ?>) !important;
}
.color_text, body>section a{
	color:rgb(<?php echo implode(',',$color); ?>);
}

/*opacity*/
.color_background.alpha50{
	background-color:rgba(<?php echo implode(',',$color); ?>, 0.5) !important;
}
.color_background.alpha25{
	background-color:rgba(<?php echo implode(',',$color); ?>, 0.25) !important;
}

/*rcolor*/
.rcolor_background{
	background-color:rgb(<?php echo implode(',',$rColor); ?>) !important;
	color:#FFFFFF;
}
.rcolor_border{
	border-color:rgb(<?php echo implode(',',$rColor); ?>) !important;
}
.rcolor_text{
	color:rgb(<?php echo implode(',',$rColor); ?>) !important;
}

/*selection*/
::selection {
	background-color:rgb(<?php echo implode(',',$rColor); ?>) !important;
	}
::-moz-selection {
	background-color:rgb(<?php echo implode(',',$rColor); ?>) !important;
}











/*noscript*/
div#fatalerror{
	position:fixed;
	top:0;
	left:0;
	right:0;
	bottom:0;
	border:8px solid white;
/*	width:100%;
	height:100%;*/
	background-color:rgb(<?php echo implode(',',$rColor); ?>) !important;
	background-color:rgba(<?php echo implode(',',$rColor); ?>,0.9) !important;
	z-index:9999;
}

div#fatalerror>div{
	position:absolute;
	top:50%;
	left:50%;
	width:400px;
	height:400px;
	padding:50px;
	margin:-250px -250px;
	color:#FFF;
	color:rgba(255,255,255,0.7);
	font-size:14px;
}

div#fatalerror>div>h1{
	margin:0;
	padding:0;
	font-size:2em;
}
