<?php
	session_start();
	if(!isset($_SESSION['db'])) {
		header('Location: http://localhost/WebP%20Project/indexl.php');
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Profile</title>
	<link rel="stylesheet" type="text/css" href="user-style.css">
	<link rel="stylesheet" type="text/css" href="common.css">
</head>
<body>
	<div id="mainContainer">
	<div id="left">
		<div id="leftpanel">
			<img src="user-icon.png" width="100px">
			<?php echo "<h4 class='user-name'>".ucfirst($_SESSION['user']).' '.ucfirst($_SESSION['lname'])."</h4>"?>
			<ul id="leftpanel">
				<li class="leftpanel"><a href="" class="leftpanel">Home</a></li>
				<li class="leftpanel"><a href="people.php" class="leftpanel">Find Fiends</a></li>
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
			<div class="post" id="newpost">
				<form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>">
				<table width="100%">
					<tr>
						<td style="display: flex;flex-flow: column;align-items: stretch;">
							<textarea id="newpost" name="data" placeholder="Type your new post"></textarea>
						</td>	
					</tr>
					<tr style="text-align: right" >
						<td>
							<input type="submit" name="post" value="Post" id="login">
						</td>	
					</tr>
				</table>
				</form>
				
			</div>

  			<?php  
  				$username = "ameen";
				$password = "ameen";
				$server = "localhost";
				$db = $_SESSION['db'];

				$conn = mysqli_connect($server, $username, $password, $db);

				if(!$conn) {
					die("Connection Failed".mysqli_connect_error());
				}

				$sql = "SELECT first_name, last_name, email FROM friends";
				$result = mysqli_query($conn, $sql);
				$flag = 0;

				if(mysqli_num_rows($result)>0) {
					$dataset = array();
					$len=0;
					while($row = mysqli_fetch_assoc($result)) {
						$email = $row['email'];
						$fname = $row['first_name'];
						$lname = $row['last_name'];
						$db = preg_replace('/[^A-Za-z0-9\-]/', '', $email);
						
						$conn2 = mysqli_connect($server, $username, $password, $db);
						$sql = "SELECT data,date FROM posts";
						$result2 = mysqli_query($conn2, $sql);
						if($result2) {
							if(mysqli_num_rows($result2)>0) {
								$flag++;
								while($row2 = mysqli_fetch_assoc($result2)) {
									$temp = array($fname, $lname, $row2['data'], $row2['date']);
									$dataset[$len] = $temp;
									$len++;
									
								}
							}
						}
						
					}
					
					for($i=0;$i<$len;$i++) {
						for($j=0;$j<$len-$i-1;$j++) {
							$temp = $dataset[$j];
							$temp2 = $dataset[$j+1];
							if($temp[3]<$temp2[3]) {
								$dataset[$j] = $temp2;
								$dataset[$j+1] = $temp;
							}
						}
					}
					
					for($i=0;$i<$len;$i++) {
						$temp = $dataset[$i];
						echo '
							<table class="post">
								<tr>
									<td width="10">
										<img src="post-icon.png" width="70">
									</td>
									<td class="postheader">
										<h4 class="user-name" style="margin-bottom:5px;">'.ucfirst($temp[0]).' '.ucfirst($temp[1]).'</h4>
										<h6>'.$temp[3].'</h6>
									</td>
								</tr>
								<tr>
									<td colspan="2"  style="padding: 10px;">
										<p class="post">'.$temp[2].'</p>
									</td>
								</tr>
							</table>
						';
					}
				}

				if($flag == 0) {
					echo "No Posts to Show. Post an Update or Find Your Friends <a href='people.php'>here</a>";
				}
				?>
		<?php
			/*<table class="post">
				<tr>
					<td width="10">
						<img src="post-icon.png" width="50">
					</td>
					<td style="text-align:left;">
						<h4>user-name</h4>
						<h6>19-09-1997 12:02:54</h6>
					</td>
				</tr>
				<tr>
					<td colspan="2"  style="padding: 10px;">
						<p>Hello welcome to facebook.This is my first post :)</p>
					</td>
				</tr>
			</table>*/

		?>

		</div>

	</div>
	</div>
	<script type="text/javascript" src="userScript.js"></script>
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

			$data = $_POST['data'];
			$date = date("Y-m-d h:i:s");
			$sql_addpost = "INSERT INTO posts (data, date) VALUES('$data','$date')";

			if(!mysqli_query($conn, $sql_addpost)) {
				echo "<script>console.log('Error adding post ".mysqli_error($conn)."')</script>";
				die();
			}
			echo "<script>window.alert('Posted Succesfully')</script>";
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