
<?php require_once '../configs/config.php' ?>

<?php
	if (!isset($_COOKIE[$name_cookie])) {
		if (isset($_POST['register'])) {
			$username = $_POST['username'];
			$password = encrypt($_POST['password'], $key);
			if (isset($_POST['remember_me'])) {
				$remember_me = true;
			} else {
				$remember_me = false;
			}
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
						$row = $query->fetch_assoc();
						$id  = encrypt($row['id'], $key);
						if ($remember_me) {
							setcookie($name_cookie, $id, time()+$time_cookie, '/');
							$_COOKIE[$name_cookie] = $id;
						} else {
							setcookie($name_cookie, $id, 0, '/');
							$_COOKIE[$name_cookie] = $id;
						}
					}
				}
			}
		} elseif (isset($_POST['login'])) {
			$username = $_POST['username'];
			$password = encrypt($_POST['password'], $key);
			if (isset($_POST['remember_me'])) {
				$remember_me = true;
			} else {
				$remember_me = false;
			}
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
						$id  = encrypt($row['id'], $key);
						if ($remember_me) {
							setcookie($name_cookie, $id, time()+$time_cookie, '/');
							$_COOKIE[$name_cookie] = $id;
						} else {
							setcookie($name_cookie, $id, 0, '/');
							$_COOKIE[$name_cookie] = $id;
						}
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
			$id = intval(decrypt($_COOKIE[$name_cookie], $key));
			echo $id;
			echo "<h1>wellcome username.</h1>
				<form action=","./login.php"," method=","post",">
					<input type=","submit"," name=","exit"," value=","exit",">
				</form>";
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
					<input type=","checkbox"," name=","remember_me"," id=","remember_me2",">
					<label for=","remember_me2",">remember me</label>
					<br>
					<input type=","submit"," name=","login"," value=","login",">
				</form>";
		}
	?>
</body>
</html>
