<?php

/*
 * @return mysqli
 * @internal param $connection
 */

class Utility
{
    public static function checkForExistingEmail($email)
    {
        if ($stmt = Utility::databaseConnection()->prepare("SELECT Email FROM personal_details WHERE email = ?")) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->bind_result($checkEmail);
            while ($stmt->fetch()) {
                $emailExists = "Email already exists please enter another email";
                Utility::alert($emailExists);
                exit();
            }
            $stmt->close();
        }
    }

    public static function databaseConnection()
    {

        $host="localhost";
        $port=3306;
        $socket="";
        $user="root";
        $password="j2FreVA+";
        $dbname="PenGui";

        $connection = new mysqli($host, $user, $password, $dbname, $port, $socket)
        or die ('Could not connect to the database server' . mysqli_connect_error());
        return $connection;

    }

//    function readFromDatabase()
//    {
//        $sql = "Select user_input_command, task_status From nmap";
//        $result = databaseConnection()->query($sql);
//
//        if ($result->num_rows > 0) {
//            while ($row = $result->fetch_assoc()) {
//                $nmapCommand = $row["user_input_command"];
//                //$nmapTaskStatus = $row["task_status"];
//                //Array($dbResult);
//                printf($nmapCommand . "\n");
//                $nmapTextFile = ">> nmap.txt";
//                $test = exec($nmapCommand . $nmapTextFile, $commandReturned);
//                printf($commandReturned);
//            }
//        } else {
//            echo "0 result";
//        }
//        databaseConnection()->close();
//    }

    public static function alert($message)
    {
        print_r('<script type="text/javascript">alert("' . $message . '")</script>');
    }

    public static function authenticateEmail($email)
    {
        if ($stmt = Utility::databaseConnection()->prepare("SELECT Email FROM personal_details WHERE email = ?")) {
            $stmt->bind_param("s", $email);
            if ($stmt->execute()) {
                $stmt->bind_result($authenticateEmail);
                $stmt->fetch();
                if ($stmt->num_rows > 0) {
                    Utility::alert("Email or Password doesn't match.");
                } else {
                    Utility::alert("Email found");
                }
                $stmt->close();
            }
        }
    }//Email not found function

    public static function validateInput($userInputString)
    {
        $userInputString = trim($userInputString);
        $userInputString = stripcslashes($userInputString);
        $userInputString = htmlspecialchars($userInputString);
        return $userInputString;
    }
}

