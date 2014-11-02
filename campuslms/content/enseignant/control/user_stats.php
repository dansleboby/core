<?php
	$req = "SELECT * FROM logs WHERE uid='".$_GET['qs'][3]."' ORDER BY id DESC";
	$query = $mysqli->query($req);
	$data['logs'] = array();
	while($res = $query->fetch_array(MYSQLI_ASSOC)){
		$data['logs'][] = $res;
	}
?>