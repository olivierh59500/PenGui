<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>PenGui Register</title>

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
                <a class="navbar-brand" href="register.php">PenGui</a>
            </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="Register-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Register</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" method="post">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="First Name" name="first_Name" type="text"
                                           autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Last Name" name="last_Name" type="text"
                                           value="">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Email" name="email" type="email"
                                           value="">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" name="password" type="password"
                                           value="">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Confirm Password" name="confirm_Password"
                                           type="password"
                                           value="">
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <button class="btn btn-lg btn-success btn-block" type="submit">Register</button>
                                <a href="login.php" class="btn btn-lg btn-success btn-block" type="submit">Login</a>
                            </fieldset>
                        </form>
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
<?php
require('utility.php');

$firstName = $_POST["first_Name"];
$lastName = $_POST["last_Name"];
$email = $_POST["email"];
$password = $_POST["password"];
$confirmPassword = $_POST["confirm_Password"];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($firstName)) {
        Utility::alert("Name is required.");
        //$errorName = "Name is required.";
        exit();
    } else {
        $checkFirstName = Utility::validateInput($firstName);
        if (!preg_match("/^[a-zA-Z ]*$/", $checkFirstName)) {
            Utility::alert(("Only letters and spaces allowed for First Name: $checkFirstName"));
            exit();
        }
    }
    if (empty($lastName)) {
        Utility::alert("Last Name is required");
        exit();
    } else {
        $checkLastName = Utility::validateInput($lastName);
        if (!preg_match("/^[a-zA-Z ]*$/", $checkLastName)) {
            Utility::alert("Only letters and spaces allowed for Last Name: $checkLastName");
            exit();
        }
    }
    if (empty($email)) {
        Utility::alert("Email is required");
        exit();
    } else {
        $checkEmail = Utility::validateInput($email);
        if (!filter_var($checkEmail, FILTER_VALIDATE_EMAIL)) {
            Utility::alert("Invalid email format" . "<br>");
            exit();
        }
    }
    if (empty($password)) {
        Utility::alert("Password is required");
        exit();
    } else {
        //fix the regex
        if (!preg_match("/^(.*){8,128}$/", $password)) {//!preg_match("/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-£%/(/)_=+]).{8,}$/", $password)) {
            Utility::alert("Password doesn't match requirements: please make sure your password is greater than 8 characters and contains atleast: 1 UPPER CASE 1 lower 1 digit 1 Special Character");
            exit();
        }
    }
    if ($confirmPassword != $password) {
        Utility::alert("Password doesn't match");
        exit();
    }

    Utility::checkForExistingEmail($email);
    $salt = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM); //creating the salt
    $saltArray = array($salt); //adding the salt to the array
    $password = password_hash($password, PASSWORD_BCRYPT, ['cost' => '14', 'salt' => $saltArray{0}]); //cost=14 ==> 0.5 second delay

    $stmt = Utility::databaseConnection()->prepare("INSERT INTO personal_details (First_Name, Last_Name, Email, Password) VALUES (?,?,?,?)");
    $stmt->bind_param("ssss", $firstName, $lastName, $email, $password);
    $stmt->execute();
    $stmt->close();

    Utility::alert(htmlentities("You have successfully registered. Going to login screen..."));
    print_r('<script> window.location.replace("login.php")</script>');
}//end of POST METHOD REQUEST
?>
