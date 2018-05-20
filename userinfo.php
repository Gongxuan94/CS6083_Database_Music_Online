<?php
session_start();
include_once 'connection.php';

$nameerror = true;
$passerror = true;

//check if form is submitted
if (isset($_POST['save'])) {
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $cpassword = mysqli_real_escape_string($conn, $_POST['cpassword']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);

    $userquery = "SELECT * FROM User WHERE username ='".$_SESSION['valid_user']."'";
    $userresult = $conn -> query($userquery);
    if ($userresult -> num_rows > 0) {
        while ($row = $userresult -> fetch_assoc()) {
            if (strlen($password)==0) {
                $password = $row['password'];
                $cpassword = $row['password'];
            }
            if (strlen($city)==0) {
                $city = $row['ucity'];
            }
            if (strlen($name)==0) {
                $name = $row['uname'];
            }
        }
    }

    // requirement for password
    if(strlen($password)!=0 && strlen($password) < 6) {
        $error = true;
        $password_error = "Password must be minimum of 6 characters";
    }
    // password should be the same
    if($password != $cpassword) {
        $error = true;
        $cpassword_error = "Password and Confirm Password doesn't match";
    }
    //name can contain only alpha characters and space
    if (!preg_match("/^[a-zA-Z ]+$/",$name)) {
        $error = true;
        $name_error = "Name must contain only alphabets and space";
    }

    // Alter
    $userquery = "UPDATE User SET password = '".sha1($password)."', uname='".$name."', ucity = '".$city."' WHERE username ='".$_SESSION['valid_user']."'";
    $userresult = $conn -> query($userquery);
    if ($userresult === TRUE) {
        $successmsg = "Successfully Saved!";
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
            <p class="navbar-form navbar-right" style="<?php if (!isset($_SESSION['valid_user'])) echo "display:none"; ?>">Welcome! <a target="_blank" href="userinfo.php"><?php echo $_SESSION['valid_user']; ?></a></p>
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

                

            </div>
        </div>
    </div>
</header>
<?php 
    echo "<div class='row'>
            <div class='center-block' style='width:70%;'>
            <div class='page-header'>
            <h2 align ='center'>Personal Information</h2><br/>
            </div>";
    if (isset($_SESSION['valid_user'])) {
        $username = $_SESSION['valid_user'];
        $userquery = "SELECT * FROM User WHERE username='".$username."'";
        $userresult = $conn -> query($userquery);
        if ($userresult -> num_rows > 0) {
            while ($row = $userresult -> fetch_assoc()) {
            echo '<form role="form" action="'.$_SERVER['PHP_SELF'].'" method="post" name="signupform">
                <fieldset>
                    <legend>Edit</legend>

                    <div class="form-group">
                        <label for="name">Username</label>
                        <input type="text" name="uname" placeholder="'.$username.'"   class="form-control" readonly="readonly"/>
                    </div>

                    <div class="form-group">
                        <label for="name">Password</label>
                        <input type="password" name="password" placeholder="******"  class="form-control" />
                        <span class="text-danger">';
                        if (isset($password_error)) {
                            echo $password_error; 
                        }
                        echo '</span>
                    </div>

                    <div class="form-group">
                        <label for="name">Confirm Password</label>
                        <input type="password" name="cpassword" placeholder="******"  class="form-control" />
                        <span class="text-danger">';
                        if (isset($cpassword_error)) {
                            echo $cpassword_error;
                        }
                        echo '</span>
                    </div>

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" placeholder="'.$row['uname'].'"  value="';
                        if($error) echo $name; 
                        echo '" class="form-control" />
                        <span class="text-danger">'; 
                        if (isset($name_error)) echo $name_error;
                            echo '</span>
                    </div>

                    <div class="form-group">
                        <label for="name">Email</label>
                        <input type="text" name="email" placeholder="'.$row['uemail'].'" readonly="readonly" class="form-control" />
                    </div>

                    <div class="form-group">
                        <label for="name">City</label>
                        <input type="text" name="city" placeholder="'.$row['ucity'].'" class="form-control" />
                    </div>

                        <input type="submit" name="save" value="Save" class="btn btn-success" />
                    <button type="button" name="cancel" class="btn btn-danger" onclick="window.close();">Cancel</button>
                </fieldset>
            </form>
            <span class="text-success">';
            if (isset($successmsg)) { echo $successmsg; } 
            echo '</span>
            <span class="text-danger">';
            if (isset($errormsg)) { echo $errormsg; } 
            echo '</span>';
            }
            echo "</table><br/>";       
        }
        echo "</div></div>";
    }
?>

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

            <script>

                $(".contentContainer").css("min-height",$(window).height());

                // Scrolls to the selected menu item on the page
                $(function() {
                    $('a[href*=#]:not([href=#],[data-toggle],[data-target],[data-slide])').click(function() {
                        if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') || location.hostname == this.hostname) {
                            var target = $(this.hash);
                            target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                            if (target.length) {
                                $('html,body').animate({
                                    scrollTop: target.offset().top
                                }, 1000);
                                return false;
                            }
                        }
                    });
                });
                //#to-top button appears after scrolling
                var fixed = false;
                $(document).scroll(function() {
                    if ($(this).scrollTop() > 250) {
                        if (!fixed) {
                            fixed = true;
                            // $('#to-top').css({position:'fixed', display:'block'});
                            $('#to-top').show("slow", function() {
                                $('#to-top').css({
                                    position: 'fixed',
                                    display: 'block'
                                });
                            });
                        }
                    } else {
                        if (fixed) {
                            fixed = false;
                            $('#to-top').hide("slow", function() {
                                $('#to-top').css({
                                    display: 'none'
                                });
                            });
                        }
                    }
                });

            </script>



</body>
</html>