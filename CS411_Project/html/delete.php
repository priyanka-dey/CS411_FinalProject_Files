<!-- This document handles deleting reviews -->

<?php
	// Connect to DB
	include 'dbh.php';

	// Check connection
	if(!$conn){
		die("Connection failed: ".mysqli_connect_error());
	}
	
	// Get review id passed in here
	$id = $_GET['id'];

	// SQL query to delete a record
	$sql = "DELETE FROM REVIEWS WHERE review_id=".$id;

	// Check for successful deletion, redirects to profile page
	if(mysqli_query($conn,$sql)){
		mysqli_close($conn);
		header('Location: profile.php');
		exit;
	} else{
		echo "Error deleting record";
	}
?>
	
	
