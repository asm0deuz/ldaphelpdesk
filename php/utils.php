<?php
function hashSSHA($password, $saltLength) {
	$salt = rand();
	$salt = substr($salt, 0, $saltLength);
	echo "test : " . $salt;
	$hash = base64_encode(sha1($password . $salt, true) . $salt);
	echo "hash : " . $hash;
	return "{SSHA}" . $hash;
}
?>