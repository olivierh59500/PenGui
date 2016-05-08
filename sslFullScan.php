<?php
require('sessionManagement.php');
require('utility.php');
require('new_task.php');
if(!isset($_SESSION['loginUser'])) {
    header("location: login.php");
}

$target = isset($_POST['sslChecker']) ? $_POST['sslChecker'] : null;
$userIpAddress = isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : null;
$sessionUser = $_SESSION['loginUser'];
$taskStatus = "processing";
$testSSL = "testssl --vulnerable ";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (empty($target)) {
        $error = true;
    } else if (!filter_var("http://www." . $target, FILTER_VALIDATE_URL)) {
        $error = true;
    } else if (isset($target)) {
        $sslCheckerAllVuln = $testSSL . $target;
        $stmt = Utility::databaseConnection()->prepare("INSERT INTO sslChecker (username, user_input_command, ip_address, task_status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $sessionUser, $sslCheckerAllVuln, $userIpAddress, $taskStatus);
        $stmt->execute();
        $success = true;
        $target = null;
        $startTask = new Task();
        $startTask->newTask($sslCheckerAllVuln);
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
                    <li class="active">
                        <a href="javascript:;" data-toggle="collapse" data-target="#sslChecker"><i
                                class="fa fa-fw fa-arrows-v"></i> SSL/TLS Scan <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="sslChecker" class="collapse">
                            <li>
                                <a href="heartbleed.php">Heartbleed Scan</a>
                            </li>
                            <li>
                                <a href="poodle.php">Poodle Scan</a>
                            </li>
                            <li class="active">
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
                            SSL
                            <small>Check your SSL </small>
                        </h1>
                        <!-- Page Content -->
                        <div id="page-content-wrapper">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h1>Full SSL/TLS Scan</h1>

                                        <form class="form-horizontal" role="form" method="post" action="sslFullScan.php">
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
                                            </div>
                                            <!-- /.col-lg-6 -->
                                        </form>
                                    </div>
                                </div>
                                <!-- Page Content -->
                                <br>
                                <h4>About this tool: </h4>
                                <p><b>testssl.sh</b> is an open source and command line tool that checks your server
                                    for the supported TLS/SSL ciphers and report any flaws found.</p>
                                <h4>How it works:</h4>
                                Testing All Known Vulnerabilities in SSL/TLS:
                                <ul><li> Heartbleed (<a href="https://cve.mitre.org/cgi-bin/cvename.cgi?name=CVE-2014-0160" target="_blank" title="CVE-2014-0160">CVE-2014-0160</a>) </li>
                                <li>CCS (<a href="https://cve.mitre.org/cgi-bin/cvename.cgi?name=CVE-2014-0224" target="_blank" title="CVE-2014-0224">CVE-2014-0224</a>)</li>
                                <li>Secure Renegotiation (<a href="https://cve.mitre.org/cgi-bin/cvename.cgi?name=CVE-2009-3555" target="_blank" title="CVE-2009-3555">CVE-2009-3555</a>)</li>
                                <li>CRIME, TLS (<a href="https://cve.mitre.org/cgi-bin/cvename.cgi?name=CVE-2012-4929" target="_blank" title="CVE-2012-4929">CVE-2012-4929</a>)</li>
                                <li>BREACH (<a href="https://cve.mitre.org/cgi-bin/cvename.cgi?name=CVE-2013-3587" target="_blank" title="CVE-2013-3587">CVE-2013-3587</a>)</li>
                                <li>POODLE, SSL (<a href="https://cve.mitre.org/cgi-bin/cvename.cgi?name=CVE-2014-3566" target="_blank" title="CVE-2014-3566">CVE-2014-3566</a>)</li>
                                <li>FREAK (<a href="https://cve.mitre.org/cgi-bin/cvename.cgi?name=CVE-2015-0204" target="_blank" title="CVE-2015-0204">CVE-2015-0204</a>)</li>
                                <li>DROWN (2016-0800, <a href="https://cve.mitre.org/cgi-bin/cvename.cgi?name=CVE-2016-0703" target="_blank" title="CVE-2016-0703">CVE-2016-0703</a>)</li>
                                <li>LOGJAM (<a href="https://cve.mitre.org/cgi-bin/cvename.cgi?name=CVE-2015-4000" target="_blank" title="CVE-2015-4000">CVE-2015-4000</a>)</li>
                                <li>BEAST (<a href="https://cve.mitre.org/cgi-bin/cvename.cgi?name=CVE-2011-3389" target="_blank" title="CVE-2011-3389">CVE-2011-3389</a>)</li>
                                <li>RC4 (<a href="https://cve.mitre.org/cgi-bin/cvename.cgi?name=CVE-2013-2566" target="_blank" title="CVE-2013-2566">CVE-2013-2566</a>)(<a href="https://cve.mitre.org/cgi-bin/cvename.cgi?name=CVE-2015-2808" target="_blank" title="CVE-2015-2808">CVE-2015-2808</a>)</li> </ul>
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
