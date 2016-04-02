<?php
include('utility.php');

class Bcrypt
{
    function hashPassword($passwordParam, $saltParam)
    {
//        $salt = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM); //creating the salt
//        $saltArray = array($salt); //adding the salt to the array
//        $passwordParam = password_hash($passwordParam, PASSWORD_BCRYPT, ['cost' => '14', 'salt' => $saltArray{0}]);
//
//        return $passwordParam . $saltParam;
        
//        $salt = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM); //creating the salt
//        $saltArray = array($salt); //adding the salt to the array
//
//        $utility = new Utility();
//        $stmt = $utility->databaseConnection()->prepare("INSERT INTO personal_details (First_Name, Last_Name, Email, Salt) VALUES (?,?,?,?)");
//        $stmt->bind_param("ssss", $firstName, $lastName, $email, $saltArray{0});
//        $stmt->execute();
//
//        $stmt = $utility->databaseConnection()->prepare("SELECT Salt from personal_details where email LIKE ?");
//        $stmt->bind_param("s", $email);
//        $stmt->execute();
//        $stmt->bind_result($dbSalt);
//        $stmt->fetch();
//        print_r($dbSalt . "\n\n");
//
//
//        $myPassword = "Mojtaba";
//        $hash = password_hash($myPassword, PASSWORD_BCRYPT, ['cost' => '14', 'salt' => $dbSalt]);
//        print_r($hash . "\n\n");
//

    }
    
    function generateSalt(){
        
        
    }

}

