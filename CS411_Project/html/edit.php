<!-- This document includes is similar to the "My Profile" page, includes functionality to modify reviews -->

<?php
	include_once('dbh.php');
	session_start();

	if($_GET['id']){
		$reviewID = $_GET['id'];
	} else{
		die('The page you are searching for does not exist');
	}

	if(isset($_POST['new-wine-name']) || isset($_POST['new-review-description']) || isset($_POST['new-score']) || isset($_POST['new-price'])){
		$newWineName = $_POST['new-wine-name'];
		$newReview = $_POST['new-review-description'];
		$newScore = $_POST['new-score'];
		$newPrice = $_POST['new-price'];
		echo "$newWineName $newReview $newScore $newPrice";
		$updateSql = "UPDATE REVIEWS SET description='$newReview', score=$newScore WHERE review_id=$reviewID";
		$updateResult = mysqli_query($conn,$updateSql) or die("Could not update".mysql_error());
		// Check for successful modification, then redirect back to profile page
		if(mysqli_query($conn,$updateSql)){
			mysqli_close($conn);
			header('Location: profile.php');
			exit;
		} else{
			echo "Error modifying record";
		}
	}

	// Get all information about this review
	$editSql = "SELECT * FROM REVIEWS WHERE review_id=".$reviewID;
	$editResult = mysqli_query($conn,$editSql);
	$editRow = mysqli_fetch_assoc($editResult);

	// Get all info about this user
	$sql = "SELECT user_id, name, age FROM USERS WHERE user_id='".$_SESSION['user_name']."'";
	$result = mysqli_query($conn,$sql);
	$queryResult = mysqli_num_rows($result);
	$row = mysqli_fetch_assoc($result);

	// Get all reviews that this user has written
	$reviewsSql = "SELECT REVIEWS.review_id, REVIEWS.description, REVIEWS.score, REVIEWS.user_id, WINES.title, WINES.price, USERS.name, USERS.age FROM REVIEWS, WINES, USERS WHERE USERS.user_id='".$_SESSION['user_name']."' AND REVIEWS.user_id=USERS.user_id AND REVIEWS.wine_id=WINES.wine_id";
	$reviewsResult = mysqli_query($conn,$reviewsSql);
	$reviewsQueryResult = mysqli_num_rows($reviewsResult);

?>	


<!DOCTYPE HTML>
<!--
	Escape Velocity by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->

