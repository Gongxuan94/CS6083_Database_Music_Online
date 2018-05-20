<?php
session_start();
include_once 'connection.php';

require_once 'vendor/autoload.php';

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
            <script src="js/jquery-3.2.1.js"></script>

            <script type="text/javascript">
           

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
            <div class="col-md-12 col-md-offset-0">

                <!-- Search Result -->
                <?php
                	echo "<div class='row'>
          				<div class='center-block' style='width:100%;'>
          				<div class='page-header'>
          				<h2 align ='center'>Artist List</h2><br/>
          				</div>";
                	$searchkeyword = $_POST['searchkeyword'];
                    if ($searchkeyword=="") {
                        exit;
                    }
                	// Search Artist
    				$artistquery = "SELECT * FROM Artist WHERE arname LIKE'%".$searchkeyword. "%' OR ardesc LIKE '%".$searchkeyword."%'";
    				$artistresult = $conn -> query($artistquery);
    				if ($artistresult -> num_rows > 0) {
    					echo "<table class= 'table table-striped table-hover'><tr><th>Name</th><th>Genre</th></tr>";
        				while ($row = $artistresult -> fetch_assoc()) {
                			echo "<tr>";
      						echo "<td><form action='artist.php' method='POST'><input name='arname' type='submit' style='outline:none;border:none' class= 'btn' value='" . $row['arname'] . "'></form></td>";
      						echo "<td>" . $row['ardesc'] . "</td>";
      						echo "</tr>";
           				}
           				echo "</table><br/>";       			
    				}
                    echo "</div></div>";

                    echo "<div class='row'>
                        <div class='center-block' style='width:100%;margin-top:2%;'>
                        <div class='page-header'>
                        <h2 align = 'center'>Track List</h2><br/>
                        </div>";
                    // Search Track
                    $query = "SELECT * FROM Track NATURAL JOIN Album WHERE ttitle LIKE'%".$searchkeyword. "%' OR arname LIKE '%".$searchkeyword."%'";
                    $result = $conn -> query($query);
                    if ($result -> num_rows > 0) {
                        echo "<table class= 'table table-striped table-hover'><tr><th>Title</th><th>Duration</th><th>Artist</th><th>Album</th></tr>";
                        while ($row = $result -> fetch_assoc()) {
                            echo "<tr>";
                            echo "<td style='width:50%;'><form action='track.php' method='POST'><input type='hidden' name='trackID' value='".$row['trackID']."'/><input name='ttitle' type='submit' style='outline:none;border:none;' class= 'btn' value='" . $row['ttitle'] . "'></form></td>";
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