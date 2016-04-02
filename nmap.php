<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

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
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> Test User <b
                            class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="#"><i class="fa fa-fw fa-user"></i> Profile</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-fw fa-envelope"></i> Inbox</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-fw fa-gear"></i> Settings</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
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
                        <a href="nmap.php" </i> Nmap</a>
                    </li>
                    <li>
                        <a href="tables.html"><i class="fa fa-fw fa-table"></i> Tables</a>
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
                                        <h1>TCP Scan</h1>

                                        <form class="form-horizontal" role="form" method="post" action="nmap.php">
                                            <div class="col-lg-6">
                                                <div class="input-group">
                                                    <input type="text" name="nmap_Scan" class="form-control"
                                                           placeholder="nmap -sV example.com">
                        <span class="input-group-btn">
                                            <button class="btn btn-success" type="submit">Scan</button>
                                            <button class="btn btn-danger" type="reset">Reset</button>
                                          </span>
                                                </div>
                                                <!-- /input-group -->
                                                <br>
                                                <label class="checkbox-inline"><input name="tcp_sync_scan"
                                                                                      type="checkbox" value="-sS">-sS
                                                    TCP SYN Scan</label>
                                                <label class="checkbox-inline"><input name="tcp_connect_scan"
                                                                                      type="checkbox" value="-sT">-sT
                                                    TCP Connect Scan</label>
                                            </div>
                                            <!-- /.col-lg-6 -->
                                        </form>
                                        <?php
                                        include('utility.php');
                                        $scan_Error = "";
                                        $scan = $_POST["nmap_Scan"]; //validate input!!!!!!!!!!
                                        $nmap_string_search = "INSERT INTO nmap (username, user_input_command, nmap_log_returned, nmap_log_simplified, ip_address, task_status)
                                          VALUES ('mojtaba', '$scan', '40', '50', '60', 'pending')";
                                        //most likely way to link this insert statement with the user is get the system time and date and use that as a reference to each unique scan by the same user.
                                        $dbConnection = new Utility();
                                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                            if (!$scan) {
                                                echo "<br><br> <p class=\"text-danger\">Error: Please enter a valid nmap command.</p>";
                                            } else if ($dbConnection->databaseConnection()->query($nmap_string_search) === TRUE) {
                                                echo "<br>" . "record added $scan";
                                            } else {
                                                echo "<br>" . "ERROR: " . $nmap_string_search . "<br>" . $dbConnection->databaseConnection()->error;
                                            }
                                        }
                                        //useless function
                                        function validate_Input($userInputString)
                                        {
                                            $userInputString = trim($userInputString);
                                            $userInputString = stripcslashes($userInputString);
                                            $userInputString = htmlspecialchars($userInputString);
                                            return $userInputString;
                                        }#completed paused pending stopped
                                        ?>
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
    <script src="http://blackrockdigital.github.io/startbootstrap-sb-admin/js/jquery.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="http://blackrockdigital.github.io/startbootstrap-sb-admin/js/bootstrap.min.js"></script>

</body>

</html>