<html>
	<head>
		<title>Wine Snob | My Profile</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/main.css" />
	</head>
	<body class="no-sidebar is-preload">
		<div id="page-wrapper">

			<!-- Header -->
				<section id="header" class="wrapper">

					<!-- Logo -->
						<div id="logo">
							<h1><a href="index.php">Wine Snob</a></h1>
							<p>Find your taste today!</p>
						</div>

					<!-- Nav -->
						<nav id="nav">
							<div class="row gtr-25" align="right">
								<?php
								if (isset($_SESSION['logged_in']) && ($_SESSION['user_name'] != "")) {
									?>
									<div class="col-10">
										<a href="#" class="button style1">My Profile</a>
									</div>
									<div class="col-1">
										<a href="logout.php" class="button style2" name="log-out">Log Out</a>
									</div> <?php
								} else {
									?>
									<div class="col-10">
										<a href="register.php" class="button style1" name="sign-up">Sign Up</a>
									</div>
									<div class="col-1">
										<a href="login.php" class="button style2" name="log-in">Log In</a>
									</div> <?php
								} ?>
							</div>
							<ul>
								<li><a href="index.php">Home</a></li>
								<li><a href="recommendation_engine.php">Recommended Wines</a></li>
								<li><a href="regions.php">Explore The Data</a></li>
							</ul>
						</nav>

				</section>

			<!-- Main -->
				<div id="main" class="wrapper style2">
					<div class="container">

						<!-- Content -->
							<div id="content">
								<article class="box post">
									<header class="style1">
										<h2><?php echo "Welcome, ".$row['name']."!"; ?></h2>
									</header>
									<div class='row gtr-25'>
										<div class='col-12' align='center'>
											<header class='style1'>
												<h1>Account Information</h1>
											</header>
										</div>
										<div class='off-3 col-3 col-12-small' align='center'>
											<b>Username:</b><br>
											<?php echo $row['user_id']; ?>
										</div>
										<div class='col-3 col-12-small' align='center'>
											<b>Age:</b><br>
											<?php echo $row['age']; ?>
										</div>
									</div>
								</article>
							</div>

					</div>
				</div>

			<!-- Highlights --> 
				<section id='highlights' class='wrapper style3'>
					<div class='title'>My Reviews</div>
					<div class='container'>
						<div class='row aln-center'>
							<?php if($reviewsQueryResult != 0){
								while($row = mysqli_fetch_assoc($reviewsResult)){
									if($row['review_id'] == $reviewID){
										echo "<div>
											<section class='box'>
												<form action='#' method='POST'>
													<div class='row gtr-25'>
														<div class='col-10 off-1'>
															<input type='text' name='new-wine-name' value='".$row['title']."' readonly/>
														</div>
														<div class='col-10 off-1'>
															<input type='text' name='new-review-description' value='".$row['description']."'/>
														</div>
														<div class='col-5 off-1'>
															<input type='number' step='1' min='0' max='100' name='new-score' value='".$row['score']."'/>
														</div>
														<div class='col-5'>
															<input type='number' step='1' min='0' max='1000' name='new-price' value='".$row['price']."' readonly/>
														</div>
														<div class='col-10 off-1' align='center'>
															<input type='submit' class='button style1' value=' Update '>
														</div>
														<div class='col-12'>
															<hr>
														</div>
													</div>
												</form>
											</section>
										</div>";				
									} else{
										echo "<div>
											<section class='box'>
												<header>
													<h2>".$row['title']."</h2>
												</header>
												<div class='row gtr-25'>
													<div class='col-12'>		
														<i>\"".$row['description']."\"</i>
													</div>
													<div class='col-12' align='right'>
														-".$row['name'].", ".$row['age']."
													</div>

													<div class='col-12 col-12-small' align='right'>";
														if($row['score'] != ''){
															echo "<b>Rating:</b> ".$row['score']."<br>";
														} else {
															echo "<b>Rating:</b> Unknown";
														}
														if($row['price'] != ''){
															echo "<b>Price:</b> $".$row['price'];
														} else{
															echo "<b>Price:</b> Unknown";
														}
													echo "</div>
													<div class='off-1 col-4' align='center'>
														<a href='delete.php?id=".$row['review_id']."' class='button style1'>Delete</a>
													</div>
													<div class='off-1 col-4' align='center'>
														<a href='edit.php?id=".$row['review_id']."' class='button style1'>Edit</a>
													</div>
													<div class='col-12'>
														<hr>
													</div>
												</div>
											</section>
										</div>";
									}
								}
							} else{
								echo "<div class='col-12' align='center'>
									You have not written any reviews yet.
								</div>
								<div class='col-12' align='center'>
									<a href='reviews.php' class='button style1'>Write a Review</a>
								</div>";
							}?>
									
						</div>
					</div>
				</section>

			<!-- Footer -->
				<section id="footer" class="wrapper">
					<div class="container">
						<header class="style1">
							<h2>Ipsum sapien elementum portitor?</h2>
							<p>
								Sed turpis tortor, tincidunt sed ornare in metus porttitor mollis nunc in aliquet.<br />
								Nam pharetra laoreet imperdiet volutpat etiam feugiat.
							</p>
						</header>
						<div class="row">
							<div class="col-6 col-12-medium">

								<!-- Contact Form -->
									<section>
										<form method="post" action="#">
											<div class="row gtr-50">
												<div class="col-6 col-12-small">
													<input type="text" name="name" id="contact-name" placeholder="Name" />
												</div>
												<div class="col-6 col-12-small">
													<input type="text" name="email" id="contact-email" placeholder="Email" />
												</div>
												<div class="col-12">
													<textarea name="message" id="contact-message" placeholder="Message" rows="4"></textarea>
												</div>
												<div class="col-12">
													<ul class="actions">
														<li><input type="submit" class="style1" value="Send" /></li>
														<li><input type="reset" class="style2" value="Reset" /></li>
													</ul>
												</div>
											</div>
										</form>
									</section>

							</div>
							<div class="col-6 col-12-medium">

								<!-- Contact -->
									<section class="feature-list small">
										<div class="row">
											<div class="col-6 col-12-small">
												<section>
													<h3 class="icon fa-home">Mailing Address</h3>
													<p>
														Untitled Corp<br />
														1234 Somewhere Rd<br />
														Nashville, TN 00000
													</p>
												</section>
											</div>
											<div class="col-6 col-12-small">
												<section>
													<h3 class="icon fa-comment">Social</h3>
													<p>
														<a href="#">@untitled-corp</a><br />
														<a href="#">linkedin.com/untitled</a><br />
														<a href="#">facebook.com/untitled</a>
													</p>
												</section>
											</div>
											<div class="col-6 col-12-small">
												<section>
													<h3 class="icon fa-envelope">Email</h3>
													<p>
														<a href="#">info@untitled.tld</a>
													</p>
												</section>
											</div>
											<div class="col-6 col-12-small">
												<section>
													<h3 class="icon fa-phone">Phone</h3>
													<p>
														(000) 555-0000
													</p>
												</section>
											</div>
										</div>
									</section>

							</div>
						</div>
						<div id="copyright">
							<ul>
								<li>&copy; Untitled.</li><li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
							</ul>
						</div>
					</div>
				</section>

		</div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/jquery.dropotron.min.js"></script>
			<script src="assets/js/browser.min.js"></script>
			<script src="assets/js/breakpoints.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>

	</body>
</html>
