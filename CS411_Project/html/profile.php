<!-- This document includes the "My Profile" page, along with ways to edit or remove reviews -->
<!-- TODO: grab price info from WINES <br>
      Modifying records was working but now its broken... investigate and fix this <br>
      Deleting records is completed!
-->
<?php
	include 'dbh.php';
	session_start();

	// Get all info about this user
	$sql = "SELECT user_id, name, age FROM USERS WHERE user_id='".$_SESSION['user_name']."'";
	$result = mysqli_query($conn,$sql);
	$queryResult = mysqli_num_rows($result);
	$row = mysqli_fetch_assoc($result);

	// Get all reviews that this user has written
	$reviewsSql = "SELECT r.review_id, r.description, r.score, r.user_id, r.wine_id,
                          u.name, u.age,
                          w.title, w.price
                          FROM REVIEWS r, USERS u, WINES w
                          WHERE u.user_id = '".$_SESSION['user_name']."'
                          AND r.user_id = u.user_id
                          AND r.wine_id = w.wine_id";
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
								<li><a href="regions.php">Explore the data</a></li>
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
