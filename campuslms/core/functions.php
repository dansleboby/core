<?php

/* Connexion a la DB */
	function dbconnect(){
		$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT);
		$mysqli->set_charset('utf8');
		if (mysqli_connect_errno()) {
			echo '<div style="position:fixed;top:0;left:0;width:100%;height:100%;background-color:rgb(1,145,214);background-color:rgba(1,145,214, 0.8);z-index:999;font-family: \'Open Sans\', \'Lucida Sans Unicode\', \'Lucida Grande\', sans-serif;"><div style="position:absolute;top:50%;left:50%;width:400px;height:200px;padding:50px;margin:-150px -250px;color:#FFF;color:rgba(255,255,255,0.7);font-size:14px;"><h1 style="margin:0;padding:0;font-size:2em;">Une erreur est survenue.</h1><p>La plateforme est momentan&eacute;ment indisponible. Veuillez r&eacute;essayer plus tard.</p></div></div>';
//			printf("&eacute;chec de  la connexion! : %s\n", mysqli_connect_error());
			exit();
		}
		
		return $mysqli;
	}

/*
	EscapeIt
*/

	function escape($str){
		return htmlentities(utf8_decode($str));
//		return str_replace('"', '\"', $str);
	}

	function escapeIt($ctn, $db=null, $type=null, $noTags){
		GLOBAL $_ORI;

		$thedb = $db;

		if ($thedb == null){
			$thedb = dbconnect();
		}
		if (is_array($ctn)){
			foreach($ctn as $k => $v) {
				if ($type){
					$_ORI[$type][$k] = $v;
				}
//				$ctn["_".$k."_"] = $v;
				$ctn[$k] = escapeIt($v, $db, $type, $noTags);
			}
		}else{
			if ($noTags){
				$ctn = strip_tags($ctn, '<b><i><em><strong><ul><li><ol><u><strike><del><ins><sup><sub><a><pre><p><span>');
			}

			$ctn = $thedb->real_escape_string(convert_word_quotes($ctn));
		}

		if ($db == null){
			$thedb->close();
		}

		return $ctn;
	}

/*Fonction qui encode le mot de passe*/
	function getpassword($user, $pass=undefined){
		if ($pass != undefined){
			return sha1('Ek59623*8@1{5L7-' .strtolower($user). '-*236>byQF2,1|8>-' .sha1($pass). '-Di9+$1Xsi@x"~Em');
		}else{
			$mysqli = dbconnect();
			$req = "SELECT pass FROM users WHERE id='".$user."'";
			$query = $mysqli->query($req);
			$res = $query->fetch_array(MYSQLI_ASSOC);
			$mysqli->close();

			return $res['pass'];
		}
	}

/*
	function drawHeader
*/

function drawHeader(){
	GLOBAL $_req, $_param;
	$header = "<title>" .(!empty($_param['title']) ? $_param['title']. " - ":"").SITE_NAME."</title>
	<meta name=\"Description\" content=\"".$_param['description']."\"/><meta name=\"keywords\" content=\"".$_param['description']."\"/>";

	$suffix = "";
	if (SITEMODE == "dev"){
		$suffix = "?t".time();
	}

	//Include CSS
		foreach($_req['css'] AS $file){
			$header .=  '		<link rel="stylesheet" media="screen" href="'.$file.$suffix.'"/>';				
		}
	//Include JS
		foreach($_req['js'] AS $file){
			$header .= '		<script src="'.$file.$suffix.'"></script>';
		}

	return $header;
}


/*
	function drawTemplate
*/
function drawTemplate($siteContent=null){
	//On charge le data
//	$siteData = getSiteData('data');
	require(dirname(__FILE__).'/../content/content/data/index.php');
	$siteData = $res;

	$siteHeader = drawHeader();

	if ($_POST['ajax']){
		$content = array();

		if ($siteHeader)
			$content['header'] = $siteHeader;
		if ($siteContent)
			$content['content'] = $siteContent;

		$content['data'] = $siteData;

		echo json_encode($content);
	}else{
		//On charge la sidebar

		if ($siteSidebar == null){
			$siteSidebar = getSiteData('sidebar',$siteData);
		}

		//On charge le footer
		if ($siteFooter == null){
			$siteFooter = getSiteData('footer',$siteData);
		}

		//Show content
		include(dirname(__FILE__)."/../template/index.php");
	}
}

function getSiteData($zone,$data=null){
	ob_start();
		require(dirname(__FILE__).'/../content/content/'.$zone.'/index.php');
	return ob_get_clean();
}

