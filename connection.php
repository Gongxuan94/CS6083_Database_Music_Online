<?php
	
	// Create connection
    $conn = new mysqli("127.0.0.1:3306","root","1qaz2wsx","MusicOnline");
    // Check connection
  	if ($conn->connect_error) {
      	die("Connection failed: " . $conn->connect_error);
      	exit;
  	}

?>

