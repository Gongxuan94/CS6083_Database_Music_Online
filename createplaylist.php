<?php
session_start();
include_once 'connection.php';

//check if form is submitted
if (isset($_POST['create'])) {
    $ttitle = mysqli_real_escape_string($conn, $_POST['ttitle']);
    $visibility = mysqli_real_escape_string($conn, $_POST['visibility']);
 

    date_default_timezone_set('America/New_York');
    $date = new DateTime();
    $datetime = $date->format('Y-m-d H:i:s');
    $i = 1;
    // count the number
    $query = "SELECT * FROM Playlist";
    $result = $conn -> query($query);
    if ($result -> num_rows > 0) {
        while ($row = $result -> fetch_assoc()) {
           $i++;
        }
    }
   
        if(mysqli_query($conn, "INSERT INTO Playlist VALUES('" . $i . "','" . $ttitle . "','" . $datetime ."','". $visibility ."','". $_SESSION['valid_user'] ."')")) {
            $successmsg = "Successfully Created!";
            header("Location: homepage.php");
        } else {
            $errormsg = "Error...Please try again later!";
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
                <li><a href="homepage.php">Home</a></li>
                <li><a href="otherusers.php">Other Users</a></li>
                <li><a href ="favoriteartists.php">Favorite Artists</a></li>
                <li class="active"><a href ="playlists.php">Playlists</a></li>
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

<div class="container" style="margin-top:5%">
    <div class="row">
        <div class="col-md-4 col-md-offset-4 well">
            <form role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="createform">
                <fieldset>
                    <legend>Create Your Playlist</legend>

                    <div class="form-group">
                        <label for="name">Title</label>
                        <input type="text" name="ttitle" placeholder="Title" required value="<?php echo $ttitle; ?>" class="form-control" />
               
                    </div>

                    <div class="form-group">
                        <label for="name">Visibility</label>
                        <select name="visibility" placeholder="Visibility" class="form-control" >
                            <option value="private">Private</option>>
                            <option value="public" default>Public</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <input type="submit" name="create" value="Create" class="btn btn-success" />
                        <button type="button" name="cancel" value="Cancel" class="btn btn-danger" onclick="window.location.href='homepage.php';">Cancel</button>
                    </div>
                </fieldset>
            </form>
            <span class="text-success"><?php if (isset($successmsg)) { echo $successmsg; } ?></span>
            <span class="text-danger"><?php if (isset($errormsg)) { echo $errormsg; } ?></span>
        </div>
    </div>


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
            <script src="js/jquery.js"></script>


</body>
</html>