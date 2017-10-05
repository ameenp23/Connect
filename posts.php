<?php
	session_start();
	if(!isset($_SESSION['db'])) {
		header('Location: http://localhost/WebP%20Project/indexl.php');
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>My Posts</title>
	<link rel="stylesheet" type="text/css" href="postsStyle.css">
	<link rel="stylesheet" type="text/css" href="common.css">
</head>
<body>
	<div id="mainContainer">
	<div id="left">
		<div id="leftpanel">
			<img src="user-icon.png" width="100px">
			<?php echo "<h4 class='user-name'>".ucfirst($_SESSION['user']).' '.ucfirst($_SESSION['lname'])."</h4>"?>
			<ul id="leftpanel">
				<li class="leftpanel"><a href="user.php" class="leftpanel">Home</a></li>
				<li class="leftpanel"><a href="people.php" class="leftpanel">Find Fiends</a></li>
				<li class="leftpanel"><a href="friends.php" class="leftpanel">My Friends</a></li>
				<li class="leftpanel"><a href="" class="leftpanel">My Posts</a></li>
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

				$sql = "SELECT data,date,SerialNo FROM posts";
				$result = mysqli_query($conn, $sql);
				$flag = 0;

				if($result) {
					if(mysqli_num_rows($result)>0) {
						$fname = $_SESSION['user'];
						$lname = $_SESSION['lname'];
						while($row = mysqli_fetch_assoc($result)) {
							$flag++;
							$SerialNo = $row['SerialNo'];
							echo '
								<table class="post">
									<tr>
										<td width="10">
											<img src="post-icon.png" width="70">
										</td>
										<td class="postheader">
											<h4 class="user-name" style="margin-bottom:5px;">'.ucfirst($fname).' '.ucfirst($lname).'</h4>
											<h6>'.$row["date"].'</h6>
										</td>
										<td style="float:right;background-color:#3aa0b2;">
											<form method="POST" action="'.htmlspecialchars($_SERVER['PHP_SELF']).'">
												<input type="hidden" name="SerialNo" value="'.$SerialNo.'">
												<input type="submit" value="Delete">
											</form>
										</td>
									</tr>
									<tr>
										<td colspan="2"  style="padding: 10px;">
											<p class="post">'.$row["data"].'</p>
										</td>
									</tr>
								</table>
							';
						}
					}
				}
				mysqli_close($conn);
			if($flag == 0) {
				echo "You haven't posted anything yet. Click <a href='user.php'>here</a> to post an update";
			}
			?>
		</div>
	</div>
	</div>
	<?php
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$username = "ameen";
			$password = "ameen";
			$server = "localhost";
			$db = $_SESSION['db'];

			$conn = mysqli_connect($server, $username, $password, $db);

			if(!$conn) {
				die("Connection Failed".mysqli_connect_error());
			}

			$SerialNo = $_POST['SerialNo'];
			$sql_rempost = "DELETE FROM posts WHERE SerialNo='$SerialNo'";

			if(!mysqli_query($conn, $sql_rempost)) {
				echo "<script>console.log('Error adding post')</script>";
				die();
			}
			header('Location: http://localhost/WebP%20Project/posts.php');
			echo "<script>console.log('Post Deleted Succesfully')</script>";

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