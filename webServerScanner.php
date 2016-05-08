<?php
//error_reporting(-1); ini_set('display_errors', 1);
require('utility.php');
require('sessionManagement.php');
require('new_task.php');

if(!isset($_SESSION['loginUser'])) {
    header("location: login.php");
}

$niktoScanTarget = isset($_POST['nikto_scan']) ? $_POST['nikto_scan'] : null;
$userIpAddress = isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : null;
$sessionUser = $_SESSION['loginUser'];
$taskStatus = "processing";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($niktoScanTarget)) {
        $error = true;
    }
    if(!filter_var($niktoScanTarget, FILTER_VALIDATE_URL) && !filter_var($niktoScanTarget, FILTER_VALIDATE_IP)) {
        $error = true;
    } else {
        $location = "perl /var/www/html/vendor/nikto/program/nikto.pl -C all -no404 -maxtime 500 -host ";
        $nikto = $location . $niktoScanTarget; //need to remove the www. from the string so need to use explode string function
        $stmt = Utility::databaseConnection()->prepare("
                INSERT INTO nikto (username, user_input_command, ip_address, task_status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss",$sessionUser, $nikto, $userIpAddress, $taskStatus);
        $stmt->execute();
        $success = true;
        //call the Task newTask function to queue the job
        $startTask = new Task();
        $startTask->newTask($nikto);
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
    <meta name="author" content="Mojtaba Amiri">

    <title>PenGui Web Server Scanner</title>

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
                    <li class="active">
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
                            Nikto
                            <small>Web Server Scanner</small>
                        </h1>
                        <!-- Page Content -->
                        <div id="page-content-wrapper">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h1>Scan</h1>
                                        <form class="form-horizontal" role="form" method="post" action="webServerScanner.php">
                                            <div class="col-lg-6">
                                                <div class="input-group">
                                                    <input type="text" name="nikto_scan" class="form-control"
                                                           placeholder="http://">
                        <span class="input-group-btn">
                                            <button class="btn btn-success" type="submit">Scan</button>
                                            <button class="btn btn-danger" type="reset">Reset</button>
                                          </span>
                                                </div>
                                            <?php
                                                if (isset($error)) {
                                                    echo "<div class=\"alert alert-danger\"> <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
                  <strong>ERROR:</strong> Please enter a valid IP Address or URL. Have you forgotten to add http:// or https://</div>";
                                                } else if (isset($success)) {
                                                    echo "<div class=\"alert alert-success\"> <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
                  <strong>Success:</strong> Scan was successful, See My Scans for the progress of your scans</div>";
                                                }
                                           ?>
                                            </div>
                                            <!-- /.col-lg-6 -->
                                        </form>
                                    </div>
                                </div>
                                <br>
                                <h4>About this tool:</h4>
                                <p>Nikto is an Open Source (GPL) web server scanner which performs comprehensive tests against web servers for multiple items, including over 6700 potentially dangerous files/programs, checks for outdated versions of over 1250 servers, and version specific problems on over 270 servers. It also checks for server configuration items such as the presence of multiple index files, HTTP server options, and will attempt to identify installed web servers and software. Scan items and plugins are frequently updated and can be automatically updated.

                                    Nikto is not designed as a stealthy tool. It will test a web server in the quickest time possible, and is obvious in log files or to an IPS/IDS. Find out more here: <a href="https://cirt.net/Nikto2">https://cirt.net/Nikto2</a> </p>
                                <h4>How it works:</h4>
                                <p>Nikto will be scanning the target URL in the backend. All URLs must begin with <b>http:// or https://</b></p>
                                <p>Nikto is capable of the followings but not limited too:</p>
                                <ul class="dl-horizontal">
                                    <li>Finding out the web server and its version</li>
                                    <li>Web server configuration issues</li>
                                    <li>Check for existing vulnerabilities</li>
                                    <li>Identifying out of date applications running on a web server</li>
                                    <li>Provides SSL/TLS information</li>
                                </ul>
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