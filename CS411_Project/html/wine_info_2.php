<html>
   <head>
      <title>Connect to MariaDB Server</title>
   </head>

   <body>
        Query statement: SELECT title, country, points, price FROM WINES ORDER BY price DESC LIMIT 10. <br /> <br /> <br />
      <?php
        $dbhost = '127.0.0.1';
        $dbuser = 'pdey3';
        $dbpass = 'cs411';
        $dbport = 3036;
        $db = 'wine_snob';
        $conn = mysqli_connect($dbhost,$dbuser,$dbpass,$db);
        if(! $conn){
           die('Could not connect: ' . mysql_error());
        }
     
        #include dbh.php;

	  $query = "SELECT title, country, points, price FROM WINES where country = 'India'";
//        $query = "SELECT title, country, points, price FROM WINES ORDER BY price DESC LIMIT 10";
	mysqli_query($conn, $query) or die('Error querying database.');
	
        $result = mysqli_query($conn, $query);
#       $row = mysqli_fetch_array($result);

        echo "<table border=1> 
          <tr>
          <th>Title</th>
          <th>Country</th>     
          <th>Points</th>       
          <th>Price</th>
         </tr>";
        
	while ($row = mysqli_fetch_array($result)) {
        echo "<tr>";
        echo "<td>" . $row['title'] . "</td>";
        echo "<td>" . $row['country'] . "</td>";
        echo "<td>" . $row['points'] . "</td>";
        echo "<td>" . $row['price'] . "</td>";
        echo "</tr>";
        #echo "<br />";
        }       
        echo "</table>";  

         mysqli_close($conn);
      ?>
      <a href="http://sp19-cs411-48.cs.illinois.edu/register.php">Click here to create an account</a>
      <br/>
      <a href="http://sp19-cs411-48.cs.illinois.edu/login.php">Click here to login to your account</a>

      <!-- Create the search bar, search bar functionality lives in search.php-->
      <form action="search.php" method="POST">
         <input type="text" name="search" placeholder="Search">
         <button type="submit" name="submit-search">Search</button>
      </form>

   </body>
</html>
