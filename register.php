<?php
//error_reporting(-1); ini_set('display_errors', 1);
require('utility.php');
require('new_task.php');

$firstName = isset($_POST["first_Name"]) ? $_POST["first_Name"] : null; //php ternary return value
$lastName = isset($_POST["last_Name"]) ? $_POST["last_Name"] : null;
$email = isset($_POST["email"]) ? $_POST["email"] : null;
$password = isset($_POST["password"]) ? $_POST["password"] : null;
$confirmPassword = isset($_POST["confirm_Password"]) ? $_POST["confirm_Password"] : null;
$error = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($firstName)) {
        $errorName = "Name is required.";
        $error[] = $errorName;
    } else {
        $checkFirstName = Utility::validateInput($firstName);
        if (!preg_match("/^[a-zA-Z ]*$/", $checkFirstName)) {
            $nameCheckError = "Only letters and spaces allowed for first name";
            $error[] = $nameCheckError;
        }
    }
    if (empty($lastName)) {
        $lastNameError = "Last Name is required";
        $error[] = $lastNameError;
    } else {
        $checkLastName = Utility::validateInput($lastName);
        if (!preg_match("/^[a-zA-Z ]*$/", $checkLastName)) {
            $checkLastNameError = "Only letters and spaces allowed for last name";
            $error[] = $checkLastNameError;
        }
    }
    if (empty($email)) {
        $emailError = "Email is required";
        $error[] = $emailError;
    } else {
        $checkEmail = Utility::validateInput($email);
        if (!filter_var($checkEmail, FILTER_VALIDATE_EMAIL)) {
            $checkEmailError = "Invalid email format";
            $error[] = $checkEmailError;
        } else {
            $checkExistingEmail = Utility::checkForExistingEmail($email);
        }
    }
    if (empty($password)) {
        $passwordError = "Password is required";
        $error[] = $passwordError;
    } else if (strlen($password) < 8) {
        $passwordError = "Please make sure your password is greater than 8 characters";
        $error[] = $passwordError;
    }
    if ($confirmPassword !== $password) {
        $passwordMatchError = "Password doesn't match";
        $error[] = $passwordMatchError;
    }
    if (empty($error)) {
        $_SESSION['tmpEmail'] = $email;
        registerUser($firstName, $lastName, $email, $password);
//        Utility::alert(htmlspecialchars("Successfully registered. Going to login screen..."));
//        sleep(3);
        //echo ('<script>window.location.replace(htmlspecialchars("login.php"))</script>');
        header("Location: emailverification.php");
    }

}//end of POST METHOD REQUEST

function sendEmail($to, $emailVerificationCode) {
    $subject = "Pengui Account Verification Code";
    $message = '
    Thank you for signing up for pengui.uk' . "\r\n" .
    
    'Your account has been created however before being able to login you will need to verify your Email address by the code provided below:' . "\r\n" .
    
    'Email Verification Code: ' . "$emailVerificationCode" . "\r\n" .

    'Copy and paste the code in the verification page' . "\r\n" .
    
    'Or go to here and enter the code: https://pengui.uk/emailverification.php' . "\r\n" .
    
    'Happy Hunting! :D';

    $headers = 'From: webmaster@pengui.com' . "\r\n" .
        'Reply-To: webmaster@pengui.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    $task = new Task();
    $task->newTask(mail($to, $subject, $message, $headers));
    //mail($to, $subject, $message, $headers);
}

function registerUser($firstName, $lastName, $email, $password)
{
    $activated = 0;
    $salt = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM); //creating the salt
    $password = password_hash($password, PASSWORD_BCRYPT, ['cost' => '14', 'salt' => $salt]); //cost=14 ==> ~0.5-1 sec second delay
    $emailSalt = mcrypt_create_iv(512, MCRYPT_DEV_URANDOM);
    $emailVerificationHash = hash("sha256", $emailSalt);
    $stmt = Utility::databaseConnection()->prepare("INSERT INTO personal_details (First_Name, Last_Name, Email, Password, Activated, Email_Hash_Verification) VALUES (?,?,?,?,?,?)");
    $stmt->bind_param("ssssis", $firstName, $lastName, $email, $password, $activated, $emailVerificationHash);
    $stmt->execute();
    $stmt->close();

    sendEmail($email, $emailVerificationHash);
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
                        <h3 class="panel-title">Register</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" method="post">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="First Name" name="first_Name" type="text"
                                           value="<?php echo htmlspecialchars(isset($_POST["first_Name"]) ? $_POST["first_Name"] : null);?>" autofocus required>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Last Name" name="last_Name" type="text"
                                           value="<?php echo htmlspecialchars(isset($_POST["last_Name"]) ? $_POST["last_Name"] : null);?>" required>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Email" name="email" type="email"
                                           value="<?php echo htmlspecialchars(isset($_POST["email"]) ? $_POST["email"] : null);?>" required>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" name="password" type="password"
                                           value="" required>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Confirm Password" name="confirm_Password"
                                           type="password" value="" required>
                                </div>
                                <div class="list-group">
                                    <?php if(isset($error)) {
                                        foreach($error as $err) {
                                            echo "<a href=\"#\" class=\"list-group-item list-group-item-danger\">$err</a>";
                                        }
                                    }
                                    ?>
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
        </div>
        <!-- jQuery -->
        <script src="https://blackrockdigital.github.io/startbootstrap-sb-admin/js/jquery.js"></script>
        <!-- Bootstrap Core JavaScript -->
        <script src="https://blackrockdigital.github.io/startbootstrap-sb-admin/js/bootstrap.min.js"></script>
</body>
</html>