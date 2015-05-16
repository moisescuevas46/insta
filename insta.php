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

function getUserID($userName){
	$url = 'https://api.instagram.com/v1/users/search?q=' . $userName . '&client_id=' .clientID;
	$instagramInfo = connectToInstagram($url);
	$results = json_decode($instagramInfo, true);

	return $results['data']['0']['id'];
}

function printImages($userID)
{
	$url = 'https://api.instagram.com/v1/users/' . $userID . '/media/recent?client_id='.clientID . '&count=5';
	$instagramInfo = connectToInstagram($url);
	$results = json_decode($instagramInfo, true);

	//Parse through thet information one by one
	foreach($results['data'] as $items)
	 {
	 	$image_url = $items['images']['low_resolution']['url']; //go through all of the results and give back the url of those pictures because we want to save it in the php server.
	 	echo '<img src=" '. $image_url .' "/><br/>';
	 	savePictures($image_url);
	 }
}

function savePictures($image_url){
	//echo $image_url . '<br>';
	$filename = basename($image_url);
	//echo $filename . '<br>';

	$destination = ImageDirectory . $filename;
	file_put_contents($destination, file_get_contents($image_url));
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
	curl_setopt($curl, CURLOPT_POSTFIELDS, $access_token_settings);
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
<link rel="stylesheet" type="text/css" href="insta.css">
	<title></title>
	<header>
				<div class="navWrapper" id="home">
					<div class=" clearfix">
						<h2 class="companyName">Moys ayye-okay project!</h2>
						<nav class="mainNav clearfix">
							<ul>
								<li><a href="#work" class="smoothScroll"><a href="https:api.instagram.com/oauth/authorize/?client_id=<?php echo clientID;?>&redirect_uri=<?php echo redirectURI?>&response_type=code">LOGIN</a>
								<script src="js/main.js"></script></a></li>

							</ul>
						</nav>
					</div><!-- end .innerWrapper -->
				</div><!-- end of .navWrapper -->

				<section class="hero">
					<div class="innerWrapper">
						<h1>Moy's Awesome Insta!</h1>
						<h3>Reps For Jesus!</h3>
						<h3>Na Meen Cuz Cuz?</h3>
					</div><!-- end .innerWrapper (.hero) -->
				</section>
			</header>
</head>
<body>
</body>
</html>

<?php  
}
?>

