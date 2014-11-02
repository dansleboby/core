<?php
	$accepted = array("collaborateur","admin","sadmin");

	if (in_array($_SESSION['user_level'], $accepted) && $_SESSION['accountType'] == "campus"){
		if ($_SESSION['user_id'] > 0){
			$mod = array(
				'defaultView'=>'404',
				'404View'=>'404'
			);

	/*		if ($_GET['qs'][2] == "lecon" && isset($_GET['qs'][3])){
				$mod['defaultView'] = 'lecon';
				$mod['404View'] = 'lecon';
			}else if($_GET['qs'][1]) {*/
				$mod['defaultView'] = 'index';
				$mod['404View'] = 'index';
	//		}
		}else{
			$_param['module'] = "error";
			$_GET['qs'][1] = "403";
		}
	}else{
		$_param['module'] = "error";
		$_GET['qs'][1] = "403";
	}
?>