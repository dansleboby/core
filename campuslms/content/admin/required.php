<?php
	$accepted = array("admin","sadmin");

	if (in_array($_SESSION['user_level'], $accepted)) {
		switch($_SESSION['accountType']){
			case "campus":
				$mod = array(
					'defaultView'=>'index',
					'404View'=>'index'
				);

				//Translate views name ($_GET[qs][1]). Other QS content should be handled BY THE CODE, since they are sort of variables
					$arr = array(
							'home'=>'index'
						);
					$_GET['qs'][1] = findInArray($_GET['qs'][1],$arr);

					//Load required files (for ALL the module's views)
				//		$_req['class'][] = 'requiredClass'
				//		$_req['control'][] = 'requiredControl'
				//		$_req['js'][] = 'requiredJs'
				//		$_req['css'][] = 'requiredCss'
			break;
			case 'cie':
				$mod = array(
					'defaultView'=>'index',
					'404View'=>'index'
				);

				//Translate views name ($_GET[qs][1]). Other QS content should be handled BY THE CODE, since they are sort of variables
					$arr = array(
							'home'=>'index'
						);
					$_GET['qs'][1] = findInArray($_GET['qs'][1],$arr);
			break;
		}
	}else{
		$_param['module'] = "error";
		$_GET['qs'][1] = "403";
	}
?>