<?php
require('sessionManagement.php');
require('utility.php');
require('new_task.php');
if(!isset($_SESSION['loginUser'])) {
    header("location: login.php");
}

$target = isset($_POST['sslChecker']) ? $_POST['sslChecker'] : null;
$userIpAddress = isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : null;
$sessionUser = isset($_SESSION['loginUser']) ? $_SESSION['loginUser'] : null ;
$taskStatus = "processing";
$testSSL = "testssl ";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (!isset($_POST['test_all_vulnerabilities']) & !isset($_POST['poodle']) & !isset($_POST['heartbleed'])) {
        $scanTypeError = "Please select at least one scan type.";
    } else if (isset($_POST['test_all_vulnerabilities'])) {
        $testAllVulnerabilities = $_POST['test_all_vulnerabilities'];
    } else if (isset($_POST['poodle'])) {
        $poodle = $_POST['poodle'];
    } else if (isset($_POST['heartbleed'])) {
        $heartbleed = $_POST['heartbleed'];
    }

    if (!filter_var("http://www." . $target, FILTER_VALIDATE_URL)) {
        $error = true;
    }  else if (isset($testAllVulnerabilities)) {
        $sslCheckerAllVuln = $testSSL . $testAllVulnerabilities . $target;
        $stmt = Utility::databaseConnection()->prepare("INSERT INTO sslChecker (username, user_input_command, ip_address, task_status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $sessionUser, $sslCheckerAllVuln, $userIpAddress, $taskStatus);
        $stmt->execute();
        $success = true;
        $target = null;
        $startTask = new Task();
        $startTask->newTask($sslCheckerAllVuln);
    } else if (isset($poodle)) {
        $sslCheckerPoodle = $testSSL . $poodle . $target;
        $stmt = Utility::databaseConnection()->prepare("INSERT INTO sslChecker (username, user_input_command, ip_address, task_status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $sessionUser, $sslCheckerPoodle, $userIpAddress, $taskStatus);
        $stmt->execute();
        $success = true;
        $target = null;
        $stmt->close();
        $startTask = new Task();
        $startTask->newTask($sslCheckerPoodle);

    } else if (isset($heartbleed)) {
        $sslCheckerHeartbleed = $testSSL . $heartbleed . $target;
        $stmt = Utility::databaseConnection()->prepare("INSERT INTO sslChecker (username, user_input_command, ip_address, task_status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $sessionUser, $sslCheckerHeartbleed, $userIpAddress, $taskStatus);
        $stmt->execute();
        $success = true;
        $target = null;
        $stmt->close();
        $startTask = new Task();
        $startTask->newTask($sslCheckerHeartbleed);
    } else {
        $error = true;
    }
}//end of post request

function cveLinker($data) {
        $url = '@(http)?(s)?(://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@';
        $data = preg_replace($url, '<a href="http$2://$4" target="_blank" title="$0">$0</a>', $data);
    
        $url = '/(CVE)(=?.*[0-9])/';
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
                        <a href="nmap.php"> Nmap</a>
                    </li>
                    <li>
                        <a href="myscans.php"><i class="fa fa-fw fa-table"></i> My Scans</a>
                    </li>
                    <li>
                        <a href="whois.php"><i class="fa fa-fw fa-edit"></i> WHOIS</a>
                    </li>
                    <li class="active">
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
                                class="fa fa-fw fa-arrows-v"></i> Dropdown <i class="fa fa-fw fa-caret-down"></i></a>
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
                            SSL
                            <small>Check your SSL </small>
                        </h1>
                        <!-- Page Content -->
                        <div id="page-content-wrapper">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h1>SSL Scan</h1>

                                        <form class="form-horizontal" role="form" method="post" action="sslchecker.php">
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
                                                <h4> Scan type: </h4>
                                                <label class="checkbox-inline">
                                                    <input name="test_all_vulnerabilities" type="checkbox" value="-U "> Test All Vulnerabilities</label>
                                                <label class="checkbox-inline">
                                                    <input name="heartbleed" type="checkbox" value="--heartbleed "> Heartbleed</label>
                                                <label class="checkbox-inline">
                                                    <input name="poodle" type="checkbox" value="--poodle "> Poodle</label>

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
                                    <br><br><br><br><br><br>
                                    <p>Heardbleed -
                                        https://cve.mitre.org/cgi-bin/cvename.cgi?name=cve-2014-0160
                                        The (1) TLS and (2) DTLS implementations in OpenSSL 1.0.1 before 1.0.1g do not properly handle Heartbeat Extension packets, which allows remote attackers to obtain sensitive information from process memory via crafted packets that trigger a buffer over-read, as demonstrated by reading private keys, related to d1_both.c and t1_lib.c, aka the Heartbleed bug.</p>
                                </div>
                                <!-- Page Content -->
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
