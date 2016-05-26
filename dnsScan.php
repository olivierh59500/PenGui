<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 06/05/16
 * Time: 22:32
 */

error_reporting(-1); ini_set('display_errors', 1);
require('utility.php');
require('new_task.php');
require('sessionManagement.php');

if(!isset($_SESSION['SessionID'])) {
    header("location: login.php");
}

$dnsScan = isset($_POST['dns_scan']) ? $_POST['dns_scan'] : null;
$userIpAddress = isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : null;
$sessionUser = $_SESSION['loginUser'];
$taskStatus = "processing";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($dnsScan)) {
        $error = true;
    }
    //strip the [http://www.] get the domain name and then add the http or https to the link to get validated
    //only works for domain name
    if(!filter_var("http://www." . $dnsScan, FILTER_VALIDATE_URL) && !filter_var($dnsScan, FILTER_VALIDATE_IP)) {
        $error = true;
    } else {
        $dnsScanTarget = "dnscan --domain " .$dnsScan; //need to remove the www. from the string so need to use explode string function
        $stmt = Utility::databaseConnection()->prepare("INSERT INTO dnsScan (username, user_input_command, ip_address, task_status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss",$sessionUser, $dnsScanTarget, $userIpAddress, $taskStatus);
        $stmt->execute();
        $success = true;
        //call the Task newTask function to queue the job
        //exec(escapeshellcmd("php backgroundTask.php >/dev/null 2>/dev/null &"));
        $startTask = new Task();
        $startTask->newTask($dnsScanTarget);
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
    <meta name="author" content="">

    <title>PenGui DNS Scan</title>

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
                    <li>
                        <a href="webServerScanner.php"><i class="fa fa-fw fa-wrench"></i> Web Server Scanner</a>
                    </li>
                    <li class="active">
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
                            DNS SCAN
                            <small>DNS Subdomain Scanner</small>
                        </h1>
                        <!-- Page Content -->
                        <div id="page-content-wrapper">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h1>Scan</h1>
                                        <form class="form-horizontal" role="form" method="post" action="dnsScan.php">
                                            <div class="col-lg-6">
                                                <div class="input-group">
                                                    <input type="text" name="dns_scan" class="form-control"
                                                           placeholder="example.com">
                        <span class="input-group-btn">
                                            <button class="btn btn-success" type="submit">Scan</button>
                                            <button class="btn btn-danger" type="reset">Reset</button>
                                          </span>
                                                </div>
                                                <?php
                                                if (isset($error)) {
                                                    echo "<div class=\"alert alert-danger\"> <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
                  <strong>ERROR:</strong> Please enter a valid Domain name.</div>";
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
                                <p>DNS Scan is a tool based in python using the top most common subdomain names. DNS Scan uses wordlist of subdomains to perform its scans. </p>
                                <br>
                                <h4>How it works:</h4>
                                <p>DNS Scan will first try to perform a DNS zone transfer and if the zone transfer failed it will lookup the TXT and MX records for the target domain and                                       performs a recursive subdomain scanning.</p>
                                <p>A zone transfer that from an external IP address is used as part of an attackers reconnaissance phase. Usually a zone transfer is a normal operation                                          between primary and secondary DNS servers in order to synchronise the records for a domain. This is typically not something you want to be externally                                        accessible. If an attacker can gather all your DNS records, they can use those to select targets for exploitation.</p>
                                <p>To find out more go here:
                                    <a href="https://hackertarget.com/zone-transfer/" target="_blank">https://hackertarget.com/zone-transfer/</a> or
                                    <a href="https://digi.ninja/projects/zonetransferme.php" target="_blank">https://digi.ninja/projects/zonetransferme.php</a> or
                                    <a href="https://technet.microsoft.com/en-us/library/cc781340(v=ws.10).aspx" target="_blank">https://technet.microsoft.com/en-us/library/cc781340(v=ws.10).aspx</a></p>
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