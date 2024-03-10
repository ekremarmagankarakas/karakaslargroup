<?php
session_start();
require_once '../classes/dbh.classes.php';
require_once '../classes/requirements.classes.php';

if ($_SESSION["usertype"] == "manager" && isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $id = $_GET['id'];
    $requirement = new Requirement();

    if ($action == 'accept') {
        $requirement->updateRequirementStatus($id, 'Accepted');
    } elseif ($action == 'decline') {
        $requirement->updateRequirementStatus($id, 'Declined');
    }

    header('Location: ../dashboard.php');
    exit();
} else {
    // Handle unauthorized access or invalid request
    header('Location: ../dashboard.php?error=unauthorized');
    exit();
}
