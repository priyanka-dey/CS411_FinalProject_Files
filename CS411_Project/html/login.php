<?php
    session_start();
    $dbhost = '127.0.0.1';
    $dbuser = 'pdey3';
    $dbpass = 'cs411';
    $dbport = 3036;
    $db = 'wine_snob';
    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $db);

      if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$username = mysql_real_escape_string ($_POST['username']);
	$pswd = mysql_real_escape_string($_POST['password']);

	$verify_username = "SELECT count(*) as s_chk FROM USERS WHERE user_id='$username'";
        $result = mysqli_query($conn, $verify_username);
	$count = mysqli_fetch_assoc($result);
 	if ($count['s_chk'] == 0) {
            echo "Sorry but this username doesn't exist.";
        } else {
           $verify_pswd = "SELECT password as pswd2 FROM USERS WHERE user_id='$username'";
           $result = mysqli_query($conn, $verify_pswd);
           $actual_pswd = mysqli_fetch_assoc($result);

           if (strcmp($pswd, $actual_pswd['pswd2']) == 0) {
                echo "Logged in!";
                $_SESSION['logged_in'] = true;
                $_SESSION['user_name'] = $username;
                echo $_SESSION['logged_in'];
        header("Location: http://sp19-cs411-48.cs.illinois.edu/index.php");
        die();
           }
           else {
                echo "Looks like you entered the wrong password!";
        }}

        }


?>

<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<form method ="post" action ="login.php">
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
	<link rel="stylesheet" href="assets/css/main.css" />
</head>
 
	<body class="no-sidebar">

		<!-- Main -->
		<div id="main" class="wrapper style2">
			<div class="container">
				<div class="row aln-center">
					<div class="col-4 col-12-medium">
						<div id="content"> 
							
								<article class="box post">
								<header>
									<center><h2>Login</h2></center>
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
						

										<!-- Submit Button -->
										<div class="col-12">
											<center><button type="submit" class="button style1" name="login-btn">Login</button></center>
										</div>
									</div>
								</form>
							</article>

						</div>

					</div>
				</center>
				
			</div>
		</div>

</body>


</html>
