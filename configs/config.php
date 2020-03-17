
<?php
	$db = mysqli_connect('localhost', 'rfluj', '772009', 'login_with_cookie');

	if (!$db) {
		echo mysqli_connect_error();
	}

	ini_set('display_errors', 1);
	error_reporting(E_ALL);

	$key         = "shamsali";
	$salt        = "shamsali";
	$name_cookie = "hafteha";
	$time_cookie = 86400 * 30 * 12;
	
	function encrypt($string, $key) {
		$string = rtrim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $string, MCRYPT_MODE_ECB)));
		return $string;
	}

	function decrypt($string, $key) {
		$string = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, base64_decode($string), MCRYPT_MODE_ECB));
		return $string;
	}

?>