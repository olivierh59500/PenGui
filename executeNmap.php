<?php


//class executeNmap
//{
    function nmapExecution()
    {
        $username = $_SESSION['loginUser'];
        $taskStatus = "completed";
        $stmt = Utility::databaseConnection()->prepare("SELECT id, username, user_input_command FROM nmap WHERE username = ? AND task_status = 'pending'");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($dbId, $dbUsername, $dbNmapCommand);
        while ($stmt->fetch()) {
            if ($username === $dbUsername) {
                $dbNmapCommand = escapeshellcmd($dbNmapCommand);
//                $nameSV = "nmap -sV ";
//                $start = "nohup";
//                $background = "&";
                $result = shell_exec($dbNmapCommand);
                $stmt = Utility::databaseConnection()->prepare("UPDATE nmap SET nmap_log_returned = ?, task_status = ? WHERE id = ?");
                $stmt->bind_param("ssi", $result, $taskStatus, $dbId);
                $stmt->execute();
            }
        }
        $stmt->close();
    }

//}