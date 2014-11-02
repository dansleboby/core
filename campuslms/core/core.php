<?php
error_reporting(E_ALL ^ E_NOTICE);
ini_set("display_errors", '1'); 

	$startTime = microtime();
	//Initialiser système
		require(dirname(__FILE__).'/init.php');
		require(dirname(__FILE__).'/functions.php');
		require(dirname(__FILE__).'/account.php');

	//Par sécurité et/ou paranoïa, on échappe tout!
	$_POST = escapeit($_POST, null, 'POST', true);
	$_GET = escapeit($_GET, null, 'GET', true);

	//Initialiser contenu - S'il n'y a rien en cours.
		if ($_GET['data'] == "")
			$_GET['data'] = "page/index";

	//Clôner et 'exploser' _GET['data'] vers _GET['qs'], pour avoir un backup 'navigable' du querystring
		$_GET['qs'] = explode('/', $_GET['data']);

	//S'il n'a pas suffisament d'élément pour générer un tableau, créer un tableau avec le seul élément présent.
		if (!is_array($_GET['qs']))
			$_GET['qs'] = array($_GET['data']);

	//Charger le contenu demandé, s'il existe.
		$_param['404'] = false;
		$_param['title'] = SITE_TITLE;
		$_param['description'] = SITE_DESCRIPTION;
		$_param['keywords'] = SITE_KEYWORDS;
		$_param['module'] = 'error';
		$_param['view'] = null;

	//Init data
		$data = array();

		//Vérifier si le contenu demandé existe
			if (is_dir(dirname(__FILE__)."/../content/".$_GET['qs'][0]))
				$_param['module'] = $_GET['qs'][0];
			
	//Charger la base du module
		require(dirname(__FILE__)."/../content/".$_param['module']."/required.php");

	//Trouvons notre view !
		if (!$_param['view'] && $_GET['qs'][1])
			$_param['view'] = $_GET['qs'][1];

		if ($_param['view'] == null && file_exists(dirname(__FILE__)."/../content/".$_param['module']."/view/".$mod['defaultView'].".php")){
			$_param['view'] = $mod['defaultView'];
		}else if (file_exists(dirname(__FILE__)."/../content/".$_param['module']."/view/".$_GET['qs'][1].".php") || file_exists(dirname(__FILE__)."/../content/".$_param['module']."/control/".$_GET['qs'][1].".php")){
			$_param['view'] = $_GET['qs'][1];			
		}else if ($mod['404View'] && ($_param['view'] != null)){
			$_param['view'] = $mod['404View'];
		}else{
			//On n'a pas trouvé le module... Affichons le module d'erreur (404)!
				$_param['module'] = 'error';
				require(dirname(__FILE__)."/../content/".$_param['module']."/required.php");
				$_param['view'] = $mod['defaultView'];
				$_param['404'] = true;
		}

	//Trouver automatiquement les classes, contenu, modules, js et css associé à notre vue.
		if (file_exists(dirname(__FILE__)."/../content/".$_param['module']."/class/".$_param['view'].".php"))
			$_req['class'][] = dirname(__FILE__)."/../content/".$_param['module']."/class/".$_param['view'].".php";		
		if (file_exists(dirname(__FILE__)."/../content/".$_param['module']."/control/".$_param['view'].".php"))
			$_req['control'][] = "/content/".$_param['module']."/control/".$_param['view'].".php";		
		if (file_exists(dirname(__FILE__)."/../content/".$_param['module']."/js/".$_param['view'].".js"))
			$_req['js'][] = "campuslms/content/".$_param['module']."/js/".$_param['view'].".js";		
	
	//Charger les controls
		foreach($_req['control'] AS $c){
			require(dirname(__FILE__)."/../".$c);
		}

	//Trouver les CSS liés à la vue
		if (file_exists(dirname(__FILE__)."/../content/".$_param['module']."/css/".$_param['view'].".css"))
			$_req['css'][] = "campuslms/content/".$_param['module']."/css/".$_param['view'].".css";

	//Si nous sommes dans une erreur 404, il est temps d'avertir le navigateur.
		if ($_param['404'])
			header("HTTP/1.0 404 Not Found");

	//On charge le contenu
		if (1){
			ob_start();
				require(dirname(__FILE__).'/../content/'.$_param['module']."/view/".$_param['view'].".php");
			$siteContent = ob_get_clean();

//			$siteContent .= "<!--".print_r($_GET, true);
//			$siteContent .= print_r($_POST,true)."-->";
		}

	//Il est temps d'afficher le contenu!
		echo drawTemplate($siteContent);
?>