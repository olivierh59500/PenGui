<?php
require('utility.php');
require('sessionManagement.php');

$emailAuthentication = $_POST['email'];
$passwordAuthentication = $_POST['password'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($emailAuthentication)) {
        Utility::alert(htmlentities("Email is required", ENT_QUOTES));
        exit();
    }
    if (empty($passwordAuthentication)) {
        Utility::alert(htmlentities("Password is required", ENT_QUOTES));
        exit();
    }

    $stmt = Utility::databaseConnection()->prepare("SELECT Email, Password FROM personal_details WHERE Email = ?");
    $stmt->bind_param("s", $emailAuthentication);
    $stmt->execute();
    $stmt->bind_result($dbEmail, $dbPassword);
    $stmt->fetch();
    if ($dbEmail == $emailAuthentication) {
        if (password_verify($passwordAuthentication, $dbPassword)) { //Timing attack safe string comparison hash_equals(Known_Hash, User_Supplied)
            Session::manageSession($emailAuthentication);
            header("location: index.php");
        } else {
            Utility::alert(htmlentities("Email or Password is incorrect. Please try again.,", ENT_QUOTES));
            exit();
        }
    } else {
        Utility::alert(htmlentities("Email or Password is incorrect. Please try again.", ENT_QUOTES));
        exit();
    }
    $stmt->close();
}//end of post method

//NewAboutYoursAPIHelp
//904 bytes, HTML + PHP     Soft wrap Raw text Duplicate

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