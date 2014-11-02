<?php
	require('../init.php');
	require('../functions.php');

	if ($_POST['ajax']){
	//Init file upload

		$mysqli = dbconnect();
		$req = "INSERT INTO uploadRef SET uid='".$_SESSION['user_id']."', dateCreated=NOW(), etat='attente'";
		$mysqli->query($req);

		$_POST['ref'] = $mysqli->insert_id;

		$mysqli->close();

		echo json_encode($_POST);
	}else{
	//Save file upload
		//Get file location
		$mysqli = dbconnect();
		$req = "SELECT * FROM uploadRef WHERE id='".$_POST['uploadId']."'";
		$query = $mysqli->query($req);
		$res = $query->fetch_array(MYSQLI_ASSOC);

		//Make sure we're still the same user..
		if ($res['uid'] == $_SESSION['user_id']){
			$targetDir = '../../../data/temp/';

			switch($res['etat']){
				case 'attente':
					$req = "UPDATE uploadRef SET etat='envoye', dateUploaded=NOW(), name='".$_POST['name']."' WHERE id='".$_POST['uploadId']."'";
					$mysqli->query($req);
				break;
				case 'recu':
					exit();
				break;
			}

			$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
			$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
			$fileName = $_POST['uploadId'];

			$filePath = $targetDir.$fileName;

			// Create target dir
			if (!file_exists($targetDir))
				@mkdir($targetDir);

			// Look for the content type header
			if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
				$contentType = $_SERVER["HTTP_CONTENT_TYPE"];

			if (isset($_SERVER["CONTENT_TYPE"]))
				$contentType = $_SERVER["CONTENT_TYPE"];

			// Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
			if (strpos($contentType, "multipart") !== false) {
				if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
					// Open temp file
					$out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
					if ($out) {
						// Read binary input stream and append it to temp file
						$in = fopen($_FILES['file']['tmp_name'], "rb");

						if ($in) {
							while ($buff = fread($in, 4096))
								fwrite($out, $buff);
						} else
							die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
						fclose($in);
						fclose($out);
						@unlink($_FILES['file']['tmp_name']);
					} else
						die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
				} else
					die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
			} else {
				// Open temp file
				$out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
				if ($out) {
					// Read binary input stream and append it to temp file
					$in = fopen("php://input", "rb");

					if ($in) {
						while ($buff = fread($in, 4096))
							fwrite($out, $buff);
					} else
						die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');

					fclose($in);
					fclose($out);
				} else
					die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
			}

$t = "0";

			// Check if file has been uploaded
			if (!$chunks || $chunk == $chunks - 1) {
$t .= "a";
				// Strip the temp .part suffix off 
				if ($res['location']){
$t .= "b";
					rename("{$filePath}.part", dirname(__FILE__)."/../../../".$res['location']);

					$req = "UPDATE uploadRef SET etat='recu' WHERE id='".$_POST['uploadId']."'";

					fixImg("../../".$res['location'], $res['name']);

				}else{
$t .= "c";
					$req = "UPDATE uploadRef SET location='".substr($filePath, 6)."', etat='recu' WHERE id='".$_POST['uploadId']."'";
				}

				$mysqli->query($req);
			}

			// Return JSON-RPC response
			die('{"jsonrpc" : "2.0", "result" : null, "id" : "id", "post":"'.print_r($_POST,true).'", "req":"'.print_r($_REQUEST,true).'", "res":"'.print_r($res,true).'","t":"'.$t.'","req":"'.$req.'"}');
		}else{
			//Security error...

		}
	}
?>