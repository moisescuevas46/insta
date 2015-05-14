<?php 
set_time_limit(0);
ini_set('default_socket_timeout', 300);
session_start();

define('clientID', 'fca09d6bd4184cfe88a35f47502026f1');
define('clientSecret', '3bd4400e6c034d359f5b49edf5939610');
define('redirectURI', 'http://localhost/appacademyapi/insta.php');
define('ImageDirectory', 'pics/');

function connectToInstagram($url){
	$ch = curl_init();

	curl_setopt_array($ch, array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_SSL_VERIFYHOST => 2,
	));
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}

function GetUserID($userName){
	$url = 'http://api.instagram.com/v1/users/search?q='.$userName.'&client_id='.clientID;
	$instagramInfo = connectToInstagram($url);
	$results = json_decode($instagramInfo, true);

	return $results['data']['0']['id'];
}	

function printImages($userID){
	$url = 'http//api.instagram.com/v1/users/'.$userID. '/media/recent?client_id='.clientID.'&count=5';
	$instagramInfo = connectToInstagram($url);
	$results = json_decode($instagramInfo,true);

	foreach ($results['data'] as $items){
		$image_url = $items['images']['low_resolution']['url'];

		echo '<img src=" '.$image_url.' "/></br>';
	}
}


if (isset($_GET['code'])){
	$code = ($_GET['code']);
	$url = 'https://api.instagram.com/oauth/access_token';
	$access_token_settings = array('client_id' => clientID,
									'client_secret' => clientSecret,
									'grant_type' => 'authorization_code',
									'redirect_uri' => redirectURI,
									'code' => $code
								);

	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $access_tokem_settings);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);

$result = curl_exec($curl);
curl_close($curl);

$results = json_decode($result,true);

$userName = $results['user']['username'];

$userID = getUserID($userName);

printImages($userID);
}
else{
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="">
	<title></title>
</head>
<body>
	<a href="https:api.instagram.com/oauth/authorize/?client_id=<?php echo clientID;?>&redirect_uri=<?php echo redirectURI?>&response_type=code">LOGIN</a>
	<script src="js/main.js"></script>
</body>
</html>