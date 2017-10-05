<?php
	session_start();
	if(!isset($_SESSION['db'])) {
		header('Location: http://localhost/WebP%20Project/indexl.php');
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Find Friends</title>
	<link rel="stylesheet" type="text/css" href="peopleStyle.css">
	<link rel="stylesheet" type="text/css" href="common.css">
</head>
<body>
	<div id="left">
		<div id="leftpanel">
			<img src="user-icon.png" width="100px">
			<?php echo "<h4 class='user-name'>".ucfirst($_SESSION['user']).' '.ucfirst($_SESSION['lname'])."</h4>"?>
			<ul id="leftpanel">
				<li class="leftpanel"><a href="user.php" class="leftpanel">Home</a>
				<li class="leftpanel"><a href="" class="leftpanel">Find Fiends</a>
				<li class="leftpanel"><a href="friends.php" class="leftpanel">My Friends</a></li>
				<li class="leftpanel"><a href="posts.php" class="leftpanel">My Posts</a></li>
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
				$sql = "SELECT email from friends";
				$emails = array();
				$l = 0;
				$result = mysqli_query($conn, $sql);
				if($result) {
					while($row = mysqli_fetch_assoc($result)) {
						$emails[$l] = $row['email'];
						$l++;
					}
				}


				$db = "WebP";
				
				$conn = mysqli_connect($server, $username, $password, $db);
				if(!$conn) {
					die("Connection Failed".mysqli_connect_error());
				}

				$sql = "SELECT first_name, last_name, email, db_name from users";
				$result = mysqli_query($conn, $sql);
				if(mysqli_num_rows($result)>0) {
					$row = mysqli_fetch_assoc($result);
					$count = 0;
					$isFriend = false;
					while($row) {
						$count = 0;
						echo  '
							<div class="userCardContainer">
						';
						while($count<6) {
							$fname = $row['first_name'];
							$lname = $row['last_name'];
							$email = $row['email'];
							$db = $row['db_name'];
							for($i=0; $i<$l; $i++) {
								if($emails[$i] == $email) {
									$isFriend = true;
								}
							}
					
							if($db != $_SESSION['db'] && !$isFriend) {
								$count++;
								echo '
								
									<div class="userCard">
										<img src="user-icon.png" width="100px">
										<h6 style="margin-bottom:7px;">'.ucfirst($fname).' '.ucfirst($lname).'</h6>
										<form method="post" action="'.htmlspecialchars($_SERVER['PHP_SELF']).'">
											<input type="hidden" value="'.$db.'" name="db">
											<input type="hidden" value="'.$fname.'" name="fname">
											<input type="hidden" value="'.$lname.'" name="lname">
											<input type="hidden" value="'.$email.'" name="email">
											<input type="submit" value="Connect">
										</form> 
										
									</div>';	
							}

							$isFriend = false;
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
				die("Connection Failed : myDB".mysqli_connect_error());
			}  
			$sql = "INSERT INTO friends VALUES('$friendFname','$friendLname','$friendEmail')";
			if(!mysqli_query($conn, $sql)) {
				echo "<script>window.alert('Already Connected')</script>";
				echo "<script>console.log('Error Inserting to myDb .".mysqli_error($conn).");</script>";
				die();
			}

			$conn = mysqli_connect($server, $username, $password, $friendDb);
			if(!$conn) {
				die("Connection Failed : friendDb".mysqli_connect_error());
			}
			$sql = "INSERT INTO friends VALUES('$myFname','$myLname','$myEmail')";
			if(!mysqli_query($conn, $sql)) {
				die("Error Inserting to friendDb".mysqli_error($conn));
			}

			mysqli_close($conn);
			echo "<script>window.alert('Connected Succesfully')</script>";
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