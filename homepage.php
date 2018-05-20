<?php
session_start();
include_once 'connection.php';

require_once 'vendor/autoload.php';

$session = new SpotifyWebAPI\Session(
    '9bec6f09d912431e804b02a8afce23ee',
    '5533209a4cc64011803188024cc67f10'
);

$session->requestCredentialsToken();
$accessToken = $session->getAccessToken();

// Store the access token somewhere. In a database for example.
$_SESSION['token'] = $accessToken;

header('Location: http://localhost:8888/Music/homepage.php');

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
    <div class="container" style="margin-top: 5%;">
            <div class="col-lg-10 col-lg-offset-1 text-center">

            <h1>Tracks By Your Favorite Artists</h1>

            <br/>
            <div class="center">
            <?php
                if(isset($_SESSION['valid_user'])) {
                    $username = $_SESSION['valid_user'];
        
                    // Search Tracks
                    $query = "SELECT * FROM Track NATURAL JOIN Artist NATURAL JOIN Like_Artist WHERE  username='".$username."' AND trackID in (SELECT trackID FROM (SELECT trackID, COUNT(*) FROM Play_Track WHERE username='".$username."' GROUP BY trackID HAVING COUNT(*)>0) AS t)";
                    $result = $conn -> query($query);
                    if ($result -> num_rows > 0) {
                        $i = 0;
                        while ($i < 5 && $row = $result -> fetch_assoc()) {
                            // $query2 = "SELECT * FROM Artist NATURAL JOIN Album NATURAL JOIN Track WHERE artistID =".$row['artistID']."AND CAST( RIGHT(aldate,2) AS SIGNED) > RIGHT((YEAR(NOW()) - 2), 2) AND CAST( RIGHT(aldate,2) AS SIGNED) <= RIGHT((YEAR(NOW())), 2)";
                            echo '<div><label>'.$row['arname'].' - '.$row['ttitle'].'</label><br /><audio controls="controls" onplay="begin_Play();" id = "'.$row['trackID'].'">
                                    <source src="music/'.$row['trackID'].'.mp3" type="audio/mpeg" />
                                    Your browser does not support the audio element.
                                </audio></div>';
                            $i++;
                        }                  
                    }
                }                 
    
            ?>
        </div>
        </div>
    </div>
    <div class="text-horizontal-center" >
<!--        <h1>Explore The Ideas</h1>-->
        <br>
        <div class="container" style="margin-top: 5%;">
            <div class="col-md-6 col-md-offset-3">

                <!-- Search Form -->
                <form role="form" action="search.php" method="POST">

                    <!-- Search Field -->
                    <div class="row">

                        <div class="form-group">
                            <div class="input-group">
                                <input class="form-control" type="text" name="searchkeyword" placeholder="Artist, Album, Track..." required/>
                                <span class="input-group-btn">
                                <button class="btn btn-success" type="submit" value="Search"><span class="glyphicon glyphicon-search"></span>
                                    <span style="margin-left:10px;"></span></button>
                                </span>
                            </div>
                        </div>
                    </div>

                </form>

                <!-- End of Search Form -->


            </div>
            
</header>


<!-- Portfolio -->
<section id="portfolio" class="portfolio">
    
    </div>
</div></section>


<!-- Footer -->
<footer>
    <div class="container">
        <div class="row">
            <div class="col-lg-10 col-lg-offset-1 text-center">
                <h4><strong>Powered by</strong>
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





            <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
            <!-- Include all compiled plugins (below), or include individual files as needed -->
            <script src="js/bootstrap.min.js"></script>
            <script src="js/jquery-3.2.1.js"></script>

            <script type="text/javascript">

                function begin_Play(ev){
                    ev = ev || window.event; //event
                    var target = ev.target || ev.srcElement; 
                    var trackID = target.getAttribute('id');
                    var username = <?php echo json_encode($_SESSION['valid_user']);?>;
            
                    var infojson = {"trackID":trackID,"username":username};
                    var infostring = JSON.stringify(infojson);
                    
                    $.ajax({  
                        type: "post",
                        url: "server/playTrack.php",
                        data: {"infostring" :infostring},
                        success: function(data){
                            if (data == 1) {
                               
                            }
                        }                   
                    });
                }

            </script>

</body>
</html>


</body>
</html>