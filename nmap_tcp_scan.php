<?php
include('utility.php');

$scan =  $_POST["nmap_Scan"];
$userIpAddress = $_SERVER['REMOTE_ADDR'];
$sessionUser = $_SESSION['loginUser'];
$taskStatus = "pending";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(empty($scan)){
        Utility::alert("Please enter a command");
    } else {
        $stmt = Utility::databaseConnection()->prepare("INSERT INTO nmap (username, user_input_command, ip_address, task_status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $sessionUser, $scan, $userIpAddress, $taskStatus);
        $stmt->execute();
        $stmt->close();
        $scan="";
        Utility::alert("Scan was successful, head to My Scans to see the progress of your scans ");
    }
}

