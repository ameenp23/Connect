<?php
	session_start();
	if(!isset($_SESSION['db'])) {
		header('Location: http://localhost/WebP%20Project/indexl.php');
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Friends</title>
	<link rel="stylesheet" type="text/css" href="friendsStyle.css">
	<link rel="stylesheet" type="text/css" href="common.css">
</head>
<body>
	<div id="left">
		<div id="leftpanel">
			<img src="user-icon.png" width="100px">
			<?php echo "<h4 class='user-name'>".ucfirst($_SESSION['user']).' '.ucfirst($_SESSION['lname'])."</h4>"?>
			<ul id="leftpanel">
				<li class="leftpanel"><a href="user.php" class="leftpanel">Home</a>
				<li class="leftpanel"><a href="people.php" class="leftpanel">Find Fiends</a></li>
				<li class="leftpanel"><a href="" class="leftpanel">My Friends</a>
				<li class="leftpanel"><a href="posts.php" class="leftpanel">My Posts</a>
				<li class="leftpanel"><a href="#" class="leftpanel" id="delAcc">Delete Account</a></li>
			</ul>
			<form method="POST" action="logout.php">
				<input type="submit" name="logout" value="Logout">
			</form>
		</div>
		<div id="logo">
			<img src="logo.png" width="200">
		</div>
	</div>
	<div id="right">
		<div id="main">
			<?php
				$username = "ameen";
				$password = "ameen";
				$server = "localhost";
				$db = $_SESSION['db'];
				
				$conn = mysqli_connect($server, $username, $password, $db);
				if(!$conn) {
					die("Connection Failed".mysqli_connect_error());
				}

				$sql = "SELECT first_name, last_name, email from friends";
				$result = mysqli_query($conn, $sql);
				$count = 0;
				if(mysqli_num_rows($result)>0) {
					$row = mysqli_fetch_assoc($result);
					while($row) {
						$count = 0;
						echo '
							<div class="userCardContainer">
						';
						while($count<6) {
							$fname = $row['first_name'];
							$lname = $row['last_name'];
							$email = $row['email'];
							$db = preg_replace('/[^A-Za-z0-9\-]/', '', $email);
							echo '
								<div class="userCard">
									<img src="user-icon.png" width="100px">
									<h6 style="margin-bottom:7px;">'.ucfirst($fname).' '.ucfirst($lname).'</h6>
									<form method="post" action="'.htmlspecialchars($_SERVER['PHP_SELF']).'">
										<input type="hidden" value="'.$db.'" name="db">
										<input type="hidden" value="'.$fname.'" name="fname">
										<input type="hidden" value="'.$lname.'" name="lname">
										<input type="hidden" value="'.$email.'" name="email">
										<input type="submit" value="Disconnect">
									</form>
								</div>
							';
							$count++;
							$row = mysqli_fetch_assoc($result);
							if(!$row) {
								break;
							}
						}
						echo '
						</div>
						';
					}
				}
				if($count == 0) {
					echo "You are not connected with anyone yet. Find your friends <a href='people.php'>here</a>";
				}
			?>
		</div>
	</div>	
	<?php
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$username = "ameen";
			$password = "ameen";
			$server = "localhost";

			$myDb = $_SESSION['db'];
			$myFname =  $_SESSION['user'];
			$myLname = $_SESSION['lname'];
			$myEmail = $_SESSION['email'];

			$friendDb = $_POST['db'];
			$friendFname = $_POST['fname'];
			$friendLname = $_POST['lname'];
			$friendEmail = $_POST['email'];

			$conn = mysqli_connect($server, $username, $password, $myDb);
			if(!$conn) {
				echo "<script>console.log('Connection Failed : myDB ".mysqli_connect_error()."');</script>";
			}  
			$sql = "DELETE FROM friends WHERE email='$friendEmail'";
			if(!mysqli_query($conn, $sql)) {
				echo "<script>console.log('Error Inserting to myDb ".mysqli_error($conn)."');</script>";
			}

			$conn = mysqli_connect($server, $username, $password, $friendDb);
			if(!$conn) {
				echo "<script>console.log('Connection Failed : friendDb ".mysqli_connect_error()."');</script>";
			}
			$sql = "DELETE FROM friends WHERE email='$myEmail'";
			if(!mysqli_query($conn, $sql)) {
				echo "<script>console.log('Error Inserting to friendDb ".mysqli_error($conn)."');</script>";
			}

			mysqli_close($conn);
			echo "<script>window.alert('Disconnected Succesfully')</script>";
		}
	?>

	<script type="text/javascript">
		document.getElementById('delAcc').onclick = showAlert;
		function showAlert() {
			if(confirm("Are You Sure?") == true) {
				window.location.href = "http://localhost/WebP%20Project/delete.php";
			}
		}
	</script>
</body>
</html>