<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 20/04/16
 * Time: 16:23
 */

require('../utility.php');
require('../sessionManagement.php');
?>
<html>
<head></head>
<body>
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
                                            <td>Nmap Scan</td>
                                            <td>Task Status</td>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $currentUser = $_SESSION['loginUser'];
                                            $stmt = Utility::databaseConnection()->prepare("
                                                    SELECT * FROM (SELECT user_input_command, task_status FROM nmap WHERE task_status = 'pending' AND username = ? ORDER BY create_time)
                                                    nmap
                                                     UNION ALL 
                                                    SELECT * FROM (SELECT user_input_command, task_status FROM whois WHERE task_status = 'pending' AND username = ? ORDER BY create_time)
                                                    whois");

                                            $stmt->bind_param("ss", $currentUser, $currentUser);
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
    $stmt = Utility::databaseConnection()->prepare("select user_input_command, nmap_log_returned, task_status, create_time from nmap where username=? and task_status='completed'
union
select user_input_command, whois_log_returned, task_status, create_time from whois where username=? and task_status='completed'
order by create_time desc");
    $stmt->bind_param("ss", $currentUser, $currentUser);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($dbNmapCommand, $dbNmapLogReturned, $dbTaskStatus, $dbCreateTime);
    while ($stmt->fetch()) {
        echo "<tr>  <td>" . htmlentities($dbNmapCommand, ENT_QUOTES) . "</td>
                                                            <td>" . nl2br(htmlentities(trim($dbNmapLogReturned), ENT_QUOTES)) . "</td> 
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
</body>
</html>