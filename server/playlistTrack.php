<?php
    
    $infostring = json_decode($_POST["infostring"],true);
    $trackID = $infostring['trackID'];
    $username = $infostring['username'];
    $playID = $infostring['playID'];
    
    // Create connection
    $conn = new mysqli("127.0.0.1:3306","root","1qaz2wsx","MusicOnline");
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
        exit;
    }

    $query = "SELECT * FROM Playlist_Track WHERE playID ='" .$playID ."'";
    $result = $conn->query($query);
    $i = 1;
    if ($result -> num_rows > 0) {
        while ($row = $result -> fetch_assoc()) {
            $i++;
        }
    }

    $rate = "INSERT INTO Playlist_Track VALUES ('". $playID ."','". $trackID ."','". $i ."')";
    $insert = $conn->query($rate);
    if ($insert === TRUE) {
        echo "1";
    } else {
        echo "Error: ". $conn->error;
        exit;
    }

?>

