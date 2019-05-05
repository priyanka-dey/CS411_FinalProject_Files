<?php
include 'dbh.php';
session_start();
if(isset($_POST['submit-search'])){
    $keywordSearch = mysqli_real_escape_string($conn,$_POST['keyword-search']);
    $typeSearch = mysqli_real_escape_string($conn,$_POST['type-search']);
    $varietySearch = mysqli_real_escape_string($conn,$_POST['variety-search']);
    $countrySearch = mysqli_real_escape_string($conn,$_POST['country-search']);
    $minPriceSearch = mysqli_real_escape_string($conn,$_POST['min-price-search']);
    $maxPriceSearch = mysqli_real_escape_string($conn,$_POST['max-price-search']);
}


$sql = "SELECT wine_id, country, description, designation, points, price, region_1, title, variety, winery, MIN(price), MAX(price), AVG(points) FROM WINES";
// Add the keyword stuff
if ($keywordSearch != "") {
    $sql = $sql." WHERE title LIKE '%".$keywordSearch."%' OR description LIKE '%".$keywordSearch."%'";
}
// Add to the query if variety is specified
if($varietySearch != "All Varieties"){
    if (strpos($sql, 'WHERE') != false) {
        $sql = $sql." AND variety='".$varietySearch."' ";
    } else {
        $sql = $sql." WHERE variety='".$varietySearch."' ";
    }
}
// Add to the query if country is specified
if($countrySearch != "All Countries"){
    if (strpos($sql, 'WHERE') != false) {
        $sql = $sql."AND country='".$countrySearch."' ";
    } else {
        $sql = $sql." WHERE country='".$countrySearch."' ";
    }
}

$sql = $sql." GROUP BY title";

// Add min/max bounds to the query if specified
if($minPriceSearch != "" AND $maxPriceSearch != "" AND $price != "Unkown"){
    $sql = $sql." HAVING MIN(price) >= ".$minPriceSearch." AND MAX(price) <= ".$maxPriceSearch." ";
} elseif($minPriceSearch != ""){
    $sql = $sql." HAVING MIN(price) >= ".$minPriceSearch." ";
} elseif($maxPriceSearch != ""){
    $sql = $sql." HAVING MAX(price) <= ".$maxPriceSearch." ";
}

//$sql = $sql." ORDER BY AVG(points) DESC";
$sql = $sql." ORDER BY AVG(points) DESC";
$result = mysqli_query($conn,$sql);
$queryResult = mysqli_num_rows($result);

// Find all distinct grape varieties - use in search field drop down menus
$varietySql = "SELECT variety FROM WINES GROUP BY variety";
$varietyResult = mysqli_query($conn,$varietySql);
$varietyQueryResult = mysqli_num_rows($varietyResult);

// Find all distinct countries - use in search field drop down menus
$countrySql = "SELECT country FROM WINES GROUP BY country ORDER BY country";
$countryResult = mysqli_query($conn,$countrySql);
$countryQueryResult = mysqli_num_rows($countryResult);

/*
// Number of results to show per page
$perPage = 50;
// Figure out the total pages inthe database
if($queryResult != 0){
	$totalPages = ceil($queryResult/$perPage);
	// Check if the 'page' variable is set in the URL
	if(isset($_GET['page']) && is_numeric($_GET['page'])){
		$showPage = $_GET['page'];
		// Make sure that showPage is valid
		if($showPage > 0 && $showPage <= $totalPages){
			$start = ($showPage-1)*$perPage;
			$end = $start + $perPage;
		} else{
			// There is an error with page numbering in the URL so show the first set of results
			$start = 0;
			$end = $perPage;
		}
	} else{
		// If the page isn't set, show the first set of results
		$start = 0;
		$end = $perPage;
	}
	
} */
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Wine Snob | Search Results</title>
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

            <!-- Navigation -->
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
                    <li><a href="regions.php">Explore By Region</a></li>
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
                                <?php if($queryResult > 0){
                                    echo "<header class='style1'>
                                    	<h2>Search Results (".$queryResult.")</h2>
					<p>Displaying the first 50 results</p>
                                    </header>";

				    // Display pagination
				    /*echo "<div align='center'>
					<b>View Page:</b> ";
				    	for($i = 1; $i <= $totalPages; $i++){
						if(isset($_GET['page']) && $_GET['page'] == $i){
							echo $i." ";
						} else{
							echo "<a href='search.php?page=$i'>$i</a> ";
						}
				    	}
				    echo "</div>
				    <div>
					<hr>
				    </div>";*/
	
				    $i = 1;
                                    while($row = mysqli_fetch_assoc($result)){
                                        // Loop through results, display only the ones that are for this page
					if($i >= 1 && $i <= 50){

					    if($row['title'] != ""){
                                            	echo "<div>
                                    	        <section class='box'>
                                        	    <header>
                                     	       <h2>".$row['title']."</h2>
                         	                   </header>
                                	            <div class='row gtr-50'>

                                        	    <div class='col-12'>
                                	            A ".$row['designation']." wine ";
                                        	    if($row['region_1'] != ""){
                                                	echo "from the ".$row['region_1']." region, ";
                                            	}
                                            	echo "produced by ".$row['winery']." in ".$row['country'].".<br>

                                            	</div>";
                                            	if($row['MIN(price)'] != $row['MAX(price)']){
                                                	echo "<div class='col-12'>
                                                	<b>Average rating:</b> ".$row['AVG(points)']."<br>
                                                	<b>Price range:</b> $".$row['MIN(price)']." - $".$row['MAX(price)']."
                                                	</div>";
                                            	} elseif($row['MIN(price)'] != 0){
                                                	echo "<div class='col-12'>
                                                	<b>Average rating:</b> ".$row['AVG(points)']."<br>
                                                	<b>Price:</b> $".$row['MIN(price)']."
                                                	</div>";
                                            	} else{
                                                	echo "<div class='col-12'>
                                                	<b>Average rating:</b> ".$row['AVG(points)']."<br>
                                                	<b>Price:</b> Unknown
                                                	</div>";
                                            	}
                                            	echo "<div class='off-1 col-4 col-12-small'>
                                            	<a href='description.php?id=".$row['wine_id']."' class='button style1'>Read Reviews</a>
                                            	</div>
                                            	<div class='off-1 col-4 col-12-small'>
                                            	<a href='reviews.php?id=".$row['wine_id']."' class='button style1'>Review Wine</a><br>
                                            	</div>
                                            	<div class='col-12'>
                                            	<hr><br>
                                            	</div>

                                            	</div>
                                            	</section>
                                            	</div>";
					    }
					    $i++;
                                        }
                                    }
                                } else {
                                    echo "<header class='style1'>
                                    <h2>Search Results (0)</h2>
                                    </header>
                                    There are no results matching your search!";
                                }
				
				// Display pagination
				/* echo "<div align='center'>
					<b>View Page:</b> ";
					for($i = 1; $i <= $totalPages; $i++){
						if(isset($_GET['page']) && $_GET['page'] == $i){
							echo $i." ";
						} else{
							echo "<a href='search.php?page=$i'>$i</a> ";
						}
					}
				echo "</div>"; */
				?>
                            </div>
                        </div>

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
