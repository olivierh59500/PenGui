<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14/04/16
 * Time: 21:45
 */
require('utility.php');

//$username = $_SESSION['loginUser'];
$taskPending = "pending";
$taskStatus = "completed";
$stmt = Utility::databaseConnection()->prepare("SELECT id, username, user_input_command FROM nmap WHERE task_status = ?");
$stmt->bind_param("s", $taskPending);
$stmt->execute();
$stmt->bind_result($dbId, $dbUsername, $dbNmapCommand);
while ($stmt->fetch()) {
    //$dbNmapCommand = escapeshellcmd($dbNmapCommand);
    $result = shell_exec($dbNmapCommand);
    $stmt = Utility::databaseConnection()->prepare("UPDATE nmap SET nmap_log_returned = ?, task_status = ? WHERE id = ?");
    $stmt->bind_param("ssi", $result, $taskStatus, $dbId);
    $stmt->execute();

}
$stmt->close();


