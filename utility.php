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

    public static function alert($message)
    {
        echo '<script type="text/javascript">alert("' . htmlentities($message, ENT_QUOTES) . '")</script>';
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

