<?php

	function isAuthenticated() {
		if(isset($_SESSION['login']) && isset($_SESSION['password'])) {
			return true;
		}
		else {
			return false;
		}
	}

?>