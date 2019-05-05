<?php
    include 'dbh.php';
    session_start();
    //

    // the 3 maps
    $user_name = $_SESSION['user_name'];
    $allWinesByCountrySQL = "SELECT country, count(*) as all_wine_count
                             FROM WINES
                             GROUP BY country
                             HAVING LENGTH(country) > 0";
    $allWinesByCountryResult = mysqli_query($conn, $allWinesByCountrySQL);
    $userWinesByCountrySQL = "SELECT a.country as country, count(*) as user_wine_count
                              FROM
                                  (SELECT country, w.wine_id
                                   FROM WINES w
                                   INNER JOIN REVIEWS r
                                   ON w.wine_id=r.wine_id
                                   WHERE r.user_id='".$user_name."') a
                              GROUP BY a.country";
    $userWinesByCountryResult = mysqli_query($conn, $userWinesByCountrySQL);
    $userRecWinesSQL = "SELECT a.country as country, count(*) as user_rec_count
                        FROM
                            (SELECT country, w.wine_id
                             FROM WINES w
                             INNER JOIN RECOMMENDATIONS r
                             ON w.wine_id=r.wine_id
                             WHERE r.user_id='".$user_name."') a
                        GROUP BY a.country";

    $userRecWinesResult = mysqli_query($conn, $userRecWinesSQL);
?>
<?php
// the top rated wines from each country as rated by the profs
// also has the price attached to it so its a nice bubble chart
    include 'dbh.php';
    session_start();
    $user_name = $_SESSION['user_name'];
    $profBestRatedWinesByCountrySQL = "SELECT max(points) as rating, country, title, price
                                    FROM WINES where country != ''
                                    GROUP BY country";
    $profBestRatedWinesByCountryResult = mysqli_query($conn, $profBestRatedWinesByCountrySQL);
?>

<?php
// this is for the triple bar chart on review scores:::
    include 'dbh.php';
    session_start();
    $user_name = $_SESSION['user_name'];
    $tripleBarChartSQL = "SELECT a_1.wine_id, a_1.avg_score, a_1.prof_points, r_1.score as user_score FROM
                           (SELECT a.wine_id, a.score as avg_score, b.points as prof_points FROM
                           (SELECT r.wine_id, avg(score) as score FROM WINES w
                           INNER JOIN REVIEWS r ON w.wine_id=r.wine_id
                           group by wine_id) a
                           INNER JOIN WINES b
                           ON b.wine_id=a.wine_id) a_1
                           INNER JOIN REVIEWS r_1
                           ON a_1.wine_id=r_1.wine_id
                           WHERE user_id='$user_name'";
    $tripleBarChartResult = mysqli_query($conn, $tripleBarChartSQL);
?>

<?php
/// Average Rating per country (user-based and professional-based averages per country)
    include 'dbh.php';
    session_start();
    $user_name = $_SESSION['user_name'];
    $countryRatingsSQL = "SELECT a.prof_avg as professional_avg, b.country as country, avg(b.score) as user_avg FROM
                           (SELECT avg(points) as prof_avg, country FROM WINES GROUP BY country) a
                           INNER JOIN
                           (SELECT country, avg(score) as score FROM WINES w
                            INNER JOIN REVIEWS r on r.wine_id=w.wine_id GROUP BY country) b
                          ON a.country=b.country
                          GROUP by b.country";
    $countryRatingsResult = mysqli_query($conn, $countryRatingsSQL);
?>

