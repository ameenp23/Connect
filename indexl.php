<?php
	session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Welcome</title>
	<link rel="stylesheet" type="text/css" href="sstyles.css">
</head>
<body>
	<div id="header">
		<div id="logo">
			<img src="logo.png" width="200">
		</div>
		<div id="login">
			<form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>">
				<input type="email" placeholder="Email" class="login" name="Email" onfocus="vanish(this)" onblur="show(this)">
				<input type="password" placeholder="Password" class="login" name="Password" onfocus="vanish(this)" onblur="show(this)">
				<input type="submit" value="Login" class="login">
				<input type="text" style="display: none;" name="source" value="topbar_login" >
			</form>
		</div>
	</div>
	<div id="reg">
		<form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>">
			<input type="text" placeholder="First Name" name="First Name" onfocus="vanish(this)" onblur="show(this)" required=""><br/>
			<input type="text" placeholder="Last Name" name="Last Name" onfocus="vanish(this)" onblur="show(this)"><br/>
			<input type="email" placeholder="Email" name="Email" onfocus="vanish(this)" onblur="show(this)" required=""><br/>
			<input type="password" placeholder="Password" name="Password" onfocus="vanish(this)" onblur="show(this)" required=""><br/>
			<input type="password" placeholder="Retype Password" name="RePassword" onfocus="vanish(this)" onblur="show(this)" required=""><br/>
			<input type="submit" value="Create Account">
			<input type="text" style="display: none;" name="source" value="create_account" >
		</form>
	</div>
	<div id="footer">
		<p>&copy;&nbsp;2017 Ameen P, Safvan Ahammed, Haziq</p>
	</div>

	<?php
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$username = "ameen";
			$password = "ameen";
			$server = "localhost";
			$db = "WebP";
			if($_POST['source'] == 'create_account') {
				$conn = mysqli_connect($server, $username, $password, $db);

				if(!$conn) {
					echo "<script>console.log('Connection Failed ".mysqli_connect_error()."')</script>";
					die();
				} 

				$fname = $_POST['First_Name'];
				$lname = $_POST['Last_Name'];
				$email = $_POST['Email'];
				$pass = $_POST['Password'];
				$RePass = $_POST['RePassword'];

				if($pass != $RePass) {
					echo "<script>window.alert('Passwords do not match')</script>";
					die();
				}

				$dbname = preg_replace('/[^A-Za-z0-9\-]/', '', $email);

				$sql = "INSERT INTO users VALUES('$fname', '$lname', '$email', '$pass', '$dbname')";
				if(mysqli_query($conn, $sql)) {
					echo "<script>console.log('Account Created Succesfully')</script>";
					$conn2 = mysqli_connect($server, $username, $password);
					if(!$conn2) {
						echo "<script>console.log('Connection2 Failed ".mysqli_connect_error()."')</script>";
						die();
					}

					$sql2 = "CREATE DATABASE $dbname";
					if(mysqli_query($conn2, $sql2)) {
						echo "<script>console.log('Database created Succesfully : ".$dbname."')</script>";
						if(mysqli_select_db($conn2, $dbname)) {
							echo "<script>console.log('Database selected successfully')</script>";
						} else {
							echo "<script>console.log('Error selecting Database ".mysqli_error($conn2)."')</script>";
						}

						$sql_friends = "CREATE TABLE friends(
							first_name VARCHAR(30),
							last_Name VARCHAR(30),
							email VARCHAR(30) PRIMARY KEY)";
						$sql_posts = "CREATE TABLE posts(
							SerialNo INT(10) AUTO_INCREMENT PRIMARY KEY,
							data VARCHAR(100),
							date DATETIME)";
						if(!mysqli_query($conn2, $sql_friends)) {
							echo "<script>console.log('Error Creating Table friends ".mysqli_error($conn2)."')</script>";
						} else {
							echo "<script>console.log('Table friends created successfully')</script>";
						}

						if(!mysqli_query($conn2, $sql_posts)) {
							echo "<script>console.log('Error Creating Table posts ".mysqli_error($conn2)."')</script>";
						} else {
							echo "<script>console.log('Table posts created successfully')</script>";
						}
					} else {
						echo "<script>console.log('Error creating DATABASE ".mysqli_error($conn2)."')</script>";
					}
					
				}
				else {
					echo "<script>window.alert('A User with this Email Address already Exists')</script>";
					die();
				}
				echo "<script>window.alert('Account Created Succesfully')</script>";
				mysqli_close($conn);
			}
			elseif ($_POST['source'] == 'topbar_login') {
				
				$conn = mysqli_connect($server, $username, $password, $db);

				if(!$conn) {
					die("Connection Failed".mysqli_connect_error());
				} 

				$email = $_POST['Email'];
				$pass = $_POST['Password'];

				$sql = "SELECT first_name,last_name,db_name FROM users WHERE email='$email' and password='$pass'";
				$result = mysqli_query($conn, $sql);
				if(mysqli_num_rows($result)>0) {
					$row = mysqli_fetch_assoc($result);
					$fname = $row['first_name'];
					$lname = $row['last_name'];
					$db = $row['db_name'];
					$_SESSION['user'] = $fname;
					$_SESSION['lname']  = $lname;
					$_SESSION['email'] = $email;
					$_SESSION['db'] = $db;
					header('Location: http://localhost/WebP%20Project/user.php');
				}
				else {
					echo "<script>window.alert('Incorrect Email or Password')</script>";
					die();
				}
			}
		}

	?>

	<script type="text/javascript" src="scripts.js"></script>
</body>
</html>