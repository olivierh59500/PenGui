<?php
//error_reporting(-1); ini_set('display_errors', 1);
require('sessionManagement.php');
require('utility.php');
require('new_task.php');

if (!isset($_SESSION['loginUser'])) {
    header("location: login.php");
}

$target = $_POST["nmap_Scan"];
$sessionUser = $_SESSION['loginUser'];
$userIpAddress = $_SERVER["REMOTE_ADDR"];
$taskStatus = "processing";
$nmap = "nmap ";


//$checkCIDR = explode("/", $scan);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['tcp_sync_scan']) & !isset($_POST['tcp_connect_scan']) & !isset($_POST['service_scan'])) {
        $scanTypeError = "Please select at least one scan type.";
    } else if (isset($_POST['tcp_sync_scan']) & isset($_POST['tcp_connect_scan'])) {
        $multiScanTypeError = "Please only select one scan type.";
    } else if (isset($_POST['tcp_connect_scan'])) {
        $tcpConnectScan = $_POST['tcp_connect_scan'];
    } else if (isset($_POST['tcp_sync_scan'])) {
        $tcpSyncScan = $_POST['tcp_sync_scan'];
    } else if (isset($_POST['service_scan'])){
        $serviceScan = $_POST['service_scan'];
    }

    if (!filter_var($target, FILTER_VALIDATE_IP) && !filter_var("http://www." . $whoIsScan, FILTER_VALIDATE_URL)) {
        $error = true;
    } else if (isset($tcpSyncScan)) {
        $nmapScanType = $nmap . $tcpSyncScan . $target;
        $stmt = Utility::databaseConnection()->prepare("INSERT INTO nmap (username, user_input_command, ip_address, task_status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $sessionUser, $nmapScanType, $userIpAddress, $taskStatus);
        $stmt->execute();
        $success = true;
        $target = null;
        $startTask = new Task();
        $startTask->newTask($nmapScanType);
    } else if (isset($tcpConnectScan)) {
        $nmapScanType = $nmap . $tcpConnectScan . $target;
        $stmt = Utility::databaseConnection()->prepare("INSERT INTO nmap (username, user_input_command, ip_address, task_status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $sessionUser, $nmapScanType, $userIpAddress, $taskStatus);
        $stmt->execute();
        $success = true;
        $target = null;
        $stmt->close();
        $startTask = new Task();
        $startTask->newTask($nmapScanType);

    } else if (isset($serviceScan)) {
        $nmapScanType = $nmap . $serviceScan . $target;
        $stmt = Utility::databaseConnection()->prepare("INSERT INTO nmap (username, user_input_command, ip_address, task_status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $sessionUser, $nmapScanType, $userIpAddress, $taskStatus);
        $stmt->execute();
        $success = true;
        $target = null;
        $stmt->close();
        $startTask = new Task();
        $startTask->newTask($nmapScanType);
    } else {
        $error = true;
    }
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
                            <a href="nmap.php"> Nmap</a>
                        </li>
                        <li>
                            <a href="myscans.php"><i class="fa fa-fw fa-table"></i> MyScans</a>
                        </li>
                        <li>
                            <a href="whois.php"><i class="fa fa-fw fa-edit"></i> WHOIS</a>
                        </li>
                        <li>
                            <a href="sslchecker.php"><i class="fa fa-fw fa-desktop"></i> SSL Checker</a>
                        </li>
                        <li>
                            <a href="webServerScanner.php"><i class="fa fa-fw fa-wrench"></i> Web Server Scanner</a>
                        </li>
                        <li>
                            <a href="dnsScan.php"><i class="fa fa-fw fa-wrench"></i> DNS Scan</a>
                        </li>
                        <li>
                            <a href="javascript:;" data-toggle="collapse" data-target="#demo"><i
                                    class="fa fa-fw fa-arrows-v"></i> Dropdown <i
                                    class="fa fa-fw fa-caret-down"></i></a>
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
                                Nmap
                                <small>Network Scanning</small>
                            </h1>
                            <!-- Page Content -->
                            <div id="page-content-wrapper">
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <h1>TCP Scan</h1>

                                            <form class="form-horizontal" role="form" method="post" action="nmap.php">
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
                                                    if(isset($error)) {
                                                        echo "<div class=\"alert alert-danger\"> <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
                  <strong>ERROR:</strong> Please enter a valid IP address or domain name.</div>";
                                                    }else if (isset($success)) {
                                                        echo "<div class=\"alert alert-success\"> <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
                  <strong>Success:</strong> Scan was successful, See My Scans for progress of your scans</div>";
                                                    }
                                                    ?>
                                                    <!-- /input-group -->
                                                    <h4> Scan type: </h4>
                                                    <label class="checkbox-inline">
                                                        <input name="tcp_sync_scan" type="checkbox" value="-sS ">-sS TCP SYN Scan</label>
                                                    <label class="checkbox-inline">
                                                        <input name="tcp_connect_scan" type="checkbox" value="-sT ">-sT TCP Connect Scan</label>
                                                    <label class="checkbox-inline">
                                                        <input name="service_scan" type="checkbox" value="-sV ">-sV Service Scan</label>

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
                                        <div class="col-lg-6">
                                            <br>
                                            <p>Nmap (Network Mapper) is an open source and free tool used for
                                                    network discovery and security auditing. It comes with myriad of features
                                                    such as; network inventory, monitoring hosts, service uptime, host discovery, service
                                                    application name and versions, operating system fingerprinting and more. It’s supported
                                                    on all operating systems and has two version; the command line and GUI (zenmap)</p>

                                            <ul class="dl-horizontal">
                                                <li>-sS (TCP SYN scan): This technique is often referred to as half-open scanning, because you don't open a full TCP connection. You send a SYN packet, as if you are going to open a real connection and then wait for a response. A SYN/ACK indicates the port is listening (open), while a RST (reset) is indicative of a non-listener. If no response is received after several retransmissions, the port is marked as filtered.</li>
                                                <li> -sT (TCP Connect Scan): This technique is not as effective as TCP Sync scan because it has to establish a full three way handshake syn > syn,ack > ack this is most likely be logged by the server you are scanning against and syslog logging the scan and if there is a IDS involved being flagged.
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