<html>
  <head>
		<title>Wine Snob | Charts</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/main.css" />
		<link rel="stylesheet" href="assets/css/image.css" />
                <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

                <script src="assets/js/jquery.min.js"></script>
		<script src="assets/js/jquery.dropotron.min.js"></script>
		<script src="assets/js/browser.min.js"></script>
		<script src="assets/js/breakpoints.min.js"></script>
		<script src="assets/js/util.js"></script>
		<script src="assets/js/main.js"></script>

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
    <script type="text/javascript">
      google.charts.load('current', {
        'packages':['geochart', 'corechart', 'bar'],
        // Note: you will need to get a mapsApiKey for your project.
        // See: https://developers.google.com/chart/interactive/docs/basic_load_libs#load-settings
        'mapsApiKey': 'AIzaSyD-9tSrke72PouQMnMX-a7eZSW0jkFMBWY'

      });
      // google.charts.setOnLoadCallback(drawRegionsMap);

      function hideDivs() {
         var div_1 = document.getElementById("regions_div_1");
         div_1.style.display = "none";

         var title_div_1 = document.getElementById("regions_title_div_1");
         title_div_1.style.display = "none";
         var title_div_2 = document.getElementById("regions_title_div_2");
         title_div_2.style.display = "none";
         var title_div_3 = document.getElementById("regions_title_div_3");
         title_div_3.style.display = "none";
         var series_chart_title = document.getElementById("series_chart_title");
         series_chart_title.style.display = "none";
         var barchart_title = document.getElementById("barchart_title");
         barchart_title.style.display = "none";


         var div_2 = document.getElementById("regions_div_2");
         div_2.style.display = "none";
         var div_3 = document.getElementById("regions_div_3");
         div_3.style.display = "none";
         var bar_div = document.getElementById("barchart_material");
         bar_div.style.display = "none";
         var series_div = document.getElementById("series_chart_div");
         series_div.style.display = "none";
      }
      function showMapDivs() {
         var div_1 = document.getElementById("regions_div_1");
         div_1.style.display = "block";
         var div_2 = document.getElementById("regions_div_2");
         div_2.style.display = "block";
         var div_3 = document.getElementById("regions_div_3");
         div_3.style.display = "block";
         var title_div_1 = document.getElementById("regions_title_div_1");
         title_div_1.style.display = "block";
         var title_div_2 = document.getElementById("regions_title_div_2");
         title_div_2.style.display = "block";
         var title_div_3 = document.getElementById("regions_title_div_3");
         title_div_3.style.display = "block";
      }
      function showBarDiv() {
         var bar_div = document.getElementById("barchart_material");
         bar_div.style.display = "block";
         var barchart_title = document.getElementById("barchart_title");
         barchart_title.style.display = "block";
      }
      function showSeriesDiv() {
         var series_div = document.getElementById("series_chart_div");
         series_div.style.display = "block";
         var series_chart_title = document.getElementById("series_chart_title");
         series_chart_title.style.display = "block";
      }


      function drawRegionsMap() {
        hideDivs();
        showMapDivs();
        var data_1 = google.visualization.arrayToDataTable([
          ['Country', 'Wine Count'],
          <?php   while($row = mysqli_fetch_assoc($allWinesByCountryResult)){ ?>
                ['<?php echo $row['country']?>' , <?php echo $row['all_wine_count']?>],
                <?php } ?>
        ]);

        var data_2 = google.visualization.arrayToDataTable([
          ['Country', 'Wine Count'],
          <?php   while($row = mysqli_fetch_assoc($userWinesByCountryResult)){ ?>
                ['<?php echo $row['country']?>' , <?php echo $row['user_wine_count']?>],
                <?php } ?>
        ]);
         var data_3 = google.visualization.arrayToDataTable([
          ['Country', 'Wine Count'],
          <?php   while($row = mysqli_fetch_assoc($userRecWinesResult)){ ?>
                ['<?php echo $row['country']?>' , <?php echo $row['user_rec_count']?>],
                <?php } ?>
        ]);


        var options = {};
        options['colorAxis'] = {colors : ['18de99', '21e0d6', '2dc5e3', '05a6e6', '1489e8',
                                          '1e69eb', '202aed', '461bf0', '6f1df2', '6f1df2']};
        options['backgroundColor'] = '#f6f7ff';
        options['datalessRegionColor'] = '#bed0ff';
        options['width'] = 900;
        options['height'] = 500;
        options['backgroundColor'] = { stroke: 'white', strokeWidth:  10};


        var chart_1 = new google.visualization.GeoChart(document.getElementById('regions_div_1'));
        chart_1.draw(data_1, options);

        var chart_2 = new google.visualization.GeoChart(document.getElementById('regions_div_2'));
        chart_2.draw(data_2, options);


        var chart_3 = new google.visualization.GeoChart(document.getElementById('regions_div_3'));
        chart_3.draw(data_3, options);
      }
    function drawSeriesChart() {
        hideDivs();
        showSeriesDiv();

      var data = google.visualization.arrayToDataTable([
        ['Country', 'Rating', 'Price', 'Wine Title'],
        <?php   while($row = mysqli_fetch_assoc($profBestRatedWinesByCountryResult)){ ?>
              ["<?php echo $row['country']?>" , <?php echo $row['rating']?>,
                <?php echo $row['price']?>, "<?php echo $row['title']?>" ],
        <?php } ?>

      ]);
      var options = {
        hAxis: {
    	      viewWindowMode: 'pretty',
              title: 'Rating',
              viewWindow: {
                  min: 82,
                  max: 102
              }
        },
        vAxis: {
    	      viewWindowMode: 'pretty',
              title: 'Price',
              viewWindow: {
                  min: -10,
                  max: 100
              }
        },
        height: '100%',
        legend: {
          position: 'right'
        },
        sizeAxis: {
          maxSize: 20,
          minSize: 10
        },
        width: '100%',
        backgroundColor: { stroke: 'white', strokeWidth:  10},
        bubble: {textStyle: {fontSize: 11}}
      };

      var chart = new google.visualization.BubbleChart(document.getElementById('series_chart_div'));
      chart.draw(data, options);
    }
      function drawBarChart() {
        hideDivs();
        showBarDiv();
        var data = google.visualization.arrayToDataTable([
          ['Wine_id', 'Your Review', 'Professional Review', 'Avg Reviews'],
          <?php   while($row = mysqli_fetch_assoc($tripleBarChartResult)){ ?>
                ["<?php echo $row['wine_id']?>" , <?php echo $row['user_score']?> ,
                 <?php echo $row['prof_points']?> , <?php echo $row['avg_score']?> ],
                <?php } ?>
        ]);

        var options = {
          chart: {
          },
          backgroundColor: { stroke: 'white', strokeWidth:  10},
          bars: 'horizontal' // Required for Material Bar Charts.
        };

        var chart = new google.charts.Bar(document.getElementById('barchart_material'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
      function drawUserBarChart() {
          var data = google.visualization.arrayToDataTable([
            ['Country', 'User Average', 'Professional Average'],
            <?php   while($row = mysqli_fetch_assoc($countryRatingsResult)){ ?>
                  ["<?php echo $row['country']?>" , <?php echo $row['professional_avg']?> ,
                   <?php echo $row['user_avg']?>],
                  <?php } ?>
        ]);

        var options = {
            chart: {
            title: 'Average Rating Per Country'
          },
          bars: 'horizontal', // Required for Material Bar Charts.
          backgroundColor: { stroke: 'white', strokeWidth:  10},
          colors: ['#cfc2ff', '#b8caff']
        };

        var chart = new google.charts.Bar(document.getElementById('barchart_material'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
   </script>
  </head>
   <body class="left-sidebar is-preload">
     <div>
    <section id="highlights" class="wrapper style3" style="padding=0%; margin_top:0%">
          <div class="title">Visualize Wine Data</div>
          <div class="container">
            <div class="row aln-center">
              <div class="col-4 col-12-medium">
                <section class="highlight">
                  <a href="#" class="image featured">
                     <img src="t_3.png" alt="" width="45" height="180"
                           onclick="drawSeriesChart()"/>
                  </a>
                  <h3>Top Rated Wines!</h3>
                  <p>Find the best wines from every country around the world!</p>
                </section>
              </div>
              <div class="col-4 col-12-medium">
                <section class="highlight">
                  <a href="#" class="image featured">
                  <img src="t_2.png" alt="" width="45" height="180"
                   onclick="drawRegionsMap()">

                  </img>
                  </a>
                  <h3>Maps!</h3>
                  <p>Visualize how many wines come from around the world's countries in these maps!</p>
                </section>
              </div>
              <div class="col-4 col-12-medium">
                <section class="highlight">
                  <a href="#" class="image featured">
                      <img src="t_1.png" alt="" width="45" height="180"
                       onclick="drawBarChart()"/>
                   </a>
                  <h3>Wine Scores!</h3>
                  <p> See how your review scores are related to professionals' and other users' ratings!
                    </p>
                 </section>
             	</div>
		 </div>
          </div>

    <div class="container" id="regions_title_div_1" style="width: 900px; display:none; margin-top:8%;">
       <section class="highlight">
            <h3 class="container" style="width: 900px; align: center">
                 From Around the World: Wine Counts
            </h3>
       </section>
    </div>
    <div class="container" id="regions_div_1"       style="align: center; width: 900px; height: 500px; display:none; margin-top:0%;">
    </div>

    <div class="container" id="regions_title_div_2" style="width: 900px; display:none; margin-top:8%;">
       <section class="highlight">
            <h3 class="container" style="width: 900px; align: center">
                 Your Reviews: Wine Counts
            </h3>
       </section>
    </div>
    <div class="container" id="regions_div_2"       style="width: 900px; height: 500px; display:none; margin-top:0%;">
    </div>


    <div class="container" id="regions_title_div_3" style="width: 900px; display:none; margin-top:8%;">
       <section class="highlight">
            <h3 class="container" style="width: 900px; align: center">
                 Your Recommendations: Wine Counts
            </h3>
       </section>
    </div>
    <div class="container" id="regions_div_3"       style="width: 900px; height: 500px; display:none; margin-top:0%;">
    </div>
    <div class="container" id="barchart_title" style="width: 900px; display:none; margin-top:8%;">
       <section class="highlight">
            <h3 class="container" style="width: 900px; align: center">
                 Review Score Comparisons
            </h3>
       </section>
    </div>
    <div class="container" id="barchart_material"   style="width: 900px; height: 500px; display:none; margin-top:0%;">
    </div>
    <div class="container" id="series_chart_title" style="width: 900px; display:none; margin-top:8%;">
       <section class="highlight">
            <h3 class="container" style="width: 900px; align: center">
                Top Rated Wines From Each Country (Rated by Professionals)
            </h3>
       </section>
    </div>
    <div class="container" id="series_chart_div"    style="width: 900px; height: 600px; display:none; margin-top:0%;">
    </div>
        </section>
      </div>
  </body>
</html>
