<?php
error_reporting(-1);
ini_set('display_errors', 1);
require('sessionManagement.php');
require('utility.php');


if (!isset($_SESSION['loginUser'])) {
    header("location: login.php");
}
exec("php backgroundTask.php >/dev/null 2>/dev/null &");
?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="refresh" content="120">
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
                        <a href="nmap.php"> Nmap</a>
                    </li>
                    <li class="active">
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
                            Nmap
                            <small>Network Scanning</small>
                        </h1>
                        <!-- Page Content -->
                        <div id="page-content-wrapper">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h2>Tasks Pending</h2>
                                        <table class="table table-hover table-responsive">
                                            <thead>
                                            <td>Nmap Scan</td>
                                            <td>Task Status</td>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $currentUser = $_SESSION['loginUser'];
                                            $stmt = Utility::databaseConnection()->prepare("SELECT user_input_command, task_status FROM nmap WHERE task_status = 'pending' AND username = ? ORDER                                                                                             BY create_time DESC");
                                            $stmt->bind_param("s", $currentUser);
                                            $stmt->bind_result($dbNmapCommand, $dbTaskStatus);
                                            $stmt->execute();
                                            $stmt->store_result();
                                            while ($stmt->fetch()) {
                                                echo "<tr>  <td>" . htmlentities($dbNmapCommand, ENT_QUOTES) . "</td>" .
                                                            "<td>" .  htmlentities($dbTaskStatus, ENT_QUOTES) . "</td> </tr>";
                                            }
                                            $stmt->close(); ?>
                                            </tbody>
                                        </table>
                                        <!--Table Continue-->
                                        <h2>Tasks completed</h2>
                                        <table class="table table-hover table-responsive">
                                            <thead>
                                            <td>Nmap Scan</td>
                                            <td>Result</td>
                                            <td>Task Status</td>
                                            </thead>
                                            <tbody>
                                            <?php //Task Completed
                                            $currentUser = $_SESSION['loginUser'];
                                            $stmt = Utility::databaseConnection()->prepare("SELECT user_input_command, nmap_log_returned, task_status FROM nmap WHERE username = ? AND task_status                                                                                            ='completed' ORDER BY create_time DESC");
                                            $stmt->bind_param("s", $currentUser);
                                            $stmt->execute();
                                            $stmt->store_result();
                                            $stmt->bind_result($dbNmapCommand, $dbNmapLogReturned, $dbTaskStatus);
                                            while ($stmt->fetch()) {
                                                echo "<tr>  <td>" . htmlentities($dbNmapCommand, ENT_QUOTES) . "</td>
                                                            <td>" . htmlentities($dbNmapLogReturned, ENT_QUOTES) . "</td> 
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





