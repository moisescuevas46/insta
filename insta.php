<?php 
set_time_limit(0);
ini_set('default_socket_timeout', 300);
session_start();

define('client_ID', 'c73d173254d844b89d8117954f97d9ee');
define('client_Secret', '971766cd8c4f4af7b7a6ff36f32b68b0');
define('redirectURI', 'http://localhost/appacademyapi/insta.php');
define('ImageDirectory', 'pics/');
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="">
	<title></title>
</head>
<body>
	<a href="http:api.instagram/oauth/authorize/?client_id=xxx&redirect_uri=xxx&response_type=code">LOGIN</a>
	<script src="js/main.js"></script>
</body>
</html>