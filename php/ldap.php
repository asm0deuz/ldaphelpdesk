<?php
require 'utils.php';

function checkLogin($environment, $login, $password) {

	$server = $environment['server'];
	$basedn = $environment['basedn'];
	$uid = "uid=" . $login;

	$ldapconn = ldap_connect($server) or die("Could not connect to LDAP server.");
	//echo "connect result is " . $ldapconn . "<br />";

	if ($ldapconn) {
		// binding anonymously
		ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
		$ldapbind = ldap_bind($ldapconn);

		if ($ldapbind) {
			$sr = ldap_search($ldapconn, $basedn, $uid);
			if ($sr) {
				$result = ldap_get_entries($ldapconn, $sr);
				echo "Number of entries returned is " . ldap_count_entries($ldapconn, $sr) . "<br />";
				echo "<pre>";
				print_r($result);
				echo "</pre>";
				if ($result[0]) {
					/*echo $password;*/
					if (ldap_bind($ldapconn, $result[0]['dn'], $password)) {
						$_SESSION["user"] = $result[0];
						return true;
					}
				}
			}

		}
	}
}

function getUsers($environment) {

	$server = $environment['server'];
	$basedn = $environment['basedn'];
	$uid = $environment['binddn'];

	$ldapconn = ldap_connect($server) or die("Could not connect to LDAP server.");

	if ($ldapconn) {
		// binding anonymously
		ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
		$ldapbind = ldap_bind($ldapconn);

		if ($ldapbind) {
			$sr = ldap_search($ldapconn, $basedn, "uid=*", array("uid"));
			if ($sr) {
				$result = ldap_get_entries($ldapconn, $sr);
				echo "Number of entries returned is " . ldap_count_entries($ldapconn, $sr) . "<br />";
				/*echo "<pre>";
				print_r($result);
				echo "</pre>";*/
				$userslist = array();
				if ($result) {
					foreach ($result as $key => $value) {
						//$userslist[]=array ($value['uid'][0], $value['dn']);
						$userslist[$value['uid'][0]] = $value['dn'];
					}
					ksort($userslist);
					return $userslist;
				}
			}
		}

	}
}

function getLockedUsers($environment) {

	$server = $environment['server'];
	$basedn = $environment['basedn'];
	$uid = $environment['binddn'];

	$ldapconn = ldap_connect($server) or die("Could not connect to LDAP server.");

	if ($ldapconn) {
		// binding anonymously
		ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
		$ldapbind = ldap_bind($ldapconn);

		if ($ldapbind) {
			$sr = ldap_search($ldapconn, $basedn, "(&(pwdAccountLockedTime=*)(!(pwdReset=*)))", array("uid"));
			if ($sr) {
				$result = ldap_get_entries($ldapconn, $sr);
				echo "Number of entries returned is " . ldap_count_entries($ldapconn, $sr) . "<br />";
				/*echo "<pre>";
				print_r($result);
				echo "</pre>";*/
				$userslist = array();
				if ($result) {
					foreach ($result as $key => $value) {
						//$userslist[]=array ($value['uid'][0], $value['dn']);
						$userslist[$value['uid'][0]] = $value['dn'];
					}
					ksort($userslist);
					return $userslist;
				}
			}
		}

	}
}


function changePassword($user, $password, $environment) {

	$server = $environment['server'];
	$basedn = $environment['basedn'];
	$uid = $environment['binddn'];
	$bindpwd = $environment['bindpwd'];

	$ldapconn = ldap_connect($server) or die("Could not connect to LDAP server.");

	if ($ldapconn) {
		ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
		$ldapbind = ldap_bind($ldapconn, $uid, $bindpwd);

		if ($ldapbind) {
			$sr = ldap_search($ldapconn, $basedn, $user);
			if ($sr) {
				ldap_mod_replace($ldapconn, $user, array("userPassword" => hashSSHA($password, 8)));
			}
		}
	}
}

function unlock($user, $environment) {

	$server = $environment['server'];
	$basedn = $environment['basedn'];
	$uid = $environment['binddn'];
	$bindpwd = $environment['bindpwd'];

	$ldapconn = ldap_connect($server) or die("Could not connect to LDAP server.");

	if ($ldapconn) {
		ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
		$ldapbind = ldap_bind($ldapconn, $uid, $bindpwd);

		if ($ldapbind) {
			$sr = ldap_search($ldapconn, $basedn, $user);
			if ($sr) {
				ldap_mod_replace($ldapconn, $user, array("pwdReset" => "TRUE"));
			}
		}
	}
}

?>