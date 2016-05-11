<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 09/05/16
 * Time: 20:41
 */

require("utility.php");
require("new_task.php");

sleep(120);
processUnprocessedTasks();

//run every 12 hours
function processUnprocessedTasks() {
    $taskStatus = "processing";
    $stmt = Utility::databaseConnection()->prepare("
    SELECT user_input_command, create_time FROM nmap WHERE task_status = ?
    UNION 
    SELECT user_input_command, create_time FROM whois WHERE task_status = ?
    UNION 
    SELECT user_input_command, create_time FROM sslChecker WHERE task_status = ?
    UNION 
    SELECT user_input_command, create_time FROM nikto WHERE task_status = ?
    UNION 
    SELECT user_input_command, create_time FROM dnsScan WHERE task_status = ?
    ORDER BY create_time DESC;");
    $stmt->bind_param("sssss", $taskStatus, $taskStatus, $taskStatus, $taskStatus, $taskStatus);
    $stmt->execute();
    $stmt->bind_result($userInputCommand, $createTime);
    while($stmt->fetch()){
        $startTask = new Task();
        $startTask->newTask($userInputCommand);
    }

    $stmt->close();
}