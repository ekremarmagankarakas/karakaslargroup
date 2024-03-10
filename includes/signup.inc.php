<?php

if(isset($_POST["submit"]))
{

    // Grabbing the data
    $uid = $_POST["uid"];
    $pwd = $_POST["pwd"];
    $pwdRepeat = $_POST["pwdrepeat"];
    $email = $_POST["email"];
    $userType = $_POST["usertype"];

    // Instantiate SignupContr class
    include "../classes/dbh.classes.php";
    include "../classes/signup.classes.php";
    include "../classes/signup-contr.classes.php";
    $signup = new SignupContr($uid, $pwd, $pwdRepeat, $email, $userType);

    // Running error handlers and user signup
    $signup->signupUser();

    // Going to back to front page
    header("location: ../login.php?error=none");
}