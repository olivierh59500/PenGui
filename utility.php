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
                htmlentities($emailExists = "Email already exists please enter another email", ENT_QUOTES);
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
        $user="pengui";
        $password="j2FreVA+";
        $dbname="PenGui";
        $connection = new mysqli($host, $user, $password, $dbname, $port, $socket)
        or die ('Could not connect to the database server' . mysqli_connect_error());
        return $connection;
    }

<<<<<<< HEAD
    public static function alert($message) //temp  function just for dev, need proper error displaying 
=======
    public static function alert($message)
>>>>>>> 10f24b186e460bfca237fff6174f6b6fb5b3c2b4
    {
        echo '<script type="text/javascript">alert("' . htmlentities($message, ENT_QUOTES) . '")</script>';
    }

<<<<<<< HEAD
=======
//    public static function authenticateEmail($email)
//    {
//        if ($stmt = Utility::databaseConnection()->prepare("SELECT Email FROM personal_details WHERE email = ?")) {
//            $stmt->bind_param("s", $email);
//            if ($stmt->execute()) {
//                $stmt->bind_result($authenticateEmail);
//                $stmt->fetch();
//                if ($stmt->num_rows > 0) {
//                    Utility::alert("Email or Password doesn't match.");
//                } else {
//                    Utility::alert("Email found");
//                }
//                $stmt->close();
//            }
//        }
//    }//Email not found function

>>>>>>> 10f24b186e460bfca237fff6174f6b6fb5b3c2b4
    public static function validateInput($userInputString)
    {
        $userInputString = trim($userInputString);
        $userInputString = stripcslashes($userInputString);
        $userInputString = htmlspecialchars($userInputString);
        return $userInputString;
    }
}

