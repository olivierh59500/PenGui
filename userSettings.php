<?php
require('sessionManagement.php');
require('utility.php');

if(!isset($_SESSION['loginUser'])) {
    header("location: login.php");
}

$currentUser = isset($_SESSION['loginUser']) ? $_SESSION['loginUser'] : null;
$firstName = isset($_POST["first_Name"]) ? $_POST["first_Name"] : null; //php ternary return value
$lastName = isset($_POST["last_Name"]) ? $_POST["last_Name"] : null;
$email = isset($_POST["email"]) ? $_POST["email"] : null;
$userPassword = isset($_POST["password"]) ? $_POST["password"] : null;
$confirmPassword = isset($_POST["confirm_Password"]) ? $_POST["confirm_Password"] : null;
$error = array();

$stmt = Utility::databaseConnection()->prepare("SELECT First_Name, Last_Name, Email, Password FROM personal_details WHERE Email = ?");
$stmt->bind_param("s", $currentUser);
$stmt->execute();
$stmt->bind_result($dbFirstName, $dbLastName, $dbEmail, $dbPassword);
while ($stmt->fetch()) {
    if ($userPassword !== $confirmPassword) {
        $error[] = "Password doesn't match";
    } else if (password_verify($userPassword, $dbPassword)){
        $error[] = "password cannot be the same as your old password";
    } else if (empty($error)) {
        //set new password
        $salt = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM); //creating the salt
        $password = password_hash($userPassword, PASSWORD_BCRYPT, ['cost' => '14', 'salt' => $salt]); //cost=14 ==> 0.5 second delay
        var_dump($password);
        $stmt = Utility::databaseConnection()->prepare("UPDATE personal_details SET Password = ? WHERE Email = ?");
        $stmt->bind_param("ss", $password, $email);
        $stmt->execute();
        $stmt->close();
        $success = "Password successfully changed";
    }


}
$stmt->close();


?>
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mojtaba Amiri">

    <title>PenGui Admin</title>

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
                <a class="navbar-brand" href="index.php">PenGui</a>
            </div>
            <!-- Top User Menu Items -->
            <ul class="nav navbar-right top-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo htmlspecialchars($_SESSION['loginUser']) ?><b
                            class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="userSettings.php"><i class="fa fa-fw fa-gear"></i> Settings</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="logout.php"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
                    <li>
                        <a href="index.php"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
                    </li>
                    <li>
                        <a href="extra/nmap.php"> Nmap</a>
                    </li>
                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#nmapScanType"><i
                                class="fa fa-fw fa-arrows-v"></i> Nmap <i
                                class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="nmapScanType" class="collapse">
                            <li class="active">
                                <a href="tcpScan.php">TCP Scan</a>
                            </li>
                            <li>
                                <a href="sweepScan.php">Ping Sweep Scan</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="whois.php"><i class="fa fa-fw fa-edit"></i> WHOIS</a>
                    </li>
                    <li>
                        <a href="webServerScanner.php"><i class="fa fa-fw fa-wrench"></i> Web Server Scanner</a>
                    </li>
                    <li>
                        <a href="dnsScan.php"><i class="fa fa-fw fa-wrench"></i> DNS Scan</a>
                    </li>
                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#sslChecker"><i
                                class="fa fa-fw fa-arrows-v"></i> SSL/TLS Checker <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="sslChecker" class="collapse">
                            <li>
                                <a href="heartbleed.php">Heartbleed Scan</a>
                            </li>
                            <li>
                                <a href="poodle.php">Poodle Scan</a>
                            </li>
                            <li>
                                <a href="sslFullScan.php"><i class="fa fa-fw fa-desktop"></i> Full SSL/TLS Scan</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="myscans.php"><i class="fa fa-fw fa-table"></i> My  Scans</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>
        <div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            User Settings
                            <small>Overview</small>
                        </h1>
                        <form role="form" method="post">
                            <fieldset>
                        <div class="col-lg-4">
                        <div class="form-group">
                            <input class="form-control" placeholder="First Name" name="first_Name" type="text"
                                   value="<?php echo htmlspecialchars(isset($dbFirstName) ? $dbFirstName : null);?>" autofocus required disabled>
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="Last Name" name="last_Name" type="text"
                                   value="<?php echo htmlspecialchars(isset($dbLastName) ? $dbLastName : null);?>" required disabled>
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="Email" name="email" type="email"
                                   value="<?php echo htmlspecialchars(isset($dbEmail) ? $dbEmail : null);?>" required disabled>
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
                                } else if(isset($success)){
                                    echo "<a href=\"#\" class=\"list-group-item list-group-item-success\">$success</a>";
                                }
                                ?>
                            </div>
                            <button class="btn btn-lg btn-success btn-block" type="submit">Save Changes</button>
                            <button class="btn btn-lg btn-success btn-block" type="reset">Reset</button>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <!-- /#wrapper -->
    </div>
    <!-- jQuery -->
    <script src="https://blackrockdigital.github.io/startbootstrap-sb-admin/js/jquery.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="https://blackrockdigital.github.io/startbootstrap-sb-admin/js/bootstrap.min.js"></script>
</body>
</html>