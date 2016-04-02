<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 01/04/16
 * Time: 03:43
 */

//include ('passwordHashing.php');
//$hashUserPassword = new Bcrypt();
//$passwordHashed = $hashUserPassword->hashPassword($password);
//print_r($passwordHashed);
//$passwordHash = "Mojtaba";
//$options = [
//    'cost' => '14',
//    'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
//];
//
//$salt = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM);
//$passwordHash = password_hash($passwordHash, PASSWORD_BCRYPT, ["hello"]);
//print_r($passwordHash);


//$salt = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM);
//$myPassword = "Mojtaba";

//$hash = crypt_sha512($myPassword, $salt);
//print_r($hash);
//CRYPT_SHA512();
include('utility.php');
$firstName = "Mo";
$lastName= "amiri";
$email = "mo@amiri.com";

$salt = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM);
$saltArray = array($salt);

$utility = new Utility();
$stmt = $utility->databaseConnection()->prepare("INSERT INTO personal_details (First_Name, Last_Name, Email, Salt) VALUES (?,?,?,?)");
$stmt->bind_param("ssss", $firstName, $lastName, $email, $saltArray{0});
$stmt->execute();

$stmt = $utility->databaseConnection()->prepare("SELECT Salt from personal_details where email LIKE ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($dbSalt);
$stmt->fetch();
print_r($dbSalt."\n\n");


$myPassword = "Mojtaba";
$hash = password_hash($myPassword,PASSWORD_BCRYPT, ['cost' => '14','salt' =>$dbSalt]);
print_r($hash . "\n\n");





#####################################################################################################################
//include('passwordHashing.php');
//$password = "mojtaba";
//
//$hashPassword = new Bcrypt();
//
////$hashedPassword = $hashPassword->hashPassword($password);
//print_r($hashPassword);
###################################################################################################################
//include('utility.php');
//$email = "mojtaba@amiri.com";
//$conn = new Utility();
//if ($stmt = $conn->databaseConnection()->prepare("SELECT Email FROM personal_details WHERE email LIKE ?")) {
//    $stmt->bind_param("s", $email);
//    $stmt->execute();
//    $stmt->bind_result($checkEmail);
//    while ($stmt->fetch()) {
//        $emailExists = "Email Already Exists Please Enter Another Email";
//        print_r($emailExists);
//        exit();
//    }
//    $stmt->close();
//}