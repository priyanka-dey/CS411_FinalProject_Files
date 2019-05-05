<?php
	include 'dbh.php';
	session_start();

	$user_name = $_SESSION['user_name'];

	// Get wine review ID number
	if($_GET['id']){
		$wineID = $_GET['id'];
	} else{
		die('The page you are searching for does not exist');
	}

	// Use a query to get information about the wine
	$sql = "SELECT * FROM WINES AS W1, WINES AS W2 WHERE W1.wine_id=".$wineID." AND W1.title=W2.title";
	$result = mysqli_query($conn,$sql);
	$queryResult = mysqli_num_rows($result);

	// Get Wine Snob user reviews
	$userReviewsSql = "SELECT REVIEWS.description, REVIEWS.score, REVIEWS.user_id, USERS.name, REVIEWS.wine_id FROM REVIEWS, USERS WHERE USERS.user_id=REVIEWS.user_id AND REVIEWS.wine_id='$wineID'";
	$userReviewsResult = mysqli_query($conn,$userReviewsSql);
	$userReviewsQueryResult = mysqli_num_rows($result);

	// figure out if any of the reviews in the reviews being printed are of the user logged in's

	// Find all distinct grape varieties - use in search field drop down menus
	$varietySql = "SELECT variety FROM WINES GROUP BY variety";
	$varietyResult = mysqli_query($conn,$varietySql);
	$varietyQueryResult = mysqli_num_rows($varietyResult);

	// Find all distinct countries - use in search field drop down menus
	$countrySql = "SELECT country FROM WINES GROUP BY country";
	$countryResult = mysqli_query($conn,$countrySql);
	$countryQueryResult = mysqli_num_rows($countryResult);

	// this is the delete review button stuff
	if(isset($_POST['delete'])){
	 // lets do the sql query
		echo "HERE";
		$deleteReviewSQL = "DELETE FROM REVIEWS WHERE user_id='$user_name' AND wine_id = '$wineID'";
		$deleteResult = mysqli_query($conn, $deleteReviewSQL);
		header("Refresh:0");
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
		<title>Wine Snob | Review</title>
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
								<!-- Login, Signup buttons -->
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
								<li><a href="regions.php">Explore The Data</a></li>
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
															mysqli_free_result($countryResult);
															?>
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
															mysqli_free_result($countryResult);
															?>
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
											<?php if ($queryResult > 0){
												$idx = 1;
												while($row = mysqli_fetch_assoc($result)){
													if($idx == 1){
														echo "<header class='style1'>
															<h2>".$row['title']."</h2>
														</header>
														<div class='row gtr-50'>
															<div class='col-12' align='center'>
																A ".$row['designation']." wine ";
																if($row['region_1'] != ""){
																	echo "from the ".$row['region_1']." region, ";
																}
																echo "produced by ".$row['winery']." in ".$row['country'].".<br><br>
															</div>
														
														</div>";

														echo "<div>
															<section class='box'>
																<div class='row gtr-25'>
																	<div class='col-3 col-12-small' align='center'>
																		<b>Designation:</b><br>";
																		if($row['designation'] != ''){
																			echo $row['designation'];
																		} else{
																			echo "Unknown";
																		}
																	echo "</div>
																	<div class='col-3 col-12-small' align='center'>
																		<b>Variety:</b><br>";
																		if($row['variety'] != ''){
																			echo $row['variety'];
																		} else{
																			echo "Unknown";
																		}
																	echo "</div>
																	<div class='col-3 col-12-small' align='center'>
																		<b>Winery:</b><br>";
																		if($row['winery'] != ''){
																			echo $row['winery'];
																		} else{
																			echo "Unknown";
																		}
																	echo "</div>
																	<div class='col-3 col-12-small' align='center'>
																		<b>Location:</b><br>";
																		if($row['region_1'] != ""){
																		echo $row['region_1'].", ";
																		} elseif($row['province'] != ""){
																			echo $row['province'].", ";
																		}
																		if($row['country'] != ""){
																			echo $row['country'];
																		} ?>
																	</div>
																	<div class='col-12'>
																		<br>
																	</div>
																	<div class='col-12' align='center'>
																	<?php
																		$URL = 'http://sp19-cs411-48.cs.illinois.edu/reviews.php?id='.$row['wine_id'];
																	?>
																		<a href=<?php echo $URL ?> class='button style1'>Review</a>
																	<!--
																		<a class='button style1'>Like!</a>
																		<a class='button style2'>Unlike.</a>
																	-->

																	</div>
													<?php } else{
														echo "<div>
															<section class='box'>
																<div class='row gtr-25'>";
													}

																echo "<div class='col-12'>
																	<hr>
																</div>";

																if($row['description'] != ''){
																	echo "<div class='col-12'>
																		<header class='style2' align='center'>
																			<h2>Reviews From the Professionals:</h2>
																		</header>
																	</div>
																	<div class='col-12'>
																		<i>\"".$row['description']."\"</i>
																	</div>
																	<div class='col-12' align='right'>";
																		if($row['taster_name'] != "" && $row['taster_twitter_handle'] != ""){
																			echo "-".$row['taster_name'].", <a href='http://twitter.com/".$row['taster_twitter_handle']."'>".$row['taster_twitter_handle']."</a>";
																		} elseif($row['taster_name'] != ""){
																			echo "-".$row['taster_name'];
																		} elseif($row['taster_twitter_handle'] != ""){
																			echo "-<a href='http://twitter.com/".$row['taster_twitter_handle']."'>".$row['taster_twitter_handle']."</a>";
																		} else{
																			echo "-Anonymous";
																		}
																	echo "</div>

																	<div class='col-12 col-12-small' align='right'>";
																		if($row['points'] != ""){
																			echo "<b>Rating:</b> ".$row['points']."<br>";
																		} else{
																			echo "<b>Rating:</b> None<br>";
																		}

																		if($row['price'] != 0){
																			echo "<b>Price:</b> $".$row['price'];
																		} else{
																			echo "<b>Price:</b> Unknown";
																		}
																	echo "</div>";
																}
															echo "</div>
														</section>
													</div>";
													$idx = $idx + 1;
												}
											}
											mysqli_free_result($result);

											$idx = 1;
											while($row = mysqli_fetch_assoc($userReviewsResult)){
												echo "<div>
													<section class='box'>
														<div class='row gtr-25'>";
															if($idx == 1){
																echo "
																</div>
																<div class='col-12'>
																	<hr>
																</div>
																<div class='col-12'>
																	<header class='style2'>
																		<h2><center>Reviews From Our Users:</center></h2>
																	</header>
																";
															}
															echo "<div class='col-12'>
																<i>\"".$row['description']."\"</i>
															<div>
															<div class='col-12' align='right'>
																-".$row['name'].", @".$row['user_id']."
															</div>
															<div class='col-6 col-12-small' align='right'>";
																if($row['score'] != ""){
																	echo "<b>Rating:</b> ".$row['score']."<br>";
																} else{
																	echo "<b>Rating:</b>None<br>";
																}
																//print_r ($row);
															echo "</div>";
															if ($_SESSION['user_name'] == $row['user_id']) {
																$URL = 'reviews.php?id='.$row['wine_id'];
																?>
																 <form href=<?php echo $URL; ?> align="right">
                                                                                                                                        <input type="submit" href='edit.php?id=".$row['review_id']."' name="edit" value="EDIT">
                                                                                                                                </form> 

																	<form method="POST" align="right">
                                                                                                                        	                        <input type="submit" name="delete" value="DELETE">
                                                                                                                                	 </form>


																<!-- need to figure out how to delete a review
															 		 i need a button called delete which will call a php function -->
															<?php }

														echo "
														</div>
													</section>
												</div>";
												$idx = $idx+1;
											}
											mysqli_free_result($userReviewsResult); ?>
										</article>
									</div>
							</div>
						</div>
					</div>
				</section>



			<!-- Wine Recommendations -->
			<!-- we created this search engine based on the assumption that a user would want to explore wines in approximately similar price ranges, and that they have similar tastes as reviewers who also liked a similar wine -->
				<section id="highlights" class="wrapper style3">
					<div class="title">You might also like...</div>
					<div class="container">
						<div class="row aln-top">
							<?php // need to redefine result after freeing it up top
							$result = mysqli_query($conn,$sql);
							$queryResult = mysqli_num_rows($result);

							// define a variable to keep count of the number of recommendations - make sure it's at 6 total rec's at all times
							$numRecs = 0;

							if($queryResult > 0){
								// Loop through all reviews to find recommendations
								while($row = mysqli_fetch_assoc($result)){

									// Recommend the highest rated wine with the same grape variety that falls within a similar price range
									$recSql = "SELECT wine_id, country, description, designation, points, price, region_1, title, variety, winery, MIN(price), MAX(price), AVG(points) FROM WINES WHERE variety=\"".$row['variety']."\" AND price>=".($row['price']-25)." AND price<=".($row['price']+25)." AND title!=\"".$row['title']."\" GROUP BY title ORDER BY AVG(points) DESC LIMIT 1";
									// echo $recSql;
									$recResult = mysqli_query($conn,$recSql);
									$recQueryResult = mysqli_num_rows($recResult);
									if($recQueryResult > 0){
										while($recRow = mysqli_fetch_assoc($recResult)){
											if($numRecs<6){
												$numRecs = $numRecs + 1;
												echo "<div class='col-4 col-12-medium'>
													<section class='highlight'>
														<h3 style='color:black'>".$recRow['title']."</h3>
														<p>A ".$recRow['designation']." wine ";
														if($recRow['region_1']!=""){
															echo "from the ".$recRow['region_1']." region, ";
														}
														echo "produced by ".$recRow['winery']." in ".$recRow['country'].".</p>
														<ul class='actions'>
															<li><a href='description.php?id=".$recRow['wine_id']."' class='button style1'>Read Reviews</a></li>
														</ul>
													</section>
												</div>";
											}
										}
									}
									mysqli_free_result($recResult);


									// If this wine has been highly reviewed by a taster, then recommend the highest reviewed wine by the same taster that falls within a similar price range
									if($row['points']>=90 AND $row['taster_name']!=""){
										$recSql = "SELECT wine_id, country, description, designation, points, price, region_1, title, variety, winery, MIN(price), MAX(price), AVG(points) FROM WINES WHERE taster_name=\"".$row['taster_name']."\" AND price>=".($row['price']-25)." AND price<=".($row['price']+25)." AND title!=\"".$row['title']."\" GROUP BY title ORDER BY AVG(points) DESC LIMIT 1";
										// echo $recSql;
										$recResult = mysqli_query($conn,$recSql);
										$recQueryResult = mysqli_num_rows($recResult);
										if($recQueryResult > 0){
											while($recRow = mysqli_fetch_assoc($recResult)){
												if($numRecs<6){
													$numRecs = $numRecs + 1;
													echo "<div class='col-4 col-12-medium'>
														<section class='highlight'>
															<h3 style='color:black'>".$recRow['title']."</h3>
															<p>A ".$recRow['designation']." wine ";
															if($recRow['region_1']!=""){
																echo "from the ".$recRow['region_1']." region, ";
															}
															echo "produced by ".$recRow['winery']." in ".$recRow['country'].".</p>
															<ul class='actions'>
																<li><a href='description.php?id=".$recRow['wine_id']."' class='button style1'>Read Reviews</a></li>
															</ul>
														</section>
													</div>";
												}
											}
										}
										mysqli_free_result($recResult);
									}

									// Recommend the highest rated wine from the same winery
									$recSql = "SELECT wine_id, country, description, designation, points, price, region_1, title, variety, winery, MIN(price), MAX(price), AVG(points) FROM WINES WHERE winery=\"".$row['winery']."\" AND title !=\"".$row['title']."\" GROUP BY title ORDER BY AVG(points) DESC LIMIT 1";
									// echo $recSql;
									$recResult = mysqli_query($conn,$recSql);
									$recQueryResult = mysqli_num_rows($recResult);

									if($recQueryResult > 0){
										while($recRow = mysqli_fetch_assoc($recResult)){
											if($numRecs<6){
												$numRecs = $numRecs + 1;
												echo "<div class='col-4 col-12-medium'>
													<section class='highlight'>
														<h3 style='color:black'>".$recRow['title']."</h3>
														<p>A ".$recRow['designation']." wine ";
														if($recRow['region_1']!=""){
															echo "from the ".$recRow['region_1']." region, ";
														}
														echo "produced by ".$recRow['winery']." in ".$recRow['country'].".</p>
														<ul class='actions'>
															<li><a href='description.php?id=".$recRow['wine_id']."' class='button style1'>Read Reviews</a></li>
														</ul>
													</section>
												</div>";
											}
										}
									}
									mysqli_free_result($recResult);


									// Recommend overall wine with the highest rating for a similar price (use this to fill up the remaining 6 spots)
									if($numRecs<6){
										$recSql = "SELECT wine_id, country, description, designation, points, price, region_1, title, variety, winery, MIN(price), MAX(price), AVG(points) FROM WINES WHERE price>=".($row['price']-25)." AND price<=".($row['price']+25)." GROUP BY title ORDER BY AVG(points) DESC LIMIT ".(6-$numRecs);
										$recResult = mysqli_query($conn,$recSql);
										$recQueryResult = mysqli_num_rows($recResult);

										if($recQueryResult > 0){
											while($recRow = mysqli_fetch_assoc($recResult)){
												$numRecs = $numRecs + 1;
												echo "<div class='col-4 col-12-medium'>
													<section class='highlight'>
														<h3 style='color:black'>".$recRow['title']."</h3>
														<p>A ".$recRow['designation']." wine ";
														if($recRow['region_1']!=""){
															echo "from the ".$recRow['region_1']." region, ";
														}
														echo "produced by ".$recRow['winery']." in ".$recRow['country'].".</p>
														<ul class='actions'>
															<li><a href='description.php?id=".$recRow['wine_id']."' class='button style1'>Read Reviews</a></li>
														</ul>
													</section>
												</div>";
											}
										}
									}
									mysqli_free_result($recResult);
								}
							}
							mysqli_free_result($result); ?>


						</div>
					</div>
				</section>

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
