<?php
    
    $infostring = json_decode($_POST["infostring"],true);
    $arname = $infostring['arname'];
    $username = $infostring['username'];
    
    // Create connection
    $conn = new mysqli("127.0.0.1:3306","root","1qaz2wsx","MusicOnline");
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
        exit;
    }
    
    $arinfo = "SELECT artistID FROM Artist WHERE arname ='".$arname."'";
    $arresult = $conn->query($arinfo);
    if ($arresult->num_rows > 0) {
        while ($row = $arresult->fetch_assoc()) {
            $aid = $row['artistID'];
        }
    }
    
    date_default_timezone_set('America/New_York');
    $date = new DateTime();
    $datetime = $date->format('Y-m-d H:i:s');

    $like = "INSERT INTO Like_Artist VALUES ('". $username ."','". $aid ."','". $datetime ."')";
    $insert = $conn->query($like);
    if ($insert === TRUE) {
        echo "1";
    } else {
        echo "Error: ". $conn->error;
        exit;
    }

?>

