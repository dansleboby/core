<?php
	require_once(dirname(__FILE__).'/../../core/init.php');
	require_once(dirname(__FILE__).'/../../core/functions.php');

	$res = array();

	if (!$mysqli)
		$mysqli = dbconnect();

//	print_r($_POST);

	$req = "SELECT * FROM messages WHERE ((id_from='".$_SESSION['user_id']."' AND id_to='".$_POST['id_user']."') OR (id_from='".$_POST['id_user']."' AND id_to='".$_SESSION['user_id']."'))".(($_POST['newest'])?" AND id > ".$_POST['newest']."":"")." ORDER BY date ASC";

//echo "\n\r\n\r".$req;

	$query = $mysqli->query($req);
	while($line = $query->fetch_array(MYSQLI_ASSOC)){
		$line['time'] = date('Y-m-dTH:i:s',strtotime($line['date']));
		$line['timed'] = date('Y-m-d H:i:s',strtotime($line['date']));

		//Remove notifications
		if ($line['id_to'] == $_SESSION['user_id']){
			$req = "UPDATE notifications SET lu='1' WHERE id_ref='".$line['id']."' AND type='message'";

			$mysqli->query($req);
		}else{
			$line['self'] = true;
		}

		$res[] = $line;
	}

	//Mark messages as read
	$req = "UPDATE messages SET lu=NOW() WHERE id_from='".$_POST['id_user']."' AND id_to='".$_SESSION['user_id']."' AND lu<='0'";

	$mysqli->query($req);
	$mysqli->close();

	if ($_POST['isDaemon']){
		echo json_encode($res);
		exit();
	}
?>