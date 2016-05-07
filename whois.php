<?php
//error_reporting(-1); ini_set('display_errors', 1);
//require('loginAuthentication.php');
require('utility.php');
require('sessionManagement.php');
require('new_task.php');

if(!isset($_SESSION['loginUser'])) {
    header("location: login.php");
}

$whoIsScan = Utility::validateInput($_POST['whois_scan']);
$userIpAddress = $_SERVER["REMOTE_ADDR"];
$sessionUser = $_SESSION['loginUser'];
$taskStatus = "processing";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($whoIsScan)) {
        $error = true;
    }
    //strip the [http://www.] get the domain name and then add the http or https to the link to get validated
    if(!filter_var("http://www." . $whoIsScan, FILTER_VALIDATE_URL) && !filter_var($whoIsScan, FILTER_VALIDATE_IP)) {
        $error = true;
    } else {
        $whois = "whois " .$whoIsScan; //need to remove the www. from the string so need to use explode string function
        $stmt = Utility::databaseConnection()->prepare("INSERT INTO whois (username, user_input_command, ip_address, task_status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss",$sessionUser, $whois, $userIpAddress, $taskStatus);
        $stmt->execute();
        $success = true;
        //call the Task newTask function to queue the job
        //exec(escapeshellcmd("php backgroundTask.php >/dev/null 2>/dev/null &"));
        $startTask = new Task();
        $startTask->newTask($whois);
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

    <title>PenGui WHOIS</title>

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
                    <li class="active">
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
                            WHOIS
                            <small>Domain Information Gathering</small>
                        </h1>
                        <!-- Page Content -->
                        <div id="page-content-wrapper">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h1>WHOIS Scan</h1>
                                        <form class="form-horizontal" role="form" method="post" action="whois.php">
                                            <div class="col-lg-6">
                                                <div class="input-group">
                                                    <input type="text" name="whois_scan" class="form-control"
                                                           placeholder="example.com or 121.121.121.121">
                        <span class="input-group-btn">
                                            <button class="btn btn-success" type="submit">Scan</button>
                                            <button class="btn btn-danger" type="reset">Reset</button>
                                          </span>
                                                </div>
                                                <?php
                                                if (isset($error)) {
                                                    echo "<div class=\"alert alert-danger\"> <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
                  <strong>ERROR:</strong> Please enter a valid IP Address or Domain name.</div>";
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
                                <p>A WHOIS search will provide information regarding a domain name, such as example.com. It may include information, such as domain ownership, where and                                            when registered, expiration date, and the nameservers assigned to the domain.</p>
                                <p>Uses of WHOIS: </p>
                                <ul class="dl-horizontal">
                                    <li>Determine whether a domain is available</li>
                                    <li>Contact network administrators regarding technical matters</li>
                                    <li>Diagnose registration difficulties</li>
                                    <li>Contact web administrators for resolution of technical matters associated with a domain name</li>
                                    <li>Obtain the real world identity, business location and contact information of an online merchant or business, or generally, any organization that has an online presence</li>
                                    <li>Associate a company, organization, or individual with a domain name, and to identify the party that is operating a web or other publicly accessible service using a domain name, for commercial or other purposes</li>
                                    <li>Contact a domain name registrant for the purpose of discussing and negotiating a secondary market transaction related to a registered domain name</li>
                                    <li>Notify a domain name registrant of the registrant's obligation to maintain accurate registration information</li>
                                    <li>Contact a domain name registrant on matters related to the protection and enforcement of intellectual property rights</li>
                                    <li>Establish or look into an identity in cyberspace, and as part of an incident response following an Internet or computer attack- (Security professionals and law enforcement agents use WHOIS to identify points of contact for a domain name)</li>
                                    <li>Gather investigative leads (i.e., to identify parties from whom additional information might be obtained)- Law enforcement agents use WHOIS to find email addresses and attempt to identify the location of an alleged perpetrator of a crime involving fraud</li>
                                    <li>Investigate spam- law enforcement agents look to the WHOIS database to collect information on the website advertised in the spam</li>
                                </ul>
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