<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 07/05/16
 * Time: 11:56
 */

//error_reporting(-1); ini_set('display_errors', 1);
require('sessionManagement.php');
require('utility.php');
require('new_task.php');

if (!isset($_SESSION['loginUser'])) {
    header("location: login.php");
}

$target = isset($_POST["nmap_Scan"]) ? $_POST["nmap_Scan"] : null;
$nmap = "nmap -sS ";
$serviceScan = isset($_POST['service_scan']) ? $_POST['service_scan'] : null;
$detectOS = isset($_POST['detectOS']) ? $_POST['detectOS'] : null;
$traceroute = isset($_POST['traceroute']) ? $_POST['traceroute'] : null;


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($target)) {
        $error = true;
    } else if (!filter_var($target, FILTER_VALIDATE_IP) && !filter_var("http://www." . $target, FILTER_VALIDATE_URL)) {
        $error = true;
    } else if (isset($serviceScan) & isset($detectOS) & isset($traceroute)) {
        $allOptionsSet = $nmap . $serviceScan . $detectOS . $traceroute . $target;
        tcpScan($allOptionsSet);
        $success = true;
    } else if (isset($serviceScan) & isset($detectOS)) {
        $serviceVersionWithOSDetectOptions = $nmap . $serviceScan . $detectOS . $target;
        tcpScan($serviceVersionWithOSDetectOptions);
        $success = true;
    } else if (isset($serviceScan) & isset($traceroute)) {
        $serviceVersionWithTraceroute = $nmap . $serviceScan . $traceroute . $target;
        tcpScan($serviceVersionWithTraceroute);
        $success = true;
    } else if (isset($detectOS) & isset($traceroute)) {
        $osDetectWithTraceroute = $nmap . $detectOS . $traceroute . $target;
        tcpScan($osDetectWithTraceroute);
        $success = true;
    } else if (isset($serviceScan)) {
        $serviceVersionScan = $nmap . $serviceScan . $target;
        tcpScan($serviceVersionScan);
        $success = true;
    } else if (isset($detectOS)) {
        $osDetection = $nmap . $detectOS . $target;
        tcpScan($osDetection);
        $success = true;
    } else if (isset($traceroute)) {
        $tracerouteScan = $nmap . $traceroute . $target;
        tcpScan($tracerouteScan);
        $success = true;
    } else {
        $noOptionsSet = $nmap . $target;
        tcpScan($noOptionsSet);
        $success = true;
    }
}

function tcpScan($nmapScanType)
{
    $sessionUser = $_SESSION['loginUser'];
    $userIpAddress = isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : null;
    $taskStatus = "processing";
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

    <title>PenGui TCP Scan</title>

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
                                <a href="sslFullScan.php"><i class="fa fa-fw fa-desktop"></i> SSL Checker</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="myscans.php"><i class="fa fa-fw fa-table"></i> My Scans</a>
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
                                        <h1>TCP Scan</h1>
                                        <form class="form-horizontal" role="form" method="post" action="tcpScan.php">
                                            <div class="col-lg-6">
                                                <div class="input-group">
                                                    <input type="text" name="nmap_Scan" class="form-control"
                                                           placeholder="127.0.0.1">
                        <span class="input-group-btn">
                                            <button class="btn btn-success" type="submit">Scan</button>
                                            <button class="btn btn-danger" type="reset">Reset</button>
                                          </span>
                                                </div>
                                                <?php
                                                if (isset($error)) {
                                                    echo "<div class=\"alert alert-danger\"> <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
                                                    <strong>ERROR:</strong> Please enter a valid IP address or domain name.</div>";
                                                } else if (isset($success)) {
                                                    echo "<div class=\"alert alert-success\"> <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
                                                    <strong>Success:</strong> Scan was successful, See My Scans for progress of your scans</div>";
                                                }
                                                ?>
                                                <!-- /input-group -->
                                                <h4> Scan options: </h4>
                                                <label class="checkbox-inline">
                                                    <input name="service_scan" type="checkbox" value="-sV "> Service
                                                    Version Detection
                                                </label>
                                                <label class="checkbox-inline">
                                                    <input name="detectOS" type="checkbox" value="-O "> Operating System
                                                    Detection
                                                </label>
                                                <label class="checkbox-inline">
                                                    <input name="traceroute" type="checkbox" value="--traceroute ">
                                                    Trace Route
                                                </label>
                                            </div>
                                            <!-- /.col-lg-6 -->
                                        </form>
                                    </div>
                                    <div class="col-lg-6">
                                        <br>
                                        <h4>About the tool:</h4>
                                        <p>Nmap (Network Mapper) is an open source and free tool used for
                                            network discovery and security auditing. It comes with myriad of features
                                            such as; network inventory, monitoring hosts, service uptime, host
                                            discovery, service
                                            application name and versions, operating system fingerprinting and more.</p>
                                        <h4>How it works: </h4>
                                        <P>This technique is often referred to as half-open scanning, because you don't
                                            open a full TCP connection. You send a SYN packet, as if you are going to
                                            open a real connection and then wait for a response. A SYN/ACK indicates the
                                            port is listening (open), while a RST (reset) is indicative of a
                                            non-listener. If no response is received after several retransmissions, the
                                            port is marked as filtered</p>
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