<?php
	session_start();
	if(!isset($_SESSION['db'])) {
		header('Location: http://localhost/WebP%20Project/indexl.php');
	}
	$email = $_SESSION['email'];

	$username = "ameen";
	$password = "ameen";
	$server = "localhost";
	$db = "WebP";

	$conn = mysqli_connect($server, $username, $password, $db);
	if(!$conn) {
		echo "<script>console.log('Connection to WebP database Failed .".mysqli_connect_error()."')</script>";
		die();
	}

	$sql = "DELETE FROM users WHERE email='$email'";
	if(!mysqli_query($conn, $sql)) {
		echo "<script>console.log('Error deleting from users')</script>";
		die();
	}

//----------------now delete from 'friends' table of all friends

	$db = $_SESSION['db'];

	$conn = mysqli_connect($server, $username, $password, $db);
	if(!$conn) {
		echo "<script>console.log('Connection to user database Failed .".mysqli_connect_error()."')</script>";
		die();
	}

	$sql = "SELECT email FROM friends";
	$result = mysqli_query($conn, $sql);

	if(mysqli_num_rows($result)>0) {
		while($row = mysqli_fetch_assoc($result)) {
			$email2 = $row['email'];
			$db2 = preg_replace('/[^A-Za-z0-9\-]/', '', $email2);
			
			$conn2 = mysqli_connect($server, $username, $password, $db2);
			$sql = "DELETE FROM friends WHERE email='$email'";
			$result2 = mysqli_query($conn2, $sql);
			if(!$result2) {
				echo "<script>console.log('Unable to delete from friends .".mysqli_error()."')</script>";
				die();
			}
			
		}

	}

//------------------now drop database
	$db = $_SESSION['db'];
	$sql = "DROP DATABASE $db";
	if(!mysqli_query($conn, $sql)) {
		echo "<script>console.log('Error deleting databse')</script>";
		die();
	}

	echo "<script>console.log('Account deleted Succesfully')</script>";
	header('Location: http://localhost/WebP%20Project/indexl.php');

?>