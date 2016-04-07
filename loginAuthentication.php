<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 03/04/16
 * Time: 12:45
 */

require('utility.php');
require('sessionManagement.php');

$emailAuthentication = $_POST['email'];
$passwordAuthentication = $_POST['password'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($emailAuthentication)) {
        Utility::alert("Email is required");
        exit();
    }
    if (empty($passwordAuthentication)) {
        Utility::alert("Password is required");
        exit();
    }

    $stmt = Utility::databaseConnection()->prepare("SELECT Email, Password FROM personal_details WHERE Email = ?");
    $stmt->bind_param("s", $emailAuthentication);
    $stmt->execute();
    $stmt->bind_result($dbEmail, $dbPassword);
    $stmt->fetch();
    if ($dbEmail == $emailAuthentication) {
        if (password_verify($passwordAuthentication, $dbPassword)) { //Timing attack safe string comparison hash_equals(Known_Hash, User_Supplied)
//            $_SESSION['loginUser'] = $emailAuthentication;
//            header("location: index.php");
            Session::manageSession($emailAuthentication);
            header("location: index.php");
        } else {
            Utility::alert("Email or Password is incorrect. Please try again.");
            exit();
        }
    } else {
        Utility::alert("Email or Password is incorrect. Please try again.");
        exit();
    }
    $stmt->close();
}//end of post method

//
//
//NewAboutYoursAPIHelp
//904 bytes, HTML + PHP     Soft wrap Raw text Duplicate
// 1
// 2
// 3
// 4
// 5
// 6
// 7
// 8
// 9
//10
//11
//12
//13
//14
//15
//16
//17
//18
//19
//20
//21
//22
//23
//24
//25
//26
//27
//28
//29
//30
//31
//32
//33
//34
//35
//36
//37
//<?php
//# Start with an array that is empty. No problems inside means no errors has occurred.  Does that make sense?
//$problems = [];
//
//
//
//if (mb_strlen($password) < 3) {
//    $problems[] = 'Your password is too short. Please think of a longer password.';
//}
//
//if (mb_strlen($name) < 1) {
//    $problems[] = 'Oh. You need to type in your name. You can not leave this field blank. Try again!';
//}
//
//if ($secure_math_question !== 6) {
//    $problems[] = 'That is not the answer. What is 3 + 3?';
//}
//
//if ($dog === 'Ronald') {
//    $problems[] = 'You guessed the name of my pet dog wrong. You need to guess correctly before you are allowed to move to the next step';
//}
//
//
//
//# Run this code, if no problems happened.
//if ($problems === []) {
//
//    do_cool_things();
//
//}
//else {
//
//    foreach ($problems as $error_message): ?>
<!--        <div>--><?//= $error_message ?><!--</div>-->
<!--        --><?php
//    endforeach;
//}
//Pasted a minute ago — Expires in 30 days