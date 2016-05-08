<?php
//require('loginAuthentication.php');
error_reporting(-1); ini_set('display_errors', 1);
require('sessionManagement.php');
if(!isset($_SESSION['loginUser'])) {
    header("location: login.php");
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
                <li class="active">
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
                            Welcome to PenGui
                        </h1>
                        <h4 align="center">Start your vulnerability scanning now!</h4>
                        <p>This website is created for </p>
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