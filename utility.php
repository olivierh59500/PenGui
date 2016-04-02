<?php

/*
 * @return mysqli
 * @internal param $connection
 */

class Utility
{
    function databaseConnection()
    {
        $host = "localhost";
        $port = 3306;
        $socket = "";
        $user = "root";
        $password = "j2FreVA+";
        $dbname = "PenGui";
        $connection = new mysqli($host, $user, $password, $dbname, $port, $socket)
        or die("connection failed: " . $connection->error);
        return $connection;
    }


    function readFromDatabase()
    {
        print "reading from database\n\n";
        $sql = "Select user_input_command, task_status From nmap";
        $result = databaseConnection()->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $nmapCommand = $row["user_input_command"];
                //$nmapTaskStatus = $row["task_status"];
                //Array($dbResult);
                printf($nmapCommand . "\n");
                $nmapTextFile = ">> nmap.txt";
                $test = exec($nmapCommand . $nmapTextFile, $commandReturned);
                printf($commandReturned);

            }
        } else {
            echo "0 result";
        }
//    $resultArray = null; $resultArray= array($result);
//    foreach($resultArray as $value) {
//        print_r($value);
//    }
//    exec($result);
        databaseConnection()->close();
    }

    function alert($message)
    {
        print_r('<script type="text/javascript">alert("' . $message . '")</script>');
    }

    function checkForExistingEmail($email)
    {
        $utility = new Utility();
        if ($stmt = $utility->databaseConnection()->prepare("SELECT Email FROM personal_details WHERE email LIKE ?")) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->bind_result($checkEmail);
            while ($stmt->fetch()) {
                $emailExists = "Email already exists please enter another email";
                $utility->alert($emailExists);
                exit();
            }
            $stmt->close();
        }
    }

    function authenticateEmail($email){
        $utility = new Utility();
        if ($stmt = $utility->databaseConnection()->prepare("SELECT Email FROM personal_details WHERE email LIKE ?")) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->bind_result($authenticateEmail);
            while($stmt->fetch()) {
                if ($email == $authenticateEmail) {
                    $utility->alert("Email or Password was incorrect. Please try again.");
                }else {
                    $utility->alert("SHIT");
                }
            }
            $stmt->close();
        }
    }//Email not found

    function validateInput($userInputString)
    {
        $userInputString = trim($userInputString);
        $userInputString = stripcslashes($userInputString);
        $userInputString = htmlspecialchars($userInputString);
        return $userInputString;
    }
}