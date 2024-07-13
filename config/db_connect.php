<?php 

	// connect to database
	$conn = mysqli_connect('localhost', 'Jano', 'maniwatan', 'mrs_db'); 

	// check the connection
	if (!$conn) {
		echo "Connection error: " . mysqli_connect_error();
	}

?>