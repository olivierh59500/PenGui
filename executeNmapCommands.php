<?php
/**
 * Created by PhpStorm.
 * User: mojtaba
 * Date: 11/04/16
 * Time: 14:12
 */
//string shell_exec ( string $cmd )
//string escapeshellcmd ( string $command )
//string escapeshellarg ( string $arg )
//escapeshellcmd() should be used on the whole command string,
//and it still allows the attacker to pass arbitrary number of
//arguments. For escaping a single argument escapeshellarg() should be used instead.

//shell_exec returns all of the output stream as a string. exec returns the last line of the output.

//void passthru ( string $command [, int &$return_var ] )
//The passthru() function is similar to the exec() function in that it executes a command. This function should be used in place of exec() or system() when the output from the Unix command is binary data which needs to be passed directly back to the browser. A common use for this is to execute something like the pbmplus utilities that can output an image stream directly. By setting the Content-type to image/gif and then calling a pbmplus program to output a gif, you can create PHP scripts that output images directly.
//Parameters:
//command
//The command that will be executed.
//return_var
//If the return_var argument is present, the return status of the Unix command will be placed here.

//passthru("cat ~/nmap.log", $result);
//print $result;

Class Nmap
{
    public function nmapExecution()
    {
        require('sessionManagement.php');
        require('utility.php');
        $username = $_SESSION['loginUser'];
        $taskStatus = "completed";

        $stmt = Utility::databaseConnection()->prepare("SELECT id, username, user_input_command FROM nmap WHERE username = ? AND task_status = 'pending'");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($dbId, $dbUsername, $dbNmapCommand);
        while ($stmt->fetch()) {
            if ($username === $dbUsername) {
                $dbNmapCommand = escapeshellcmd($dbNmapCommand);
                $result = shell_exec($dbNmapCommand);
                //store $r in Database
                $stmt = Utility::databaseConnection()->prepare("UPDATE nmap SET nmap_log_returned = ?, task_status = ? WHERE id = ?");
                $stmt->bind_param("ssi", $result, $taskStatus, $dbId);
                $stmt->execute();
            }
        }
        $stmt->close();
    }
}






