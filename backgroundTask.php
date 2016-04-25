<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14/04/16
 * Time: 21:45
 */
require('utility.php');

//$username = $_SESSION['loginUser'];
<<<<<<< HEAD
$taskStatus = "completed";
$stmt = Utility::databaseConnection()->prepare("SELECT id, username, user_input_command FROM nmap WHERE nmap_log_returned is null");
=======
$taskPending = "pending";
$taskStatus = "completed";
$stmt = Utility::databaseConnection()->prepare("SELECT id, username, user_input_command FROM nmap WHERE task_status = ?");
$stmt->bind_param("s", $taskPending);
>>>>>>> 10f24b186e460bfca237fff6174f6b6fb5b3c2b4
$stmt->execute();
$stmt->bind_result($dbId, $dbUsername, $dbNmapCommand);
while ($stmt->fetch()) {
    //$dbNmapCommand = escapeshellcmd($dbNmapCommand);
<<<<<<< HEAD
    $result = shell_exec(escapeshellcmd($dbNmapCommand));
    $stmt = Utility::databaseConnection()->prepare("UPDATE nmap SET nmap_log_returned = ?, task_status = ? WHERE id = ?");
    $stmt->bind_param("ssi", $result, $taskStatus, $dbId);
    $stmt->execute();
}
$stmt->close();


$taskStatus = "completed";
$stmt = Utility::databaseConnection()->prepare("SELECT id, username, user_input_command FROM whois WHERE whois_log_returned is null");
$stmt->execute();
$stmt->bind_result($dbId, $dbUsername, $dbwhoisCommand);
while ($stmt->fetch()) {
    //$dbNmapCommand = escapeshellcmd($dbNmapCommand);
    $result = shell_exec(escapeshellcmd($dbwhoisCommand));
    $stmt = Utility::databaseConnection()->prepare("UPDATE whois SET whois_log_returned = ?, task_status = ? WHERE id = ?");
    $stmt->bind_param("ssi", $result, $taskStatus, $dbId);
    $stmt->execute();
=======
    $result = shell_exec($dbNmapCommand);
    $stmt = Utility::databaseConnection()->prepare("UPDATE nmap SET nmap_log_returned = ?, task_status = ? WHERE id = ?");
    $stmt->bind_param("ssi", $result, $taskStatus, $dbId);
    $stmt->execute();

>>>>>>> 10f24b186e460bfca237fff6174f6b6fb5b3c2b4
}
$stmt->close();


<<<<<<< HEAD
























//try {
//    $db = new PDO('mysql:host=localhost;dbname=PenGui;charset=utf8mb4', 'pengui', 'j2FreVA+');
//    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
//
//    $stmt = $db->prepare("SELECT id, username, user_input_command FROM nmap WHERE nmap_log_returned is null");
//    $stmt->execute();
//    $result = $stmt->fetchAll();
//    
//    foreach ($result as $r){
//        echo $r;
//    }
//    
//} catch (Exception $e){
//    print $e."This is stupid";
//    
//}



























=======
>>>>>>> 10f24b186e460bfca237fff6174f6b6fb5b3c2b4
