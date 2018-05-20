<?php
session_start();

if(isset($_SESSION['admin'])) {
    header("Location: homepage.php");
}

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
<html>
<head>
    <title>User Registration</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" />    
    <script src="js/jquery-3.2.1.js"></script>
    <script src="js/bootstrap.min.js"></script>
</head>
<?php
if (isset($_SESSION['valid_user'])) {
        $url = "http://localhost:8888/Music/homepage.php";
        echo 'You are signed in as: ' .$_SESSION['valid_user']. '<br />';
        echo "<meta http-equiv=\"refresh\" content=\"0.5;url=$url\">"; 
        exit;
    }
?>
<body>

<nav class="navbar navbar-default" role="navigation">
    <div class="container-fluid">
        <!-- add header -->
        <div class="navbar-header">
            <a class="navbar-brand" href="homepage.php">Home Page</a></li>
        </div>
        <!-- menu items -->
        <div class="collapse navbar-collapse" id="navbar1">
            <ul class="nav navbar-nav navbar-right">
                <li class="active"><a href="signin.php">Sign In</a></li>
                <li><a href="signup.php">Sign Up</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4 well">
            <form role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="signinform">
                <fieldset>
                    <legend>Sign In</legend>

                    <div class="form-group">
                        <label for="name">Username</label>
                        <input type="text" name="uname" placeholder="Username" required value="<?php if($error) echo $uname; ?>" class="form-control" />
                        <span class="text-danger"><?php if (isset($uname_error)) echo $uname_error; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="name">Password</label>
                        <input type="password" name="password" placeholder="Password" required class="form-control" />
                        <span class="text-danger"><?php if (isset($password_error)) echo $password_error; ?></span>
                    </div>

                    <div class="form-group">
                        <input type="submit" name="signin" value="Sign In" class="btn btn-primary" />
                    </div>
                </fieldset>
            </form>
            <span class="text-success"><?php if (isset($successmsg)) { echo $successmsg; } ?></span>
            <span class="text-danger"><?php if (isset($errormsg)) { echo $errormsg; } ?></span>
        </div>
    </div>
</div>
</body>
</html>