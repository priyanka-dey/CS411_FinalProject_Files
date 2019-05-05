<html>
   <head>
      <title>Connect to MariaDB Server</title>
   </head>

   <body>
	Query statement: SELECT title, country, points, price FROM WINES ORDER BY price DESC LIMIT 10. <br />
      <?php
/*         $dbhost = 'sp19-cs411-48.cs.illinois.edu:3036'; */
         $dbhost = '127.0.0.1';
         $dbuser = 'pdey3';
         $dbpass = 'cs411';
         $dbport = 3036;
         $db = 'wine_snob';
         $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $db);
         if(! $conn ) {
            die('Could not connect: ' . mysql_error());
         }

	 $query = "SELECT title, country, points, price FROM WINES ORDER BY price DESC LIMIT 10";
         mysqli_query($conn, $query) or die('Error querying database.');

	$result = mysqli_query($conn, $query);
#	$row = mysqli_fetch_array($result);

	
        while ($row = mysqli_fetch_array($result)) {
 	echo $row['title'] . '   ' . $row['country'] . '   ' . $row['points'] . '   ' . $row['price'] .'<br />';
	}	
        mysqli_close($conn);
      ?>

      <form>
         <input type="text" name="search" placeholder="Search">
         <button type="submit" name="submit-search"></button>
      </form>

      <div class="article-container">
         <?php
            $sql = "SELECT * FROM WINES";
	    $result = mysqli_query($conn, $query);
            $queryResults = mysqli_num_rows($result);

            if($queryResults > 0) {
               while ($row = mysqli_fetch_assoc()) {
                  echo "<div>
                     <h3>".$row['title']."</h3>
                     <p>".$row['country']."</p>
                     <p>".$row['points']."</p>
                     <p>".$row['price']."</p>
                  </div>";
               }
            }
        ?> 

   </body>
</html>
