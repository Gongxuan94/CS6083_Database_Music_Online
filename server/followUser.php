<?php
    
    $infostring = json_decode($_POST["infostring"],true);
    $follow = $infostring['follow'];
    $username = $infostring['username'];
    
    // Create connection
    $conn = new mysqli("127.0.0.1:3306","root","1qaz2wsx","MusicOnline");
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
        exit;
    }

    date_default_timezone_set('America/New_York');
    $date = new DateTime();
    $datetime = $date->format('Y-m-d H:i:s');

    $follow = "INSERT INTO Follow_User VALUES ('". $follow ."','". $username ."','". $datetime ."')";
    $insert = $conn->query($follow);
    if ($insert === TRUE) {
        echo "1";
    } else {
        echo "Error: ". $conn->error;
        exit;
    }

?>

