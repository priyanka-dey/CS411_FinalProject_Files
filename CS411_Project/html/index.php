<!-- This document contains the formatted Wine Snob home page! -->

<!--
TODO: Fix wine_id to accept title of the wine<br>
      Formatting the Log-in, Sign-up pages.<br>
      Modifying reviews (from the profile page).<br>
      Maybe add a tab to the navigation bar called "My Reviews" that exists only when a user is logged in.<br>
      Fix pagination - is okay for page 1 but doesn't display anything else for other pages.<br>
-->

<?php
	// This file contains the Wine Snob main page
	session_start();
	// need to include all info to connect the database
	include_once('dbh.php');

    if ($_SESSION['logged_in'] == 1)
        // echo "logged in";

	// Find all distinct grape varieties - use in search field drop down menus
	$varietySql = "SELECT distinct(variety) FROM WINES ORDER BY variety";
	$varietyResult = mysqli_query($conn,$varietySql);
	$varietyQueryResult = mysqli_num_rows($varietyResult);

	// Find all distinct countries - use in search field drop down menus
	$countrySql = "SELECT distinct(country) FROM WINES ORDER BY country";
	$countryResult = mysqli_query($conn,$countrySql);
	$countryQueryResult = mysqli_num_rows($countryResult);
?>


<!--
	Escape Velocity by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->

<html>


	<head>
		<title>Wine Snob | Home</title>
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
                            <?php
                            if (isset($_SESSION['logged_in']) && ($_SESSION['user_name'] != '')) {
                                ?>
							<ul>
								<li class="current"><a href="index.php">Home</a></li>
								<li><a href="recommendation_engine.php">Recommended Wines</a></li>
								<li><a href="regions.php">Explore The Data</a></li>
							</ul> <?php
                        }
                            ?>
						</nav>

				</section>

                <?php
                if (isset($_SESSION['logged_in']) && ($_SESSION['user_name'] != '')) {
                    ?>

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
											<form action="search.php?page=".1 method="POST">
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
															} ?>
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
															} ?>
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
												<h2>Interested in trying some new wines?</h2>
												<p>Here's a compiled list of professional favorites!</p>
											</header>
										</article>
										<div class="row gtr-150">
											<div class="col-6 col-12-small">
												<section class="box">
													<header>
														<h2>Popular Wines</h2>
													</header>
													<a href="#" class="image featured"><img src="wine_1.jpg" alt="" width="100" height= "180" /></a>
													<p> <a href="http://sp19-cs411-48.cs.illinois.edu/description.php?id=345"</a> Chambers Rosewood Vineyards NV Rare Muscat (Rutherglen)</a> </p>
													<p> <a href="http://sp19-cs411-48.cs.illinois.edu/description.php?id=7335"</a> Avignonesi 1995 Occhio di Pernice  (Vin Santo di Montepulciano)</a> </p>
													<p> <a href="http://sp19-cs411-48.cs.illinois.edu/description.php?id=36528"</a> Krug 2002 Brut  (Champagne) </a> </p>
													<p> <a href="http://sp19-cs411-48.cs.illinois.edu/description.php?id=39286"</a> Tenuta dell'Ornellaia 2007 Masseto Merlot (Toscana) </a> </p>
													<p> <a href="http://sp19-cs411-48.cs.illinois.edu/description.php?id=42197"</a> Casa Ferreirinha 2008 Barca-Velha Red (Douro) </a> </p>
												</section>
											</div>
											<div class="col-6 col-12-small">
												<section class="box">
													<header>
														<h2>Top 5 Wines on a Budget</h2>
													</header>
													<a href="#" class="image featured"><img src="wine_bottles.jpg" alt="" width="100" height="180" /></a>
													<p> <a href="http://sp19-cs411-48.cs.illinois.edu/description.php?id=111757"</a> Château Ausone 2010 Saint-Émilion</a> </p>
													<p> <a href="http://sp19-cs411-48.cs.illinois.edu/description.php?id=111758"</a> Château Latour 2010 Pauillac</a> </p>
													<p> <a href="http://sp19-cs411-48.cs.illinois.edu/description.php?id=116093"</a> Krug 2002 Brut (Champagne)</a> </p>
													<p> <a href="http://sp19-cs411-48.cs.illinois.edu/description.php?id=116094"</a> Château d'Yquem 2009 Barrel sample (Sauternes)</a> </p>
													<p> <a href="http://sp19-cs411-48.cs.illinois.edu/description.php?id=116095"</a> Château Pontet-Canet 2009 Barrel sample (Pauillac)</a> </p>
												</section>
											</div>
										</div>
									</div>

							</div>
						</div>
					</div>

			</section> <?php
                }
                ?>

<!-- If user is not logged in -->
<?php
    if ($_SESSION['logged_in'] == 0) {
?>

			<!-- Highlights -->
				<section id="highlights" class="wrapper style3">
					<div class="title">What We Have To Offer</div>
					<div class="container">
						<div class="row aln-center">
							<div class="col-4 col-12-medium">
								<section class="highlight">
									<a href="#" class="image featured"><img src="images/reviews.jpg" alt="" width="45" height="180" /></a>
									<h3>Rate Wines!</h3>
									<p>Users of Wine Snob can write reviews with scores. Find out what others think of your favorite wines!</p>
								</section>
							</div>
							<div class="col-4 col-12-medium">
								<section class="highlight">
									<a href="#" class="image featured"><img src="images/wine.jpg" alt="" width="45" height="180"/></a>
									<h3>Get Personalized Wine Recommendations!</h3>
									<p>Users of Wine Snob will be able to select from selected wines based on their preferences. The more wines a user reviews, the more recommendations!</p>
								</section>
							</div>
							<div class="col-4 col-12-medium">
								<section class="highlight">
									<a href="#" class="image featured"><img src="images/map.jpg" alt="" width="45" height="180"/></a>
									<h3>Discover Wines From Around The World</h3>
									<p>Wondering what the world has to offer... visualize the wine dataset via maps and more!</p>
                                				</section>
							</div>
						</div>
					</div>
				</section> <?php
} ?>


			<!-- Footer -->
				<section id="footer" class="wrapper">
					<div class="container">

						<header class="style1">
							<h2>We came, we saw, we drank!</h2>
						</header>


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
