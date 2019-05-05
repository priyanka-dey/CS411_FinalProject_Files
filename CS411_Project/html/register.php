<?php
    session_start();
    $dbhost = '127.0.0.1';
    $dbuser = 'pdey3';
    $dbpass = 'cs411';
    $dbport = 3036;
    $db = 'wine_snob';
    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $db);

      if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$name = mysql_real_escape_string($_POST['name']);
	$username = mysql_real_escape_string ($_POST['username']);
	$pswd = mysql_real_escape_string($_POST['password']);
	$age = $_POST['age'];

	$safety_chk = "SELECT count(*) as s_chk FROM USERS WHERE user_id='$username'";
        $result = mysqli_query($conn, $safety_chk);
	$count = mysqli_fetch_assoc($result);

	if ($count['s_chk'] > 0) {
	    echo "Sorry but this username is already taken.";
	} else {
	    // create the user
	   $query = "INSERT INTO USERS(user_id, password, name, age) VALUES('$username', '$pswd', '$name', '$age')";
           mysqli_query($conn, $query);
	   echo "Success!";
	   $_SESSION['logged_in'] = true;
	   $_SESSION['user_name'] = $username;
       header("Location: http://sp19-cs411-48.cs.illinois.edu/index.php");
	}
      }
?>

<!DOCTYPE html>
<html>
<head>
	<title>Register, login, and logout user php mysql</title>
	<form method = "post" action="register.php">
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
	<link rel="stylesheet" href="assets/css/main.css" />
</head>
 
	<body>

		<!-- Main -->
		<section id="main" class="wrapper style4">
			<div class="container">
				<div class="row aln-center">
					<div class="col-4 col-12-medium">
						<div id="content"> 
							<section class="box">
								<header>
									<center><h2>Wine Snob Registration Page</h2></center>
								</header>
								<form action="login" method="POST">
									<div class="row gtr-50">

										<!-- LOGIN  -->
										<div class="col-12">
											<input type="text" name="username" placeholder="User Name"/>
										</div>
										<div class="col-12">
											<input type="password" name="password" placeholder="Password"/>
										</div>
										<div class="col-12">
											<input type="password" name="password2" placeholder="Password Again"/>
										</div>
										<div class="col-12">
											<input type="number" name="age" placeholder="Age > 21"/>
										</div>
										<div class="col-12">
											<input type="text" name="name" placeholder="Name"/>
										</div>
						

										<!-- Submit Button -->
										<div class="col-12">
											<center><button type="submit" class="button style1" name="register_btn">Register</button></center>
										</div>
									</div>
								</form>
							</section>

						</div>

					</div>
				</div>
			</div>
		</section>




</body>
</html>
