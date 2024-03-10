<?php
session_start();

if (isset($_POST["submit"])) {
    $itemName = $_POST["itemName"];
    $price = $_POST["price"];
    $explanation = $_POST["explanation"];
    $userId = $_SESSION["userid"]; 

    // Include database connection and classes
    include "../classes/dbh.classes.php";
    include "../classes/requirements.classes.php";

    // Instantiate Requirement class and submit
    $requirement = new Requirement();
    $requirement->submitRequirement($userId, $itemName, $price, $explanation);

    // Redirect back with a success message
    header("location: ../dashboard.php?error=none");
} else {
    // Redirect or throw an error if the user is not allowed to submit
    header("location: ../dashboard.php?error=unauthorized");
    exit();
}
