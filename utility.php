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
                htmlspecialchars($emailExists = "Email already exists please enter another email", ENT_QUOTES);
                return $emailExists;
                //Utility::alert($emailExists);
            }
            $stmt->close();
        }
    }

    public static function databaseConnection()
    {
        $host="localhost";
        $port=3306;
        $socket="";
        $user="pengui";
        $password="j2FreVA+";
        $dbname="PenGui";
        $connection = new mysqli($host, $user, $password, $dbname, $port, $socket)
        or die ('Could not connect to the database server' . mysqli_connect_error());
        return $connection;
    }

    public static function alert($message) //temp  function just for dev, need proper error displaying
    {
        echo '<script type="text/javascript">alert("' . htmlentities($message, ENT_QUOTES) . '")</script>';
    }

    public static function validateInput($userInputString)
    {
        $userInputString = trim($userInputString);
        $userInputString = stripcslashes($userInputString);
        $userInputString = htmlspecialchars($userInputString);
        return $userInputString;
    }
}

