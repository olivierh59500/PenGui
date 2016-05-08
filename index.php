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

    <title>Welcome to PenGui</title>

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
                            Welcome to PenGui! - <b><i>BETA!</i></b>
                        </h1>
                        <h2 align="center">Start your vulnerability scanning now!</h2>
                        <p>This website is created for people who are interested in security and in security of their
                            infrastructure.The aim of PenGui is to provide a one stop shop for most users looking to
                            scan their servers and services to reveal any vulnerabilities found by PenGui using open
                            source security tools.</p>
                        For example:
                        <ul>
                            <li>Scan your network and servers from external IP address</li>
                            <li>Save your self time and the hassle form installing these tools on your local machine
                            </li>
                            <li>Some scans could be blocked or filtered by your network</li>
                            <li>Ease of access and human readable logs</li>
                            <li>Each scan type has information about how they work</li>
                        </ul>
                        <br>
                        <h4>Getting Started</h4>
                        <ul>
                            <li>All the tools are available on the left hand side.</li>
                            <li>Each of these tools are clearly presented with information on how to use them.</li>
                            <li>After a successful scan you will find your scan results in <b>My Scans</b></li>
                        </ul>
                        <h4>Things to still come:</h4>
                        <ul>
                            <li>Further Log Simplification - Easier to read Logs returned via the tools</li>
                            <li>Shed more features</li>
                        </ul>
                        <h4>Bugs bugs bugs!</h4>
                        <p>If you have identified a bug please report it at: bugs@pengui.uk</p>
                        <p>For feedback and sites issues please contact webmaster@pengui.uk</p>
                        <br><br><br>
                        <div align="center">
                            <div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert"
                                                               aria-label="close">&times;</a>
                                <strong>WARNING: YOU MUST HAVE PERMISSIONS BEFORE TARGETING A HOST USING THE
                                    TOOLS PROVIDED ON PenGui</strong>
                            </div>
                        </div>
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