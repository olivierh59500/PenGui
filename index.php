<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
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

                        <form class="form-horizontal" role="form" method="post" action="index.php"> <?php #htmlspecialchars($_SERVER["PHP_SELF"]);?>
												<div class="col-lg-6">
                          <div class="input-group">
                              <input type="text" name="nmap_Scan" class="form-control" placeholder="nmap -sV example.com">
                              <span class="input-group-btn">
                                  <button class="btn btn-success" type="submit">Scan</button>
                                  <button class="btn btn-danger" type="reset">Cancle Scan</button>
                              </span>
                          </div><!-- /input-group -->
                        </div><!-- /.col-lg-6 -->
                      </form>
												<br><br><p>Nmap is a tool used for various network....................</p>
                        <?php
                        $host="localhost"; $port=3306; $socket=""; $user="rootOfAllRoots";
                        $password="ILoveJesus"; $dbname="PenGui";

                        $connection = new mysqli($host, $user, $password, $dbname, $port, $socket)
                          or die("connection failed: " . mysqli_connect_error());

                        $scan_Error= "";
                        $scan = "";
                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                          if (!$_POST["nmap_Scan"]) {
                            $scan_Error = "Please enter a valid nmap command";
                          } else {
                            $scan = validate_Input($_POST["nmap_Scan"]);
                          }
                        }//end of $_SERVER POST REQUEST_METHOD
                        //nmap user input scan will be stored in PenGui database
                        $nmap_User_Input_Scan = (isset($_POST["nmap_Scan"]));
                        $nmap_Insert_Query = "INSERT INTO nmap VALUES (1, $nmap_User_Input_Scan, NULL, NULL, 192.168.0.9)";

                        if (mysqli_connect_error()) {
                          mysqli_query($connection, $nmap_Insert_Query);
                          echo "new entry added";
                        } else {
                          echo "Error:" . mysqli_connect_errno() . mysqli_connect_error();
                        }

                        function validate_Input($userInputString){
                          $userInputString = trim($userInputString);
                          $userInputString = stripcslashes($userInputString);
                          $userInputString = htmlspecialchars($userInputString);
                          return $userInputString;
                        }
                        ?>

												<p>blah blah <code>#page-content-wrapper</code>.</p>
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
