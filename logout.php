<?php
	session_start();
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		session_unset();
		session_destroy();
		header('Location: http://localhost/WebP%20Project/indexl.php');
	}
?>