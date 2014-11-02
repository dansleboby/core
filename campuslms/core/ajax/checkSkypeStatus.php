<?php
	$url = "http://mystatus.skype.com/".$_POST['username'].".xml";

	$curl = curl_init(); 
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$data = curl_exec($curl);
	curl_close($curl);
	$pattern = '/xml:lang="en">(.*)</';
	preg_match($pattern,$data, $match);

	$status = strtolower($match[1]);

	switch($status){
		case 'online':
			$text = "En ligne";
		break;
		case 'offline':
			$text = "Hors ligne";
		break;
		default:
			$text = "Status inconnu";
		break;
	}

	$ret = array("id"=>$_POST['id'], "username"=>$_POST['username'], "status"=>$status, "text"=>$text);
	echo json_encode($ret);
?>