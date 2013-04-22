<?php
session_start();
require 'php/ldap.php';

$envlist = array();
$environments = parse_ini_file("config/servers.ini", true);
foreach ($environments as $env => $value) {
	$envlist[] = $env;
}

if (isset($_POST['login']) && isset($_POST['password']) && isset($_POST['Environment'])) {
	$login = $_POST['login'];
	$password = $_POST['password'];
	$environment = $environments[$_POST['Environment']];
	$_SESSION['login'] = $login;
	$_SESSION['password'] = $password;
	$_SESSION['environment'] = $environment;
	if (checkLogin($environment, $login, $password)) {
		header("Location:pages/reset.php");
	}
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>Login page</title>
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
	<form action="index.php" method="post">
		<fieldset>
			<ul>
				<li>
					<label for="login">Login</label>
					<input type="text" name="login" id="login"/>
				</li>
				<li>
					<label for="password">Password</label>
					<input type="password" name="password" id="password"/>
				</li>
				<li>
					<?php
					echo '<label for="' . "Environment" . '">' . "Environment" . '</label>';
					echo '<select name="' . "Environment" . '">';
					foreach ($envlist as $env => $value) {
						echo '<option value="' . $value . '">' . $value . '</option>';
					}
					?>
				</select>
			</li>
			<li>
				<input type="submit"/>
			</li>
		</ul>
	</fieldset>
</form>
</body>
</html>
