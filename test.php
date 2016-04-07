<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 01/04/16
 * Time: 03:43
 */


$mysqli = new mysqli("127.0.0.1", "user", "password", "world");

if($mysqli->connect_error)
{
    die("$mysqli->connect_errno: $mysqli->connect_error");
}

$query = "SELECT Name, Population, Continent FROM Country WHERE Continent=? ORDER BY Name LIMIT 1";


$stmt->prepare($query))
$stmt->bind_param("s", $continent);
$continent_array = array('Europe', 'Africa', 'Asia', 'North America');
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_array(MYSQLI_NUM)) {
    foreach ($row as $r) {
        print "$r ";
    }
    print "\n";


$stmt->close();
$mysqli->close();




































                        include ('utility.php');
                        $currentUser = $_SESSION['loginUser'];
        print_r($currentUser);
                        $taskCompleted = "completed";
                        $stmt = Utility::databaseConnection()->prepare("Select user_input_command, nmap_log_returned, task_status From nmap WHERE username = ? 
                                                                        and task_status = ? order by create_time DESC;");
                        $stmt->bind_param("ss", $currentUser, $taskCompleted);
                        $stmt->bind_result($dbNmapCommand, $dbNmapLogReturned, $dbTaskStatus);
                        $stmt->execute();
                        $stmt->fetch();
                        print_r($dbNmapLogReturned);
//                        while ($stmt->fetch()) {
//                            print_r("<tr> <td>" . $dbNmapCommand . "</td> <td>" . $dbNmapLogReturned . "</td> <td>" . $dbTaskStatus . "</td> </tr>");
//                        }
                        $stmt->close();
                        




















//include ('utility.php');

//$email = "mojtaba_amiri@hotmail.co";
//authenticateEmail($email);
//
//function authenticateEmail($email)
//{
//    $utility = new Utility();
//    if ($stmt = $utility->databaseConnection()->prepare("SELECT Email FROM personal_details WHERE email LIKE ?")) {
//        $stmt->bind_param("s", $email);
//        if($stmt->execute()) {
//            $stmt->bind_result($authenticateEmail);
//            $stmt->fetch();
//            if ($stmt->num_rows == 0){
//                print_r("Email Don't exist");
//            }
//            $stmt->close();
//            $utility->databaseConnection()->close();
//        }
//    }//Email not found function
//}

//three variables from the database Email, Password, Salt
//user input email and password
//get the salt and the string password and hash it
//compare the hash values if they are identical allow user access if not error out
//
//$email = "mojtaba_amiri@hotmail.com";
//$password = "amirim1993220";
//$utility = new Utility();
//$stmt = $utility->databaseConnection()->prepare("SELECT Email, Password, Salt FROM personal_details WHERE Email like ?");
//$stmt->bind_param("s", $email);
//$stmt->execute();
//$stmt->bind_result($dbEmail, $dbPassword, $dbSalt);
//$stmt->fetch();
//if ($dbEmail == $email){
//        $password = password_hash($password, PASSWORD_BCRYPT, ['cost' => '14', 'salt' => $dbSalt]); //cost=14 ==> 0.5 second delay
//        if(hash_equals($dbPassword, $password)){ //Timing attack safe string comparison hash_equals(Known_Hash, User_Supplied)
//            print_r('<script> window.location.replace("index.php")</script>');
//
//        } else {
//            $utility->alert("Email or Password is incorrect. Please try again.");
//            exit();
//        }
//    } else {
//        print_r("Email or Password is incorrect. Please try again.");
//        exit();
//    }
//$stmt->close();
//$utility->databaseConnection()->close();

################################################################################################################
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
#####################################################################################################################
//include('utility.php');
//$firstName = "Mo";
//$lastName= "amiri";
//$email = "mo@amiri.com";
//
//$salt = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM);
//$saltArray = array($salt);
//
//$utility = new Utility();
//$stmt = $utility->databaseConnection()->prepare("INSERT INTO personal_details (First_Name, Last_Name, Email, Salt) VALUES (?,?,?,?)");
//$stmt->bind_param("ssss", $firstName, $lastName, $email, $saltArray{0});
//$stmt->execute();
//
//$stmt = $utility->databaseConnection()->prepare("SELECT Salt from personal_details where email LIKE ?");
//$stmt->bind_param("s", $email);
//$stmt->execute();
//$stmt->bind_result($dbSalt);
//$stmt->fetch();
//print_r($dbSalt."\n\n");
//
//
//$myPassword = "Mojtaba";
//$hash = password_hash($myPassword,PASSWORD_BCRYPT, ['cost' => '14','salt' =>$dbSalt]);
//print_r($hash . "\n\n");

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