/* 
	Convertir ID vers petit ID, et vice versa 
*/

function pid2id($pid){
	static $chars = "j97de8qabfh6yz0vrw2s3km1tu4cpn5";
	if ($pid === '0')
		return 0;

	$len = strlen($pid);
	$num = strpos($chars, $pid[$len-1]);
	for ($i=$len-2,$j=1; $i>=0; $i--,$j++){
			$cval = strpos($chars, $pid[$i]);
			$num += pow(strlen($chars), $j)*$cval;
	}
	return ($num-2345)/3;
}

function id2pid($id){
	static $chars = "j97de8qabfh6yz0vrw2s3km1tu4cpn5";

	$id = 2345+($id*3);

	$str = '';
	$x=0;

	do {
		$idx = ($id % strlen($chars));
		$str .= $chars[$idx];
		$id = floor($id/strlen($chars));
	} while ($id > 0);

	return strrev($str);
}

/*
SaveLog
*/

function saveLog($texte, $ref=null, $ref2=null, $uid=null, $details=null){
/*
	login
	logout

	logErr

	newUser
	newGroup
	newCours

	user2group
	formation2group


*/
	if ($uid == null){
		$uid = $_SESSION['user_id'];
	}

	$mysqli = dbconnect();

	$req = "INSERT INTO logs SET ".(($_SESSION['accountType'] == "cie")?'cie=1, ':'')."uid='".$uid."', texte='".$texte."', ref='".$ref."', details='".$details."', ref2='".$ref2."', ip='".$_SERVER['REMOTE_ADDR']."', date=NOW()";

	$mysqli->query($req);
}


/*
	findInArray function.. utilisé entre autre pour traduction.
*/
	function findInArray($var, $arr=array()){
		if (isset($arr[$var]))
			return $arr[$var];

		return $var;
	}

/*
	Convert word "not-so-smart" quote
*/
function convert_word_quotes($string) { 
	$search = array(chr(145), 
					chr(146), 
					chr(147), 
					chr(148), 
					chr(151)); 

	$replace = array("'", 
					 "'", 
					 '"', 
					 '"', 
					 '-'); 

	return str_replace($search, $replace, $string); 
}

/*
	shuffle associative array
*/
function shuffle_assoc($array, $recursive = false) { 
	if (!is_array($array)) return $array; 

	$keys = array_keys($array); 
	shuffle($keys); 
	$random = array(); 
	foreach ($keys as $key) {
		if ($recursive){
			$array[$key] = shuffle_assoc($array[$key], true);
		}
		
		$random[$key] = $array[$key]; 
	}

	return $random; 
} 

/* 
	File upload management
*/

function return_bytes($val) {
    $val = trim($val);
    $last = strtolower($val[strlen($val)-1]);
    switch($last) {
        // The 'G' modifier is available since PHP 5.1.0
        case 'g':
            $val *= 1024;
        case 'm':
            $val *= 1024;
        case 'k':
            $val *= 1024;
    }

    return $val;
}

function createLocation($finalLocation){
	$finalLocation = dirname(__FILE__)."/../../".implode('/', $finalLocation);

	if (!is_dir($finalLocation))
		return mkdir($finalLocation,0777,true);

	return true;

/*	$loc = explode("/", $finalLocation);
	$curLoc = dirname(__FILE__)."/../";

	$err = 0;

	foreach ($loc AS $l){
		$curLoc .= "/".$l;
		if (!is_dir(curLoc)){
			if (!mkdir($curLoc))
				$err++;
		}
	}

	if (err)
		return -1;
	return 1;*/
}

