<?php
//error_reporting(-1);
//ini_set('display_errors', 1);
require('recaptcha/src/autoload.php');
$siteKey = '6Le-0h0TAAAAAMBEJikzNagQH4kLHF9xMuzaIAfL';
$secret = '6Le-0h0TAAAAAID82fxX_0hmGHoP-X-utZOtmtiu';
$lang = 'en';

if (isset($_POST['g-recaptcha-response']))
    var_export($_POST);
// If the form submission includes the "g-captcha-response" field
// Create an instance of the service using your secret
    $recaptcha = new \ReCaptcha\ReCaptcha($secret);

// Make the call to verify the response and also pass the user's IP address
    $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);

    if ($resp->isSuccess()) {
        header("location: index.php");
    } else {
//        foreach ($resp->getErrorCodes() as $code) {
//            echo '<tt>', $code, '</tt> ';
//        }
    }
    ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>PenGui Login</title>

    <!-- Bootstrap Core CSS -->
    <link href="https://blackrockdigital.github.io/startbootstrap-sb-admin/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="https://blackrockdigital.github.io/startbootstrap-sb-admin/css/sb-admin.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="https://blackrockdigital.github.io/startbootstrap-sb-admin/font-awesome/css/font-awesome.min.css"
          rel="stylesheet" type="text/css">
</head>

<body>
<div class="container-fluid">
    <div id="wrapper">
        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="login.php">PenGui</a>
            </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Please Sign In</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" method="post">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="E-mail" name="email" type="email"
                                           autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" name="password" type="password"
                                           value="">
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input name="remember" type="checkbox" value="Remember Me">Remember Me
                                    </label>
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <div class="g-recaptcha" data-sitekey="<?php echo $siteKey; ?>"></div>
                                <script type="text/javascript"
                                        src="https://www.google.com/recaptcha/api.js?hl=<?php echo $lang; ?>">
                                </script>
                                <button class="btn btn-lg btn-success btn-block" type="submit">Login</button>
                                <a href="register.php" class="btn btn-lg btn-success btn-block"
                                   type="submit">Register</a>
                            </fieldset>
                        </form>
                        <div class="g-recaptcha" data-sitekey="6Le-0h0TAAAAAMBEJikzNagQH4kLHF9xMuzaIAfL"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- jQuery -->
    <script src="https://blackrockdigital.github.io/startbootstrap-sb-admin/js/jquery.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="https://blackrockdigital.github.io/startbootstrap-sb-admin/js/bootstrap.min.js"></script>
</body>
<script src='https://www.google.com/recaptcha/api.js'></script>
</html>
<?php
require('utility.php');
require('sessionManagement.php');

$emailAuthentication = $_POST['email'];
$passwordAuthentication = $_POST['password'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($emailAuthentication)) {
        Utility::alert(htmlentities("Email is required", ENT_QUOTES));
        exit();
    }
    if (empty($passwordAuthentication)) {
        Utility::alert(htmlentities("Password is required", ENT_QUOTES));
        exit();
    }

    $stmt = Utility::databaseConnection()->prepare("SELECT Email, Password FROM personal_details WHERE Email = ?");
    $stmt->bind_param("s", $emailAuthentication);
    $stmt->execute();
    $stmt->bind_result($dbEmail, $dbPassword);
    $stmt->fetch();
    if ($dbEmail == $emailAuthentication) {
        if (password_verify($passwordAuthentication, $dbPassword)) { //Timing attack safe string comparison hash_equals(Known_Hash, User_Supplied)
            Session::manageSession($emailAuthentication);
        } else {
            Utility::alert(htmlentities("Email or Password is incorrect. Please try again.,", ENT_QUOTES));
            exit();
        }
    } else {
        Utility::alert(htmlentities("Email or Password is incorrect. Please try again.", ENT_QUOTES));
        exit();
    }
    $stmt->close();
}//end of post method
?>