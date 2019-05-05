<!-- This document contains the formatted page to create a review! -->
<!-- Make changes into this file first until you can verify that things work and move this file to reviews.php -->


<?php

	session_start();

	include 'dbh.php';

	// Find all distinct grape varieties - use in search field drop down menus
	$varietySql = "SELECT distinct(variety) FROM WINES ORDER BY variety";
	$varietyResult = mysqli_query($conn,$varietySql);
	$varietyQueryResult = mysqli_num_rows($varietyResult);

	// Find all distinct countries - use in search field drop down menus
	$countrySql = "SELECT distinct(country) FROM WINES ORDER BY country";
	$countryResult = mysqli_query($conn,$countrySql);
	$countryQueryResult = mysqli_num_rows($countryResult);

	// Set inputs to review values
	$wineName = mysql_real_escape_string($_POST['wine-name']);
	$review = mysql_real_escape_string($_POST['review-description']);
	$score = $_POST['score'];
	$price = $_POST['price'];

	$user_name = $_SESSION['user_name'];

	// Get wine review ID number from URL
	$wineId = $_GET['id'];

	// Get info about this wine
	$reviewsSql = "SELECT * FROM WINES WHERE wine_id=".$wineId;
	$reviewsResult = mysqli_query($conn,$reviewsSql);
	$reviewsQueryResult = mysqli_num_rows($reviewsResult);

	// check if user is logged in and that a review has actually been filled out
	if(isset($_SESSION['logged_in']) && ($_SESSION['user_name'] != '')){

		$sql = "SELECT COUNT(*) as c FROM REVIEWS WHERE user_id='$user_name' AND wine_id=$wineId";
		$checkRevCount = mysqli_query($conn, $sql);

		while ($row = mysqli_fetch_assoc($checkRevCount)) {
			$rev_count = $row['c'];
		}

		// echo $rev_count;
		$rev_desc = "Enter a review!";
		if($_POST['submit-review']){
			if ($rev_count > 0) {
				$sql = "SELECT description, score, title FROM REVIEWS WHERE user_id='$user_name' AND wine_id=$wineId";
				echo $sql;
				$rev_sql = mysqli_query($conn, $sql);

				while($row = mysqli_fetch_assoc($rev_sql)) {
    					$rev_desc = $row['description'];
					$rev_score = $row['score'];
				} mysqli_free_result($rev_sql);

				$update_sql = "UPDATE REVIEWS SET description = '$review', score = '$score' WHERE wine_id='$wineId' AND user_id='$user_name'";
				if($conn->query($update_sql) == TRUE){
					// review has been submitted and updated in db, go back to description page
					header('Location: http://sp19-cs411-48.cs.illinois.edu/description.php?id='."$wineId");
				} else{
					echo "<br>Error: ".$sql."<br>".$conn->error;
				}
			} else {
				// create the new review
				if ($review != '' || $score !='')
					$sql = "INSERT INTO REVIEWS(description, score, user_id, wine_id) VALUES('".$review."',".$score.",'".$_SESSION['user_name']."',".$wineId.")";
				if($conn->query($sql) == TRUE){
					// Review has been submitted and updated in db, go back to description page
					header('Location: http://sp19-cs411-48.cs.illinois.edu/description.php?id='."$wineId");
				} else{
					echo "<br>Error: ".$sql."<br>".$conn->error;
				}
			}
		} else {
			// Form has not been submitted, do nothing
		}

	}

?>


