<?php
session_start();
include_once 'connection.php';

$nameerror = true;
$passerror = true;
//check if form is submitted
if (isset($_POST['signin'])) {
    $uname = mysqli_real_escape_string($conn, $_POST['uname']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // verify the username and password
    $userquery = "SELECT * FROM User";
    $userresult = $conn -> query($userquery);
    if ($userresult -> num_rows > 0) {
        while ($row = $userresult -> fetch_assoc()) {
            if ($row['username'] == $uname) {
                $nameerror = false;
                if ($row['password'] == sha1($password)) {
                    $passerror = false;            
                    $_SESSION['valid_user'] = $uname;
                }
            }
        }
    }
    if ($nameerror) {
        $uname_error = "This username has not been registered";
    }
    if ($passerror) {
        $password_error = "Wrong Password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->


    <title>Music Online</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" />

     <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
            <!-- Include all compiled plugins (below), or include individual files as needed -->
            <script src="js/bootstrap.min.js"></script>
            <script type ="text/javascript" src="jquery-3.2.1.js"></script>

            <script type="text/javascript">
                function likeArtist() {
                    var arname = <?php echo json_encode($_POST['arname']);?>;
                    var username = <?php echo json_encode($_SESSION['valid_user']);?>;
                   
                    var infojson = {"arname":arname,"username":username};
                    var infostring = JSON.stringify(infojson);
                    
                    $.ajax({  
                        type: "post",
                        url: "server/likeArtist.php",
                        data: {"infostring" :infostring},
                        success: function(data){
                            if (data == 1) {
                            }
                        }                   
                    });
                }

                function rateTrack() {
                    var trackID = <?php echo json_encode($_POST['trackID']);?>;
                    var username = <?php echo json_encode($_SESSION['valid_user']);?>;
                    var rselect = document.getElementById("score");
                    var rindex = rselect.selectedIndex;
                    var score = rselect.options[rindex].value; // score
                   
                    var infojson = {"trackID":trackID,"username":username,"score":score};
                    var infostring = JSON.stringify(infojson);
                    
                    $.ajax({  
                        type: "post",
                        url: "server/rateTrack.php",
                        data: {"infostring" :infostring},
                        success: function(data){
                            if (data == 1) {
                                document.getElementById("mark").style.display="block";
                            }
                        }                   
                    });
                }

                function playlistTrack() {
                    var trackID = <?php echo json_encode($_POST['trackID']);?>;
                    var username = <?php echo json_encode($_SESSION['valid_user']);?>;
                    var rselect = document.getElementById("playlists");
                    var rindex = rselect.selectedIndex;
                    var playID = rselect.options[rindex].value; // playID
                    var infojson = {"trackID":trackID,"username":username,"playID":playID};
                    var infostring = JSON.stringify(infojson);
                    
                    $.ajax({  
                        type: "post",
                        url: "server/playlistTrack.php",
                        data: {"infostring" :infostring},
                        success: function(data){
                            if (data == 1) {
                                document.getElementById("mark2").style.display="block";
                            }
                        }                   
                    });
                }

            </script>
    <style>

        .title{
            color: #FFFFFF;
        }

        .navbar-brand{
            font-size: 1.8em;
        }


        #topRow h1 {
            font-size: 300%;

        }

        .center{
            text-align: center;
        }

        .title{
            margin-top: 100px;
            font-size: 300%;
        }

        #footer {
            background-color: #B0D1FB;
        }

        .marginBottom{
            margin-bottom: 30px;
        }

        .tagcontainer{
            height: 350px;
            width: 1200px;
            background:url("images/hometagbackground.jpg") center;
            color: white;
         
    </style>
</head>
<body>
<div class ="navbar-default navbar-fixed-top">
    <div class = "container">
        <div class ="navbar-header">
            <button class ="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            </button>
            <a class="navbar-brand">Music Online</a>
        </div>

        <div class="collapse navbar-collapse">

            <ul class ="nav navbar-nav">
                <li class="active"><a href="homepage.php">Home</a></li>
                <li><a href="otherusers.php">Other Users</a></li>
                <li><a href ="favoriteartists.php">Favorite Artists</a></li>
                <li><a href ="playlists.php">Playlists</a></li>
            </ul>

            <form style="<?php if (isset($_SESSION['valid_user'])) echo "display:none"; ?>" class="navbar-form navbar-right" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

                    <div class="form-group">
                        <input type="text" name="uname" placeholder="Username" required value="<?php if($error) echo $uname; ?>" class="form-control" />
                        <span class="text-danger"><?php if (isset($uname_error)) echo $uname_error; ?></span>
                    </div>

                    <div class="form-group">
                        <input type="password" name="password" placeholder="Password" required class="form-control" />
                        <span class="text-danger"><?php if (isset($password_error)) echo $password_error; ?></span>
                    </div>
                    <input type="submit" class="btn btn-success" name="signin" value="Sign In"/>

                <button type="button" class ="btn btn-danger" onclick="window.location.href='signup.php'">Sign Up</button>

            </form>
            <p class="navbar-form navbar-right" style="<?php if (!isset($_SESSION['valid_user'])) echo "display:none"; ?>">Welcome! <a target="_blank" href="userinfo.php"><?php echo $_SESSION['valid_user']; ?></a><a style="<?php if (!isset($_SESSION['valid_user'])) echo "display:none"; ?>"href="logout.php">
            Log Out</a></p>
        </div>
    </div>
</div>

<!-- Header -->
<header id="top" class="searchheader">
    <div class="text-vertical-center" >
<!--        <h1>Explore The Ideas</h1>-->
        <br>
        <div class="container" style="margin-top: 5%;">
            <div class="col-md-6 col-md-offset-3">

                <!-- Search Result -->
                <?php
                    echo "<div class='row'>
                        <div class='center-block' style='width:100%;'>
                        <div class='page-header'>
                        <h2 align ='center'>Track Information</h2><br/>
                        </div>";
                    $trackID = $_POST['trackID'];
                    if ($trackID=="") {
                        exit;
                    }
                    // Search Track
                    $query = "SELECT * FROM Track NATURAL JOIN Album WHERE trackID ='".$trackID."'";
                    $result = $conn -> query($query);
                    if ($result -> num_rows > 0) {
                        echo "<table class= 'table table-striped table-hover'><tr><th>Title</th><th>Duration</th><th>Artist</th><th>Album</th></tr>";
                        while ($row = $result -> fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['ttitle'] . "</td>";
                            $totaltime = (int)$row['duration'];
                            $minute = (int)($totaltime / 1000 / 60);
                            if ($minute < 10) {
                                $minute = "0".$minute;
                            }
                            $second = (int)($totaltime / 1000 % 60);
                            if ($second < 10) {
                                $second = "0".$second;
                            }
                            $milis = (int)($totaltime % 1000);
                            while (strlen((string)$milis) < 3) {
                                $milis = $milis ."0";
                            }
                            echo "<td>" . $minute. ":". $second.".". $milis . "</td>";
                            echo "<td>" . $row['arname'] . "</td>";
                            echo "<td>" . $row['altitle'] . "</td>";
                            echo "</tr>";
                        }
                        echo "</table><br/>";                   
                    }

                    $myquery = "SELECT * FROM Rate_Song WHERE username='". $_SESSION['valid_user']. "' AND trackID='".$trackID."'";
                    $myresult = $conn -> query($myquery);
                    if ($myresult -> num_rows > 0) {
                        while ($row = $myresult -> fetch_assoc()) {
                            echo "<h3> Your rate is " .$row['score']. "/5 Stars</h3>";
                        }
                    } else {
                        echo '<p>You can rate this track: </p>
                        <select id="score" class="form-control" >
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5" default>5</option>
                            </select><br /><button type="button" class ="btn btn-success" onclick="rateTrack();" >Submit</button><br/>
                            <span id="mark" class="text-success" style="display:none;">Your rate was submitted successfully!</span>';
                    }

                    echo '<p>You can add this track to your playlist: </p>
                          <select id="playlists" class="form-control" >';
                    $lcount = 0;
                    $listquery = "SELECT * FROM Playlist WHERE username='". $_SESSION['valid_user']. "'";
                    $listresult = $conn -> query($listquery);
                    if ($listresult -> num_rows > 0) {
                        while ($row = $listresult -> fetch_assoc()) {
                            echo "<option value='".$row['playID']."'>".$row['ptitle']."</option>";
                        }
                    }
                    echo '</select><br /><button type="button" class ="btn btn-success" onclick="playlistTrack();" >Submit</button><br/>
                            <span id="mark2" class="text-success" style="display:none;">The track was added to your playlist successfully!</span>';
                    echo "</div></div>";

                ?>
                <!-- End of Search Result -->

            </div>
        </div>
    </div>
</header>



<!-- Footer -->
<footer>
    <div class="container">
        <div class="row">
            <div class="col-lg-10 col-lg-offset-1 text-center">
                <h4><strong>Powered by</strong>
                    <iframe src="http://open.spotify.com/track/2TpxZ7JUBn3uw46aR7qd6V" width="300" height="380" frameborder="0" allowtransparency="true"></iframe>
                </h4>
                <p>Xuan Gong
                    <br>Jingwen Zhang</p>
            <hr class="small">
                <p class="text-muted">Copyright &copy; Music Online</a></p>
            </div>
        </div>
    </div>
    <a id="to-top" href="#top" class="btn btn-dark btn-lg"><i class="fa fa-chevron-up fa-fw fa-1x"></i></a>
</footer>
</body>
</html>