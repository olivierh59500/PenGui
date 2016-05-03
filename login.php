<?php
error_reporting(-1);
ini_set('display_errors', 1);
require('utility.php');
require('sessionManagement.php');
require('recaptcha/src/autoload.php');

$siteKey = '6Le-0h0TAAAAAMBEJikzNagQH4kLHF9xMuzaIAfL';
$secret = '6Le-0h0TAAAAAID82fxX_0hmGHoP-X-utZOtmtiu';
$lang = 'en';
$emailAuthentication = isset($_POST['email']) ? $_POST['email'] : null;
$passwordAuthentication = isset($_POST['password']) ? $_POST['password'] : null;
$error = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "im on the post";
    if (empty($emailAuthentication)) {
        $error[] = $emailError = "Email is required";
    }
    if (empty($passwordAuthentication)) {
        $error[] = $passwordAuthenticationError = "Password is required";
    }
    $stmt = Utility::databaseConnection()->prepare("SELECT Email, Password FROM personal_details WHERE Email = ?");
    $stmt->bind_param("s", $emailAuthentication);
    $stmt->execute();
    $stmt->bind_result($dbEmail, $dbPassword);
    $stmt->fetch();
    if ($dbEmail == $emailAuthentication) {
        if (password_verify($passwordAuthentication, $dbPassword)) { //This function is safe against timing attacks.
            Session::manageSession($emailAuthentication);
        } else {
            $error[] = $loginError = "Email or Password is incorrect \n or CAPTCHA is not set. Please try again";
        }
    } else {
        $error[] = $loginError = "Email or Password is incorrect \n or CAPTCHA is not set. Please try again";
    }
    $stmt->close();
}//end of post

if (isset($_POST['g-recaptcha-response']) && empty($error)) {
    var_export($_POST);
    $recaptcha = new \ReCaptcha\ReCaptcha($secret);
    $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
    if ($resp->isSuccess()) {
        header("location: index.php");
    }
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
                                           value="<?php echo htmlspecialchars(isset($_POST['email']) ? $_POST['email'] : null); ?>" autofocus required>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" name="password" type="password"
                                           value="" required>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input name="remember" type="checkbox" value="Remember Me">Remember Me
                                    </label>
                                </div>
                                <div class="g-recaptcha" data-sitekey="<?php echo htmlspecialchars($siteKey); ?>"></div>
                                <script type="text/javascript"
                                        src="https://www.google.com/recaptcha/api.js?hl=<?php echo htmlspecialchars($lang); ?>">
                                </script>

                                <div class="list-group">
                                    <?php foreach($error as $err) {
                                            echo "<a href=\"#\" class=\"list-group-item list-group-item-danger\">$err</a>";
                                        }
                                    ?>
                                </div>
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