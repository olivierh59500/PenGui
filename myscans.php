<?php
//require('loginAuthentication.php');
require('sessionManagement.php');
if (!isset($_SESSION['loginUser'])) {
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
    <meta name="author" content="Mojtaba Amiri">

    <title>PenGui Admin</title>

    <!-- Bootstrap Core CSS -->
    <link href="http://blackrockdigital.github.io/startbootstrap-sb-admin/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="http://blackrockdigital.github.io/startbootstrap-sb-admin/css/sb-admin.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="http://blackrockdigital.github.io/startbootstrap-sb-admin/font-awesome/css/font-awesome.min.css"
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
                            class="fa fa-user"></i> <?php print_r(htmlspecialchars($_SESSION['loginUser'])) ?><b
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
        <div class="container pull-left">
            <h2>Tasks Pending</h2>
            <table class="table table-hover table-responsive">
                <thead>
                <td>Nmap Scan</td>
                <td>Task Status</td>
                </thead>
                <tbody>
                <tr>
                    <?php
                    //pending tasks
                    error_reporting(-1); ini_set('display_errors', 1);
                    require('utility.php');
                    $currentUser = $_SESSION['loginUser'];
                    $taskStatus = "pending";
                    $stmt = Utility::databaseConnection()->prepare("Select user_input_command, task_status From nmap WHERE task_status = ? and username = ? order by create_time DESC;");
                    $stmt->bind_param("ss", $taskStatus, $currentUser);
                    $stmt->bind_result($dbNmapCommand, $dbTaskStatus);
                    $stmt->execute();
                    while ($stmt->fetch()) {
                        print_r("<tr> <td>" . $dbNmapCommand . "</td> <td>" . $dbTaskStatus . "</td> </tr>");
                    }
                    $stmt->close();
                    ?>
                </tr>
                </tbody>
            </table>

            <div class="container pull-left">
                <h2>Tasks Pending</h2>
                <table class="table table-hover table-responsive">
                    <thead>
                    <td>Nmap Scan</td>
                    <td>Result</td>
                    <td>Task Status</td>
                    </thead>
                    <tbody>
                    <tr>
                        <?php
                        $currentUser = $_SESSION['loginUser'];
                        $taskCompleted = "completed";
                        $stmt = Utility::databaseConnection()->prepare("Select user_input_command, nmap_log_returned, task_status From nmap WHERE username = ? 
                                                                        and task_status = ? order by create_time DESC;");
                        $stmt->bind_param("ss", $currentUser, $taskCompleted);
                        $stmt->execute();
                        $stmt->store_result();
                        $numOfRows = $stmt->num_rows;
                        $result = $stmt->get_result();
                        while($row = $result->fetch_assoc()){
                            foreach ($row as $r){
                                print "$r \n";
                            }
                        }



                        //$stmt->fetch();
//                        print_r($dbNmapLogReturned);
//                        while ($stmt->fetch()) {
//                            print_r("<tr> <td>" . $dbNmapCommand . "</td> <td>" . $dbNmapLogReturned . "</td> <td>" . $dbTaskStatus . "</td> </tr>");
//                        }
//                        $stmt->close();
                        ?>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- jQuery -->
    <script src="http://blackrockdigital.github.io/startbootstrap-sb-admin/js/jquery.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="http://blackrockdigital.github.io/startbootstrap-sb-admin/js/bootstrap.min.js"></script>

</body>
</html>
