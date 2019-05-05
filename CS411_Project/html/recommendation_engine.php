<?php
include 'dbh.php';
session_start();

// echo "Your recommendations based on your current reviews are: <br>";

$var = $_SESSION['user_name'];
$checkIfRanOnceSQL = "SELECT rec_ran FROM USERS where user_id='".$var."'";
$checkResult = mysqli_query($conn, $checkIfRanOnceSQL);
$row = mysqli_fetch_row($checkResult);
if ($row[0] === NULL) { // rec_ran variable in each user
        $output = shell_exec("python3.6 recommendation_engine.py '$var'");
        $lines = explode("\n", $output);
        foreach($lines as $wine_id) {
            // $result = mysqli_query($conn, $sql);
            $insertSQL = "INSERT INTO RECOMMENDATIONS (user_id, wine_id) 
                          VALUES ('".$var."',".$wine_id.")";
            $insertResult = mysqli_query($conn, $insertSQL);
       }
        // we need to modify row[0] to True (rec engine has run once)
        $updateSQL = "UPDATE USERS
                      SET rec_ran='True'
                      WHERE user_id='".$var."'";
        $updateResult = mysqli_query($conn, $updateSQL);
} else {    
    // rec_ran is True 
    // 1. Check whether the check_var = TRUE (which means we need to execute)
            // if check_var == false // then just take the values from rec table (bc rev_count%10 != 0)
    $check_varSQL = "SELECT check_var 
                    FROM USERS 
                    WHERE user_id='".$var."'";
    $check_varResult = mysqli_query($conn, $check_varSQL);
    $chkVar = mysqli_fetch_row($check_varResult);
    if ($chkVar[0] == 'True') {
        echo ("checkvar if statement");
        // 2. First delete all the records in the recommendations table for the current user
        $deleteSQL = "DELETE FROM RECOMMENDATIONS WHERE user_id='".$var."'";
        $deleteResult = mysqli_query($conn, $deleteSQL);
        // 3. Execute the python script 
        $command = "python3.6 recommendation_engine.py '$var'";
        $output = shell_exec("python3.6 recommendation_engine.py '$var'");
        // 4. Insert results into the recommendations table
        $lines = explode("\n", $output);
        foreach($lines as $wine_id) {
            // $result = mysqli_query($conn, $sql);
            $insertSQL = "INSERT INTO RECOMMENDATIONS (user_id, wine_id) 
                          VALUES ('".$var."',".$wine_id.")";
            $insertResult = mysqli_query($conn, $insertSQL);
       }
        // 5. Make the check_var variable to Null
        $updateChkVarSQL = "UPDATE USERS
                            SET check_var=NULL
                            WHERE user_id='".$var."'";
        $updateChkVarResult = mysqli_query($conn, $updateChkVarSQL); 
    }
}
// Take everything from the recommendations table
$recSQL = "SELECT * FROM RECOMMENDATIONS r, WINES w WHERE r.user_id='".$var."' AND r.wine_id = w.wine_id";
$recResult = mysqli_query($conn, $recSQL);
$recQueryResult = mysqli_num_rows($recResult);
// foreach($recResult as $r) {
//             echo("result: ");
//             echo($r[title]);
//             echo ("<br>");
// }

// Queries to populate the dropdown menus for the search bar
// Find all distinct grape varieties
$varietySql = "SELECT distinct(variety) FROM WINES ORDER BY variety";
$varietyResult = mysqli_query($conn,$varietySql);
$varietyQueryResult = mysqli_num_rows($varietyResult);
// Find all distinct countries
$countrySql = "SELECT distinct(country) FROM WINES ORDER BY country";
$countryResult = mysqli_query($conn,$countrySql);
$countryQueryResult = mysqli_num_rows($countryResult);

?>



<!-- Begin formatting -->
<!DOCTYPE HTML>
<html>
<head>
    <title>Wine Snob | My Recommendations</title>
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
			</div>
			<?php
		    } else {
			?>
			<div class="col-10">
			    <a href="register.php" class="button style1" name="sign-up">Sign Up</a>
			</div>
			<div class="col-1">
			    <a href="login.php" class="button style2" name="log-in">Log In</a>
			</div>
			<?php
		    }
		    ?>
		</div>
		<ul>
		    <li><a href="index.php">Home</a></li>
		    <li class="current"><a href="recommendation_engine.php">Recommended Wines</a></li>
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
					    <input type="text" name="max-price-search" placeholder="Minimum Price">
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

		    <!-- Middle Content Section -->
		    <div class="col-8 col-12-medium imp-medium">
			<div id="content">
			    <article class="box post">
				<?php if($recQueryResult > 0){
				   echo "<header class='style1'>
					<h2>Recommendations (".$recQueryResult.")</h2>
				   </header>";

				   foreach($recResult as $row) {
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
						    	</div>
							<div class='col-12'>
							    <b>Rating:</b> ".$row['points']."<br>
							    <b>Price:</b> $".$row['price']."
						        </div>
						    	<div class='off-1 col-4 col-12-small'>
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
				   } 
				} elseif (isset($_SESSION['logged_in']) && ($_SESSION['user_name'] != '')) {
				    // Logged in but no recommendations
				    echo "<header class='style1'>
					<h2>Recommendations (0)</h2>
				    </header>
				    <p>You have no recommendations yet, please review some wines first so we know what you like!</p>";
				} else {
				    echo "<header class='style1'>
					<h2>Recommendations (0)</h2>
				    </header>
				    <p>Please log in first to view your recommended wines!</p>";
				}?>
			    </article>
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







