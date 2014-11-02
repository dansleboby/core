<?php
	if ($_SESSION['user_id'] > 0){
		$mod = array(
			'defaultView'=>'index',
			'404View'=>'index'
		);
	}else{
		$_param['module'] = "error";
		$_GET['qs'][1] = "403";
	}
?>