function manageUpload($postName, $where, $originalName, $replace=false, $action=null){
	$loc = explode('/',$where);
	array_pop($loc);
	createLocation($loc);

	if ($replace){
	//Make sure the file don't exist
		$name = substr($where, 0, strrpos(".", $where));
		$oName = $name;
		$ext = substr($where, strrpos(".", $where)+1);
		$i = 1;

		while (file_exists(dirname(__FILE__)."/../../".$name.".".$ext))  {
			$i++;
			$name = $oName.$i;
		}
		$where = $name.".".$ext;
	}

	//If we got the posted file
	if (is_uploaded_file($_FILES[$postName])){
		//save it
		if(!move_uploaded_file($_FILES[$postName]['tmp_name'], dirname(__FILE__)."/../../".$where)) {
			//ERROR
			return false;
		}

		$originalName = $_FILES[$postName]['name'];

		$mysqli = dbconnect();
		$req = "UPDATE uploadRef SET location='".$where."', name='".$originalName."' WHERE id='".$ref."'";
		$mysqli->query($req);
		$mysqli->close();

		fixImg(dirname(__FILE__)."/../../".$where, $originalName);

	}else{
		$nb = $_POST[$postName.'uploadNb'];
		$ref = $_POST['fileRef'.$nb];

		//Check the ref file
		$mysqli = dbconnect();
		$req = "SELECT * FROM uploadRef WHERE id='".$ref."'";
		$query = $mysqli->query($req);
		$res = $query->fetch_array(MYSQLI_ASSOC);

		if ($res['etat'] == 'recu'){
		//If already uploaded, move the file to his where value
			if (!rename($res['location'], dirname(__FILE__)."/../../".$where)){
				//ERROR
				return false;
			}

			fixImg(dirname(__FILE__)."/../../".$where, $originalName);
		}else{
		//If not yet uploaded, save the where value
			$req = "UPDATE uploadRef SET location='".$where."', name='".$originalName."' WHERE id='".$ref."'";
			$mysqli->query($req);
			$mysqli->close();

			//Create temp file to reserve space.
			$file = fopen(dirname(__FILE__)."/../../".$where, 'w') or die("can't open file");
			fclose($file);


		}

		return true;
	}
}

function fileExt($filename) {
    $pos = strrpos($filename, '.');
    if($pos===false) {
        return false;
    } else {
        return strtolower(substr($filename, $pos+1));
    }
}


function fixImg($fileUrl, $originalName){
	$imgExt = array('jpg','jpeg','gif','png');

	$ext1 = fileExt($fileUrl);
	$ext2 = fileExt($originalName);

	if (in_array($ext1, $imgExt)){
		$img = imageCreateFrom($fileUrl,$ext2);

		switch($ext1){
			case 'jpg':
			case 'jpeg':
				$img = imagejpeg($img, $fileUrl, 80);
			break;
			case 'gif':
				$img = imagegif($img, $fileUrl);
			break;
			case 'png':
				$img = imagepng($img, $fileUrl, 2);
			break;
			default:
				$img = false;
			break;
		}

		return $img;
	}

	return true;
}

function imageCreateFrom($file, $ext=null){
	$img = imagecreatefromstring(file_get_contents($file));

	if (!$img){
		if ($ext == null){
			$ext = strtolower(substr($file, strrpos(".", $file)+1));
		}

		switch($ext){
			case 'jpg':
			case 'jpeg':
				$img = imagecreatefromjpeg($file);
			break;
			case 'gif':
				$img = imagecreatefromgif($file);
			break;
			case 'png':
				$img = imagecreatefrompng($file);
			break;
			default:
				$img = null;
			break;
		}
	}

	if ($img){
		return $img;
	}
	return false;
}

function humanFilesize($filename, $decimals = 2) {
	$bytes = filesize($filename);
    $size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
}


function getFirstLetters($str){
	$str = multiexplode(array(' ','-',"'"),utf8_decode($str));

	$ret = "";
	while(count($str) > 0){
		$ret .= utf8_encode(strtoupper($str[0][0]));
		array_shift($str);
	}

	return $ret;
}

function multiexplode ($delimiters,$str) {
	$str = str_replace($delimiters, $delimiters[0], $str);
	return explode($delimiters[0], $str);
}

function stripAccents($str){
	$str = utf8_decode($str);

	return strtr($str,utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'),
utf8_decode('aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY'));
}

function createValidateKey($keyword, $salt){
	if (!$_SESSION['spice']){
		$_SESSION['spice'] = uniqid();
	}

	return "::".$keyword.":".sha1('82yrf78iygsnw9e' .$keyword. '_' .md5($salt).$_SESSION['spice']);
}

function checkValidateKey($value, $salt=null){
	if ($salt == null){
		$salt = $_POST['salt'];
	}

	if (strlen($value) > 2){
		if (substr($value, 0, 2) == "::"){
			$val = substr($value, 2);
			$val = explode(":", $val);

			$key = createValidateKey($val[0],$salt);

			if ($key == $value){
				//It's good !

				return $val[0];
			}else{
				//Wrong key
				exit('Erreur 3');
			}
		}else{
			//No key
			exit('Erreur 2');
		}
	}else{
		//Too short
		exit('Erreur 1');
	}

	return $value;
}

function nl2br4txt($str){
	if (trim(substr($str, 0, 1)) == '<'){
		return $str;
	}else{
		return nl2br($str);
	}
}

?>