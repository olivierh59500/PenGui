<?php
require('sessionManagement.php');
require('utility.php');
require('new_task.php');
if(!isset($_SESSION['loginUser'])) {
    header("location: login.php");
}

$target = $_POST['sslChecker'];
$userIpAddress = $_SERVER["REMOTE_ADDR"];
$sessionUser = $_SESSION['loginUser'];
$taskStatus = "processing";
$testSSL = "testssl ";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (!isset($_POST['test_all_vulnerabilities']) & !isset($_POST['poodle']) & !isset($_POST['heartbleed'])) {
        $scanTypeError = "Please select at least one scan type.";
    } else if (isset($_POST['test_all_vulnerabilities'])) {
        $testAllVulnerabilities = $_POST['test_all_vulnerabilities'];
    } else if (isset($_POST['poodle'])) {
        $poodle = $_POST['poodle'];
    } else if (isset($_POST['heartbleed'])) {
        $heartbleed = $_POST['heartbleed'];
    }

    if (!filter_var("http://www." . $target, FILTER_VALIDATE_URL)) {
        $error = true;
    }  else if (isset($testAllVulnerabilities)) {
        $sslCheckerAllVuln = $testSSL . $testAllVulnerabilities . $target;
        $stmt = Utility::databaseConnection()->prepare("INSERT INTO sslChecker (username, user_input_command, ip_address, task_status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $sessionUser, $sslCheckerAllVuln, $userIpAddress, $taskStatus);
        $stmt->execute();
        $success = true;
        $target = null;
        $startTask = new Task();
        $startTask->newTask($sslCheckerAllVuln);
    } else if (isset($poodle)) {
        $sslCheckerPoodle = $testSSL . $poodle . $target;
        $stmt = Utility::databaseConnection()->prepare("INSERT INTO sslChecker (username, user_input_command, ip_address, task_status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $sessionUser, $sslCheckerPoodle, $userIpAddress, $taskStatus);
        $stmt->execute();
        $success = true;
        $target = null;
        $stmt->close();
        $startTask = new Task();
        $startTask->newTask($sslCheckerPoodle);

    } else if (isset($heartbleed)) {
        $sslCheckerHeartbleed = $testSSL . $heartbleed . $target;
        $stmt = Utility::databaseConnection()->prepare("INSERT INTO sslChecker (username, user_input_command, ip_address, task_status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $sessionUser, $sslCheckerHeartbleed, $userIpAddress, $taskStatus);
        $stmt->execute();
        $success = true;
        $target = null;
        $stmt->close();
        $startTask = new Task();
        $startTask->newTask($sslCheckerHeartbleed);
    } else {
        $error = true;
    }
}//end of post request

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mojtaba Amiri">

    <title>PenGui SSL Checker</title>

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
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i
                            class="fa fa-user"></i> <?php echo (htmlspecialchars($_SESSION['loginUser'])) ?><b
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
                        <a href="nmap.php"> Nmap</a>
                    </li>
                    <li>
                        <a href="myscans.php"><i class="fa fa-fw fa-table"></i> My Scans</a>
                    </li>
                    <li>
                        <a href="whois.php"><i class="fa fa-fw fa-edit"></i> WHOIS</a>
                    </li>
                    <li class="active">
                        <a href="sslchecker.php"><i class="fa fa-fw fa-desktop"></i> SSL Checker</a>
                    </li>
                    <li>
                        <a href="bootstrap-grid.html"><i class="fa fa-fw fa-wrench"></i> Bootstrap Grid</a>
                    </li>
                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#demo"><i
                                class="fa fa-fw fa-arrows-v"></i> Dropdown <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="demo" class="collapse">
                            <li>
                                <a href="#">Dropdown Item</a>
                            </li>
                            <li>
                                <a href="#">Dropdown Item</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="index.php"><i class="fa fa-fw fa-dashboard"></i> RTL Dashboard</a>
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
                            SSL
                            <small>Check your SSL </small>
                        </h1>
                        <!-- Page Content -->
                        <div id="page-content-wrapper">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h1>SSL Scan</h1>

                                        <form class="form-horizontal" role="form" method="post" action="sslchecker.php">
                                            <div class="col-lg-6">
                                                <div class="input-group">
                                                    <input type="text" name="sslChecker" class="form-control"
                                                           placeholder="example.com">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-success" type="submit">Scan</button>
                                                        <button class="btn btn-danger" type="reset">Reset</button>
                                                    </span>
                                                </div>
                                                <?php
                                                if (isset($error)) {
                                                    echo "<div class=\"alert alert-danger\"> <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
                                                           <strong>ERROR:</strong> Please enter a valid domain name.</div>";
                                                } else if (isset($success)) {
                                                    echo "<div class=\"alert alert-success\"> <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
                                                           <strong>Success:</strong> Scan was successful, See My Scans for progress of your scans</div>";
                                                }
                                                ?>
                                                <h4> Scan type: </h4>
                                                <label class="checkbox-inline">
                                                    <input name="test_all_vulnerabilities" type="checkbox" value="-U "> Test All Vulnerabilities</label>
                                                <label class="checkbox-inline">
                                                    <input name="heartbleed" type="checkbox" value="--heartbleed "> Heartbleed</label>
                                                <label class="checkbox-inline">
                                                    <input name="poodle" type="checkbox" value="--poodle "> Poodle</label>

                                                <?php if(isset($scanTypeError)) {
                                                    echo "<div class=\"alert alert-danger\"> <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
                  <strong>ERROR: </strong>" . $scanTypeError. "</div>";
                                                } else if (isset($multiScanTypeError)) {
                                                    echo "<div class=\"alert alert-danger\"> <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
                  <strong>ERROR: </strong>" . $multiScanTypeError. "</div>";
                                                }

                                                ?>


                                            </div>
                                            <!-- /.col-lg-6 -->
                                        </form>
                                    </div>
                                    <br><br><br><br><br><br>
                                </div>
                                <!-- Page Content -->
                                <div id="page-content-wrapper">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <h2>Tasks Pending</h2>
                                                <table class="table table-hover table-responsive">
                                                    <thead>
                                                    <td>Scan</td>
                                                    <td>Task Status</td>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                    $currentUser = $_SESSION['loginUser'];
                                                    $stmt = Utility::databaseConnection()->prepare("SELECT user_input_command, task_status FROM sslChecker WHERE task_status = 'processing' AND username = ? ORDER BY create_time");

                                                    $stmt->bind_param("s", $currentUser);
                                                    $stmt->bind_result($dbCommand, $dbTaskStatus);
                                                    $stmt->execute();
                                                    $stmt->store_result();
                                                    while ($stmt->fetch()) {
                                                        echo "<tr>  <td>" . htmlentities($dbCommand, ENT_QUOTES) . "</td>" .
                                                            "<td>" .  htmlentities($dbTaskStatus, ENT_QUOTES) . "</td> </tr>";
                                                    }
                                                    $stmt->close(); ?>
                                                    </tbody>
                                                </table>
                                                <!--Table Continue-->
                                                <h2>Tasks completed</h2>
                                                <table class="table table-hover table-responsive">
                                                    <thead>
                                                    <td>Scan</td>
                                                    <td>Result</td>
                                                    <td>Task Status</td>
                                                    </thead>
                                                    <tbody>
                                                    <?php //Task Completed
                                                    $currentUser = $_SESSION['loginUser'];
                                                    $stmt = Utility::databaseConnection()->prepare("SELECT user_input_command, sslChecker_log_returned, task_status, create_time from sslChecker where username= ? and task_status='completed' order by create_time desc");
                                                    $stmt->bind_param("s", $currentUser);
                                                    $stmt->execute();
                                                    $stmt->store_result();
                                                    $stmt->bind_result($dbCommand, $dbLogReturned, $dbTaskStatus, $dbCreateTime);
                                                    while ($stmt->fetch()) {
                                                        echo "<tr>  <td>" . htmlentities($dbCommand, ENT_QUOTES) . "</td>
                                                            <td>" . nl2br(htmlentities(trim($dbLogReturned), ENT_QUOTES)) . "</td> 
                                                            <td>" . htmlentities($dbTaskStatus, ENT_QUOTES) . "</td> </tr>";
                                                    }
                                                    $stmt->close(); ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                            </div>
                        </div>
                        <!-- /#page-content-wrapper -->
                    </div>
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
