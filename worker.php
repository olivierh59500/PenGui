<?php
/**
 * Created by PhpStorm.
 * User: mo
 * Date: 23/04/16
 * Time: 20:34
 */

//require('sessionManagement.php');
require('utility.php');

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();
$channel->queue_declare('task_queue', false, true, false, false);

echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

$callback = function($msg) {
    echo " [x] Received ", $msg->body, "\n"; //$msg->body: converts message object to a string
    if(strpos($msg->body, "nmap") !== false) {
        $nmapResult = shell_exec(escapeshellcmd($msg->body));
        storeNmapResult($msg->body, $nmapResult);
    } else if (strpos($msg->body, "whois") !== false){
        $whoisResult = shell_exec(escapeshellcmd($msg->body));
        storeWhoisResult($msg->body, $whoisResult);
    } else if (strpos($msg->body, "testssl") !== false) {
        $testSSResult = shell_exec($msg->body);
        storeTestSSLResult($msg->body, $testSSResult);
    }

    echo " [x] Done", "\n";
    $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
};
//1 = prefetch count
$channel->basic_qos(null, 1, null);
$channel->basic_consume('task_queue', '', false, false, false, false, $callback);

while(count($channel->callbacks)) {
    $channel->wait(); //wait for incoming messages/tasks
}

$channel->close();
$connection->close();

function storeTestSSLResult($testSSLType, $testSSLResult) {
    $taskStatus = "completed";
    $stmt = Utility::databaseConnection()->prepare("SELECT id, user_input_command FROM sslChecker WHERE sslChecker_log_returned is null");
    $stmt->execute();
    $stmt->bind_result($dbId, $dbtestSSLCommand);
    while ($stmt->fetch()) {
        if ($dbtestSSLCommand === $testSSLType) {
            $stmt = Utility::databaseConnection()->prepare("UPDATE sslChecker SET sslChecker_log_returned = ?, task_status = ? WHERE id = ?");
            $stmt->bind_param("ssi", $testSSLResult, $taskStatus, $dbId);
            $stmt->execute();
            //function here
            simplifySSLResult($testSSLResult, $dbId);
        }
    }
    $stmt->close();
}
function simplifySSLResult($testSSLResult, $id) {
    $testSSLResult = preg_grep("/(not vulnerable|VULNERABLE)/", explode("\n", $testSSLResult));
    foreach($testSSLResult as $value){
        $testSSLResultSimplified = preg_replace("#\e.*?m#", "", $value);
        $sslResult = preg_split("/\(timed out\)/", $testSSLResultSimplified);
    }
    $stmt = Utility::databaseConnection()->prepare("UPDATE sslChecker SET sslChecker_log_simplified = ? WHERE id = ?");
    $stmt->bind_param("si", $sslResult[0], $id);
    $stmt->execute();
    $stmt->close();
}

function storeNmapResult($nmapScanType, $nmapResult) {
    $taskStatus = "completed";
    $stmt = Utility::databaseConnection()->prepare("SELECT id, user_input_command FROM nmap WHERE nmap_log_returned is null");
    $stmt->execute();
    $stmt->bind_result($dbId, $dbNmapCommand);
    while ($stmt->fetch()) {
        if ($dbNmapCommand === $nmapScanType) {
            $stmt = Utility::databaseConnection()->prepare("UPDATE nmap SET nmap_log_returned = ?, task_status = ? WHERE id = ?");
            $stmt->bind_param("ssi", $nmapResult, $taskStatus, $dbId);
            $stmt->execute();
        }
    }
    $stmt->close();
}

function storeWhoisResult($whoisScanCommand, $whoisResult) {
    $taskStatus = "completed";
    $stmt = Utility::databaseConnection()->prepare("SELECT id, user_input_command FROM whois WHERE whois_log_returned is null");
    $stmt->execute();
    $stmt->bind_result($dbId, $dbWhoisCommand);
    while ($stmt->fetch()) {
        if ($dbWhoisCommand === $whoisScanCommand)
        $stmt = Utility::databaseConnection()->prepare("UPDATE whois SET whois_log_returned = ?, task_status = ? WHERE id = ?");
        $stmt->bind_param("ssi", $whoisResult, $taskStatus, $dbId);
        $stmt->execute();
    }
    $stmt->close();
}

function simplifyNmapLog() {

}