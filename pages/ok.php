<?php
	session_start();

	echo "Connected";
	echo "<pre>";
	print_r($_SESSION['user']);
	print_r($_SESSION['password']);
	print_r($_SESSION['environment']);
	echo "</pre>";
?>