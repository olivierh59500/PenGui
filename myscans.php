<?php
error_reporting(-1);
ini_set('display_errors', 1);
require('sessionManagement.php');
require('utility.php');


if (!isset($_SESSION['loginUser'])) {
    header("location: login.php");
}

function linkParse($data){
//    $url = '@(http)?(s)?(://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@';
//    $data = preg_replace($url, '<a href="http$2://$4" target="_blank" title="$0">$0</a>', $data);
//    return $data;
    $url = '@(http)?(s)?(://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@';
    $data = preg_replace($url, '<a href="http$2://$4" target="_blank" title="$0">$0</a>', $data);

    $url = '/(CVE)(.*[0-9])/';
    $data = preg_replace($url, '<a href="https://cve.mitre.org/cgi-bin/cvename.cgi?name=$0" target="_blank" title="$0">$0</a>', $data);
    return $data;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="refresh" content="120">
    <meta name="description" content="">
    <meta name="author" content="Mojtaba Amiri">

    <title>PenGui My Scans</title>

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
                            class="fa fa-user"></i> <?php echo(htmlspecialchars($_SESSION['loginUser'], ENT_QUOTES)) ?>
                        <b
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
                    <li class="active">
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
                            My Scans
                            <small>List of your Scans</small>
                        </h1>
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
                                            $stmt = Utility::databaseConnection()->prepare("
SELECT user_input_command, task_status, create_time FROM nmap WHERE task_status = 'processing' AND username = ?
UNION 
SELECT user_input_command, task_status, create_time FROM whois WHERE task_status = 'processing' AND username = ?
UNION 
SELECT user_input_command, task_status, create_time FROM sslChecker WHERE task_status = 'processing' AND username = ?
UNION 
SELECT user_input_command, task_status, create_time FROM nikto WHERE task_status = 'processing' AND username = ?
UNION 
SELECT user_input_command, task_status, create_time FROM dnsScan WHERE task_status = 'processing' AND username = ?
ORDER BY create_time DESC;");
                                            $stmt->bind_param("sssss", $currentUser, $currentUser, $currentUser, $currentUser, $currentUser);
                                            $stmt->execute();
                                            $stmt->bind_result($dbCommand, $dbTaskStatus, $dbCreateTime);
                                            while($stmt->fetch()) {
                                                if(empty($dbCommand)){
                                                    echo "<tr><td> Nothing to process </td></tr>";
                                                } else {
                                                    echo "<tr>  <td>" . htmlentities($dbCommand, ENT_QUOTES) . "</td>" .
                                                        "<td>" . htmlentities($dbTaskStatus, ENT_QUOTES) . "</td> </tr>";
                                                }
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
                                            $stmt = Utility::databaseConnection()->prepare("
SELECT user_input_command, nmap_log_simplified, task_status, create_time FROM nmap WHERE username= ? AND task_status='completed'
UNION
SELECT user_input_command, whois_log_returned, task_status, create_time FROM whois WHERE username= ? AND task_status='completed'
UNION
SELECT user_input_command, sslChecker_log_simplified, task_status, create_time from sslChecker where username= ? and task_status='completed'
UNION
SELECT user_input_command, nikto_log_returned, task_status, create_time FROM nikto WHERE username = ? AND task_status = 'completed'
UNION
SELECT user_input_command, dns_log_returned, task_status, create_time FROM dnsScan WHERE username = ? AND task_status = 'completed'
ORDER BY create_time DESC");
                                            $stmt->bind_param("sssss", $currentUser, $currentUser, $currentUser, $currentUser, $currentUser);
                                            $stmt->execute();
                                            $stmt->store_result();
                                            $stmt->bind_result($dbCommand, $dbLogReturned, $dbTaskStatus, $dbCreateTime);
                                            while ($stmt->fetch()) {
                                                $dbLogReturned = linkParse($dbLogReturned);
                                                $dbCommand = linkParse($dbCommand);
                                                echo "<tr>  <td>" . $dbCommand. "</td>
                                                            <td>" . nl2br(trim($dbLogReturned), ENT_QUOTES) . "</td> 
                                                            <td>" . htmlentities($dbTaskStatus, ENT_QUOTES) . "</td> </tr>";
                                            }
                                            $stmt->close(); ?>
                                            </tbody>
                                        </table>
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