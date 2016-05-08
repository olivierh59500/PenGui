<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 07/05/16
 * Time: 13:32
 */

//error_reporting(-1); ini_set('display_errors', 1);
require('sessionManagement.php');
require('utility.php');
require('new_task.php');

if (!isset($_SESSION['loginUser'])) {
    header("location: login.php");
}

$target = isset($_POST["sweepScan"]) ? $_POST["sweepScan"] : null;


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($target)) {
        $error = true;
    } else if (strpos($target, "/") !== false) {
        $checkCIDR = explode("/", $target);
        if (!filter_var($checkCIDR[0], FILTER_VALIDATE_IP)) {
            $error = true;
        } else {
            //function
            sweepScan($target);
            $success = true;
        }

    } else if (strpos($target, "-") !== false) {
        $checkRange = explode("-", $target);
        if (!filter_var($checkRange[0], FILTER_VALIDATE_IP)) {
            $error = true;
        } else {
            sweepScan($target);
            $success = true;
        }
    } else {
        sweepScan($target);
        $success =true;
    }
}

function sweepScan($target){
    $sessionUser = $_SESSION['loginUser'];
    $userIpAddress = isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : null;
    $taskStatus = "processing";
    $nmap = "nmap -sn ";

    $nmapScanType = $nmap . $target;
    $stmt = Utility::databaseConnection()->prepare("INSERT INTO nmap (username, user_input_command, ip_address, task_status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $sessionUser, $nmapScanType, $userIpAddress, $taskStatus);
    $stmt->execute();
    $target = null;
    $stmt->close();
    $startTask = new Task();
    $startTask->newTask($nmapScanType);
}

?>
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mojtaba Amiri">

    <title>PenGui Sweep Scan</title>

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
                <button type="button" class="navbar-toggle" data-toggle="collapse"
                        data-target=".navbar-ex1-collapse">
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
                            class="fa fa-user"></i> <?php echo(htmlspecialchars($_SESSION['loginUser'])) ?><b
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
                    <li class="active">
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
                        <a href="myscans.php"><i class="fa fa-fw fa-table"></i> MyScans</a>
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
                            Nmap
                            <small>Network Scanning</small>
                        </h1>
                        <!-- Page Content -->
                        <div id="page-content-wrapper">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h1>Sweep Scan</h1>
                                        <form class="form-horizontal" role="form" method="post" action="sweepScan.php">
                                            <div class="col-lg-6">
                                                <div class="input-group">
                                                    <input type="text" name="sweepScan" class="form-control"
                                                           placeholder="127.0.0.1-255">
                        <span class="input-group-btn">
                                            <button class="btn btn-success" type="submit">Scan</button>
                                            <button class="btn btn-danger" type="reset">Reset</button>
                                          </span>
                                                </div>
                                                <!-- /input-group -->
                                                <?php
                                                if(isset($error)) {
                                                    echo "<div class=\"alert alert-danger\"> <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
                                                    <strong>ERROR:</strong> Please enter a valid IP address.</div>";
                                                }else if (isset($success)) {
                                                    echo "<div class=\"alert alert-success\"> <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
                                                    <strong>Success:</strong> Scan was successful, See My Scans for progress of your scans</div>";
                                                }
                                                ?>
                                            </div>
                                            <!-- /.col-lg-6 -->
                                        </form>
                                    </div>
                                    <div class="col-lg-6">
                                        <br>
                                        <h4>About the tool: </h4>
                                        <p>Nmap (Network Mapper) is an open source and free tool used for
                                            network discovery and security auditing. It comes with myriad of features
                                            such as; network inventory, monitoring hosts, service uptime, host discovery, service
                                            application name and versions, operating system fingerprinting and more.</p>

                                        <h4>How it works:</h4>
                                            This technique is often referred to as Ping Sweep Scan. It doesn't perform a port scan and will only report on hosts that are alive (available) This can be a great scanning technique for sysadmins to easily scan available machines within a given network. This scanning technique will consists of an ICMP echo request, TCP SYN to port 443, TCP ACK to port 80, and an ICMP timestamp request.
                                    </div>
                                </div>
                            </div>
                            <!-- /#page-content-wrapper -->
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