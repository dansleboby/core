<?php
	require_once(dirname(__FILE__).'/../../core/init.php');
	require_once(dirname(__FILE__).'/../../core/functions.php');

	if (!$mysqli)
		$mysqli = dbconnect();

	$req = "SELECT count(1) AS nb FROM notifications WHERE id_user='".$_SESSION['user_id']."' AND lu='0'";

	$query = $mysqli->query($req);
	$res = $query->fetch_array(MYSQLI_ASSOC);

	if ($_POST['prevNb'] == "")
		$_POST['prevNb'] = 0;

	$res['delta'] = $res['nb'] - $_POST['prevNb'];

	if ($res['nb'] > $_POST['prevNb']){
		$res['alert'] = $res['delta']+" nouvelle".(($delta>1)?'s':'')." notification".(($delta>1)?'s':'');
	}

	if ($_POST['isDaemon'])
		echo json_encode($res);
?>