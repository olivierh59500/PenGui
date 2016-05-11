<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 10/05/16
 * Time: 20:22
 */

require('utility.php');
error_reporting(-1);
ini_set('display_errors', 1);

$userSuppliedEmailHashVerification = isset($_POST["emailVerificationHash"]) ? $_POST["emailVerificationHash"] : null; //php ternary return value
$error = array();
$getDbEmailHashOrError = getEmailHashVerification($userSuppliedEmailHashVerification);

//User supplies the verification code that was emailed to them on recieiving the code they will come to this page and
//enter the code. The verification code gets checked against the DB and if there is a match then the users accounts get
//activated = 1 & disable = 0
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($userSuppliedEmailHashVerification) & !isset($userSuppliedEmailHashVerification)) {
        $error[] = $errorEmailVerification = "Email Verification code is required.";
    } else if ($getDbEmailHashOrError !== $userSuppliedEmailHashVerification) {
        $error[] = $errorEmailVerification = "Email Verification Code is invalid. Try again.";
    } else {
        getEmailHashVerification($userSuppliedEmailHashVerification);
    }
}//end of POST METHOD REQUEST

function getEmailHashVerification($userSuppliedEmailHashVerification) {
    //$error[] = array();
    $stmt = Utility::databaseConnection()->prepare("SELECT Email, Email_Hash_Verification FROM personal_details WHERE Email_Hash_Verification = ?");
    $stmt->bind_param("s", $userSuppliedEmailHashVerification);
    $stmt->execute();
    $stmt->bind_result($dbEmail, $dbEmailHashVerification);
    while ($stmt->fetch()) {
        if (hash_equals($dbEmailHashVerification, $userSuppliedEmailHashVerification)) {
            activateUser($dbEmail);
        } else {
            $error = "Email Verification Code is invalid. Try again.";
            return $error;
        }
    }
    $stmt->close();
    return $dbEmailHashVerification;
}

function activateUser($email) {
    $activated = 1;
    $stmt = Utility::databaseConnection()->prepare("UPDATE personal_details SET Activated = ? WHERE Email = ?"); //, Email_Hash_Verification = ? WHERE Email = ?");
    $stmt->bind_param("is", $activated, $email);
    $stmt->execute();
    $stmt->close();
    Utility::alert(htmlspecialchars("Account successfully verified. Going to login screen..."));
    echo('<script>window.location.replace("login.php")</script>');
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
    <title>PenGui Email Verification</title>
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
        <!--         Navigation-->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!--             Brand and toggle get grouped for better mobile display-->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="register.php">PenGui</a>
            </div>
    </div>
    <div align="center" class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="Register-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Email Verification Required:</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" method="post">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Enter Verification Code"
                                           name="emailVerificationHash" type="text"
                                           value="" autofocus required>
                                </div>
                                <div class="list-group">
                                    <?php if (isset($error)) {
                                        foreach ($error as $err) {
                                            echo "<a href=\"#\" class=\"list-group-item list-group-item-danger\">$err</a>";
                                        }
                                    }
                                    ?>
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <button class="btn btn-lg btn-success btn-block" type="submit">Activate Account</button>
                            </fieldset>
                        </form>
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
</html>