<!DOCTYPE HTML>
<!--
	Escape Velocity by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>Wine Snob | Write a Review</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/main.css" />
	</head>
	<body class="left-sidebar is-preload">
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
								if (isset($_SESSION['logged_in']) && ($_SESSION['user_name'] != '')) {
									?>
									<div class="col-10">
										<a href="profile.php" class="button style1">My Profile</a>
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
									<li><a href="regions.php">Explore the data</a><//li>
								</ul>
							</nav>

					</section>

			<!-- Main -->
				<section id="main" class="wrapper style2">
					<div class="container">
						<div class="row gtr-150">
							<div class="col-4 col-12-medium">

								<!-- Sidebar -->
									<div id="sidebar">
										<section class="box">
											<header>
												<center><h2>Find a Recommended Wine!</h2></center>
											</header>
											<form action="search.php" method="POST">
												<div class="row gtr-50">

													<!-- Keyword Search -->
													<div class="col-12">
														<input type="text" name="keyword-search" placeholder="Keyword Search">
													</div>

													<!-- Variety Selection Dropdown -->
													<div class="col-12">
														<select name="variety-search">
															<option value="All Varieties">Grape Variety (opt.)</option>
															<?php while($row = mysqli_fetch_assoc($varietyResult)){
																if($row['variety'] != ""){
																	echo "<option value=".$row['variety'].">".$row['variety']."</option>";
																}
															}
															mysqli_free_result($varietyResult); ?>
														</select>
													</div>

													<!-- Country Selection Dropdown -->
													<div class="col-12">
														<select name="country-search">
															<option value="All Countries">Country (opt.)</option>
															<?php while($row = mysqli_fetch_assoc($countryResult)){
																if($row['country'] != ""){
																	echo "<option value=".$row['country'].">".$row['country']."</option>";
																}
															}
															mysqli_free_result($countryResult); ?>
														</select>
													</div>

													<!-- Input Price Range -->
													<div class="col-6 col-12-small">
														<input type="text" name="min-price-search" placeholder="Minimum Price">
													</div>
													<div class="col-6 col-12-small">
														<input type="text" name="max-price-search" placeholder="Maximum Price">
													</div>

													<!-- Submit Button -->
													<div class="col-12">
														<center><button type="submit" class="button style1" name="submit-search">Search</button></center>
													</div>
												</div>
											</form>
										</section>
									</div>
							</div>
							<div class="col-8 col-12-medium imp-medium">

								<!-- Content -->
									<div id="content">
										<article class="box post">
											<header class="style1">
												<h2>Write a Review</h2>

												<?php // if a review has previously been submitted
												if(isset($_POST['submit-search'])){
													echo "Thank you for your review submission.";
												} ?>
											</header>
											<!-- Review Form -->
											<!-- this is a post method because we need data from the client side: they are entering the username and password -->
											<section>
												<form method='POST'>

													<div class="row gtr-50">

														<?php if($_GET['id']){
															$row = mysqli_fetch_assoc($reviewsResult);
															echo "<div class='col-10 off-1'>
																		<input type='text' name='wine-name' value='".$row['title']."' readonly/>
															</div>
															<div class='col-10 off-1'>
																<textarea name='review-description' placeholder='".$rev_desc."' rows='4'></textarea>
															</div>
															<div class='col-5 off-1'>
																<input type='number' step='1' min='0' max='100' name='score' placeholder='Rating'/>
															</div>
															<div class='col-5'>
																<input type='number' step='1' min='0' max='1000' name='price' value='".$row['price']."'/ readonly>
															</div>
															<div class='col-10 off-1' align='center'>
																<input type='submit' class='button style1' name='submit-review'>
															</div>";
															mysqli_free_result($reviewsResult);
														} else {
															echo "<div class='col-10 off-1'>
																<input type='text' name='wine-name' placeholder='Wine Name'/>
															</div>
															<div class='col-10 off-1'>
																<textarea name='review-description' placeholder='".$rev_desc."' rows='4'></textarea>
															</div>
															<div class='col-5 off-1'>
																<input type='number' step='1' min='0' max='100' name='score' placeholder='Rating'/>
															</div>
															<div class='col-5'>
																<input type='number' step='1' min='0' max='1000' name='price' placeholder='Price ($)'/>
															</div>
															<div class='col-10 off-1' align='center'>
																<input type='submit' class='button style1' name='submit-review'>
															</div>";
														} ?>
													</div>
												</form>
											</section>
									</div>
							</div>
						</div>
					</div>
				</section


			<!-- Footer -->
				<section id="footer" class="wrapper">
					<div class="container">
						<header class="style1">
							<h2>We came, we saw, we drank!</h2>
						</header>

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
