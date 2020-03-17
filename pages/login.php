


<?php require_once '../configs/config.php' ?>

<?php
	if (!isset($_COOKIE[$name_cookie])) {
		if (isset($_POST['register'])) {
			$username    = $_POST['username'];
			$password    = encrypt($_POST['password'], $key);
			$remember_me = isset($_POST['remember_me']);
			if (empty($username) or empty($password)) {
				echo "tamam field ha ra por konid.";
			} else {
				$query = mysqli_query($db, "SELECT * FROM users WHERE username='$username'");
				if (mysqli_num_rows($query) != 0) {
					echo "in username gablan gerefte shode ast.";
				} else {
					mysqli_query($db, "INSERT INTO users (username, password) VALUES ('$username', '$password')");
					$query = mysqli_query($db, "SELECT * FROM users WHERE username='$username' AND password='$password'");
					if ($query) {
						$row      = $query->fetch_assoc();
						$id       = $row['id'];
						$q        = mysqli_query($db, "SELECT * FROM token WHERE id='$id'");
						if (mysqli_num_rows($q) == 1) {
							$r    = $q->fetch_assoc();
							$Uuid = $r['Uuid'];
							if ($remember_me) {
								setcookie($name_cookie, $Uuid, time()+$time_cookie, '/');
								$_COOKIE[$name_cookie] = $Uuid;
							} else {
								setcookie($name_cookie, $Uuid, 0, '/');
								$_COOKIE[$name_cookie] = $Uuid;
							}
						} else {
							$encoding = uniqid(uniqid(), true);
							mysqli_query($db, "INSERT INTO token (id, Uuid) VALUES ('$id', '$encoding')");
							if ($remember_me) {
								setcookie($name_cookie, $encoding, time()+$time_cookie, '/');
								$_COOKIE[$name_cookie] = $encoding;
							} else {
								setcookie($name_cookie, $encoding, 0, '/');
								$_COOKIE[$name_cookie] = $encoding;
							}
						}
					}
				}
			}
		} elseif (isset($_POST['login'])) {
			$username    = $_POST['username'];
			$password    = encrypt($_POST['password'], $key);
			$remember_me = isset($_POST['remember_me2']);
			if (empty($username) or empty($password)) {
				echo "tamam field ha ra por konid.";
			} else {
				$query = mysqli_query($db, "SELECT * FROM users WHERE username='$username' AND password='$password'");
				if (mysqli_num_rows($query) == 0) {
					echo "in username ba in password gablan sabt nam nakarde ast.";
				} else {
					// mysqli_query($db, "INSERT INTO users (username, password) VALUES ('$username', '$password')");
					// $query = mysqli_query($db, "SELECT * FROM users ORDER BY id DESC LIMIT 1");
					if ($query) {
						$row = $query->fetch_assoc();
						$id  = $row['id'];
						$q   = mysqli_query($db, "SELECT * FROM token WHERE id='$id'");
						if (mysqli_num_rows($q) == 0) {
							$encoding = uniqid(uniqid(), true);
							mysqli_query($db, "INSERT INTO token (id, Uuid) VALUES ('$id', '$encoding')");
							if ($remember_me) {
								setcookie($name_cookie, $encoding, time()+$time_cookie, '/');
								$_COOKIE[$name_cookie] = $encoding;
							} else {
								setcookie($name_cookie, $encoding, 0, '/');
								$_COOKIE[$name_cookie] = $encoding;
							}
						} else {
							$r    = $q->fetch_assoc();
							$Uuid = $r['Uuid'];
							if ($remember_me) {
								setcookie($name_cookie, $Uuid, time()+$time_cookie, '/');
								$_COOKIE[$name_cookie] = $Uuid;
							} else {
								setcookie($name_cookie, $Uuid, 0, '/');
								$_COOKIE[$name_cookie] = $Uuid;
							}
						}
					// 	if ($remember_me) {
					// 		setcookie($name_cookie, $id, time()+$time_cookie, '/');
					// 		$_COOKIE[$name_cookie] = $id;
					// 	} else {
					// 		setcookie($name_cookie, $id, 0, '/');
					// 		$_COOKIE[$name_cookie] = $id;
					// 	}
					}
				}
			}
		}
	}
	if (isset($_POST['exit'])) {
		echo "string";
		unset($_COOKIE[$name_cookie]);
		setcookie($name_cookie, false, time()-$time_cookie, '/');
	}
?>

<html>
<head>
	<title>login</title>
</head>
<body>
	<?php
		if (isset($_COOKIE[$name_cookie])) {
			$Uuid  = $_COOKIE[$name_cookie];
			$query = mysqli_query($db, "SELECT * FROM token WHERE Uuid='$Uuid'");
			if (mysqli_num_rows($query) == 0) {
				unset($_COOKIE[$name_cookie]);
				setcookie($name_cookie, false, time()-$time_cookie, '/');
				header('location: ./login.php');
				exit();
			} else {
				$row = $query->fetch_assoc();
				$id  = $row['id'];
				echo $id;
				$query = mysqli_query($db, "SELECT * FROM users WHERE id='$id'");
				if (mysqli_num_rows($query) == 0) {
					echo "<h1>useri vojod nadarad.goyi mage?khar sabt nam haken.</h1>
						<form action=","./login.php"," method=","post",">
							<input type=","submit"," name=","exit"," value=","exit",">
						</form>";
				} else {
					$row      = $query->fetch_assoc();
					$username = $row['username'];
					echo "<h1>wellcome $username.</h1>
						<form action=","./login.php"," method=","post",">
							<input type=","submit"," name=","exit"," value=","exit",">
						</form>";
				}
			}
		} else {
			echo "<form action=","./login.php"," method=","post",">
					<span>register</span>
					<br>
					<input type=","text"," name=","username"," placeholder=","username",">
					<br>
					<input type=","password"," name=","password"," placeholder=","password",">
					<br>
					<input type=","checkbox"," name=","remember_me"," id=","remember_me",">
					<label for=","remember_me",">remember me</label>
					<br>
					<input type=","submit"," name=","register"," value=","register",">
				</form>
				<hr>
				<form action=","./login.php"," method=","post",">
					<span>login</span>
					<br>
					<input type=","text"," name=","username"," placeholder=","username",">
					<br>
					<input type=","password"," name=","password"," placeholder=","password",">
					<br>
					<input type=","checkbox"," name=","remember_me2"," id=","remember_me2",">
					<label for=","remember_me2",">remember me</label>
					<br>
					<input type=","submit"," name=","login"," value=","login",">
				</form>";
		}
	?>
</body>
</html>
