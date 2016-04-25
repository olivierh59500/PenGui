<?php
header("Cache-Control: no-store, no-cache, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta http-equiv="Cache-control" content="no-cache">

    <title>PenGui</title>
    <!-- Bootstrap Core CSS -->
    <link href="https://blackrockdigital.github.io/startbootstrap-simple-sidebar/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="https://blackrockdigital.github.io/startbootstrap-simple-sidebar/css/simple-sidebar.css" rel="stylesheet">
</head>
<body>
    <div id="wrapper">
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <li class="sidebar-brand">
                    <a href="#">
                        Start PenGui
                    </a>
                </li>
                <li>
                    <a href="/nmap">Nmap</a>
                </li>
            </ul>
        </div>
        <!-- /#sidebar-wrapper -->
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1>Nmap</h1>

                        <form class="form-horizontal" role="form" method="post" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]);?>">
												<div class="col-lg-6">
                          <div class="input-group">
                              <input type="text" name="nmap_Scan" class="form-control" placeholder="nmap -sV example.com">
                              <span class="input-group-btn">
                                  <button class="btn btn-success" type="submit">Scan</button>
                                  <button class="btn btn-danger" type="reset">Cancel Scan</button>
                              </span>
                          </div><!-- /input-group -->
                        </div><!-- /.col-lg-6 -->
                      </form>

                        <?php
                        include('utility.php');
                        $scan_Error= "";
                        $scan = $_POST["nmap_Scan"]; //validate input?
                        $nmap_string_search = "INSERT INTO nmap (username, user_input_command, nmap_log_returned, nmap_log_simplified, ip_address, task_status)
                                               VALUES ('mojtaba', '$scan', '40', '50', '60', 'pending')";
                        //most likely way to link this insert statement with the user is get the system time and date and use that as a reference to each unique scan by the same user.
                        $dbConnection = new Test();
                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            if (!$scan) {
                                echo "<p class=\"text-danger\">Error: Please enter a valid nmap command.</p>";
                            } else if ( $dbConnection ->databaseConnection()->query($nmap_string_search) === TRUE) {
                                echo "record added $scan";
                            } else {
                                echo "ERROR: " . $nmap_string_search . "<br>" . $dbConnection ->databaseConnection()->error;
                            }
                        }


                        //useless function
                        function validate_Input($userInputString){
                          $userInputString = trim($userInputString);
                          $userInputString = stripcslashes($userInputString);
                          $userInputString = htmlspecialchars($userInputString);
                          return $userInputString;
                        }#completed paused pending stopped
                        ?>
                        <br><br><p>Nmap is a tool used for various network....................</p>
                        <p>Make sure to keep all page content within the <code>#page-content-wrapper</code>.</p>
                        <a href="#menu-toggle" class="btn btn-default" id="menu-toggle">Toggle Menu</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- /#page-content-wrapper -->
    </div>
    <!-- /#wrapper -->
    <!-- jQuery -->
    <script src="https://blackrockdigital.github.io/startbootstrap-simple-sidebar/js/jquery.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="https://blackrockdigital.github.io/startbootstrap-simple-sidebar/js/bootstrap.min.js"></script>
    <!-- Menu Toggle Script -->
    <script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
    </script>
</body>
</html>
