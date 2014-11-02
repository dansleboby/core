<?php
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


		$_param['askedView'] = $_param['view'];	
		$_req['control'][] = "/content/".$_param['module']."/control/base.php";		

//		$_param['view'] = "base";
	}else{
		$_param['module'] = "error";
		$_GET['qs'][1] = "403";
	}
?>