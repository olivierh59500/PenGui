<?php
error_reporting(-1); ini_set('display_errors', 1);
require('sessionManagement.php');
require('utility.php');
require('executeNmap.php');

if (!isset($_SESSION['loginUser'])) {
    header("location: login.php");
}

$scan = $_POST["nmap_Scan"];
$userIpAddress = $_SERVER['REMOTE_ADDR'];
$sessionUser = $_SESSION['loginUser'];
$taskStatus = "pending";
$nmap = "nmap ";

//$checkCIDR = explode("/", $scan);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!isset($_POST['tcp_sync_scan']) & !isset($_POST['tcp_connect_scan'])) {
        $scanTypeError = "Please select at least one scan type.";
    } else if (isset($_POST['tcp_sync_scan']) & isset($_POST['tcp_connect_scan'])) {
        $multiScanTypeError = "Please only select one scan type.";
    } else if (isset($_POST['tcp_connect_scan'])) {
        $tcpConnectScan = $_POST['tcp_connect_scan'];
    } else if (isset($_POST['tcp_sync_scan'])) {
        $tcpSyncScan = $_POST['tcp_sync_scan'];
    }

    if (!filter_var($scan, FILTER_VALIDATE_IP)) {
        $error = true;
    } else if (isset($tcpSyncScan)) {
        $nmapScanType = $nmap . $tcpSyncScan . $scan;
        $stmt = Utility::databaseConnection()->prepare("INSERT INTO nmap (username, user_input_command, ip_address, task_status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $sessionUser, $nmapScanType, $userIpAddress, $taskStatus);
        $stmt->execute();
        $success = true;
        $scan = null;
        exec("php backgroundTask.php >/dev/null 2>/dev/null &");
    } else if (isset($tcpConnectScan)) {
        $nmapScanType = $nmap . $tcpConnectScan . $scan;
        $stmt = Utility::databaseConnection()->prepare("INSERT INTO nmap (username, user_input_command, ip_address, task_status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $sessionUser, $nmapScanType, $userIpAddress, $taskStatus);
        $stmt->execute();
        $success = true;
        $scan = null;
        $stmt->close();
        exec("php backgroundTask.php >/dev/null 2>/dev/null &");
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
                            <a href="forms.html"><i class="fa fa-fw fa-edit"></i> Forms</a>
                        </li>
                        <li>
                            <a href="bootstrap-elements.html"><i class="fa fa-fw fa-desktop"></i> Bootstrap Elements</a>
                        </li>
                        <li>
                            <a href="bootstrap-grid.html"><i class="fa fa-fw fa-wrench"></i> Bootstrap Grid</a>
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
                  <strong>ERROR:</strong> Please enter a valid IP address.</div>";
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
                                        <br><br>
                                        <p>Nmap is a tool used for various network......</p>
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