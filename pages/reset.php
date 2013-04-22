<?php
session_start();
require '../php/ldap.php';
require '../php/auth.php';

if (!isAuthenticated()) {
	header("Location:../index.php");
}

$users = getUsers($_SESSION['environment']);
/*echo "<pre>";
print_r($users);
echo "</pre>";*/
if (isset($_POST['users']) && !empty($_POST['password'])) {
	$user = $_POST['users'];
	$pwd = $_POST['password'];
	if (changePassword($user, $pwd, $_SESSION['environment'])) {
		echo "done";
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>Action page</title>
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
	<?php include 'nav.php'?>
	<div id="resetpwd">
		<form action="reset.php" method="post">
			<fieldset>
				<ul>
					<li>
						<?php
						echo '<label for="' . "users" . '">' . "users" . '</label>';
						echo '<select name="' . "users" . '">';
						foreach ($users as $key => $value) {
							echo '<option value="' . $value . '">' . $key . '</option>';
						}
						echo '</select>';
						?>
					</li>
					<li>
						<label for="password">Password</label>
						<input type="password" name="password" id="password"/>
					</li>
					<li>
						<input type="submit" value="change password"/>
					</li>
				</ul>
			</fieldset>
		</form>
	</div>
	<div>
		<a href="logout.php">Logout</a>
	</div>
</body>
</html>